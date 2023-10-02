<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Database\SampleMessage\MessageType;
use App\Enums\General\BtnType;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AutoMessage\UpdateRequest;
use App\Models\SampleMessage;
use DB;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AutoMessageController extends Controller
{
    public function index()
    {
        $title = 'پیام های خودکار';
        $routeData = route('admin.auto-message.data');
        $selects = ['id', 'title', 'created_at'];

        return view('admin.auto_message.index', compact('title', 'routeData', 'selects'));
    }

    public function data()
    {
        try {
            $sampleMessages = SampleMessage::query()
                ->where('type', MessageType::AutoSend);

            return DataTables::of($sampleMessages)
                ->editColumn('created_at', function (SampleMessage $sampleMessage) {
                    return $sampleMessage->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function (SampleMessage $sampleMessage) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.auto-message.edit', $sampleMessage->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function edit(SampleMessage $sampleMessage)
    {
        $title = 'پیام های خودکار - ویرایش';
        $routeUpdate = route('admin.auto-message.update', $sampleMessage->id);

        return view('admin.auto_message.edit', compact('title', 'routeUpdate', 'sampleMessage'));
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

    protected function itemProvider(Request $request): array
    {
        $item['message'] = $request->get('message');
        $item['type'] = MessageType::AutoSend;

        return $item;
    }
}
