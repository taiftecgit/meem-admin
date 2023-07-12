@extends('layouts.app')
@section('page-title')| Dashboard  @endsection

@section('css')
    <link href="{!! env('APP_ASSETS') !!}css/dashboard.css" rel="stylesheet" type="text/css">
    @endsection
@section('content')
    @php

            $resto = \App\Models\Restaurants::find(\App\Helpers\CommonMethods::getRestuarantID());

            $lang = $resto->default_lang;

    app()->setLocale($lang);
    if(session('app_lang') !==null){
    $lang = session('app_lang');
    app()->setLocale($lang);

    }

            $outlets = \App\Models\Outlets::whereNull('deleted_at')->where('resto_id',$resto->id)->get();
            $o = NULL;
            if(isset($outlets) && $outlets->count() > 0){
            foreach($outlets as $outlet){
    //dump($outlet->outlet_orders);
            $o[] = array(
                'outlet_name' => $outlet->name,
                'orders'=>$outlet->outlet_orders->count()
                );
            }

            }




                                            $totalRevenue = \App\Models\Restaurants::totalRevenue($resto->id);
                                            $last7DaysData = \App\Models\Restaurants::totalRevenueLast7Days($resto->id);

                                            $last7DaysDataCount =  \App\Models\Restaurants::totalCountRevenue($resto->id);
                                            $totalOnlineOrders =  \App\Models\Restaurants::totalOnlineOrders($resto->id);
                                            $totalSourceOrders =  \App\Models\Restaurants::totalOrdersBySource($resto->id);

                                            $totalOnlineOrders7Days  = \App\Models\Restaurants::totalOnlineOrdersLast7Days($resto->id);
                                            $totalCountOnlineOrders7Days  = \App\Models\Restaurants::totalCountOnlineOrdersLast7Days($resto->id);


                                            $last7DaysData = isset($last7DaysData)?$last7DaysData:NULL;




    @endphp
@php
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

