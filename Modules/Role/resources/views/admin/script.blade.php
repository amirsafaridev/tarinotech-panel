<script>
    let selectPermissions = $('#permissions');
    $(document).ready(function (){

        @if(isset($permissionSelected) && is_array($permissionSelected))
        let values="{{ implode(',',$permissionSelected) }}";
        $.each(values.split(","), function(i,e){
            //$("#permissions option[value='" + e + "']").prop("selected", true);
        });
        @endif

        selectPermissions.multiSelect({
            selectableHeader: "<input type='text' class='multi-search-input' autocomplete='off' placeholder='جستجو ...'>",
            selectionHeader: "<input type='text' class='multi-search-input' autocomplete='off' placeholder='جستجو ...'>",


        });

        $('#btn-select').click(function(){
            selectPermissions.multiSelect('select_all');
            return false;
        });
        $('#btn-de-select').click(function(){
            selectPermissions.multiSelect('deselect_all');
            return false;
        });
    });
</script>
