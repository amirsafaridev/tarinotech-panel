<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Database\SampleMessage\MessageType;
use App\Enums\General\BtnType;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SampleMessage\StoreRequest;
use App\Http\Requests\Admin\SampleMessage\UpdateRequest;
use App\Models\SampleMessage;
use DB;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SampleMessageController extends Controller
{
    public function index()
    {
        $title = 'پیام های آماده';
        $routeData = route('admin.sample-message.data');
        $selects = ['id', 'title', 'created_at'];

        return view('admin.sample_message.index', compact('title', 'routeData', 'selects'));
    }

    public function data()
    {
        try {
            $sampleMessages = SampleMessage::query()
                ->where('type', MessageType::ReadyMessage);

            return DataTables::of($sampleMessages)
                ->editColumn('created_at', function (SampleMessage $sampleMessage) {
                    return $sampleMessage->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function (SampleMessage $sampleMessage) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.sample-message.edit', $sampleMessage->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function create()
    {
        $title = 'پیام های آماده - نوع جدید';
        $routeStore = route('admin.sample-message.store');

        return view('admin.sample_message.create', compact('title', 'routeStore'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $item = $this->itemProvider($request);
            SampleMessage::create($item);
            DB::commit();

            return response()->json([
                'result' => 'success',
                'message' => trans('panel.success_store'),
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);

            return response()->json([
                'result' => 'exception',
                'message' => trans('panel.error_store'),
            ], 500);
        }
    }

    public function edit(SampleMessage $sampleMessage)
    {
        $title = 'پیام های آماده - ویرایش';
        $routeUpdate = route('admin.sample-message.update', $sampleMessage->id);
        $routeDestroy = route('admin.sample-message.destroy', $sampleMessage->id);

        return view('admin.sample_message.edit', compact('title', 'routeUpdate', 'routeDestroy', 'sampleMessage'));
    }

    public function update(UpdateRequest $request, SampleMessage $sampleMessage)
    {
        try {
            DB::beginTransaction();
            $item = $this->itemProvider($request);
            $sampleMessage->update($item);
            DB::commit();

            return response()->json([
                'result' => 'success',
                'message' => trans('panel.success_update'),
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);

            return response()->json([
                'result' => 'exception',
                'message' => trans('panel.error_update'),
            ], 500);
        }
    }

    public function destroy(SampleMessage $sampleMessage)
    {
        try {
            $sampleMessage->delete();

            return redirect(route('admin.sample-message.index'))->with('success', trans('panel.success_delete'));
        } catch (Exception $e) {
            report($e);

            return redirect(route('admin.sample-message.index'))->with('danger', trans('panel.error_delete'));
        }
    }

    protected function itemProvider(Request $request): array
    {
        $item['title'] = $request->input('title');
        $item['message'] = $request->input('message');
        $item['type'] = MessageType::ReadyMessage;

        return $item;
    }
}
