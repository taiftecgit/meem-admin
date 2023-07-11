@extends('layouts.app')
@section('content')
    <style>
        ul.timeline {
            list-style-type: none;
            position: relative;
        }
        ul.timeline:before {
            content: ' ';
            background: #d4d9df;
            display: inline-block;
            position: absolute;
            left: 29px;
            width: 2px;
            height: 100%;
            z-index: 400;
        }
        ul.timeline > li {
            margin: 20px 0;
            padding-left: 20px;
        }
        ul.timeline > li:before {
            content: ' ';
            background: white;
            display: inline-block;
            position: absolute;
            border-radius: 50%;
            border: 3px solid #22c0e8;
            left: 20px;
            width: 20px;
            height: 20px;
            z-index: 400;
        }
        p div{
            background: #f7f6f6;
            padding: 2px 5px;
            border-radius: 0;
            margin-bottom: 2px;
        }
        #print-recipt{
            display: none; }

        .custom-gap{
            padding: 1rem 1.5rem;
        }
        .custom-gap h5{ font-size: 14px; }
        .custom-gap p{ font-size: 12px; }


        #top .logo {
        / / float: left;
            height: 60px;
            width: 60px;
           // background: url({!! env('APP_URL').'public/uploads/logo/'.\Illuminate\Support\Facades\Auth::user()->restaurants->photos->file_name !!}) no-repeat;
            background-size: 60px 60px;
        }

        .clientlogo {
            float: left;
            height: 60px;
            width: 60px;
        //    background: url({!! env('APP_URL').'public/uploads/logo/'.\Illuminate\Support\Facades\Auth::user()->restaurants->photos->file_name !!}) no-repeat;
            background-size: 60px 60px;
            border-radius: 50px;
        }


    </style>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <h4 class="page-title">Order Details</h4>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{!! env('APP_URL') !!}dashboard"><i class="mdi mdi-home-outline"></i></a></li>
                                    <li class="breadcrumb-item" aria-current="page"><a href="{!! env('APP_URL') !!}orders">Order</a> </li>
                                    <li class="breadcrumb-item active" aria-current="page">Order Details</li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-xxxl-4 col-12">
                        <div class="box">
                            <div class="box-body">
                                <div class="d-flex align-items-center">
                                    <!-- <img class="me-10 rounded-circle avatar avatar-xl b-2 border-primary" src="{!! env('APP_ASSETS') !!}images/avatar/1.jpg" alt=""> -->
                                    <i class="fa fa-user-circle" style="
    font-size: 54px;
    margin-right: 10px;
