<script>
    function setupInternalPagesContent() {
        const $internalPagesContent = $('#internal-pages-content');

        function createRow(index) {
            return `
        <div class="row" style="display: none;">
            <div class="col-12 col-lg-6">
                <x-admin.input
                    identify="internal_pages_content[${index}][page]"
                    placeholder="اسم صفحه"
                    :w-full="true"/>
            </div>
            <div class="col-lg-6 d-flex align-items-end gap-2">
                <x-admin.input
                    identify="internal_pages_content[${index}][content]"
                    placeholder="محتوای داخل صفحه"
                    :w-full="true"/>
                <div class="d-flex mb-3 gap-2">
                    <button type="button" class="btn-add add-row"><i class="fa fa-plus-circle"></i></button>
                    <button type="button" class="btn-remove remove-row"><i class="fa fa-minus-circle"></i></button>
                </div>
            </div>
        </div>`;
        }

        function addRow() {
            const index = $internalPagesContent.children('.row').length; // Calculate the current index
            const $newRow = $(createRow(index)); // Create new row with the current index
            $internalPagesContent.append($newRow);
            $newRow.fadeIn(300);
            $internalPagesContent.find('.row:first .remove-row').hide(); // Hide the remove button for the first row
        }

        $internalPagesContent.on('click', '.add-row', function () {
            addRow();
        });

        $internalPagesContent.on('click', '.remove-row', function () {
            $(this).closest('.row').fadeOut(300, function () {
                $(this).remove();
            });
        });

        $internalPagesContent.find('.row:first .remove-row').hide();

        if ($internalPagesContent.children('.row').length === 0) {
            addRow();
        }
    }


    function setupContractDifferences() {
        const $contractDifferences = $('#contract-differences');

        function createRow(index) {
            return `
        <div class="row" style="display: none;">
            <div class="col-12 d-flex align-items-end gap-2">
                <x-admin.input
                    identify="contract_differences[${index}][content]"
                    :w-full="true"/>
                <div class="d-flex mb-3 gap-2">
                    <button type="button" class="btn-add add-row"><i class="fa fa-plus-circle"></i></button>
                    <button type="button" class="btn-remove remove-row"><i class="fa fa-minus-circle"></i></button>
                </div>
            </div>
        </div>`;
        }

        function addRow() {
            const index = $contractDifferences.children('.row').length;
            const $newRow = $(createRow(index));
            $contractDifferences.append($newRow);
            $newRow.fadeIn(300);
            $contractDifferences.find('.row:first .remove-row').hide();
        }

        $contractDifferences.on('click', '.add-row', function () {
            addRow();
        });

        $contractDifferences.on('click', '.remove-row', function () {
            $(this).closest('.row').fadeOut(300, function () {
                $(this).remove();
            });
        });

        $contractDifferences.find('.row:first .remove-row').hide();

        if ($contractDifferences.children('.row').length === 0) {
            addRow();
        }
    }


    $(document).ready(function () {
        setupInternalPagesContent();
        setupContractDifferences();
    });

</script>