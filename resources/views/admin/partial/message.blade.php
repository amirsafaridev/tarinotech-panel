@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <span class="alert-inner--icon"><i class="fe fe-thumbs-up"></i></span>
        <span class="alert-inner--text">{{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <span class="alert-inner--icon"><i class="fe fe-info"></i></span>
        <span class="alert-inner--text">{{ session('warning') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
    </div>
@endif

@if (session('danger'))
    <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
        <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
        <span class="alert-inner--text">{{ session('danger') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
    </div>
@endif
