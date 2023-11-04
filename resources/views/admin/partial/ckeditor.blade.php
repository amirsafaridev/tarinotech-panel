<script>
    $(document).ready(function (){
        CKEDITOR.config.customConfig = '{{ asset('res-admin/assets/plugins/ckeditor/config.js'.'?update='.time()) }}';

        CKEDITOR.on( 'instanceCreated', function ( event, data ) {
            let editor = event.editor;
            editor.name = 'ckeditor' + Math.floor(Math.random() * 50000) + 1;

            event.editor.on('contentDom', function() {
                event.editor.document.on('keyup', function(event) {
                    updateEditors();
                });
            });
        });

        $('form.requestForm button[type="submit"]').click(function (event){
            event.preventDefault();
            if($(this).attr('name')==='create_new'){
                $('form.requestForm #create_new').prop('checked', true);
            }
            let myPromise = new Promise(function(myResolve) {
                if (typeof CKEDITOR !== 'undefined') {
                    for (let instance in CKEDITOR.instances ){
                        CKEDITOR.instances[instance].updateElement();
                    }
                }
                setTimeout(function (){
                    myResolve();
                },300)
            });

            myPromise.then(
                function() {
                    $('form.requestForm').submit();
                    setTimeout(function (){
                        $('form.requestForm #create_new').prop('checked', false);
                    },200)
                }
            );
        });

    });

    function updateEditors(){
        if (typeof CKEDITOR !== 'undefined') {
            for (let instance in CKEDITOR.instances ){
                CKEDITOR.instances[instance].updateElement();
            }
        }
    }
    function findCkeditorId(targetDom) {
        return $(targetDom[0].nextElementSibling).attr('id').replace('cke_','');
    }
</script>
