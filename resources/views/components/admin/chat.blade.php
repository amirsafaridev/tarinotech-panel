<div class="card">
    <div class="card-body">
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center" id="message-header-container">
                <img class="w-7 h-7 rounded m-logo d-none" src="" alt="">
                <div class="ms-3 w-100 d-flex justify-content-between">
                   <div>
                       <h5 class="card-title m-title">یک گفتگو را انتخاب کنید</h5>
                       <h6 class="card-subtitle mb-0 text-muted m-description">نمایش گفتگو</h6>
                   </div>
                    <div>
                        @if(isset($chat))
                            @if($chat->type === \App\Enums\Database\Chat\ChatType::Public)
                                <a class="btn btn-success" href="{{ route('admin.support.notify.edit',$chat->id) }}">ویرایش کانال</a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            <hr class="hr-message">

            <div class="aw-message" id="message-container"></div>

            <hr class="hr-message">

            <div class="aw-message-action">
                <form method="post" id="message-form" action="{{ route('admin.chat.message.store') }}">
                    @csrf
                    <x-admin.input identify="chat_id" type="hidden"/>

                    <x-admin.input identify="parent_id" type="hidden"/>

                    <div id="replay-container"></div>

                    <div class="position-relative">
                        <span class="position-absolute top-100 start-50 translate-middle">
                            <button class="btn btn-sm btn-danger d-none" id="btn-cancel-edit" type="button">انصراف</button>
                        </span>
                        <x-admin.textarea identify="message" placeholder="پیام خود ار بنویسید" />
                    </div>

                    <div class="attachment" id="attachment-container"></div>

                    <div>
                        <x-admin.input type="file" identify="file-attachment" id="file-attachment"/>
                    </div>

                    <div class="action mt-2">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#sample-message-modal" class="btn btn-pill btn-icon btn-gray">
                            <span class="fal mt-1 fa-message"></span>
                        </button>

                        <button type="button" id="btn-microphone" class="btn btn-pill btn-icon btn-danger">
                            <span class="fal mt-1 fa-microphone"></span>
                        </button>
                        <button type="submit" id="btn-message-send" class="btn btn-pill btn-icon btn-success">
                            <span class="fal mt-1 fa-send fa-rotate-180"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Message Ready -->
<div class="modal fade" id="sample-message-modal" tabindex="-1" aria-labelledby="sample-message-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sample-message-modal-label">پیام های آماده</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <x-admin.input identify="search_sample_message" placeholder="جستجو پیام ها"/>
                    <div id="sample-message-container" class="row">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>
@push('styles')
    <link rel="stylesheet" href="{{ asset('res-admin/assets/css/chat.css') }}">
@endpush

@push('scripts')
    @include('admin.partial.loader.script',['load'=>[
       \App\Enums\Assets\ScriptLoader::Toast(),
       \App\Enums\Assets\ScriptLoader::AjaxForm(),
       \App\Enums\Assets\ScriptLoader::Recorder(),
       \App\Enums\Assets\ScriptLoader::Alert(),
    ]])
    @include('scripts.admin.chat')
@endpush
