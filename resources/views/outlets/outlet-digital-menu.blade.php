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

        @media (min-width: 820px) and (max-width: 1024px){
            html[dir="rtl"] .content-wrapper {
                width: 100% !important;
                margin-right: 0px !important;
                padding-left: 20px;
                padding-right: 10px;
                margin-top: 78px !important;
            }     
            html[dir="rtl"] .sidebar_div_main{
              padding-left: 0px;  
            }
        }
    </style>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
          

            <!-- Main content -->
            <section class="content">
                <div class="row">

                    <div class="col-12 col-sm-4 sidebar_div_main" style="padding-right: 0;background-color: #F5F5F5">
                        @include('outlets.outlet-sidebar')
                    </div>
@php
                                                          $resto = \App\Models\Restaurants::find(\App\Helpers\CommonMethods::getRestuarantID());
					$lang = $resto->default_lang; 

app()->setLocale($lang);
if(session('app_lang') !==null){
$lang = session('app_lang');
app()->setLocale($lang);

}
					
					
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
			$business_pwa = isset($resto_metas['USE_PWA_DMENU'])?$resto_metas['USE_PWA_DMENU']:"false";	
					
					$url = env('QRCODE_HOST')."m/".$resto->resto_unique_name."?outlet=". \Illuminate\Support\Str::slug($outlet->name);
					if($business_pwa == "true")
						$url = "https://rjsdemo.taiftec.com/";
					
					
					
					
          
@endphp
                    <div class="col-12 col-sm-8 p-15">
                       
                            

                                <div id="main" >
                                    <div class="row">
                                        
                                        <div class="col-sm-12 col-md-4">
                                            <div class="card mt-4">
                                                <div class="card-header">{{__('label.digital_menu_qr_code')}}</div>
                                                <div class="card-body">
                                                    <div id="output"></div>
                                                   

                                                    <a style="width: 100%; margin:  10px 0" class="btn btn-primary download-image ">{{__('label.download')}}</a>

                                                    <p>{{__('label.this_is_your_unique_qr_code_for_customers_to_see_the_menu_and_order_from_this_outlet_print_it_and_leave_it_on_the_tables_customers_just_need_to_scan_it_with_a_smartphone_to_place_their_order')}}</p>

                                                    <!-- <input type="text" class="form-control" readonly name="" value="{!! env('QRCODE_HOST') !!}m/{!! $resto->resto_unique_name !!}">-->
													 <input type="text" class="form-control" readonly name="" value="{!! env('QRCODE_HOST') !!}m/{!! $resto->resto_unique_name !!}?outlet={!! \Illuminate\Support\Str::slug($outlet->name) !!}"> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    


                                </div>

                            </div>
                        
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
                text: "{!! $url !!}",
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
               
                  text: "{!! $url !!}",
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

                console.log(canvas.toDataURL());
// Change here
                $.ajax({
                    url: "{!! env('APP_URL') !!}download/qrcode",
                    type: "POST",
                    data: {
                        resto:"digital-menu-{!! $resto->resto_unique_name !!}-outlet-{!! \Illuminate\Support\Str::slug($outlet->name) !!}",
                        data: canvas.toDataURL(),
                        '_token': "{!! csrf_token() !!}"
                    },
                    success: function (response) {
                        console.log(response);
                        var link = document.createElement('a');
                        link.href = response;
                          link.download = "digital-menu-{!! $resto->resto_unique_name !!}-qrcode.png";
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

    })
</script>
@endsection
