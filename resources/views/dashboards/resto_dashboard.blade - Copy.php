@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @php
    $resto = isset(\Illuminate\Support\Facades\Auth::user()->restaurants)?\Illuminate\Support\Facades\Auth::user()->restaurants:NULL;
    @endphp
    <h1 class="mt-4">{!! isset($resto)?$resto->name:"" !!}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">{!! isset($resto)?$resto->name:"" !!}</li>
    </ol>
    <div class="row">
        <div class="col-xl-4 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">{!! isset($resto) && isset($resto->recipes)?$resto->recipes->count():0 !!} Recipes!</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{!! env('APP_URL') !!}recipes">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">11 New Orders!</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="bookings.html">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">11 New Reviews!</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="reviews.html">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-area mr-1"></i>
                    Sales earnings this month
                </div>
                <div class="card-body">
                    <canvas id="myAreaChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar mr-1"></i>
                    All Time Earnings
                </div>
                <div class="card-body">
                    <canvas id="myBarChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
            Recent Recipes
        </div>
        <div class="card-body">
            @php
                $recipes = NULL;
                if(isset($resto))
                    $recipes = \App\Recipes::whereNull('deleted_at')->where('resto_id',$resto->id)->limit(10)->get();
            @endphp
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr class="text-uppercase">
                            <th>#</th>

                            <th>Name</th>
                            <th>Price</th>{{--
                            <th>Description</th>--}}

                            <th>Orders</th>
                            <th>Comments</th>
                            <th>Customizable</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!----><!---->
                        @if(isset($recipes) && $recipes->count() > 0)
                            @foreach($recipes as $recipe)
                                <tr>
                                    <td>
                                        @if(isset($recipe->main_images))
                                            <img class="img-profile rounded-circle" src="{!! env('APP_URL').'public/uploads/main_image/'.$recipe->main_images->file_name !!}">
                                        @else
                                            <img class="img-profile rounded-circle" src="{!! env('APP_ASSETS') !!}img/user/1.png">
                                        @endif

                                    </td>

                                    <td> {!! $recipe->name !!}	</td>

                                    <td>{!! number_format($recipe->price,2) !!}</td>{{--
                                    <td>{!! substr($recipe->short_description,0,150) !!}</td>--}}
                                    <td>0</td>
                                    <td>{!! $recipe->comments !!}</td>
                                    <td>{!! $recipe->active?'<span class="badge badge-success">Yes</span>':'<span class="badge badge-danger">No</span>' !!}</td>
                                    <td>
                                        <a href="{!! env('APP_URL') !!}recipe/show/{!! $recipe->id !!}" class="btn btn-success btn-sm"  data-toggle="tooltip" data-placement="top" title="View"><i class="feather-eye"></i></a>
                                        <a href="{!! env('APP_URL') !!}recipe/edit/{!! $recipe->id !!}" class="btn btn-sm btn-primary"  data-toggle="tooltip" data-placement="top" title="Edit"><i class="feather-edit"></i></a>
                                        <a href="javascript:;" data-id="{!! $recipe->id !!}" class="btn btn-sm btn-danger delete-recipe"  data-toggle="tooltip" data-placement="top" title="Delete"><i class="feather-trash"></i></a>

                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
            RECENT 10 ORDER
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @php
                    $orders = NULL;
                   if(isset($resto))
                           $orders = \App\Orders::where('resto_id',$resto->id)->limit(10)->get();
                @endphp
                <table class="table" id="dataTable1" width="100%" cellspacing="0">
                    <thead>
                    <tr class="text-uppercase">
                        <th>Order</th>
                        <th>Customer Name</th>
                        <th>Waiter Name</th>
                        <th>Table No</th>

                        <th>Order Date</th>


                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!----><!---->
                    @if(isset($orders) && $orders->count() > 0)
                        @foreach($orders as $order)
                            @php
                            $classname = "badge-muted";
                                if(strtolower($order->status)=="placed")
                                        $classname = "badge-info";
                            if(strtolower($order->status)=="send_to_kitchen")
                                        $classname = "badge-warning";
                             if(strtolower($order->status)=="rejected" || strtolower($order->status)=="cancelled_by_customer")
                                        $classname = "badge-danger";
                             if(strtolower($order->status)=="preparing_order")
                                        $classname = "badge-primary";
                            if(strtolower($order->status)=="served")
                                        $classname = "badge-success";

                            @endphp
                            <tr>
                                <td>
                                    {!! \App\Helpers\CommonMethods::idFormat($order->resto_id,$order->id) !!}
                                </td>
                                <td> {!! $order->customer_name !!}	</td>
                                <td>{!! isset($order->waiters)?$order->waiters->name:"" !!}</td>
                                <td>{!! $order->table_no !!}</td>

                                <td>{!! \App\Helpers\CommonMethods::formatDate($order->created_at) !!}</td>
                                <td><span class="badge {!! $classname !!}">{!! $order->status !!}</span> </td>
                                <td>
                                    <a href="{!! env('APP_URL') !!}order/show/{!! $order->id !!}" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="View"><i class="feather-eye"></i> </a>
                                </td>
                            </tr>
                    @endforeach
                 @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
    @endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <!-- Chart Js -->
    <script src="{!! env('APP_ASSETS') !!}assets/demo/chart-area-demo.js"></script>
    <script src="{!! env('APP_ASSETS') !!}assets/demo/chart-bar-demo.js"></script>
    <script src="{!! env('APP_ASSETS') !!}assets/demo/chart-pie-demo.js"></script>
    <!-- Datatable Js -->
    <script src="{!! env('APP_ASSETS') !!}vendor/dataTables/dataTables/js/jquery.dataTables.min.js"></script>
    <script src="{!! env('APP_ASSETS') !!}vendor/dataTables/dataTables/js/dataTables.bootstrap.min.js"></script>
    <script src="{!! env('APP_ASSETS') !!}assets/demo/datatables-demo.js"></script>

    <script>
        var resto_id = 0;
        $(function () {
            $("body").on('click','.delete-recipe',function () {
                var id = $(this).data('id');

                $.ajax({
                    url:"{!! env('APP_URL') !!}recipe/delete/"+id,
                    success:function (response) {
                        location.reload();
                    }
                });
            });

            $('#dataTable1').DataTable();

            $("body").on('click','.save-credentails',function () {

                var password =  $("#password").html();

                $.ajax({
                    url:"{!! env('APP_URL') !!}update/password",
                    type:"POST",
                    data:{
                        resto_id:resto_id,
                        password:password,
                        '_token':"{!! csrf_token() !!}"
                    },
                    success:function (response) {
                        $("#create-credentials .alert").show();

                        setTimeout(function () {
                            location.reload();
                        },1500)
                    }
                });

            });
        })
    </script>
@endsection