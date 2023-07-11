@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <h1 class="mt-4">Restaurants</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{!! env('APP_URL') !!}dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{!! env('APP_URL') !!}restaurants">Restaurants</a></li>
            <li class="breadcrumb-item active">{!! $restaurant->name !!}'s Detail</li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div class="card mb-4">

                    <div class="card-body">
                        <div class="card mb-4 order-list">
                            <div class="gold-members p-4">

                                <div class="media">
                                    <a href="#">

                                        @if(isset($restaurant->photos))
                                            <img class="mr-4" src="{!! $restaurant->photos->file_name !!}" alt="{!! $restaurant->name !!}">
                                            @else
                                        <img class="mr-4" src="{!! env('APP_ASSETS') !!}img/3.jpg" alt="Generic placeholder image">
                                            @endif
                                    </a>
                                    <div class="media-body">
                                        <a href="#">
                                            <span class="float-right text-success">Created on {!! date('l, d M, Y', strtotime($restaurant->created_at)) !!} <i class="feather-check-circle text-success"></i></span>
                                        </a>
                                        <div class="row">
                                            <div class="col-md-6">

                                                <h6 class="mb-3"><a href="#">
                                                    </a><a href="#!" class="text-dark">{!! $restaurant->name !!}
                                                    </a>
                                                </h6>
                                                <p class="text-black-50 mb-1"><i class="feather-phone"></i> {!! $restaurant->	phone_number !!}</p>
                                                <p class="text-black-50 mb-1"><i class="feather-map-pin"></i> {!! $restaurant->address !!}
                                                </p>
                                                <p class="text-black-50 mb-3"> {!! $restaurant->short_description !!}
                                                </p>
                                                <p>
                                                    <a href="{!! env('QRCODE_HOST') !!}?id={!! $restaurant->unique_shared_key !!}" target="_blank">{!! env('QRCODE_HOST') !!}?id={!! $restaurant->unique_shared_key !!}</a>
                                                </p>
                                                <p>
                                                    <a href="{!! env('QRCODE_HOST_ORDER') !!}d/{!! $restaurant->resto_unique_name !!}" target="_blank">{!! env('QRCODE_HOST_ORDER') !!}delivery/{!! $restaurant->resto_unique_name !!}</a>
                                                </p>

                                            </div>
                                            <div class="col-md-2">

                                                <div class="text-center">
                                                    <p>Resto QR Code</p>
                                                    <div id="output"></div>
                                                    <div id="download" style="display: none"></div>

                                                    <a style="position: relative; top: 13px; font-size: 13px" href="#!" class="download-image mt-4"><i style="font-size: 20px" class="fa fa-download"></i> </a>
                                                </div>

                                            </div>
                                            <div class="col-md-2">

                                                <div class="text-center">
                                                    <p>Order QR Code</p>
                                                    <div id="output-order"></div>
                                                    <div id="download-order" style="display: none"></div>

                                                    <a style="position: relative; top: 13px; font-size: 13px" href="#!" class="download-image-order mt-4"><i style="font-size: 20px" class="fa fa-download"></i> </a>
                                                </div>

                                            </div>
                                        </div>

                                        <hr>
                                        <div class="float-right">{{--
                                            <a href="#!" class="btn btn-sm btn-warning"><i class="feather-message-circle"></i> Message</a>--}}

                                            <a href="{!! env('APP_URL') !!}restaurant/edit/{!! \App\Helpers\CommonMethods::encrypt($restaurant->id) !!}" class="btn btn-sm btn-info"><i class="feather-edit"></i></a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <h5>{!! $restaurant->name !!}'s Recipe</h5>
                        <div class="table-responsive">
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
                                    @if(isset($restaurant->recipes) && $restaurant->recipes->count() > 0)
                                        @foreach($restaurant->recipes as $recipe)
                                            <tr>
                                                <td>
                                                    @if(isset($recipe->main_images))
                                                        <img class="img-profile rounded-circle" src="{!! $recipe->main_images->file_name !!}">
                                                    @else
                                                        <img class="img-profile rounded-circle" src="{!! env('APP_ASSETS') !!}img/user/1.png">
                                                    @endif

                                                </td>

                                                <td> {!! $recipe->name !!}	</td>

                                                <td>{!! number_format($recipe->price,2) !!}</td>
                                               {{-- <td>{!! substr($recipe->short_description,0,150) !!}</td>--}}
                                                <td>0</td>
                                                <td>{!! $recipe->comments !!}</td>
                                                <td>{!! $recipe->active?'<span class="badge badge-success">Yes</span>':'<span class="badge badge-danger">No</span>' !!}</td>

                                                <td>
                                                    <a href="{!! env('APP_URL') !!}recipe/show/{!! $recipe->id !!}" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="View"><i class="feather-eye"></i> </a>
                                                    <a href="{!! env('APP_URL') !!}recipe/edit/{!! $recipe->id !!}" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Edit"><i class="feather-edit"></i> </a>
                                                    <a href="javascript:;" data-id="{!! $recipe->id !!}" class="btn btn-sm btn-danger delete-recipe" data-toggle="tooltip" data-placement="top" title="Delete"><i class="feather-trash"></i> </a>

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
            </div>
        </div>
    </div>
