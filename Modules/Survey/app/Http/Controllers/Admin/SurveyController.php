<?php

namespace Modules\Survey\app\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
/*use Modules\Survey\app\Exports\Admin\Report\DatatableExport;
use Modules\Survey\app\Filters\Survey\DateFilter;
use Modules\Survey\app\Filters\Survey\RequiresAuthFilter;
use Modules\Survey\app\Filters\Survey\SearchFilter;
use Modules\Survey\app\Filters\Survey\SortFilter;
use Modules\Survey\app\Filters\Survey\StatusFilter;*/
use Modules\Survey\app\Http\Requests\Admin\Survey\StoreRequest;
use Modules\Survey\app\Http\Requests\Admin\Survey\UpdateRequest;
use Modules\Survey\app\Models\Survey;

class SurveyController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'نظرسنجی‌ها';

    const CREATE_TITLE = 'ایجاد نظرسنجی جدید';

    const EDIT_TITLE = 'ویرایش نظرسنجی';

    const PREVIEW_TITLE = 'پیش‌نمایش نظرسنجی';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $surveys = Survey::query()
            ->select([
                'surveys.id',
                'surveys.title',
                'surveys.description',
                'surveys.requires_auth',
                'surveys.auth_guard',
                'surveys.is_active',
                'surveys.start_date',
                'surveys.end_date',
                'surveys.access_token',
                'surveys.created_at',
                'surveys.updated_at',
                'admins.mobile as admin_mobile',
            ])
            ->withCount(['questions', 'responses'])
            ->join('admins', 'surveys.admin_id', '=', 'admins.id')
            ->filter([
                /*StatusFilter::class,
                RequiresAuthFilter::class,
                DateFilter::class,
                SortFilter::class,
                SearchFilter::class,*/
            ]);

        if (request('export')) {
            return $this->export($surveys->get());
        }

        $surveys = $surveys->paginate(20)
            ->withQueryString();

        return view('survey::admin.index', compact('title', 'surveys'));
    }

    /*private function export($surveys)
    {
        try {
            $fileName = 'Surveys-'.Carbon::now()->format('Y-m-d').'.xlsx';

            return Excel::download(new DatatableExport(collect($surveys)), $fileName);
        } catch (Exception $exception) {
            report($exception);

            return back()->with('danger', 'خطا در هنگام صادر کردن اطلاعات');
        }
    }*/

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('survey::admin.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $surveyData = $this->prepareSurveyData($request);
            Survey::create($surveyData);

            DB::commit();

            return $this->successResponse(
                route('admin.survey.index'),
            );
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(Survey $survey)
    {
        $title = self::EDIT_TITLE;

        $survey->loadCount(['questions', 'responses']);

        return view('survey::admin.edit', compact('title', 'survey'));
    }

    public function update(UpdateRequest $request, Survey $survey)
    {
        try {
            DB::beginTransaction();

            $surveyData = $this->prepareSurveyData($request, false);
            $survey->update($surveyData);

            DB::commit();

            return $this->successUpdateResponse(route('admin.survey.index'));
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(Survey $survey)
    {
        try {
            $survey->delete();

            return $this->successDestroyBack(route('admin.survey.index'));
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }

    public function preview(Survey $survey)
    {
        $title = self::PREVIEW_TITLE;

        $survey->load(['questions' => function ($query) {
            $query->orderBy('order');
        }, 'questions.options' => function ($query) {
            $query->orderBy('order');
        }]);

        return view('survey::admin.preview', compact('title', 'survey'));
    }

    public function duplicate(Survey $survey)
    {
        try {
            DB::beginTransaction();

            // Duplicate survey
            $newSurvey = $survey->replicate();
            $newSurvey->title = 'کپی از '.$survey->title;
            $newSurvey->is_active = false;
            $newSurvey->access_token = Str::random(32);
            $newSurvey->created_at = now();
            $newSurvey->updated_at = now();
            $newSurvey->save();

            foreach ($survey->questions as $question) {
                $newQuestion = $question->replicate();
                $newQuestion->survey_id = $newSurvey->id;
                $newQuestion->save();

                if (in_array($question->question_type, ['single_choice', 'multiple_choice'])) {
                    foreach ($question->options as $option) {
                        $newOption = $option->replicate();
                        $newOption->question_id = $newQuestion->id;
                        $newOption->save();
                    }
                }
            }

            DB::commit();

            return $this->successBack(
                route('admin.survey.edit', $newSurvey->id),
                'نظرسنجی با موفقیت کپی شد. اکنون می‌توانید آن را ویرایش کنید.'
            );
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    protected function prepareSurveyData(Request $request, $isNew = true): array
    {
        $data = [
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'requires_auth' => $request->boolean('requires_auth'),
            'auth_guard' => $request->input('auth_guard'),
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->filled('start_date')) {
            $data['start_date'] = Helper::toGregorian($request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $data['end_date'] = Helper::toGregorian($request->input('end_date'));
        }

        if ($isNew) {
            $data['admin_id'] = auth()->id();
        }

        return $data;
    }
}
