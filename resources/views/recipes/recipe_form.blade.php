@extends('layouts.app')
@section('css')
    <link href="{!! env('APP_ASSETS') !!}vendor_components/dropzone/dist/dropzone.css" rel="stylesheet">
<link href="{!! env('APP_ASSETS') !!}vendor_components/select2/dist/css/select2.min.css" rel="stylesheet">

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
          .alert{
            display: none;
        }
         .container-full,.content-wrapper{
            background-color: transparent !important;
        }
        .card-header{
            display: inline-block;
        }

        #image-preview {
            width: 768px;
            border-radius: 20px;
            /*height: 341px;*/
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
        #add-variations .modal-dialog{
            max-width: 70%;
        }
          .dynamic-column{
              min-width: 200px;
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
        .form-control, .form-select {
                    height: 46px ;
                    border-color: #E4E6EB !important;
                    border-radius: 7px !important;
                }
	.select2 {
		width: 100% !important;
		height: 46px;
		border-color: #E4E6EB !important;
    	border-radius: 7px !important;
		/*padding: 0.375rem 0.75rem !important;
		font-size: 1rem !important;
		font-weight: 400;
		line-height: 1.5;
		color: #212529;*/
	}
    textarea{
        height: 80px !important;
    }
	.select2-container--default .select2-selection--multiple{
		padding:6px;
	}

          .select2-container--default .select2-selection--single{
              padding:12px 5px;
              height: 46px;
          }

	@media only screen and (max-width:428px){
		#image-preview{
			width:100%;
		}
	}

	.modal-header {
        border-bottom-color: #ffab00;
        background-color: #ffab00;
    	color:white;
    }
    .ar-mrl-adjust{
        margin-left: 22px;
    }
    html[dir="rtl"] .ar-mrl-adjust{
        margin-right: 25px;
    }
    html[dir="rtl"] .page-top-title{
        padding: 7px 12px;
    }

    </style>
