<?php

namespace Modules\Survey\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Survey\app\Enums\Database\QuestionTypeEnum;
use Modules\Survey\app\Http\Requests\Admin\Question\StoreRequest;
use Modules\Survey\app\Http\Requests\Admin\Question\UpdateRequest;
use Modules\Survey\app\Models\Survey;
use Modules\Survey\app\Models\SurveyQuestion;

class QuestionController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'سوالات نظرسنجی';

    const CREATE_TITLE = 'افزودن سوال جدید';

    const EDIT_TITLE = 'ویرایش سوال';

    public function index(Survey $survey)
    {
        $title = self::INDEX_TITLE;

        $questions = $survey->questions()
            ->withCount('options')
            ->orderBy('order')
            ->get();

        return view('survey::admin.question.index', compact('title', 'survey', 'questions'));
    }

    public function create(Survey $survey)
    {
        $title = self::CREATE_TITLE;

        return view('survey::admin.question.create', compact('title', 'survey'));
    }

    public function store(StoreRequest $request, Survey $survey)
    {
        try {
            DB::beginTransaction();

            // Get the next order value
            $nextOrder = $survey->questions()->max('order') + 1;

            $questionData = [
                'survey_id' => $survey->id,
                'question_text' => $request->input('question_text'),
                'question_type' => $request->input('question_type'),
                'is_required' => $request->boolean('is_required'),
                'order' => $nextOrder,
            ];

            // Handle settings for questions that need additional configuration
            if ($request->input('question_type') == QuestionTypeEnum::Number) {
                $questionData['settings'] = $request->input('settings', []);
            }

            $question = SurveyQuestion::query()->create($questionData);

            DB::commit();

            $redirectUrl = in_array((int) $request->input('question_type'), [
                QuestionTypeEnum::Single,
                QuestionTypeEnum::Multiple,
            ])
                ? route('admin.survey.question.option.create', [$survey->id, $question->id])
                : route('admin.survey.question.index', $survey->id);

            $message = in_array((int) $request->input('question_type'), [
                QuestionTypeEnum::Single,
                QuestionTypeEnum::Multiple,
            ])
                ? 'سوال با موفقیت ایجاد شد. اکنون می‌توانید گزینه‌های آن را تعریف کنید.'
                : 'سوال با موفقیت ایجاد شد.';

            return $this->successResponse($redirectUrl, $message);
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(Survey $survey, SurveyQuestion $question)
    {
        $title = self::EDIT_TITLE;

        return view('survey::admin.question.edit', compact('title', 'survey', 'question'));
    }

    public function update(UpdateRequest $request, Survey $survey, SurveyQuestion $question)
    {
        try {
            DB::beginTransaction();

            $questionData = [
                'question_text' => $request->input('question_text'),
                'question_type' => $request->input('question_type'),
                'is_required' => $request->boolean('is_required'),
            ];

            // Handle settings for questions that need additional configuration
            if ($request->input('question_type') == QuestionTypeEnum::Number) {
                $questionData['settings'] = $request->input('settings', []);
            } else {
                $questionData['settings'] = null;
            }

            // If changing question type from choice to non-choice, delete options
            if (! in_array($request->input('question_type'), [QuestionTypeEnum::Single, QuestionTypeEnum::Multiple]) &&
                in_array($question->question_type, [QuestionTypeEnum::Single, QuestionTypeEnum::Multiple])) {
                $question->options()->delete();
            }

            $question->update($questionData);

            DB::commit();

            return $this->successResponse(route('admin.survey.question.index', $survey->id));
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(Survey $survey, SurveyQuestion $question)
    {
        try {
            $question->delete();

            // Reorder remaining questions
            $this->reorderQuestions($survey);

            return $this->successDestroyBack(route('admin.survey.question.index', $survey->id));
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }

    public function reorder(Request $request, Survey $survey)
    {
        try {
            $items = $request->input('items', []);

            if (count($items) > 0) {
                foreach ($items as $item) {
                    SurveyQuestion::query()->where('id', $item['id'])
                        ->where('survey_id', $survey->id)
                        ->update(['order' => $item['order']]);
                }
            }

            return response()->json(['success' => true]);
        } catch (Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    private function reorderQuestions(Survey $survey)
    {
        $questions = $survey->questions()->orderBy('order')->get();

        foreach ($questions as $index => $question) {
            $question->update(['order' => $index + 1]);
        }
    }
}
