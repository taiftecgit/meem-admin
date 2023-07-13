@extends('layouts.app')
@section('page-title')| {{__('label.discounts')}}  @endsection
@section('css')
    <link href="{!! env('APP_ASSETS') !!}css/discounts.css" rel="stylesheet" type="text/css">
@endsection

@php
    $resto = \App\Models\Restaurants::find(\App\Helpers\CommonMethods::getRestuarantID());
    $lang = $resto->default_lang;

    app()->setLocale($lang);
    if(session('app_lang') !==null){
    $lang = session('app_lang');
    app()->setLocale($lang);

    }
@endphp

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
        .flex-item{
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }
        .btn-md.add-discount{
            width: max-content;
            margin-right: 10px;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Main content -->
            <section class="content">

                <div class="row m-0">
                    <div class="col-md-6">
                        <div class="page-top-title">
                            <h3 class="title m-0">{{__('label.paymentlinks')}}</h3>
                        </div>
                    </div>

                    @php
                        $restuarant1 = $resto;
                            $resto_meta = isset($restuarant1->resto_metas)?$restuarant1->resto_metas:null;

                            //dump($resto_meta);
                             $resto_metas = [];
                                $billing = [];
                                if(isset($resto_meta)){
                                    foreach($resto_meta as $meta){
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
                    <div class="col-md-6 flex-item">
                        <a href="{!! env('APP_URL') !!}payment/link/new"  class="form-control btn btn-primary btn-md add-discount text-center">
                            <i class="fa  fa-plus mr-2"></i>
                            <!-- <i class="mdi mdi-plus-circle"></i> --> {{__('label.add_payment_link')}}
                        </a>
                    </div>
                </div>




                <div class="row">
                    <div class="col-12 mt-4">
                        <div class="table-responsive">
                            <table class="table table-striped" id="discount-table">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th scope="col">{{__('label.purpose')}}</th>
                                    <th scope="col">{{__('label.outletname')}}</th>
                                    <th scope="col">{{__('label.amount')}}</th>
                                    <th scope="col">{{__('label.usage')}}</th>
                                    <th scope="col">{{__('label.creation_date')}}</th>

                                    <th scope="col"></th>


                                </tr>
                                </thead>
                                <tbody>

                                @if(isset($payment_links) && $payment_links->count() > 0)
                                    @foreach($payment_links as $payment_link)
                                        <tr>
                                            <td>
                                                <button type="button"  data-on-text="Open"  data-off-text="Closed" class="btn  btn-toggle btn-sm btn-success @if($payment_link->status=="active") active @endif switch-me" data-id="{!! $payment_link->unique_key !!}" data-bs-toggle="button" aria-pressed="@if($payment_link->status=="active") true @else false @endif" autocomplete="off">
                                                    <div class="handle"></div>
                                                </button> {{__('label.active')}}
                                            </td>
                                            <td>
                                                {!! $payment_link->purpose_payment !!}
                                            </td>
                                            <td>{!! $payment_link->outlet_id !!}</td>
                                            <td>{!! $payment_link->amount !!}</td>
                                            <td>{!! $payment_link->number_of_uses !!}</td>
                                            <td>{!! \Carbon\Carbon::parse($payment_link->created_at)->format('d, M Y') !!}</td>
                                            <td>
                                                <a href="{!! env('APP_URL') !!}payment/link/{!! $payment_link->unique_id !!}"  class="waves-effect waves-circle btn btn-circle  btn-success btn-xs mb-5"><i class="mdi mdi-eye"></i></a>

                                                <a type="button" data-id="{!! $payment_link->id !!}" class="waves-effect waves-circle btn btn-circle delete-payment-link btn-danger btn-xs mb-5"><i class="mdi mdi-delete"></i></a>

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

            $("body").on("click",".switch-me",function (e) {
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
                    url:"{!! env('APP_URL') !!}payment/update/status",
                    type:"POST",
                    data:{
                        id:id,
                        status:status,
                        "_token":'{!! csrf_token() !!}'
                    },
                    success:function () {
                        if(is_active=="false"){
                            $.toast({
                                heading: "{{__('label.discount_status')}}",
                                text: "{{__('label.discount_deactive')}}",
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 3000,
                                stack: 1
                            });
                        }else{
                            $.toast({
                                heading: "{{__('label.discount_status')}}",
                                text: "{{__('label.discount_deactive')}}",
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'success',
                                hideAfter: 3000,
                                stack: 1
                            });
                        }
                    }
                });

                e.preventDefault();
                e.stopPropagation();


            });

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

            $("body").on("click",".delete-discount",function(e){
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
                                url:"{!! env('APP_URL') !!}delete/discount/"+id,
                                success:function(){
                                    _this.parent('tr').remove();
                                }
                            });
                        }
                    });



                e.preventDefault();
                e.stopPropagation();
            });




        })
    </script>
@endsection
