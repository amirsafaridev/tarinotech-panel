<?php

namespace Modules\Contract\app\Http\Controllers\Admin;

use App\Enums\Database\Print\PrintableType;
use App\Http\Controllers\Controller;
use App\Service\PdfService;
use Exception;
use Modules\Project\app\Models\ProjectAds;
use Modules\Project\app\Models\ProjectSeo;
use Modules\Project\app\Models\ProjectWeb;

class PreviewController extends Controller
{
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
            $this->pdfService->writeHtml($view);

            return $this->pdfService->output('document.pdf', 'I');
        } catch (Exception $e) {
            report($e);

            return $e->getMessage();
        }
    }

    private function findModel(int $typeId, string $typeTarget): ProjectSeo|ProjectWeb|ProjectAds
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
            default => abort(404),
        };
    }

    private function getViewPath(string $typeTarget): ?string
    {
        return match ($typeTarget) {
            PrintableType::ProjectWeb => 'contract::admin.pdf.web-project',
            PrintableType::ProjectSeo => 'contract::admin.pdf.seo-project',
            PrintableType::ProjectAds => 'contract::admin.pdf.ads-project',
            default => null,
        };
    }

    private function setupPdfService()
    {
        $this->pdfService->setFont('DejaVuSans', 'B', 14);
        $this->pdfService->setMargins(0, 0, 40);
    }
}
