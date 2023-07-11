@extends('layouts.app')
@section('css')
    <link href="{!! env('APP_ASSETS') !!}vendor_components/dropzone/dist/dropzone.css" rel="stylesheet">
<link href="{!! env('APP_ASSETS') !!}vendor_components/select2/dist/css/select2.min.css" rel="stylesheet">
    
    @endsection
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
        .form-control, .form-select {
                    height: 46px !important;
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
	.select2-container--default .select2-selection--multiple{
		padding:6px;
	}
	
	@media only screen and (max-width:428px){
		#image-preview{
			width:100% !important;
			height: 50vh !important;
			margin-bottom: 10px;
		}
	}
	
	
	.modal-header {
    border-bottom-color: #ffab00;
    background-color: #ffab00;
	color:white;
}
    </style>
@php
$resto = \App\Restaurants::find(\App\Helpers\CommonMethods::getRestuarantID());
$lang = $resto->default_lang; 

app()->setLocale($lang);
if(session('app_lang') !==null){
$lang = session('app_lang');
app()->setLocale($lang);
}
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
$business_type =  trim($business_type);




            
          
@endphp
 <div class="content-wrapper"> 
        <div class="container-full">
            <section class="content">
				<h1 class="mt-4">Recipe</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{!! env('APP_URL') !!}dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">  @if(isset($recipe)) Edit Recipe @else New Recipe @endif</li>
        </ol>
                <div class="row">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fa fa-plus mr-1"></i>
                       @if(isset($recipe))
                            Edit {!! $recipe->name !!}
                            @else
                        New Item
                            @endif
                    </div>
                    <div class="card-body">
                        <form id="restaurant-form" method="POST" action="{!! env('APP_URL') !!}recipe/save" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{!! isset($recipe)?$recipe->id:'' !!}" />
                            <input type="hidden" name="business_type" value="{!! $business_type !!}">


							
							<div class="row mb-5">
								<div class="col-sm-8">
									<div class="card">
										<div class="card-header">
										<h3 class="pull-left">Product Basic Details</h3>
											<!--<a href="#!" class="btn btn-sm btn-primary pull-right add-faq">Add FAQ</a>-->
										</div>
										<div class="card-body">
									  <div class="row">
                            <div class="col-sm-4 col-md-12">
							
							<!-- row start-->
							<div class="row">
							<div class="col-6">
								<div class="form-group">
                                    <label>English Name</label>
                                    <input type="text" class="form-control" placeholder="" name="name" value="{!! isset($recipe)?$recipe->name:'' !!}" required>
                                </div>
							</div>
							<div class="col-6">
								<div class="form-group">
                                    <label>Arabic Name</label>
                                    <input type="text" class="form-control" placeholder="" name="arabic_name" value="{!! isset($recipe)?$recipe->arabic_name:'' !!}" required>
                                </div>
							</div>
							</div>
							<!-- row end-->
							
							
							<!-- row start-->
							<div class="row">
							<div class="col-6">
								<div class="form-group">
                                    <label>English Description</label>
                                    <textarea  class="form-control" placeholder="" name="short_description">{!! isset($recipe)?$recipe->short_description:'' !!}</textarea>
                                </div>
							</div>
							<div class="col-6">
								<div class="form-group">
                                    <label>Arabic Description</label>
                                    <textarea  class="form-control" placeholder="" name="short_description_arabic">{!! isset($recipe)?$recipe->short_description_arabic:'' !!}</textarea>
                                </div>
							</div>
							</div>
							<!-- row end-->
							
														<!-- row start-->
							<div class="row">
							<div class="col-6">
								 @php
                            $c = [];
                            if(isset($recipe)){
                            $c = $recipe->categories->pluck('category_id')->toArray();
                           // dump($c);

                            }
                            @endphp
							<div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>Category</label>
                                  <select class="custom-select" name="category[]" multiple>
                                      <option value="">Select Category</option>
                                      @if(isset($categories) && $categories->count() > 0)
                                        @foreach($categories as $category)
                                            <option value="{!! $category->id !!}" @if(isset($recipe) && in_array($category->id,$c)) selected @endif>{!! $category->name !!}</option>
                                            @endforeach
                                      @endif
                                  </select>
                                </div>
                            </div>
							</div>
							<div class="col-6">
								<div class="col-sm-12 col-md-12">
                                    <div class="form-group">
									<label>Base Price </label>
									<br>
										<input type="number" class="form-control" placeholder="" name="price" value="{!! isset($recipe)?$recipe->price:'' !!}" required>
										
									
										
                                        
										
										
										
                                        
                                    </div>
                                </div>
							</div>
							</div>
							<!-- row end-->
							
							<!-- row start-->
							<div class="row">
							<div class="col-6">
								<div class="form-group">
                                    <label>Product Active ?</label>
									&nbsp;&nbsp;&nbsp;
										@if(isset($recipe))
                                        <input type="checkbox" id="status" class="filled-in btn-success"  role="switch"  name="status"  @if($recipe->status==1) checked @endif>
                                        <label for="status"></label>
                                        @else
                                         <input type="checkbox" id="status" class="filled-in"  name="status">
                                         <label for="status"></label>
                                         @endif   

                                </div>
							</div>
							<div class="col-6">
								<div class="form-group">
                                     <label>Show Price ? </label>
									    &nbsp;&nbsp;&nbsp;
										@if(isset($recipe))
                                        <input type="checkbox" id="basic_checkbox_2" class="filled-in"  name="show_recipe_main_price"  @if($recipe->show_recipe_main_price==1) checked @endif>
                                        <label for="basic_checkbox_2"></label>
                                        @else
                                         <input type="checkbox" id="basic_checkbox_2" class="filled-in"  name="show_recipe_main_price">
                                         <label for="basic_checkbox_2"></label>
                                         @endif                                </div>
							</div>
							</div>
							<!-- row end-->
							
							<!-- row start-->
							<!-- 
							<div class="row">
							<div class="col-6">

									<div class="form-group">
                                    <label>Product Cover Image</label>
                                    <div id="image-preview" @if(isset($recipe) && isset($recipe->main_images) && !empty($recipe->main_images->file_name)) style="width:90%; height:80%;background: url({!! $recipe->main_images->file_name !!})" @endif>
                                    <label for="image-upload" id="image-label">Choose File</label>
                                    <input type="file" name="main_image" id="image-upload" />
                                </div>
                                @if(isset($recipe) && isset($recipe->main_images) && !empty($recipe->main_images->file_name))
                                <a href="#!" class="text-center text-danger remove-image" data-recipe-id="{!! isset($recipe)?$recipe->id:'' !!}">Remove Image</a>
                                @endif
                                </div>
							</div>
							<div class="col-6">
								
							</div>
							</div>
							-->
							<!-- row end-->

							
                            </div>
                        </div>
										</div>
										</div>
									</div>
									 <div class="col-sm-4">
                                    <p style="font-size: 14px">Cover Image</p>

                                <div id="image-preview" @if(isset($recipe) && isset($recipe->main_images) && !empty($recipe->main_images->file_name)) style="width:90%; height:80%;background: url({!! $recipe->main_images->file_name !!})" @endif>
                                    <label for="image-upload" id="image-label">Choose File</label>
                                    <input type="file" name="main_image" id="image-upload" />
                                </div>
                                @if(isset($recipe) && isset($recipe->main_images) && !empty($recipe->main_images->file_name))
                                <a href="#!" class="btn btn-danger remove-image" data-recipe-id="{!! isset($recipe)?$recipe->id:'' !!}">Remove Image</a>
                                @endif
                                </div>
									
							</div>
							
							<!-- Variations start -->
							@if($business_type=="ClothsStore")
                        @php
                            $sizes = [];
                            $colors = [];
							$color_data = null;
                            if(isset($recipe)){
                                $colors = \App\ClothOptions::where('product_id',$recipe->id)->where('type','color')->pluck('name')->whereNull('deleted_at')->toArray();
							//dump($colors);
                            $sizes = \App\ClothOptions::where('product_id',$recipe->id)->where('type','size')->pluck('name')->toArray();
							$color_data = \App\ClothOptions::where('product_id',$recipe->id)->whereIn('type',['color','color_image'])->whereNull('deleted_at')->get();
                            }
                            
                            
                        @endphp
								<div class="row mb-5">
								   <div class="col-sm-8">
									  <div class="card">
										 <div class="card-header">
											<h3 class="pull-left">Add Product Variations</h3>
											<!--<a href="#!" class="btn btn-sm btn-primary pull-right add-faq">Add FAQ</a>-->
										 </div>
										 <div class="card-body">
											<div class="row">
											   <div class="col-sm-4 col-md-12">
												  <!-- row start -->
												  <div class="row">
													 <div class="col-sm-4 col-md-6">
														<div class="form-group">
														   <label>Add Sizes (S/M/L/EUR....)</label> 
														   <select class="form-control" multiple id="size" name="size[]">
														   <option value="30" @if(in_array('30',$sizes)) selected @endif>30</option>
														   <option value="32" @if(in_array('32',$sizes)) selected @endif>32</option>
														   <option value="34" @if(in_array('34',$sizes)) selected @endif>34</option>
														   <option value="36" @if(in_array('36',$sizes)) selected @endif>36</option>
														   <option value="38" @if(in_array('38',$sizes)) selected @endif>38</option>
														   <option value="40" @if(in_array('40',$sizes)) selected @endif>40</option>
														   <option value="42" @if(in_array('42',$sizes)) selected @endif>42</option>
														   <option value="44" @if(in_array('44',$sizes)) selected @endif>44</option>
														   <option value="46" @if(in_array('46',$sizes)) selected @endif>46</option> 
														   <option value="52" @if(in_array('52',$sizes)) selected @endif>52</option> 
														   <option value="54" @if(in_array('54',$sizes)) selected @endif>54</option> 
														   <option value="56" @if(in_array('56',$sizes)) selected @endif>56</option> 
														   <option value="58" @if(in_array('58',$sizes)) selected @endif>58</option> 
														   <option value="60" @if(in_array('60',$sizes)) selected @endif>60</option> 
														   <option value="XXS" @if(in_array('XXS',$sizes)) selected @endif>XXS</option>
														   <option value="XS" @if(in_array('XS',$sizes)) selected @endif>XS</option>
														   <option value="S" @if(in_array('S',$sizes)) selected @endif>S</option>
														   <option value="M" @if(in_array('M',$sizes)) selected @endif>M</option>
														   <option value="L" @if(in_array('L',$sizes)) selected @endif>L</option>
														   <option value="XL" @if(in_array('XL',$sizes)) selected @endif>XL</option>
														   <option value="XXL" @if(in_array('XXL',$sizes)) selected @endif>XXL</option>
														   <option value="XXXL" @if(in_array('XXXL',$sizes)) selected @endif>XXXL</option>
														   <option value="0-3-Months" @if(in_array('0-3-Months',$sizes)) selected @endif>0-3 Months</option>
														   <option value="3-6-Months" @if(in_array('3-6-Months',$sizes)) selected @endif>3-6 Months</option>
														   <option value="6-9-Months" @if(in_array('6-9-Months',$sizes)) selected @endif>6-9 Months</option>
														   <option value="9-12-Months" @if(in_array('9-12-Months',$sizes)) selected @endif>9-12 Months</option>
														   <option value="1-2-YEARS" @if(in_array('1-2-YEARS',$sizes)) selected @endif>1-2 YEARS</option>
														   <option value="2-3-YEARS" @if(in_array('2-3-YEARS',$sizes)) selected @endif>2-3 YEARS</option>
														   <option value="3-4-YEARS" @if(in_array('3-4-YEARS',$sizes)) selected @endif>3-4 YEARS</option>
														   <option value="5-6-YEARS" @if(in_array('5-6-YEARS',$sizes)) selected @endif>5-6 YEARS</option>
														   <option value="6-7-YEARS" @if(in_array('6-7-YEARS',$sizes)) selected @endif>6-7 YEARS</option>
														   <option value="7-8-YEARS" @if(in_array('7-8-YEARS',$sizes)) selected @endif>7-8 YEARS</option>
														   <option value="1-2-Y" @if(in_array('1-2-Y',$sizes)) selected @endif>1-2 Y</option>
														   <option value="2-3-Y" @if(in_array('2-3-Y',$sizes)) selected @endif>2-3 Y</option>
														   <option value="3-4-Y" @if(in_array('3-4-Y',$sizes)) selected @endif>3-4 Y</option>
														   <option value="5-6-Y" @if(in_array('5-6-Y',$sizes)) selected @endif>5-6 Y</option>
														   <option value="6-7-Y" @if(in_array('6-7-Y',$sizes)) selected @endif>6-7 Y</option>
														   <option value="7-8-Y" @if(in_array('7-8-Y',$sizes)) selected @endif>7-8 Y</option>
														   <option value="0-3-آشهر" @if(in_array('0-3-آشهر',$sizes)) selected @endif>0-3 آشهر</option>
														   <option value="3-6-آشهر" @if(in_array('3-6-آشهر',$sizes)) selected @endif>3-6 آشهر</option>
														   <option value="6-9-آشهر" @if(in_array('6-9-آشهر',$sizes)) selected @endif>6-9 آشهر</option>
														   <option value="9-12-آشهر" @if(in_array('9-12-آشهر',$sizes)) selected @endif>9-12 آشهر</option>
														   <option value="1-2-سنوات" @if(in_array('1-2-سنوات',$sizes)) selected @endif>1-2 سنوات</option>
														   <option value="2-3-سنوات" @if(in_array('2-3-سنوات',$sizes)) selected @endif>2-3 سنوات</option>
														   <option value="3-4-سنوات" @if(in_array('3-4-سنوات',$sizes)) selected @endif>3-4 سنوات</option>
														   <option value="5-6-سنوات" @if(in_array('5-6-سنوات',$sizes)) selected @endif>5-6 سنوات</option>
														   <option value="6-7-سنوات" @if(in_array('6-7-سنوات',$sizes)) selected @endif>6-7 سنوات</option>
														   <option value="7-8-سنوات" @if(in_array('7-8-سنوات',$sizes)) selected @endif>7-8 سنوات</option>
														   </select>
														</div>
													 </div>
												  </div>
												  <!-- row end -->
												  <!-- row start-->
												  <div class="row">
													 <div class="col-6">
														<div class="form-group">
														   <label>Select Color Type (Color, Color+Image, Only Image....)</label>
														   <select class="form-control" required name="color_option">
														   <option value="color" @if(isset($recipe) && $recipe->color_option == 'color' ) selected @endif>Color Only</option>
														   <option value="color_image" @if(isset($recipe) && $recipe->color_option =='color_image' ) selected @endif>Color + Image</option>
														   </select>
														</div>
													 </div>
													 <div class="col-6">
													 </div>
												  </div>
												  <!-- row end-->
												  <!-- row start-->
												  <div class="row">
														<div class="col-sm-4 col-md-6">
														   <div class="form-group">
															  <label>Add Colors</label> 
															  <select class="form-control" multiple id="color" name="color[]">
															  </select>
														   </div>
														</div>
												  </div>
												  <!-- row end-->
												  <div class="row">
													 @if(isset($color_data))
													 <div class="row">
														<div class="col-sm-4 col-md-6">
														   <table class="table table-stripped">
															  <tbody>
																 @foreach( $color_data as $color)
																 <tr>
																	<td>
																	   <div style="width: 20px; height: 20px; background-color: {!! $color->name !!}; float: left; border-radius: 20px;"></div>
																	</td>
																	<td>@if($color->img_url!="") <img style="width: 40px" src="{!! $color->img_url!!}" @endif </td>
																	<td><a href="#!" data-id="{!! $color->id !!}" class="btn btn-sm btn-danger delete-color">Delete</a></td>
																 </tr>
																 @endforeach
															  </tbody>
														   </table>
														</div>
													 </div>
													 @endif
													 <div class="row">
														<div class="col-sm-4 col-md-6">
														   <table id="color-table" class="table table-stripped">
															  <tbody></tbody>
														   </table>
														</div>
													 </div>
												  </div>
											   </div>
											</div>
										 </div>
									  </div>
								   </div>
								</div>
								<!-- Variations end -->
                        @endif
                            


                            
							<!-- Gallery Start -->
							<div class="row mb-5">
								<div class="col-sm-8">
									<div class="card">
										<div class="card-header">
										<h3 class="pull-left">Product Gallery</h3>
									@if( isset($recipe))
                                    <a href="#!" class="btn btn-primary upload-gallery pull-right">Upload Gallery</a>
                                        @if($business_type=="Restaurants" || $business_type=="Florist" )
                                        <a href="#!" class="btn btn-primary add-options">Add Extra Options</a>
                                        @endif
                                    @endif
										</div>
										<div class="card-body">
										@if(isset($recipe)  && isset($recipe->galleries))
                                        <div class="row mb-2">
                                            @foreach($recipe->galleries as $gallery)
                                                <div class="col-sm-2 gallery">
                                                    <div class="mb-1" style="width: 50px; height: 50px; background-image: url({!! $gallery->file_name !!}); background-position: center; background-size: contain">


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
									
								</div>
							</div>
							<!-- Gallery End -->
							
							

                        @if($business_type=="Restaurants" || $business_type=="Florist")

                         @if(isset($recipe->extra_options) && $recipe->extra_options->count() > 0)
                             <h3>Extra Options</h3>
                             <hr />
                                <div class="row">
                                    <div class="col-sm-6 col-md-8">
                             <table class="table table-bordered">
                                 <thead>
                                    <tr>
                                        <th>Option Name</th>
                                        <th>Price</th>
                                        <th>Items</th>
                                        <th>Is Mandatory?</th>
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
							@if(isset($resto_metas['ENABLED_PRODUCT_FAQS']) && $resto_metas['ENABLED_PRODUCT_FAQS']=="Yes")
								
							<div class="row mb-5">
								<div class="col-sm-8">
									<div class="card">
										<div class="card-header">
										<h3 class="pull-left">Product FAQs</h3>
											<a href="#!" class="btn btn-sm btn-primary pull-right add-faq">Add FAQ</a>
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


							@if($business_type=="Restaurants" || $business_type=="Florist")
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" id="is_customized"  @if(isset($recipe) && $recipe->is_customized=="1")   checked @endif  name="is_customized" type="checkbox" />
                                        <label class="custom-control-label" for="is_customized">Customizedable</label>
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <a href="#!" class="btn btn-primary save">Save</a>
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
        <h5 class="modal-title" id="staticBackdropLabel">Recipe Gallery</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Gallery</label>
                                <div class="dropzone dz-clickable" id="gallery">
                                    <div class="dz-default dz-message" data-dz-message="">
                                        <span>Drop files here to upload <br>
										<b>(accepted file formats .jpg,.png,.jpeg case sensitive)</b> </span>
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


     <div class="modal" id="extra-options" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Add Extra Options</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="extra-options-form" method="POST" action="{!! env('APP_URL') !!}save/extra/options">
                            @csrf
                            <input type="hidden" name="recipe_id" value="{!! $recipe->id !!}" />
                                <input type="hidden" name="resto_id" value="{!! $recipe->resto_id !!}" />
                                                <div class="row">
                                                    <div class="col-sm-4">
													<b>Name English: </b>
                                                        <input class="form-control" name="option" placeholder="Name of option" required />
                                                    </div>
													<div class="col-sm-4">
													<b>Name Arabic: </b>
                                                        <input class="form-control" name="arabic_option" placeholder="Name of option in arabic" required />
                                                    </div>
                                                    <div class="col-sm-3">
													<b>Price : </b>
                                                        <input class="form-control" name="price" placeholder="Price if it has" />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12 text-right"><a href="#!" class="btn btn-sm btn-danger mt-1 add-extra-items">Add Extra Item</a> </div>
                                                </div>

                                                <div id="items-list">

                                                </div>
                            </form>
      </div>
      <div class="modal-footer">
                        <button type="button" class="btn btn-primary save-extra-options">Add</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
  </div>
