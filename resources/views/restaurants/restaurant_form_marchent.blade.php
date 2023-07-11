@extends('layouts.app')
@section('css')


@endsection

@section('content')

    <link href="{!! env('APP_ASSETS') !!}vendor_components/spectrum/spectrum.css" rel="stylesheet">
    <link href="{!! env('APP_ASSETS') !!}vendor_components/select2/dist/css/select2.min.css" rel="stylesheet">
    <link href="{!! env('APP_ASSETS') !!}/vendor_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet"/>

    <style>
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

    </style>

@php
$lang = $restaurant->default_lang; 

 

app()->setLocale($lang);
if(session('app_lang') !==null){
$lang = session('app_lang');
app()->setLocale($lang);
}
@endphp


 <div class="content-wrapper">
        <div class="container-full">
            <section class="content">
                @if(isset($restaurant))
                <h3 style="margin-left: 10px">{!! $restaurant->name !!}</h3>
                @endif
                  <form id="restaurant-form" method="POST" action="{!! env('APP_URL') !!}restaurant/save"
              enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{!! isset($restaurant)?$restaurant->id:'' !!}"/>
            <div class="row">
                <div class="col-xl-9">
                    <div class="card mb-4">
                        
                        <div class="card-body">
                            @if(\Illuminate\Support\Facades\Auth::user()->role=="restaurant")
                            <div class="row mb-4">
                                <div class="col-sm-12">
                                    <p style="font-size: 14px">{{__('label.cover_image')}}</p>
                                <div id="image-preview" @if(isset($restaurant) && isset($restaurant->home_images) && !empty($restaurant->home_images->file_name)) style="background: url({!! $restaurant->home_images->file_name !!})" @endif>
                                    <label for="image-upload" id="image-label">Choose File</label>
                                    <input type="file" name="home_image" id="image-upload" />
                                </div>
                                </div>
                            </div>
                            @endif


                            <div class="row">
                                <div class="col-sm-4 col-md-6">
                                    <div class="form-group">
                                        <label>{{__('label.name_english')}}</label>
                                        <input type="text" class="form-control" placeholder="" name="name"
                                               value="{!! isset($restaurant)?$restaurant->name:'' !!}" required>
                                    </div>
                                </div>
                            </div>
                             <div class="row">

                                <div class="col-sm-4 col-md-6">
                                    <div class="form-group">
                                        <label>{{__('label.name_arabic')}}</label>
                                        <input type="text" class="form-control" placeholder="" name="arabic_name"
                                               value="{!! isset($restaurant)?$restaurant->arabic_name:'' !!}" required>
                                    </div>
                                </div>
                            </div>

                        
                                 @if(\Illuminate\Support\Facades\Auth::user()->role=="restaurant")
<!--
								 <div class="row">
									 <div class="col-sm-4 col-md-6">
										<div class="form-group">
											<label>Short description</label>
											<textarea class="form-control" placeholder=""
													  name="short_description">{!! isset($restaurant)?$restaurant->short_description:'' !!}</textarea>
										</div>
									</div>
								</div>
