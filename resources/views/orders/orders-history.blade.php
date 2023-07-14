@extends('layouts.app')
@section('page-title')| Order History  @endsection
@section('content')
    <link href="{!! env('APP_ASSETS') !!}css/order-history.css" rel="stylesheet" type="text/css">
    <style>
        .content{
            padding-left:10px;
        }
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

        .page-link{
            padding: .5em 1em !important;
            border-radius: 2px;
            border: 0;
            margin: 0;
            min-width: 50px !important;
            text-align: center;
        }

        .page-item.active .page-link{
            background-color: #4c95dd;
        }
        table.dataTable {
            clear: both;
            margin-top: 6px !important;
            margin-bottom: 6px !important;
            max-width: none !important;
            border-collapse: collapse !important;
            font-family: 'Open Sans';
        }
        /*table.dataTable td{
            border-width: 1px;
        }*/
        .theme-primary .pagination li a:hover {

            background-color: #000 !important;
        }
        .table > :not(:last-child) > :last-child > * {
            border-bottom-color: transparent;
        }
        table.dataTable th{font-weight: 700 !important;}
        .dataTables_paginate {
            width: 100%;
            text-align: center;
        }
        div.dataTables_wrapper div.dataTables_paginate ul.pagination{
            justify-content: center !important;
        }
     .right-panel-footer {
            background-color: #fff7e8;
        }
		.actions{
			position:absolute; top: 25px; right: 0
		}
        .content-wrapper {
            width: calc(100% - 290px);
            background-color: #fff !important;
        }
		.actions li{ margin:0 !important; padding:0}
        @media(max-width:767px)
        {
           .sm-w-50{
            width:50%;
           }
        }
        .pl-10{
            padding-left: 10px;
        }
        .pr-10{
            padding-right: 10px;
        }
        .page-top-title{
            padding-left: 0;
        }
        html[dir="rtl"] .row.pt-15.pl-10.pr-10{
            margin: 0 !important;
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
    $restuarant1 = $resto ;
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

			$translations = \App\Models\Translations::whereIn('item_key',['delivery','delivered','rejected','cancelled','close'])->get();

			$tr_ar = [];
			$tr_en = [];

			foreach($translations as $tr){
				if($tr->lang_id=="en")
					$tr_en[ucwords($tr->item_key)]= $tr->item_val;
				else
					$tr_ar[ucwords($tr->item_key)]= $tr->item_val;

				}


@endphp
    <div class="content-wrapper">
        <div class="container-full">
            <section class="content">
                <div class="row ">
                    <div class="col-md-10">
                        <div class="page-top-title">
                            <h3 class="title m-0">{{__('label.order_history')}}</h3>
                        </div>
                    </div>
                </div>
                <div class="row pt-15 pl-10 pr-10">
                    <div class="card cust_card p-15 rounded-1">
                        <form class="row g-3 ordhistory" action="#">
                            <div class="col-md-4">
                                <input type="text" class="form-control" placeholder="{{__('label.id')}}" id="inputEmail4">
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" placeholder="{{__('label.phone_number')}}" id="inputPassword4">
                            </div>
                            <div class="col-12">
                                <select class="form-control form-select " title="{{__('label.outlets')}}" data-live-search="true">
                                    @php
                                        $outlets = \App\Models\Outlets::whereNull('deleted_at')->where('resto_id',\App\Helpers\CommonMethods::getRestuarantID())->where('active',1)->get();
                                    @endphp
                                    @if(isset($outlets))
                                        @foreach($outlets as $outlet)
                                            <option value="{!! $outlet->id !!}">{!! $outlet->name !!}</option>
                                        @endforeach
                                        @endif

                                </select>
                            </div>
                            <div class="col-12">
                                <input type="text" class="form-control" id="inputAddress2" placeholder="{{__('label.date_range')}}">
                            </div>

                            <div class="col-12">
                                <select class="form-control form-select" data-live-search="true"  title="{{__('label.order_status')}}">

                                    <option value="Placed">{{__('label.new')}}</option>
                                    <option value="Accepted">{{__('label.in_preparation')}}</option>
                                    <option value="On_Road">{{__('label.in_routeready')}}</option>
                                    <option value="Has_Delivered">{{__('label.delivered')}}</option>
                                    <option value="Cancelled">{{__('label.cancelled')}}</option>
                                    <option value="Rejected">{{__('label.rejected')}}</option>
                                    <option value="Reject_by_User">{{__('label.new')}}</option>
                                    <option value="Close">{{__('label.rejected_by_user')}}</option>

                                </select>
                            </div>
                            <div class="col-12">
                                <select class="form-control form-select selectpicker">
                                    <option>{{__('label.select_option')}}</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <select class="form-control form-select selectpicker">
                                    <option>{{__('label.select_option')}}</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-outline-primary btn-md rounded-0 sm-w-50">{{__('label.search')}}</button>
                            </div>
                        </form>
                    </div>
                    <div class="card p-15 rounded-1">
                        <div class="jumbotron p-0">


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="example" class="table table-striped" >
                                            <thead>
                                            <tr>
                                                <th>{{__('label.id')}}</th>
                                                <th>{{__('label.status')}}</th>
                                                <th>{{__('label.customer')}}</th>
                                                <th>{{__('label.mobile_no')}}</th>
                                                <th>{{__('label.type')}}</th>
                                                <th>{{__('label.payment_mode')}}</th>
                                                <th>{{__('label.total')}}</th>
                                                <th>{{__('label.channel')}}</th>
                                                <th>{{__('label.order_at')}}</th>
                                                <th>{{__('label.rating')}}</th>

                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(isset($orders) && $orders->count() > 0)
                                                @php

                                                    $custom_status['Placed'] = ['Accepted'=>'Accepted','Rejected'=>'Rejected', 'Rejected_by_User'=>'Rejected by User'];
                                                    $custom_status['Send_to_Kitchen'] = ['On_Road'=>'On the Way','Rejected'=>'Rejected', 'Rejected_by_User'=>'Rejected by User'];
                                                    $custom_status['On_Road'] = ['Has_Delivered'=>'Delivered','Rejected'=>'Rejected', 'Rejected_by_User'=>'Rejected by User'];
                                                    $custom_statuses = ['Rejected_by_User'=>'Rejected by User','Accepted'=>'Accepted','Rejected'=>'Rejected','Placed'=>'Placed','Send_to_Kitchen'=>'Send to Kitchen','On_Road'=>'On the Way', 'Has_Delivered'=>'Delivered','Served'=>"Served","Cancelled_by_Customer"=>"Cancelled","Close"=>"Close"];

                                                     $custom_status['Accepted'] = ['On_Road'=>'On the Way','Rejected'=>'Rejected', 'Rejected_by_User'=>'Rejected by User'];
                                                @endphp
                                                @foreach($orders as $order)
                                           <tr class="order-detail" data-id="{!! $order->id !!}">
                                                <td> {!! $order->order_ref !!}</td>
											     @if(app()->getLocale()=="en")
                                                <td>{!! isset($custom_statuses[$order->status])?$custom_statuses[$order->status]:"" !!}</td>
											   @else
											   <td>{!! $tr_ar[isset($custom_statuses[$order->status])?$custom_statuses[$order->status]:""] !!}</td>
											   @endif
                                                <td>{!! isset($order->customers)?$order->customers->name:$order->customer_name !!}</td>
                                                @php
                                                    $new_phone = "";
                                                    if(isset($order->customers)){
                                                    $pos = strpos($order->customers->users->email, env('COUNTRY_CODE'));
                                                            if ($pos !== false) {
                                                                $new_phone = substr_replace($order->customers->users->email, '', $pos, strlen(env('COUNTRY_CODE')));

                                                            }
                                                    }

                                                @endphp
                                                <td>{!! $new_phone !!}</td>

                                                <td>{!! app()->getLocale()=="en"?$order->order_type:$tr_ar[ucwords($order->order_type)] !!}</td>

                                                <td>{!! $order->payment_mode=="COD"?__('label.cash'):__('label.card') !!} </td>
                                                <td>{!! $currency !!} {!! number_format($order->total_price+$order->delivery_fee) !!}</td>
                                                <td>{!! $order->campaign_type!!}</td>
                                                <td>{!! \App\Helpers\CommonMethods::formatDateTime($order->created_at) !!}</td>
                                                <td></td>
                                            </tr>
                                            @endforeach
                                        @endif

                                            </tbody>
                                        </table>
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

<div class="modal fade" id="show-order-detail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">{{__('label.order_detail')}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <div class="order-section" style="max-height: 500px; overflow-x: auto;">

                            <div class="row">

                                <div class="col-sm-12">
                                    <div class="boxs">
                                        <div class="box-header with-border">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p class="fw-bold" rel="order_ref"></p>
                                                <p rel="order_placed"></p>
                                                <p class="p-2 inkitchen-btn" id="toggle"></p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-sm-12 mt-1 text-end">
                                        <a href="#!">{{__('label.print')}}</a>
                                    </div>
                                </div>


                                <div class="row right-panel-box">
                                    <div class="col-md-6">
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
                                    <div class="col-md-6">
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
                                    <div class="col-md-6">
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
                                    <div class="col-md-6 for-delivery">
                                        <div class="boxs">
                                            <div class="box-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="">
                                                        <p class="text-fade mb-0">{{__('label.delivery_at')}}</p>
                                                        <p rel="delivery_at"></p>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6  for-pickup" style="display:none">
                                        <div class="boxs">
                                            <div class="box-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="">
                                                        <p class="text-fade mb-0">{{__('label.pickup_at')}}</p>
                                                        <p rel="delivery_at"></p>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row right-panel-box">
                                    <div class="col-md-6">
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
                                    <div class="col-md-6">
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
                                    <div class="col-md-6">
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
                                    <div class="col-md-6">
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
                                    <div class="col-md-6">
                                        <div class="boxs">
                                            <div class="box-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="">
                                                        <p class="text-fade mb-0">{{__('label.address')}}</p>
                                                        <p rel="address"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="boxs">
                                            <div class="box-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="">
                                                        <p class="text-fade mb-0">{{__('label.delivery_notes')}}</p>
                                                        <p rel="delivery_notes"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <!--<div class="row right-panel-box for-delivery" id="google-map-link">
                                     <div class="col-md-6">
                                        <div class="boxs">
                                            <div class="box-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="">
                                                        <p class="text-fade mb-0">Formatted Address</p>
                                                        <p rel="formatted_address"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="boxs">
                                            <div class="box-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="">
                                                        <p class="text-fade mb-0">Share Google map link</p>
                                                        <p rel="google-map-link"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>-->

								<div class="row right-panel-box">
                                    <div class="col-md-12">
                                        <div class="boxs" style="position: relative">
                                            <div class="box-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="">
                                                        <p class="text-fade mb-0">{{__('label.address')}}</p>
                                                        <p rel="address"></p>
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
                                     <div class="col-md-6">
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
                                    <div class="col-md-6">
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





                            </div>
                            <div class="row mt-0 right-panel-footer ">
                                <div class="col-12" id="show-recipes">


                                </div>
                                <div class="col-12">
                                    <div class="box-header border-0 p-15 mt-0 pb-0">
                                        <div class="d-flex justify-content-between align-items-center m-0">
                                            <p>{{__('label.sub_total')}}:</p>
                                            <p class="sub_total"></p>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center m-0">
                                            <p>{{__('label.delivery_fee')}}:</p>
                                            <p class="delivery_fee"></p>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center m-0">
                                            <p class="p-15 m-5 ">{{__('label.total')}}:</p>
                                            <p class="total_txt"></p>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('label.close')}}</button>

      </div>
    </div>
  </div>
