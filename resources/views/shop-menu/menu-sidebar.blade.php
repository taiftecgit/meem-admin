<style>
    #main{
        position: relative;
    }
    #main .disabled{
        content: "";
        position: absolute;
        top: 0;
        left: -12.5px;
        width: calc(100% + 25px);
        height: 100%;
        z-index: 5;
        background: hsla(0,0%,100%,.9);
    }

    #main.active .disabled{
        display: none;}
    .btn-toggle.btn-sm,.btn-toggle.btn-sm > .handle{
        border-radius: 16px;
    }

    .btn-toggle.btn-lg,.btn-toggle.btn-lg > .handle{
        border-radius: 20px;
    }

    .btn-toggle,.btn-toggle > .handle{
        border-radius: 16px;
    }
    .outlet-status{
        background: #C1C1C1;
        padding: 30px 45px 10px;
    }
    .fixed .content-wrapper{
        padding-top: 0 !important;
    }

    .resto-menu{

    }
    .resto-menu li{
        display: inline-block;
        width: 100%;
        font-size: 1rem;
        padding: 7px;
    }
    .resto-menu li .nav-link {
        font-size: 14px;
        font-weight: 400;
        color:#000

    }

    .resto-menu li:hover,.resto-menu li.active{
        border-right: 4px solid;
        border-color: #FFAB00;
    }

    .resto-menu li:hover,.resto-menu li.active{
        border-right: 4px solid;
        border-color: #FFAB00;
    }

    .resto-menu li.active span{
        font-weight: 600;
    }


    .outlet-active{
        background-color: #FFAB00 !important;
    }
    .nav-link:hover, .nav-link:focus{

    }
    .p-outletnameP{
        padding-left: 47px;
        padding-top: 22px;
        padding-bottom: 25px;
    }
    .outlet-info-section{
        padding:30px;
    }
    /*.outlet-active{
        height: 30px !important;
        width: 90px !important;
    }

    .outlet-active:after{
        font-size: 13px !important;
        content: "Yes" !important;
    }

    .outlet-active > .handle{
        width: 2.125rem !important;
        height: 2.125rem !important;
        top:0rem !important;
    }
    .outlet-active.active > .handle {
        left: 2.1rem !important;
        transition: left .25s !important;
    }*/
</style>

<div class="row" style="--bs-gutter-x:0">
    <div class="col-12 outlet-status">
        <div class="d-flex justify-content-start mb-3">

            <div class="">
                @if(isset($menu))
                    <p style="margin-top: 0; margin-bottom: 5px">{{__('label.menu_is_active')}}</p>
                @endif

            </div>
            <div class="" style="margin-right: 10px; margin-left: 7px; margin-top: -4px">
                @if(isset($menu))

                    <button type="button" class="btn btn-sm btn-toggle btn-success @if(isset($menu) &&$menu->active=="1") active @endif outlet-active" data-bs-toggle="button" aria-pressed="@if(isset($menu) && $menu->active=="1") true @else false @endif" autocomplete="off">
                        <div class="handle"></div>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>