-->
							<div class="row">
                                <div class="col-sm-4 col-md-6">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea class="form-control" placeholder="" name="address"
                                                  required>{!! isset($restaurant)?$restaurant->address:'' !!}</textarea>
                                    </div>
                                </div>

                          
                            </div>
                            @endif

                           
                            <div class="row">
                                 @if(\Illuminate\Support\Facades\Auth::user()->role=="restaurant")
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>Email Notification</label>
                                        <input type="text" class="form-control" placeholder="" name="notification_email"
                                               value="{!! isset($restaurant)?$restaurant->notification_email:'' !!}">
                                    </div>
                                </div>
                                <!-- <div class="col-sm-3 col-md-3">
                                    <div class="form-group">
                                        <label>Phone number</label>
                                        <input type="text" class="form-control" placeholder="" name="phone_number"
                                               required value="{!! isset($restaurant)?$restaurant->phone_number:'' !!}">
                                    </div>
                                </div> -->
                                @endif
                           
                                 @if(\Illuminate\Support\Facades\Auth::user()->role=="restaurant")
                                    @if(isset($restaurant) && $restaurant->allow_whatsapp_notifications=="1")
                                    <div class="col-sm-3 col-md-3">
                                        <div class="form-group">
                                            <label>{{__('label.whatsapp_number_for_order_notification')}}</label>
                                            <input type="text" class="form-control" placeholder="" name="whatsapp_number_notification"
                                                   required
                                                   value="{!! isset($restaurant)?$restaurant->whatsapp_number_notification:'' !!}">
                                           <!--  <p class="text-danger">In order to receive notifications on whatsapp kindly register here from mobile  , <a href="https://meemapp.net/dmenu/order/meemnotif" target="_blank">https://meemapp.net/dmenu/order/meemnotif</a></p> -->
                                        </div>
                                    </div>
                                        @endif
                                    @endif
                            </div>
                            
                            @php
                                $countries = \App\Countries::whereNull('deleted_at')->get();
                              

                                $outlet_countries = !empty($restaurant->outlet_countries)?explode(',',$restaurant->outlet_countries):[];
                                

                            @endphp
                             @if(\Illuminate\Support\Facades\Auth::user()->role=="administrator")
                            <div class="row">
                                <div class="col-sm-3 col-md-3">
                                    <div class="form-group">
                                        <label>{{__('label.country')}}</label>
                                        <select class="form-select" required name="country_id">
                                            <option value="">Select Country</option>
                                            @if(isset($countries) && $countries->count() > 0)
                                                @foreach($countries as $country)
                                                <option value="{!! $country->id !!}" @if(isset($restaurant) && $restaurant->country_id==$country->id) selected @endif>{!! $country->country_name !!}</option>
                                                @endforeach
                                            @endif


                                        </select>
                                    </div>
                                </div>
                                 <div class="col-sm-9 col-md-9">
                                    <div class="form-group">
                                        <label>{{__('label.outlet_countries')}}</label>
                                       <select class="form-select outlet_countries" required name="outlet_countries[]" multiple>
                                           
                                            @if(isset($countries) && $countries->count() > 0)
                                                @foreach($countries as $country)
                                                <option value="{!! $country->country_long_name !!}" @if(in_array($country->country_long_name,$outlet_countries)) selected @endif>{!! $country->country_long_name !!}</option>
                                                @endforeach
                                            @endif
                                       </select>
                                    </div>
                                </div>
                               <!--  <div class="col-sm-3 col-md-3">
                                    <div class="form-group">
                                        <label>Place</label>
                                        <select class="form-select" required name="place">
                                            <option value="">Select place</option>


                                        </select>
                                    </div>
                                </div> -->
                            </div>
                            @endif
							@if(\Illuminate\Support\Facades\Auth::user()->role=="administrator")
							<div class="row">
                                <div class="col-sm-3 col-md-3">
                                    <div class="form-group">
                                        <label>{{__('label.country')}}</label>
                                        <select class="form-select" required name="default_lang">
                                            <option value="">{{__('label.default_language')}}</option>
                                            <option value="ar" @if(isset($restaurant) && $restaurant->default_lang=='ar') selected @endif>Arabic</option>
											<option value="en"  @if(isset($restaurant) && $restaurant->default_lang=='en') selected @endif>English</option>


                                        </select>
                                    </div>
                                </div>
							</div>
							@endif
                             @if(\Illuminate\Support\Facades\Auth::user()->role=="restaurant")
                            <div class="row">
                               
                                
								<div class="col-sm-2 col-md-2">
                                    <div class="form-group">
                                        <label>{{__('label.default_color_for_order')}}</label><br />
                                        <input type="text" class="form-control" placeholder="" value="{!! isset($restaurant)?$restaurant->default_color:'' !!}"  name="default_color" id="color">
                                    </div>
                                </div>
                        
                                <div class="col-sm-12 col-md-6">
                                   
                                    <select name="time_zone" class="form-control" required>
                                        <option value="">{{__('label.choose_timezone')}}</option>
                                        @if(isset($time_zones) && $time_zones->count() > 0)
                                            @foreach($time_zones as $timezone)
                                            <option value="{!! $timezone->timezone !!}" @if(isset($restaurant) && $restaurant->time_zone==$timezone->timezone) selected @endif>{!! $timezone->timezone !!}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            @endif
                                        @php
                                        $resto_metas = \App\RestoMetaDefs::where('parent_meta_def_id',0)->get();
							$colors = [];
                                        //dump($resto_metas);
                                        $existing_resto_meta = [];
                                        if(isset($restaurant))
                                        $existing_resto_meta = \App\RestoMetas::where('bussiness_id',$restaurant->id)->whereNull('outlet_id')->pluck('meta_def_id')->toArray();

                                        $existing_resto_meta = isset($existing_resto_meta )?$existing_resto_meta:[];
                                        $existing_resto_meta_value = null;
                                         if(isset($restaurant))
                                        $existing_resto_meta_value = \App\RestoMetas::where('bussiness_id',$restaurant->id)->whereNull('outlet_id')->get();
                                        $v = [];
                                        if(isset($existing_resto_meta_value) && $existing_resto_meta_value->count() > 0){
                                            foreach($existing_resto_meta_value as $value){
                                                    $v[$value->meta_def_id] = $value->meta_val;
                                            }
                                        }

                                        
	

                                        @endphp
						

                                        @if(isset($resto_metas) && $resto_metas->count() > 0)
                                        @foreach($resto_metas as $meta)
											
                                        @if($meta->for_role==\Illuminate\Support\Facades\Auth::user()->role)
                                        @if(($meta->meta_def_name=="DISPLAY_TAX_INFO" || $meta->meta_def_name=="TERM_AND_CONDITIONS") && $restaurant->countries->country_name=="Iraq" ) @continue  @endif
                                       
                                        
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
										@if($meta->input_type!="colors")
                                        <label>{!! str_replace('_',' ',$meta->meta_def_name) !!}</label>
										@endif

                                        @if(isset($meta->childern) && $meta->childern->count() > 0 && $meta->input_type!="colors")
										
                                        <select class="form-control" name="resto_meta[]" @if($meta->is_required=="Yes") required @endif>
                                                <option value="">Choose {!! ucwords(strtolower(str_replace('_',' ',$meta->meta_def_name))) !!}</option> 
                                            @foreach($meta->childern as $childern)
											
                                            <option  value="{!! $childern->meta_def_id !!}" @if(in_array($childern->meta_def_id,$existing_resto_meta)) selected @endif>{!! $childern->meta_def_name !!}</option>
											
                                            @endforeach
                                        </select>
                                        @else
										@if($meta->input_type!="colors")
                                            <input type="hidden" name="resto_meta[]" value="{!! $meta->meta_def_id !!}">
										@endif
                                            @if($meta->input_type=="text-field")
                                            <input type="text" class="form-control" name="resto_meta_value[{!! $meta->meta_def_id !!}]" @if(isset($restaurant) && isset($v[$meta->meta_def_id])) value="{!! $v[$meta->meta_def_id] !!}" @endif  @if($meta->is_required=="Yes") required @endif placeholder="{!! $meta->meta_def_desc !!}">
										    @endif
										
										 	@if($meta->input_type=="colors")
										<div class="row">
												@if(isset($meta->childern) && $meta->childern->count() > 0)
														@foreach($meta->childern as $childern)
											<div class="col-sm-12 col-md-4">
										  			<label>{!! str_replace('_',' ',$childern->meta_def_name) !!}</label> <br />
													<input type="hidden" name="resto_meta[]" value="{!! $childern->meta_def_id !!}">
                                            		<input type="text" class="form-control choose-colors" name="resto_meta_value[{!! $childern->meta_def_id !!}]" @if(isset($restaurant) && isset($v[$childern->meta_def_id])) value="{!! $v[$childern->meta_def_id] !!}" @endif  @if($childern->is_required=="Yes") required @endif placeholder="{!! str_replace('_',' ',$childern->meta_def_name) !!}">
												</div>
													@endforeach
												@endif
											</div>
										    @endif
										
										@if($meta->input_type=="text-area")
										<textarea style="height:100px !important" class="form-control" placeholder="{!! $meta->meta_def_desc !!}" name="resto_meta_value[{!! $meta->meta_def_id !!}]"  @if($meta->is_required=="Yes") required @endif>{!! isset($v[$meta->meta_def_id])?$v[$meta->meta_def_id]:"" !!}</textarea>
                                           
										@endif
                                        @endif    
                                </div>
                                </div>
                            </div>

                           

                            @endif
                            @endforeach
                            @endif 
							
							@php
								
							@endphp
                         
                        </div>
                    </div>
                   
                    


                    <div class="card card-body mb-4">
                         @if(\Illuminate\Support\Facades\Auth::user()->role=="restaurant")
                        <div class="row">
                            <div class="col-sm-4 col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.logo')}}</label>
                                    <input type="file" class="form-control" placeholder=""
                                           accept="image/x-png,image/jpeg" name="logo">
                                    <span class="text-danger m-1">{{__('label.note_please_use_jpg_or_png_images')}}</span>
                                </div>
                                @if(isset($restaurant) && isset($restaurant->photos))
                                    <div class="col-2">
                                        <img src="{!! $restaurant->photos->file_name !!}"
                                             class="img-fluid mb-2" alt="{!! $restaurant->name !!}">
                                    </div>
                                @endif
                            </div>

                            
                        </div>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->role=="restaurant")
                        @if(isset($restaurant))
                            <div class="row mb-4">
                              
                                <div class="col-md-3">

                                    <div class="text-center">
                                       
                                        <p class="mt-4"><a href="{!! env('QRCODE_HOST_ORDER') !!}d/{!! $restaurant->resto_unique_name !!}" target="_blank">{!! env('QRCODE_HOST_ORDER') !!}d/{!! $restaurant->resto_unique_name !!}</a></p>
                                    </div>

                                </div>
                            </div>
                        @endif
                        @endif
                        <div class="row">
                            <div class="col-sm-4 col-md-6">
                               

                                @if(\Illuminate\Support\Facades\Auth::User()->role=="administrator")
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" id="active"
                                                   @if(isset($restaurant) && $restaurant->active=="1") checked
                                                   @endif  name="active" type="checkbox"/>
                                            <label class="custom-control-label" for="active">Active</label>
                                        </div>
                                    </div>
                                @endif

                                @if(\Illuminate\Support\Facades\Auth::User()->role=="administrator")
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" id="allow_whatsapp_notifications"
                                                   @if(isset($restaurant) && $restaurant->allow_whatsapp_notifications=="1") checked
                                                   @endif  name="allow_whatsapp_notifications" type="checkbox"/>
                                            <label class="custom-control-label" for="allow_whatsapp_notifications">{{__('label.allow_whatsapp_notifications')}}</label>
                                        </div>
                                    </div>
                                @endif


                            </div>
							@if(isset($restaurant))
							 <div class="col-md-3">

                                    <div class="text-center">
                                        <p>{{__('label.order_qr_code')}}</p>
                                        <div id="output-order"></div>
                                        <div id="download-order" style="display: none"></div>

                                        <a style="position: relative; top: 13px; font-size: 13px" href="#!"
                                           class="download-image-order mt-4"><i style="font-size: 20px"
                                                                          class="fa fa-download"></i> </a>
																		  
                                        <p class="mt-4"><a href="{!! env('QRCODE_HOST_ORDER') !!}d/{!! $restaurant->resto_unique_name !!}" 
										target="_blank">{!! env('QRCODE_HOST_ORDER') !!}d/{!! $restaurant->resto_unique_name !!}</a></p>
										
										
                                    </div>

                                </div>
							@endif
                        </div>
                    </div>
                   
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card card-body mb-4">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <a href="#!" class="btn btn-primary save"><i class="feather-save mr-1"></i> {{__('label.save')}}</a>
                                      <!--   @if( isset($restaurant))
                                            <a href="#!" class="btn btn-primary upload-gallery"><i
                                                        class="feather-image mr-1"></i> Upload Gallery</a>
                                        @endif -->

                                      
                                    </div>
                                </div>


                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="alert alert-success success"></div>
                                        <div class="alert alert-danger error"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>
            </div>

        </form>
            </section>
        </div>
    </div>



    



    @if( isset($restaurant))
        <div class="modal" id="upload-gallery" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('label.resto_gallery')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>{{__('label.gallery')}}</label>
                                    <div class="dropzone dz-clickable" id="gallery">
                                        <div class="dz-default dz-message" data-dz-message="">
                                            <span>{{__('label.drop_files_here_to_upload')}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary upload">{{__('label.upload')}}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('label.close')}}</button>
                    </div>
                </div>
            </div>
        </div>

    @endif
@endsection

@section('js')
  

    <script src="{!! env('APP_ASSETS') !!}vendor_components/spectrum/spectrum.js"></script>
    <script src="{!! env('APP_ASSETS') !!}vendor_components/select2/dist/js/select2.min.js"></script>
<script src="{!! env('APP_ASSETS') !!}js/jquery.qrcode.min.js"></script>
    <script src="{!! env('APP_ASSETS') !!}/vendor_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
    <script>
       
        $(function () {
           
$("select[name=time_zone]").select2();
$(".outlet_countries").select2();
            $("#color").spectrum({
                preferredFormat: "hex",
                color: "{!! isset($restaurant->default_color)?$restaurant->default_color:"#F00" !!}",
                showInput: true,
            });
			
			$(".choose-colors").spectrum({
                preferredFormat: "hex",
               
                showInput: true,
            });

             $.uploadPreview({
                input_field: "#image-upload",   // Default: .image-upload
                preview_box: "#image-preview",  // Default: .image-preview
                label_field: "#image-label",    // Default: .image-label
                label_default: "Choose File",   // Default: Choose File
                label_selected: "Change File",  // Default: Change File
                no_label: true    ,
                success_callback: function() {
                  
                }// Default: false
            });

            $("body").on("click",".delete-delivery-fee",function () {
                var id = $(this).data('id');

                var _this = $(this);

                $.ajax({
                    url:"{!! env('APP_URL') !!}delete/save/delivery/fee/"+id,
                    success:function () {
                        //_this.parents('tr').remove();
                        location.reload();
                    }
                });
            });

            $("body").on("change","select[name=country_id]",function () {
                var id = $(this).val();
                $("select[name=city_id]").html('');
                $.ajax({
                    url:"{!! env('APP_URL') !!}get/city/by/country/"+id,
                    success:function (response) {
                        response = $.parseJSON(response);
                        var str = "";
                        $.each(response,function (i,v) {
                            str+='<option value="'+v.id+'">'+v.name+'</option>'
                        });
                        $("select[name=city]").append(str);
                        @if(isset($restaurant))
                        $( "select[name=city]" ).val("{!! $restaurant->city !!}");
                        @endif
                    }


                });
            });
            setTimeout(function () {
                $("body").on("change","select[name=city]",function () {
                    var id = $(this).val();
                    $("select[name=place]").html('');
                    $.ajax({
                        url:"{!! env('APP_URL') !!}get/place/by/city/"+id, 
                        success:function (response) {
                            response = $.parseJSON(response);
                            var str = '<option value="">{{__("label.choose_place")}}</option>';
                            $.each(response,function (i,v) {
                                str+='<option value="'+v.id+'">'+v.place_name+'</option>'
                            });
                            $("select[name=place]").append(str);
                            @if(isset($restaurant))
                            $( "select[name=place]" ).val("{!! $restaurant->place !!}");
                            @endif
                        }


                    });
                });
            },1000);



            @if(isset($restaurant))
				
				$('#output-order').qrcode({
                render: "canvas",
                text: "{!! env('QRCODE_HOST_ORDER') !!}d/{!! $restaurant->resto_unique_name !!}",
                width: 200,
                height: 200,
                background: "#ffffff",
                foreground: "#000000",
                src: "{!! isset($restaurant) && isset($restaurant->photos)?env('APP_URL').'public/uploads/logo/'.$restaurant->photos->file_name:env('APP_URL').'public/layout/img/favicon.png' !!}",
                imgWidth: 50,
                imgHeight: 50
            });

            $('#download-order').qrcode({
                render: "canvas",
                text: "{!! env('QRCODE_HOST_ORDER') !!}d/{!! $restaurant->resto_unique_name !!}",
                width: 2000,
                height: 2000,
                background: "#ffffff",
                foreground: "#000000",
                src: "{!! isset($restaurant) && isset($restaurant->photos)?env('APP_URL').'public/uploads/logo/'.$restaurant->photos->file_name:env('APP_URL').'public/layout/img/favicon.png' !!}",
                imgWidth: 500,
                imgHeight: 500
            });


            $(".download-image-order").click(function () {
                var canvas = $('#download-order canvas')[0];
                var _this = $(this);
// Change here
                $.ajax({
                    url: "{!! env('APP_URL') !!}download/qrcode",
                    type: "POST",
                    data: {
						 resto:"{!! $restaurant->resto_unique_name !!}-{!! $restaurant->id !!}",
                        data: canvas.toDataURL(),
                        '_token': "{!! csrf_token() !!}"
                    },
                    success: function (response) {
                        console.log(response);
                        var link = document.createElement('a');
                        link.href = response;
                        link.download = "{!! $restaurant->resto_unique_name !!}-qrcode.png";
                        link.click();
                    }
                });
            });
				
            $( "select[name=country_id]" ).trigger( "change" );
            setTimeout(function () {
                $("select[name=city]").trigger("change");
            },1000);



            $("body").on("click", ".map-category-resto", function () {

                var categories = $(".default-category");
                var price_array = [];
                var category_array = [];
                var price = $(".default-price");
                $.each(categories, function () {
                    category_array.push($(this).val());
                });

                $.each(price, function () {
                    price_array.push($(this).val());
                });

                $.ajax({
                    url: "{!! env('APP_URL') !!}map/category/resto",
                    type: "POST",
                    data: {
                        price: price_array,
                        categories: category_array,
                        resto_id: "{!! $restaurant->id !!}",
                        "_token": "{!! csrf_token() !!}"
                    },
                    success: function (response) {

                    }
                });

            });
            @endif
            $("#choose-categories").select2().on("change", function () {
                var data = ($('#choose-categories').select2('data'));


                if (data) {
                    var str = "";
                    $.each(data, function (i, v) {
                        str += "<tr>";
                        str += "<td>" + v.text + "</td>";
                        str += '<td><input type="hidden" name="category[]" class="default-category" value="' + v.id + '" /><input type="text" class="form-control default-price" name="price[]" />  </td>';
                        str += "</tr>"

                    });

                    $("#table-default-price tbody").html(str);
                }
            });
            $("body").on("click", ".edit-fee", function () {
                var id = $(this).data('id');
            });
            $("body").on("click", ".save-delivery-fee", function () {

                $(this).parents('.tab-pane').find("form").ajaxForm(function (response) {
                    response = $.parseJSON(response);
                    if (response) {
                        if (response.type == "success") {
                            $('#delivery-fee-modal .alert.success').html(response.message);
                            $('#delivery-fee-modal .alert.success').show();

                            setTimeout(function () {

                                location.reload();

                            }, 2000)
                        } else {
                            $('#special-offers .alert.error').html(response.message);
                            $('#special-offers .alert.error').show();
                        }
                    }
                }).submit();


            });

            $("body").on("click", ".add-delivery-fee", function () {
                $("#delivery-fee-modal").modal();
            });

            $("body").on("change", ".country_id", function () {
                var _this = $(this);
                var code = $(this).val();
                _this.parents('.delivery-fee-item').find('.city').html('');

                $.ajax({
                    url: "{!! env('APP_URL') !!}get/cities/by/country/" + code,
                    success: function (response) {
                        response = $.parseJSON(response);
                        var str = '<option value="">{{__("label.choose_city")}}</option>';
                        _this.parents('.delivery-fee-item').find(".city").html(str);
                        if (response) {

                            $.each(response, function (i, v) {
                                str += '<option value="' + v.city + '">' + v.city + '</option>';
                            });

                            _this.parents('.delivery-fee-item').find(".city").html(str);
                        }

                    }
                });
            });

            $("body").on("change", ".city", function () {
                var name = $(this).val();

                var _this = $(this);
                


                $.ajax({
                    url: "{!! env('APP_URL') !!}get/places/by/city/" + name,
                    success: function (response) {
                        response = $.parseJSON(response);
                        var str = '<option value="">{{__("label.choose_place")}}</option>';
                        _this.parents('.delivery-fee-item').find(".place").html(str);
                        if (response) {

                            $.each(response, function (i, v) {
                                str += '<option value="' + v.city_unique_id + '" data-category="' + v.category + '">' + v.city_name + '</option>';
                            });

                            _this.parents('.delivery-fee-item').find(".place").html(str);
                        }

                    }
                });
            });

            

            

            

           


          

           

            $("body").on("change", ".make_active", function () {
                var value = $(this).val();
                var resto_id = $(this).data('resto-id');

                if ($(this).is(":checked")) {
                    $.ajax({
                        url: "{!! env('APP_URL') !!}offer/make/active",
                        type: "POST",
                        data: {
                            id: value,
                            resto_id: resto_id,
                            "_token": "{!! csrf_token() !!}"
                        },
                        success: function (response) {

                        }
                    });
                }
            });

            
            


           

            $("body").on('click', '.save', function () {
                $(".alert").hide();
                if ($("#restaurant-form").valid()) {
                    $("#restaurant-form").ajaxForm(function (response) {
                        response = $.parseJSON(response);
                        if (response) {
                            if (response.type == "success") {
                                $('#restaurant-form .alert.success').html(response.message);
                                $('#restaurant-form .alert.success').show();

                                setTimeout(function () {
                                    @if(\Illuminate\Support\Facades\Auth::user()->role=="administrator" )
                                        window.location = '{!! env('APP_URL') !!}businesses';
                                    @else
                                    location.reload();
                                    @endif
                                }, 2000)
                            } else {
                                $('#restaurant-form .alert.error').html(response.message);
                                $('#restaurant-form .alert.error').show();
                            }
                        }
                    }).submit();
                }
            });

         


        })
    </script>
@endsection