 @php
        $resto_rtl = \App\Models\Restaurants::find(\App\Helpers\CommonMethods::getRestuarantID());
$lang = $resto_rtl->default_lang;
app()->setLocale($lang);
if(session('app_lang') !==null){
$lang = session('app_lang');
app()->setLocale($lang);

}


if(\Illuminate\Support\Facades\Auth::user()->role=="administrator" || \Illuminate\Support\Facades\Auth::user()->role=="admin_user")
$lang="en";

@endphp
<!DOCTYPE html>
@if($lang=="ar")
<!--<html lang="ar" dir="rtl">-->
<html lang="ar" dir="rtl">
@else
<html lang="en">
@endif
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="MOPUhVXZcUk6jStOhUNBIjlzjQOz911kSUPoP7gN">
       <!--<title> {!! env('APP_NAME') !!} @yield('page-title')</title>-->
	   <title> {!! env('APP_NAME') !!} </title>

    <meta property="og:type" content="website" />

    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" href="{!! env('APP_ASSETS') !!}images/favicon.jpeg">
    <link rel="apple-touch-icon"  href="{!! env('APP_ASSETS') !!}images/favicon.jpeg">
    <link rel="shortcut icon"  href="{!! env('APP_ASSETS') !!}images/favicon.jpeg">
    <!-- Feather Icon-->
    <link href="{!! env('APP_ASSETS') !!}css/vendors_css.css" rel="stylesheet" type="text/css">
    <!-- Fontawesome Icon-->
<!--    <link href="{!! env('APP_ASSETS') !!}css/style.css" rel="stylesheet" type="text/css">-->
    <link href="{!! env('APP_ASSETS') !!}css/style_new.css?v=1.5" rel="stylesheet" type="text/css">

