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
    $restuarant1 =$resto;
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

        .ar-mrl-adjust{
            margin-left: 10px;
        }
        html[dir="rtl"]  .ar-mrl-adjust{
            margin-right: 10px;
            margin-left: 0;
        }
        html[dir="rtl"]  .ar-m-0{
            margin: 0 !important;
        }

    </style>


    <div class="content-wrapper">
        <div class="container-full">
            <section class="content pt-3">


                    <div class="row ar-m-0">
                        <div class="col-xl-8">
                            <div class="card mb-4">



                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-9">
                                            <h1>Payment link #{!! $payment_link->unique_id !!}</h1>
                                            <p class="mt-40 mb-1"> {!! \Carbon\Carbon::parse($payment_link->created_at)->format('M d, Y') !!}</p>
                                            <h5 class="fw-bold">{!! $payment_link->amount !!} {!! $currency !!}</h5>
                                            <div class="d-flex align-items-center">
                                                <div style="width: 90%">
                                                    <div class="progress mt-20 ">
                                                        <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>

                                                <div class="ar-mrl-adjust">
                                                    {!! $payment_link->payments_count?$payment_link->payments_count:0 !!} / {!! $payment_link->number_of_uses !!}
                                                </div>
                                            </div>

                                            <p class="text-danger mt-0">{!! $payment_link->number_of_uses - $payment_link->payments_count !!} {{__('label.payment_left')}}</p>

                                            <dl class="row mt-30">
                                                <dt class="col-sm-3">{{__('label.purpose_of_payment')}}</dt>
                                                <dd class="col-sm-9">{!! $payment_link->purpose_payment !!}</dd>
                                                <dt class="col-sm-3">{{__('label.payment_message')}}</dt>
                                                <dd class="col-sm-9">{!! $payment_link->payment_message !!}</dd>
                                                <dt class="col-sm-3">{{__('label.outlet')}}</dt>
                                                <dd class="col-sm-9">{!! isset($payment_link->outlets)?$payment_link->outlets->name:"" !!}</dd>
                                                <dt class="col-sm-3">{{__('label.created_at')}}</dt>
                                                <dd class="col-sm-9">{!! \Carbon\Carbon::parse($payment_link->created_at)->format('d, M Y') !!}</dd>
                                            </dl>

                                        <div class="row mt-20">
                                            <div class="col-sm-12">
                                                <h3 class="link"><a href="{!! env('QRCODE_HOST') !!}{!! $payment_link->restuarents->name !!}/payment/link/{!! $payment_link->unique_id !!}">{!! env('QRCODE_HOST') !!}{!! $payment_link->restuarents->resto_unique_name !!}/payment/link/{!! $payment_link->unique_id !!}</a> </h3>
                                            </div>
                                        </div>

                                            <div class="row mt-10">
                                                <div class="col-m-12">
                                                    <button class="btn btn-primary copy-link" data-link="{!! env('QRCODE_HOST') !!}{!! $payment_link->restuarents->resto_unique_name !!}/payment/link/{!! $payment_link->unique_id !!}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-back" viewBox="0 0 16 16">
                                                            <path d="M0 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2h2a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2H2a2 2 0 0 1-2-2V2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H2z"/>
                                                        </svg> &nbsp; Copy Link</button>
                                                    <button class="btn btn-primary copy-whatsapp-link" data-link="{!! env('QRCODE_HOST') !!}{!! $payment_link->restuarents->resto_unique_name !!}/payment/link/{!! $payment_link->unique_id !!}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                                                            <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                                                        </svg> &nbsp; Share Link via whatsapp</button>
                                                    <button class="btn btn-primary qrcode-link" data-link="{!! env('QRCODE_HOST') !!}{!! $payment_link->restuarents->resto_unique_name !!}/payment/link/{!! $payment_link->unique_id !!}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-qr-code-scan" viewBox="0 0 16 16">
                                                            <path d="M0 .5A.5.5 0 0 1 .5 0h3a.5.5 0 0 1 0 1H1v2.5a.5.5 0 0 1-1 0v-3Zm12 0a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0V1h-2.5a.5.5 0 0 1-.5-.5ZM.5 12a.5.5 0 0 1 .5.5V15h2.5a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5Zm15 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1 0-1H15v-2.5a.5.5 0 0 1 .5-.5ZM4 4h1v1H4V4Z"/>
                                                            <path d="M7 2H2v5h5V2ZM3 3h3v3H3V3Zm2 8H4v1h1v-1Z"/>
                                                            <path d="M7 9H2v5h5V9Zm-4 1h3v3H3v-3Zm8-6h1v1h-1V4Z"/>
                                                            <path d="M9 2h5v5H9V2Zm1 1v3h3V3h-3ZM8 8v2h1v1H8v1h2v-2h1v2h1v-1h2v-1h-3V8H8Zm2 2H9V9h1v1Zm4 2h-1v1h-2v1h3v-2Zm-4 2v-1H8v1h2Z"/>
                                                            <path d="M12 9h2V8h-2v1Z"/>
                                                        </svg> &nbsp; View via QR Code</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>

                        </div>
                    </div>

            </section>
        </div>
    </div>

    <div class="modal center-modal fade" id="qrcode-modal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-body text-center pt-50">
                  <div id="qrcode"></div>
                    <div id="download" style="display: none"></div>
                </div>
                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{__('label.close')}}</button>
                    <button type="button" class="btn btn-primary float-end download_qrcode">{{__('label.download_qrcode')}}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

    <script src="{!! env('APP_ASSETS') !!}js/jquery.qrcode.min.js"></script>
    <script type="text/javascript">
        $(function(){

            $("body").on("click",".copy-link",function(){
                var _link = $("h3.link a").text();
                $("h3.link a").select();
               navigator.clipboard.writeText(_link);
            });

            $("body").on("click",".copy-whatsapp-link",function(){
                var _link = $("h3.link a").text();

                window.open("https://api.whatsapp.com/send?text="+(_link),"_blank");
            });

            $("body").on("click",".qrcode-link",function(){
                var _link = $("h3.link a").text();
                $('#qrcode').html('');
                $('#download').html('');
                $('#qrcode').qrcode({
                    render: "canvas",
                    text: _link,
                    width: 350,
                    height: 350,
                    background: "#ffffff",
                    foreground: "#000000",
                    imgWidth: 50,
                    imgHeight: 50
                });

                $('#download').qrcode({
                    render: "canvas",

                    text: _link,
                    width: 2000,
                    height: 2000,
                    background: "#ffffff",
                    foreground: "#000000",
                    imgWidth: 500,
                    imgHeight: 500
                });

                $("#qrcode-modal").modal('show');

            });

            $(".download_qrcode").click(function () {
                var canvas = $('#download canvas')[0];
                var _this = $(this);

                var _link = $("h3.link a").text();
// Change here
                $.ajax({
                    url: "{!! env('APP_URL') !!}download/qrcode",
                    type: "POST",
                    data: {
                        resto:"payment-link-"+"{!! $payment_link->restuarents->resto_unique_name !!}",
                        data: canvas.toDataURL(),
                        '_token': "{!! csrf_token() !!}"
                    },
                    success: function (response) {
                        console.log(response);
                        var link = document.createElement('a');
                        link.href = response;
                        link.download = "payment-link-{!! $payment_link->restuarents->resto_unique_name !!}-qrcode.png";
                        link.click();
                    }
                });
            });

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
