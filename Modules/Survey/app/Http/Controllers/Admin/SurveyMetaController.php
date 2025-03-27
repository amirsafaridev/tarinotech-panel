<?php

namespace Modules\Survey\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Modules\Project\app\Models\Project;
use Modules\Survey\app\Models\Survey;
use Modules\Survey\app\Models\SurveyMeta;
use Modules\Survey\app\Models\SurveyResponse;

class SurveyMetaController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'مدیریت متا پرسشنامه';

    const CREATE_TITLE = 'افزودن متا جدید';

    public function index(Request $request)
    {
        $title = self::INDEX_TITLE;

        $result = $this->validateAndGetModel($request);
        if (! $result['success']) {
            abort(404, $result['message']);
        }

        $model = $result['model'];
        $modelClass = $result['modelClass'];
        $surveyable_type = $request->query('surveyable_type');
        $surveyable_id = $request->query('surveyable_id');

        $modelInfo = [
            'id' => $model->id,
            'title' => $this->getModelTitle($model),
            'class' => $surveyable_type,
            'full_class' => $modelClass,
        ];

        $existingMetas = SurveyMeta::query()->where('surveyable_type', $modelClass)
            ->where('surveyable_id', $surveyable_id)
            ->with('survey')
            ->get();

        $surveys = Survey::where('has_meta', true)
            ->where('is_active', true)
            ->get();

        return view('survey::admin.meta.index', compact(
            'title',
            'surveys',
            'surveyable_type',
            'surveyable_id',
            'existingMetas',
            'modelInfo'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'survey_id' => 'required|exists:surveys,id',
        ]);

        $result = $this->validateAndGetModel($request);
        if (! $result['success']) {
            return $this->errorBack($result['message']);
        }

        $modelClass = $result['modelClass'];
        $surveyable_type = $request->query('surveyable_type');
        $surveyable_id = $request->query('surveyable_id');

        try {
            DB::beginTransaction();

            $existingMeta = SurveyMeta::query()->where('survey_id', $request->survey_id)
                ->where('surveyable_type', $modelClass)
                ->where('surveyable_id', $surveyable_id)
                ->first();

            if ($existingMeta) {
                DB::rollBack();

                return $this->failure('این پرسشنامه قبلاً به این آیتم متصل شده است.', 400);
            }

            SurveyMeta::create([
                'survey_id' => $request->survey_id,
                'surveyable_type' => $modelClass,
                'surveyable_id' => $surveyable_id,
            ]);

            DB::commit();

            $redirectUrl = $request->redirect_back ?: route('admin.survey.meta.index', [
                'surveyable_type' => $surveyable_type,
                'surveyable_id' => $surveyable_id,
            ]);

            return $this->successResponse($redirectUrl);
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(SurveyMeta $meta)
    {
        try {
            DB::beginTransaction();

            $surveyable_type = $this->getSimpleModelName($meta->surveyable_type);
            $surveyable_id = $meta->surveyable_id;

            SurveyResponse::query()->where('survey_meta_id', $meta->id)->delete();

            $meta->delete();

            DB::commit();

            return $this->successDestroyBack(route('admin.survey.meta.index', [
                'surveyable_type' => $surveyable_type,
                'surveyable_id' => $surveyable_id,
            ]));
        } catch (Exception $exception) {
            DB::rollBack();
            report($exception);

            return $this->exceptionBack($exception);
        }
    }

    /**
     * Validate parameters and get model
     */
    private function validateAndGetModel(Request $request): array
    {

        $surveyable_type = $request->query('surveyable_type');
        $surveyable_id = $request->query('surveyable_id');

        if (! $surveyable_type || ! $surveyable_id) {
            return [
                'success' => false,
                'message' => 'اطلاعات مدل مورد نظر ناقص است.',
            ];
        }

        $modelInstance = $this->resolveModelClass($surveyable_type);
        if (! $modelInstance) {
            return [
                'success' => false,
                'message' => 'مدل مورد نظر یافت نشد.',
            ];
        }

        $modelClass = get_class($modelInstance);

        $model = $modelInstance->query()->where('id', $surveyable_id)->first();
        if (! $model) {
            return [
                'success' => false,
                'message' => 'رکورد مورد نظر یافت نشد.',
            ];
        }

        return [
            'success' => true,
            'model' => $model,
            'modelClass' => $modelClass,
        ];
    }

    /**
     * Resolve the model class from a simple name
     */
    private function resolveModelClass(string $modelName): ?Model
    {
        $baseName = class_basename($modelName);

        return match ($baseName) {
            'Project' => new Project(),
            default => null,
        };
    }

    /**
     * Get a simplified name from a full class name
     */
    private function getSimpleModelName(string $fullClassName): string
    {
        return class_basename($fullClassName);
    }

    /**
     * Get a suitable title for the model
     */
    private function getModelTitle($model): string
    {

        foreach (['title', 'name'] as $field) {
            if (! empty($model->$field)) {
                return $model->$field;
            }
        }

        return 'شناسه: '.$model->id;
    }
}
