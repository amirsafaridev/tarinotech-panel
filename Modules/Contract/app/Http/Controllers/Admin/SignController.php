<?php

namespace Modules\Contract\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Contract\app\Enums\SignableStatus;
use Modules\Contract\app\Http\Requests\Admin\Signable\UpdateRequest;
use Modules\Contract\app\Models\Signable;
use Modules\Contract\app\Models\UserSignable;
use Modules\Contract\app\Traits\HandlesSignableUpdateTrait;
use Modules\Project\app\Models\ProjectSeo;
use Modules\Project\app\Models\ProjectWeb;
use Throwable;

class SignController extends Controller
{
    use HandlesSignableUpdateTrait;
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'درخواست های امضاء';

    const EDIT_TITLE = 'درخواست های امضاء';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $signables = DB::table('signables_with_details')
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

        return view('contract::admin.signable.index', compact('title', 'signables'));

    }

    public function edit(Signable $signable)
    {
        $signable->load(['files', 'target.project']);

        $title = self::EDIT_TITLE.' - '.$signable->target?->project->title;

        return view('contract::admin.signable.edit', compact('title', 'signable'));
    }

    /**
     * @throws Throwable
     */
    public function update(UpdateRequest $request, Signable $signable)
    {
        try {
            DB::beginTransaction();

            $item = $this->prepareItemData($request);
            $oldStatus = $signable->status;
            $signable->update($item);

            if ($this->shouldCreateUserSignable($request, $signable)) {
                $this->createUserSignable($signable);
            }

            $this->handleSignableUpdate($signable, $oldStatus, $request->all());

            DB::commit();

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(Signable $signable)
    {
        try {
            $signable->delete();

            return $this->successDestroyBack(route('admin.contract.sign.index'));
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }

    protected function prepareItemData(Request $req): array
    {
        $signableData['status'] = $req->input('status');
        $signableData['note'] = $req->input('note');
        $signableData['sign_at'] = now();

        return $signableData;
    }

    private function shouldCreateUserSignable(UpdateRequest $request, Signable $signable): bool
    {
        return $request->input('status') === SignableStatus::Signed &&
            ($signable->target_type === ProjectWeb::class || $signable->target_type === ProjectSeo::class);
    }

    private function createUserSignable(Signable $signable): void
    {
        UserSignable::query()->firstOrCreate(
            [
                'target_type' => $signable->target_type,
                'target_id' => $signable->target_id,
                'user_id' => $signable->target->project->user_id,
            ]
        );
    }
}
