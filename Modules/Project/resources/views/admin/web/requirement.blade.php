@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
       \App\Enums\Assets\StyleLoader::Toast(),
   ]])
    <style>
        button.btn-add , button.btn-remove{
            background-color: transparent;
            border: 0px;
        }
        button.btn-add i, button.btn-remove i{
            font-size: 24px;
        }

        button.btn-add i{
            color: #0b7347;
        }

        button.btn-remove i{
            color: #b31212;
        }
    </style>
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.index') }}">پروژه ها</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.web.index') }}">وب سایت ها</a></li>
                <li class="breadcrumb-item active">نیازمندی ها</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.project.web.requirement.update', $projectId) }}">
                        @csrf
                        @method('PATCH')

                        <x-admin.input
                                identify="representative"
                                title="نماینده کارفرما در جلسه"
                                :old="$requirement->representative ?? ''"/>

                        <x-admin.input
                                identify="color_scheme"
                                title="رنگ بندی سایت"
                                :old="$requirement->color_scheme ?? ''"/>

                        <x-admin.input
                                identify="similar_websites"
                                title="سایت هایی که در زمینه کاری مشابه قرار دارند"
                                :old="$requirement->similar_websites ?? ''"/>

                        <x-admin.input
                                identify="preferred_websites"
                                title="سایت های مورد پسند"
                                :old="$requirement->preferred_websites ?? ''"/>

                        <x-admin.input
                                identify="site_title"
                                title="عنوان سایت"
                                :old="$requirement->site_title ?? ''"/>

                        <x-admin.input
                                identify="design_based_on"
                                title="طراحی برپایه"
                                :old="$requirement->design_based_on ?? ''"/>

                        <x-admin.input
                                identify="menu_titles"
                                title="عناوین که در منو قرار میگیرند"
                                :old="$requirement->menu_titles ?? ''"/>

                        <x-admin.textarea
                                :rows="8"
                                identify="homepage_layout"
                                title="چیدمان صفحه اصلی سایت"
                                :old="$requirement->homepage_layout ?? ''">
                        </x-admin.textarea>

                        <x-admin.textarea
                                :rows="8"
                                identify="website_features"
                                title="امکانات وبسایت"
                                :old="$requirement->website_features ?? ''">
                        </x-admin.textarea>

                        <h5 class="mt-5 mb-5">صفحات داخلی و محتوای آن</h5>
                        <div id="internal-pages-content" class="mb-5">
                            @if(!empty($requirement->internal_pages_content))
                                @foreach($requirement->internal_pages_content as $page)
                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            <x-admin.input
                                                    identify="internal_pages_content[{{ $loop->index }}][page]"
                                                    placeholder="اسم صفحه"
                                                    :w-full="true"
                                                    :old="$page['page'] ?? ''"/>
                                        </div>
                                        <div class="col-lg-6 d-flex align-items-end gap-2">
                                            <x-admin.input
                                                    identify="internal_pages_content[{{ $loop->index }}][content]"
                                                    placeholder="محتوای داخل صفحه"
                                                    :w-full="true"
                                                    :old="$page['content'] ?? ''"/>
                                            <div class="d-flex mb-3 gap-2">
                                                <button type="button" class="btn-add add-row"><i class="fa fa-plus-circle"></i></button>
                                                <button type="button" class="btn-remove remove-row"><i class="fa fa-minus-circle"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <h5 class="mb-5">تفاوت ها با پیوست قرارداد</h5>
                        <div id="contract-differences" class="mb-5">
                            @if(!empty($requirement->contract_differences))
                                @foreach($requirement->contract_differences as $difference)
                                    <div class="row">
                                        <div class="col-12 d-flex align-items-end gap-2">
                                            <x-admin.input
                                                    identify="contract_differences[{{ $loop->index }}][content]"
                                                    :w-full="true"
                                                    :old="$difference['content']"/>
                                            <div class="d-flex mb-3 gap-2">
                                                <button type="button" class="btn-add add-row"><i class="fa fa-plus-circle"></i></button>
                                                <button type="button" class="btn-remove remove-row"><i class="fa fa-minus-circle"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="alert alert-danger">
                            <p>کارفرمای محترم این فرم با توجه به صحبت ها و نیاز های شما در جلسه نیازسنجی تکمیل شده لطفا تمامی موارد رو با دقت مطالعه و درصورت وجود مغایرت با نیاز و خواسته های شما اعلام بفرمایید. لازم به ذکر است مبنای اجرای سایت این فایل خواهد بود.</p>
                        </div>

                        <x-admin.checkbox
                                identify="final_decision"
                                description="اتمام حجت"
                                :checked="$requirement->final_decision ?? false"/>

                        <br>

                        <x-admin.button title="به روزرسانی"/>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.request')
    @include('project::admin.web.part.script-requirement')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.project.web.index') }}');
        })
    </script>
@endsection
