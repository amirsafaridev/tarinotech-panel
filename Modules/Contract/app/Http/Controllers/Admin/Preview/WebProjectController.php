<?php

namespace Modules\Contract\app\Http\Controllers\Admin\Preview;

use App\Foundation\Contracts\PrintControllerInterface;
use App\Http\Controllers\Controller;
use App\Service\PdfService;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Modules\Contract\app\Enums\PlaceHolderKeys;
use Modules\Project\app\Models\ProjectWeb;
use Mpdf\MpdfException;
use Throwable;

class WebProjectController extends Controller implements PrintControllerInterface
{
    const EMPTY_PLACEHOLDER = '--------------';

    private PdfService $pdfService;

    public function __construct()
    {
        $this->pdfService = new PdfService([
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 35,
            'margin_bottom' => 40,
            'nonPrintMargin' => 0,
            'margin_header' => 0,
            'margin_footer' => 0,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function index(int $id)
    {
        try {
            $model = $this->findModel($id);

            $fileName = $this->getPreparedHtml($model);

            return $this->pdfService->output($fileName);
        } catch (Exception $e) {
            report($e);

            return $e->getMessage();
        }
    }

    /**
     * @throws Throwable
     */
    public function saveToDisk(int $id)
    {
        try {
            $model = $this->findModel($id);
            $this->getPreparedHtml($model);

            $pdfContent = $this->pdfService->outputFile('save_contact');

            $timestamp = now()->format('Y-m-d_H-i-s');
            $fileName = sprintf('web_contract_project_%s_contract_%s.pdf', $model->id, $timestamp);
            $filePath = 'contracts/'.$fileName;

            Storage::disk('private')->put($filePath, $pdfContent);

            return $filePath;
        } catch (Exception $e) {
            report($e);

            return 'An error occurred: '.$e->getMessage();
        }
    }

    /**
     * @return ProjectWeb
     */
    public function findModel(int $id): Model
    {
        return ProjectWeb::query()
            ->with([
                'project.user',
                'package',
                'project.base',
                'project.type',
                'signable',
            ])
            ->findOrFail($id);
    }

    /**
     * @param  ProjectWeb  $model
     */
    public function fillData(string $view, Model $model): string
    {
        $orEmpty = fn ($value) => $value ?? self::EMPTY_PLACEHOLDER;

        $replacements = [
            PlaceHolderKeys::ALPHA_DATE => $orEmpty($model->project?->agreement_at?->toJalali()->formatWord('d F Y')),
            PlaceHolderKeys::USER_COMPANY_REGISTER_ID => $orEmpty($model->project?->user?->company?->register_id),
            PlaceHolderKeys::USER_COMPANY_POSITION => $orEmpty($model->project?->user?->company?->position),
            PlaceHolderKeys::USER_COMPANY_IDENTIFY => $orEmpty($model->project?->user?->company?->identify),
            PlaceHolderKeys::USER_COMPANY => $orEmpty($model->project?->user?->company?->name),
            PlaceHolderKeys::USER_NATIONAL => $orEmpty($model->project?->user?->national_id),
            PlaceHolderKeys::USER_ADDRESS => $orEmpty($model->project?->user?->address?->address),
            PlaceHolderKeys::USER_TEL => $orEmpty($model->project?->user?->mobile),
            PlaceHolderKeys::USER_EMAIL => $orEmpty($model->project?->user?->email),
            PlaceHolderKeys::USERNAME => $orEmpty($model->project?->user?->fullname),
            PlaceHolderKeys::DOCUMENT_ID => $orEmpty($model->project?->user?->document_id),
            PlaceHolderKeys::PROJECT_PRICE => $orEmpty(number_format($model->project?->price)),
            PlaceHolderKeys::PROJECT_TYPE => $orEmpty($model->package?->title),
            PlaceHolderKeys::PROJECT_TIME_WORK => $orEmpty($model->working_days),
            PlaceHolderKeys::USER_ECONOMIC_CODE => $orEmpty($model->project?->user?->economic_code),
        ];

        $keys = array_keys($replacements);
        $values = array_values($replacements);

        return str($view)->replace($keys, $values);
    }

    /**
     * @param  ProjectWeb  $model
     */
    public function getViewPath(Model $model): string
    {
        if (in_array($model->package_id, [5, 4, 10])) {
            return 'contract::admin.pdf.web-project-cheap-contract';
        }

        return 'contract::admin.pdf.web-project';
    }

    /**
     * @throws MpdfException
     * @throws Throwable
     */
    public function setupPdfService(?Model $model = null): void
    {
        $header = view('contract::admin.pdf.header', compact('model'))->render();
        $footer = view('contract::admin.pdf.footer', compact('model'))->render();
        $this->pdfService->getMpdfInstance()->SetHTMLHeader($header);
        $this->pdfService->getMpdfInstance()->SetHTMLFooter($footer);
        $this->pdfService->setFont('DejaVuSans', 'B', 14);
    }

    /**
     * @param  ProjectWeb  $model
     */
    public function fileName(Model $model): string
    {
        return $model->id.'.pdf';
    }

    /**
     * @throws Throwable
     */
    public function getPreparedHtml(ProjectWeb $model): string
    {

        $this->setupPdfService($model);

        $viewPath = $this->getViewPath($model);
        $view = view($viewPath, compact('model'))->render();

        $viewFilled = $this->fillData($view, $model);
        $fileName = $this->fileName($model);

        $this->pdfService->writeHtml($viewFilled);

        return $fileName;
    }
}
