@extends('layouts.app')
@section('content')
    <link href="{!! env('APP_ASSETS') !!}/vendor_components/dropzone/dropzone.css" rel="stylesheet"/>
    <link href="{!! env('APP_ASSETS') !!}/vendor_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet"/>
    <link href="{!! env('APP_ASSETS') !!}/css/jquery.timepicker.min.css" rel="stylesheet"/>
    <style>
        .vtabs .tabs-vertical {
            width: 229px;
        }
        .bootstrap-tagsinput {
            min-height: 60px; width: 100%;
        }
        h4{ margin-top: 40px}
        .bootstrap-timepicker-widget table td input{ width: 46px}

        .content{
            padding: 0;
        }
        .form-control, .form-select {
            height: 46px !important;
            border-color: #E4E6EB !important;
            border-radius: 7px !important;
        }
        .payout-details{
            background-color: #fff4de;
            border-radius: 3px;
            padding: 15px;
        }
        .theme-primary [type=checkbox].filled-in:checked.chk-col-primary + label:after {
            border: 2px solid #ffab00;
            background-color: #ffab00;
        }

        .theme-primary .btn-success:hover, .theme-primary .btn-success:active, .theme-primary .btn-success:focus, .theme-primary .btn-success.active {
            background-color: #3fd642!important;
            border-color: #3fd642!important;
            color: #ffffff !important;
        }
        #output canvas{
            width: 100%;
        }

    </style>
@php
        $resto = \Illuminate\Support\Facades\Auth::user()->restaurants;

$lang = $resto->default_lang; 