@php
    $restuarant1 = $resto;
        $resto_meta = isset($restuarant1->resto_metas)?$restuarant1->resto_metas:null;


         $resto_metas = [];
            $billing = [];
            if(isset($resto_meta)){
                foreach($resto_meta as $meta){
                  //  dump($meta->resto_meta_defs);
                   $index_name = isset($meta->resto_meta_defs->parents)?$meta->resto_meta_defs->parents->meta_def_name:$meta->resto_meta_defs->meta_def_name;
                   if(isset($_GET['debug'])){
                      //  dump($index_name );
                    }
                    if($meta->resto_meta_defs->meta_def_name=="BILLING_GATEWAY"){
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
            $allow_pre_order = isset($resto_metas['ALLOW_PRE_ORDERS'])?$resto_metas['ALLOW_PRE_ORDERS']:"No";

$business_type =  trim($business_type);






@endphp
 <div class="content-wrapper">
        <div class="container-full">
            <section class="content">
                <div class="row ">
                    <div class="col-md-10">
                        <div class="page-top-title">
                            <h3 class="title m-0">{{__('label.recipe')}}</h3>
                        </div>
                    </div>
                    <ol class="breadcrumb mb-4 ar-mrl-adjust">
                        <li class="breadcrumb-item"><a href="{!! env('APP_URL') !!}dashboard">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active">  @if(isset($recipe)) {{__('label.edit_recipe')}}  @else {{__('label.new_recipe')}}  @endif</li>
                    </ol>
                </div>
				
                <div class="row m-0">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fa fa-plus mr-1"></i>
                       @if(isset($recipe))
                            {{__('label.edit')}} {!! $recipe->name !!}
                            @else
                        {{__('label.new_item')}}
                            @endif
                    </div>
                    <div class="card-body">
                        <form id="restaurant-form" method="POST" action="{!! env('APP_URL') !!}recipe/save" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{!! isset($recipe)?$recipe->id:'' !!}" />
                            <input type="hidden" name="business_type" value="{!! $business_type !!}">


                            <div class="row mb-4 main-cover-image">
                                <div class="col-sm-12">
                                    <p style="font-size: 14px">{{__('label.cover_image')}}</p>
                                <div id="image-preview" class="ratio ratio-16x9" @if(isset($recipe) && isset($recipe->main_images) && !empty($recipe->main_images->file_name)) style="background: url({!! $recipe->main_images->file_name !!})" @endif>
                                    <label for="image-upload" id="image-label">{{__('label.choose_file')}}</label>
                                    <input type="file" name="main_image" id="image-upload" />
                                </div>
                                @if(isset($recipe) && isset($recipe->main_images) && !empty($recipe->main_images->file_name))
                                <a href="#!" class="text-center text-danger remove-image" data-recipe-id="{!! isset($recipe)?$recipe->id:'' !!}">{{__('label.remove_image')}}</a>
                                @endif
                                    <p class="text-warning mt-2">* image size 1920 X 1280 pixels , size 2.4 Mb</p>
                                </div>
                            </div>
                        <div class="row">
                            <div class="col-sm-4 col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.english_name')}}</label>
                                    <input type="text" class="form-control" placeholder="" name="name" value="{!! isset($recipe)?$recipe->name:'' !!}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4 col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.arabic_name')}}</label>
                                    <input type="text"  style="direction: rtl" class="form-control" placeholder="" name="arabic_name" value="{!! isset($recipe)?$recipe->arabic_name:'' !!}" required>
                                </div>
                            </div>
                        </div>
                            @php
                            $c = [];
                            if(isset($recipe)){
                            $c = $recipe->categories->pluck('category_id')->toArray();
                           // dump($c);

                            }
                            @endphp

                            <div class="row">
                            <div class="col-sm-4 col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.category')}}</label>
                                  <select class="custom-select" name="category[]" multiple>
                                      <option value="">{{__('label.select_category')}}</option>
                                      @if(isset($categories) && $categories->count() > 0)
                                        @foreach($categories as $category)
                                            <option value="{!! $category->id !!}" @if(isset($recipe) && in_array($category->id,$c)) selected @endif>{!! $category->name !!}</option>
                                            @endforeach
                                      @endif
                                  </select>
                                </div>
                            </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label>{{__('label.price')}}</label>
                                        <input type="number" class="form-control" placeholder="" name="price" value="{!! isset($recipe)?$recipe->price:'' !!}" required>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4" style="margin-top:2.2rem">
                                    <div class="form-group">
                                     <label style="visibility: hidden;">Price</label>
                                        @if(isset($recipe))
                                        <input type="checkbox" id="basic_checkbox_2" class="filled-in"  name="show_recipe_main_price"  @if($recipe->show_recipe_main_price==1) checked @endif>
                                        <label for="basic_checkbox_2">{{__('label.show_item_price')}}</label>
                                        @else
                                         <input type="checkbox" id="basic_checkbox_2" class="filled-in"  name="show_recipe_main_price">
                                        <label for="basic_checkbox_2">{{__('label.show_item_price')}}</label>
                                         @endif
                                    </div>
                                </div>
                        </div>



                        <div class="row">
                            <div class="col-sm-4 col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.english_description')}}</label>
                                    <textarea  class="form-control" placeholder="" name="short_description">{!! isset($recipe)?$recipe->short_description:'' !!}</textarea>
                                </div>
                            </div>
                        </div>

							 <div class="row">
                            <div class="col-sm-4 col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.arabic_description')}}</label>
                                    <textarea  class="form-control" style="direction: rtl" placeholder="" name="short_description_arabic">{!! isset($recipe)?$recipe->short_description_arabic:'' !!}</textarea>
                                </div>
                            </div>
                        </div>

                        @if($business_type=="ClothsStore")
                        @php
                            $sizes = [];
                            $colors = [];
							$color_data = null;
                            if(isset($recipe)){
                                $colors = \App\Models\ClothOptions::where('product_id',$recipe->id)->where('type','color')->pluck('name')->whereNull('deleted_at')->toArray();
							//dump($colors);
                            $sizes = \App\Models\ClothOptions::where('product_id',$recipe->id)->where('type','size')->pluck('name')->toArray();
							$color_data = \App\Models\ClothOptions::where('product_id',$recipe->id)->whereIn('type',['color','color_image'])->whereNull('deleted_at')->get();
                            }


                        @endphp


                        @endif



                            <div class="row">
                                <div class="col-sm-4 col-md-6">
                                   <div class="form-group">
                                        <label>{{__('label.gallery')}}</label>

                                    </div>

                                    @if(isset($recipe)  && isset($recipe->galleries))
                                        <div class="row mb-2">
                                            @foreach($recipe->galleries as $gallery)
                                                <div class="col-sm-2 gallery">
                                                    <div class="mb-1" style="width: 100px; height: 100px; background-image: url({!! $gallery->file_name !!}); background-position: center; background-size: contain">


                                                    </div>
                                                    <div class="text-center">
                                                        <a href="#!" class="delete-image text-danger" data-id="{!! $gallery->id !!}"><i class="glyphicon glyphicon-trash"></i> </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                    @endif
                                </div>
                            </div>

{{--                        @if($business_type!="ClothsStore")--}}
                            @if(1)

                         @if(isset($recipe->extra_options) && $recipe->extra_options->count() > 0)
                             <h3>{{__('label.extra_options')}}</h3>
                             <hr />
                                <div class="row">
                                    <div class="col-sm-6 col-md-8">
                             <table class="table table-bordered">
                                 <thead>
                                    <tr>
                                        <th>{{__('label.option_name')}}</th>
                                        <th>{{__('label.price')}}Price</th>
                                        <th>{{__('label.items')}}</th>
                                        <th>{{__('label.is_mandatory')}}?</th>
                                        <th></th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @foreach($recipe->extra_options as $option)
                                        <tr>
                                            <td>{!! $option->name !!} @if($option->name_arabic!="") ( {!! $option->name_arabic !!} ) @endif</td>
                                            <td>{!! $option->price !!}</td>
                                            <td>
                                             @if(isset($option->extra_option_items) && $option->extra_option_items->count() > 0)
                                                <a href="#!" class="badge badge-success view-items" data-id="{!! $option->id !!}" >{!! $option->extra_option_items->count() !!}</a>

                                              @endif
                                                 <a href="#!" class="badge badge-danger add-new-items"  data-id="{!! $option->id !!}"><i class="glyphicon glyphicon-plus"></i> </a>
                                            </td>
                                            <td>

                                                @if(isset($option->extra_option_items) && $option->extra_option_items->count() > 0)
                                         <!--        <input type="checkbox" class="is_mandatory" @if($option->is_mandatory==1) checked @endif value="" data-id="{!! $option->id !!}" /> -->
                                                <input type="checkbox" id="is_mandatory{!! $option->id !!}" class="filled-in is_mandatory"  name="is_mandatory" @if($option->is_mandatory==1) checked @endif value="" data-id="{!! $option->id !!}">
                                                <label for="is_mandatory{!! $option->id !!}">Mandatory Items</label>
                                                    @if($option->is_mandatory==1) User can select <span class="badge badge-success">{!! $option->mandatory_amount !!}</span> items must  @endif
                                                    @endif
                                            </td>
                                            <td>

                                                <a href="#!" class="btn btn-sm btn-primary edit-option" data-id="{!! $option->id !!}"  data-toggle="tooltip" data-placement="top" title="Edit"><i class="glyphicon glyphicon-edit"></i> </a>
                                                <a href="javascript:;" data-id="{!! $option->id !!}" class="btn btn-sm btn-danger delete-option"><i class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i></a>


                                            </td>
                                        </tr>
                                    @endforeach
                                 </tbody>
                             </table>
                                    </div>
                                </div>
                         @endif

                        @endif
                            @if(isset($recipe))


                                    <div class="row mb-5">
                                        <div class="col-sm-8">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="pull-left">{{__('label.variations')}}</h3>

                                                </div>
                                                <div class="card-body table-responsive">


                                                    @if(isset($recipe->variations) && $recipe->variations->count() > 0)
                                                        @php
                                                            $v = (json_decode($recipe->variations[0]->variations));

                                                            $exclude_columns = ['variant_type'];

                                                            $thead = [];

                                                            if(isset($v)){
                                                                 foreach($v as $k=>$value){
                                                                     if(!in_array($k,$exclude_columns))
                                                                        $thead[$k] =  ucwords(str_replace('_',' ',$k));


                                                                    }

                                                                 if(!in_array('image',$thead))
                                                                     $thead['image'] = 'Image';
                                                            }





                                                        @endphp
                                                        <table class="table table-striped" id="accordionExample">
                                                            <thead>
                                                                <tr>


                                                                        @if(isset($thead))
                                                                            @foreach($thead as $k=>$value)
                                                                                    <th>{!! $value !!}</th>
                                                                            @endforeach
                                                                        @endif


                                                                <th></th>

                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($recipe->variations as $variant)
                                                                <tr>
                                                                    @php
                                                                        $variants = (json_decode($variant->variations));
                                                                        $is_image = "No";
                                                                    @endphp

                                                                    @if(isset($variants))
                                                                        @foreach($variants as $k=>$value)

                                                                            @if(!in_array($k,$exclude_columns))
                                                                                @if($k=="image")
                                                                                    @php
                                                                                        $is_image = "Yes";
                                                                                    @endphp
                                                                                   <td><div style="width:50px; height: 50px; background-size: cover; background-position: center; background-image: url({!! $value !!})"></div></td>
                                                                                    @else
                                                                                    @if($k=="color")
                                                                                        <td><div style="width: 20px; height: 20px; background-color: {!! $value !!}; float: left; border-radius: 20px;"></div> </td>
                                                                                        @else
                                                                            <td>{!! $value !!}</td>
                                                                                        @endif
                                                                                    @endif
                                                                            @endif
                                                                        @endforeach
                                                                    @if($is_image=="No")
                                                                        <td></td>
                                                                        @endif
                                                                                    <td>
                                                                                        <a href="javascript:;" data-id="{!! $variant->id !!}" class="btn btn-sm btn-primary edit-variation"><i class="glyphicon glyphicon-pencil"></i></a>
                                                                                        <a href="javascript:;" data-id="{!! $variant->id !!}" class="btn btn-sm btn-danger delete-variation"><i class="glyphicon glyphicon-trash"></i></a>
                                                                                    </td>
                                                                    @endif
                                                                </tr>
                                                                @endforeach
                                                            </tbody>

                                                        </table>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                            @endif

							@if(isset($recipe))
							@if(isset($resto_metas['ENABLED_PRODUCT_FAQS']) && $resto_metas['ENABLED_PRODUCT_FAQS']=="Yes")

							<div class="row mb-5">
								<div class="col-sm-8">
									<div class="card">
										<div class="card-header">
										<h3 class="pull-left">{{__('label.product_faqs')}}</h3>
											<a href="#!" class="btn btn-sm btn-primary pull-right add-faq">{{__('label.add_faq')}}</a>
										</div>
										<div class="card-body">


									@if(isset($recipe->product_faqs) && $recipe->product_faqs->count() > 0)

									<div class="accordion" id="accordionExample">
										@foreach($recipe->product_faqs as $faq)

									  <div class="accordion-item">
										<h2 class="accordion-header" id="heading{!! $faq->id !!}">
										  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{!! $faq->id !!}" aria-expanded="true" aria-controls="collapse{!! $faq->id !!}">
											{!! $faq->question !!}
										  </button>
										</h2>
										<div id="collapse{!! $faq->id !!}" class="accordion-collapse collapse" aria-labelledby="heading{!! $faq->id !!}" data-bs-parent="#accordionExample">
										  <div class="accordion-body">
											<p>{!! nl2br($faq->answer) !!}</p>
											  <div class="text-end">
											  <a href="javascript:void(0)" data-id="{!! $faq->id !!}" class="edit-faq" style="margin: 0 3px 0 10px"><i class="glyphicon glyphicon-edit"></i></a>
												  |
												  <a href="javascript:void(0)" data-id="{!! $faq->id !!}" class="delete-faq" style="color:red"><i class="glyphicon glyphicon-trash"></i></a>
											  </div>
										  </div>
										</div>
									  </div>
										@endforeach


									</div>
									@endif
										</div>
									</div>

								</div>
							</div>
							@endif
							@endif



                        <div class="row">
                            <div class="col-sm-4 col-md-6">


{{-- @if($business_type!="ClothsStore")--}}
                                @if(1)
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" id="is_customized"  @if(isset($recipe) && $recipe->is_customized=="1")   checked @endif  name="is_customized" type="checkbox" />
                                        <label class="custom-control-label" for="is_customized">{{__('label.customizedable')}}</label>
                                    </div>
                                </div>
                                @endif
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" id="status" @if(isset($recipe) && $recipe->status=="1")   checked @endif name="status" type="checkbox" />
                                        <label class="custom-control-label" for="status">{{__('label.active')}}?</label>
                                    </div>
                                </div>
                                @if($allow_pre_order=="Yes")
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" id="allow_pre_order" @if(isset($recipe) && $recipe->allow_pre_order=="Yes")   checked @endif name="allow_pre_order" type="checkbox" />
                                        <label class="custom-control-label" for="allow_pre_order">{{__('label.allow_pre_order')}}?</label>
                                    </div>
                                </div>

                                    @endif

                            </div>
                        </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <a href="#!" class="btn btn-primary save">{{__('label.save')}}</a>
                                    @if( isset($recipe))
                                    <a href="#!" class="btn btn-primary upload-gallery">{{__('label.upload_gallery')}}</a>
                                        @if($business_type!="ClothsStore" )
                                        <a href="#!" class="btn btn-primary add-options">{{__('label.add_extra_options')}}</a>
                                        @endif
                                        <a href="#!" class="btn btn-primary add-variations">{{__('label.add_variations')}}</a>
                                    @endif
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="alert alert-success success"></div>
                                    <div class="alert alert-danger error"></div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
            </section>
        </div>
</div>









    @if( isset($recipe))



    <div class="modal" id="upload-gallery" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">{{__('label.recipe_gallery')}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Gallery</label>
                                <div class="dropzone dz-clickable" id="gallery">
                                    <div class="dz-default dz-message" data-dz-message="">
                                        <span>{{__('label.drop_files_here_to_upload')}} <br>
										<b>(accepted file formats .jpg,.png,.jpeg case sensitive)</b> </span>
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


     <div class="modal" id="extra-options" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">{{__('label.add_extra_options')}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="extra-options-form" method="POST" action="{!! env('APP_URL') !!}save/extra/options">
                            @csrf
                            <input type="hidden" name="recipe_id" value="{!! $recipe->id !!}" />
                                <input type="hidden" name="resto_id" value="{!! $recipe->resto_id !!}" />
                                                <div class="row">
                                                    <div class="col-sm-4">
													<b>{{__('label.english_name')}}: </b>
                                                        <input class="form-control" name="option" placeholder="Name of option" required />
                                                    </div>
													<div class="col-sm-4">
													<b>{{__('label.name_arabic')}}: </b>
                                                        <input class="form-control" name="arabic_option" placeholder="Name of option in arabic" required />
                                                    </div>
                                                    <div class="col-sm-3">
													<b>{{__('label.price')}} : </b>
                                                        <input class="form-control" name="price" placeholder="Price if it has" />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12 text-right"><a href="#!" class="btn btn-sm btn-danger mt-1 add-extra-items">{{__('label.add_extra_item')}}</a> </div>
                                                </div>

                                                <div id="items-list">

                                                </div>
                            </form>
      </div>
      <div class="modal-footer">
                        <button type="button" class="btn btn-primary save-extra-options">{{__('label.add')}}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('label.close')}}</button>
                    </div>
  </div>
</div>
</div>




 <div class="modal" id="edit-extra-option" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">{{__('label.edit_option')}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{!! env('APP_URL') !!}update/option" id="edit-form">
                        <input type="hidden" name="id" />
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
							<b>{{__('label.english_name')}} </b>
                                <input class="form-control" name="option" placeholder="Name of option" required />
                            </div>
							</div>
							<div class="row">
							<div class="col-sm-12">
							<b>{{__('label.name_arabic')}}</b>
                                <input class="form-control" name="arabic_option" placeholder="Name of option in arabic" />
                            </div>
							</div>
							<div class="row">
                            <div class="col-sm-12">
							<b>{{__('label.price')}} </b>
                                <input class="form-control" name="price" placeholder="Price" />
                            </div>
							</div>
							<div class="row">
                            <div class="col-sm-12">
							<br>
							<b></b>
                               <a href="#!" style="margin-top: 2px" class="btn btn-primary update-option">{{__('label.save')}} <i class="fa fa-save"></i> </a>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="alert alert-success success"></div>
                                <div class="alert alert-danger error"></div>
                            </div>
                        </div>
                    </form>
      </div>
	  <!--
					<div class="modal-footer">
                        <button type="button" class="btn btn-primary save-extra-options">Add</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
					-->
  </div>
</div>
</div>




<div class="modal" id="extra-option-item" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">{{__('label.customized_items_list')}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered" id="item-table">
                        <thead>
                        <tr>
                            <th>{{__('label.name')}}</th>
							<th>{{__('label.name_arabic')}}</th>
                            <th>{{__('label.price')}}</th>
                            <th>{{__('label.iItem_type')}}</th>
                            <th>{{__('label.customized_sub_items_list')}}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>

                    </table>
      </div>

  </div>
</div>
</div>




   <div class="modal" id="edit-extra-item-option" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel"><b>{{__('label.edit_item')}}</b> </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body modal-lg">
        <form method="POST" action="{!! env('APP_URL') !!}update/item" id="edit-item-form">
                        <input type="hidden" name="id" />
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
							<b>{{__('label.english_name')}}: </b>
                                <input class="form-control" name="option" placeholder="Name of Item" required />
                            </div>

						</div>
						<div class="row">
							<div class="col-sm-12">
							<b><br>{{__('label.name_arabic')}}: </b>
                            <input class="form-control" name="name_arabic" placeholder="Name of option in arabic" required />
							<br>
							</div>
						</div>
						<div class="row">
                            <div class="col-sm-12">
							<b>{{__('label.price')}}: </b>
                                <input class="form-control" name="price" placeholder="Price" />
                            </div>
						</div>
						<div class="row">
                            <div class="col-sm-12">
                                <br><select class="form-control" name="item_type">
                                    <option value="">{{__('label.choose_button_type')}}</option>
                                    <option value="option">{{__('label.option_button')}}</option>
                                    <option value="check-box">{{__('label.checkbox')}}</option>
                                </select>
                            </div>
							<br>
						</div>

						<div class="row">
                            <div class="col-sm-12">
                                <br><a href="#!" style="margin-top: 2px" class="btn btn-primary update-item">{{__('label.save')}} <i class="fa fa-save"></i> </a>
                            </div><br>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="alert alert-success success"></div>
                                <div class="alert alert-danger error"></div>
                            </div>
                        </div>
                    </form>
      </div>

  </div>
</div>
</div>


 <div class="modal" id="mandatory-extra-item-option" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">{{__('label.make_mandatory_options')}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{!! env('APP_URL') !!}update/mandatory/item" id="mandatory-item-form">
                        <input type="hidden" name="id" />
                        @csrf
                        <div class="row">
                            <div class="col-sm-10">
                                <input class="form-control" type="number" name="mandatory_amount" placeholder="Enter quantity of items" required />
                            </div>

                            <div class="col-sm-1">
                                <a href="#!" style="margin-top: 2px" class="btn btn-sm btn-success make-mandatory-item"><i class="fa fa-save"></i> </a>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="alert alert-success success"></div>
                                <div class="alert alert-danger error"></div>
                            </div>
                        </div>
                    </form>
      </div>

  </div>
</div>
</div>



 <div class="modal" id="add-new-items" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">{{__('label.add_more_items')}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <form id="extra-items-form" method="POST" action="{!! env('APP_URL') !!}save/add/items">
                        @csrf
                        <input type="hidden" name="extra_option_id"/>

                        <input type="hidden" name="parent_id" />

                        <div class="row">
                            <div class="col-sm-12 text-right"><a href="#!" class="btn btn-sm btn-danger mt-1 add-extra-items-2">{{__('label.add_extra_item')}}</a> </div>
                        </div>

                        <div id="items-list-2">
                            <div class="row" style="margin-top: 10px">
                                <div class="col-sm-3">
                                    <input class="form-control" name="item_name[]" placeholder="Name of item">
                                </div>
								<div class="col-sm-3">
                                    <input class="form-control" name="item_name_arabic[]" placeholder="Name of item in Arabic">
                                </div>
                                <div class="col-sm-3">
                                    <select class="form-control" name="item_type[]"><option value="option">{{__('label.option_button')}}</option><option value="check-box">{{__('label.checkbox')}}</option> </select>
                                </div>
                                <div class="col-sm-2">
                                    <input class="form-control" name="item_price[]" placeholder="Price if it has">
                                </div>

                            </div>
                        </div>
                    </form>
      </div>

      <div class="modal-footer">
                    <button type="button" class="btn btn-primary save-extra-items">{{__('label.add_items')}}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('label.close')}}</button>
                </div>

  </div>