@endsection
@section('js')
<script>
    $(function () {
        @if( isset($restaurant))
        $('#output').qrcode({
            render: "canvas",
            text: "{!! env('QRCODE_HOST') !!}?id={!! $restaurant->unique_shared_key !!}",
            width: 100,
            height: 100,
            background: "#ffffff",
            foreground: "#000000",
            src: "{!! isset($restaurant) && isset($restaurant->photos)?$restaurant->photos->file_name:env('APP_URL').'public/layout/img/favicon.png' !!}",
            imgWidth: 20,
            imgHeight: 20
        });

        $('#download').qrcode({
            render: "canvas",
            text: "{!! env('QRCODE_HOST') !!}?id={!! $restaurant->unique_shared_key !!}",
            width: 2000,
            height: 2000,
            background: "#ffffff",
            foreground: "#000000",
            src: "{!! isset($restaurant) && isset($restaurant->photos)?$restaurant->photos->file_name:env('APP_URL').'public/layout/img/favicon.png' !!}",
            imgWidth: 500,
            imgHeight: 500
        });


        $(".download-image").click(function () {
            var canvas =  $('#download canvas')[0];
            var _this = $(this);
            // Change here
            $.ajax({
                url:"{!! env('APP_URL') !!}download/qrcode",
                type:"POST",
                data:{
                    data:canvas.toDataURL(),
                    '_token':"{!! csrf_token() !!}"
                },
                success:function(response){
                    console.log(response);
                    var link = document.createElement('a');
                    link.href = response;
                    link.download = "qrcode.png";
                    link.click();
                }
            });
        });
        $('#output-order').qrcode({
            render: "canvas",
            text: "{!! env('QRCODE_HOST_ORDER') !!}?id={!! $restaurant->unique_shared_key !!}",
            width: 100,
            height: 100,
            background: "#ffffff",
            foreground: "#000000",
            src: "{!! isset($restaurant) && isset($restaurant->photos)?$restaurant->photos->file_name:env('APP_URL').'public/layout/img/favicon.png' !!}",
            imgWidth: 20,
            imgHeight: 20
        });

        $('#download-order').qrcode({
            render: "canvas",
            text: "{!! env('QRCODE_HOST_ORDER') !!}?id={!! $restaurant->unique_shared_key !!}",
            width: 2000,
            height: 2000,
            background: "#ffffff",
            foreground: "#000000",
            src: "{!! isset($restaurant) && isset($restaurant->photos)?$restaurant->photos->file_name:env('APP_URL').'public/layout/img/favicon.png' !!}",
            imgWidth: 500,
            imgHeight: 500
        });


        $(".download-image-order").click(function () {
            var canvas =  $('#download-order canvas')[0];
            var _this = $(this);
            // Change here
            $.ajax({
                url:"{!! env('APP_URL') !!}download/qrcode",
                type:"POST",
                data:{
                    data:canvas.toDataURL(),
                    '_token':"{!! csrf_token() !!}"
                },
                success:function(response){
                    console.log(response);
                    var link = document.createElement('a');
                    link.href = response;
                    link.download = "qrcode.png";
                    link.click();
                }
            });
        });
        @endif
    })
</script>
    @endsection