app()->setLocale($lang);
if(session('app_lang') !==null){
$lang = session('app_lang');
app()->setLocale($lang);
}
@endphp
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
           {{-- <div class="content-header">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <h4 class="page-title">Outlets</h4>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{!! env('APP_URL') !!}dashboard"><i
                                                    class="mdi mdi-home-outline"></i></a></li>

                                    <li class="breadcrumb-item active" aria-current="page">Outlets</li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                </div>
            </div>--}}

            <!-- Main content -->
            <section class="content">
                <div class="row">

                    <div class="col-3" style="padding-right: 0;background-color: #F5F5F5">
                        @include('outlets.outlet-sidebar')
                    </div>

                    <div class="col-9 p-15">
                        <form id="save-outlet" method="POST" action="{!! env('APP_URL') !!}save/features/outlet" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="feature_type" value="contactless_dining" />
                            <input type="hidden" name="id" value="{!! $outlet->id !!}" />
                            <div class="p-15">

                                <div class="d-flex bd-highlight">
                                    <div class="p-2 flex-grow-1 bd-highlight">
                                        <h4 style="margin-top: 0; margin-bottom: 5px">Contactless dining</h4>
                                        <p> Customers can order for dine-in at the outlet </p>
                                    </div>
                                    <div class="p-2 bd-highlight">
                                        <button type="button" class="btn btn-lg btn-toggle btn-success switch @if($outlet->is_contactless_dining=="1") active @endif switch" data-bs-toggle="button" aria-pressed="@if($outlet->is_contactless_dining=="1") true @else false @endif" autocomplete="off">
                                            <div class="handle"></div>
                                        </button>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-12 payout-details">
                                        <p class="fw-bold">Recieve your payouts</p>
                                        <p>
                                            Fill up your bank details here so we can transfer your money.
                                            Learn more about payout cycles <a href="#!" class="fw-bold">here</a>
                                        </p>
                                    </div>
                                </div>

                                <div id="main" @if($outlet->is_contactless_dining=="1") class="active" @endif>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-8">
                                            <div class="disabled"></div>
                                    <h4> Payment Methods</h4>
                                    <p>  Select all payment methods accepted on delivery  </p>
                                    @php
                                        $payment_methods = [];
                                        if(isset($features) && !empty($features->payment_methods)){
                                            $payment_methods = explode(',',$features->payment_methods);
                                        }

                                    @endphp
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <input type="checkbox" id="card_on_delivery" name="payment_methods[]" @if(in_array('card',$payment_methods)) checked @endif value="card" class="filled-in chk-col-primary" />
                                            <label for="card_on_delivery">Card on delivery</label>           <br />
                                            <input type="checkbox" id="cash_on_delivery"  name="payment_methods[]"  @if(in_array('cash',$payment_methods)) checked @endif value="cash" class="filled-in chk-col-primary" />
                                            <label for="cash_on_delivery">Cash on delivery</label>
                                        </div>
                                    </div>


                                    <h4> Opening hours</h4>
                                    <p>  Select all payment methods accepted on delivery  </p>

                                    @php
                                        $days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
                                    $existing_days = \App\BranchHours::where('branch_id',$outlet->id)->where('hours_for','contactless_dining')->get();
                                    $day_exists = [];

                                    @endphp

                                    <div class="row mb-3">
                                        <div class="col-sm-2">
                                            <p class="fw-bold">Day</p>
                                        </div>

                                        <div class="col-sm-3"><p  class="fw-bold">From</p></div>
                                        <div class="col-sm-3">
                                            <p  class="fw-bold">To</p>
                                        </div>
                                    </div>

                                    @if(isset($existing_days) && $existing_days->count() > 0)
                                        @foreach($existing_days as $d)
                                            <div class="row mb-3 hour-section">
                                                <div class="col-sm-2"> @if(!in_array($d->day_name,$day_exists)){!! ucwords($d->day_name) !!}@endif</div>

                                                <div class="col-sm-3 time-show start_time" @if($d->status=="close") style="display: none" @endif >
                                                    <div class="bootstrap-timepicker">
                                                        <input type="text" name="start_time[{!! strtolower($d->day_name) !!}][]" value="{!! $d->start_time !!}" class=" form-control timepicker">
                                                    </div>
                                                </div>

                                                <div class="col-sm-3 time-show end_time position-relative" @if($d->status=="close") style="display: none" @endif>
                                                    <div class="bootstrap-timepicker position-relative">
                                                        <input type="text" name="end_time[{!! strtolower($d->day_name) !!}][]" value="{!! date('H:i a',strtotime($d->end_time)) !!}"  class="form-control timepicker ">
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 time-show  hours-action" @if($d->status=="close") style="display: none" @endif>
                                                    @if(!in_array($d->day_name,$day_exists))
                                                        <a href="#!" class="text-primary add-more-hours"  data-day="{!! strtolower($d->day_name) !!}"> <i class="fa fa-plus-square-o"></i> Add hours</a>
                                                    @endif
                                                        @if(in_array($d->day_name,$day_exists))
                                                        <a href="#!" class="text-mute remove-hours"  data-id="{!! $d->id !!}" data-day="{!! strtolower($d->day_name) !!}"> <i class="fa fa-ban"></i> Remove hours</a>
                                                            @endif
                                                </div>
                                                <div class="col-sm-2">
                                                    @if(!in_array($d->day_name,$day_exists))
                                                        <button type="button"  data-on-text="Open"  data-off-text="Closed" class="btn  btn-toggle btn-sm btn-success update-hour-status @if($d->status=="open") active @endif" data-bs-toggle="button" aria-pressed="@if($d->status=="open") true @else false @endif" autocomplete="off">
                                                            <div class="handle"></div>
                                                        </button>
                                                        <input type="checkbox" name="is_open[{!! strtolower($d->day_name) !!}]" @if($d->status=="open") checked @endif />
                                                    @endif
                                                </div>
                                            </div>
                                            @php
                                                $day_exists[] = $d->day_name;
                                            @endphp
                                        @endforeach
                                    @else
                                        @foreach($days as $d)
                                            <div class="row mb-3 hour-section">
                                                <div class="col-sm-2">{!! $d !!}</div>

                                                <div class="col-sm-3 time-show start_time">
                                                    <div class="bootstrap-timepicker">
                                                        <input type="text" name="start_time[{!! strtolower($d) !!}][]" class="form-control timepicker time">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 time-show end_time position-relative">
                                                    <div class="bootstrap-timepicker">
                                                        <input type="text" name="end_time[{!! strtolower($d) !!}][]" class="form-control timepicker  time">
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 time-show hours-action"><a href="#!" class="text-primary add-more-hours" data-day="{!! strtolower($d) !!}"> <i class="fa fa-plus-square-o"></i> Add hours</a></div>
                                                <div class="col-sm-2">
                                                    <button type="button"  data-on-text="Open"  data-off-text="Closed" class="btn  btn-toggle btn-sm btn-success active update-hour-status" data-bs-toggle="button" aria-pressed="true" autocomplete="off">
                                                        <div class="handle"></div>
                                                    </button>
                                                    <input type="checkbox" name="is_open[{!! strtolower($d) !!}]" checked />
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                    <!-- <h4> Operation Settings</h4>
                                    <p>Set the default time needed for preparing and delivering orders</p>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Preparation (minutes)</label>
                                                <input type="text" name="preparation_time" class="form-control" value="{!! isset($features)?$features->preparation_timing:15 !!}"  />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Preparation + delivery (minutes)</label>
                                                <input type="text"  name="preperation_delivery" class="form-control" value="{!! isset($features)?$features->preparation_delivery_time:40 !!}"  />
                                            </div>
                                        </div>
                                    </div> -->

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <a href="#!" class="btn btn-primary save-changes mt-4">Save Changes</a>
                                        </div>
                                    </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <div class="card mt-4">
                                                <div class="card-header">Contactless dining QR code</div>
                                                <div class="card-body">
                                                    <div id="output"></div>
                                                   

                                                    <a style="width: 100%; margin:  10px 0" class="btn btn-primary download-image ">Download</a>

                                                    <p>This is your unique QR code for customers to see the menu and order from this outlet. Print it and leave it on the tables. Customers just need to scan it with a smartphone to place their order.</p>
                                                    @php
                                                          $resto = \App\Restaurants::find(\App\Helpers\CommonMethods::getRestuarantID());
                                                    @endphp

                                                    <input type="text" class="form-control" readonly name="" value="{!! env('QRCODE_HOST') !!}dining/{!! $resto->resto_unique_name !!}/outlet/{!! \Illuminate\Support\Str::slug($outlet->name) !!}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    


                                </div>

                            </div>
                        </form>
                    </div>


                </div>
            </section>
            <!-- /.content -->

        </div>
         <div id="download" style="display: none"></div>
    </div>
    <!-- /.content-wrapper -->