{{--
<link href="{!! env('APP_ASSETS') !!}css/style_rtl.css" rel="stylesheet" type="text/css">--}}
<!-- Bootstrap Css -->
    <link href="{!! env('APP_ASSETS') !!}css/skin_color.css" rel="stylesheet"><!-- Custom Css -->

    <link href="{!! env('APP_ASSETS') !!}css/sidebar-new.css?v=1.5" rel="stylesheet" type="text/css">

    <link href="{!! env('APP_ASSETS') !!}css/admin_ar.css" rel="stylesheet" type="text/css">
	<link href="{!! env('APP_ASSETS') !!}vendor_components/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
    @yield('css')


    <style>
            
    .page-top-title {
        padding: 7px 10px;
    }
    .pr-0{
        padding-right: 0 !important;
    }
        .table {
            --bs-table-bg: #ffffff;
            --bs-table-striped-bg:#fff5de;

        }
        table.dataTable {
            clear: both;
            margin-top: 6px !important;
            margin-bottom: 6px !important;
            max-width: none !important;
            border-collapse: collapse !important;
            font-family: 'Open Sans';
        }
        .table > tbody > tr > td {
            border-bottom-width: 0;
            padding: 1.5rem;
        }
		.content{
			padding-top:0 !important;
		}
    .btn-default{
        border-color: transparent;
    }
        .main-header .logo .logo-lg{ padding-left: 13px;}
        label.error{
            color:#FF0000;
        }
        .sidebar-active{
            display: block !important;
            width: 100% !important;
        }
        .theme-primary .btn-primary{
            background-color: #ffab00;
            border-color: #ffab00;
        }

		.theme-primary .badge-primary{
            background-color: #ffab00;
            border-color: #ffab00;
        }
        .sidebar-active .sidebar-mini.sidebar-collapse .sidebar-menu > li > a > span{ display: block !important;}
        .sidebar-menu > li .badge {
            margin-left: 5px;
            width: 20px;
            height: 20px;
            padding-left: 4px;
            padding-top: 6px;
            border-radius: 100%;
            line-height: 9px;
            text-align: center;
            font-weight: 300;
            margin-top: 3px;
            display: none;
        }
        .main-header .navbar{
            margin-left: 16.29rem;
        }
        .new-order {
            background: rgb(110 195 82 / 50%) !important;
            color: #000;
        }
        .alert{ display: none}
        #loader{
            background: #fff url({!! env('APP_ASSETS') !!}images/preloaders/preloader.svg) no-repeat center center;
            left: 0px !important;
            top: 0;
        }

        .theme-primary .btn-primary:hover, .theme-primary .btn-primary:active, .theme-primary .btn-primary:focus, .theme-primary .btn-primary.active{
            background-color: #ffab00 !important;
            border-color: #ffab00 !important;
        }

        .sidebar-menu > li,.sidebar-menu > li:hover { margin-bottom: 2px}

        .theme-primary.light-skin .sidebar-menu > li.active > a {
            color: #fff;
            background-color: #FEC34D ;padding:0px 10px;

        }
        .theme-primary.light-skin .sidebar-menu > li:hover > a, .theme-primary.light-skin .sidebar-menu > li:active > a, .theme-primary.light-skin .sidebar-menu > li.active > a{
            padding:0px 4px 0 4px;
            transition: color 0.25s ease-in-out, background-color 0.25s ease-in-out, border-color 0.25s ease-in-out, box-shadow 0.25s ease-in-out;
        }
        .sidebar-menu > li > a > i{
            margin-right: -2px;
        }

        #dropdownMenu2 {
            border-radius: 0px;
            background: #ffd684;
            text-align: left;
            padding-left: 23px;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-secondary:hover, .btn-secondary:active, .btn-secondary:focus, .btn-secondary.active{
            background-color: #ffd684 !important;
            border-color: #ffd684 !important;
        }
        .dropdown-item:hover, .dropdown-item:focus {

            background-color: transparent;
        }

        #dropdownMenu2 i{
            font-size:1.5714285714rem
        }
        .sidebar-collapse .sidebar-menu.tree{margin-top: 10px}
        /* .theme-primary.light-skin .sidebar-menu > li.active > a > i{
             background-color: #FFAD12 !important;
             box-shadow: 0 4px 5px 0 #d4b483 !important;
         }*/
        .theme-primary.light-skin .sidebar-menu > li.active {
            background-color: rgba(76, 149, 221, 0);
            color: #000;
            border-left: 0px solid #000;
        }
        .theme-primary.light-skin .sidebar-menu > li.active > a > i{
            box-shadow: 0 4px 5px 0 rgb(0 0 0 / 50%);
        }
        .theme-primary.light-skin .sidebar-menu > li:hover > a > i, .theme-primary.light-skin .sidebar-menu > li:active > a > i, .theme-primary.light-skin .sidebar-menu > li.active > a > i{
            background-color: rgba(0, 0, 0, 0);
            margin-bottom: 5px;

        }
        ul.footer-drop{
            transform: translate(269px, 2px) !important;
                box-shadow: 3px 0 4px rgba(0, 0, 0, 0.06);
        }

        .notification-badge{
            font-size: 75%;position: absolute;top: -2px !important;right: -13px;padding: 5px 10px;border-radius: 50%;background: red;color: white;
        }
        button .pull-right-container {
            position: absolute;
            right: calc(100% - 87%);
            top: calc(100% - 54%);
            margin-top: -7px;
        }

        @php
            
            $no_gap_pages = ['OrderListing','outlets-form','OutletEdit','outlets-address','outlets-delivery','outlets-pickup','outlets-delivery-area','outlets-new-delivery-area','outlets-digital-menu','marketing','payment-links'];
            
        @endphp

        @if(in_array(Route::currentRouteName(),$no_gap_pages))
        .content-wrapper {
            width: calc(100% - 270px);
            background-color: #fff !important;
        }
        .fixed .content-wrapper {
            margin-top: 0px;
            margin-left: 270px;
            margin-left: 270px;
        }
        @else
        .content-wrapper {
            width: calc(100% - 280px);
            background-color: #fff !important;
        }
        .fixed .content-wrapper {
            margin-top: 0px;
            margin-left: 275px;
        }
        @endif


         @media (min-width: 950px) and (max-width:  1020px){
.d-md-none{
    display: block !important;
}
.push-btn{
    display: none !important;
}
.fixed .content-wrapper{
    margin-left: 223px !important;
}

.delivery-section {
    width: 356px !important;
    left:0 !important;
    top: 0 !important;
}

.save-delivery-area{
    width: 200px;
}

.content-wrapper {
    width: calc(100% - 223px);
    }

    .nav-tabs .nav-link {
    padding: 0.5rem 2.25rem!important;
    font-size: 15px;
}



.main-sidebar,.fixed .multinav,.main-header div.logo-box{
        width: 16.29rem;
}
            .sidebar-menu.tree {
                margin-top: 0px !important;
                margin-left: 0px !important;
            }

            .sidebar-menu {
                list-style: none;
                margin: 0 0px;
                padding: 10px 0px 50px 0px;
            }

            .light-skin .sidebar-menu > li > a > span {
                background-color: transparent !important;
                box-shadow: none !important;
                margin-left: 11px;
                -webkit-transform: none;
                font-size: 14px;
            }
.theme-primary.light-skin .sidebar-menu > li:hover > a > i, .theme-primary.light-skin .sidebar-menu > li:active > a > i, .theme-primary.light-skin .sidebar-menu > li.active > a > i{
    margin-bottom: 0;
}
.sidebar-menu > li > a{
    padding: 1px 5px;
}
            .theme-primary.light-skin .sidebar-menu > li.active {
                background-color: rgba(76, 149, 221, 0);
                color: #000;
                border-left: 0px solid #000;
            }

            .theme-primary.light-skin.sidebar-mini.sidebar-collapse .sidebar-menu > li > a > span{
                display: inline-block !important;
                background: transparent !important;
                font-size: 14px;
            }
            .theme-primary.light-skin.sidebar-mini.sidebar-collapse .sidebar-menu > li.active > a > span{
                background: transparent !important; font-size: 14px;
            }
            .sidebar-collapse .sidebar-menu > li > a {
                padding: 1px 12px;
            }

            .main-sidebar .dropdown{
                width: 228px;
            }
            .sidebar-mini.sidebar-collapse .dropdown button > span{ display: inline-block !important; }

            .add-outlet{ width: 200px; }


    }

        @media (min-width: 1025px) {
            .main-header .logo {
            height: 61px;
        }
        .main-header .logo .logo-lg{
            line-height: 61px;
        }
        }

        @media (min-width: 800px) and (max-width:  949px){
            .fixed .multinav{
                width: 100%;
            }
            html[dir="rtl"] header .logo-box{
                margin-right: -1.76rem !important;
            }
            .main-header .logo {
                width: 100%;
                float: none;
                text-align: center;
            }
            .main-header > div {
                width: auto !important;
                float: none;
                margin-left: 0 !important;
            }
            html[dir="rtl"] .main-header .logo {
                text-align: left!important;
            }
            html[dir="rtl"] .main-header .navbar{
                margin-left: 0rem;
                min-height: 0px !important;
                padding: 0 0 0 1.5rem !important;
            }
            .order-sections-list {
                width: 100%;
            }
            html[dir="rtl"] .content-wrapper{
                margin-right: 0 !important;
            }

            .main-header .navbar {
                margin-left: 16.29rem;
                width: auto;
                float: none;
                margin: 0;
                z-index: 999;
            }

        }
        @media  only screen
        and (min-device-width: 768px)
        and (max-device-width: 1024px)
        and (orientation: portrait)
        and (-webkit-min-device-pixel-ratio: 1) {

            .content-wrapper {
                width: calc(100%) !important;
                background-color: #fff !important;
            }
            .fixed .content-wrapper {
                margin-top: 60px !important;
                margin-left: 0px;
                padding: 5px;
            }

            .add-outlet{
                margin-top: 15px;
            }



            .sidebar-mini .sidebar-menu > li > a > span{


            }
            .light-skin .sidebar-menu > li > a > span{
                background-color: transparent !important;
                box-shadow:none !important;
                margin-left: 11px;
                -webkit-transform:none;
                font-size: 16px;
            }


            .sidebar-footer {
                width: auto;
            }

            .sidebar-mini .dropdown button > span {
                border-top-right-radius: 5px;
                display: inline-grid !important;
                -webkit-transform: translateZ(0);
            }

            .sidebar-menu.tree {
                margin-top: 34px;
                margin-left: 25px;
            }

            button .pull-right-container {
                position: absolute;
                right: calc(100% - 96%);
                top: calc(100% - 60%);
                margin-top: -7px;
            }

            .theme-primary.light-skin.sidebar-mini.sidebar-collapse .sidebar-menu > li.active > a > span {
                background: transparent !important;
            }
            .col-sm-12.col-md-6.box-shadowed{
                width: 100%;
            }
            .order-sections-list{
                width: 100%;
            }
            .no-order{
                padding-top: 30% !important;
                height: 48.2vh !important;
            }

            .all-orders{ max-height: 300px !important; height: 300px !important; min-height:300px !important;}
            .nav-tabs .nav-link {
                padding: 14px 21px !important;
            }

            .nav-tabs {

                height: 57px;
                margin-top: 11px;
            }
        }


        @media (min-width: 950px) and (max-width:  1020px){
            .order-sections-list ,.box-shadowed{
                width: calc(100% - 16.4rem);
            }
        }
        @media (max-width: 767px){
            .navbar{
                background-color: transparent !important;
            }
            .hs:before, .hs:after{
                display: none
            }
            .fixed .multinav{
                width: 100% ;
            }
        }
        @media  only screen
        and (min-device-width: 768px)
        and (max-device-width: 1024px)
        and (orientation: landscape)
        and (-webkit-min-device-pixel-ratio: 1) {
            .navbar{
                background-color: transparent !important;
            }
            .sidebar-menu li a {
                font-size: inherit !important;
            }
            .sidebar-footer button {
                font-size: inherit !important;
            }
            .fixed .content-wrapper {
                margin-top: -0px;
                margin-left: 271px;
                padding: 5px;
            }
            .no-order,.order-section{
                height: 98.7vh !important;

            }
            .col-md-6.box-shadowed{
                width: 66%;
            }
            .tab-pane{
                max-height: 88.5vh !important;
            }
            /*.navbar-static-top{ display: none !important;}*/
        }
        @media (max-width:641px) {
            .navbar{
                background-color: transparent !important;
            }
            .content-wrapper {
                width: 100%;
                background-color: #fff !important;
            }
            .fixed .content-wrapper {
                margin-top: 60px ;
                margin-left: 0px;
                padding: 5px;
            }

            .add-outlet{
                margin-top: 15px;
            }



            .sidebar-mini .sidebar-menu > li > a > span{


            }
            .light-skin .sidebar-menu > li > a > span{
                background-color: transparent !important;
                box-shadow:none !important;
                margin-left: 11px;
                -webkit-transform:none;
                font-size: 16px;
            }
            .fixed .multinav{
                width: 100%;
            }

            .sidebar-footer {
                width: auto;
            }

            .sidebar-mini .dropdown button > span {
                border-top-right-radius: 5px;
                display: inline-grid !important;
                -webkit-transform: translateZ(0);
            }

            .sidebar-menu.tree {
                margin-top: 34px;
                margin-left: 25px;
            }

            button .pull-right-container {
                position: absolute;
                right: calc(100% - 96%);
                top: calc(100% - 60%);
                margin-top: -7px;
            }

            .theme-primary.light-skin.sidebar-mini.sidebar-collapse .sidebar-menu > li.active > a > span {
                background: transparent !important;
            }
            .col-sm-12.col-md-6.box-shadowed{
                width: 100%;
            }
            .order-sections-list{
                width: 100%;
            }
            .no-order{
                padding-top: 30% !important;
                height: 48.2vh !important;
            }

       /*     .all-orders{ max-height: 300px !important; height: 300px !important; min-height:300px !important;}*/
            .nav-tabs .nav-link {
                padding: 14px 21px !important;
            }

            .nav-tabs {

                height: 57px;
                margin-top: 11px;
            }
        }


        @media (min-width: 768px) and (max-width: 1024px) {
            .head_sub_div, .navbar {
                background-color: var(--navcolor) !important;
            }
            .main-header .navbar {
                min-height: 80px;
            }
            .right_bar {
                display: inherit;
            }
            .left_bar{
            display: none !important;
            }
            .hs:before, .hs:after{
                display: none
            }
            .switch-lang{
              position: absolute;
              right:50%;
              top: 30px;
              z-index: 1081;
              margin: 0px !important;
            }
            .content-wrapper .navbar.navbar-static-top {
                background: transparent !important;
            }
        }


        @media (max-width:767px){
            .main-sidebar{
                top: 0;
                left: 0;
                padding-top: 80px;
                min-height: 100%;
            }
            .sidebar-menu.tree {
                margin-top: 34px;
                margin-left: 25px;
            }
            .right-panel-footer{
                --bs-gutter-x: 0;
            }
            .count_div {
                padding: 10px;
            }

            .order-section{
                overflow: hidden !important;
            }
            .theme-primary.light-skin .sidebar-menu > li.active {
                background-color: rgba(76, 149, 221, 0);
                color: #000;
                border-left: 0px solid #000;
            }
            .sidebar-menu {
                list-style: none;
                margin: 0 0px;
                padding: 20px 0px 50px 0px;
            }
            .back-to-orders{
                padding: 10px;
                background: orange;
                font-size: 14px;
                cursor: pointer;
                font-weight: 600;
            }
            .address_action_div{
                padding: 0rem 0.4rem !important;
            }
            .container, .container-fluid, .container-sm, .container-md, .container-lg, .container-xl, .container-xxl{padding-left: 0}
            .right-panel-box div.box-body{
                padding: 0rem 1.5rem 10px !important;
            }

            .box-header{
                padding: 0.5rem;
            }
            #show-recipes .box-header {.order-section
                padding: 15px 0.5rem 5px !important;
            }
            .right-panel-footer {
                /*margin-bottom: 25px !important;*/
                padding: 10px 1.5rem;
            }
            .all-orders{ max-height: 70vh !important; height: 70vh !important; min-height:70vh !important;}
            .order-section{
                height: 100vh !important;
                overflow-x: auto;
                position: absolute;
                z-index: 999;
                top: 50px;
                background: white;
                left: 0;
                right: 0;
            }
            input, select:focus{
                border-right-width: 1px !important;
            }
            .circle-div{ width: 40px; height: 40px}
            .gap-items p.min{
                line-height: 11px !important;
                padding-top: 8px !important;
                font-size: 11px;
            }
            .box-bodys{ margin-bottom: 10px;}
            .p-15{
                padding:5px !important;
            }
            .add-outlet{ width: 100%;}
            .navbar{
                background-color: transparent !important;
            }
            .content-wrapper {
                width: 100%;
                background-color: #fff !important;
            }
            .fixed .content-wrapper {
                margin-top: 35px ;
                margin-left: 0px !important;
                padding: 5px;
				overflow:hidden;
            }



            .sidebar-mini.sidebar-collapse .sidebar-menu > li > a > span{
                display: inline-grid !important

            }
            .light-skin.sidebar-mini.sidebar-collapse .sidebar-menu > li > a > span{
                background-color: transparent !important;
                box-shadow:none !important;
                margin-left: 11px;
                -webkit-transform:none;
                font-size: 16px;
            }

            .sidebar-collapse .sidebar-footer {
                width: auto;
            }

            .sidebar-mini.sidebar-collapse .dropdown button > span {
                border-top-right-radius: 5px;
                display: inline-grid !important;
                -webkit-transform: translateZ(0);
            }

            .sidebar-collapse .sidebar-menu.tree {
                margin-top: 34px;
                margin-left: 25px;
            }

            button .pull-right-container {
                position: absolute;
                right: calc(100% - 96%);
                top: calc(100% - 60%);
                margin-top: -7px;
            }

            .theme-primary.light-skin.sidebar-mini.sidebar-collapse .sidebar-menu > li.active > a > span {
                background: transparent !important;
            }
            .col-sm-12.col-md-6.box-shadowed{
                width: 100%;
            }
            .order-sections-list{
                width: 100%;
				position: relative;
				/*width: 414px;*/
				right: 0;

            }
            .no-order{
                padding-top: 30% !important;
                height: 48.2vh !important;
                display: none;
            }

            .hs > li, .item{
                width: auto;
                margin: auto !important;
            }

            .nav-tabs .nav-link {
                padding: 7px 13px !important;
            }

            .nav-tabs {

                height: 57px;
                margin-top: 11px;
            }
        }

		@media (max-width:440px){
            .sidebar-footer {
                width: 100%;
            }
            .main-sidebar .sidebar-footer ul.footer-drop{
                left: -57% !important;
                right: 1px !important;
                width: 100%;
                margin-bottom: 51px !important;
            }
			.switch-lang{
				    position: absolute;
					right:50%;
					top: 13px;
					z-index: 1081;
				margin: 0px !important;
			}
			 .switch-lang{
				left: 26px;
				right: inherit;
				top: 15px;
			}

			html[dir="rtl"] .main-header .logo{
				text-align: left!important;
			}
			.main-header > div .logo {
				text-align: revert;
			}
			.order-sections-list{
				width:100%;

			}
			.tab-bar-section{
				width:465px;

			}
			html[dir="rtl"] .tab-bar-section .nav-tabs {
				    margin-right: -43px;
			}
			  .hs > li, .item{
				width:auto !important;
				margin:auto !important;

			}
		}
		@media (max-width:428px){
			.tab-bar-section {
				width: 451px;
			}
		}


		@media (max-width:414px){
			.tab-bar-section {
				width: 435px;
			}
			html[dir="rtl"] .tab-bar-section .nav-tabs {
				    margin-right: -42px;
			}
			html[dir="rtl"] .nav-tabs .nav-link {
				padding: 7px 11px !important;
			}
		}

		@media (max-width:390px){

			.tab-bar-section {
				width: 414px;
			}

			html[dir="rtl"] .tab-bar-section .nav-tabs {
				    margin-right: -49px;
			}

			html[dir="rtl"] .nav-tabs .nav-link {
				font-size: 12px !important;
			}
			.nav-tabs .nav-link {
				padding: 7px 10px !important;
			}
		}
		@media (max-width:375px){
			.tab-bar-section {
				width: 395px;
			}

			html[dir="rtl"] .nav-tabs .nav-link {
				font-size: 12px !important;
			}
			.nav-tabs .nav-link {
					padding: 7px 8px !important;
				}
		}

        @media (max-width:320px){
            .order-section{
                height: 100vh !important;
                overflow-x: auto;
                position: absolute;
                z-index: 999999;
                top: 0;
                background: white;
                left: 0;
                right: 0;
            }
            .circle-div{ width: 40px; height: 40px}
            .gap-items p.min{
                line-height: 11px !important;
                padding-top: 8px !important;
                font-size: 11px;
            }
            .box-bodys{ margin-bottom: 10px;}
            .p-15{
                padding:5px !important;
            }
            .add-outlet{ width: 100%;}
            .navbar{
                background-color: transparent !important;
            }
            .content-wrapper {
                width: calc(100%) !important;
                background-color: #fff !important;
            }
            .fixed .content-wrapper {
                margin-top: 35px ;
                margin-left: 0px;
                padding: 5px;
            }

            .theme-primary.light-skin .sidebar-menu > li:hover > a, .theme-primary.light-skin .sidebar-menu > li:active > a, .theme-primary.light-skin .sidebar-menu > li.active > a{
                background: none !important;
            }

            .sidebar-mini.sidebar-collapse .sidebar-menu > li > a > span{
                display: inline-grid !important

            }
            .light-skin.sidebar-mini.sidebar-collapse .sidebar-menu > li > a > span{
                background-color: transparent !important;
                box-shadow:none !important;
                margin-left: 11px;
                -webkit-transform:none;
                font-size: 16px;
            }

            .sidebar-collapse .sidebar-footer {
                width: auto;
            }

            .sidebar-mini.sidebar-collapse .dropdown button > span {
                border-top-right-radius: 5px;
                display: inline-grid !important;
                -webkit-transform: translateZ(0);
            }

            .sidebar-collapse .sidebar-menu.tree {
                margin-top: 34px;
                margin-left: 25px;
            }

            button .pull-right-container {
                position: absolute;
                right: calc(100% - 96%);
                top: calc(100% - 60%);
                margin-top: -7px;
            }

            .theme-primary.light-skin.sidebar-mini.sidebar-collapse .sidebar-menu > li.active > a > span {
                background: transparent !important;
            }
            .col-sm-12.col-md-6.box-shadowed{
                width: 100%;
            }
            .order-sections-list{
                width: 100%;

            }
            .no-order{
                padding-top: 30% !important;
                height: 48.2vh !important;
                display: none;
            }

            .hs > li, .item{
                width: 160px;
            }

            .nav-tabs .nav-link {
                padding: 7px 13px !important;
            }

            .nav-tabs {

                height: 57px;
                margin-top: 11px;
            }
        }

       /* @media (device-width: 375px) and (orientation: portrait){
            .navbar{
                background-color: transparent !important;
            }
            .content-wrapper {
                width: calc(96%) !important;
                background-color: #fff !important;
            }
            .fixed .content-wrapper {
                margin-top: 35px !important;
                margin-left: 0px;
                padding: 5px;
            }

            .theme-primary.light-skin .sidebar-menu > li:hover > a, .theme-primary.light-skin .sidebar-menu > li:active > a, .theme-primary.light-skin .sidebar-menu > li.active > a{
                background: none !important;
            }

            .sidebar-mini.sidebar-collapse .sidebar-menu > li > a > span{
                display: inline-grid !important

            }
            .light-skin.sidebar-mini.sidebar-collapse .sidebar-menu > li > a > span{
                background-color: transparent !important;
                box-shadow:none !important;
                margin-left: 11px;
                -webkit-transform:none;
                font-size: 16px;
            }

            .sidebar-collapse .sidebar-footer {
                width: auto;
            }

            .sidebar-mini.sidebar-collapse .dropdown button > span {
                border-top-right-radius: 5px;
                display: inline-grid !important;
                -webkit-transform: translateZ(0);
            }

            .sidebar-collapse .sidebar-menu.tree {
                margin-top: 34px;
                margin-left: 25px;
            }

            button .pull-right-container {
                position: absolute;
                right: calc(100% - 96%);
                top: calc(100% - 60%);
                margin-top: -7px;
            }

            .theme-primary.light-skin.sidebar-mini.sidebar-collapse .sidebar-menu > li.active > a > span {
                background: transparent !important;
            }
            .col-sm-12.col-md-6.box-shadowed{
                width: 480px;
            }
            .order-sections-list{
                width: 295px;
            }

            .nav-tabs .nav-link {
                padding: 14px 21px !important;
            }

            .nav-tabs {

                height: 57px;
                margin-top: 11px;
            }
        }

        @media (device-width: 360px) and (orientation: portrait){
            .order-sections-list{
                width: 100%;

            }
            .no-order{
                padding-top: 30% !important;
                height: 48.2vh !important;
                display: none;
            }

            .hs > li, .item{
                width: 160px;
            }

            .nav-tabs .nav-link {
                padding: 7px 13px !important;
            }
            .navbar{
                background-color: transparent !important;
            }

            .content-wrapper {
                width: calc(96%) !important;
                background-color: #fff !important;
            }
            .fixed .content-wrapper {
                margin-top: 35px !important;
                margin-left: 0px;
                padding: 5px;
            }

            .theme-primary.light-skin .sidebar-menu > li:hover > a, .theme-primary.light-skin .sidebar-menu > li:active > a, .theme-primary.light-skin .sidebar-menu > li.active > a{
                background: none !important;
            }

            .sidebar-mini.sidebar-collapse .sidebar-menu > li > a > span{
                display: inline-grid !important

            }
            .light-skin.sidebar-mini.sidebar-collapse .sidebar-menu > li > a > span{
                background-color: transparent !important;
                box-shadow:none !important;
                margin-left: 11px;
                -webkit-transform:none;
                font-size: 16px;
            }

            .sidebar-collapse .sidebar-footer {
                width: auto;
            }

            .sidebar-mini.sidebar-collapse .dropdown button > span {
                border-top-right-radius: 5px;
                display: inline-grid !important;
                -webkit-transform: translateZ(0);
            }

            .sidebar-collapse .sidebar-menu.tree {
                margin-top: 34px;
                margin-left: 25px;
            }

            button .pull-right-container {
                position: absolute;
                right: calc(100% - 96%);
                top: calc(100% - 60%);
                margin-top: -7px;
            }

            .theme-primary.light-skin.sidebar-mini.sidebar-collapse .sidebar-menu > li.active > a > span {
                background: transparent !important;
            }
            .col-sm-12.col-md-6.box-shadowed{
                width: 480px;
            }
            .order-sections-list{
                width: 295px;
            }

            .nav-tabs .nav-link {
                padding: 14px 21px !important;
            }

            .nav-tabs {

                height: 57px;
                margin-top: 11px;
            }
        }*/
