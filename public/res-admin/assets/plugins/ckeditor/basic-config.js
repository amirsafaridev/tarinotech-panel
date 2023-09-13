CKEDITOR.editorConfig = function( config ) {
	config.toolbarGroups = [
        { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
        { name: 'editing', groups: [ 'find', 'selection', 'editing' ] },
        { name: 'styles', groups: [ 'styles' ] },
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
        { name: 'paragraph', groups: [ 'list', 'align', 'bidi', 'paragraph' ] },
        { name: 'colors', groups: [ 'colors' ] },
        { name: 'links', groups: [ 'links' ] },
        { name: 'insert', groups: [ 'insert' ] },
        { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
	];

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript,SpecialChar,Anchor,HorizontalRule';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;h4;h5;h6';

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';

	config.height = 300;
	config.skin = 'kama';
	config.rtl = true;
	config.language = 'fa';


    config.filebrowserUploadUrl = 'http://localhost/teammiri/public/asyiAyiFga/ajax/ckeditor/upload';
    config.filebrowserUploadMethod = 'form';

    config.extraPlugins = [
        'bidi',
        'justify',
        'imageuploader',
    ].join(',');







};