</div>


@endsection

@section('js')
{{--    <script src="{!! env('APP_ASSETS') !!}vendor_components/bootstrap-select/dist/js/bootstrap-select.js"></script>--}}
    <script>
        var resto_id = 0;
		var order_object = null;
        $(function () {

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

  $("body").on("click",".order-detail",function(){
	  $("#show-recipes").html('');
                var id = $(this).data('id');
                $.ajax({
                    url:"{!! env('APP_URL') !!}get/order/detail/"+id,
                    success:function (response) {
                        response = $.parseJSON(response);
						order_object = response;
						$("#show-recipes").html('');
                        $("#show-order-detail").modal('show');

                        var price = 0;
                        var delivery_fee = 0;

                        $(".change-status").attr('data-id',id);
                        $(".change-status-reject").attr('data-id',id);

                        var o_t = response.order_type.toLowerCase();
                       $(".for-delivery").hide();
                       $(".for-pickup").hide();

                       $(".for-"+o_t).show();

                        $.each(response,function (i,v) {
                            $("p[rel="+i+"]").text(v);
                            $("span[rel="+i+"]").text(v);

                            if(i=="delivery_fee"){
                                delivery_fee = v;
                                $(".delivery_fee").html("{{ $currency }} "+v);
                            }



                            if(i=="geo_location"){

                                $("p[rel=google-map-link]").html('<a target="_blank" href="https://maps.google.com/?q='+v+'">Share</a>');
								//$("p[rel=google-map-link]").html('<a target="_blank" href="https://maps.googleapis.com/maps/api/staticmap?zoom=15&size=900x500&markers=color:yellow|label:D|'+v+'&key=AIzaSyBFh6fzq8G7dgWLfz8kccvTlmPCSI_uWXQ">Share</a>');
                            }

                            if(i=="order_type"){
                                if(v=="Delivery")
                                    $("#google-map-link").show();
                                else
                                    $("#google-map-link").hide();

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

                            if(i=="next_action"){
                                if(v=="Accepted")
                                    $(".change-status").html('Accept');
                                    else
                                $(".change-status").html(v);
                            }

                            if(i=="next_status"){
                                $(".change-status").attr('data-status',v);
                            }


                            if(i=="recipes"){
                                var recipes = v;
                               /* if(recipes){
                                    $.each(recipes,function(m,n){
                                        console.log(n);
                                        $("#show-recipes").append(show_recipe(n));
                                        price+=parseFloat(n.total_price);
                                        if(n.extra_options){
                                            $.each(n.extra_options,function(c,d){
                                                price+=(parseInt(n.quantity) * parseFloat(d.price));
                                            });
                                        }
                                    });

                                    $(".sub_total").html('{{ $currency }}  '+price);
                                }*/

								 if(recipes){

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

									  $.each(recipes,function(m,n){
                                        console.log(n);
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
											 price+=parseFloat(n.total_price);
										}



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

                                    $(".sub_total").html('{{ $currency }}  '+price?price:0);
                                }
                            }

                            $(".total_txt").html("{{ $currency }}  "+(price+delivery_fee));

                        });
                    }
                });
            });



            $("body").on("click",".order-row",function () {
                var id = $(this).data('id');
                window.location = "{!! env('APP_URL') !!}order/show/"+id;
            });
            $("body").on("click",'.order-status',function (e) {
                // alert();
                var status = $(this).data('status');
                var order_id = $(this).data('id');

                $.ajax({
                    url:"{!! env('APP_URL') !!}update/order/status",
                    type:"POST",
                    data:{
                        id:order_id,
                        status:status,

                        "_token":"{!! csrf_token() !!}"
                    },
                    success:function () {
                        location.reload();
                    }
                });
                e.preventDefault();
                e.stopPropagation();
            });
            $('#example').DataTable({
                "bSort": true,
                "searching": false,
                "paging": true,
                "info": false,
                "bLengthChange": false,
                "aaSorting": [[0,'desc']],

                language: {
                    paginate: {
                        next: '<img src="{!! env("APP_ASSETS") !!}images/icons/next.png">', // or '→'
                        previous: '<img src="{!! env("APP_ASSETS") !!}images/icons/preivew.png">' // or '←'
                    }
                },

            });
            $("td nav").addClass('d-flex justify-content-center');
           // $('#example_wrapper .row:last').find('.col-md-5').first().remove()
        })

 /*function show_recipe(recipe){
            console.log(recipe);

            var str='<div class="box-header border-1 ">\n' +
                '                                        <div class="ml-5">\n' +
                '                                            <div class="d-flex justify-content-between align-items-center">\n' +
                '                                                <p>'+recipe.quantity+'x '+recipe.recipe_name+'</p>\n' ;
            if(recipe.total_price > 0)
            str+=   '                                                <p>{{ $currency }}  '+recipe.total_price+'</p>\n';
            if(recipe.extra_options){
                str+='<dl>';
                if(recipe.extra_options){
                    $.each(recipe.extra_options,function(c,d){
                       str+="<dt>"+d.name+" : {{ $currency }}  "+d.price;
                       if(d.sub_items){
                        //str+="<ul>";
                        $.each(d.sub_items,function(m,n){
                            console.log("Sub Items");
                            str+="<dd>"+n.name+"</dd>";


                        });
                        //str+="</dt>";
                       }
                       str+="</li>"
                    });
                }
                str+='<dl>';
            }
            str+=
            '                                            </div>\n' +
                '                                        </div>\n' +
                '                                    </div>';

            return str;
        }*/

			 function show_recipe(recipe){
            var discount_amount = parseFloat(recipe.discount_amount);

            var str='<div class="box-header border-1 ">\n' +
                '                                        <div class="ml-5">\n' +
                '                                            <div class="d-flex justify-content-between align-items-center">\n' +
                '                                                <p><img src="'+recipe.recipe_image+'" style="border-radius:10px" height="24px" width="24px" />'+recipe.quantity+'x '+recipe.recipe_name+'</p>\n' ;
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
                       str+="<dt>"+d.name+" : {{ $currency }}  "+price;
                       if(d.sub_items){
                        //str+="<ul>";
                        $.each(d.sub_items,function(m,n){
                            console.log("Sub Items");
                            str+="<dd>"+n.name+"</dd>";


                        });
                        //str+="</dt>";
                       }
                       str+="</li>";
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
					str += " *Payment Method:* "+object.order_type+' '+sep;
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
