<?php

namespace Modules\Survey\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Survey\app\Http\Requests\Admin\QuestionOption\StoreRequest;
use Modules\Survey\app\Http\Requests\Admin\QuestionOption\UpdateRequest;
use Modules\Survey\app\Models\Survey;
use Modules\Survey\app\Models\SurveyQuestion;
use Modules\Survey\app\Models\SurveyQuestionOption;

class QuestionOptionController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'گزینه‌های سوال';

    const CREATE_TITLE = 'افزودن گزینه جدید';

    const EDIT_TITLE = 'ویرایش گزینه';

    public function index(Survey $survey, SurveyQuestion $question)
    {
        $title = self::INDEX_TITLE;

        $options = $question->options()
            ->orderBy('order')
            ->get();

        return view('survey::admin.question.option.index', compact('title', 'survey', 'question', 'options'));
    }

    public function create(Survey $survey, SurveyQuestion $question)
    {
        $title = self::CREATE_TITLE;

        return view('survey::admin.question.option.create', compact('title', 'survey', 'question'));
    }

    public function store(StoreRequest $request, Survey $survey, SurveyQuestion $question)
    {
        try {
            DB::beginTransaction();

            // Get the next order value
            $nextOrder = $question->options()->max('order') + 1;

            $optionData = [
                'survey_question_id' => $question->id,
                'option_text' => $request->input('option_text'),
                'color_code' => $request->input('color_code', '#'.substr(md5($request->input('option_text')), 0, 6)),
                'order' => $nextOrder,
            ];

            SurveyQuestionOption::create($optionData);

            DB::commit();

            if ($request->has('create_another')) {
                return $this->successResponse(
                    route('admin.survey.question.option.create', [$survey->id, $question->id]),
                );
            }

            return $this->successResponse(
                route('admin.survey.question.option.index', [$survey->id, $question->id]),
            );
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(Survey $survey, SurveyQuestion $question, SurveyQuestionOption $option)
    {
        $title = self::EDIT_TITLE;

        return view('survey::admin.question.option.edit', compact('title', 'survey', 'question', 'option'));
    }

    public function update(UpdateRequest $request, Survey $survey, SurveyQuestion $question, SurveyQuestionOption $option)
    {
        try {
            DB::beginTransaction();

            $optionData = [
                'option_text' => $request->input('option_text'),
                'color_code' => $request->input('color_code'),
            ];

            $option->update($optionData);

            DB::commit();

            return $this->successResponse(
                route('admin.survey.question.option.index', [$survey->id, $question->id]),
            );
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(Survey $survey, SurveyQuestion $question, SurveyQuestionOption $option)
    {
        try {
            $option->delete();

            $this->reorderOptions($question);

            return $this->successDestroyBack(
                route('admin.survey.question.option.index', [$survey->id, $question->id])
            );
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }

    public function reorder(Request $request, SurveyQuestion $question)
    {
        try {
            $items = $request->input('items', []);

            if (count($items) > 0) {
                foreach ($items as $item) {
                    SurveyQuestionOption::where('id', $item['id'])
                        ->where('survey_question_id', $question->id)
                        ->update(['order' => $item['order']]);
                }
            }

            return response()->json(['success' => true]);
        } catch (Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    private function reorderOptions(SurveyQuestion $question)
    {
        $options = $question->options()->orderBy('order')->get();

        foreach ($options as $index => $option) {
            $option->update(['order' => $index + 1]);
        }
    }
}