</div>
</div>



<div class="modal" id="add-new-faq" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">{{__('label.add_faq')}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <form id="faq-form" method="POST" action="{!! env('APP_URL') !!}save/faq">
                        @csrf

						<input type="hidden" name="id" />
                        <input type="hidden" name="product_id" value="{!! $recipe->id !!}" />

		   						<div class="form-group">
                                    <label>{{__('label.question')}}</label>
<!--                                    <input type="text" name="question" required class="form-control" />-->
									<textarea row="2" style="height: 200px !important" name="question" required class="form-control"></textarea>
                                </div>

		   						<div class="form-group">
                                    <label>{{__('label.answer')}}</label>
                                    <textarea row="4" style="height: 200px !important" name="answer" required class="form-control"></textarea>
                                </div>




                    </form>
      </div>

      <div class="modal-footer">
                    <button type="button" class="btn btn-primary save-faq">{{__('label.add')}}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('label.close')}}</button>
                </div>

  </div>
</div>
</div>


    <div class="modal" id="add-variations" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{__('label.variations')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="variation-form" method="POST" action="{!! env('APP_URL') !!}save/variations">
                        @csrf

                        <input type="hidden" name="id" />
                        <input type="hidden" name="product_id" value="{!! $recipe->id !!}" />



                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Product Variations</label>
                                    <select id="variant_type" name="variant_type[]" multiple class="form-control">
                                        @if(isset($variant_types) && $variant_types->count() > 0)
                                            @foreach($variant_types as $variant_type)
                                                <option value="{!! $variant_type->id !!}">{!! $variant_type->name !!}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>



                            </div>
                            <div class="col-sm-12 text-end">
                                <a href="#!" class="btn btn-sm btn-primary add-more-variations">New Variation Type</a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12" id="variation-table">

                            </div>
                        </div>


                    </form>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="alert alert-success success"></div>
                            <div class="alert alert-danger error"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer text-end">
                    <button type="button" class="btn btn-primary save-variations">{{__('label.save_variations')}}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('label.close')}}</button>
                </div>

            </div>
        </div>
    </div>



    @endif
