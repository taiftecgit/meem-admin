@extends('layouts.app')
@section('css')
<style>
	.card-header{
		padding:10px 15px; border-radius: 3px
	}
	.select2-container{width:100% !important}
</style>

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
                        <div class="card-header bg-warning"><h4 class="card-title">Busniess Information</h4></div>
                        <div class="card-body">
                            @if(\Illuminate\Support\Facades\Auth::user()->role=="restaurant")
                            <div class="row mb-4">
                                <div class="col-sm-12">
                                    <p style="font-size: 14px">Cover Image</p>
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
                                        <label style="font-weight: bold">Name (English)</label>
                                        <input type="text" class="form-control" placeholder="" name="name"
                                               value="{!! isset($restaurant)?$restaurant->name:'' !!}" required>
                                    </div>
                                </div>
								 <div class="col-sm-4 col-md-6">
                                    <div class="form-group">
                                        <label style="font-weight: bold">Name (Arabic)</label>
                                        <input type="text" class="form-control" placeholder="" name="arabic_name"
                                               value="{!! isset($restaurant)?$restaurant->arabic_name:'' !!}" required>
                                    </div>
                                </div>
                            </div>



                                 @if(\Illuminate\Support\Facades\Auth::user()->role=="restaurant")
                             <div class="row">
								 <div class="col-sm-4 col-md-6">
                                    <div class="form-group">
                                        <label style="font-weight: bold">Short description</label>
                                        <textarea class="form-control" placeholder=""
                                                  name="short_description">{!! isset($restaurant)?$restaurant->short_description:'' !!}</textarea>
                                    </div>
                                </div>

                                <div class="col-sm-4 col-md-6">
                                    <div class="form-group">
                                        <label style="font-weight: bold">Address</label>
                                        <textarea class="form-control" placeholder="" name="address"
                                                  required>{!! isset($restaurant)?$restaurant->address:'' !!}</textarea>
                                    </div>
                                </div>


                            </div>
                            @endif


                            <div class="row">
								<div class="col-sm-3 col-md-3">
                                    <div class="form-group">
                                        <label style="font-weight: bold">Default Language</label>
                                        <select class="form-select" required name="default_lang">
                                            <option value="">Default Language</option>
                                            <option value="ar" @if(isset($restaurant) && $restaurant->default_lang=='ar') selected @endif>Arabic</option>
											<option value="en"  @if(isset($restaurant) && $restaurant->default_lang=='en') selected @endif>English</option>


                                        </select>
                                    </div>
                                </div>
                              <div class="col-sm-12 col-md-6">
									@php
                               			$multiple_langs = !empty($restaurant->multiple_langs)?explode(',',$restaurant->multiple_langs):[];
								   $countries = \App\Models\Countries::whereNull('deleted_at')->get();


                                $outlet_countries = !empty($restaurant->outlet_countries)?explode(',',$restaurant->outlet_countries):[];
                                	@endphp
									<label style="font-weight: bold">Choose Language(s)</label> <small><i>(Languages supported in Order Page)</i></small><br />
									<select class="form-control multiple_langs" multiple required name="multiple_langs[]">

										<option @if(isset($restaurant) && in_array('en',$multiple_langs))) selected @endif value="en">English</option>
										<option @if(isset($restaurant) && in_array('ar',$multiple_langs))) selected @endif value="ar">Arabic</option>
									</select>
								</div>
								<div class="col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label style="font-weight: bold">Country</label>
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


                            </div>

                            @php



                            @endphp

							@if(\Illuminate\Support\Facades\Auth::user()->role=="administrator")
							<div class="row">
								<div class="col-sm-6 col-md-6">
									<div class="form-group">
                                        <label style="font-weight: bold">Domain Name (if have)</label>
                                        <input placeholder="https://o.****.***/" type="text" class="form-control" placeholder="" name="domain_name"
                                               value="{!! isset($restaurant)?$restaurant->domain_name:'' !!}">
                                    </div>
								</div>
							</div>
							<div class="row">

								<div class="col-sm-3 col-md-3">


                                @if(\Illuminate\Support\Facades\Auth::User()->role=="administrator")
                                    <div class="form-group">
										 <label style="visibility: hidden; margin-bottom: 15px">Default Color for Order</label><br />
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" id="active"
                                                   @if(isset($restaurant) && $restaurant->active=="1") checked
                                                   @endif  name="active" type="checkbox"/>
                                            <label class="custom-control-label" for="active">Active</label>
                                        </div>
                                    </div>
                                @endif
								</div>
								<div class="col-sm-3 col-md-3">

                                @if(\Illuminate\Support\Facades\Auth::User()->role=="administrator")
                                    <div class="form-group">
										 <label style="visibility: hidden; margin-bottom: 15px">Default Color for Order</label><br />
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" id="allow_whatsapp_notifications"
                                                   @if(isset($restaurant) && $restaurant->allow_whatsapp_notifications=="1") checked
                                                   @endif  name="allow_whatsapp_notifications" type="checkbox"/>
                                            <label class="custom-control-label" for="allow_whatsapp_notifications">Allow whatsapp notifications (Marchent)</label>
                                        </div>
                                    </div>


                                @endif


								</div>
                                <div class="col-sm-3 col-md-3">

                                    @if(\Illuminate\Support\Facades\Auth::User()->role=="administrator")
                                        <div class="form-group">
                                            <label style="visibility: hidden; margin-bottom: 15px">Default Color for Order</label><br />
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" id="allow_whatsapp_notifications_to_customera"
                                                       @if(isset($restaurant) && $restaurant->allow_whatsapp_notifications_to_customera=="Yes") checked
                                                       @endif  name="allow_whatsapp_notifications_to_customera" type="checkbox"/>
                                                <label class="custom-control-label" for="allow_whatsapp_notifications_to_customera">Allow Whatsapp notifications (Customers)</label>
                                            </div>
                                        </div>


                                    @endif
                                </div>
                                <div class="col-sm-3 col-md-3">

                                    @if(\Illuminate\Support\Facades\Auth::User()->role=="administrator")
                                        <div class="form-group">
                                            <label style="visibility: hidden; margin-bottom: 15px">Default Color for Order</label><br />
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" id="allow_email_notifications"
                                                       @if(isset($restaurant) && $restaurant->allow_email_notifications=="Yes") checked
                                                       @endif  name="allow_email_notifications" type="checkbox"/>
                                                <label class="custom-control-label" for="allow_email_notifications">Allow Email notifications</label>
                                            </div>
                                        </div>


                                    @endif
                                </div>

							</div>
							@endif



						</div>


                        </div>

					<div class="card mb-4">
						<div class="card-header bg-warning"><h4 class="card-title">Busniess Meta</h4></div>
						<div class="card-body">
							  <div class="row">
							@php
                                        $resto_metas = \App\Models\RestoMetaDefs::where('parent_meta_def_id',0)->get();
                                        //dump($resto_metas);
                                        $existing_resto_meta = [];
                                        if(isset($restaurant))
                                        $existing_resto_meta = \App\Models\RestoMetas::where('bussiness_id',$restaurant->id)->whereNull('outlet_id')->pluck('meta_def_id')->toArray();

                                        $existing_resto_meta = isset($existing_resto_meta )?$existing_resto_meta:[];
                                        $existing_resto_meta_value = null;
                                         if(isset($restaurant))
                                        $existing_resto_meta_value = \App\Models\RestoMetas::where('bussiness_id',$restaurant->id)->whereNull('outlet_id')->get();
                                        $v = [];
                                        if(isset($existing_resto_meta_value) && $existing_resto_meta_value->count() > 0){
                                            foreach($existing_resto_meta_value as $value){
                                                    $v[$value->meta_def_id] = $value->meta_val;
                                            }
                                        }


											$multiple_options = ['BILLING_GATEWAY'];

                                        @endphp

                                        @if(isset($resto_metas) && $resto_metas->count() > 0)
                                        @foreach($resto_metas as $meta)

                                        @if($meta->for_role==\Illuminate\Support\Facades\Auth::user()->role)
                                        @if(($meta->meta_def_name=="DISPLAY_TAX_INFO" || $meta->meta_def_name=="TERM_AND_CONDITIONS" ) && $restaurant->countries->country_name=="Iraq" ) @continue  @endif



                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label style="font-weight:;font-size:12px">{!! ucwords(str_replace('_',' ',$meta->meta_def_name)) !!}</label>

                                        @if(isset($meta->childern) && $meta->childern->count() > 0)

                                        <select @if(in_array($meta->meta_def_name, $multiple_options)) multiple rel="multiple-option" @endif class="form-control" name="resto_meta[]" @if($meta->is_required=="Yes") required @endif>
                                                <option value="">Choose {!! ucwords(strtolower(str_replace('_',' ',$meta->meta_def_name))) !!}</option>
                                            @foreach($meta->childern as $childern)

                                            <option  value="{!! $childern->meta_def_id !!}" @if(in_array($childern->meta_def_id,$existing_resto_meta)) selected @endif>{!! $childern->meta_def_name !!}</option>

                                            @endforeach
                                        </select>
                                        @else
                                            <input type="hidden" name="resto_meta[]" value="{!! $meta->meta_def_id !!}">
                                            @if($meta->input_type=="text-field")
                                            <input type="text" class="form-control" name="resto_meta_value[{!! $meta->meta_def_id !!}]" @if(isset($restaurant) && isset($v[$meta->meta_def_id])) value="{!! $v[$meta->meta_def_id] !!}" @endif  @if($meta->is_required=="Yes") required @endif placeholder="">
										@endif

										@if($meta->input_type=="text-area")
										<textarea style="height:40px !important" class="form-control" placeholder="{!! $meta->meta_def_desc !!}" name="resto_meta_value[{!! $meta->meta_def_id !!}]"  @if($meta->is_required=="Yes") required @endif>{!! isset($v[$meta->meta_def_id])?$v[$meta->meta_def_id]:"" !!}</textarea>

										@endif
                                        @endif
                                </div>
                                </div>




                            @endif
                            @endforeach
                            @endif
								  </div>
						</div>
					</div>








				</div>
				<div class="col-xl-3">
                    <div class="card mb-4">
						<div class="card-header bg-warning"><h4 class="card-title">Business QR Code</h4></div>
						<div class="card-body">
						<div class="row">
                            @if(isset($restaurant))
							 <div class="col-md-12">

                                    <div class="text-center">
                                        <p>Order Qr Code</p>
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
					</div>
					<div class="card  mb-4">
						 <div class="card-header bg-warning"><h4 class="card-title">Logo</h4></div>
						<div class="card-body">


                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label style="font-weight: bold">Logo</label>
                                    <input type="file" class="form-control" placeholder=""
                                           accept="image/x-png,image/jpeg" name="logo">
                                    <span class="text-danger m-1">Note* Please use jpg or png images</span>
                                </div>
                                @if(isset($restaurant) && isset($restaurant->photos))
                                    <div class="col-2">
                                        <img src="{!! $restaurant->photos->file_name !!}"
                                             class="img-fluid mb-2" alt="{!! $restaurant->name !!}">
                                    </div>
                                @endif
                            </div>


                        </div>

						</div>

                    </div>


                            <div class="card card-body mb-4">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <a href="#!" class="btn btn-primary save" style="width: 100% !important"><i class="feather-save mr-1"></i> Save</a>
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

        </form>
            </section>
        </div>
    </div>







    @if( isset($restaurant))
        <div class="modal" id="upload-gallery" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Resto Gallery</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label style="font-weight: bold">Gallery</label>
                                    <div class="dropzone dz-clickable" id="gallery">
                                        <div class="dz-default dz-message" data-dz-message="">
                                            <span>Drop files here to upload</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary upload">Upload</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
$(".multiple_langs").select2();
$(".outlet_countries").select2();
			$("select[rel=multiple-option]").select2();

            $("#color").spectrum({
                preferredFormat: "hex",
                color: "{!! isset($restaurant->default_color)?$restaurant->default_color:"#F00" !!}",
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
                            var str = '<option value="">Choose Place</option>';
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
                        var str = '<option value="">Choose City</option>';
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
                        var str = '<option value="">Choose place</option>';
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
