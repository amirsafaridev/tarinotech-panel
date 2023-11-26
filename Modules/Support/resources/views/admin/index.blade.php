@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[\App\Enums\Assets\StyleLoader::DataTable()]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-5">
            <div class="card">
                <div class="main-content-app pt-0">
                    <div class="main-content-body main-content-body-chat h-100">
                        <div class="main-chat-header pt-3 d-block d-sm-flex">
                            <div class="main-img-user online">
                                <img alt="avatar" src="../assets/images/users/1.jpg">
                            </div>
                            <div class="main-chat-msg-name mt-2">
                                <h6>Saul Goodmate</h6>
                                <span class="dot-label bg-success"></span>
                                <small class="me-3">online</small>
                            </div>
                            <nav class="nav">
                                <div class="">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search ...">
                                        <span class="input-group-text btn bg-white text-muted border-start-0">
                    <i class="fe fe-search"></i>
                  </span>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <a class="nav-link" href="" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="fe fe-more-horizontal"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="javascript:void(0)">
                                            <i class="fe fe-phone-call me-1"></i> Phone Call </a>
                                        <a class="dropdown-item" href="javascript:void(0)">
                                            <i class="fe fe-video me-1"></i> Video Call </a>
                                        <a class="dropdown-item" href="javascript:void(0)">
                                            <i class="fe fe-user-plus me-1"></i> Add Contact </a>
                                        <a class="dropdown-item" href="javascript:void(0)">
                                            <i class="fe fe-trash-2 me-1"></i> Delete </a>
                                    </div>
                                </div>
                            </nav>
                        </div>
                        <!-- main-chat-header -->
                        <div class="main-chat-body flex-2" id="ChatBody">
                            <div class="content-inner">
                                <label class="main-chat-time">
                                    <span>2 days ago</span>
                                </label>
                                <div class="media flex-row-reverse chat-right">
                                    <div class="main-img-user online">
                                        <img alt="avatar" src="../assets/images/users/21.jpg">
                                    </div>
                                    <div class="media-body">
                                        <div class="main-msg-wrapper"> Nulla consequat massa quis enim. Donec pede justo, fringilla vel... </div>
                                        <div class="main-msg-wrapper"> rhoncus ut, imperdiet a, venenatis vitae, justo... </div>
                                        <div>
                                            <span>9:48 am</span>
                                            <a href="">
                                                <i class="icon ion-android-more-horizontal"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="media chat-left">
                                    <div class="main-img-user online">
                                        <img alt="avatar" src="../assets/images/users/1.jpg">
                                    </div>
                                    <div class="media-body">
                                        <div class="main-msg-wrapper"> Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. </div>
                                        <div>
                                            <span>9:32 am</span>
                                            <a href="">
                                                <i class="icon ion-android-more-horizontal"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="media flex-row-reverse chat-right">
                                    <div class="main-img-user online">
                                        <img alt="avatar" src="../assets/images/users/21.jpg">
                                    </div>
                                    <div class="media-body">
                                        <div class="main-msg-wrapper"> Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor </div>
                                        <div class="main-msg-wrapper">
                    <span class="text-dark">
                      <span>
                        <i class="fa fa-image fs-14 text-muted pe-2"></i>
                      </span>
                      <span class="fs-14 mt-1"> Image_attachment.jpg </span>
                      <i class="fe fe-download mt-3 text-muted ps-2"></i>
                    </span>
                                        </div>
                                        <div>
                                            <span>11:22 am</span>
                                            <a href="">
                                                <i class="icon ion-android-more-horizontal"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <label class="main-chat-time">
                                    <span>Yesterday</span>
                                </label>
                                <div class="media chat-left">
                                    <div class="main-img-user online">
                                        <img alt="avatar" src="../assets/images/users/1.jpg">
                                    </div>
                                    <div class="media-body">
                                        <div class="main-msg-wrapper"> Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. </div>
                                        <div>
                                            <span>9:32 am</span>
                                            <a href="">
                                                <i class="icon ion-android-more-horizontal"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="media flex-row-reverse chat-right">
                                    <div class="main-img-user online">
                                        <img alt="avatar" src="../assets/images/users/21.jpg">
                                    </div>
                                    <div class="media-body">
                                        <div class="main-msg-wrapper"> Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. </div>
                                        <div class="main-msg-wrapper"> Nullam dictum felis eu pede mollis pretium </div>
                                        <div>
                                            <span>9:48 am</span>
                                            <a href="">
                                                <i class="icon ion-android-more-horizontal"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <label class="main-chat-time">
                                    <span>Today</span>
                                </label>
                                <div class="media chat-left">
                                    <div class="main-img-user online">
                                        <img alt="avatar" src="../assets/images/users/1.jpg">
                                    </div>
                                    <div class="media-body">
                                        <div class="main-msg-wrapper"> Maecenas tempus, tellus eget condimentum rhoncus </div>
                                        <div class="main-msg-wrapper">
                                            <img alt="avatar" class="w-10 h-10" src="../assets/images/media/3.jpg">
                                            <img alt="avatar" class="w-10 h-10" src="../assets/images/media/4.jpg">
                                            <img alt="avatar" class="w-10 h-10" src="../assets/images/media/5.jpg">
                                        </div>
                                        <div>
                                            <span>10:12 am</span>
                                            <a href="">
                                                <i class="icon ion-android-more-horizontal"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="media flex-row-reverse chat-right">
                                    <div class="main-img-user online">
                                        <img alt="avatar" src="../assets/images/users/21.jpg">
                                    </div>
                                    <div class="media-body">
                                        <div class="main-msg-wrapper"> Maecenas tempus, tellus eget condimentum rhoncus </div>
                                        <div class="main-msg-wrapper"> Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. </div>
                                        <div>
                                            <span>09:40 am</span>
                                            <a href="">
                                                <i class="icon ion-android-more-horizontal"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="main-chat-footer">
                            <input class="form-control" placeholder="Type your message here..." type="text">
                            <a class="nav-link" data-bs-toggle="tooltip" href="" title="Attach a File">
                                <i class="fe fe-paperclip"></i>
                            </a>
                            <button type="button" class="btn btn-icon  btn-primary brround">
                                <i class="fa fa-paper-plane-o"></i>
                            </button>
                            <nav class="nav"></nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
            <div class="card">
                <div class="main-content-app pt-0 main-chat-2">
                    <div class="main-content-left main-content-left-chat">
                        <div class="card-body d-flex">
                            <div class="main-img-user online">
                                <img alt="avatar" src="../assets/images/users/21.jpg">
                            </div>
                            <div class="main-chat-msg-name">
                                <h6>Percy Kewshun</h6>
                                <span class="dot-label bg-success"></span>
                                <small class="me-3">Available</small>
                            </div>
                            <nav class="nav ms-auto">
                                <div class="dropdown">
                                    <a class="nav-link text-muted fs-20" href="" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="fe fe-more-horizontal"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="javascript:void(0)">
                                            <i class="fe fe-user me-1"></i> Profile </a>
                                        <a class="dropdown-item" href="javascript:void(0)">
                                            <i class="fe fe-edit me-1"></i> Edit </a>
                                        <a class="dropdown-item" href="javascript:void(0)">
                                            <i class="fe fe-users me-1"></i> New Group </a>
                                        <a class="dropdown-item" href="javascript:void(0)">
                                            <i class="fe fe-settings me-1"></i> Settings </a>
                                        <a class="dropdown-item" href="javascript:void(0)">
                                            <i class="fe fe-trash-2 me-1"></i> Delete </a>
                                    </div>
                                </div>
                            </nav>
                        </div>
                        <!-- main-chat-header -->
                        <div class="card-body ">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ...">
                                <span class="input-group-text btn btn-primary">Search</span>
                            </div>
                            <div class="main-chat-contacts-wrapper d-none d-sm-block">
                                <div class="lSSlideOuter main-chat-contacts-slider">
                                    <div class="main-chat-contacts-slider lSSlideWrapper usingCss">
                                        <div class="main-chat-contacts lightSlider lsGrab lSSlide ps-0" id="chatActiveContacts" style="width: 464px; height: 59px; padding-bottom: 0%;">
                                            <div class="lslide active">
                      <span class="avatar avatar-md bradius cover-image" data-bs-image-src="../assets/images/users/5.jpg" style="background: url(&quot;../assets/images/users/5.jpg&quot;) center center;">
                        <span class="avatar-status bg-secondary"></span>
                      </span>
                                                <small>Ariana</small>
                                            </div>
                                            <div class="lslide">
                      <span class="avatar avatar-md bradius cover-image" data-bs-image-src="../assets/images/users/9.jpg" style="background: url(&quot;../assets/images/users/9.jpg&quot;) center center;">
                        <span class="avatar-status bg-red"></span>
                      </span>
                                                <small>Monino</small>
                                            </div>
                                            <div class="lslide">
                      <span class="avatar avatar-md bradius cover-image" data-bs-image-src="../assets/images/users/6.jpg" style="background: url(&quot;../assets/images/users/6.jpg&quot;) center center;">
                        <span class="avatar-status bg-green"></span>
                      </span>
                                                <small>Reynante</small>
                                            </div>
                                            <div class="lslide">
                      <span class="avatar avatar-md bradius cover-image" data-bs-image-src="../assets/images/users/7.jpg" style="background: url(&quot;../assets/images/users/7.jpg&quot;) center center;">
                        <span class="avatar-status bg-yellow"></span>
                      </span>
                                                <small>Labares</small>
                                            </div>
                                            <div class="lslide">
                      <span class="avatar avatar-md bradius cover-image" data-bs-image-src="../assets/images/users/5.jpg" style="background: url(&quot;../assets/images/users/5.jpg&quot;) center center;">
                        <span class="avatar-status bg-secondary"></span>
                      </span>
                                                <small>Rolando</small>
                                            </div>
                                            <div class="lslide">
                      <span class="avatar avatar-md bradius cover-image" data-bs-image-src="../assets/images/users/9.jpg" style="background: url(&quot;../assets/images/users/9.jpg&quot;) center center;">
                        <span class="avatar-status bg-red"></span>
                      </span>
                                                <small>Paloso</small>
                                            </div>
                                            <div class="lslide">
                      <span class="avatar avatar-md bradius cover-image" data-bs-image-src="../assets/images/users/6.jpg" style="background: url(&quot;../assets/images/users/6.jpg&quot;) center center;">
                        <span class="avatar-status bg-green"></span>
                      </span>
                                                <small>Maricel</small>
                                            </div>
                                            <div class="lslide">
                      <span class="avatar avatar-md bradius cover-image" data-bs-image-src="../assets/images/users/7.jpg" style="background: url(&quot;../assets/images/users/7.jpg&quot;) center center;">
                        <span class="avatar-status bg-yellow"></span>
                      </span>
                                                <small>Villalon</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- main-active-contacts -->
                            </div>
                        </div>
                        <div class="main-chat-list flex-2 ">
                            <div class="main-chat-list tab-pane">
                                <a class="media new border-top-0" href="javascript:void(0)">
                                    <div class="main-img-user online">
                                        <img alt="" src="../assets/images/users/5.jpg">
                                    </div>
                                    <div class="media-body">
                                        <div class="media-contact-name">
                                            <span>Raymart Santiago</span>
                                            <span>10 min</span>
                                        </div>
                                        <p> Hey! there I'm available </p>
                                    </div>
                                </a>
                                <a class="media new" href="javascript:void(0)">
                                    <div class="main-img-user">
                                        <img alt="" src="../assets/images/users/6.jpg">
                                        <span>3</span>
                                    </div>
                                    <div class="media-body">
                                        <div class="media-contact-name">
                                            <span>Ariana Monino</span>
                                            <span>30 min</span>
                                        </div>
                                        <p>Good Morning</p>
                                    </div>
                                </a>
                                <a class="media selected" href="javascript:void(0)">
                                    <div class="main-img-user online">
                                        <img alt="" src="../assets/images/users/9.jpg">
                                    </div>
                                    <div class="media-body">
                                        <div class="media-contact-name">
                                            <span>Reynante Labares</span>
                                            <span>9.40 am</span>
                                        </div>
                                        <p> Nice to meet you </p>
                                    </div>
                                </a>
                                <a class="media new" href="javascript:void(0)">
                                    <span class="avatar avatar-md brround bg-danger-transparent text-danger">J</span>
                                    <div class="media-body">
                                        <div class="media-contact-name">
                                            <span>Joyce Chua</span>
                                            <span>11.20 am</span>
                                        </div>
                                        <p> Hi, How are you? </p>
                                    </div>
                                </a>
                                <a class="media new" href="javascript:void(0)">
                                    <div class="main-img-user">
                                        <img alt="" src="../assets/images/users/4.jpg">
                                    </div>
                                    <div class="media-body">
                                        <div class="media-contact-name">
                                            <span>Rolando Paloso</span>
                                            <span>1.38 pm</span>
                                        </div>
                                        <p> Hey! there I'm available </p>
                                    </div>
                                </a>
                                <a class="media new" href="javascript:void(0)">
                                    <div class="main-img-user">
                                        <div class="avatar avatar-md brround bg-primary-transparent text-primary">D</div>
                                        <span>1</span>
                                    </div>
                                    <div class="media-body">
                                        <div class="media-contact-name">
                                            <span>Dexter dela Cruz</span>
                                            <span>4.08 pm</span>
                                        </div>
                                        <p>Typing...</p>
                                    </div>
                                </a>
                                <a class="media new" href="javascript:void(0)">
                                    <div class="main-img-user">
                                        <img alt="" src="../assets/images/users/21.jpg">
                                    </div>
                                    <div class="media-body">
                                        <div class="media-contact-name">
                                            <span>Maricel Villalon</span>
                                            <span>8.09 pm</span>
                                        </div>
                                        <p> Hey! there I'm available </p>
                                    </div>
                                </a>
                                <a class="media new" href="javascript:void(0)">
                                    <span class="avatar avatar-md brround bg-success-transparent text-success">M</span>
                                    <div class="media-body">
                                        <div class="media-contact-name">
                                            <span>Maryjane Pechon</span>
                                            <span>1 day ago</span>
                                        </div>
                                        <p>I have some work</p>
                                    </div>
                                </a>
                                <a class="media new" href="javascript:void(0)">
                                    <div class="main-img-user">
                                        <img alt="" src="../assets/images/users/5.jpg">
                                    </div>
                                    <div class="media-body">
                                        <div class="media-contact-name">
                                            <span>Lovely Dela Cruz</span>
                                            <span>3 days ago</span>
                                        </div>
                                        <p>I have some work</p>
                                    </div>
                                </a>
                                <a class="media new" href="javascript:void(0)">
                                    <div class="avatar avatar-md brround bg-secondary-transparent">
                                        <i class="fe fe-user text-secondary"></i>
                                    </div>
                                    <div class="media-body">
                                        <div class="media-contact-name">
                                            <span>Daniel Padilla</span>
                                            <span>5 days ago</span>
                                        </div>
                                        <p>I have some work</p>
                                    </div>
                                </a>
                                <a class="media new" href="javascript:void(0)">
                                    <div class="main-img-user">
                                        <img alt="" src="../assets/images/users/3.jpg">
                                    </div>
                                    <div class="media-body">
                                        <div class="media-contact-name">
                                            <span>John Pratts</span>
                                            <span>20/06/2021</span>
                                        </div>
                                        <p>I have some work</p>
                                    </div>
                                </a>
                                <a class="media new" href="javascript:void(0)">
                                    <div class="main-img-user">
                                        <img alt="" src="../assets/images/users/7.jpg">
                                    </div>
                                    <div class="media-body">
                                        <div class="media-contact-name">
                                            <span>Socrates Itumay</span>
                                            <span>18/07/2021</span>
                                        </div>
                                        <p> Hey! there I'm available </p>
                                    </div>
                                </a>
                                <a class="media new border-bottom-0" href="javascript:void(0)">
                                    <div class="main-img-user">
                                        <img alt="" src="../assets/images/users/6.jpg">
                                    </div>
                                    <div class="media-body">
                                        <div class="media-contact-name">
                                            <span>Samuel Lerin</span>
                                            <span>29/07/2021</span>
                                        </div>
                                        <p> Hey! there I'm available </p>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- main-chat-list -->
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[\App\Enums\Assets\ScriptLoader::DataTable()]])
@endsection
