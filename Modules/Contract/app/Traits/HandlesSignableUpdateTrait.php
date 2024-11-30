<?php

namespace Modules\Contract\App\Traits;

use Modules\Contract\app\Enums\SignableStatus;
use Modules\Contract\app\Http\Controllers\Admin\Preview\SeoProjectController;
use Modules\Contract\app\Http\Controllers\Admin\Preview\WebProjectController;
use Modules\Contract\app\Models\Signable;
use Modules\Contract\app\Models\UserSignable;
use Modules\Project\app\Models\ProjectSeo;
use Modules\Project\app\Models\ProjectWeb;

trait HandlesSignableUpdateTrait
{
    protected function handleSignableUpdate(Signable|UserSignable $signable, string $oldStatus, array $requestData): void
    {
        $isRelevantProjectAndSigned =
            in_array($signable->target_type, [ProjectWeb::class, ProjectSeo::class])
            && $signable->status === SignableStatus::Signed;

        $statusChanged = $oldStatus != $requestData['status'];
        $shouldMakeFresh = array_key_exists('make_fresh', $requestData);

        if (($isRelevantProjectAndSigned && $statusChanged) || $shouldMakeFresh) {
            $controllers = [
                ProjectWeb::class => ['controller' => WebProjectController::class, 'type' => 'web'],
                ProjectSeo::class => ['controller' => SeoProjectController::class, 'type' => 'seo'],
            ];

            $controllerData = $controllers[$signable->target_type] ?? null;

            if ($controllerData) {
                $storePath = resolve($controllerData['controller'])->saveToDisk($signable->target_id, $controllerData['type']);
                $signable->files()->create([
                    'file_path' => $storePath,
                ]);
            }
        }
    }
}