</div>
</div>




 <div class="modal" id="edit-extra-option" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Edit Option</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{!! env('APP_URL') !!}update/option" id="edit-form">
                        <input type="hidden" name="id" />
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
							<b>Name English </b>
                                <input class="form-control" name="option" placeholder="Name of option" required />
                            </div>
							</div>
							<div class="row">
							<div class="col-sm-12">
							<b>Name Arabic</b>
                                <input class="form-control" name="arabic_option" placeholder="Name of option in arabic" />
                            </div>
							</div>
							<div class="row">
                            <div class="col-sm-12">
							<b>Price </b>
                                <input class="form-control" name="price" placeholder="Price" />
                            </div>
							</div>
							<div class="row">
                            <div class="col-sm-12">
							<br>
							<b></b>
                               <a href="#!" style="margin-top: 2px" class="btn btn-primary update-option">Save <i class="fa fa-save"></i> </a>
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
        <h5 class="modal-title" id="staticBackdropLabel">Customized Items List</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered" id="item-table">
                        <thead>
                        <tr>
                            <th>Name</th>
							<th>Name Arabic</th>
                            <th>Price</th>
                            <th>Item type</th>
                            <th>Customized Sub Items List</th>
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
        <h5 class="modal-title" id="staticBackdropLabel"><b>Edit Item</b> </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body modal-lg">
        <form method="POST" action="{!! env('APP_URL') !!}update/item" id="edit-item-form">
                        <input type="hidden" name="id" />
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
							<b>Name English: </b>
                                <input class="form-control" name="option" placeholder="Name of Item" required />
                            </div>
							
						</div>
						<div class="row">
							<div class="col-sm-12">
							<b><br>Name Arabic: </b>
                            <input class="form-control" name="name_arabic" placeholder="Name of option in arabic" required />
							<br>
							</div>
						</div>
						<div class="row">
                            <div class="col-sm-12">
							<b>Price: </b>
                                <input class="form-control" name="price" placeholder="Price" />
                            </div>
						</div>
						<div class="row">
                            <div class="col-sm-12">
                                <br><select class="form-control" name="item_type">
                                    <option value="">Choose Button type</option>
                                    <option value="option">Option Button</option>
                                    <option value="check-box">Checkbox</option>
                                </select>
                            </div>
							<br>
						</div>
						
						<div class="row">
                            <div class="col-sm-12">
                                <br><a href="#!" style="margin-top: 2px" class="btn btn-primary update-item">Save <i class="fa fa-save"></i> </a>
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
        <h5 class="modal-title" id="staticBackdropLabel">Make Mandatory options</h5>
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
        <h5 class="modal-title" id="staticBackdropLabel">Add more Items</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <form id="extra-items-form" method="POST" action="{!! env('APP_URL') !!}save/add/items">
                        @csrf
                        <input type="hidden" name="extra_option_id"/>

                        <input type="hidden" name="parent_id" />

                        <div class="row">
                            <div class="col-sm-12 text-right"><a href="#!" class="btn btn-sm btn-danger mt-1 add-extra-items-2">Add Extra Item</a> </div>
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
                                    <select class="form-control" name="item_type[]"><option value="option">Option Button</option><option value="check-box">Checkbox</option> </select>
                                </div>
                                <div class="col-sm-2">
                                    <input class="form-control" name="item_price[]" placeholder="Price if it has">
                                </div>

                            </div>
                        </div>
                    </form>
      </div>

      <div class="modal-footer">
                    <button type="button" class="btn btn-primary save-extra-items">Add Items</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
      
  </div>
