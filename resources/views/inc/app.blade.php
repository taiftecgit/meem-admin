<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Meem" />
    <meta name="author" content="Meem" />
    <title>{!! env('APP_NAME') !!}</title>
    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" href="{!! env('APP_ASSETS') !!}img/favicon.png">
    <!-- Feather Icon-->
    <link href="{!! env('APP_ASSETS') !!}css/vendors_css.css" rel="stylesheet" type="text/css">
    <!-- Fontawesome Icon-->
    <link href="{!! env('APP_ASSETS') !!}css/style.css" rel="stylesheet" type="text/css">
    <!-- Bootstrap Css -->
    <link href="{!! env('APP_ASSETS') !!}css/skin_color.css" rel="stylesheet"><!-- Custom Css -->

    @yield('css')


    <style>
        label.error{
            color:#FF0000;
        }

        .new-order {
            background: rgb(110 195 82 / 50%) !important;
            color: #000;
        }
        .alert{ display: none}
        #loader{
            background: #fff url({!! env('APP_ASSETS') !!}images/preloaders/1.gif) no-repeat center center;
        }
    </style>
</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed sidebar-collapse">
<div class="wrapper">
    <div id="loader"></div>

    @include('inc.header')
    @include('inc.sidebar_resto')
    @yield('content')
    @include('inc.sidebar_resto_right')

</div>
</body>

<script src="{!! env('APP_ASSETS') !!}js/vendors.min.js"></script>
<script src="{!! env('APP_ASSETS') !!}js/pages/chat-popup.js"></script>
<script src="{!! env('APP_ASSETS') !!}vendor_components/apexcharts-bundle/dist/apexcharts.min.js"></script>
<script src="{!! env('APP_ASSETS') !!}icons/feather-icons/feather.min.js"></script>

<script src="{!! env('APP_ASSETS') !!}vendor_components/progressbar.js-master/dist/progressbar.js"></script>
<script src="{!! env('APP_ASSETS') !!}vendor_components/OwlCarousel2/dist/owl.carousel.js"></script>
<script src="{!! env('APP_ASSETS') !!}vendor_components/datatable/datatables.min.js"></script>

<!-- Riday Admin App -->
<script src="{!! env('APP_ASSETS') !!}js/template.js"></script>

</html>
@yield('js')