"></i>
                                    <div>
                                        <h5 class="mb-0">{!! $order->customer_name !!}</h5>
                                        <span class="fs-14 text-info">Customer</span>
                                    </div>
                                </div>
                            </div>
                            <div class="box-body border-bottom">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-phone me-10 fs-24"></i>
                                    @php
                                        $new_phone = "";
                                        if(isset($order->customers)){
                                        $pos = strpos($order->customers->users->email, env('COUNTRY_CODE'));
                                                if ($pos !== false) {
                                                    $new_phone = substr_replace($order->customers->users->email, '', $pos, strlen(env('COUNTRY_CODE')));

                                                }
                                        }

                                    @endphp
                                    <h4 class="mb-0">{!! $new_phone !!}</h4>
                                </div>
                            </div>

                            <div class="box-body border-bottom">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-map-marker me-10 fs-24"></i>

                                    <h5 class="mb-0 text-black"> @if(isset($order->customers) && isset($order->customers->customer_addresses))
                                            
                                            <p class="m-0"> 
                                               <strong>  {!! isset($order->customers->customer_addresses[0])?ucwords($order->customers->customer_addresses[0]->label):NULL !!}</strong> ،
                                                {!! isset($order->customers->customer_addresses[0])?$order->customers->customer_addresses[0]->area:NULL !!} ،
                                                    {!! isset($order->customers->customer_addresses[0])?$order->customers->customer_addresses[0]->address:NULL !!} 
                                                   
                                                </p>
                                        @endif</h5>
                                </div>
                            </div>
                            <div class="box-body border-bottom">
                                <h5 class="mb-10">Delivery Notes</h5>
                                <p>@if(isset($order->customers) && isset($order->customers->customer_addresses))
                                        {!! isset($order->customers->customer_addresses[0])?$order->customers->customer_addresses[0]->instructions:NULL !!}
                                    @endif

                                </p>
                            </div>
                            @php
                                    $classname = "badge-primary";
                                        if(strtolower($order->status)=="placed")
                                                $classname = "badge-info";
                                    if(strtolower($order->status)=="send_to_kitchen")
                                                $classname = "badge-warning";
                                     if(strtolower($order->status)=="rejected" || strtolower($order->status)=="cancelled_by_customer")
                                                $classname = "badge-danger";
                                     if(strtolower($order->status)=="preparing_order")
                                                $classname = "badge-primary";
                                    if(strtolower($order->status)=="has_delivered")
                                                $classname = "badge-success";

                                @endphp
                                @php
                        $statesu = Illuminate\Support\Facades\DB::select( \Illuminate\Support\Facades\DB::raw("SELECT SUBSTRING(COLUMN_TYPE,5) as status FROM information_schema.COLUMNS WHERE TABLE_NAME='tb_dm_orders' AND COLUMN_NAME='status'"));

                        $status = explode(',',str_replace(['(',')',"'"],'',$statesu[0]->status));
                           $ignore_status = ['Rejected','Served','Preparing_Order'];
                           $status = array_diff($status,$ignore_status);
                           $custom_status = ['Placed'=>'Placed','Send_to_Kitchen'=>'Send to Kitchen','On_Road'=>'On the Way', 'Has_Delivered'=>'Delivered'];

                        asort($status)
                    @endphp
                            <div class="box-body border-bottom custom-gap">
                                <h5 class="mb-10">Order ID</h5>
                                <p>{!! $order->order_ref !!}  </span>

                                </p>
                            </div>
                            <div class="box-body border-bottom custom-gap">
                                
                                <h5 class="mb-10">Order Instructions</h5>

                                <p id="instruction">{!! nl2br($order->order_instructions) !!}</p>
                                <textarea class="form-control mb-2" style="display: none">{!! ($order->order_instructions) !!}</textarea>
                                <a href="#!" class="edit-instruction" >Edit Instruction</a>
                                <a href="#!" class="update-instruction" style="display: none;" >Update Instruction</a>
                            </div>

                            <div class="box-body border-bottom custom-gap">
                                <h5 class="mb-10">Order Delivery Time</h5>
                                <p>{!!  $order->order_deliver_time!=""?$order->order_deliver_time:"(اقرب وقت)" !!}

                                </p>
                            </div>
                            <div class="box-body border-bottom custom-gap">
                                <h5 class="mb-10">Order Date</h5> 
                                <p>{!! \App\Helpers\CommonMethods::formatDateTime($order->created_at) !!}

                                </p>
                            </div>

                        </div>
                       



                    </div>
                    @php

                                     $custom_status_buttons['Placed'] = ['Accepted'=>'Accepted','Rejected'=>'Rejected', 'Rejected_by_User'=>'Rejected by User'];

                                     $custom_status_buttons['Accepted'] = ['On_Road'=>'On the Way','Rejected'=>'Rejected', 'Rejected_by_User'=>'Rejected by User'];
                                      $custom_status_buttons['On_Road'] = ['Has_Delivered'=>'Delivered','Rejected'=>'Rejected', 'Rejected_by_User'=>'Rejected by User'];

                                      $timeline_status['Accepted'] = ['Placed'=>'Placed','Accepted'=>'Accepted','On_Road'=>'On the Way' ,'Has_Delivered'=>'Delivered'];
                                      $timeline_status['Rejected'] = ['Placed'=>'Placed','Rejected'=>'Rejected'];
                                      $timeline_status['Rejected_by_User'] = ['Placed'=>'Placed','Rejected_by_User'=>'Rejected by User'];

                                      $time_line_status = 'Accepted';

                                      if($order->status=="Rejected_by_User")
                                        $time_line_status="Rejected_by_User";

                                    if($order->status=="Rejected")
                                        $time_line_status="Rejected";

                                       

                              
                    @endphp
                    <div class="col-xxxl-8 col-12">
                        <div class="box">
                            <div class="box-body border-bottom">
                                <ol class="c-progress-steps">
                                    @foreach($timeline_status[$time_line_status] as $k=>$s)

                                    <li class="c-progress-steps__step @if(in_array(($k), $activities))  done @endif"><span>{!! $s !!}</span></li>
                                    @endforeach

                                </ol>
                            </div>
                          
                            <div class="box-body">
                                 @if(isset($custom_status_buttons[$order->status]))
                                 @foreach($custom_status_buttons[$order->status] as $k=>$s)
                                   
                                 
                                    @php

                                        $classname = "btn-secondary";
                               if(strtolower($k)=="placed")
                                       $classname = "btn-info";
                           if(strtolower($k)=="on_road")
                                       $classname = "btn-warning";
                            if(strtolower($k)=="rejected" || strtolower($k)=="cancelled_by_customer"||strtolower($k)=="rejected_by_user")
                                       $classname = "btn-danger";
                            if(strtolower($k)=="preparing_order")
                                       $classname = "btn-primary";
                           if(strtolower($k)=="has_delivered")
                                       $classname = "btn-success";
                                    @endphp
                                    <button type="button"  @if(in_array($k,$activities)) disabled="" @endif class="btn bg-gradient btn-sm {!! $classname !!} order-status" data-status="{!! $k !!}">{!! str_replace('_',' ',$s) !!}</button>
                                @endforeach @endif
                            </div>

                        </div>

                        <div class="box">
                            <div class="box-body">
                                <div class="table-responsive-xl">
                                    <table class="table product-overview">
                                        <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th width="35%">Product Info</th>

                                            <th width="10%">Price</th>
                                            <th width="10%">Quantity</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                            {{--<th>Action</th>--}}
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($order->orderItems) && $order->orderItems->count() > 0)
                                            @php
                                                $extra_price = 0;
                                            @endphp
                                            @foreach($order->orderItems as $item)

                                                <tr>
                                                    <td>
                                                        @if(isset($item->recipes) && isset($item->recipes->main_images))
                                                            <div style="width: 80px; height: 52px; background: url({!! $item->recipes->main_images->file_name !!});background-size: contain; background-position: center; "></div>
                                                            @else

                                                        <img src="{!! env('APP_ASSETS') !!}images/product/product-1.png" alt="" width="80">
                                                            @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                        $recipe  = $item->recipes;
                                                        $cate_name = "";
                                                            $categories = isset($recipe->categories)?$recipe->categories->pluck('category_id'):NULL;
                                                           if($categories){

                                                               $categories = \App\Categories::whereIn('id',$categories)->pluck('name')->toArray();
                                                               $cate_name = $categories[0];


                                                           }
                                                        @endphp
                                                        @php
                                                            $extra_options = NULL;

                                                               $opt = [];
                                                           if(!empty($item->extra_options)){
                                                           $extra_options = json_decode($item->extra_options);

                                                           //$opt = "<ul>";
                                                           foreach($extra_options as $option){

                                                            $itm = \App\ExtraOptionItems::find($option->id);
                                                            if(isset($itm)){

                                                                $opt[] = $itm->name;
                                                             //  $opt.="<li>".$itm->name.' <span class="ml-2 badge badge-danger">'.($itm->price).'</span>';
                                                                $extra_price = $extra_price+$itm->price;

                                                               if(isset($option->sub_items)){

                                                                    foreach($option->sub_items as $sub){

                                                                       $itm = \App\ExtraOptionItems::find($sub->sub_item_id);


                                                                       $extra_price = $extra_price+$itm->price;
                                                                    }

                                                               }
                                                               //$opt.="</li>";
                                                            }
                                                           }
                                                          // $opt.="</ul>";
                                                           }
                                                        @endphp



                                                        <h6>{!! $cate_name !!}</h6>
                                                        <h4>{!! isset($item->recipes)?$item->recipes->name:"" !!}

                                                        </h4>   @if(count($opt) > 0)( {!! implode(', ',$opt) !!} ) @endif
                                                    </td>

                                                    <td>IQD {!! number_format($item->price) !!}</td>
                                                    <td><input type="number" class="form-control" value="{!! $item->qty !!}" placeholder="1" min="0"></td>
                                                    <th>IQD {!! number_format($item->qty*$item->price) !!}</th>



                                                    <td>
                                                        <a href="javascript:;" class="btn btn-circle btn-primary btn-xs remove-item" data-quantity="{!! $item->qty !!}" data-price="{!! $item->price !!}" data-item-id="{!! $item->order_item_id !!}"  data-toggle="tooltip" data-placement="top" title="Remove"><i class="ti-trash"></i> </a>

                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <th>
                                                    Delivery Fee:
                                                </th>
                                                <th colspan="6">IDQ {!! number_format($order->delivery_fee) !!}</th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    Total:
                                                </th>
                                                <th colspan="6">IQD {!! number_format($order->delivery_fee+$order->total_price+$extra_price) !!}</th>
                                            </tr>
                                            <tr>
                                                <th>Print Receipt</th>
                                                <th colspan="6">
                                                    <button class="btn btn-default print" data-id="{!! $order->id !!}"><i class="glyphicon glyphicon-print"></i> </button>
                                                </th>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>

                                </div>
                                <div id="print-recipt">





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