</div>
</div>



<div class="modal" id="add-new-faq" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Add FAQ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <form id="faq-form" method="POST" action="{!! env('APP_URL') !!}save/faq">
                        @csrf
                       
						<input type="hidden" name="id" />
                        <input type="hidden" name="product_id" value="{!! $recipe->id !!}" />
		   
		   						<div class="form-group">
                                    <label>Question</label> 
<!--                                    <input type="text" name="question" required class="form-control" />-->
									<textarea row="2" style="height: 200px !important" name="question" required class="form-control"></textarea>
                                </div>
		   
		   						<div class="form-group">
                                    <label>Answer</label> 
                                    <textarea row="4" style="height: 200px !important" name="answer" required class="form-control"></textarea>
                                </div>

                        

                        
                    </form>
      </div>

      <div class="modal-footer">
                    <button type="button" class="btn btn-primary save-faq">Add</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
		color_option=$("select[name=color_option]").val();
        $(function () {
			@if(isset($recipe))
			$("body").on("change","select[name=color_option]",function(){
				var _existing = "{!! $recipe->color_option !!}";
				var _this_value = $(this).val();
				
				if(_existing!=_this_value){
					alert('Color setting will be reset, you have to add all color settings again');
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
            @foreach(\App\Colors::groupBy('color_code')->get() as $color)
            {
                id:"{!! $color->color_code !!}",
                text:"{!! $color->color_name !!}",
                html:'<div class="row" style="margin:0"><div style="width: 20px; height: 20px; background-color: {!! $color->color_code !!}; float: left; border-radius: 20px;"></div><div style="float: right; width: 250px">{!! addslashes($color->color_name) !!}</div></div>', 
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
				console.log("color_option: "+color_option+"\n--->Str: "+str)
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
				console.log("value :"+_value)
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
                success_callback: function() {
                  
                }// Default: false
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
                $("#msg").html('<div class="alert alert-danger">There is some thing wrong, Please try again!</div>');
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