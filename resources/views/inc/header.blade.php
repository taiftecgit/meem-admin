<style>
    a .menu-close {
        color: #707070 !important;
        display: none;
    }
    .sidebar-open a i.fa-bars{
        display: none;
    }
    .sidebar-open a .menu-close{
        display: block;
    }
</style>

<header class="main-header">
   {{-- <div class="d-flex align-items-center logo-box justify-content-start">
        <a href="#" class="waves-effect waves-light nav-link d-none d-md-inline-block mx-10 push-btn bg-transparent hover-primary" data-toggle="push-menu" role="button">
            <span class="icon-Align-left"><span class="path1"></span><span class="path2"></span><span class="path3"></span></span>
        </a>
        <!-- Logo -->
        <a href="{!! env('APP_URL') !!}dashboard" class="logo">
            <!-- logo-->
            <div class="logo-lg">

                    <span class="light-logo"><img src="{!! env('APP_ASSETS') !!}images/meem.png" alt="logo" style="width:110px"></span>


              --}}{{--  <span class="dark-logo"><img src="{!! env('APP_ASSETS') !!}images/logo-light-text.png" alt="logo"></span>--}}{{--
            </div>
        </a>
    </div>--}}
    <div class="d-flex align-items-center logo-box justify-content-between head_sub_div">

        <!-- Logo -->
        <a class="logo">
            <!-- logo-->
            <div class="logo-lg">
                <span class="light-logo"><img src="{!! env('APP_ASSETS') !!}images/logo-dashboard.png" alt="logo"></span>
                <span class="dark-logo"><img src="{!! env('APP_ASSETS') !!}images/logo-dashboard.png" alt="logo"></span>
            </div>
        </a>
       <a href="#" class="waves-effect waves-light nav-link d-lg-none d-md-inline-block mx-10 push-btn bg-transparent hover-primary left_bar" data-toggle="push-menu" data-option="sidebar-collapse" role="button">
            <!-- <span class="icon-Align-left"><span class="path1"></span><span class="path2"></span><span class="path3"></span></span> -->
            <i class="fa fa-bars" aria-hidden="true"></i>
           <div class="menu-close" aria-hidden="true">
               <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                   <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
               </svg>
           </div>
        </a>

<!--		<ul  class="switch-lang"><li><a class="change-lang" data-lang="en" href="#!">EN</a></li><li><a class="change-lang" data-lang="ar" href="#!">AR</a></li></ul>-->
		@if(\Illuminate\Support\Facades\Auth::user()->role!="administrator")
		<ul class="switch-lang">
			@if(app()->getLocale()=="ar")
			<li><a class="change-lang" data-lang="en" href="javascript:;">EN</a></li>
			@endif
			@if(app()->getLocale()=="en")
			<li><a class="change-lang" data-lang="ar" href="javascript:;">AR</a></li>
			@endif
		@endif
    </div>
    <nav class="navbar navbar-static-top d-flex align-items-end flex-column mb-0">
        <!-- Sidebar toggle button-->
        <div class="row">
            <div class="col-md-12 bar-section">
                <div class="navbar-custom-menu r-side">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu right_bar">
                            <a href="#" class="waves-effect waves-light nav-link mx-10 push-btn bg-transparent hover-primary" >
                                <i class="fa fa-bars" aria-hidden="true"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
        <!-- <div class="app-menu"></div> -->

    </nav>
    <!-- Header Navbar -->

</header>
