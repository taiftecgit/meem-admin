@extends('layouts.app')
@section('content')
@php
        $resto = \Illuminate\Support\Facades\Auth::user()->restaurants;

$lang = $resto->default_lang;

app()->setLocale($lang);
if(session('app_lang') !==null){
$lang = session('app_lang');
app()->setLocale($lang);
}
@endphp
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
      @media (min-width: 820px) and (max-width: 1024px){
            html[dir="rtl"] .content-wrapper {
                width: 100% !important;
                margin-right: 0px !important;
                padding-left: 20px;
                padding-right: 10px;
            }
            html[dir="rtl"] .sidebar_div_main{
              padding-left: 0px;
            }
        }
    </style>

    <!-- Content Wrapper. Contains page content -->


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">


            <!-- Main content -->
            <section class="content">
                <div class="row">
                     <div class="col-12 col-sm-4 sidebar_div_main" style="padding-right: 0;background-color: #F5F5F5">
                        @include('outlets.outlet-sidebar')
                    </div>
                    <div class="col-12 col-sm-8 p-15">
                        <form id="save-outlet" method="POST" action="{!! env('APP_URL') !!}save/features/outlet" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="feature_type" value="pickup" />
                                                <input type="hidden" name="id" value="{!! $outlet->id !!}" />
                                            <div class="p-15">


                                            <div class="d-flex bd-highlight">
                                                <div class="p-2 flex-grow-1 bd-highlight">
                                                    <h4 style="margin-top: 0; margin-bottom: 5px">{{__('label.pickup')}}</h4>
                                                    <p>{{__('label.customers_can_pickup_their_orders_at_outlet')}} </p>
                                                </div>
                                                <div class="p-2 bd-highlight">
                                                    @php
                                                      $bstatus=($outlet->is_pickup=="1")?'active':'';
                                                      $bflag=($outlet->is_pickup=="1")? true:false;
                                                      $bcolor=($outlet->is_pickup=="1")? 'success':'danger';
                                                    @endphp
                                                    <button type="button" class="btn btn-lg btn-toggle btn-{{$bcolor}} switch {{$bstatus}} switch" data-bs-toggle="button" aria-pressed="{!! $bflag !!}" autocomplete="off">
                                                        <div class="handle"></div>
                                                    </button>
                                                </div>
                                            </div>



                                                <div id="main"  @if($outlet->is_pickup=="1") class="active" @endif>
                                                    <div class="disabled"></div>
                                                    <h4>{{__('label.payment_methods')}}</h4>
                                                    <p>{{__('label.select_all_payment_methods_accepted_on_delivery')}}</p>
                                                    @php
                                                        $payment_methods = [];
                                                        if(isset($features) && !empty($features->payment_methods)){
                                                            $payment_methods = explode(',',$features->payment_methods);
                                                        }

                                                    @endphp
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <input type="checkbox" id="card_on_delivery" name="payment_methods[]" @if(in_array('card',$payment_methods)) checked @endif value="card" class="filled-in chk-col-primary" />
                                                            <label for="card_on_delivery">{{__('label.card_on_delivery')}}</label>           <br />
                                                            <input type="checkbox" id="cash_on_delivery"  name="payment_methods[]"  @if(in_array('cash',$payment_methods)) checked @endif value="cash" class="filled-in chk-col-primary" />
                                                            <label for="cash_on_delivery">{{__('label.cash_on_delivery')}}</label>
                                                        </div>
                                                    </div>


                                                    <h4> {{__('label.opening_hours')}}</h4>
                                                    <p>{{__('label.select_all_payment_methods_accepted_on_delivery')}}</p>

                                                    @php
                                                        $days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
                                                    $existing_days = \App\Models\BranchHours::where('branch_id',$outlet->id)->where('hours_for','pickup')->get();
                                                 $day_exists = [];
                                                    @endphp

                                                    @if(isset($existing_days) && $existing_days->count() > 0)
                                                        @foreach($existing_days as $d)
                                                            <div class="row mb-3 hour-section">
                                                                <div class="col-sm-2"> @if(!in_array($d->day_name,$day_exists))

                                                                    {{__('label.'.trim(strtolower($d->day_name)))}}
                                                                @endif</div>
                                                                <div class="col-sm-2">
                                                                    @if(!in_array($d->day_name,$day_exists))
                                                                        <button type="button"  data-on-text="Open"  data-off-text="Closed" class="btn  btn-toggle btn-sm btn-success update-hour-status @if($d->status=="open") active @endif" data-bs-toggle="button" aria-pressed="@if($d->status=="open") true @else false @endif" autocomplete="off">
                                                                            <div class="handle"></div>
                                                                        </button>
                                                                        <input type="checkbox" name="is_open[{!! strtolower($d->day_name) !!}]" @if($d->status=="open") checked @endif />
                                                                    @endif
                                                                </div>
                                                                <div class="col-sm-3 time-show start_time" @if($d->status=="close") style="display: none" @endif >
                                                                    <div class="bootstrap-timepicker">
                                                                        <input type="text" name="start_time[{!! strtolower($d->day_name) !!}][]" value="{!! $d->start_time !!}" class=" form-control timepicker">
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-3 time-show end_time" @if($d->status=="close") style="display: none" @endif>
                                                                    <div class="bootstrap-timepicker">
                                                                        <input type="text" name="end_time[{!! strtolower($d->day_name) !!}][]" value="{!! date('H:i a',strtotime($d->end_time)) !!}"  class="form-control timepicker ">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2 time-show " @if($d->status=="close") style="display: none" @endif>
                                                                    @if(!in_array($d->day_name,$day_exists))
                                                                        <a href="#!" class="text-primary add-more-hours"  data-day="{!! strtolower($d->day_name) !!}"> <i class="fa fa-plus-square-o"></i>{{__('label.add_hours')}}</a>
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
                                                                <div class="col-sm-2">{{__('label.'.trim(strtolower($d)))}}</div>
                                                                <div class="col-sm-2">
                                                                    <button type="button"  data-on-text="Open"  data-off-text="Closed" class="btn  btn-toggle btn-sm btn-success active update-hour-status" data-bs-toggle="button" aria-pressed="true" autocomplete="off">
                                                                        <div class="handle"></div>
                                                                    </button>
                                                                    <input type="checkbox" name="is_open[{!! strtolower($d) !!}]" checked />
                                                                </div>
                                                                <div class="col-sm-3 time-show start_time">
                                                                    <div class="bootstrap-timepicker">
                                                                        <input type="text" name="start_time[{!! strtolower($d) !!}][]" class="form-control timepicker time">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 time-show end_time">
                                                                    <div class="bootstrap-timepicker">
                                                                        <input type="text" name="end_time[{!! strtolower($d) !!}][]" class="form-control timepicker  time">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2 time-show"><a href="#!" class="text-primary add-more-hours" data-day="{!! strtolower($d) !!}"> <i class="fa fa-plus-square-o"></i> {{__('label.add_hours')}}</a></div>
                                                            </div>
                                                        @endforeach
                                                    @endif

                                                    <h4>{{__('label.operation_settings')}}</h4>
                                                    <p>{{__('label.set_the_default_time_needed_for_preparing_and_delivering_orders')}}</p>

                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>{{__('label.preparation_minutes')}}</label>
                                                                <input type="text" name="preparation_time" class="form-control" value="{!! isset($features)?$features->preparation_timing:15 !!}"  />
                                                            </div>
                                                        </div>
                                                        <!-- <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>Preparation + delivery (minutes)</label>
                                                                <input type="text"  name="preperation_delivery" class="form-control" value="{!! isset($features)?$features->preparation_delivery_time:40 !!}"  />
                                                            </div>
                                                        </div> -->
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <a href="#!" class="btn btn-primary save-changes mt-4">{{__('label.save_changes')}}</a>
                                                        </div>
                                                    </div>


                                                </div>
                                        </div>
                                            </form>
                    </div>
                </div>
            </section>
        </div>
    </div>



    <!-- /.content-wrapper -->



