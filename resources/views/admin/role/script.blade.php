<script>
    let selectPermissions = $('#permissions');
    $(document).ready(function (){

        @if(isset($permissionSelected) && is_array($permissionSelected))
        let values="{{ implode(',',$permissionSelected) }}";
        $.each(values.split(","), function(i,e){
            $("#permissions option[value='" + e + "']").prop("selected", true);
        });
        @endif

        selectPermissions.multiSelect({
            selectableHeader: "<input type='text' class='multi-search-input' autocomplete='off' placeholder='Find ...'>",
            selectionHeader: "<input type='text' class='multi-search-input' autocomplete='off' placeholder='Find ...'>",
            afterInit: function(ms){
                let that = this,
                    $selectableSearch = that.$selectableUl.prev(),
                    $selectionSearch = that.$selectionUl.prev(),
                    selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                    selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

                that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                    .on('keydown', function(e){
                        if (e.which === 40){
                            that.$selectableUl.focus();
                            return false;
                        }
                    });

                that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                    .on('keydown', function(e){
                        if (e.which === 40){
                            that.$selectionUl.focus();
                            return false;
                        }
                    });
            },
            afterSelect: function(){
                this.qs1.cache();
                this.qs2.cache();
            },
            afterDeselect: function(){
                this.qs1.cache();
                this.qs2.cache();
            }


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
