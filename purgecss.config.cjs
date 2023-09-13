module.exports = {
    content: [
        './resources/views/**/*.php',
        './public/res-admin/assets/js/jquery.min.js',
        './public/res-admin/assets/plugins/bootstrap/js/popper.min.js',
        './public/res-admin/assets/plugins/bootstrap/js/bootstrap.min.js',
        './public/res-admin/assets/plugins/sidemenu/sidemenu.js',
        './public/res-admin/assets/plugins/sidebar/sidebar.js',
        './public/res-admin/assets/plugins/p-scroll/perfect-scrollbar.js',
        './public/res-admin/assets/plugins/p-scroll/pscroll.js',
        './public/res-admin/assets/js/sticky.js',
        './public/res-admin/assets/js/custom.js',
        './public/res-admin/assets/plugins/datatable/datatables.min.js',
        './public/res-admin/assets/plugins/lou-multi-select/js/jquery.multi-select.js',
        './public/res-admin/assets/plugins/lou-multi-select/js/jquery.quicksearch.js',
    ],
    css: [
        //'./public/res-admin/assets/css/style.css',
        //'./public/res-admin/assets/plugins/bootstrap/css/bootstrap.rtl.min.css',
        './public/res-admin/assets/font-awesome/css/fontawesome.min.css',
    ],
    //output: './public/res-admin/assets/css/style.purged.css',
    //output: './public/res-admin/assets/plugins/bootstrap/css/bootstrap.rtl.purged.css',
    output: './public/res-admin/assets/font-awesome/css/fontawesome.purged.css',
};
// npx purgecss --config purgecss.config.cjs