@endsection

@section('js')
    <script src="{!! env('APP_ASSETS') !!}js/jquery.timepicker.min.js"></script>
    <script>
        $(function () {

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
                    feature:'is_pickup',
                    is_active:is_active,
                    "_token":"{!! csrf_token() !!}"
                },
                type:"POST",
                success:function () {

                }
            });
        });

            $("body").on("click",".add-more-hours",function () {
                var _hour_section = $(this).parents(".hour-section");
                var day = $(this).data('day');
                var start_time='<div class="bootstrap-timepicker mt-1">\n' +
                    '                                                                <input type="text" name="start_time['+day+'][]" class="form-control timepicker time">\n' +
                    '                                                                </div>';
                var end_time='<div class="bootstrap-timepicker mt-1">\n' +
                    '                                                                <input type="text" name="end_time['+day+'][]" class="form-control timepicker time">\n' +
                    '                                                                </div>';
                var input_start_time = _hour_section.find('.col-sm-3.time-show.start_time').append(start_time);

                var input_end_time = _hour_section.find('.col-sm-3.time-show.end_time').append(end_time);

                  $(".timepicker").timepicker({ step:5});
        $('.timepicker').on('showTimepicker',function(){
             $("li['data-time=86340']").remove();
            $("body").find(".ui-timepicker-list").append('<li id="li-11-59" class="ui-timepicker-pm" data-time="86340">11:59pm</li>');
        });

            });

           $(".timepicker").timepicker({ step:5});
                $('.timepicker').on('showTimepicker',function(){
                   $("li['data-time=86340']").remove();
                   $("body").find(".ui-timepicker-list").append('<li id="li-11-59" class="ui-timepicker-pm" data-time="86340">11:59pm</li>');


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
                                heading: '{{__("label.outlet_update")}}',
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
                                heading: '{{__("label.outlet_update")}}',
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

            $("body").on("click",".switch",function () {
                var is_active = $(this).attr("aria-pressed");
                if(is_active=="false"){
                    $("#main").removeClass('active');
                }else{
                    $("#main").addClass('active');
                }
            });
        })
    </script>
@endsection
