<div id="message-{{$message->id}}" class="d-flex  @if($message->user_id  !== auth()->id()) justify-content-end @else justify-content-start @endif">
    <div class="d-flex gap-2 align-items-start aw-message-item  @if($message->user_id  !== auth()->id()) flex-row-reverse @endif">
        <div class="d-flex justify-content-center align-items-center flex-column flex-shrink-0 w-8">
            <img class="w-7 mb-1" src="{{ asset($message->user->avatar ?? 'uploads/admin/avatar.png') }}" alt="{{ $message->user->first_name }}">
            <span class="font-12">{{ $message->user->first_name }}</span>
            <span class="font-12">{{ $message->user->last_name }}</span>
        </div>
        <div class="aw-message-item-text">
            <p>{{ $message->content }}</p>
            <spac class="date">{{ $message->created_at->toJalali()->format(formatJalaliDateTime())  }}</spac>

            @if($message->attachments->isNotEmpty())
                <div class="d-flex flex-wrap mt-2 gap-2">
                    @foreach($message->attachments as $attachment)
                        @if(in_array($attachment->file_extension,['png','jpg','gif','jpeg']))
                            <a target="_blank" href="{{ route('admin.chat.attachment.steam.read',['path'=>str_replace('/','|',$attachment->file_path)]) }}" class="attachment">
                                <div class="w-6 h-6 p-3" style="border-radius: 10px;padding:5px;background-color: rgba(255,255,255,0.06);background-image: url('{{ route('admin.chat.attachment.steam.read',['path'=>str_replace('/','|',$attachment->file_path)]) }}');background-size: cover">
                                </div>
                            </a>
                        @else
                            <a target="_blank" href="{{ route('admin.chat.attachment.steam.read',['path'=>str_replace('/','|',$attachment->file_path)]) }}" class="attachment">
                                <span class="fal fa-file"></span>
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif

        </div>
        <div class="d-flex flex-column gap-2 action">
            <button type="button">
                <span class="fal fa-edit text-warning"></span>
            </button>
            <button type="button">
                <span class="fal fa-trash text-danger"></span>
            </button>
            <button type="button">
                <span class="fal fa-reply text-info"></span>
            </button>
        </div>
    </div>
</div>