/* CSS by Sadaf(customdev) start */

html[dir="rtl"] .content-wrapper {
    width: calc(100% - 270px);
    margin-right:270px;
}
html[dir="rtl"] .btn-toggle.btn-sm:after{
    right: 0.3rem;
}
html[dir="rtl"] .switch-me.btn-toggle.btn-sm.btn-outlet.switch-me[aria-pressed=" false "]{
   background: grey !important;
}

html[dir="rtl"] .main-sidebar {
	right:0 !important;
}
html[dir="rtl"] .d-flex {
	direction:rtl !important;
}
html[dir="rtl"] .d-flex:has(>.iqd_small) {
	flex-direction: row-reverse !important;
}
html[dir="rtl"] p,  html[dir="rtl"] h1, html[dir="rtl"] h2, html[dir="rtl"] h3, html[dir="rtl"] h4, html[dir="rtl"] h5, html[dir="rtl"] h6, html[dir="rtl"] input, html[dir="rtl"] textarea, html[dir="rtl"]  button{
	direction:rtl !important;
}
html[dir="rtl"] select{
  direction:rtl!important;
  background-position: left 0.8rem center;
}
.form-group.has-search {
	position:relative;
}
.form-group.has-search input {
	padding-right:30px !important;
}
.form-group.has-search .fa-search {
	right:10px !important;
	width:15px !important;
}
html[dir="rtl"] .form-group.has-search input {
	padding-left:30px !important;
}
html[dir="rtl"] .form-group.has-search input.search-outlet {
	padding-left:0.75rem !important;
	padding-right:0.75rem !important;
}
html[dir="rtl"] .form-group.has-search .fa-search {
	left:10px !important;
	right:unset !important;
}
html[dir="rtl"] form p {
    text-align: right !important;
}
html[dir="rtl"] .sidebar-menu > li > a > i {
    transform: scaleX(-1);
    -moz-transform: scaleX(-1);
    -webkit-transform: scaleX(-1);
    -ms-transform: scaleX(-1);
}
html[dir="rtl"] .main-sidebar .sidebar-footer {
	right:0 !important;
	left:unset !important;
}
html[dir="rtl"] .main-sidebar .sidebar-footer ul.footer-drop {
    transform: translate(-269px, 2px) !important;
}
html[dir="rtl"] .main-sidebar .sidebar-footer #dropdownMenu2 {
    text-align: right !important;
	padding-right: 45px !important;
}
html[dir="rtl"] .main-sidebar .sidebar-footer button .pull-right-container {
    right: unset !important;
	left: calc(100% - 93%) !important;
}
html[dir="rtl"] .main-sidebar .sidebar-footer #dropdownMenu2 i {
	transform: scaleX(-1);
    -moz-transform: scaleX(-1);
    -webkit-transform: scaleX(-1);
    -ms-transform: scaleX(-1);
}
html[dir="rtl"] header .logo-box {
	margin-right: -25.76rem !important;
	margin-left:unset !important;
	float:right !important;
}