@endphp
<style type="text/css">
    
    .tbl_row,.tbl_h4_title{
        padding: 10px;
    }
    .tbl_h4_title
    {
      padding-left: 0;
      margin-left: 0 !important;
    }
    table {
    --bs-table-bg: transparent !important;

}
</style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Main content -->

            <section class="content">
                <div class="row ">
                    <div class="col-md-10">
                        <div class="page-top-title">
                            <h3 class="title m-0">{{__('label.dashboard')}}</h3>
                        </div>
                    </div>
                </div>
                <div class="row p-0 m-0">
                    <div class="col-md-6 ">
                        <select class="form-control form-select">
                            <option value="">{{ __('label.current_month') }}</option>
                        </select>
                    </div>
                    <!--<div class="col-md-6">
                       <button class="form-control btn  btn-md shadow-none ">Launch marketing activity</button>
                       </div>-->
                </div>
                <div class="row m-0 align-items-center">
                    <div class="col-md-6">
                        <div class="count_div h-100 ">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex p-2 bd-highlight">
                                    <p class="count_title">{{__('label.total_orders')}}</p>
                                </div>
                                <div class="right_text_div">
                                    <a class="d-block text-decoration-none count_atag">
                                        <h3 class="count_h3 text-break">{!! isset($resto)?($resto->delivered_orders->count()):0; !!}</h3>
                                        <small class="text-secondary count_small_tag active">{{__('label.order_completed')}}</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="count_div h-100 ">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex p-2 bd-highlight">
                                    <p class="count_title">{{__('label.average_basket_value')}}</p>
                                </div>
                                <div class="right_text_div">
                                    <a class="d-block text-decoration-none count_atag">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="iqd_small">{!! $currency !!}</small>
                                            <h3 class="count_h3 text-break">
                                            @if(isset($resto) && isset($totalRevenue) && isset($resto->delivered_orders) && $resto->delivered_orders->count() > 0)
                                                {!! number_format($totalRevenue[0]->total_price/ $resto->delivered_orders->count()) !!}
                                                @else
                                                N/A
                                                @endif
                                            </h3>
                                        </div>
                                        <small class="text-secondary count_small_tag">{{__('label.average_basket_value')}}</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="count_div h-100 ">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex p-2 bd-highlight">
                                    <p class="count_title">{{__('label.total_sale')}}</p>
                                </div>
                                <div class="right_text_div">
                                    <a class="d-block text-decoration-none count_atag">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="iqd_small">{!! $currency !!}</small>
                                            <h3 class="count_h3 text-break"> {!! isset($totalRevenue)?number_format($totalRevenue[0]->total_price):0 !!}</h3>
                                        </div>
                                        <small class="text-secondary count_small_tag">{{__('label.total_sale_revenue')}}</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="count_div h-100 ">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex p-2 bd-highlight">
                                    <p class="count_title">{{__('label.average_order_rating')}}</p>
                                </div>
                                <div class="right_text_div">
                                    <a class="d-block text-decoration-none count_atag">
                                        <h3 class="count_h3 text-break">N/A</h3>
                                        <small class="text-secondary count_small_tag">{{__('label.average_order_rating')}}</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				@php
					$order_status_orders = \App\Models\Orders::select(\DB::raw('count(id) as customer_order'))->where('resto_id',$resto->id)->where('status','Has_Delivered')->groupBy('customer_id')->get()->take(4000);
				    $order_status_customers = \App\Models\Orders::select(\DB::raw('count(customer_id) as customer_order'))->where('resto_id',$resto->id)->where('status','Has_Delivered')->groupBy('customer_id')->get()->take(4000);

				    $order_total_prices = \App\Models\Orders::select(\DB::raw('sum(total_price) as total_price'),\DB::raw('count(id) as order_count'))->where('resto_id',$resto->id)->where('status','Has_Delivered')->groupBy('customer_id')->get();


					//dump( $order_total_prices);



				$new_customers = 0;
				$returnning_customers = 0;

				$new_order = 0;
				$returnning_order = 0;

				$new_order_total_price = 0;
				$returnning_order_total_price = 0;


				if(isset($order_status_orders)){
					foreach($order_status_orders as $cus_order){
						if($cus_order->customer_order==1)
							$new_order = $new_order+1;

						if($cus_order->customer_order>1)
							$returnning_order = $returnning_order+$cus_order->customer_order;
					}
				}

				if(isset($order_status_customers)){
					foreach($order_status_customers as $cus_order){
						if($cus_order->customer_order==1)
							$new_customers = $new_customers+1;

						if($cus_order->customer_order>1)
							$returnning_customers = $returnning_customers+1;
					}
				}

				if(isset($order_total_prices)){
					foreach($order_total_prices as $cus_order){
						if($cus_order->order_count==1)
							$new_order_total_price = $new_order_total_price+$cus_order->total_price;

						if($cus_order->order_count>1)
							$returnning_order_total_price = $returnning_order_total_price+$cus_order->total_price;
					}
				}


				//dump($order_status_customers );
				$total_customers = $new_customers + $returnning_customers ;

				$percent_new_customer = $new_customers>0 && $total_customers > 0 ?number_format(($new_customers / $total_customers) * 100,2):0;
				$percent_returnning_customers = $returnning_customers > 0 && $total_customers? number_format(($returnning_customers / $total_customers) * 100,2):0;

				$avg_new_order_basket = $new_order_total_price > 0 && $new_order > 0?($new_order_total_price/$new_order):0;

				$avg_returnning_order_basket = $returnning_order_total_price > 0 && $returnning_order > 0?($returnning_order_total_price/$returnning_order):0;

				$avg_total_avg = $avg_new_order_basket + $avg_returnning_order_basket ;

				@endphp
                <div class="row m-0">
                    <div class="col-12 mt-25">
                        <div class="tbl_row">
                            <h4 class="m-5 tbl_h4_title">{{__('label.new_customers_x_returning_customers')}}</h4>
                            <div class="table-responsive">
                                <table class="table text-center table-borderless">
                                    <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">{{__('label.orders')}}</th>
                                        <th scope="col">{{__('label.customers')}}</th>
                                        <th scope="col">{{__('label.customers')}} %</th>
                                        <th scope="col">{{__('label.average_basket_value')}}</th>
                                        <th scope="col">{{__('label.total_sale')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{{__('label.new')}}</td>
                                        <td>{!! $new_order !!}</td>
                                        <td>{!! $new_customers !!}</td>
                                        <td>{!! round($percent_new_customer) !!}</td>
                                        <td>{!! number_format(round($avg_new_order_basket))  !!}</td>
                                        <td>{!! number_format($new_order_total_price)  !!}</td>
                                    </tr>
                                    <tr class="row_background">
                                        <td>{{__('label.returning')}}</td>
                                        <td>{!! $returnning_order !!}</td>
                                        <td>{!! $returnning_customers !!}</td>
                                        <td>{!! round($percent_returnning_customers) !!}</td>
                                        <td>{!! number_format(round($avg_returnning_order_basket))  !!}</td>
                                        <td>{!! number_format($returnning_order_total_price)  !!}</td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>{{__('label.total')}}</th>
                                        <th>{!! $new_order + $returnning_order !!}</th>
                                        <th>{!! $total_customers !!}</th>
                                        <th>100%</th>
                                        <th>{!! number_format(round($avg_total_avg)) !!}</th>
                                        <th>{!! number_format($returnning_order_total_price + $new_order_total_price) !!}</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-25 mb-25 m-0">
                    <div class="col-md-6">
                        <div class="boxs">
                            <div class="box-header with-border">
                                <h4 class="box-title">{{__('label.total_order_by_source')}}</h4>
                            </div>
                            <div class="box-bodys">
                                <div id="chart" class="h-350">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="boxs">
                            <div class="box-header with-border2">
                                <h4 class="box-title">{{__('label.total_orders_by_outlets')}}</h4>
                            </div>
                            <div class="box-bodys">
                                <div id="yearly-comparison" class="h-350">

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
   <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
   <script src="{!! env('APP_ASSETS') !!}vendor_components/apexcharts-bundle/dist/apexcharts.min.js"></script>
   <script src="{!! env('APP_ASSETS') !!}vendor_components/chartist-js-develop/chartist.js"></script>
    <!-- Chart Js -->
    <!-- Datatable Js -->


    <script>
        var resto_id = 0;
        $(function () {

            var data = '{!! $last7DaysData !!}';
            var order_data = '{!! $totalOnlineOrders7Days !!}';
            data = $.parseJSON(data);
            order_data = $.parseJSON(order_data);

            var days = [];
            var sale = [];
            $.each(data, function (i, v) {
                days.push(v.day_name);
                sale.push(v.total_price);
            });

            var order_days = [];
            var orders = [];
            $.each(order_data, function (i, v) {
                order_days.push(v.day_name);
                orders.push(v.total_orders);
            });


            $("body").on('click', '.delete-recipe', function () {
                var id = $(this).data('id');

                $.ajax({
                    url: "{!! env('APP_URL') !!}recipe/delete/" + id,
                    success: function (response) {
                        location.reload();
                    }
                });
            });


            // var data = ["200,500,500,600,290,12,80"];
            var options = {
                series: [{
                    name: 'Revenue',
                    data: sale
                }],
                chart: {
                    height: 350,
                    type: 'area',
                    zoom: {
                        enabled: false
                    },
                },
                colors: ["#4c95dd"],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    categories: days
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "{!! $currency !!} " + val + " "
                        }
                    },
                },
            };

            var chart = new ApexCharts(document.querySelector("#chart3"), options);
            chart.render();


            console.log(order_data);

            var options = {
                chart: {
                    height: 325,
                    type: 'bar',
                    toolbar: {
                        show: false
                    },
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        endingShape: 'rounded',
                        columnWidth: '65%',
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 5,
                    colors: ['transparent']
                },
                colors: ["#e66430"],
                series: [{
                    name: 'Online Order',
                    data: orders
                }],
                xaxis: {
                    categories: order_days,
                    axisBorder: {
                        show: true,
                        color: '#bec7e0',
                    },
                    axisTicks: {
                        show: true,
                        color: '#bec7e0',
                    },
                },
                legend: {
                    show: false,
                },
                fill: {
                    opacity: 1

                },
                grid: {
                    row: {
                        colors: ['transparent'], // takes an array which will be repeated on columns
                        opacity: 0.2
                    },
                    borderColor: '#f1f3fa'
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return +0 + " Orders"
                        }
                    }
                }
            }

            /*   var chart = new ApexCharts(
                   document.querySelector("#yearly-comparison"),
                   options
               );

               chart.render();
   */
            new Chartist.Bar('#yearly-comparison',
                {
                    labels: [
                        @if(isset($o))
                            @foreach($o as $s)
                                "{!! $s['outlet_name'] !!}",
                            @endforeach
                        @endif
                    ],
                    series: [

                        {className: "stroke-green", meta: "OK", data: [@if(isset($o))
                                    @foreach($o as $s)
                                {!! $s['orders'] !!},
                                @endforeach
                                @endif]},

                    ]
                },
                {
                    seriesBarDistance: 10,
                    reverseData: true,
                    horizontalBars: true,
                    axisY: {
                        offset: 70
                    }
                });


            new Chartist.Bar('#chart',
                {
                    labels: [
                        @if(isset($totalSourceOrders))
                                @foreach($totalSourceOrders as $s)
                            "{!! $s['campaign_type'] !!}",
                        @endforeach
                        @endif
                    ],
                    series: [

                        {className: "stroke-green", meta: "OK", data: [@if(isset($totalSourceOrders))
                                @foreach($totalSourceOrders as $s)
                                {!! $s['order_source'] !!},
                                @endforeach
                                @endif]},

                    ]
                },
                {
                    seriesBarDistance: 10,
                    reverseData: true,
                    horizontalBars: true,
                    axisY: {
                        offset: 70
                    }
                });


        });
    </script>
@endsection
