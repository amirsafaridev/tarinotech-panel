<?php

namespace Modules\Contract\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Contract\app\Http\Requests\Admin\UserSignable\UpdateRequest;
use Modules\Contract\app\Models\SignableAttachment;
use Modules\Contract\app\Models\UserSignable;
use Modules\Contract\app\Traits\HandlesSignableUpdateTrait;
use Modules\Project\app\Models\ProjectAds;
use Modules\Project\app\Models\ProjectSeo;
use Modules\Project\app\Models\ProjectWeb;
use Throwable;

class UserSignController extends Controller
{
    use HandlesSignableUpdateTrait;
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'درخواست های امضاء کارفرما';

    const EDIT_TITLE = 'درخواست های امضاء کارفرما';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $userSignables = DB::table('user_signables_with_details')
            // Filter by search term
            ->when(request('search'), function (Builder $query): void {
                $search = escapeLike(request('search'));
                $query->where(function (Builder $q) use ($search): void {
                    $q->where('project_title', 'like', '%'.$search.'%')
                        ->orWhere('project_domain', 'like', '%'.$search.'%')
                        ->orWhere('project_id', 'like', '%'.$search.'%');
                });
            })

            // Filter by admin name
            ->when(request('admin'), function (Builder $query): void {
                $admin = escapeLike(request('admin'));
                $query->where(function (Builder $q) use ($admin): void {
                    $q->where('admin_first_name', 'like', '%'.$admin.'%')
                        ->orWhere('admin_last_name', 'like', '%'.$admin.'%');
                });
            })

            // Filter by status
            ->when(request('status'), function (Builder $query): void {
                $query->where('status', request('status'));
            })

            // Sort results
            ->when(
                request('sort') && preg_match('/^(created_at)\|(asc|desc)$/', request('sort')),
                function (Builder $query): void {
                    $query->orderBy(...explode('|', request('sort')));
                },
                function (Builder $query): void {
                    $query->orderBy('created_at', 'desc');
                }
            )

            // Paginate results
            ->paginate()
            ->withQueryString();

        return view('contract::admin.user_signable.index', compact('title', 'userSignables'));

    }

    public function edit(UserSignable $userSignable)
    {

        $relationshipsToLoad = $this->determineRelationshipsToLoad($userSignable);
        $userSignable->load($relationshipsToLoad);

        $title = self::EDIT_TITLE.' - '.$userSignable->target?->project->title;

        return view('contract::admin.user_signable.edit', compact('title', 'userSignable'));
    }

    /**
     * @throws Throwable
     */
    public function update(UpdateRequest $request, UserSignable $userSignable)
    {
        try {
            DB::beginTransaction();

            $oldStatus = $userSignable->status;

            $itemData = $this->prepareItemData($request);
            $userSignable->update($itemData);

            $this->updateAttachments($request->input('attachments'), $userSignable);

            $this->handleSignableUpdate($userSignable, $oldStatus, $request->all());

            DB::commit();

            return $this->successUpdateResponse(route('admin.contract.sign.user.edit', $userSignable->id));
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function show(UserSignable $userSignable)
    {
        $relationshipsToLoad = $this->determineRelationshipsToLoad($userSignable);
        $userSignable->load($relationshipsToLoad);
        $title = self::EDIT_TITLE.' - '.$userSignable->target?->project->title;

        return view('contract::admin.user_signable.show', compact('title', 'userSignable'));
    }

    /**
     * Update attachments based on the provided IDs.
     */
    protected function updateAttachments(?array $attachmentIds, UserSignable $userSignable): void
    {
        if (empty($attachmentIds)) {
            return;
        }

        SignableAttachment::query()->whereIn('ulid', $attachmentIds)
            ->update([
                'target_type' => UserSignable::class,
                'target_id' => $userSignable->id,
                'is_used' => true,
            ]);
    }

    protected function prepareItemData(Request $req): array
    {
        $signableData['status'] = $req->input('status');
        $signableData['note'] = $req->input('note');

        return $signableData;
    }

    private function determineRelationshipsToLoad($userSignable): array
    {
        $relationshipsToLoad = ['attachments', 'files'];

        if (in_array($userSignable->target_type, [ProjectWeb::class, ProjectSeo::class])) {
            $relationshipsToLoad[] = 'target.project';
            $relationshipsToLoad[] = 'target.package';
        } elseif ($userSignable->target_type === ProjectAds::class) {
            $relationshipsToLoad[] = 'target.project';
        }

        return $relationshipsToLoad;
    }
}
