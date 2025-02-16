<?php

namespace Modules\Contract\app\Http\Controllers\Admin\Preview;

use App\Foundation\Contracts\PrintControllerInterface;
use App\Http\Controllers\Controller;
use App\Service\PdfService;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Modules\Factor\app\Models\Factor;
use Mpdf\MpdfException;
use Throwable;

class FactorController extends Controller implements PrintControllerInterface
{
    private PdfService $pdfService;

    public function __construct()
    {
        $this->pdfService = new PdfService([
            'orientation' => 'L',
            'margin_bottom' => 5,
            'margin_top' => 10,
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
     * @return Factor
     */
    public function findModel(int $id): Model
    {
        return Factor::query()
            ->with([
                'project.user',
                'items',
            ])
            ->findOrFail($id);
    }

    public function fillData(string $view, Model $model): string
    {
        return $view;
    }

    public function getViewPath(Model $model): string
    {
        return 'contract::admin.pdf.factor';
    }

    /**
     * @throws MpdfException
     * @throws Throwable
     */
    public function setupPdfService(?Model $model = null): void
    {
        $this->pdfService->setFont('DejaVuSans', 'B', 14);
    }

    /**
     * @param  Factor  $model
     */
    public function fileName(Model $model): string
    {
        return $model->identify.'.pdf';
    }

    public function saveToDisk(int $id, string $prefixNameFile): string
    {
        // TODO: Implement saveToDisk() method.
        return '';
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