@endsection

@section('js')

    <script src="{!! env('APP_ASSETS') !!}vendor_components/dropzone/dist/min/dropzone.min.js"></script>
    <script src="{!! env('APP_ASSETS') !!}vendor_components/select2/dist/js/select2.min.js"></script>
    <script src="{!! env('APP_ASSETS') !!}vendor_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
    <script>
		var spinner = '<span class="spinner-border" role="status" aria-hidden="true"></span>';
       Dropzone.autoDiscover = false;
        var extra_option_id = 0;
		var _image_is_required = "required";
		var color_option = "color";
        var _selectBoxes = [];
		color_option=$("select[name=color_option]").val();
        $(function () {

            $("body").on("click",".add-variations",function(){
                $("#add-variations").modal('show');
            });

            $("body").on("click",".edit-variation",function(){
                var _id = $(this).data('id');

                $("#variation-form input[name=id]").val(_id);

                $.ajax({
                    url:"{!! env('APP_URL') !!}get/variation/"+_id,
                    success:function(response){
                        $("#variant_type").select2().val(response.variation_type).trigger('change');
                        var variations = (response.variations);

                        setTimeout(function(){
                            $("#variation-table table tr td").each(function(i,v){
                                console.log("td");
                                var _select_name = ($(v).find("select").attr('name'));
                                var _input_name = ($(v).find("input").attr('name'));
                                if(_select_name){
                                    _select_name = _select_name.replace('[]','');
                                    $("."+_select_name).select2().val(variations[_select_name]).trigger('change');
                                    //console.log("data: "+variations[_select_name]);
                                }
                                if(_input_name){
                                    _input_name = _input_name.replace('[]','');
                                    $("."+_input_name).val(variations[_input_name]);
                                    //console.log("data: "+variations[_input_name]);
                                }


                            });
                            $(".add-more-variant").hide();
                        },500);
                    }
                });

                $("#add-variations").modal('show');

            });

            $("body").on("click",".delete-variation",function(){
                var _id = $(this).data('id');
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
                                url:"{!! env('APP_URL') !!}delete/variation/"+_id,
                                success:function (response) {
                                  _this.parents('tr').remove();
                                }
                            });
                        }
                    });


            });

            $("body").on("click",".save-variations",function(){
                var _this = $(this);
                    if($("#variation-form").valid()){
                        _this.attr('disabled','disabled');
                        _this.html(progress_img);
                        _this.removeClass('btn-primary');
                        _this.addClass('btn-muted');
                        $("#variation-form").ajaxForm(function(response){
                            if(response.type=="success"){
                                $("#add-variations .success").html(response.message);
                                $("#add-variations .success").show();

                                setTimeout(function(){
                                    location.reload();
                                },1500);
                            }
                        }).submit();
                    }
            });

            $("body").on("click",".add-more-variant",function(){
               console.log(_selectBoxes.length);
                var _tr = "<tr>";

                $(_selectBoxes).each(function(i,v){

                    if(v.attributes){

                        _tr+="<td  class='dynamic-column'>";
                        var _className=v.name.toLowerCase().replaceAll(' ','_');
                        var _select = "<select required class='form-control "+_className+"' name='"+v.name.toLowerCase().replaceAll(' ','_')+"[]'>";
                        $(v.attributes).each(function(m,n){
                            if(v.name=="Color")
                                _select+="<option value='"+n.attribute_code+"'>"+n.attribute_name+"</option>";
                            else
                                _select+="<option value='"+n.attribute_name+"'>"+n.attribute_name+"</option>";
                        });
                        _select+="</select>";
                        _tr+=_select;
                        _tr+="</td>";
                    }

                });
                _tr+="<td><input type='number' class='form-control variation_price' required name='variation_price[]'> </td>";
                _tr+="<td><input type='number' class='form-control variation_quantity' required name='variation_quantity[]'> </td>";
                _tr+="<td><input type='number' class='form-control stock_alert' name='stock_alert[]'> </td>";
                _tr+="<td><input type='file' class='form-control' name='image[]'> </td>";
                _tr+="</tr>";

                $("#variation-table table tbody").append(_tr);
                $("#variation-table select").select2();
                $(".color").select2({
                    data: colors,
                    dropdownAutoWidth : true,
                    templateResult: color_template,
                    escapeMarkup: function(m) {
                        return m;
                    }
                });
            });


            $("#variant_type").select2().on('change',function(){
                var _value = $(this).val();

                $.ajax({
                    url:"{!! env('APP_URL') !!}get/variation/attributes",
                    type:"POST",
                    data:{
                        variations:_value,
                        "_token":"{!! csrf_token() !!}"
                    },
                    success:function(response){
                        _selectBoxes = [];
                        //#variation-table
                        var _table = "<table class='table table-bordered'>";
                        var _thead="<thead>";
                        var _tr = "<tr>";
                        var _tr_length = 1;
                        $(response).each(function(i,v){
                            _tr+="<th>"+v.name+"</th>";
                            _tr_length++;

                        });
                        _tr+="<th>Price</th><th>Quantity</th><th>Stock Alert</th><th>Image</th>";
                        _tr+="</tr>";
                        _thead+=_tr;
                        _thead+="</thead>";
                        _tr_length = _tr_length+4;
                        //DATA ROWS

                        var _tbody = "<tbody>";
                        var _tr = "<tr>";

                        $(response).each(function(i,v){

                            if(v.attributes){
                                _selectBoxes.push(v);
                                _tr+="<td class='dynamic-column'>";
                                var _className=v.name.toLowerCase().replaceAll(' ','_');
                                var _select = "<select required class='form-control "+_className+"' name='"+v.name.toLowerCase().replaceAll(' ','_')+"[]'>";
                                $(v.attributes).each(function(m,n){
                                    if(v.name=="Color")
                                        _select+="<option value='"+n.attribute_code+"'>"+n.attribute_name+"</option>";
                                    else
                                        _select+="<option value='"+n.attribute_name+"'>"+n.attribute_name+"</option>";
                                });
                                _select+="</select>";
                                _tr+=_select;
                                _tr+="</td>";
                            }

                        });
                        _tr+="<td><input type='number' class='form-control variation_price' required name='variation_price[]'> </td>";
                        _tr+="<td><input type='number' class='form-control variation_quantity' required name='variation_quantity[]'> </td>";
                        _tr+="<td><input type='number' class='form-control stock_alert' name='stock_alert[]'> </td>";
                        _tr+="<td><input type='file' class='form-control' name='image[]'> </td>";
                        _tr+="</tr>";
                        _tbody+=_tr;
                        _tbody+="</tbody>";

                        _table+=_thead;
                        _table+=_tbody;

                        var _tfoot="<tfoot><tr><td style='text-align: right' colspan='"+_tr_length+"'>";
                        _tfoot+='<a href="#!" class="btn btn-sm btn-primary add-more-variant">Add More</a>';
                        _tfoot+="</td></tr></tfoot>"

                        _table+=_tfoot;

                        _table+="</table>";
                        $("#variation-table").html(_table);
                        $("#variation-table select").select2();
                        $(".color").select2({
                            data: colors,
                            dropdownAutoWidth : true,
                            templateResult: color_template,
                            escapeMarkup: function(m) {
                                return m;
                            }
                        });
                    }
                });

            });;


			@if(isset($recipe))
			$("body").on("change","select[name=color_option]",function(){
				var _existing = "{!! $recipe->color_option !!}";
				var _this_value = $(this).val();

				if(_existing!=_this_value){
					alert("{{__('label.color_setting_will_be_reset')}}");
				}
				color_option = _this_value;
				if(_this_value=="color_image"){
					_image_is_required = "required";

				}else{
					_image_is_required = "";
					$(".color-image-file").remove();
				}


			});
			@endif

			$("body").on("click",".delete-color",function(){
				var _id = $(this).data('id');
				var _this = $(this);
				if(confirm("Do you want to delete?")){
					$.ajax({
						type:"POST",
						url:"{!! env('APP_URL') !!}delete/color-image",
						data:{
							"id":_id,
							"_token":"{!! csrf_token() !!}"
						},
						success:function(){
							_this.parents("tr").remove();
						}
					});
				}
			});

			$("body").on("click",".delete-faq",function(){
				var _id = $(this).data('id');
				//alert(_id);
				var _this = $(this);
				if(confirm("Do you want to delete?")){
					$.ajax({
						type:"POST",
						url:"{!! env('APP_URL') !!}delete/faq",
						data:{
							"id":_id,
							"_token":"{!! csrf_token() !!}"
						},
						success:function(){
							_this.parents(".accordion-item").remove();
						}
					});
				}

			});

			$("body").on("click",".edit-faq",function(){
				var _id = $(this).data('id');
				$("#faq-form input[name=id]").val(_id);
				var _content = $(this).parents(".accordion-body").find('p').text();
				var _question = $(this).parents(".accordion-item").find('.accordion-button').text();
				$("#faq-form textarea[name=question]").val($.trim(_question));
				$("#faq-form textarea[name=answer]").val(_content);
				$("#add-new-faq").modal('show');
			});

			$("body").on("click",".save-faq",function(){
				if($("#faq-form").valid()){
					$("#faq-form").ajaxForm(function(response){
					location.reload();
				}).submit();
				 }
			});

			$("body").on("click",".add-faq",function(){
				$("#add-new-faq").modal('show');
			});

             @if($business_type=="ClothsStore")
            var colors = [
            @foreach(\App\Models\Colors::groupBy('color_code')->get() as $color)
            {
                id:"{!! $color->color_code !!}",
                text:"{!! $color->color_name !!}",
                html:'<div class="row" style="margin:0"><div style="width: 20px; height: 20px; background-color: {!! $color->color_code !!}; float: left; border-radius: 20px;"></div><div style="float: right; width: 200px">{!! addslashes($color->color_name) !!}</div></div>',
            },
            @endforeach
            ];

                function color_template(colors) {
                    return colors.html;
                }
			function return_color_row(value){
				var str = "";
				$(value).each(function(i,v){
					str += '<tr>';
					str+='<td><div style="width: 20px; height: 20px; background-color: '+v+'; float: left; border-radius: 20px;"></div></td>';
					if(color_option=="color_image")
					str+='<td><input name="color_image['+v+']" type="file" class="form-control color-image-file" required />';
					str+='</tr>';
				});

				return str;

			}
            $("#color").select2({
                    data: colors,
                   templateResult: color_template,
                   escapeMarkup: function(m) {
                      return m;
                   }
            }).on('change',function(){
				var _value = $(this).val();
				var rows = return_color_row(_value);
				$("#color-table tbody").html(rows);
			});;




            $("#size").select2();
            @endif

            $("body").on("click",".remove-image",function(){
                var id = $(this).data('recipe-id');
                var _this = $(this);

                $.ajax({
                    url:"{!! env('APP_URL') !!}remove/recipe/main-image",
                    type:"POST",
                    data:{
                        id:id,
                        '_token':"{!! csrf_token() !!}"
                    },
                    success:function(){
                        _this.parents('.main-cover-image').find("#image-preview").removeAttr('style');
                    }
                });
            });

            $.uploadPreview({
                input_field: "#image-upload",   // Default: .image-upload
                preview_box: "#image-preview",  // Default: .image-preview
                label_field: "#image-label",    // Default: .image-label
                label_default: "Choose File",   // Default: Choose File
                label_selected: "Change File",  // Default: Change File
                no_label: true    ,
                success_callback: function(e) {


                }// Default: false
            });
            $("#image-upload").on("change",function(e){
                var _current_file = (e.currentTarget.files[0]);



                var filesize = ((_current_file.size/1024)/1024).toFixed(2);
                if(filesize>2.4){
                    swal({title:"Image Size error",text:"You are exceeding from 2.4MB maximum size, your file size is "+filesize+" MB"},function(){
                        $("#image-preview").removeAttr('style');
                        $("#image-upload").val('');
                    });

                    return false;
                }else{
                    var reader = new FileReader();

                    reader.readAsDataURL(_current_file);

                    reader.onload = function (e) {
                        //Initiate the JavaScript Image object.
                        var image = new Image();

                        //Set the Base64 string return from FileReader as source.
                        image.src = e.target.result;

                        //Validate the File Height and Width.
                        image.onload = function () {
                            var height = this.height;
                            var width = this.width;
                            if (height > 1280 || width > 1980) {

                                swal({title:"Image Dimensions error",text:"You are exceeding from dimension limit (1920 x 1280), your current dimensions are width: "+width+"px and height: "+height+"px"},function(){
                                    $("#image-preview").removeAttr('style');
                                    $("#image-upload").val('');
                                });
                                return false;
                            }

                        };

                    }
                }


            });
            $(".custom-select").select2();

            $("body").on("click",".make-mandatory-item",function () {
                $(".alert").hide();
                if($("#mandatory-item-form").valid()){
                    $("#mandatory-item-form").ajaxForm(function (response) {
                        response = $.parseJSON(response);
                        if(response){

                            if(response.type=="success"){
                                $('#mandatory-item-form .alert.success').html(response.message);
                                $('#mandatory-item-form .alert.success').show();

                                setTimeout(function(){
                                   location.reload();
                                },2000)
                            }else{
                                $('#mandatory-item-form .alert.error').html(response.message);
                                $('#mandatory-item-form .alert.error').show();
                            }
                        }
                    }).submit();
                }
            });

            $("body").on("click",".is_mandatory",function () {
                var id = $(this).data('id');

                if($(this).is(":checked")){
                    $("#mandatory-extra-item-option").modal('show');

                    $("#mandatory-extra-item-option input[name=id]").val(id);
                }else{
                    if(confirm("Do you want disable this option?")){
                            $.ajax({
                                url:"{!! env('APP_URL') !!}remove/mandatory/"+id,
                                success:function () {
                                 location.reload();
                                }
                            });
                    }else{
                        return false;
                    }
                }
            });

            $("body").on('click','.save',function () {
				var _this = $(this);


                if($("#restaurant-form").valid()){
					_this.removeClass('save');;
					_this.html(spinner);

					//return false;


                    $("#restaurant-form").ajaxForm(function (response) {
						//_this.removeAttr('disabled');
                        response = $.parseJSON(response);
                        if(response){

                            if(response.type=="success"){
                                $('#restaurant-form .alert.success').html(response.message);
                                $('#restaurant-form .alert.success').show();

                                setTimeout(function(){
                                    //window.location = '{!! env('APP_URL') !!}recipes';
                                    location.reload();
                                },2000)
                            }else{
                                $('#restaurant-form .alert.error').html(response.message);
                                $('#restaurant-form .alert.error').show();
                            }
                        }
                    }).submit();
                }
            });
            @if( isset($recipe))
            $("body").on("click",".upload-gallery",function () {
                $("#upload-gallery").modal('show');
            });

            $("body").on("click",".add-options",function () {
                $("#extra-options").modal('show');
            });

            $("body").on("click",".add-new-items",function () {
                var id = $(this).data('id');
                $("input[name=extra_option_id]").val(id);
                $("#add-new-items").modal('show');
            });
            $("body").on("click",".add-sub-item",function () {
                var id = $(this).data('id');
                var extra_option_id = $(this).data('extra-option-id');
                $("input[name=parent_id]").val(id);
                $("input[name=extra_option_id]").val(extra_option_id);
                $("#add-new-items").modal('show');
            });

            $("body").on("click",".add-extra-items",function () {
               var new_item = add_new_item();

               $("#items-list").append(new_item);
            });

            $("body").on("click",".add-extra-items-2",function () {
                var new_item = add_new_item();

                $("#items-list-2").append(new_item);
            });

            $("body").on("click",".delete-item",function () {


                var _this = $(this);
                var id = $(this).data('id');

                $.ajax({
                    url:"{!! env('APP_URL') !!}extra/item/delete/"+id,
                    success:function (response) {
                        _this.parent().parent().remove();
                    }
                });
            });

            $("body").on("click",".delete-new-item",function () {


                $(this).parent().parent().remove();
            });

            $("body").on("click",".delete-option",function () {

                var _this = $(this);
                var id = $(this).data('id');

                $.ajax({
                    url:"{!! env('APP_URL') !!}extra/option/delete/"+id,
                    success:function (response) {
                       _this.parent().parent().remove();
                    }
                });
                //
            });

            $("body").on("click",".update-option",function () {


               if($("#edit-form").valid()){
                   $("#edit-form").ajaxForm(function (response) {
                       location.reload();
                   }).submit();
               }
            });


            $("body").on("click",".update-item",function () {


                if($("#edit-item-form").valid()){
                    $("#edit-item-form").ajaxForm(function (response) {
                        var id = extra_option_id;
                        $.ajax({
                            url:"{!! env('APP_URL') !!}view/items/"+id,
                            success:function (response) {
                                response = response.data;
                                var row = "";
                                $.each(response,function (i,v) {


                                    row+=show_item(v);
                                });

                                $("#item-table > tbody").html(row);

                                $("#edit-extra-item-option").modal('hide');

                            }
                        });
                    }).submit();
                }
            });


            $("body").on("click",".edit-option",function () {

                var id = $(this).data('id');

                $.ajax({
                    url:"{!! env('APP_URL') !!}edit/option/"+id,
                    success:function (response) {
                        response = response.data;
                        $("#edit-form input[name=id]").val(response.id);
                        $("#edit-form input[name=option]").val(response.name);
						$("#edit-form input[name=arabic_option]").val(response.name_arabic);
                        $("#edit-form input[name=price]").val(response.price);

                        $("#edit-extra-option").modal('show');

                    }
                });

            });

            $("body").on("click",".edit-item",function () {

                var id = $(this).data('id');

                $.ajax({
                    url:"{!! env('APP_URL') !!}edit/item/"+id,
                    success:function (response) {
                        response = response.data;
                        $("#edit-item-form input[name=id]").val(response.id);
                        $("#edit-item-form input[name=item_type]").val(response.item_type);
                        $("#edit-item-form input[name=option]").val(response.name);
						$("#edit-item-form input[name=name_arabic]").val(response.name_arabic);
                        $("#edit-item-form input[name=price]").val(response.price);

                        $("#edit-extra-item-option").modal('show');

                    }
                });

            });


            $("body").on("click",".view-items",function () {

                    var id = $(this).data('id');
                extra_option_id = id;
                    $.ajax({
                        url:"{!! env('APP_URL') !!}view/items/"+id,
                        success:function (response) {
                            response = response.data;
                            var row = "";
                            $.each(response,function (i,v) {


                                row+=show_item(v);
                            });

                            $("#item-table > tbody").html(row);

                            $("#extra-option-item").modal('show');

                        }
                    });

            });

            $("body").on("click",".save-extra-options",function () {


                    if($("#extra-options-form").valid()){
                        $("#extra-options-form").ajaxForm(function (response) {
                            response = $.parseJSON(response);
                            if(response){

                                if(response.type=="success"){
                                    $('#extra-options .alert.success').html(response.message);
                                    $('#extra-options .alert.success').show();

                                    setTimeout(function(){
                                        window.location.reload();
                                    },2000)
                                }else{
                                    $('#extra-options .alert.error').html(response.message);
                                    $('#extra-options .alert.error').show();
                                }
                            }
                        }).submit();
                    }



            });

            $("body").on("click",".save-extra-items",function () {


                if($("#extra-items-form").valid()){
                    $("#extra-items-form").ajaxForm(function (response) {
                        response = $.parseJSON(response);
                        if(response){

                            if(response.type=="success"){
                                $('#extra-items-form .alert.success').html(response.message);
                                $('#extra-items-form .alert.success').show();

                                setTimeout(function(){
                                    window.location.reload();
                                },2000)
                            }else{
                                $('#extra-items-form .alert.error').html(response.message);
                                $('#extra-items-form .alert.error').show();
                            }
                        }
                    }).submit();
                }



            });

