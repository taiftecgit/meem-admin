<!-- Content Right Sidebar -->
@php
    $resto = isset(\Illuminate\Support\Facades\Auth::user()->restaurants)?\Illuminate\Support\Facades\Auth::user()->restaurants:NULL;
@endphp
<div class="right-bar">
    <div id="sidebarRight">
        <div class="right-bar-inner">
            <div class="text-end position-relative">
                <a href="#" class="d-inline-block d-xl-none btn right-bar-btn waves-effect waves-circle btn btn-circle btn-danger btn-sm">
                    <i class="mdi mdi-close"></i>
                </a>
            </div>
            <div class="right-bar-content">
                <div class="box no-shadow box-bordered border-light">
                    <div class="box-body">
                        <div class="d-flex justify-content-between align-items-center">
                            @php
                                $totalOnlineOrders =  \App\Restaurants::totalOnlineOrders($resto->id);
                            $totalRevenue = \App\Restaurants::totalRevenue($resto->id);
                            @endphp
                            <div>
                                <h5>Total Sale</h5>
                                <h2 class="mb-0">IQD {!! isset($totalRevenue)?number_format($totalRevenue[0]->total_price):0 !!}</h2>
                            </div>
                            <div class="p-10">
                                <div id="chart-spark1"></div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="my-0">{!! isset($totalOnlineOrders)?($totalOnlineOrders[0]->total_orders):0 !!} total orders</h5>
                            <a href="#" class="mb-0">View Report</a>
                        </div>
                    </div>
                </div>

            @php
                $current_page = strtolower(Route::currentRouteName());



            @endphp
                @if($current_page=="dashboard" || $current_page=="orderlisting")
                    @php

                        $today = \Carbon\Carbon::today()->format('Y-m-d');
;
                          $orders = \App\Orders::where('resto_id',$resto->id)->where('created_at','Like','%'.$today.'%')->orderBy('created_at','DESC')->get()->take(5);

                    @endphp
                <div class="box no-shadow box-bordered border-light">
                    <div class="box-header">
                        <h4 class="box-title">Todays Orders</h4>
                    </div>
                    <div class="box-body p-5">
                        <div class="media-list media-list-hover">
                            @if(isset($orders) && $orders->count() > 0)
                                @foreach($orders as $order)
                            <a class="media media-single mb-10 p-0 rounded-0" href="{!! env('APP_URL') !!}order/show/{!! $order->id !!}">
                                <h4 style="width: 80px" class=" text-gray fw-500">{!! date('h:i a',strtotime($order->created_at)) !!}</h4>
                                <div class="media-body ps-15 bs-5 rounded border-primary">
                                    <p>  Order ID {!! $order->order_ref !!}</p>
                                    <span class="text-fade">{!! $order->customer_name !!}<br /> {!! isset($order->customers)?(str_replace(env('COUNTRY_CODE'),'',$order->customers->users->email)):"" !!}</span>
                                </div>
                            </a>
                                @endforeach
                                @endif



                        </div>
                    </div>

                </div>
                    @endif

                @if($current_page=="show_order")
                    @php
                        $activities = \App\OrderActivities::where('order_id',$order->id)->get();

$custom_status = ['Placed'=>'Placed','Send_to_Kitchen'=>'Send to Kitchen','Send to Kitchen'=>'Send to Kitchen','On_Road'=>'On the Way','On Road'=>'On Road', 'Has_Delivered'=>'Delivered', 'Delivered'=>'Delivered','Cancelled_by_Customer'=>'Cancelled','Accepted'=>'Accepted','Rejected'=>'Rejected', 'Rejected_by_User'=>'Rejected by User'];



                    @endphp

                    <div class="box no-shadow box-bordered border-light">
                        <div class="box-header">
                            <h4 class="box-title">Order Activities</h4>
                        </div>
                        <div class="box-body p-5">
                            <div class="media-list media-list-hover">
                                @if(isset($activities) && $activities->count() > 0)

                                    @foreach($activities as $activity)
                                        @php
                                            $classname = "border-primary";
                                                if(strtolower($activity->status)=="placed")
                                                        $classname = "border-info";
                                            if(strtolower($activity->status)=="send_to_kitchen")
                                                        $classname = "border-warning";
                                             if(strtolower($activity->status)=="rejected" || strtolower($activity->status)=="cancelled_by_customer" || strtolower($activity->status)=="rejected_by_user")
                                                        $classname = "border-danger";
                                             if(strtolower($activity->status)=="on_road")
                                                        $classname = "border-warning";
                                            if(strtolower($activity->status)=="has_delivered")
                                                        $classname = "border-success";

                                        @endphp
                                <a class="media media-single mb-10 p-0 rounded-0" href="#">
                                    <h4 style="width: 80px" class=" text-gray fw-500">{!! date('h:i a',strtotime($activity->created_at)) !!}</h4>
                                    <div class="media-body ps-15 bs-5 rounded {!! $classname !!}">
                                        <p>{!! isset($custom_status[$activity->status])?$custom_status[$activity->status]:"" !!}</p>
                                        <span class="text-fade">by {!! $resto->name !!}</span>
                                    </div>
                                </a>

                                    @endforeach
                                    @endif



                            </div>
                        </div>

                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- /.Content Right Sidebar -->