@endsection

@section('js')
    <script>
        $(function () {
            
            $("body").on("click",".print",function () {
                $.ajax({
                    url:"{!! env('APP_URL') !!}order/print/{!! $order->id !!}",
                    success:function (response) {
                        $('#print-recipt').html(response);
                        $('#print-recipt').printThis();
                    }
                });

            });

            $(".edit-instruction").click(function () {
                $("textarea").show();
                $(".update-instruction").show();

                $("#instruction").hide();
                $(this).hide();
            });

            $("body").on("click",".remove-item",function () {
                return;
                var id = $(this).data('item-id');
                var quantity = $(this).data('quantity');
                var price = $(this).data('price');
                var total_less_price = quantity * price;
                var _this = $(this);
                _this.parents('tr').remove();

                $.ajax({
                    url:"{!! env('APP_URL') !!}delete/order/item",
                    type:"POST",
                    data:{
                        itemId:id,
                        orderId:"{!! $order->id !!}",
                        total_less_price:total_less_price,
                        "_token":"{!! csrf_token() !!}"
                    },
                    success:function () {

                    }
                });
            });


            $("body").on("click",".update-instruction",function () {
                var text = $("textarea").val();

                $.ajax({
                    url:"{!! env('APP_URL') !!}update/instruction",
                    data:{
                        id:"{!! $order->id !!}",
                        text:text,
                        "_token":"{!! csrf_token() !!}",

                    },
                    type:"POST",
                    success:function () {
                        $(".update-instruction").hide();
                        $(".edit-instruction").show();
                        $("#instruction").show();
                        $("textarea").hide();
                    }
                });
            });

            $("body").on('click','.save',function () {
                $(".alert").hide();
                if($("#password-form").valid()){
                    $("#password-form").ajaxForm(function (response) {
                        response = $.parseJSON(response);
                        if(response){
                            if(response.type=="success"){
                                $('#password-form .alert.success').html(response.message);
                                $('#password-form .alert.success').show();

                                setTimeout(function(){

                                    location.reload();

                                },2000)
                            }else{
                                $('#password-form .alert.error').html(response.message);
                                $('#password-form .alert.error').show();
                            }
                        }
                    }).submit();
                }
            });

            $("body").on("click",'.order-status',function () {
               // alert();
                var status = $(this).data('status');

                $.ajax({
                    url:"{!! env('APP_URL') !!}update/order/status",
                    type:"POST",
                    data:{
                        id:"{!! $order->id !!}",
                        status:status,

                        "_token":"{!! csrf_token() !!}"
                    },
                    success:function () {
                        location.reload();
                    }
                });
            });

        })
    </script>
@endsection