.sidebar-menu > li:hover > a > i, .sidebar-menu > li:active > a > i, .sidebar-menu > li.active > a > i {
   margin-bottom: 0 !important;
}
.theme-primary.light-skin .sidebar-menu > li:hover > a, .theme-primary.light-skin .sidebar-menu > li:active > a, .theme-primary.light-skin .sidebar-menu > li.active > a {
	padding:5px; !important;
}
html[dir="rtl"] .sidebar-menu > li:hover > a, html[dir="rtl"] .sidebar-menu > li:active > a, html[dir="rtl"] .sidebar-menu > li.active > a {
	padding:8px 30px; !important;
}
h3[style="margin-left: 10px"] {
	margin-right: 10px; !important;
	margin-left:unset !important;
}
html[dir="rtl"] .sidebar-menu > li .notification-badge {
    margin-right: 5px !important;
	right: unset !important;
}
html[dir="rtl"] .sidebar-menu li svg {
	margin-top: unset !important;
}
html[dir="rtl"] .sidebar-menu li > a > i {
    width: 24px !important;
    height: auto !important;
    line-height: 50% !important;
    margin-right: 0 !important;
	margin-left: 3px !important;
}
html[dir="rtl"] .main-header .logo {
    text-align: right ;
	padding-right: 45px !important;
}
html[dir="rtl"] foreignObject[x="10"] .ct-label.ct-vertical.ct-start{
	-webkit-transform:rotateY(180deg);
  -moz-transform:rotateY(180deg);
  -o-transform:rotateY(180deg);
  -ms-transform:rotateY(180deg);
  justify-content: flex-start !important
}
html[dir="rtl"] .sidebar-menu > li > a {
    padding: 8px 30px !important;
}
html[dir="rtl"] .ct-chart-bar.ct-horizontal-bars {
	transform: scaleX(-1);
    -moz-transform: scaleX(-1);
    -webkit-transform: scaleX(-1);
    -ms-transform: scaleX(-1);
}
html[dir="rtl"] .btn-primary.add-outlet {
	float:left !important;
}
html[dir="rtl"] .has-search a {
    left: 15px !important;
	right:unset !important;
}
html[dir="rtl"] .content-wrapper .order-sections-list .media-list .media {
        flex-direction: row-reverse !important;
}
html[dir="rtl"] .content-wrapper .order-sections-list .search-div [type="search"] {
	border-right-width:1px !important;
	border-left-width:0 !important;
}
html[dir="rtl"] .content-wrapper .order-sections-list .search-div .btn {
	border-left-width:1px !important;
	border-right-width:0 !important;
}
html[dir="rtl"] .content-wrapper .content .row:first-child .col-6:nth-child(2) .btn {
	float:left !important;
	margin-left: 10px !important;
}
html[dir="rtl"] .content-wrapper .content .col-md-5 .btn.add-discount {
	float:left !important;
	margin-left: 15px !important;
}
html[dir="rtl"] .content .dataTables_wrapper .row:first-child {
	 flex-direction: row-reverse !important;
}
html[dir="rtl"] a.search-location {
    right: unset !important;
    top: 27px !important;
    left: 15px !important;
}
html[dir="rtl"] [name="delivery_fee"], html[dir="rtl"] [name="min_basket"]  {
	border-top-right-radius: 0 !important;
    border-bottom-right-radius: 0 !important;
	border-right: 0 !important;
	border-top-left-radius: 10px !important;
    border-bottom-left-radius: 10px !important;
	border-left:1px solid #ced4da !important;
}
html[dir="rtl"] .input-group:has([name="delivery_fee"]) .input-group-addon, html[dir="rtl"] .input-group:has([name="min_basket"]) .input-group-addon {
	border-top-right-radius: 10px !important;
    border-bottom-right-radius: 10px !important;
	border-top-left-radius: 0 !important;
    border-bottom-left-radius: 0 !important;
	    border-left: 0 !important;
}
html[dir="rtl"] .form-group:has(a.search-location) #pac-input {
	position:unset !important;
}
html[dir="rtl"] .form-group:has(a.search-location) {
	float:right !important;
	padding-top:26px !important;
}
html[dir="rtl"] .order-section .actions {
     right: unset !important;
	left:22px !important
}
html[dir="rtl"] foreignObject:not([x="10"]) .ct-label.ct-horizontal.ct-end {
	-webkit-transform:rotateY(180deg);
  -moz-transform:rotateY(180deg);
  -o-transform:rotateY(180deg);
  -ms-transform:rotateY(180deg);
  justify-content: flex-start !important
}
html[dir="rtl"] [type="checkbox"]:not(:checked), html[dir="rtl"] [type="checkbox"]:checked {
      left: unset !important;
    right: -9999px !important;
}
html[dir="rtl"] .p-outletnameP {
    padding-right: 47px;
	padding-left: unset !important;
}
ul:has(.change-lang) {
	display: flex;
    list-style: none;
	margin: 10px;
}
li:has( > .change-lang) {
    background: hsla(0,0%,100%,.4);
    border-radius: 20px;
    color: #828282;
    float: left;
    font-size: 10px;
    height: 20px;
    line-height: 20px;
    margin: 2px;
    text-align: center;
    width: 20px;
}
html[dir="rtl"] li:has( > .change-lang[data-lang="en"]) {
background: #fff;
    color: #000;
    height: 22px;
    line-height: 22px;
    margin-top: 1px;
    width: 22px;
}
html[lang="en"] li:has( > .change-lang[data-lang="ar"]) {
	background: #fff;
    color: #000;
    height: 22px;
    line-height: 22px;
    margin-top: 1px;
    width: 22px;
}

