@php
    $isMessageAlignedRight = $message->user_id  !== auth()->id() && $message->user_type !== \Modules\Admin\app\Models\Admin::class || isset($reverse);
@endphp

<div id="message-{{$message->id}}" class="d-flex  @if($isMessageAlignedRight) justify-content-end @else justify-content-start @endif">
    <div class="d-flex gap-2 align-items-start aw-message-item  @if($isMessageAlignedRight) flex-row-reverse @endif">
        <div class="d-flex justify-content-center align-items-center flex-column flex-shrink-0 w-8">
            <img class="w-7 mb-1" src="{{ asset($message->user->avatar ?? 'uploads/admin/avatar.png') }}" alt="{{ $message->user->first_name }}">
            <span class="font-12">{{ $message->user->first_name }}</span>
            <span class="font-12">{{ $message->user->last_name }}</span>
        </div>
        <div class="aw-message-item-text">

            @if($message->replay)
                <div class="replay" data-parent-id="{{ $message->parent_id }}">
                    <p>{{ $message->replay->content }}</p>
                </div>
            @endif

            <p>{{ $message->content }}</p>
            <span class="date mb-2 mt-1 block">{{ $message->created_at->toJalali()->format(formatJalaliDateTime())  }}</span>

            @if($message->attachments->isNotEmpty())
                @foreach($message->attachments as $attachment)
                    @if(in_array($attachment->file_extension,['png','jpg','gif','jpeg']))
                        <div class="d-flex justify-content-between w-full py-1">
                            <a target="_blank" href="{{ route('stream.read',['path'=>str_replace('/','|',$attachment->file_path)]) }}" class="attachment">
                                <div class="w-4 h-4 p-3" style="border-radius: 10px;padding:5px;background-color: rgba(255,255,255,0.06);background-image: url('{{ route('stream.read',['path'=>str_replace('/','|',$attachment->file_path)]) }}');background-size: cover">
                                </div>
                            </a>
                            <div class="d-flex flex-column align-items-end font-12">
                                <span>{{ $attachment->file_name }}</span>
                                <span>{{ formatFileSize($attachment->file_size) }}</span>
                            </div>
                        </div>
                    @elseif(in_array($attachment->file_extension,['mp3','ogg','wav']))
                        <div class="d-flex justify-content-between w-full py-1">
                            <audio controls class="w-100">
                                <source src="{{ route('stream.read',['path'=>str_replace('/','|',$attachment->file_path)]) }}" type="audio/mp3">
                                Your browser does not support the audio tag.
                            </audio>
                        </div>
                    @elseif(in_array($attachment->file_extension,['mp4','mov','avi']))
                        <div class="d-flex justify-content-between w-full py-1">
                            <video controls class="w-100">
                                <source src="{{ route('stream.read',['path'=>str_replace('/','|',$attachment->file_path)]) }}" type="video/mp4">
                                <source src="{{ route('stream.read',['path'=>str_replace('/','|',$attachment->file_path)]) }}" type="video/quicktime">
                                <source src="{{ route('stream.read',['path'=>str_replace('/','|',$attachment->file_path)]) }}" type="video/x-msvideo">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    @else
                        <a target="_blank" href="{{ route('stream.read',['path'=>str_replace('/','|',$attachment->file_path)]) }}" class="attachment">
                            <span class="fal fa-file"></span>
                        </a>
                    @endif
                @endforeach
            @endif

            <div class="d-flex gap-2 action mt-2">
                @if($message->user_id === auth()->id())
                    <button type="button" class="btn-edit" data-id="{{$message->id}}">
                        <span class="fal fa-pen text-warning"></span>
                    </button>
                    <button type="button" class="btn-delete"  data-id="{{$message->id}}">
                        <span class="fal fa-trash text-danger"></span>
                    </button>
                @endif

                <button type="button" class="btn-replay" data-id="{{$message->id}}" data-message="{{str($message->content)->stripTags()->limit(50)}}">
                    <span class="fal fa-reply text-info"></span>
                </button>
            </div>

        </div>

    </div>
</div>