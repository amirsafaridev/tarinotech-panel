<?php

namespace Modules\Contract\app\Http\Controllers\Admin\Preview;

use App\Foundation\Contracts\PrintControllerInterface;
use App\Http\Controllers\Controller;
use App\Service\PdfService;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Modules\Contract\app\Enums\PlaceHolderKeys;
use Modules\Contract\App\Traits\SavesPdfToDiskTrait;
use Modules\Project\app\Models\ProjectSeo;
use Mpdf\MpdfException;
use Throwable;

class SeoProjectController extends Controller implements PrintControllerInterface
{
    use SavesPdfToDiskTrait;

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

    public function index(int $id)
    {
        try {
            $model = $this->findModel($id);

            $fileName = $this->getPreparedHtml($model);

            return $this->pdfService->output($fileName);
        } catch (Exception $e) {
            report($e);

            return $e->getMessage();
        } catch (Throwable $e) {
            report($e);

            return $e->getMessage();
        }
    }

    /**
     * @return ProjectSeo
     */
    public function findModel(int $id): Model
    {
        return ProjectSeo::query()
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
     * @param  ProjectSeo  $model
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
            PlaceHolderKeys::PROJECT_DOMAIN => $orEmpty($model->project->domain),
            PlaceHolderKeys::SEO_KEYWORD_COUNT => $orEmpty($model->keywords_count),
            PlaceHolderKeys::SEO_AMOUNT_CONTENT => $orEmpty($model->amount_content),
            PlaceHolderKeys::SEO_KEYWORDS => $orEmpty(
                is_array($model->keywords)
                    ? implode(', ', $model->keywords)
                    : (is_string($model->keywords) ? $model->keywords : '')
            ),
            PlaceHolderKeys::USER_ECONOMIC_CODE => $orEmpty($model->project?->user?->economic_code),
            PlaceHolderKeys::SEO_MONTHLY_PAYMENT_DOUBLE => $orEmpty(number_format($model->price_monthly * 2)),
            PlaceHolderKeys::SEO_MONTHLY_PAYMENT => $orEmpty(number_format($model->price_monthly)),
            PlaceHolderKeys::SEO_AGREEMENT_DURATION => $orEmpty(round($model->agreement_duration / 30)),
        ];

        $keys = array_keys($replacements);
        $values = array_values($replacements);

        return str($view)->replace($keys, $values);
    }

    /**
     * @param  ProjectSeo  $model
     */
    public function getViewPath(Model $model): string
    {
        return 'contract::admin.pdf.seo-project';
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
     * @param  ProjectSeo  $model
     */
    public function fileName(Model $model): string
    {
        return $model->id.'.pdf';
    }

    /**
     * @throws Throwable
     */
    public function getPreparedHtml(Model $model): string
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