var gallery = new Dropzone("div#gallery",
                {
                    paramName: "files", // The name that will be used to transfer the file
                    addRemoveLinks: true,
                    uploadMultiple: true,
                    autoProcessQueue: false,
                    parallelUploads: 50,
                    maxFilesize: 5, // MB
                    acceptedFiles: ".png, .jpeg, .jpg, .JPG, .JPEG, .PNG,",
                    url: "{!! env('APP_URL') !!}upload/gallery/recipe",
                });

            gallery.on("sending", function(file, xhr, formData) {
                var filenames = [];

                $('.dz-preview .dz-filename').each(function() {
                    filenames.push($(this).find('span').text());
                });

                formData.append('filenames', filenames);

                formData.append('_token','{!! csrf_token() !!}');
                formData.append('recipe_id',"{!! $recipe->id !!}");

            });

            /* Add Files Script*/
            gallery.on("success", function(file, message){
                $("#msg").html(message);
                //setTimeout(function(){window.location.href="index.php"},200);
            });

            gallery.on("error", function (data) {
                $("#msg").html('<div class="alert alert-danger">{{__("label.there_is_some_thing_wrong")}}</div>');
            });

            gallery.on("complete", function(file) {
                gallery.removeFile(file);
               location.reload();
            });

            $(".upload").on("click",function (e){
                gallery.processQueue();
                e.preventDefault();
            });

            $("body").on("click",".delete-image",function () {
                var _this = $(this);
                var id = $(this).data('id');

                $.ajax({
                    url:"{!! env('APP_URL') !!}delete/image/"+id+"?type=recipe",
                    success:function (response) {
                        _this.parents(".gallery").remove();
                    }
                });
            });



            $("body").on("click",".delete-image",function () {
                var _this = $(this);
                var id = $(this).data('id');

                $.ajax({
                    url:"{!! env('APP_URL') !!}delete/image/"+id+"?type=recipe",
                    success:function (response) {
                        _this.parents(".gallery").remove();
                    }
                });
            });
