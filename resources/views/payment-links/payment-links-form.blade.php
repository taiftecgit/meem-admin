@extends('layouts.app')
@section('css')


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


    <link href="{!! env('APP_ASSETS') !!}vendor_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
    <link href="{!! env('APP_ASSETS') !!}vendor_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet"/>
    <link href="{!! env('APP_ASSETS') !!}/css/jquery.timepicker.min.css" rel="stylesheet"/>
    <link href="{!! env('APP_ASSETS') !!}vendor_components/select2/dist/css/select2.min.css" rel="stylesheet">
    <style>
        .datepicker-days{
            display: block !important;
        }
        .alert {
            display: none;
        }

        .border-red {
            border: 1px solid #F00 !important;
        }
        .tab-pane{}
        .container-full,.content-wrapper{
            background-color: transparent !important;
        }
        #image-preview {
            width: 700px;
            border-radius: 20px;
            height: 341px;
            position: relative;
            overflow: hidden;
            background-color: #f9f9f9;
            color: #ecf0f1;
            background-position: center !important;
            background-size: cover !important;
        }
        #image-preview input {
            line-height: 200px;
            font-size: 200px;
            position: absolute;
            opacity: 0;
            z-index: 10;
        }
        #image-preview label {
            position: absolute;
            z-index: 5;
            opacity: 0.8;
            cursor: pointer;
            background-color: #bdc3c7;
            width: 200px;
            height: 50px;
            font-size: 20px;
            line-height: 50px;
            text-transform: uppercase;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto;
            text-align: center;
        }
        .select2-container .select2-selection--multiple{
            min-height: 46px;
        }
        .form-control, .form-select {
            height: 46px !important;
            border-color: #E4E6EB !important;
            border-radius: 7px !important;
        }
        .select2-container--default .select2-selection--single{
            height: 40px !important;
            border-color: #E4E6EB !important;
            border-radius: 7px !important;
            padding: 9px 12px;
        }

        .outlet-section,.item-section{
            display: none;
        }

        .outlets.selected .outlet-section, .items.selected .item-section{
            display: inline-flex;
        }
        .select2 {width: 100% !important;}

        html[dir="rtl"]  .ar-m-0{
            margin: 0 !important;
        }

    </style>


    <div class="content-wrapper">
        <div class="container-full">
            <section class="content pt-3">
                @if(isset($payment_link))
                    <h3 style="margin-left: 10px">New Payment Link</h3>
                @endif
                <form id="payment-link-form" method="POST" action="{!! env('APP_URL') !!}payment/link/save"
                      enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{!! isset($payment_link)?$payment_link->id:'' !!}"/>
                    <div class="row ar-m-0">
                        <div class="col-xl-8">
                            <div class="card mb-4">



                                <div class="card-body">
                                    <h4>{{__('label.add_payment_link')}}</h4>
                                    <p>Fill in the details and share the link to get paid in a few minutes.
                                    </p>


                                    <div class="row">
                                        <div class="col-sm-4 col-md-6">
                                            <div class="form-group">
                                                <label class="fw-bold">{{__('label.amount')}}*</label>
                                                <input type="text" class="form-control" name="amount"
                                                       value="{!! isset($payment_link)?$payment_link->amount:'' !!}" required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label class="fw-bold">{{__('label.usage')}}</label>
                                                <input type="text" class="form-control"  name="number_of_uses"
                                                       value="{!! isset($payment_link)?$payment_link->number_of_uses:'' !!}" required="">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4 col-md-6">
                                            <label class="fw-bold">{{__('label.outlet')}}</label>
                                            <select class="form-control" name="outlet_id" required>
                                                <option value="">Choose outlet</option>
                                                @if(isset($outlets) && $outlets->count() > 0)
                                                    @foreach($outlets as $outlet)
                                                        <option value="{!! $outlet->id !!}">{!! $outlet->name !!}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label class="fw-bold">{{__('label.purpose_of_payment')}}</label>
                                                <input type="text" class="form-control" name="purpose_payment"
                                                       value="{!! isset($payment_link)?$payment_link->purpose_payment:'' !!}" required="">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label class="fw-bold">{{__('label.payment_message')}}</label>
                                                <textarea rows="5" style="height: 70px !important;" class="form-control" name="payment_message">{!! isset($payment_link)?$payment_link->payment_message:'' !!}</textarea>
                                            </div>
                                        </div>

                                    </div>




                                </div>
                            </div>



                            <div class="card card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <a href="#!" class="btn btn-primary save-payment-link">{{__('label.save_payment_link')}}</a>
                                    </div>
                                </div>

                            </div>




                        </div>
                    </div>

                </form>
            </section>
        </div>
    </div>


@endsection

@section('js')
    <script src="{!! env('APP_ASSETS') !!}vendor_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="{!! env('APP_ASSETS') !!}vendor_components/select2/dist/js/select2.min.js"></script>
    <script src="{!! env('APP_ASSETS') !!}js/jquery.timepicker.min.js"></script>

    <script type="text/javascript">
        $(function(){

            $("body").on("click",".save-payment-link",function(){
                if($("#payment-link-form").valid()){
                    $("#payment-link-form").ajaxForm(function(response){
                        response = $.parseJSON(response);
                        if(response.type=="success"){
                            $.toast({
                                heading: "{{__('label.payment_saved')}}",
                                text: response.message,
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'success',
                                hideAfter: 3000,
                                stack: 1
                            });

                            setTimeout(function () {
                                window.location = "{!! env('APP_URL') !!}payment/links";
                            },2000);
                        }else{
                            $.toast({
                                heading:"{{__('label.payment_error')}}",
                                text: response.message,
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 3000,
                                stack: 1
                            });
                        }
                    }).submit();
                }
            });
        })
    </script>


@endsection