@media(min-width:1024px)
{
    html[dir="rtl"] .content-wrapper:has(.order-sections-list) {
        width: calc(100% - 270px) ;
        margin-right: 270px !important;
    }


}
@media (min-width: 768px) {
	html[dir="rtl"] .col-md-5:has(.btn-primary.add-outlet) {
		width:50% !important;
	}
	html[dir="rtl"] .col-md-5:has(.btn.add-discount) {
		width:50% !important;
	}

}
@media (max-width: 641px) {
html[dir="rtl"] .content-wrapper {
    width: 100% !important;
    margin-right: 0 !important;
}
html[dir="rtl"] .main-sidebar .sidebar-footer ul.footer-drop {
    /*transform: translate(-226px, 2px) !important;*/
    transform: translate(0px, -42px) !important;
}
html[dir="rtl"] header .logo-box  {
	margin-right: 0 !important;
}
html[dir="rtl"] header .logo-box .left_bar {
	order: -1 !important;
}
.main-header > div .logo .logo-lg {
    margin-right: calc(100% + -200%) !important;
	margin-left:unset !important;
}
html[dir="rtl"] .main-sidebar .sidebar-footer .resto-name-long {
	padding-left: 30px !important;
    margin-right: 10px !important;
	top:0 !important;
}
html[dir="rtl"] .order-sections-list{
                /*width: 100% !important;
				position: unset !important;*/

            }
html[dir="rtl"] .text-end:has(.print) {
	text-align: left !important;
}
/*.mlist_li.selected .box:not(.bg-danger) {
	background-color:#000 !important;
}
.mlist_li.selected .box.bg-danger {
	background-color:#e66430 !important;
}
.mlist_li.selected {
    color: #fff !important;
    background-color: transparent !important;
}
.mlist_li.selected .media-list {
    color: #fff !important;
    background-color: transparent !important;
}*/
.mlist_li.selected p {
    color: #fff !important;
}
.mlist_li.selected p {
    color: #fff !important;
}
.media-list-hover > .media:not(.media-list-header):not(.media-list-footer):hover, .media-list-hover .media-list-body > .media:hover {
    background-color: transparent !important;
}
.fixed .main-header {
    z-index: 999999  !important;
}
}
/* CSS by Sadaf(customdev) end */
    </style>