@endsection

@section('js')
    <script src="{!! env('APP_ASSETS') !!}js/jquery.timepicker.min.js"></script>
    <script src="{!! env('APP_ASSETS') !!}js/jquery.qrcode.min.js"></script>
<script>
    $(function () {


        $('#output').qrcode({ 
                render: "canvas",
                text: "{!! env('QRCODE_HOST') !!}dining/{!! $resto->resto_unique_name !!}/outlet/{!! \Illuminate\Support\Str::slug($outlet->name) !!}",
                width: 250,
                height: 250,
                background: "#ffffff",
                foreground: "#000000",
              /*  src: "{!! isset(\Illuminate\Support\Facades\Auth::user()->restaurants) && isset(\Illuminate\Support\Facades\Auth::user()->restaurants->home_images) && !empty(\Illuminate\Support\Facades\Auth::user()->restaurants->home_images->file_name)?\Illuminate\Support\Facades\Auth::user()->restaurants->home_images->file_name:env('APP_URL').'public/layout/img/favicon.png' !!}",
               */ imgWidth: 50,
                imgHeight: 50
            });

            $('#download').qrcode({
                render: "canvas",
               
                  text: "{!! env('QRCODE_HOST') !!}dining/{!! $resto->resto_unique_name !!}/outlet/{!! \Illuminate\Support\Str::slug($outlet->name) !!}",
                width: 2000,
                height: 2000,
                background: "#ffffff",
                foreground: "#000000",
                 /* src: "{!! isset(\Illuminate\Support\Facades\Auth::user()->restaurants) && isset(\Illuminate\Support\Facades\Auth::user()->restaurants->home_images) && !empty(\Illuminate\Support\Facades\Auth::user()->restaurants->home_images->file_name)?\Illuminate\Support\Facades\Auth::user()->restaurants->home_images->file_name:env('APP_URL').'public/layout/img/favicon.png' !!}",
                 */ imgWidth: 500,
                imgHeight: 500
            });

            $(".download-image").click(function () {
                var canvas = $('#download canvas')[0];
                var _this = $(this);

              
// Change here
                $.ajax({
                    url: "{!! env('APP_URL') !!}download/qrcode",
                    type: "POST",
                    data: {
                        resto:"dining-{!! $resto->resto_unique_name !!}-outlet-{!! \Illuminate\Support\Str::slug($outlet->name) !!}",
                        data: canvas.toDataURL(),
                        '_token': "{!! csrf_token() !!}"
                    },
                    success: function (response) {
                        console.log(response);
                        var link = document.createElement('a');
                        link.href = response;
                        link.download = "dining-{!! $resto->resto_unique_name !!}-qrcode.png";
                        link.click();
                    }
                });
            });

        $("body").on("click",".remove-hours",function () {
            var _id = $(this).data('id');
            var _this = $(this);

            $.ajax({
                url:"{!! env('APP_URL') !!}remove/hour",
                type:"POST",
                data:{
                    id:_id,
                    "_token":"{!! csrf_token() !!}"
                },
                success:function (response) {
                    response = $.parseJSON(response);

                    if(response.type=="success"){
                        $.toast({
                            heading: 'Hours',
                            text: response.message,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 3000,
                            stack: 6
                        });
                    }else{
                        $.toast({
                            heading: 'Hours',
                            text: response.message,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'danger',
                            hideAfter: 3000,
                            stack: 6
                        });
                    }
                }
            });

            _this.parents('.hour-section').remove();
        });


        $("body").on("click",".add-more-hours",function () {
            var _hour_section = $(this).parents(".hour-section");
            var day = $(this).data('day');
            var start_time='<div class="bootstrap-timepicker mt-1">\n' +
                '                                                                <input type="text" name="start_time['+day+'][]" class="form-control timepicker time">\n' +
                '                                                                </div>';
            var end_time='<div class="bootstrap-timepicker mt-1">\n' +
                '                                                                <input type="text" name="end_time['+day+'][]" class="form-control timepicker time">\n' +
                '                                                                </div><i class="fa fa-ban delete-hour"></i>';
            var input_start_time = _hour_section.find('.col-sm-3.time-show.start_time').append(start_time);

            var input_end_time = _hour_section.find('.col-sm-3.time-show.end_time').append(end_time);
           /* var hour_sections = _hour_section.find('.col-sm-2.time-show.hours-action').append('<div style="margin-top: 37px"><a href="#!" class="text-mute remove-hours" data-id="60" data-day="sunday"> <i class="fa fa-ban"></i> Remove hours</a></div>');
*/
            $(".timepicker").timepicker({ step:5});
            $('.timepicker').on('showTimepicker',function(){
                $("#li-11-59").remove();
                $("body").find(".ui-timepicker-list").append('<li id="li-11-59" class="ui-timepicker-pm" data-time="86340">11:59pm</li>');


            });

        });

        $("body").on("click",".update-hour-status",function () {
            var is_active = $(this).attr("aria-pressed");
            is_active = $.trim(is_active);

            if(is_active=="false"){
            //    alert('off');
                $(this).parents('.hour-section').find('input[type=checkbox]').prop('checked',false);
                $(this).parents('.hour-section').find('.time-show').hide();
            }else{
                $(this).parents('.hour-section').find('input[type=checkbox]').prop('checked','checked');
                $(this).parents('.hour-section').find('.time-show').show();
            }

        })

        /*$("body").on("click",".action-on-off",function () {
            var unique_key =
        });*/


        $(".timepicker").timepicker({ step:5});
        $('.timepicker').on('showTimepicker',function(){
            $("#li-11-59").remove();
            $("body").find(".ui-timepicker-list").append('<li id="li-11-59" class="ui-timepicker-pm" data-time="86340">11:59pm</li>');


        });
        $("body").on("click",".switch",function () {
            var is_active = $(this).attr("aria-pressed");
            is_active = $.trim(is_active);
            if(is_active=="false"){
                $("#main").removeClass('active');
            }else{
                $("#main").addClass('active');
            }

            $.ajax({
                url:"{!! env('APP_URL') !!}update/outlet/feature/status",
                data:{
                    outletId:"{!! $outlet->id !!}",
                    feature:'is_contactless_dining',
                    is_active:is_active,
                    "_token":"{!! csrf_token() !!}"
                },
                type:"POST",
                success:function () {

                }
            });
        });

        $("body").on("click",".save-changes",function () {

            /*$.toast({
                heading: 'Welcome to my Riday Admin',
                text: 'Use the predefined ones, or specify a custom position object.',
                position: 'top-right',
                loaderBg: '#ff6849',
                icon: 'info',
                hideAfter: 3000,
                stack: 6
            });*/

            if($("#save-outlet").valid()){
                $("#save-outlet").ajaxForm(function (response) {

                    response = $.parseJSON(response);

                    if(response.type=="success"){
                        $.toast({
                            heading: 'Outlet Update.',
                            text: response.message,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 3000,
                            stack: 1
                        });

                        setTimeout(function () {
                            location.reload();
                        },2000);
                    }else{
                        $.toast({
                            heading: 'Outlet Update.',
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
