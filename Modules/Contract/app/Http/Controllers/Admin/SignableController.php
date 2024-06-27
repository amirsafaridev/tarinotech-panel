<?php

namespace Modules\Contract\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Modules\Contract\app\Enums\SignableStatus;
use Modules\Project\app\Models\ProjectAds;
use Modules\Project\app\Models\ProjectSeo;
use Modules\Project\app\Models\ProjectWeb;

class SignableController extends Controller
{
    public function web(ProjectWeb $projectWeb)
    {

        return $this->handleProjectPrint($projectWeb);
    }

    public function seo(ProjectSeo $projectSeo)
    {
        return $this->handleProjectPrint($projectSeo);
    }

    public function ads(ProjectAds $projectAds)
    {
        return $this->handleProjectPrint($projectAds);
    }

    private function handleProjectPrint(ProjectWeb|ProjectSeo|ProjectAds $project)
    {
        try {
            if ($project->signable) {
                return back()->with('warning', 'قبلا برای این پروژه در خواست قرارداد داده شده.');
            }

            $project->signable()->create([
                'make_admin_id' => auth()->id(),
                'status' => SignableStatus::Pending,
            ]);

            return back()->with('success', 'در خواست با موفقیت ثبت شد.');
        } catch (Exception $e) {
            report($e);

            return back()->with('danger', $e->getMessage());
        }
    }
}