</head>

<body class="light-skin sidebar-mini theme-primary fixed">
<div class="wrapper">
    <div id="loader"></div>

    @include('inc.header')
     @if(\Illuminate\Support\Facades\Auth::user()->role=="administrator")
            @include('inc.sidebar_admin')
            @elseif(\Illuminate\Support\Facades\Auth::user()->role=="resto_user")
            @include('inc.sidebar_resto_user')
         @elseif(\Illuminate\Support\Facades\Auth::user()->role=="admin_user")
		 	@include('inc.sidebar_admin_users')
		@else
            @include('inc.sidebar_resto')
        @endif
    @yield('content')
    @php

 if(\Illuminate\Support\Facades\Auth::user()->role=="restaurant"){
        $placed_order = false;
        $recent_till = \Carbon\Carbon::now()->subDays(2)->format('Y-m-d');;

            $one_order = \Illuminate\Support\Facades\Auth::user()->restaurants->id;
            $order1 = \App\Models\Orders::where('status','Placed')->where('resto_id',$one_order)->where('created_at','>=',$recent_till)->first();


        if(isset($order1) && $order1->status=="Placed"){
        $placed_order = true;
        }
        //dump($order);
    }

    @endphp
    {{-- @include('inc.sidebar_resto_right')--}}

    {{--<footer class="main-footer" style="font-size: 10px">
        <video controls="controls" src="{!! env('APP_URL') !!}/1sec.avi" muted autoplay loop style="display:none">
            Your browser does not support the HTML5 Video element.
        </video>
        <img src="{!! env('APP_ASSETS') !!}images/favicon.png" height="18" /> meem powered by <img src="{!! env('APP_ASSETS') !!}images/taiftec.png" height="16" />
    </footer>--}}
    <input type="button" id="toggle" value="Wake Lock is disabled"  style="display:none"/>
</div>
</body>
<script src="{!! env('APP_ASSETS') !!}js/vendors.min.js"></script>

