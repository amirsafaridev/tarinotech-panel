@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',[
        'load'=>[
            \App\Enums\Assets\StyleLoader::Toast(),
            \App\Enums\Assets\StyleLoader::Alert(),
        ]
    ])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">پکیج ها</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.package.index') }}">لیست پکیج ها</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-3">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.package.update',$package->id) }}">
                        @csrf
                        @method('PATCH')

                        <x-admin.select-model identify="base_id" title="پایه پروژه"/>

                        <x-admin.select-model identify="type_id" title="تایپ پروژه"/>

                        <x-admin.input identify="title" title="عنوان" :old="$package->title"/>

                        <x-admin.input identify="price" title="قیمت" :old="$package->finalPrice?->price"/>

                        <x-admin.input identify="minimum_price_percent" title="درصد تخفیف" :old="$package->minimum_price_percent"/>

                        @include('package::admin.part.seo-fields')

                        <x-admin.input identify="change_reason" title="علت تفییر"/>

                        <x-admin.textarea identify="contract_text" title="متن قرارداد"  :old="$contractText"/>

                        <x-admin.textarea identify="contract_attachment" title="پیوست قرارداد"  :old="$package->contract_attachment"/>


                        <x-admin.button title="{{ trans('panel.update') }}"/>

                        <x-admin.button title="{{ trans('panel.delete') }}" type="button" color="danger" on-click="confirmDelete()"/>

                    </form>

                    <form id="deleteItem" action="{{ route('admin.package.destroy',$package->id) }}" method="post" class="form-inline">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            @include('package::admin.price.card.list',['prices'=>$package->prices])
            @include('package::admin.part.history',['package' => $package])
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',[
        'load'=>[
            \App\Enums\Assets\ScriptLoader::Alert(),
             \App\Enums\Assets\ScriptLoader::CKEditor(),
        ],
    ])
    @include('admin.partial.request')
    @include('admin.partial.ckeditor')
    @include('admin.partial.script.global')
    <script>
        $(document).ready(function (){
            activeParentUl('{{ route('admin.package.index') }}');
            CKEDITOR.replace( 'contract_attachment',{height:400});
            CKEDITOR.replace('contract_text', { height: 400 });
        })
    </script>
    @include('package::admin.part.script')

@endsection
