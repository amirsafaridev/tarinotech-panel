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
use Modules\Survey\app\Models\SurveyMeta;
use Modules\Survey\app\Models\SurveyResponse;
use Modules\User\app\Models\User;

class SurveyController extends Controller
{
    const SESSION_SURVEY_START_TIME = 'survey_start_time';

    const SESSION_SURVEY_ID = 'survey_session_id';

    const SESSION_SURVEY_META_ID = 'survey_meta_id';

    /**
     * @var Survey|null The current survey being processed
     */
    protected ?Survey $survey = null;

    public function show(Request $request, string $accessToken): View|RedirectResponse
    {
        $survey = $this->findActiveSurvey($accessToken);

        if (! $survey) {
            abort(404, 'نظرسنجی مورد نظر یافت نشد یا در دسترس نیست.');
        }

        $this->survey = $survey;

        if ($survey->has_meta) {
            $metaToken = $request->query('meta');

            if (! $metaToken) {
                abort(404, 'این نظرسنجی نیازمند پارامتر meta است.');
            }

            $meta = SurveyMeta::query()->where('access_token', $metaToken)
                ->where('survey_id', $survey->id)
                ->first();

            if (! $meta) {
                abort(404, 'پارامتر meta نامعتبر است.');
            }

            session([self::SESSION_SURVEY_META_ID => $meta->id]);
        }

        if ($survey->requires_auth && ! Auth::check()) {
            return view('survey::web.login_required', compact('survey'));
        }

        if ($this->hasUserSubmitted($survey)) {
            return redirect()->route('survey.public.thankYou', $survey->access_token)
                ->with('message', 'شما قبلا در این نظرسنجی شرکت کرده‌اید.');
        }

        $this->loadSurveyWithQuestions($survey);
        $this->initiateSurveySession();

        session([self::SESSION_SURVEY_START_TIME => now()]);

        $showPersonalInfo = $this->shouldShowPersonalInfo();

        return view('survey::web.show', compact('survey', 'showPersonalInfo'));
    }

    public function submit(Request $request, string $accessToken)
    {
        $survey = $this->findActiveSurvey($accessToken);

        if (! $survey) {
            abort(404, 'نظرسنجی مورد نظر یافت نشد یا در دسترس نیست.');
        }

        if ($survey->has_meta && ! session(self::SESSION_SURVEY_META_ID)) {
            abort(404, 'این نظرسنجی نیازمند پارامتر meta است.');
        }

        if ($survey->requires_auth && ! Auth::check()) {
            return view('survey::web.login_required', compact('survey'));
        }

        $this->validateSurveySubmission($request, $survey);

        try {
            DB::beginTransaction();

            $startTime = session(self::SESSION_SURVEY_START_TIME);

            $response = $this->createSurveyResponse($request, $survey, $startTime);
            $this->processAnswers($request, $response, $survey);

            DB::commit();

            session()->forget([
                self::SESSION_SURVEY_ID,
                self::SESSION_SURVEY_START_TIME,
                self::SESSION_SURVEY_META_ID,
            ]);

            return redirect()->route('survey.public.thankYou', $survey->access_token);
        } catch (Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'خطا در ثبت پاسخ‌ها: '.$e->getMessage());
        }
    }

    public function thankYou(string $accessToken): View
    {
        $survey = Survey::query()->where('access_token', $accessToken)->first();

        if (! $survey) {
            abort(404);
        }

        return view('survey::web.thank', compact('survey'));
    }

    private function findActiveSurvey(string $accessToken): ?Survey
    {
        return Survey::query()->where('access_token', $accessToken)
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
        if (session()->has(self::SESSION_SURVEY_META_ID)) {
            $metaId = session(self::SESSION_SURVEY_META_ID);

            return SurveyResponse::query()->where('survey_id', $survey->id)
                ->where('survey_meta_id', $metaId)
                ->exists();
        }

        if (Auth::check()) {
            $user = Auth::user();

            return SurveyResponse::query()->where('survey_id', $survey->id)
                ->where('respondent_type', get_class($user))
                ->where('respondent_id', $user->id)
                ->exists();
        }

        return false;
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
        session([self::SESSION_SURVEY_ID => $sessionId]);
    }

    /**
     * Check if personal info section should be shown
     */
    private function shouldShowPersonalInfo(): bool
    {
        return ! Auth::check() && ! $this->survey->requires_auth && ! session()->has(self::SESSION_SURVEY_META_ID);
    }

    private function createSurveyResponse(Request $request, Survey $survey, $startTime): SurveyResponse
    {
        $responseData = [
            'survey_id' => $survey->id,
            'ip_address' => $request->ip(),
            'session_id' => session(self::SESSION_SURVEY_ID),
            'started_at' => $startTime,
            'completed_at' => now(),
        ];

        $responseData['respondent_email'] = $request->input('email');
        $responseData['respondent_name'] = $request->input('name');

        $user = $this->getUserForResponse();

        if ($user) {
            $responseData['respondent_type'] = User::class;
            $responseData['respondent_id'] = $user->id;

            if (empty($responseData['respondent_name'])) {
                $responseData['respondent_name'] = trim($user->first_name.' '.$user->last_name) ?: null;
            }

            if (empty($responseData['respondent_email']) && $user->email) {
                $responseData['respondent_email'] = $user->email;
            }
        }

        if (session()->has(self::SESSION_SURVEY_META_ID)) {
            $responseData['survey_meta_id'] = session(self::SESSION_SURVEY_META_ID);
        }

        return SurveyResponse::query()->create($responseData);
    }

    /**
     * Get the user for the survey response
     */
    private function getUserForResponse(): ?User
    {
        if (session()->has(self::SESSION_SURVEY_META_ID)) {
            $meta = SurveyMeta::query()->find(session(self::SESSION_SURVEY_META_ID));

            if ($meta && $meta->surveyable && method_exists($meta->surveyable, 'user')) {
                $user = $meta->surveyable->user;
                if ($user) {
                    return $user;
                }
            }
        }

        if (Auth::check()) {
            return Auth::user();
        }

        return null;
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

                    SurveyAnswerOption::query()->create([
                        'answer_id' => $answer->id,
                        'survey_question_option_id' => $optionId,
                    ]);
                    break;

                case QuestionTypeEnum::Multiple:
                    $optionIds = $request->input($questionId, []);
                    $answer->save();

                    foreach ($optionIds as $optionId) {
                        SurveyAnswerOption::query()->create([
                            'answer_id' => $answer->id,
                            'survey_question_option_id' => $optionId,
                        ]);
                    }
                    break;
            }
        }
    }
}