<script src="{!! env('APP_ASSETS') !!}js/pages/chat-popup.js"></script>


<script src="{!! env('APP_ASSETS') !!}icons/feather-icons/feather.min.js"></script>

<script src="{!! env('APP_ASSETS') !!}vendor_components/progressbar.js-master/dist/progressbar.js"></script>
<script src="{!! env('APP_ASSETS') !!}vendor_components/OwlCarousel2/dist/owl.carousel.js"></script>
<script src="{!! env('APP_ASSETS') !!}vendor_components/datatable/datatables.min.js"></script>
<script src="{!! env('APP_ASSETS') !!}vendor_components/jquery-toast-plugin-master/src/jquery.toast.js"></script>
<script src="{!! env('APP_ASSETS') !!}vendor_components/sweetalert/sweetalert.min.js"></script>
<script src="{!! env('APP_ASSETS') !!}js/printThis.js"></script>
<script src="{!! env('APP_ASSETS') !!}js/jquery.uploadPreview.js"></script>
<script src="{!! env('APP_ASSETS') !!}js/jquery.validate.min.js"></script>
<script src="{!! env('APP_ASSETS') !!}js/jquery.form.js"></script>
<script src="{!! env('APP_ASSETS') !!}js/NoSleep.min.js"></script>
<!-- Riday Admin App -->
<script src="//js.pusher.com/3.1/pusher.min.js"></script>
<script src="{!! env('APP_ASSETS') !!}js/template.js"></script>
<script type="text/javascript">
    var progress_img =  '<img src="{!! env('APP_ASSETS') !!}images/preloader-1.svg" style="height: 25px;">';
</script>

<script>
    var logged_user = "{!! \App\Helpers\CommonMethods::getRestuarantID() !!}";
    //screen.orientation.lock('');
    nosleep();
    function nosleep()
    {

        var noSleep = new NoSleep();
        noSleep.enable();
    }
</script>
<!-- AUDIO.JS -->
<script type="text/javascript">
    const CACHE_NAME = "audioCache";
    const FILE_NAME = 'https://admin.meemapp.net/notif-order.mp3';



    (function main(){
        updateStatus();
    })();

    // Update the status field
    function updateStatus() {
        isCached().then(value => {

        });
    }

    function isCached() {
        return window.caches.open(CACHE_NAME)
            .then(cache => cache.match(FILE_NAME))
            .then(Boolean);
    }

    function addToCache() {
        window.caches.open(CACHE_NAME)
            .then(cache => cache.add(FILE_NAME))
            .then(() => console.log('cached audio file'))
            .catch(e => console.error('failed to cache file', e))
            .finally(updateStatus); // This only works in chrome/ff at the time of writing
    }

    function removeFromCache() {
        window.caches.open(CACHE_NAME)
            .then(cache => cache.delete(FILE_NAME))
            .then(() => console.log('removed cached file'))
            .catch(e => console.error('failed to remove cached file', e))
            .finally(updateStatus); // This only works in chrome/ff at the time of writing
    }
</script>


<script>
    if ('serviceWorker' in navigator) {
        var file = "{!! env('APP_ASSETS') !!}js/audio_cache_sw.js"
        navigator.serviceWorker.register(file)
            .then(function(reg) {
                console.log('Registration succeeded.');
            }).catch(function(error) {
            console.log('Registration failed with ' + error);
        });
    }
</script>
<!-- END AUDIO.JS -->
<script>


    /*let src = "https://admin.meemapp.net/notif-order.mp3";
       let audio = new Audio(src);
       audio.loop = true;
       audio.play();
   */
    addToCache();
    var timeFormat = function(datetime){
        var time =datetime;
        var date = new Date(time);
        date = date.getFullYear()+"-"+date.getMonth()+"-"+date.getDate()+" "+date.getHours()+":"+date.getMinutes()+":"+date.getMilliseconds();
        return date;
    };





    var site_url = "{!! env('APP_URL') !!}";
    logged_user = parseInt(logged_user);
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
        //  $('#dataTable').DataTable();

        $("body").on("click",".notifications",function () {
            $.ajax({
                url:"{!! env('APP_URL') !!}read/notifications",
                success:function (response) {
                    $(".badge-counter").attr("data-count",0);
                    $(".badge-counter").html("");
                }
            });


        });

    })

</script>

<script>



    let src = "{!! env('APP_URL') !!}notif-order.mp3";
    let audio = new Audio(src);
    audio.loop = true;
