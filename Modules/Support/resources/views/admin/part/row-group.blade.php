<div class="aw-chat-item" data-chat-id="{{ $chat->id }}">
    <div class="aw-chat-header"
         data-chat-id="{{ $chat->id }}"
         data-chat-title="{{ $chat->title }}"
         data-chat-logo="{{ asset($chat->logo ) }}"
         data-chat-description="{{ $chat->project->title }}">
        <div class="info">
            <div class="aw-log-container">
                @if($chat->logo)
                    <img src="{{ asset($chat->logo ) }}" alt="{{ asset($chat->title ) }}">
                @endif
            </div>
            <div class="aw-project">
                <h4>
                    <span>{{ $chat->title }}</span>
                    @if($chat->status === \App\Enums\Database\Chat\ChatStatus::Close)
                        <span class="aw-chat-status text-danger">بسته</span>
                    @else
                        <span class="aw-chat-status text-success">باز</span>
                    @endif
                </h4>
                <span>{{ $chat->project->title }}</span>
            </div>
        </div>

        <div class="aw-time-notify">
            @if($chat->users->isNotEmpty())
                @foreach($chat->users as $chatUser)
                    @if($chatUser->user_id === auth()->id() && $chatUser->user_type === \Modules\Admin\app\Models\Admin::class)
                        <span class="notify">{{ $chatUser->unread  }}</span>
                    @endif
                @endforeach
            @endif
            <span>{{ $chat->updated_at->toJalali()->format(formatJalaliDateTime()) }}</span>
        </div>
    </div>

    <div class="aw-chat-user">
        <div class="aw-user">
            @if($chat->users->isNotEmpty())
                @foreach($chat->users as $chatUser)
                    @if(isset($chatUser->user->avatar))
                        <img src="{{ asset($chatUser->user->avatar) }}"/>
                    @else
                        <img src="{{ asset('uploads/admin/avatar.png') }}"/>
                    @endif
                @endforeach
            @endif
        </div>
        <div class="aw-action">
            <a class="aw-action-success" href="{{ route('admin.support.group.edit',$chat->id) }}">
                <span class="fal fa-plus"></span>
            </a>
        </div>
    </div>
</div>