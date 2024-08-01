<?php

namespace Modules\Contract\app\Http\Controllers\Admin;

use App\Enums\Database\Print\PrintableType;
use App\Http\Controllers\Controller;
use App\Service\PdfService;
use Exception;
use Modules\Contract\app\Enums\PlaceHolderKeys;
use Modules\Factor\app\Models\Factor;
use Modules\Project\app\Models\ProjectAds;
use Modules\Project\app\Models\ProjectSeo;
use Modules\Project\app\Models\ProjectWeb;
use View;

class PreviewController extends Controller
{
    const EMPTY_PLACEHOLDER = '--------------';

    public function __construct(private PdfService $pdfService)
    {
    }

    public function index(string $typeTarget, int $typeId)
    {
        try {

            $viewPath = $this->getViewPath($typeTarget);

            if (! $viewPath) {
                throw new Exception('اطلاعات ارسالی صحیح نیست!');
            }

            $model = $this->findModel($typeId, $typeTarget);

            $this->setupPdfService();

            $view = view($viewPath, compact('model'))->render();

            $this->pdfService->getMpdfInstance()->showImageErrors = true;

            $header = View::make('contract::admin.pdf.header')->render();
            $footer = View::make('contract::admin.pdf.footer')->render();

            $this->pdfService->getMpdfInstance()->SetHeader($header);
            $this->pdfService->getMpdfInstance()->SetFooter($footer);

            $this->pdfService->writeHtml($this->fillData($view, $model));

            return $this->pdfService->output('document.pdf', 'I');
        } catch (Exception $e) {
            report($e);

            return $e->getMessage();
        }
    }

    private function fillData(string $view, ProjectSeo|ProjectWeb|ProjectAds|Factor $model)
    {

        $orEmpty = fn ($value) => $value ?? self::EMPTY_PLACEHOLDER;

        $replacements = [
            PlaceHolderKeys::ALPHA_DATE => $orEmpty($model->project?->agreement_at?->toJalali()->formatWord('d F Y')),
            PlaceHolderKeys::USER_COMPANY_REGISTER_ID => $orEmpty($model->project?->user?->company?->register_id),
            PlaceHolderKeys::USER_COMPANY_POSITION => $orEmpty($model->project?->user?->company?->position),
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
        ];

        $keys = array_keys($replacements);
        $values = array_values($replacements);

        return str($view)->replace($keys, $values);
    }

    private function findModel(int $typeId, string $typeTarget): ProjectSeo|ProjectWeb|ProjectAds|Factor
    {
        return match ($typeTarget) {
            PrintableType::ProjectWeb => ProjectWeb::query()
                ->with([
                    'project.user',
                    'package',
                    'project.base',
                    'project.type',
                    'signable',
                ])
                ->findOrFail($typeId),
            PrintableType::ProjectSeo => ProjectSeo::query()
                ->with('project.user')
                ->findOrFail($typeId),
            PrintableType::ProjectAds => ProjectAds::query()
                ->with('project.user')
                ->findOrFail($typeId),
            PrintableType::Factor => Factor::query()
                ->with(['project.user', 'items'])
                ->findOrFail($typeId),
            default => abort(404),
        };
    }

    private function getViewPath(string $typeTarget): ?string
    {
        return match ($typeTarget) {
            PrintableType::ProjectWeb => 'contract::admin.pdf.web-project',
            PrintableType::ProjectSeo => 'contract::admin.pdf.seo-project',
            PrintableType::ProjectAds => 'contract::admin.pdf.ads-project',
            PrintableType::Factor => 'contract::admin.pdf.factor',
            default => null,
        };
    }

    private function setupPdfService()
    {
        $this->pdfService->setFont('DejaVuSans', 'B', 14);

    }
}