@endif
        });

        function add_new_item() {
            return '<div class="row" style="margin-top: 10px">\n' +

				'                        <div class="col-sm-3">\n' +
                '                            <input class="form-control" name="item_name[]" placeholder="Name of item">\n' +
                '                        </div>\n' +

				'                        <div class="col-sm-3">\n' +
                '                            <input class="form-control" name="item_name_arabic[]" placeholder="Name of item">\n' +
                '                        </div>\n' +

                '<div class="col-sm-3">\n' +
            '                            <select class="form-control" name="item_type[]">' +
                '<option value="option">Option Button</option>' +
                '<option value="check-box">Checkbox</option> </select>\n' +
            '                        </div>\n' +
                '                        <div class="col-sm-2">\n' +
                '                            <input class="form-control" name="item_price[]" placeholder="Price if it has">\n' +
                '                        </div>\n' +

                '                        <div class="col-sm-1">\n' +
                '                            <a href="#!" class="delete-new-item btn btn-sm btn-danger" style="margin-top: 3px"><i class="glyphicon glyphicon-trash"></i> </a> \n' +
                '                        </div>\n' +
                '                    </div>'
        }

        function show_item(item) {
            var str = "<tr>";

                str+='<td>'+item.name+'</td>';
				str+='<td>'+item.name_arabic+'</td>';
                str+='<td>'+item.price+'</td>';
                str+='<td>'+(item.item_type?item.item_type:"")+'</td>';

                str+='<td>';
            var list = "";
                if(item.childern){
                    if(item.childern.length > 0){
                         list = '<ul class="list-group">';
                        $.each(item.childern,function (i,v) {
                            list += '<li class="list-group-item d-flex justify-content-between align-items-center"><a href="#!" class="edit-item" data-id="'+v.id+'"> '+v.name+'</a>' +
                                '<span class="badge badge-primary badge-pill">'+v.price+'</span>' +
                                '</li>';
                        });
                        list += "</ul>";
                    }
                }
                str+=list;
                str+='</td>';

                str+='<td>' +
                    '<a href="#!" class="btn btn-sm btn-primary edit-item" data-id="'+item.id+'"  data-toggle="tooltip" data-placement="top" title="Edit"><i class="glyphicon glyphicon-edit"></i> </a>'+
           ' <a href="javascript:;" data-id="'+item.id+'" class="btn btn-sm btn-danger delete-item"><i class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i></a>'+
            ' <a href="javascript:;" data-id="'+item.id+'"  data-extra-option-id="'+item.extra_option_id+'" class="btn btn-sm btn-warning add-sub-item"><i class="glyphicon glyphicon-th-list" data-toggle="tooltip" data-placement="top" title="Add sub items"></i></a>'
            '</td>';
            str+='</tr>';

            return str;
        }
    </script>
    @endsection
