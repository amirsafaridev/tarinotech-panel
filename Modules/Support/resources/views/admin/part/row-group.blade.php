<div class="aw-chat-item" data-chat-id="{{ $chat->id }}">
    <div class="aw-chat-header">
        <div class="aw-project">
            <h4>{{ $chat->title }}</h4>
            <span>{{ $chat->project->title }}</span>
        </div>
        <div class="aw-time-notify">
            <span class="notify">1</span>
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