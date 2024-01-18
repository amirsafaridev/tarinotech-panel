<?php

namespace Modules\Support\app\Http\Controllers\Admin;

use App\Enums\Database\Chat\ChatType;
use Modules\Support\app\Models\Chat;

class NotifyController
{
    const INDEX_TITLE = 'اطلاعیه ها';

    public function index()
    {
        $chat = Chat::query()
            ->where('type', ChatType::Public)
            ->firstOrFail();

        $title = self::INDEX_TITLE;

        return view('support::admin.notify.index', compact('title', 'chat'));

    }
}
