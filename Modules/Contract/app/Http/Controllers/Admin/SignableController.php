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
            $signable = $project->signable;
            $userId = auth()->id();
            $pendingStatus = SignableStatus::Pending;

            if ($signable) {
                if ($signable->status === SignableStatus::Reject) {
                    $signable->update([
                        'make_admin_id' => $userId,
                        'status' => $pendingStatus,
                        'created_at' => now(),
                    ]);

                    return back()->with('success', 'در خواست مجدد ارسال شد.');
                }

                return back()->with('warning', 'قبلا برای این پروژه در خواست قرارداد داده شده.');
            }

            $project->signable()->create([
                'make_admin_id' => $userId,
                'status' => $pendingStatus,
            ]);

            return back()->with('success', 'در خواست با موفقیت ثبت شد.');
        } catch (Exception $e) {
            report($e);

            return back()->with('danger', $e->getMessage());
        }
    }
}
