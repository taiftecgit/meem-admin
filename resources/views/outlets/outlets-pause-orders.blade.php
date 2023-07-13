@extends('layouts.app')
@section('page-title')| {{__('label.outlet_pause_orders')}} @endsection
@section('css')
    <link href="{!! env('APP_ASSETS') !!}css/outlets.css?v=1.3" rel="stylesheet" type="text/css">
@endsection
@section('content')
    <style>
        @-webkit-keyframes special {
            from { background-color: rgba(255, 121, 77, 0.27); }
            to { background-color: inherit; }
        }
        @-moz-keyframes special {
            from { background-color: rgba(255, 121, 77, 0.27);; }
            to { background-color: inherit; }
        }
        @-o-keyframes special {
            from { background-color: rgba(255, 121, 77, 0.27);; }
            to { background-color: inherit; }
        }
        @keyframes special {
            from { background-color: rgba(255, 121, 77, 0.27);; }
            to { background-color: inherit; }
        }
        .special {
            -webkit-animation: special 1s infinite; /* Safari 4+ */
            -moz-animation:    special 1s infinite; /* Fx 5+ */
            -o-animation:      special 1s infinite; /* Opera 12+ */
            animation:         special 1s infinite; /* IE 10+ */
        }
        .btn-toggle.btn-sm,.btn-toggle.btn-sm > .handle{
            border-radius: 16px;
        }
        table.dataTable {
            clear: both;
            margin-top: 6px !important;
            margin-bottom: 6px !important;
            max-width: none !important;
            border-collapse: collapse !important;
            font-family: 'Open Sans';
        }
        .theme-primary .paging_simple_numbers .pagination .paginate_button.active a,.theme-primary .pagination li a:hover,.theme-primary .paging_simple_numbers .pagination .paginate_button:hover a{
            color: white !important;
            background: #ffa505 !important;
        }
        .search-outlet{
            width: 400px;
            background-color: white;

        }
        .search-buttom{
            top: 8px;
            right: 15px;
            font-size: 20px;
            color: #e1e1e1;
        }
        .btn-toggle.btn-sm:focus, .btn-toggle.btn-sm.focus, .btn-toggle.btn-sm:focus.active, .btn-toggle.btn-sm.focus.active{
            box-shadow: none;
        }

        .btn-outlet{

        }

        .btn-toggle.active{
            color: white !important;
            background: #ffa505;
        }
        .rdot {
    height: 10px;
    width: 10px;
    background-color: red;
    border-radius: 0%;
    display: inline-block;
}

  .gdot {
    height: 10px;
    width: 10px;
    background-color: green;
    border-radius: 0%;
    display: inline-block;
}
.pause-order{
    padding: 40px; background-color: #fff8ec; margin-bottom: 30px;
}


@media (max-width:641px) {
  .list-inline li{
    margin-right: 5px;
}
}
@media(max-width:767px)
{
    .order_status_div
    {
        text-align:left !important;
        margin-top:14px;
    }
    .sm-mt-0
    {
        margin-top: 0px !important;
    }
    button.switch-me
    {
        margin-left: 3px !important;
    }
}

    </style>
@php
$resto = \App\Models\Restaurants::find(\App\Helpers\CommonMethods::getRestuarantID());
$lang = $resto->default_lang;

app()->setLocale($lang);
if(session('app_lang') !==null){
$lang = session('app_lang');
app()->setLocale($lang);
}
@endphp

@php
//$resto = \App\Models\Restaurants::find(\App\Helpers\CommonMethods::getRestuarantID());
    $restuarant1 = $resto;
        $resto_meta = isset($restuarant1->resto_metas)?$restuarant1->resto_metas:null;

        //dump($resto_meta);
         $resto_metas = [];
            $billing = [];
            if(isset($resto_meta)){
                foreach($resto_meta as $meta){
                    if($meta->outlet_id!=""){

                      continue;
                    }
                      $index_name = isset($meta->resto_meta_defs->parents)?$meta->resto_meta_defs->parents->meta_def_name:$meta->resto_meta_defs->meta_def_name;


                  //  dump($meta->resto_meta_defs);
                    if($index_name=="BILLING_GATEWAY"){
                   //     dump($meta->resto_meta_defs->meta_def_name);
                     // $resto_metas['BILLING_GATEWAY'][] = $meta->meta_val;
                        $billing[] = array('id'=>$meta->meta_id,'value'=>$meta->meta_val);
                    }
                    $resto_metas[$index_name] = $meta->meta_val;
                }
            }
            $resto_metas['BILLING_GATEWAY'] = $billing;
            $currency = isset($resto_metas['BUSSINESS_CCY'])?$resto_metas['BUSSINESS_CCY']:"IQD";
            $business_type = isset($resto_metas['BUSSINESS_TYPE'])?trim($resto_metas['BUSSINESS_TYPE']):"Restaurants";


