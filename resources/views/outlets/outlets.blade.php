@extends('layouts.app')
@section('page-title')| Outlets  @endsection
@section('css')
    <link href="{!! env('APP_ASSETS') !!}css/outlets.css" rel="stylesheet" type="text/css">
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
        .dataTables_wrapper.container-fluid.dt-bootstrap4.no-footer
        {
            padding: 0px;
        }
        .content{
            padding-right: 15px;
        }
        .w-max{
            width:max-content;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
@php
$resto = \App\Models\Restaurants::find(\App\Helpers\CommonMethods::getRestuarantID());
$lang = $resto->default_lang;

app()->setLocale($lang);
if(session('app_lang') !==null){
$lang = session('app_lang');
app()->setLocale($lang);
}
@endphp
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Main content -->
            <section class="content">

                <div class="row ">
                    <div class="col-md-6">
                        <div class="m-15">
                            <h3 class="title">{{__('label.outlets')}}</h3>
                            <p>{{__('label.add_and_manage_your_outlets')}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <a href="{!! env('APP_URL') !!}new/outlet" class="form-control btn btn-primary btn-md add-outlet text-center w-max">
                            <i class="fa  fa-plus mr-2"></i>
                            <!-- <i class="mdi mdi-plus-circle"></i> --> {{__('label.add_outlet')}}
                        </a>
                    </div>
                </div>


                <div class="row mt-15">
                    <div class="col-md-12 pt-5">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="orange_text p-5" style="margin-left: 10px">{!! $outlets->count() !!} {{__('label.outlets')}}</h4>
                            <div class="form-group has-search">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control" placeholder="{{__('label.search_outlet')}}">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mt-4">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">{{__('label.status')}}</th>
                                    <th scope="col">{{__('label.name')}}</th>
                                    <th scope="col">{{__('label.delivery')}}</th>
                                    <th scope="col">{{__('label.pickup')}}</th>
                                    <th></th>

                                </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $resto = \App\Models\Restaurants::find(\App\Helpers\CommonMethods::getRestuarantID());
                                        @endphp
                                @if(isset($outlets) && $outlets->count() > 0)
                                    @foreach($outlets as $outlet)

                                <tr>
                                    <td>
                                        <button type="button"  data-on-text="Open"  data-off-text="Closed" class="btn  btn-toggle btn-sm btn-success @if($outlet->active=="1") active @endif switch-me" data-id="{!! $outlet->id !!}" data-bs-toggle="button" aria-pressed="@if($outlet->active=="1") true @else false @endif" autocomplete="off">
                                            <div class="handle"></div>
                                        </button> {{__('label.active')}}
                                    </td>
                                    <td >
                                        <h5>{!! $outlet->name !!}</h5>
                                        <p class="text-cover">{!! $resto->name !!} | {!! $resto->address !!}</p>
                                    </td>
                                    <td>
                                        @if($outlet->is_delivery=="1")<span class="gdot"></span>  {{__('label.on')}} @else <span class="rdot"></span> {{__('label.off')}} @endif
                                    </td>
                                    <td>
                                        @if($outlet->is_pickup=="1")<span class="gdot"></span>  {{__('label.on')}} @else <span class="rdot"></span> {{__('label.off')}} @endif
                                    </td>
                                    <td>
                                        <a href="{!! env('APP_URL') !!}outlet/edit/{!! $outlet->unique_key !!}" type="button" class="waves-effect waves-circle btn btn-circle btn-primary btn-xs mb-5"><i class="mdi mdi-tooltip-edit"></i></a>
                                        <a type="button" data-id="{!! $outlet->unique_key !!}" class="waves-effect waves-circle btn btn-circle delete-outlet btn-danger btn-xs mb-5"><i class="mdi mdi-delete"></i></a>
                                    </td>
                                </tr>
                             @endforeach
                                    @endif

                                </tbody>
                            </table>
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

            $('.table').DataTable({
                paging: true,
                bLengthChange:false,
                searching: false,
                 language: {
                @if($lang=='ar')
                url:`{{asset('public/assets/js/dataTablear.json')}}`,
                @endif

            },

            });

            $("body").on("click",".delete-outlet",function () {



                var id = $(this).data('id');
                var _this = $(this);

                swal({
                        title: " Confirm?",
                        text: "Do you want delete?",
                        type: "error",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: " Confirm, delete it!",
                        cancelButtonText: "No, cancel please!",
                        closeOnConfirm: true,
                        closeOnCancel: true
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url:'{!! env('APP_URL') !!}outlet/delete/'+id,

                                success:function (response) {
                                    $.toast({
                                        heading: 'Outlet Status',
                                        text: 'Outlet is delete successfully.',
                                        position: 'top-right',
                                        loaderBg: '#ff6849',
                                        icon: 'success',
                                        hideAfter: 3000,
                                        stack: 1
                                    });
                                    _this.parents('tr').remove();
                                }

                            });
                        }
                    });


            });

            $("body").on("click",".switch-me",function () {
                var is_active = $(this).attr("aria-pressed");
                var id = $(this).data('id');
                is_active = $.trim(is_active);
                var status = 0;
                if(is_active=="false"){
                    status = 0;
                }else{
                    status = 1;
                }

                $.ajax({
                    url:"{!! env('APP_URL') !!}outlet/update/status",
                    type:"POST",
                    data:{
                        id:id,
                        status:status,
                        "_token":'{!! csrf_token() !!}'
                    },
                    success:function () {
                        if(is_active=="false"){
                            $.toast({
                                heading: 'Outlet Status',
                                text: 'Outlet is deactive',
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 3000,
                                stack: 1
                            });
                        }else{
                            $.toast({
                                heading: 'Outlet Status',
                                text: 'Outlet is deactive.',
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
