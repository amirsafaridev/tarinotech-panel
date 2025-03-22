<?php

namespace Modules\Survey\app\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Modules\Survey\app\Enums\Database\QuestionTypeEnum;
use Modules\Survey\app\Models\Survey;
use Modules\Survey\app\Models\SurveyAnswer;
use Modules\Survey\app\Models\SurveyAnswerOption;
use Modules\Survey\app\Models\SurveyResponse;

class SurveyPublicController extends Controller
{
    public function show(string $accessToken): View|RedirectResponse
    {
        $survey = $this->findActiveSurvey($accessToken);

        if (! $survey) {
            abort(404, 'نظرسنجی مورد نظر یافت نشد یا در دسترس نیست.');
        }

        if ($survey->requires_auth && ! Auth::check()) {
            return redirect()->route('login', ['redirect' => url()->full()]);
        }

        if ($survey->requires_auth && $this->hasUserSubmitted($survey)) {
            return redirect()->route('survey.public.thankyou', $survey->access_token)
                ->with('message', 'شما قبلا در این نظرسنجی شرکت کرده‌اید.');
        }

        $this->loadSurveyWithQuestions($survey);
        $this->initiateSurveySession();

        // Record start time
        session(['survey_start_time' => now()]);

        return view('survey::web.show', compact('survey'));
    }

    public function submit(Request $request, string $accessToken): RedirectResponse
    {
        $survey = $this->findActiveSurvey($accessToken);

        if (! $survey) {
            abort(404, 'نظرسنجی مورد نظر یافت نشد یا در دسترس نیست.');
        }

        $this->validateSurveySubmission($request, $survey);

        try {
            DB::beginTransaction();

            $startTime = session('survey_start_time');

            $response = $this->createSurveyResponse($request, $survey, $startTime);
            $this->processAnswers($request, $response, $survey);

            DB::commit();

            // Clear survey session data
            session()->forget(['survey_session_id', 'survey_start_time']);

            return redirect()->route('survey.public.thankyou', $survey->access_token);
        } catch (Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'خطا در ثبت پاسخ‌ها: '.$e->getMessage());
        }
    }

    public function thankYou(string $accessToken): View
    {
        $survey = Survey::where('access_token', $accessToken)->first();

        if (! $survey) {
            abort(404);
        }

        return view('survey::web.thank', compact('survey'));
    }

    private function findActiveSurvey(string $accessToken): ?Survey
    {
        return Survey::where('access_token', $accessToken)
            ->where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', now());
            })
            ->first();
    }

    private function hasUserSubmitted(Survey $survey): bool
    {
        return SurveyResponse::where('survey_id', $survey->id)
            ->where('user_id', Auth::id())
            ->exists();
    }

    private function loadSurveyWithQuestions(Survey $survey): void
    {
        $survey->load([
            'questions' => function ($query) {
                $query->orderBy('order');
            },
            'questions.options' => function ($query) {
                $query->orderBy('order');
            },
        ]);
    }

    private function initiateSurveySession(): void
    {
        $sessionId = Str::random(40);
        session(['survey_session_id' => $sessionId]);
    }

    private function createSurveyResponse(Request $request, Survey $survey, $startTime): SurveyResponse
    {
        $response = new SurveyResponse([
            'survey_id' => $survey->id,
            'user_id' => Auth::check() ? Auth::id() : null,
            'respondent_email' => $request->input('email'),
            'respondent_name' => $request->input('name'),
            'ip_address' => $request->ip(),
            'session_id' => session('survey_session_id'),
            'started_at' => $startTime,
            'completed_at' => now(),
        ]);

        $response->save();

        return $response;
    }

    private function validateSurveySubmission(Request $request, Survey $survey): void
    {
        $rules = [];
        $messages = [];

        if (! Auth::check() && ! $survey->requires_auth) {
            $rules['name'] = 'nullable|string|max:255';
            $rules['email'] = 'nullable|email|max:255';
        }

        foreach ($survey->questions as $question) {
            if ($question->is_required) {
                $questionId = 'question_'.$question->id;

                switch ($question->question_type) {
                    case QuestionTypeEnum::Single:
                        $rules[$questionId] = 'required';
                        $messages[$questionId.'.required'] = 'لطفاً به سوال "'.$question->question_text.'" پاسخ دهید.';
                        break;

                    case QuestionTypeEnum::Multiple:
                        $rules[$questionId] = 'required|array|min:1';
                        $messages[$questionId.'.required'] = 'لطفاً به سوال "'.$question->question_text.'" پاسخ دهید.';
                        $messages[$questionId.'.min'] = 'لطفاً حداقل یک گزینه برای سوال "'.$question->question_text.'" انتخاب کنید.';
                        break;

                    case QuestionTypeEnum::Text:
                        $rules[$questionId] = 'required|string';
                        $messages[$questionId.'.required'] = 'لطفاً به سوال "'.$question->question_text.'" پاسخ دهید.';
                        break;
                }
            }
        }

        $request->validate($rules, $messages);
    }

    private function processAnswers(Request $request, SurveyResponse $response, Survey $survey): void
    {
        foreach ($survey->questions as $question) {
            $questionId = 'question_'.$question->id;

            if (! $request->has($questionId)) {
                continue;
            }

            $answer = new SurveyAnswer([
                'response_id' => $response->id,
                'survey_question_id' => $question->id,
            ]);

            switch ($question->question_type) {
                case QuestionTypeEnum::Text:
                    $answer->answer_text = $request->input($questionId);
                    $answer->save();
                    break;

                case QuestionTypeEnum::Single:
                    $optionId = $request->input($questionId);
                    $answer->save();

                    SurveyAnswerOption::create([
                        'answer_id' => $answer->id,
                        'survey_question_option_id' => $optionId,
                    ]);
                    break;

                case QuestionTypeEnum::Multiple:
                    $optionIds = $request->input($questionId, []);
                    $answer->save();

                    foreach ($optionIds as $optionId) {
                        SurveyAnswerOption::create([
                            'answer_id' => $answer->id,
                            'survey_question_option_id' => $optionId,
                        ]);
                    }
                    break;
            }
        }
    }
}