@endphp
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Main content -->
            <section class="content">

                <div class="row ">
                    <div class="col-md-6">
                        <div class="m-15">
                            <h3 class="title">{{__('label.pause_orders')}}</h3>

                        </div>
                    </div>

                </div>
@php
 $resto = \App\Models\Restaurants::find(\App\Helpers\CommonMethods::getRestuarantID());
@endphp
   <div class="p-15">
        @if(isset($outlets) && $outlets->count() > 0)
         @foreach($outlets as $outlet)
            <div class="pause-order">
               <div class="row">
                   <div class="col-md-6"><p class="fw-normal m-0">{!! $resto->name !!}</p>
                    <h5 class="m-0 fw-bold">{!! $outlet->name !!}</h5>
                   </div>
                   <div class="col-md-6 text-end order_status_div">
                    <ul class="list-inline">
                        <li> @if($outlet->is_delivery=="1") <span class="gdot"></span>{{__('label.delivery')}} @else<span class="rdot"></span> {{__('label.delivery_pause')}} @endif</li>
                        <li> @if($outlet->is_pickup=="1") <span class="gdot"></span> {{__('label.pickup')}}  @else <span class="rdot"></span>{{__('label.pickup_pause')}}   @endif</li>
                         @if($business_type=="Restaurants")
                         <li> @if($outlet->is_contactless_dining=="1") <span class="gdot"></span> {{__('label.dine_in')}}  @else <span class="rdot"></span> {{__('label.dine_in_pause')}} @endif</li>
                        @endif
                    </ul>

                  </div>
               </div>

               <div class="row mt-30 sm-mt-0">
                   <div class="col-md-12 col-lg-4 mb-2">
                        <button type="button"  data-on-text="Open" data-type="delivery"  data-off-text="Closed" class="switch-me btn-toggle btn-sm btn-outlet @if($outlet->is_delivery=="1") active @endif switch-me" data-id="{!! $outlet->id !!}" data-bs-toggle="button" aria-pressed="@if($outlet->is_delivery=="1") true @else false @endif" autocomplete="off">
                                            <div class="handle"></div>
                                        </button> {{__('label.accepting_delivery_orders')}}
                   </div>
                   <div class="col-md-12 col-lg-4">
                       <button type="button"  data-type="pickup"   data-on-text="Open"  data-off-text="Closed" class="switch-me btn-toggle btn-sm btn-outlet @if($outlet->is_pickup=="1") active @endif switch-me" data-id="{!! $outlet->id !!}" data-bs-toggle="button" aria-pressed="@if($outlet->is_pickup=="1") true @else false @endif" autocomplete="off">
                                            <div class="handle"></div>
                                        </button> {{__('label.accepting_pickup_orders')}}
                   </div>
                   @if($business_type=="Restaurants")
                   <div class="col-md-12 col-lg-4">
                    <button type="button"  data-type="dine-in"  data-on-text="Open"  data-off-text="Closed" class="switch-me btn-toggle btn-sm btn-outlet @if($outlet->is_contactless_dining=="1") active @endif switch-me" data-id="{!! $outlet->id !!}" data-bs-toggle="button" aria-pressed="@if($outlet->is_contactless_dining=="1") true @else false @endif" autocomplete="off">
                                            <div class="handle"></div>
                                        </button>{{__('label.accepting_dine_in')}}
                   </div>
                   @endif
               </div>
           </div>
         @endforeach
        @endif
   </div>                            



            </section>
            <!-- /.content -->
        </div>
    </div>
    <!-- /.content-wrapper -->




@endsection

@section('js')
    <script>
        $(function () {



            $("body").on("click",".switch-me",function () {
                var is_active = $(this).attr("aria-pressed");
                var id = $(this).data('id');
                var  type = $(this).data('type');
                is_active = $.trim(is_active);
                var _this = $(this);
                var status = 0;
                if(is_active=="false"){
                    status = 0;
                    _this.parents('tr').addClass('inactive');
                }else{
                    status = 1;
                    _this.parents('tr').removeClass('inactive');
                }

                $.ajax({
                    url:"{!! env('APP_URL') !!}outlet/feature/update/status",
                    type:"POST",
                    data:{
                        id:id,
                        status:status,
                        type:type,
                        "_token":'{!! csrf_token() !!}'
                    },
                    success:function () {
                        if(is_active=="false"){
                            $.toast({
                                heading: 'Outlet '+type+' Status',
                                text: 'Outlet '+type+' is deactive',
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 3000,
                                stack: 1
                            });
                        }else{
                            $.toast({
                                heading: 'Outlet '+type+' Status',
                                text: 'Outlet '+type+' is active.',
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'success',
                                hideAfter: 3000,
                                stack: 1
                            });
                        }
                    }
                });




            });
        })
    </script>
@endsection
