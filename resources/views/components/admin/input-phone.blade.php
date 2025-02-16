<div class="mb-3">
    @if ($type !== 'hidden' && $title)
        <label for="{{ $identify }}-phone" class="form-label">{{ $title }}</label>
    @endif
    <input type="{{ $type }}"
           class="form-control @if ($isSmall) form-control-sm @endif @if(count($addClass)){{ implode(' ',$addClass) }}@endif"
           id="{{ $identify }}-phone"
           @if ($placeholder)  placeholder="{{ $placeholder }}" @endif
           @if (!is_null($old))  value="{{ $old }}" @endif
           @if ($disabled) disabled @endif
           @if ($readOnly) readonly @endif
           @if ($isDatePicker) data-jdp @endif
    />
        <input type="hidden" id="{{ $identify }}"  name="{{ $identify }}">
    @if ($description)
        <p class="form-help">{{ $description }}</p>
    @endif
</div>

@push('styles')
    @include('admin.partial.loader.style',['load'=>[
        \App\Enums\Assets\StyleLoader::CellPhone(),
    ]])
@endpush

@push('scripts')
    @include('admin.partial.loader.script',['load'=>[
       \App\Enums\Assets\ScriptLoader::CellPhone(),
   ]])

    <script>
        $(document).ready(function() {
            const input = $("#{{ $identify }}-phone");
            const intlTelInputInstance = window.intlTelInput(input[0], {
                initialCountry: "ir",
                autoHideDialCode: false,
                nationalMode: false,
                formatOnDisplay: false,
                separateDialCode: false,
                searchPlaceholder: 'جستجو',
                utilsScript: "{{ asset('res-admin/assets/plugins/intlTelInput/js/utils.js') }}"
            });

            function updateFullPhone() {
                const fullNumber = intlTelInputInstance.getNumber();
                $("#{{ $identify }}").val(fullNumber);
            }

            // Allow only numeric input
            input.on("keypress", function(e) {
                if (e.which < 48 || e.which > 57) {
                    e.preventDefault();
                }
            });

            // Event listener for country change
            input.on("countrychange", updateFullPhone);

            // Event listener for typing in the phone number
            input.on("input", updateFullPhone);

            setTimeout(()=>{
                updateFullPhone();
            },100)
        });

    </script>
@endpush
