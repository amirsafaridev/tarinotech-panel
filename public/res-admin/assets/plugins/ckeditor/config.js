/**
 * @license Copyright (c) 2003-2023, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function (config) {
    // Set right-to-left text direction and language to Persian.
    config.rtl = true;
    config.language = 'fa';

    // Enable source dialog plugin
    config.extraPlugins = 'sourcedialog,table';

    // Configure source dialog settings
    config.sourceDialog_backgroundColor = '#f1f1f1';
    config.sourceDialog_height = '400px';
    config.sourceDialog_width = '600px';

    // Toolbar groups arrangement, optimized for a single toolbar row.
    config.toolbarGroups = [
        { name: 'document', groups: ['mode', 'document', 'doctools'] },
        { name: 'clipboard', groups: ['clipboard', 'undo'] },
        { name: 'editing', groups: ['find', 'selection', 'spellchecker'] },
        { name: 'forms' },
        { name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
        { name: 'paragraph', groups: ['list', 'blocks', 'align', 'bidi'] },
        { name: 'links' },
        { name: 'insert' },
        { name: 'styles' },
        { name: 'tools' },
        { name: 'others' }
    ];

    // Remove unnecessary buttons.
    config.removeButtons = 'Undo,Redo,Anchor,Underline,Strike,Subscript,Superscript';

    // Simplify dialog windows.
    config.removeDialogTabs = 'link:advanced';

    // Configure the toolbar with source control
    config.toolbar = [
        {
            name: 'document',
            items: [
                'Source',           // Add Source button at the beginning
                'Sourcedialog',     // Add Source dialog button
                '-',
                'Save',
                'NewPage',
                'Preview',
                'Print',
                '-',
                'Templates'
            ]
        },
        { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
        { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'] },
        { name: 'forms', items: ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'] },
        { name: 'basicstyles', items: ['Bold', 'Italic', 'Strike', 'RemoveFormat'] },
        { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'] },
        { name: 'links', items: ['Link', 'Unlink', 'Anchor'] },
        { name: 'insert', items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe'] },
        { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
        { name: 'colors', items: ['TextColor', 'BGColor'] },
        { name: 'tools', items: ['Maximize', 'ShowBlocks'] },
        { name: 'others', items: ['-'] }
    ];

    // Configure source editing
    config.allowedContent = true; // Disable content filtering
    config.height = '400px';      // Set editor height

    // Add keyboard shortcuts for source mode
    config.keystrokes = [
        [ CKEDITOR.CTRL + CKEDITOR.SHIFT + 83 /*S*/, 'sourcedialog' ] // Ctrl+Shift+S
    ];
};
