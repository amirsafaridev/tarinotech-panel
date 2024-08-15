<script>

    const bases = @json($bases);
    const baseSelect = $('#base_id');
    const typeSelect = $('#type_id');
    const price = $('#price');
    const minimumPricePercent = $('#minimum_price_percent');

    // Seo
    const seoContainer = $('#seo_container');
    const seoKeywordsCount = $('#seo_keywords_count');
    const seoAgreementDuration = $('#seo_agreement_duration');
    const seoAmountContent = $('#seo_amount_content');

    $(document).ready(function () {
        initializeSelects();

        @if(isset($package) && $package)
            setBaseAndType(parseInt('{{ $package->type_id }}'));
        @endif

        applyTypeInput();
    });

    function applyTypeInput(){
        makeInputPrice(price);
        makeInputPrice(minimumPricePercent);
        makeInputPrice(seoKeywordsCount);
        makeInputPrice(seoAgreementDuration);
        makeInputPrice(seoAmountContent);
    }

    function initializeSelects() {
        populateBaseSelect(bases, baseSelect);
        baseSelect.change(function () {
            const selectedBaseId = $(this).val();
            updateTypeSelect(bases, selectedBaseId, typeSelect);
            previewSeoContainer(selectedBaseId);
        });
    }

    function previewSeoContainer(selectedBaseId){
        if(parseInt(selectedBaseId) === 2){
            seoContainer.fadeIn();
        }
        else{
            seoContainer.fadeOut();
        }
    }

    function populateBaseSelect(bases, baseSelect) {
        $.each(bases, function (index, base) {
            baseSelect.append(new Option(base.title, base.id));
        });
    }

    function updateTypeSelect(bases, baseId, typeSelect) {
        typeSelect.empty().append(new Option('انتخاب نوع', ''));

        if (baseId) {
            const selectedBase = bases.find(base => parseInt(base.id) === parseInt(baseId));

            if (selectedBase && selectedBase.types.length > 0) {
                $.each(selectedBase.types, function (index, type) {
                    typeSelect.append(new Option(type.title, type.id));
                });
            }
        }
    }

    function setBaseAndType(typeId) {
        let foundBase = null;

        $.each(bases, function (index, base) {
            const foundType = base.types.find(type => parseInt(type.id) === parseInt(typeId));
            if (foundType) {
                foundBase = base;
                return false;
            }
        });

        if (foundBase) {
            baseSelect.val(foundBase.id).trigger('change');
            setTimeout(function() {
                typeSelect.val(typeId);
            }, 100);
        }
    }
</script>