@if(\Illuminate\Support\Facades\Auth::user()->role=="restaurant")
    @if(Route::currentRouteName()=="OrderListing" || $placed_order)
    @if((isset($is_pending_order) && !empty($is_pending_order)) || $placed_order)

    audio.play();
            @endif
            @endif
            @endif



    var pusher = new Pusher('{{env("PUSHER_APP_KEY")}}', {
            cluster: '{{env("PUSHER_APP_CLUSTER")}}',
            encrypted: true
        });


    var notification_counter = parseInt($(".badge-counter").data('count'));
    var new_order_count = parseInt($("#order-counter").html());
    if(new_order_count=="")
        new_order_count = 0;

    var channel = pusher.subscribe("{!! env('PUHER_APP_CHANNEL') !!}");
    channel.bind('App\\Events\\OrderNotification', function(data) {
        console.log('TEST Order Placiing');
        console.log(data);

        var order_resto_id = parseInt(data.order_resto_id);
        if(order_resto_id==logged_user && data.notification_for=="update-order-status"  ){

			$(".order-section").hide();
			$(".no-order").show();
            //  location.reload();
            audio.pause();
            $("#orders-list").html('');
			var after_pusher_status = $(".nav-link.active").data('status');
			if(after_pusher_status && after_pusher_status=="")
				after_pusher_status = "all";
            $.ajax({
                url:"{!! env('APP_URL') !!}liveorders/"+after_pusher_status,
                success:function (response) {

                    //response = $.parseJSON(response);

                    if(response){
                        $.each(response.orders,function (i,v) {
                            var str='<li class="mlist_li" rel="detail" data-order-id="'+v.id+'">\n' +
                                '    <div class="box '+v.box_bg+' rounded-0">\n' +
                                '        <div class="media-list media-list-divided media-list-hover">\n' +
                                '             <div class="media align-items-center">\n' +
                                '                <div class="media-body">\n' +
                                '                    <p>#'+v.order_ref+'</p>\n' +
                                '                       <p> '+(v.campaign_type!=""?v.campaign_type:"Direct")+',  '+v.created_at+'</p>\n' +
                                '                </div>\n' +
                                '                <div class="media-right gap-items">\n' +
                                '                    <div class="user-social-acount text-center">\n' +
                                '                        <p class="m-0 status">'+v.status+'</p>\n' +
                                '                        <div class="d-flex align-items-center float-end">\n' +
                                '                            <div class="circle-div '+v.bg+' text-center" data-min="'+v.remaining_min+'" data-color="'+v.bg_color+'">\n' +
                                '                                   <p class="mb-5 min">'+v.remaining_min+'<br />\n'+
                                '                                  Min</p>\n'+
                                '                            </div>\n' +
                                '                        </div>\n' +
                                '                    </div>\n' +
                                '                </div>\n' +
                                '            </div>\n' +
                                '        </div>\n' +
                                '    </div>\n' +
                                '</li>';
                            counter = counter+1;
                            $("#orders-list").append(str);
                        });
                        $("span."+status).html(counter);
                    }

                }
            });

            $.ajax({
                url: "{!! env('APP_URL') !!}order/counts",
                success: function (response) {
                    //    response = $.parseJSON(response);
                    var total_accepted = 0;
                    var total = 0;

                    $.each(response,function (i,v) {
                        if(v.status=="Send_to_Kitchen" || v.status=="Accepted"){
                            total_accepted+=parseInt(v.status_count);
                            total+=parseInt(v.status_count);
                            $(".labelcenter.kitchen").text(total_accepted);
                        }

                        if(v.status=="On_Road"){
                            $(".labelcenter.route").text(v.status_count);
                            total+=parseInt(v.status_count);
                        }


                        if(v.status=="Placed"){

                            $(".labelcenter.new").html(v.status_count);
                            total+=parseInt(v.status_count);
                            if(parseInt(v.status_count) > 0){
                                $("#order-counter").html(v.status_count);
                                $("#order-counter").show();
                            }else{
                                audio.pause();
                                $("#order-counter").html(0);
                                $("#order-counter").hide();
                            }
                        }

                    });
                    $(".labelcenter.all").text(total);
                }
            });

            $(".mlist_li[data-order-id="+data.order_id+"]").remove();
        }

        var resto_id = parseInt(data.resto_id);

        console.log('logged_user: '+logged_user+" , resto_id: "+resto_id);
        if(resto_id==logged_user){


            audio.play();

            var new_order = (data.order_data);
            var v = new_order;
            console.log("New ORder");

            var li =  '<li class="mlist_li" rel="detail" data-order-id="'+v.id+'">\n' +
                '    <div class="box bg-danger rounded-0">\n' +
                '        <div class="media-list media-list-divided media-list-hover">\n' +
                '            <div class="media align-items-center">\n' +
                '                <div class="media-body">\n' +
                '                    <p>#'+v.order_ref+'</p>\n' +
                '                    <p> '+(v.campaign_type?v.campaign_type:"Direct")+',  Just now</p>\n' +
                '                </div>\n' +
                '                <div class="media-right gap-items">\n' +
                '                    <div class="user-social-acount text-center">\n' +
                '                        <p class="m-0 status">New</p>\n' +
                '                        <div class="d-flex align-items-center float-end">\n' +
                '                            <div class="circle-div blu-bg text-center">\n' +
                '                                 <p class="mb-5 min">0<br />\n' +
                '                                Min</p>\n' +
                '                            </div>\n' +
                '                        </div>\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '            </div>\n' +
                '        </div>\n' +
                '    </div>\n' +
                '</li>';
            console.log(li);
            /* var action_list = '<div class="btn-group">\n' +
                 ' <a class="hover-primary dropdown-toggle no-caret" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></a>\n' +
                 ' <div class="dropdown-menu">\n' +
                 '  <a class="dropdown-item order-status" href="#!" data-id="'+new_order.id+'" data-status="Accepted">Accepted</a>\n' +
                 '  <a class="dropdown-item order-status" href="#!" data-id="'+new_order.id+'" data-status="Rejected">Rejected</a>\n' +
                 '  <a class="dropdown-item order-status" href="#!" data-id="'+new_order.id+'" data-status="Rejected_by_User">Rejected by User</a>\n' +
                 '                                                                    \n' +
                 ' </div>\n' +
                 ' </div>';

             var row = '<tr class="special order-row" data-id="'+new_order.id+'">';
             row+='<td>'+new_order.order_ref+'</td>';
             row+='<td>'+timeFormat(new_order.created_at)+'</td>';
             row+='<td>'+new_order.customer_name+'</td>';
             row+='<td>'+ data.customer_mobile+ '</td>';
             row+='<td>'+data.customer_location+'</td>';
             row+='<td>'+new_order.order_type+'</td>';

             // row+='<td>'+new_order.campaign_name+'</td>';
             // row+='<td>'+new_order.campaign_date+'</td>';
             row+='<td>'+new_order.campaign_type+'</td>';

             row+='<td>'+data.total_price+'</td>';





             /!* row+='<td>'+(new_order.order_deliver_time?new_order.order_deliver_time:"(اقرب وقت)\t")+'</td>';*!/

             row+='<td><span class="badge badge-info-light">Placed</span></td>';
             row+='<td>'+action_list+'</td>';
             row+='</tr>';*/

            $.ajax({
                url: "{!! env('APP_URL') !!}order/counts",
                success: function (response) {
                    // response = $.parseJSON(response);
                    var total_accepted = 0;
                    var total = 0;

                    $.each(response,function (i,v) {
                        if(v.status=="Send_to_Kitchen" || v.status=="Accepted"){
                            total_accepted+=parseInt(v.status_count);
                            total+=parseInt(v.status_count);
                            $(".labelcenter.kitchen").text(total_accepted);
                        }

                        if(v.status=="On_Road"){
                            $(".labelcenter.route").text(v.status_count);
                            total+=parseInt(v.status_count);
                        }

                        if(v.status=="Placed"){
                            $(".labelcenter.new").text(v.status_count);
                            total+=parseInt(v.status_count);
                            if(parseInt(v.status_count) > 0){
                                $("#order-counter").html(v.status_count);
                                $("#order-counter").show();
                            }

                        }

                    });
                    $(".labelcenter.all").text(total);
                }
            });
             @if(\Illuminate\Support\Facades\Auth::user()->role=="restaurant")
            @if(Route::currentRouteName()=="OrderListing")
            $("#orders-list").prepend(li);

            @endif
            @endif
            //  $("#new-order-tables tbody").prepend(row);
            //  $("#new-orders-modal").modal();
            setTimeout(function () {
                $("#new-orders-modal").modal('hide');
            },30000);
            var notification = '<li>\n' +
                '                                    <a href="{!! env('APP_URL') !!}order/show/'+data.order_id+'">\n' +
                '                                        <i class="fa fa-users text-info"></i> '+data.message+'.\n' +
                '                                    </a>\n' +
                '                                </li>';

            $("#notifications").prepend(notification);
            notification_counter += 1;
            new_order_count += 1;
            $(".badge-counter").attr('data-count',notification_counter);
            $(".badge-counter").html(notification_counter);
            $("#order-counter").html(new_order_count);
            $("#order-counter").show();
        }

    });

    $("body").on("click",".push-btn",function () {
        $(".main-sidebar").toggleClass('sidebar-active')
    });

	$("body").on("click",".change-lang",function () {
		var _lang = $(this).data('lang');
        $.ajax({
			url:"{!! env('APP_URL') !!}change/lang/"+_lang,
			success:function(){
				location.reload();
			}
		});
    });
</script>
</html>
@yield('js')
