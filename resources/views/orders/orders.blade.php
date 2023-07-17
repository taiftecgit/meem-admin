@extends('layouts.app')
@section('page-title')| Live Orders  @endsection
@section('css')
    <link href="{!! env('APP_ASSETS') !!}css/liveorder.css?v={!! time() !!}" rel="stylesheet" type="text/css">

@endsection
@section('content')
    <style>
        #show-recipes, .calc_div{
            background-color: #fff7e8;
        }
        .row.right-panel-box{
            padding:0 10px
        }
        .lh-48
        {
            line-height:48px;
        }
        .theme-primary .nav-tabs .nav-link .labelcenter{
            position: relative;
            top: 3px;
        }
		.arrow-arabic svg{
			-webkit-transform:rotate(180deg);
			  -moz-transform: rotate(180deg);
			  -ms-transform: rotate(180deg);
			  -o-transform: rotate(180deg);
			  transform: rotate(180deg);
		}
        .print{
            color:#000 !important
        }
        .load-more-order{
            position: sticky;
            background: #ffab55;
            bottom: 0;
            right: 0;
            left: 0;
            padding: 20px;
            text-align: center;
            color: #FFF;
            font-weight: 700;
        }
        body {
            background-color: #FFF;
        }
		.discount_with{
			display:none !important;
		}
		.discount_with.active{
			display:flex !important
		}
        .delivery_discount_with{
            display:none !important;
        }
        .delivery_discount_with.active{
            display:flex !important
        }
		#print-recipt{
            display: none; }
        .mlist_li.selected{
            color: #000;
            background-color: #f1f1f1;
        }
        @if(app()->getLocale()=="ar")
			 #show-recipes .box-header{
            padding: 15px 1.2rem 5px 0;
        }
        @else
        #show-recipes .box-header{
            padding: 15px 0 5px 1.2rem;
        }
        @endif
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
        .mlist_li.selected .media, .mlist_li.selected .box{
            /*background-color: transparent !important;*/
        }

        .mlist_li.selected p{
            color:#000 !important;
        }

        .labelcenter { width: auto}
        .gap-items p.status{ font-size: 12px}
        .gap-items p.min{
            line-height: 13px;
            padding-top: 11px;
            font-size: 11px;
        }

        .bg-danger p.min, .bg-danger .gap-items p.status{
            display: none;}

        .bg-danger .circle-div{ height: 10px; width: 10px; background: #fff !important; margin-right: 15px;}


        .mlist_li.selected .media-list-hover > .media:not(.media-list-header):not(.media-list-footer):hover,.mlist_li.selected .media-list-hover .media-list-body > .media:hover{
            background-color: transparent !important;
        }

         .mlist_li.selected .media-list{
            color: #000;
            background-color: #f1f1f1 !important;
        }


        .sidebar-mini.sidebar-collapse .content-wrapper {
            width: calc(100% - 60px) !important;
            margin-left: 60px !important;
        }
        .no-order-found{

            background-color: #000;
            background-image: url({!! env('APP_ASSETS') !!}images/no-order.png);
            background-repeat: no-repeat;
            background-position: center;
        ;
        }

		.actions{
			position:absolute; top: 20px; right: 10px;
		}
		.actions li{ margin:0 !important; padding:0}
        @media screen and (min-device-width: 1200px) and (max-device-width: 1600px) and (-webkit-min-device-pixel-ratio: 1){
            body {
                padding: 0px !important;
            }
        }

		 @media (max-width:767px){
			 .actions {
				position: absolute;
				top: 20px;
				right: 15px;
			}

			 .calc_div{
				 padding: 14px;
			 }

					.mlist_li.selected p {
    color: #fff!important;
}
			.mlist_li.selected .media-list {
    color: #fff;
    background-color: transparent!important;
}
/*------Start Arabic css---------------*/

html[dir="rtl"].nav-tabs{
   padding-right:0 !important; 
}
/*------end Arabic css---------------*/
body.fixed {
	overflow-y:hidden;
		}


				 .for-mobile{
/*					 max-height: 330px; */
					 max-height: 60vh;
					 overflow: auto;

				 }

				 #show-recipes .box-header {
						padding:0rem 18px 0 12px !important;
					}
				 .right-panel-footer p{margin-bottom: 5px !important;
    padding: 0 !important;}
				 .button-bottom-div{margin-top: 0px !important;}

			 }

			.box-header p {
				margin-bottom: 0;
			}
			.box-header {
				padding: 1.5rem 0.5rem;
			}
			@media (max-width:767px){
				.box-header {
					padding: 0.5rem 14px;
				}
			 }
             @media (max-width: 1024px){
                .navbar-custom-menu .navbar-nav > li:last-child > a {
                    line-height: 25px;
                    margin-left: 30px !important;
                }
            }
            @media (min-width: 768px) and (max-width: 1024px) {

                body{
                    overflow-x:hidden !important;
                    overflow-y:hidden !important;
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
            $business_type = isset($resto_metas['BUSSINESS_TYPE'])?$resto_metas['BUSSINESS_TYPE']:"Restaurants";
            $is_allow_print_preview = isset($resto_metas['PRINT_PREVIEW_ON_ACCEPT_ORDER'])?$resto_metas['PRINT_PREVIEW_ON_ACCEPT_ORDER']:"Disabled";

@endphp
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper p-0">
        <div id="countdown"></div>

        <div class="container-full">
            <section class="content p-0">

                <div class="row">
                    <div class="col-sm-12 p-0">
                        <nav class="navbar navbar-static-top p-0">
                            <!-- Sidebar toggle button-->
                            <div class="row sm-m-0">


                                <div class="col-md-12 tab-bar-section">
                                    <div class="app ovrflow-x-auto">
                                        <ul class="hs full nav nav-tabs" role="tablist">

                                            <li class="item nav-item d-inline">
                                                <div class="gap-items-4">

                                                    <a class="nav-link p-0 active lh-48" data-bs-toggle="tab" href="#new-orders"  data-status="new" role="tab">{{__('label.new')}}
                                                        <span class="labelcenter label-default new">0</span></a>


                                                </div>

                                            </li>
                                            <li class="item nav-item d-inline">
                                                <div class="gap-items-4">

                                                    <a class="nav-link p-0 lh-48" data-bs-toggle="tab" href="#in-kitchen"  data-status="kitchen" role="tab">{{__('label.in_preparation')}}
                                                        <span class="labelcenter label-default kitchen">0</span></a>

                                                </div>

                                            </li>
                                            <li class="item nav-item d-inline">
                                                <div class="gap-items-4">
                                                    <a class="nav-link p-0 lh-48" data-bs-toggle="tab" href="#in-route"  data-status="route" role="tab" >{{__('label.in_routeready')}}
                                                        <span class="labelcenter label-default route">0</span></a>

                                                </div>

                                            </li>

                                            <li class="item nav-item d-inline">
                                                <div class="gap-items-4">
                                                    <a class="nav-link p-0  lh-48" data-bs-toggle="tab" href="#all-orders" data-status="all" role="tab">{{__('label.all')}}
                                                        <span class="labelcenter label-default all">0</span></a>

                                                </div>
                                            </li>

                                        </ul>
                                    </div>
                                </div>




                            </div>
                            <!-- <div class="app-menu"></div> -->

                            <a href="#" class="d-inline-block btn right-bar-btn waves-effect waves-circle btn btn-circle btn-danger btn-sm right-close-btn">
                                <i class="mdi mdi-close"></i>
                            </a>
                        </nav>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-4 order-sections-list" style="background-color: #f1f1f1">
                        <div class="row p-10">
                            <div class="col-md-12 mt-5">
                                <div class="search-bx mx-5">
                                    <form>
                                        <div class="input-group search-div">
                                            <input type="search" class="form-control" style="background-color: transparent" placeholder="{{__('label.search_by_order_id')}}" aria-label="Search" aria-describedby="button-addon2">
                                            <div class="input-group-append">
                                                <button class="btn btn-sarch" type="submit" id="button-addon3"><i class="ti-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5 list_row">
                            <div class=" col-md-12 p-0">

                                <div class="tab-content">
                                    <div class="tab-pane fade in active all-orders position-relative" id="all-orders" role="tabpanel" style="max-height: 82vh; min-height: 82vh; overflow: auto;">
                                        <div class="p-0">
                                            <ul class="list-group list-ul list-unstyled orders-list"  id="orders-list">

                                                {{--<li class="mlist_li p-5 mb-5 pl-15">
                                                    <div class="d-grid gap-2 d-flex justify-content-center">
                                                        <button class="btn btn-md btn-primary ref-btn">Refresh</button>
                                                    </div>
                                                </li>--}}
                                            </ul>

                                            <div class="load-more-order" data-start-page="2" data-number-of-items="{!! env('ORDER_NUMBER_ITEMS') !!}">Load More Orders</div>

                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 p-0 box-shadowed">
                        <div class="no-order" style="padding-top: 50%; height: 90vh ;">
                            <h3 class="text-center text-light">{{__('label.no_order_selected')}}</h3>
                        </div>
                        <div class="order-section" style="display: none; height: 90vh ; overflow-x: auto">
                            <div class="row back-to-orders d-md-none d-lg-none">
                                <div class="col-sm-12 text-center @if($lang=='ar') arrow-arabic @endif">
									@if($lang=="en")
                                    <svg style="    filter: drop-shadow(1px 0px 0px black);" xmlns="http://www.w3.org/2000/svg" width="13.917" height="9.785" viewBox="0 0 13.917 9.785">
  <path id="Shape_878" data-name="Shape 878" d="M301.31,1645.861h-7.36v2.854l-6.557-4.9,6.548-4.889v2.853h7.369Zm-8.177,1.227v-2.051h7.354v-2.443h-7.364v-2.036l-4.368,3.261Z" transform="translate(-287.393 -1638.93)"/>
</svg>
									{{__('label.live_orders')}}
									@else

									 <svg style="    filter: drop-shadow(1px 0px 0px black);" xmlns="http://www.w3.org/2000/svg" width="13.917" height="9.785" viewBox="0 0 13.917 9.785">
  <path id="Shape_878" data-name="Shape 878" d="M301.31,1645.861h-7.36v2.854l-6.557-4.9,6.548-4.889v2.853h7.369Zm-8.177,1.227v-2.051h7.354v-2.443h-7.364v-2.036l-4.368,3.261Z" transform="translate(-287.393 -1638.93)"/>
</svg> {{__('label.live_orders')}}

									@endif
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-sm-12">
                                    <div class="boxs">
                                        <div class="box-header with-border">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p class="fw-bold" rel="order_ref"></p>
                                                <p rel="order_placed"></p>
                                                <span class="inkitchen-btn badge badge-warning " id="toggle"></span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-sm-12 mt-1 @if(app()->getLocale()=="ar") text-start @else text-end @endif  px-0 pt-1 ">
                                        <a href="#!" class="print">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-printer-fill" viewBox="0 0 16 16">
                                                <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2H5zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1z"/>
                                                <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2V7zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
								<div class="for-mobile">

                                <div class="row right-panel-box">
                                    <div class="col-6">
                                        <div class="boxs">
                                            <div class="box-body">
                                                <div class="d-flex align-items-start">
                                                    <div>
                                                        <p class="text-fade mb-0">{{__('label.brand')}}</p>
                                                        <p rel="brand_name"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="boxs">
                                            <div class="box-body">
                                                <div class="d-flex align-items-start">
                                                    <div>
                                                        <p class="text-fade mb-0">{{__('label.outlets')}}</p>
                                                        <p rel="outlet_name"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row right-panel-box">
                                    <div class="col-6">
                                        <div class="boxs">
                                            <div class="box-body">
                                                <div class="d-flex align-items-start">
                                                    <div>
                                                        <p class="text-fade mb-0">{{__('label.order_type')}}</p>
                                                        <p>
                                                            <i class="icon-Dinner"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                            <span rel="order_type"></span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 for-delivery">
                                        <div class="boxs">
                                            <div class="box-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="">
                                                        <p class="text-fade mb-0">{{__('label.delivery_at')}}</p>
                                                        <p style="direction: ltr !important;" rel="delivery_at"></p>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6  for-pickup" style="display:none">
                                        <div class="boxs">
                                            <div class="box-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="">
                                                        <p class="text-fade mb-0">{{__('label.pickup_at')}}</p>
                                                        <p  rel="delivery_at"></p>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row right-panel-box">
                                    <div class="col-6">
                                        <div class="boxs">
                                            <div class="box-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="">
                                                        <p class="text-fade mb-0">{{__('label.customers')}}</p>
                                                        <p rel="customer"> <br>
                                                            <i class="mdi mdi-crown org-color"></i>
                                                            <small class="org-color">Ordered 5 times </small>
                                                        </p>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="boxs">
                                            <div class="box-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="">
                                                        <p class="text-fade mb-0">{{__('label.phone_number')}}</p>
                                                        <p rel="phone"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row right-panel-box">
                                    <div class="col-6">
                                        <div class="boxs">
                                            <div class="box-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="">
                                                        <p class="text-fade mb-0">{{__('label.payment_mode')}}</p>
                                                        <p rel="payment"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="boxs">
                                            <div class="box-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="">
                                                        <p class="text-fade mb-0">{{__('label.channel')}}</p>
                                                        <p rel="channel"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row right-panel-box">
                                    <!--<div class="col-md-6" id="address">
                                        <div class="boxs">
                                            <div class="box-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="">
                                                        <p class="text-fade mb-0">Address</p>
                                                        <p rel="address"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>-->

                                    <div class="col-6" id="table" style="display:none">
                                        <div class="boxs">
                                            <div class="box-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="">
                                                        <p class="text-fade mb-0">{{__('label.table')}}</p>
                                                        <p rel="for_table"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-6 for-delivery">
                                        <div class="boxs">
                                            <div class="box-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="">
                                                        <p class="text-fade mb-0">
														{{__('label.order_notes')}}</p>
                                                        <p rel="delivery_notes">
														</p>
														<p rel="order_instructions">
														</p>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>





								<div class="row right-panel-box">
                                    <div class="col-md-12">
                                        <div class="boxs" style="position: relative">
                                            <div class="box-body address_action_div">
                                                <div class="d-flex align-items-start">
                                                    <div class="">
                                                        <p class="text-fade mb-0">{{__('label.address')}}</p>
                                                        <p rel="address" style="max-width: 80%"></p>
                                                    </div>
                                                </div>
                                            </div>

											<div class="actions">

												<ul class="list-inline">
													<li  class="list-inline-item"><a href="#!" class="address-actions" data-action="copy-clipboard"><svg data-v-10e82b3e="" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="" style="color: var(--color-midnight); width: 20px; height: 20px;"><path fill-rule="evenodd" d="M16 5.5H6a.5.5 0 00-.5.5v10a.5.5 0 00.5.5h10a.5.5 0 00.5-.5V6a.5.5 0 00-.5-.5zM6 4a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2H6z" clip-rule="evenodd"></path><path fill-rule="evenodd" d="M1.25 4A2.75 2.75 0 014 1.25h6a.75.75 0 010 1.5H4c-.69 0-1.25.56-1.25 1.25v6a.75.75 0 01-1.5 0V4z" clip-rule="evenodd"></path></svg></a></li>
													<li  class="list-inline-item"><a href="#!"  class="address-actions" data-action="google-map"><svg data-v-2740a47f="" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg" class="" style="width: 20px; height: 20px;"><path d="M16.45 7.7c0 3.152-2.19 4.965-3.831 6.72-.983 1.051-1.769 4.48-1.769 4.48s-.784-3.426-1.764-4.475C7.443 12.671 5.25 10.855 5.25 7.7a5.6 5.6 0 1111.2 0v0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M8.61 7.7a2.24 2.24 0 104.48 0 2.24 2.24 0 00-4.48 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg></a></li>
													<li  class="list-inline-item">
														<a href="#!"   class="address-actions" data-action="whatsapp">
														<svg data-v-2740a47f="" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg" class="" style="width: 20px; height: 20px;"><path fill-rule="evenodd" clip-rule="evenodd" d="M4.071 4.013a9.192 9.192 0 016.502-2.663 9.192 9.192 0 019.207 9.143v.01A9.222 9.222 0 015.803 18.33l-2.92.925a.75.75 0 01-.937-.954l.932-2.763a9.027 9.027 0 01-1.528-5.042v-.003a9.192 9.192 0 012.721-6.48zM10.572 2.1l-.002.75a7.692 7.692 0 00-7.72 7.65 7.527 7.527 0 001.464 4.473.75.75 0 01.107.685l-.58 1.72 1.84-.583a.75.75 0 01.638.088 7.722 7.722 0 0011.96-6.387 7.692 7.692 0 00-7.705-7.646l-.002-.75zM8.548 5.878c.223.115.47.313.606.628.055.126.132.314.212.51l.177.43c.07.167.134.316.186.428.026.056.045.096.058.12v.002a1.149 1.149 0 01.058 1.17 1.27 1.27 0 01-.282.38 5.126 5.126 0 01-.07.063l-.048.045a6.172 6.172 0 002.386 2.02c.085-.116.18-.253.252-.367.156-.246.41-.47.777-.513.277-.032.52.062.614.098l.009.004c.2.076 1.063.497 1.425.673l.136.066c.019.009.04.02.064.03.077.037.173.082.246.123.085.047.297.166.434.4.04.068.067.14.083.213a1.81 1.81 0 01-.216 1.572c-.796 1.379-2.173 1.81-3.55 1.667-1.356-.14-2.785-.827-3.962-1.76-1.178-.934-2.19-2.183-2.63-3.538-.452-1.393-.293-2.904.899-4.163.401-.436 1-.53 1.429-.496.233.018.482.078.707.195zm5.91 7.139l-.066-.031-.175-.085a155.61 155.61 0 00-1.056-.508 6 6 0 01-.39.508c-.133.15-.35.327-.67.366a1.22 1.22 0 01-.686-.133 7.672 7.672 0 01-3.436-2.979l-.01-.016a1.033 1.033 0 01-.085-.922c.086-.216.248-.379.276-.408a8.417 8.417 0 01.24-.234 15.217 15.217 0 01-.242-.552l-.19-.463a46.22 46.22 0 00-.15-.365.524.524 0 00-.313.012c-.76.8-.875 1.717-.565 2.672.323.995 1.113 2.016 2.135 2.826 1.021.81 2.188 1.34 3.184 1.443.963.1 1.683-.193 2.108-.943a.728.728 0 01.038-.061.312.312 0 00.052-.127zM7.515 7.194l-.002.003.002-.003zm.346.018H7.86h.002zm4.226 4.58zm-2.29-3.779z" fill="currentColor"></path></svg>
														</a>
													</li>
												</ul>

											</div>
                                        </div>
                                    </div>
								</div>
                                @if( $business_type=="Florist")
                                <div class="row right-panel-box" >
                                     <div class="col-6">
                                        <div class="boxs">
                                            <div class="box-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="">
                                                        <p class="text-fade mb-0">{{__('label.receiver_name')}}</p>
                                                        <p rel="recipient_name"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="boxs">
                                            <div class="box-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="">
                                                        <p class="text-fade mb-0">{{__('label.receiver_phone')}}</p>
                                                        <p rel="recipient_phone"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="row right-panel-box" >
                                    <div class="col-md-12">
                                        <div class="boxs">
                                            <div class="box-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="">
                                                        <p class="text-fade mb-0">{{__('label.greeting_message')}}</p>
                                                        <p rel="greeting_message"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @endif
                                    <div class="col-12" id="show-recipes"></div>
                                    <div class="col-12 calc_div">
                                        <div class="box-header border-0 p-15 mt-0 pb-0">
                                            <div class="d-flex justify-content-between align-items-center m-0">
                                                <p>{{__('label.sub_total')}}:</p>
                                                <p class="sub_total"></p>
                                            </div>
											<div class="discount_with text-success d-flex justify-content-between align-items-center m-0">
                                                <p>{{__('label.discounts')}}:</p>
                                                <p class="discount_total">AED 0</p>
                                            </div>
                                            <div id="only-delivery">
                                                <div class="d-flex justify-content-between align-items-center m-0">
                                                    <p>{{__('label.delivery_fee')}}:</p>
                                                    <p class="delivery_fee"></p>
                                                </div>

                                                <div class="delivery_discount_with text-success d-flex justify-content-between align-items-center m-0">
                                                    <p>{{__('label.delivery_discounts')}}:</p>
                                                    <p class="delivery_discount_total">AED 0</p>
                                                </div>
                                            </div>

                                            <div class="d-flex fw-bold justify-content-between align-items-center m-0">
                                                <p class="">{{__('label.total')}}:</p>
                                                <p class="total_txt"></p>
                                            </div>
                                        </div>
                                    </div>
							</div>




                            </div>
                            <div class="row mt-0 right-panel-footer footer_btn_div">



                                <div class="col-12 mt-5 button-bottom-div">
                                    <div class="m-0 text-center">
                                        <button type="button" class="waves-effect waves-light btn btn-outline btn-danger mb-0 btn-w80 rounded-0 rclose-btn reject change-status-reject" data-status="Rejected" style="display: none">{{__('label.reject')}}</button>
                                        <button type="button" class="waves-effect waves-light btn btn-outline btn-dark mb-0 btn-w50 rounded-0 rclose-btn accept change-status-reject" data-status="Close"  style="display: none">{{__('label.close')}}</button>
                                        <button type="button" class="waves-effect waves-light btn btn-outline btn-danger mb-0 btn-w50 rounded-0 accept change-status-reject" data-status="Rejected"  style="display: none">{{__('label.cancel')}}</button>
                                        <button type="button" class="waves-effect waves-light btn btn-default btn-w80 rounded-0 ready-collect change-status " data-status="">{{__('label.accept')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
            <!-- /.content -->

        </div>




    </div>
    <!-- /.content-wrapper -->




    <div class="modal center-modal fade" id="accept-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-body">
                    <h4>{{__('label.order_settings')}}</h4>
                    <p>{{__('label.set_time_preparing_orders')}}</p>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{__('label.preparation_minutes')}}</label>
                                <input type="text" name="preparation_time" class="form-control" value=""  />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{__('label.preparation_delivery_minutes')}}</label>
                                <input type="text"  name="preperation_delivery" class="form-control" value=""  />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>{{__('label.type')}}</label>
                                <select class="form-control" name="preparation_type">
                                    <option value="MINUTES">{{__('label.minutes')}}</option>
                                    <option value="HOURS">{{__('label.hours')}}</option>
                                    <option  value="DAYS">{{__('label.days')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{__('label.close')}}</button>
                    <button type="button" class="btn btn-primary float-end accept-order">{{__('label.accept')}}</button>
                </div>
            </div>
        </div>
    </div>

<div id="print-recipt"> </div>
@endsection

@section('js')
    <script src="{!! env('APP_ASSETS') !!}js/jquery.jscroll.min.js"></script>
    <script src="{!! env('APP_ASSETS') !!}js/jquery.countdown360.min.js"></script>
    <script src="{!! env('APP_ASSETS') !!}vendor_components/sweetalert/sweetalert.min.js"></script>
    <script>
        var resto_id = 0;
        var status = "new";
        var counter = 0;
        var order_id = null;
		var order_object = null;
        $(function () {

            count_order_status();

            $("body").on("click",".back-to-orders",function () {
                $(".order-section").hide();
            });

			$("body").on("click",".address-actions",function(){
				var _actions = $(this).data("action");
				if(_actions=="google-map"){
					var map = "https://maps.google.com/?q="+order_object.geo_location;
					window.open(map,"_blank");
					return false;
				}

				if(_actions=="whatsapp"){
					var template = order_text_template(order_object,"whatsapp");
					//console.log(encodeURIComponent(template));
					window.open("https://api.whatsapp.com/send?text="+(template),"_blank");
				}

				if(_actions=="copy-clipboard"){
					var template = order_text_template(order_object,"copy-clipboard");
					//console.log(encodeURIComponent(template));
					navigator.clipboard.writeText(template);
				}



			});

			$("body").on("click",".print",function () {
                $.ajax({
                    url:"{!! env('APP_URL') !!}order/print/"+order_id,
                    success:function (response) {
                        $('#print-recipt').html(response);
                        $('#print-recipt').printThis();
                    }
                });

            });




            $("body").on("click",".load-more-order",function(){
                var _start_page = $(this).data('start-page');
                var _number_of_orders =  $(this).data('number-of-items');


                $.ajax({
                    url:"{!! env('APP_URL') !!}liveorders",
                    type:"POST",
                    data:{
                        "_token":"{!! csrf_token() !!}",
                        status:status,
                        start_page:_start_page,
                        number_of_orders:_number_of_orders
                    },
                    success:function (response) {

                        // response = $.parseJSON(response);
                        console.log(response);

                        if(response){
                            if(response.type=="success"){
                                $.each(response.orders,function (i,v) {
                                    if(i!="pagination"){
                                        var str='<li class="mlist_li" rel="detail" data-order-id="'+v.id+'">\n' +
                                            '    <div class="box '+v.box_bg+' rounded-0">\n' +
                                            '        <div class="media-list media-list-divided media-list-hover">\n' +
                                            '            <div class="media align-items-center">\n' +
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
                                    }else{

                                        $('div.load-more-order').attr('data-start-page',v.next_page);
                                        $('div.load-more-order').data('start-page',v.next_page);
                                        //$("#orders-list").append(str);
                                    }
                                });
                                $("span."+status).html(counter);









                            }else{
                                $('div.load-more-order').html("No more order found");
                              //  $("#all-orders").addClass('no-order-found');
                            }

                        }

                    }
                });
            });

            $("#all-orders").removeClass('no-order-found');
            $.ajax({
                url:"{!! env('APP_URL') !!}liveorders",
                type:"POST",
                data:{
                    "_token":"{!! csrf_token() !!}",
                    status:status,
                    start_page:1,
                    number_of_orders:100
                },
                success:function (response) {

                    // response = $.parseJSON(response);
                     console.log(response);

                    if(response){
                        if(response.type=="success"){
                            $.each(response.orders,function (i,v) {
                                if(i!="pagination"){
                                var str='<li class="mlist_li" rel="detail" data-order-id="'+v.id+'">\n' +
                                    '    <div class="box '+v.box_bg+' rounded-0">\n' +
                                    '        <div class="media-list media-list-divided media-list-hover">\n' +
                                    '            <div class="media align-items-center">\n' +
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
                                }else{
                                    console.log(v);
                                    $('div.load-more-order').attr('data-start-page',v.next_page);
                                    //$("#orders-list").append(str);
                                }
                            });
                            $("span."+status).html(counter);









                        }else{
                            $("#all-orders").addClass('no-order-found');
                        }

                    }

                }
            });
            $("body").on("click",".nav-link",function () {
                $("#orders-list").html('');
                $(".no-order").show();
                $(".order-section").hide();
                status = $(this).data('status');
                $("#all-orders").removeClass('no-order-found');
                var counter = 0;
                $.ajax({
                    url:"{!! env('APP_URL') !!}liveorders",
                    type:"POST",
                    data:{
                        "_token":"{!! csrf_token() !!}",
                        status:status,
                        start_page:1,
                        number_of_orders:"{!! env('ORDER_NUMBER_ITEMS') !!}"
                    },
                    success:function (response) {

                        //    response = $.parseJSON(response);

                        if(response){
                            if(response.type=="success"){



                                $.each(response.orders,function (i,v) {
                                    var str='<li class="mlist_li" rel="detail" data-order-id="'+v.id+'">\n' +
                                        '    <div class="box '+v.box_bg+' rounded-0">\n' +
                                        '        <div class="media-list media-list-divided media-list-hover">\n' +
                                        '            <div class="media align-items-center">\n' +
                                        '                <div class="media-body">\n' +
                                        '                    <p>#'+v.order_ref+'</p>\n' +
                                        '                    <p> '+(v.campaign_type!=""?v.campaign_type:"Direct")+',  '+v.created_at+'</p>\n' +
                                        '                </div>\n' +
                                        '                <div class="media-right gap-items">\n' +
                                        '                    <div class="user-social-acount text-center">\n' +
                                        '                        <p class="m-0 status">'+v.status+'</p>\n' +
                                        '                         <div class="circle-div '+v.bg+' text-center" data-min="'+v.remaining_min+'" data-color="'+v.bg_color+'">\n' +
                                        '                                   <p class="mb-5 min">'+v.remaining_min+'<br />\n'+
                                        '                                  Min</p>\n'+
                                        '                            </div>\n' +
                                        '                    </div>\n' +
                                        '                </div>\n' +
                                        '            </div>\n' +
                                        '        </div>\n' +
                                        '    </div>\n' +
                                        '</li>';

                                    $("#orders-list").append(str);



                                    counter = counter+1;
                                });
                            }else{
                                $("#all-orders").addClass('no-order-found');
                            }

                            //$("span."+status).html(counter);
                        }

                    }
                });
            });

			var _already_clicked = false;



            $("body").on("click","li[rel=detail]",function () {

				if(!_already_clicked){

				_already_clicked = true;

                var id = $(this).data("order-id");
                order_id = id;

                $(".mlist_li.selected").removeClass('selected');
                $("#show-recipes").html('');

                $(this).addClass('selected');
					$(".discount_with").removeClass('active');
                    $(".delivery_discount_with").removeClass('active');
                $.ajax({
                    url:"{!! env('APP_URL') !!}get/order/detail/"+id,
                    success:function (response) {
						_already_clicked = false;
                        response = $.parseJSON(response);
                        $("#accept-modal input[name=preparation_time]").val(response.estimated_code.preparation_time);
                        $("#accept-modal input[name=preperation_delivery]").val(response.estimated_code.preparation_delivery_time);
                        $("#accept-modal select[name=preparation_type]").val(response.estimated_code.time_type);
 						order_object = response;
						var discount_with_order = response.discount_with_order?response.discount_with_order:null;

                        var price = 0;
                        var delivery_fee = 0;

                        $(".change-status").attr('data-id',id);
                        $(".change-status-reject").attr('data-id',id);

                        var o_t = response.order_type.toLowerCase();
                       $(".for-delivery").hide();
                       $(".for-pickup").hide();

                       $(".for-"+o_t).show();

                        if(o_t=="pickup"){
                            $("#only-delivery").hide();
                        }

                        var discounted_delivery = 0;
                        $.each(response,function (i,v) {
                            $("p[rel="+i+"]").text(v);
                            $("span[rel="+i+"]").text(v);

                            if(i=="delivery_fee"){
                                delivery_fee = v;
                                if(discount_with_order){

                                    if(discount_with_order.is_delivery_discount=="Yes" && o_t=="delivery"){
                                      //  alert('test');
                                        if(discount_with_order.delivery_discount_type=="percentage"){
                                             discounted_delivery =  (parseFloat(delivery_fee) * (parseFloat(discount_with_order.delivery_discount_value) /100));
                                            console.log('discounted_delivery : '+discounted_delivery);
                                            // delivery_fee = delivery_fee - ()
                                        }
                                    }
                                    $(".delivery_discount_total").html("{{ $currency }} "+discounted_delivery);
                                    $(".delivery_discount_with").addClass('active');
                                }

                                $(".delivery_fee").html("{{ $currency }} "+v);
                            }



                            if(i=="geo_location"){

                                $("p[rel=google-map-link]").html('<a target="_blank" href="https://maps.google.com/?q='+v+'">Share</a>');
								//$("p[rel=google-map-link]").html('<a target="_blank" href="https://maps.googleapis.com/maps/api/staticmap?zoom=15&size=900x500&markers=color:yellow|label:D|'+v+'&key=AIzaSyBFh6fzq8G7dgWLfz8kccvTlmPCSI_uWXQ">Share</a>');
                            }

                           if(i=="order_type"){
                                if(v=="Dine in"){
                                    $("#address").hide();
                                    $("#table").show();
                                    $("#google-map-link").hide();
                                }else{
                                     $("#address").show();
                                    $("#table").hide();
                                     if(v=="Delivery")
                                    $("#google-map-link").show();
                                else
                                    $("#google-map-link").hide();
                                }


                            }

                            if(i=="status"){
                                $(".inkitchen-btn").html(v);
                                $(".inkitchen-btn").addClass();

                                if(v=="New"){
                                    $(".reject").show();
                                    $(".accept").hide();
                                }else{
                                    $(".accept").show();
                                    $(".reject").hide();

                                }
                            }

                            if(i=="bg"){
                                $(".inkitchen-btn").removeClass('org-bg');
                                $(".inkitchen-btn").removeClass('bg-danger');
                                $(".inkitchen-btn").removeClass('green-bg');
                                $(".inkitchen-btn").addClass(v);


                            }

                            {{--if(i=="next_action"){--}}
                            {{--    if(v=="Accepted")--}}
                            {{--        $(".change-status").html("{{__('label.accept')}}");--}}
                            {{--        else--}}
                            {{--    $(".change-status").html(v);--}}
                            {{--}--}}
                            if(i=="next_status_label"){

                                    $(".change-status").html(v);
                            }
                            if(i=="next_status"){
                                $(".change-status").attr('data-status',v);
                            }

var discounted_price = 0;
                            if(i=="recipes"){
                                var recipes = v;
                                if(recipes){
										console.log(recipes);
                                    /*$.each(recipes,function(m,n){
                                        console.log(n);
                                        $("#show-recipes").append(show_recipe(n));
                                        price+=parseFloat(n.total_price);
                                        console.log("price: "+price);
                                         @if($business_type!="ClothsStore")
                                        if(n.extra_options){
                                            $.each(n.extra_options,function(c,d){
                                                price+=(parseInt(n.quantity) * parseFloat(d.price));
                                            });
                                        }
                                        @endif
                                    });*/

									if(discount_with_order){

										$.each(recipes,function(m,n){



                                        $("#show-recipes").append(show_recipe_with_discount(n,discount_with_order));

											 price+=parseFloat(n.total_price);


										});



									}else{
										$.each(recipes,function(m,n){



                                        $("#show-recipes").append(show_recipe(n));
										var discount_amount = n.discount_amount!=""?parseFloat(n.discount_amount):0;
										var discount_type = n.discount_type!=""?n.discount_type:"";



										if(discount_amount > 0 && discount_type!=""){
											if(discount_type=="%"){
												var d_price = parseFloat(n.total_price) - (parseFloat(n.total_price) * (discount_amount/100));
												console.log(d_price);
												price+=d_price;
											}else{
												var d_price = parseFloat(n.total_price) - discount_amount/100;
												price+=d_price;
											}

										}else{
											console.log(n);
											 price+=parseFloat(n.total_price);
										}


                                        console.log("price: "+price);
                                         @if($business_type!="ClothsStore")
                                        if(n.extra_options){
                                            $.each(n.extra_options,function(c,d){
												if(d.price && d.price!=""){
												if(discount_amount > 0 && discount_type!=""){
											if(discount_type=="%"){
												var d_price = parseFloat(d.price) - (parseFloat(d.price) * (discount_amount/100));
												console.log(d_price);
												price+=d_price;
											}else{
												var d_price = parseFloat(d.price) - discount_amount/100;
												price+=d_price;
											}

										}else{
											  price+=(parseInt(n.quantity) * parseFloat(d.price));
										}
												}

                                            });
                                        }
                                        @endif
                                    });
									}



									  if(price==0)
										price=response.total_price;

                                    $(".sub_total").html('{{ $currency }}  '+(price?price:0));

                                }
                            }
								if(discount_with_order){
											discounted_price = discount_with_order.discounted_amount?discount_with_order.discounted_amount:0;
											$(".discount_total").html('{{ $currency }}  '+(discount_with_order.discounted_amount?discount_with_order.discounted_amount:0));
											$(".discount_with").addClass('active');
										}

                            $(".total_txt").html("{{ $currency }}  "+((price?price:0)+delivery_fee-discounted_price-discounted_delivery));

                        });
                    }
                });
                $(".no-order").hide();
                $(".order-section").show();

					}
            });

            $('ul.pagination').hide();
            $('#all-orders').jscroll({
                autoTrigger: true,
                loadingHtml: '<img class="text-center" src="{!! env('APP_ASSETS') !!}images/preloaders/preloader.svg" alt="Loading..." />',
                padding: 0,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.mlist_li',
                callback: function() {

                    $('ul.pagination').remove();
                }
            });

            $("body").on("click",".nav-link",function () {
                $(".nav-link").removeClass('active')
                $(this).addClass('active')
            });


            $("body").on("click",".order-row",function () {
                var id = $(this).data('id');
                window.location = "{!! env('APP_URL') !!}order/show/"+id;
            });

            $("body").on("click",".accept-order",function () {
                 var _this = $(this);
                var preparation_time = $("input[name=preparation_time]").val();
                var preperation_delivery = $("input[name=preperation_delivery]").val();
                var preparation_type = $("select[name=preparation_type]").val();

                if(preperation_delivery==""){
                    $("input[name=preperation_delivery]").focus();
                    return false;
                }

                if(preparation_time==""){
                    $("input[name=preparation_time]").focus();
                    return false;
                }

                _this.attr('disabled','disabled');
                _this.html(progress_img);
                _this.removeClass('btn-primary');
                _this.addClass('btn-muted');

                $.ajax({
                    url:"{!! env('APP_URL') !!}update/order/status",
                    type:"POST",
                    data:{
                        id:order_id,
                        status:"Accepted",
                        preparation_time:preparation_time,
                        preperation_delivery:preperation_delivery,
                        preparation_type:preparation_type,

                        "_token":"{!! csrf_token() !!}"
                    },
                    success:function () {
                        // alert();
                        $(".mlist_li.selected").remove();

                            //$("#orders-list").html('');
                             _this.removeAttr('disabled');
                        _this.html('Accept Order');
                        _this.removeClass('btn-muted');
                        _this.addClass('btn-primary');

                        count_order_status();




                        $(".no-order").show();
                        $(".order-section").hide();

                        $("#accept-modal").modal('hide');
						@if($is_allow_print_preview=="Enabled")
						$(".print").trigger('click');
						@endif
                    }
                });
            });

            $("body").on("click",'.change-status,.change-status-reject',function (e) {
                // alert();
                var s = $(".nav-link.active").data('status');

                var status = $(this).attr('data-status');
                // alert(status); return;

                if(status=="Rejected"){

                    swal({
                        title: "Are you sure?",
                        text: "You will not be able to recover this order!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, Reject it!",
                        closeOnConfirm: false

                    }, function(e){
						if(e){
							//.after('disable-this');
							 $(".sa-button-container .confirm").attr('disabled','disabled');
							//$(".sa-button-container .confirm").html(progress_img);
							$(".sa-button-container .confirm").removeClass('btn-primary');
							$(".sa-button-container .confirm").addClass('btn-muted');


                        $.ajax({
                            url:"{!! env('APP_URL') !!}update/order/status",
                            type:"POST",
                            data:{
                                id:order_id,
                                status:status,

                                "_token":"{!! csrf_token() !!}"
                            },
                            success:function () {
                                //alert();
								 $(".sa-button-container .confirm").removeAttr('disabled');
							//$(".sa-button-container .confirm").html(progress_img);
							$(".sa-button-container .confirm").addClass('btn-primary');
							$(".sa-button-container .confirm").removeClass('btn-muted');
								 swal.close()
                                $(".mlist_li.selected").remove();

                                $(".no-order").show();
                                $(".order-section").hide();
                               count_order_status();
                                //  swal("Rejected!", "Order is rejected.", "success");
                            }
                        });
						}


                    });
                    return false;
                }

                if(status=="Accepted"){

                    $("#accept-modal").modal('show');
                    return false;
                }



                //   alert(order_id);

                $.ajax({
                    url:"{!! env('APP_URL') !!}update/order/status",
                    type:"POST",
                    data:{
                        id:order_id,
                        status:status,

                        "_token":"{!! csrf_token() !!}"
                    },
                    success:function () {

                        $(".mlist_li.selected").remove();
                        count_order_status();
                        if(s=="all"){
                            $("#orders-list").html('');


                        }
                        $(".no-order").show();
                        $(".order-section").hide();

                    }
                });
                e.preventDefault();
                e.stopPropagation();
            });
            $('.table').DataTable({
                "order": [[ 0, "desc" ]],
                'paging'      : true,
                'lengthChange': true,
                'searching'   : true,
                'ordering'    : true,
                'info'        : true,
                'autoWidth'   : true,
                /*"processing": true,
                "serverSide": true,
                "ajax": "{!! env('APP_URL') !!}get/ajax/orders",
                "order": [[ 0, "desc", ]],*/
                "lengthMenu": [ 50, 100, 150 ]

            });
            var min_minus = 1;
            setInterval(function(){
                $(".circle-div").each(function (i,v) {
                    var min = parseInt($(this).data('min'));
                    min = 1;
                    if((min)>0){
                        var new_time = (min-min_minus);
                       // alert(new_time);
                        if(new_time >= 0){
                            $(this).attr('data-min',new_time);
                            $(this).find('p.min').html(new_time+"<br />Min");
                        }


                    }


                });
                min_minus++;
            },60000);

        })



			  function show_recipe(recipe){
            var discount_amount = parseFloat(recipe.discount_amount);

            var str='<div class="box-header border-1 ">\n' +
                '                                        <div class="ml-5">\n' +
                '                                            <div class="d-flex justify-content-between align-items-center">\n' +
                '                                                <p><span class="badge rounded-pill bg-secondary mx-2">'+recipe.quantity+'  </span><img src="'+recipe.recipe_image+'" style="border-radius:10px; margin:0 3px" height="40px" width="40px" /> '+recipe.recipe_name+'</p>\n' ;
            if(recipe.total_price > 0){
				var price = parseFloat(recipe.total_price);
						if(discount_amount > 0){
							if(recipe.discount_type=="%"){
								price = price - (price * (discount_amount/100));
								price = "<span>"+price+"</span>" + "<span style='margin-left:10px; color:red'><del>"+recipe.total_price+"</del></span>";
							}
						}
			str+=   '                                                <p>{{ $currency }}  '+price+'</p>\n';
			}

            if(recipe.extra_options){
                str+='<dl>';
                if(recipe.extra_options){
                    console.log(recipe.extra_options);
                    $.each(recipe.extra_options,function(c,d){

                        @if($business_type=="ClothsStore")

                        if(c=="color")
                        str+='<div style="width: 20px; height: 20px; background-color: '+d+'; border-radius: 20px; float:left"></div>';

                         if(c=="size")
                        str+='<div style="float:right;font-size: 14px;margin-left: 8px;font-weight: 700;"> '+d+'</div>';
                        @else
						if(d.price && d.price!=""){
						var price = parseFloat(d.price);
						if(discount_amount > 0){
							if(recipe.discount_type=="%"){
								price = price - (price * (discount_amount/100));
								price = "<span>"+price+"</span>" + "<span style='margin-left:10px; color:red'><del>"+d.price+"</del></span>";
							}
						}
						var extItemName = d.name;
						@if($lang=='ar')
							extItemName = d.name_ar;
						@endif
                       str+="<dt>"+
					    extItemName
					   +" : {{ $currency }}  "+price;
                       if(d.sub_items){
                        //str+="<ul>";
                        $.each(d.sub_items,function(m,n){
                            console.log("Sub Items");
                            str+="<dd>"+n.name+"</dd>";


                        });
                        //str+="</dt>";
                       }
                       str+="</li>"
						}
                       @endif


                    });
                }
                str+='<dl>';
            }
            str+=
            '                                            </div>\n' +
                '                                        </div>\n' +
                '                                    </div>';

            return str;
        }
				 function show_recipe_with_discount(recipe,with_discount){

            var discount_amount = parseFloat(with_discount.discounted_amount);

            var str='<div class="box-header border-1 ">\n' +
                '                                        <div class="ml-5">\n' +
                '                                            <div class="d-flex justify-content-between align-items-center">\n' +
                '                                                <p><span class="badge rounded-pill bg-secondary mx-2">'+recipe.quantity+'  </span><img src="'+recipe.recipe_image+'" style="border-radius:10px; margin:0 3px" height="40px" width="40px" /> '+recipe.recipe_name+'</p>\n' ;
                     if(recipe.total_price > 0){
				var price = parseFloat(recipe.total_price);


								//price = price - (price * (discount_amount/100));
								price = "<span>"+price+"</span>" + "</span>";


			str+=   '                                                <p>{{ $currency }}  '+price+'</p>\n';
			}

            if(recipe.extra_options){
                str+='<dl>';
                if(recipe.extra_options){
                    console.log(recipe.extra_options);
                    $.each(recipe.extra_options,function(c,d){

                        @if($business_type=="ClothsStore")

                        if(c=="color")
                        str+='<div style="width: 20px; height: 20px; background-color: '+d+'; border-radius: 20px; float:left"></div>';

                         if(c=="size")
                        str+='<div style="float:right;font-size: 14px;margin-left: 8px;font-weight: 700;"> '+d+'</div>';
                        @else
						if(d.price && d.price!=""){
						var price = parseFloat(d.price);
						if(discount_amount > 0){

								price = "<span>"+price+"</span>" + "<span style='margin-left:10px; color:red'><del>"+d.price+"</del></span>";

						}
                       str+="<dt>"+d.name+" : {{ $currency }}  "+price;
                       if(d.sub_items){
                        //str+="<ul>";
                        $.each(d.sub_items,function(m,n){
                            console.log("Sub Items");
                            str+="<dd>"+n.name+"</dd>";


                        });
                        //str+="</dt>";
                       }
                       str+="</li>"
						}
                       @endif


                    });
                }
                str+='<dl>';
            }
            str+=
            '                                            </div>\n' +
                '                                        </div>\n' +
                '                                    </div>';

            return str;
        }

					function order_text_template(object,action){

						console.log(object);

					var sep = "%0A";
			if(action=="copy-clipboard")
				sep = "\n";


					var map = "https://maps.google.com/?q="+object.geo_location;
					var str = "I would like to share an order with you:"+sep+sep;
					 str += "*OrderID:* "+object.order_ref+' '+sep;
					 str += " *Business:* "+object.brand_name+' '+sep;
					str += " *Outlet:* "+object.outlet_name+' '+sep;
					str += " *Customer Name:* "+object.customer+' '+sep;
					str += " *Customer Phone No:* "+object.phone+' '+sep;
					str += " *Payment Method:* "+object.payment+' '+sep;
					str += " *Delivery Date:* "+object.delivery_at+' '+sep;
					str += " *Delivery Address:* "+object.address+' '+sep;
					if(action=="copy-clipboard")
						str += ' *Map:* '+(map)+' '+sep;
					else
					str += ' *Map:* '+encodeURIComponent(map)+' '+sep;
					str += ' *Sub Total:* '+object.total_price+' {!! $currency !!} '+sep;
					str += ' *Delivery Fee:* '+object.delivery_fee+' {!! $currency !!} '+sep;
					str += ' *Total:* '+(parseFloat(object.total_price) + parseFloat(object.delivery_fee))+' {!! $currency !!} '+sep;

					return str;
				}


    </script>
@endsection
