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
            <section class="content">
                @if(isset($discount))
                <h3 style="margin-left: 10px">{!! $discount->discont_code !!}</h3>
                @endif
                  <form id="discount-form" method="POST" action="{!! env('APP_URL') !!}discount/save"
              enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{!! isset($discount)?$discount->id:'' !!}"/>
            <div class="row ar-m-0">
                <div class="col-xl-9">
                    <div class="card mb-4">



                        <div class="card-body">
                           <h4>{{__('label.add_discount')}}</h4>


                            <div class="row">
                                <div class="col-sm-4 col-md-6">
                                    <div class="form-group">
                                        <label>{{__('label.discount_name')}}*</label>
                                        <input type="text" class="form-control" placeholder="E.g. Taco Tuesday" name="discount_name"
                                               value="{!! isset($discount)?$discount->discount_name:'' !!}" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 col-md-6">
                                    <div class="form-group">
                                        <label>{{__('label.discount_arabic_name')}}*</label>
                                        <input style="direction: rtl" type="text" class="form-control" placeholder="E.g. Taco Tuesday" name="discount_name_arabic"
                                               value="{!! isset($discount)?$discount->discount_name_arabic:'' !!}" required="">
                                    </div>
                                </div>
                            </div>
                             <div class="row">

                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label>{{__('label.discount_code')}}</label>
                                        <input type="text" class="form-control" placeholder="E.g. TACO01102" name="discount_code"
                                               value="{!! isset($discount)?$discount->discount_code:'' !!}" required="">
                                    </div>
                                </div>

                                <div class="col-sm-4 col-md-2">
                                    <div class="form-group">
                                        <a href="#!" class="btn btn-primary generate-code mt-20">{{__('label.generate')}}</a>
                                    </div>
                                </div>
                            </div>

							<div class="row">
                                <div class="col-sm-4 col-md-6">
                                    <div class="form-group">
                                         <input type="checkbox" id="order_applicable" name="order_applicable"  class="filled-in"  @if(isset($discount) && $discount->order_applicable == "Yes") checked  @endif />
										 <label for="order_applicable">{{__('label.apply_at_whole_order')}}</label>
                                    </div>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-sm-4 col-md-6">
                                    <div class="form-group">
                                        <input type="checkbox" id="is_discount_at_delivery" name="is_discount_at_delivery"  class="filled-in"  @if(isset($discount) && $discount->is_discount_at_delivery == "Yes") checked  @endif />
                                        <label for="is_discount_at_delivery">{{__('label.is_discount_at_delivery')}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label for="order_applicable">{{__('label.discount_at_delivery')}}</label>
                                        <input type="text" id="discount_at_delivery" name="discount_at_delivery"  class="form-control" value="{!! isset($discount)?$discount->discount_at_delivery:'' !!}" />

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="order_applicable" style="visibility: hidden">{{__('label.discount_at_delivery')}}</label>
                                    <input name="delivery_discount_type" value="percentage" type="radio" id="radio_4" checked="">
                                    <label for="radio_4">Percentage (%)</label>
                                </div>
                                <div class="col-md-3">
                                    <label for="order_applicable" style="visibility: hidden">{{__('label.discount_at_delivery')}}</label>
                                    <input name="delivery_discount_type" type="radio" value="fixed_value" id="radio_5">
                                    <label for="radio_5">Fixed Value</label>
                                </div>
                            </div>



                        </div>
                    </div>


					<div class="selection-based-discount">
                    <div class="card card-body">
                        <h4 class="mb-40">{{__('label.application')}}</h4>

                        <div class="row mb-20">
                             <div class="col-md-1">
                                    <div class="form-group">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input " id="application-automatic" value="automatic" name="application_type" @if(isset($discount)) @if($discount->application_type=="automatic") checked @endif @else checked @endif type="radio"/>
                                            <label class="custom-control-label" for="application-automatic"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <h4 class="mb-0">{{__('label.automatic')}}  </h4>
                                    <p>{{__('label.will_be_automatically_applied_upon_checkout')}}</p>

                                </div>
                        </div>

                        <div class="row mb-20">
                             <div class="col-md-1">
                                    <div class="form-group">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input " id="application-manual" value="manual" @if(isset($discount) && $discount->application_type=="manual") checked @endif  name="application_type" type="radio"/>
                                            <label class="custom-control-label" for="application-manual"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <h4 class="mb-0">{{__('label.manual')}}</h4>
                                    <p>{{__('label.will_be_displayed_on_the_ordering_menu')}}</p>

                                </div>
                        </div>

                        <div class="row">
                             <div class="col-md-1">
                                    <div class="form-group">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input " id="application-hidden" value="hidden" name="application_type"  @if(isset($discount) && $discount->application_type=="hidden") checked @endif  type="radio"/>
                                            <label class="custom-control-label" for="application-hidden"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <h4 class="mb-0">{{__('label.hidden_code')}}</h4>
                                    <p>{{__('label.wont_ be_displayed_on_the_ordering_menu')}}</p>

                                </div>
                        </div>


                    </div>

                   <div class="card card-body">
                        <h4 class="mb-40">{{__('label.order_type')}}</h4>

                         @php
                         $order_type = !empty($discount->order_type)?explode(',',$discount->order_type):[];
                       @endphp

                        <div class="demo-checkbox">
                        <input type="checkbox" id="basic_checkbox_1" name="order_type[]" value="delivery" class="filled-in" @if(isset($discount)) @if(in_array('delivery', $order_type)) checked @endif @else checked @endif />
                        <label for="basic_checkbox_1">{{__('label.delivery')}}</label>
                        <input type="checkbox" id="basic_checkbox_2"  name="order_type[]" value="pickup" class="filled-in"  @if(in_array('pickup', $order_type)) checked @endif />
                        <label for="basic_checkbox_2">{{__('label.pickup')}}</label>
                        <input type="checkbox" id="basic_checkbox_3"  name="order_type[]" value="dine-in" class="filled-in" @if(in_array('dine-in', $order_type)) checked @endif />
                        <label for="basic_checkbox_3">{{__('label.dine_in')}}</label>

                        </div>

                   </div>


                   <div class="card card-body">
                        <h4 class="mb-40">{{__('label.application_outlets')}}</h4>

                        <div class="demo-checkbox">
                            <div class="row">
                                <div class="col-md-6  outlets">
                                    <input name="outlets" value="all_outlets" type="radio" id="radio_outlets_12" @if(isset($discount)) @if($discount->outlets=="all_outlets") checked @endif @else checked @endif />
                        <label for="radio_outlets_12">{{__('label.all_outlets')}}</label>
                                </div>
                                <div class="col-md-6 outlets @if(isset($discount) && $discount->outlets=="selected_outlets") selected @endif">
                                     <input name="outlets" type="radio" value="selected_outlets" id="radio_outlets_22" @if(isset($discount) && $discount->outlets=="selected_outlets") checked @endif />
                                    <label for="radio_outlets_22">{{__('label.selected_outlets')}}</label>
                                    @php
                                    $resto_id =\App\Helpers\CommonMethods::getRestuarantID();
                                        $outlets = \App\Models\Outlets::whereNull('deleted_at')->where('resto_id',$resto_id)->get();
                                        $selected_outlets = [];
                                        $selected_items = [];
                                        if(isset($discount)){
                                        if($discount->outlets=="selected_outlets"){
                                                $outlt = ($discount->discount_outlets);
                                                if(isset($outlt) && $outlt->count() > 0){
                                                    $selected_outlets = $outlt->pluck('outlet_id')->toArray();

                                                }
                                            }

                                            if($discount->items=="selected_items"){

                                                $d_items = ($discount->discount_items);
                                                if(isset($d_items) && $d_items->count() > 0){
                                                    $selected_items = $d_items->pluck('item_id')->toArray();


                                                }
                                            }

                                        }



                                    @endphp

                                        <div class="col-md-12 outlet-section">
                                                    @if(isset($outlets) && $outlets->count() > 0)
                                                    <ul  class="list-unstyled">
                                                        @foreach($outlets as $outlet)
                                                        <li>
                                                            <div class="custom-control custom-checkbox">
                                                                <input class="custom-control-input selected-outlets" @if(isset($discount) && count($selected_outlets) > 0 && in_array($outlet->id,$selected_outlets)) checked @endif id="outlet-manager-{!! $outlet->id !!}"  value="{!! $outlet->id !!}" name="selected_outlets[]" type="checkbox"/>
                                                                <label class="custom-control-label" for="outlet-manager-{!! $outlet->id !!}"> {!! $outlet->name !!}</label>
                                                            </div>
                                                   </li>
                                                        @endforeach
                                                    </ul>
                                                    @endif
                                                </div>
                                </div>
                            </div>





                        </div>

                   </div>
</div>
                    <div class="card card-body">
                        <h4 class="mb-40">{{__('label.discount_type')}}</h4>

                        <div class="demo-checkbox">
                            <div class="row">
                                <div class="col-md-6">
                                 <input name="discount_type" value="percentage" type="radio" id="radio_1" checked />
                        <label for="radio_1">{{__('label.percentage_%')}}</label>
                                </div>
                                <div class="col-md-6">
                                  <input name="discount_type" type="radio" value="fixed_value" id="radio_2" />
                        <label for="radio_2">{{__('label.fixed_value')}}</label>
                                </div>
                            </div>





                        </div>

                        <div class="row mt-20 mb-20">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.amount')}} *</label>
                                    <input type="text" value="{!! isset($discount)?$discount->amount:'' !!}" required="" name="amount" required="" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.minimum_order_value')}} </label>
                                    <input type="text" value="{!! isset($discount)?$discount->minimum_order_value:'' !!}"  name="minimum_order_value"  class="form-control">
                                </div>
                            </div>

                             <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.maximum_discount')}} </label>
                                    <input type="text"  name="maximum_discount" value="{!! isset($discount)?$discount->maximum_discount:'' !!}"  class="form-control">
                                </div>
                            </div>

                        </div>

                   </div>
<div class="selection-based-discount">
                   <div class="card card-body">
                        <h4 class="mb-40">{{__('label.discount_items')}}</h4>

                        <div class="demo-checkbox">
                            <div class="row">
                                <div class="col-md-6 items">
                                    <input name="items" value="all_items" type="radio" id="radio_12" checked />
                        <label for="radio_12">{{__('label.all_Items')}}</label>
                                </div>
                                <div class="col-md-6 items @if(isset($discount) && $discount->items=="selected_items") selected @endif">
                                     <input name="items" type="radio" value="selected_items" @if(isset($discount) && $discount->items=="selected_items") checked @endif id="radio_22" />
                                     <label for="radio_22">{{__('label.selected_items')}}</label>

                                     @php
                                        $items = \App\Models\Recipes::whereNull('deleted_at')->where('resto_id',$resto_id)->get();

                                     @endphp
                                      <div class="col-md-12 item-section">
                                            <select id="item-list" multiple class="form-control" name="discount_item[]">
                                                @if(isset($items) && $items->count() > 0)
                                                    @foreach($items as $item)
                                                        <option value="{!! $item->unique_shared_key !!}"  @if(isset($discount) && count($selected_items) > 0 && in_array($item->unique_shared_key,$selected_items)) selected @endif >{!! $item->name !!}</option>
                                                    @endforeach

                                                @endif

                                            </select>
                                        </div>
                                </div>
                            </div>





                        </div>

                   </div>
					</div>






                    <div class="card card-body">
                        <h4 class="mb-40">{{__('label.customer_segmentation')}}</h4>

                        <div class="demo-checkbox">
                            <div class="row">
                                <div class="col-md-6">
                                    <input name="customer_segmentation" value="all_customers" type="radio" id="customer_segmentation_12" checked />
                                    <label for="customer_segmentation_12">{{__('label.all_customers')}}</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="customer_segmentation" type="radio" value="new_customer" id="customer_segmentation_22" />
                                    <label for="customer_segmentation_22">{{__('label.new_customer')}}</label>

                                </div>
                            </div>







                        </div>

                        <div class="row mt-10 mb-20">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.number_of_uses_per_customer')}}</label>
                                    <input type="number"  value="{!! isset($discount)?$discount->number_of_uses_per_customer:'' !!}" required="" name="number_of_uses_per_customer"  class="form-control">
                                </div>
                            </div>
                        </div>

                   </div>

                    <div class="card card-body">
                        <h4 class="mb-40">{{__('label.availability')}}</h4>



                        <div class="row mt-20 mb-10">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.start_date')}}  *</label>
                                    <input type="text" required="" name="start_date"  value="{!! isset($discount)?$discount->start_date:'' !!}" required="" readonly class="form-control date">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.start_time')}} * </label>
                                    <input type="text"  name="start_time"  value="{!! isset($discount)?$discount->start_time:'' !!}" required=""  class="form-control timepicker">
                                </div>
                            </div>



                        </div>

                        <div class="demo-checkbox">
                        <input name="expire_on_date" value="expire_on_date" type="radio" id="expire_on_date_12" checked />
                        <label for="expire_on_date_12">{{__('label.expires_on_specific_date')}}</label>
                        <input name="expire_on_date" type="radio" value="never_expire" id="expire_on_date_22" />
                        <label for="expire_on_date_22">{{__('label.never_expire')}}</label>



                        </div>

                         <div class="row mt-10 mb-10">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.end_date')}}</label>
                                    <input type="text" name="end_date"  value="{!! isset($discount)?$discount->end_date:'' !!}" readonly class="form-control date" id="end_date">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.end_time')}}</label>
                                    <input type="text"  name="end_time"  value="{!! isset($discount)?$discount->end_time:'' !!}"   class="form-control timepicker">
                                </div>
                            </div>



                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.total_number_of_uses')}}</label>
                                    <input type="number" name="total_number_of_cases"  value="{!! isset($discount)?$discount->total_number_of_cases:'' !!}" required="" class="form-control">
                                </div>
                            </div>
                        </div>

                   </div>

                    <div class="card card-body">
                        <div class="row">
                            <div class="col-md-3">
                              <a href="#!" class="btn btn-primary save-discount">{{__('label.save_discount')}}</a>
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
            $(".timepicker").timepicker({ step:5});
            $("#item-list").select2({
                width: 'element',
            });
            $(".date").datepicker({
                format:"yyyy-mm-dd"
            });

			$("body").on("change","#order_applicable",function(){
				//var _required_inputs = $("").find("input").prop("required");

				if($(this).is(":checked")){
					$(".selection-based-discount").hide();
					$("input[name=amount]").prop('required',false);
				}

				else{
					$("input[name=amount]").prop('required',true);
					$(".selection-based-discount").show();
				}


			});

            $("input[name=outlets]").on('change',function(){
                var value = $(this).val();
                var _this = $(this);
                $(".outlets").removeClass('selected');

                 _this.parent().addClass('selected');
            });

            $("input[name=items]").on('change',function(){
                var value = $(this).val();
                var _this = $(this);
                $(".items").removeClass('selected');

                 _this.parent().addClass('selected');
            });

            $("body").on("click",".generate-code",function(){
                        var result = '';
                        var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                        var charactersLength = characters.length;
                        for ( var i = 0; i < 6; i++ ) {
                          result += characters.charAt(Math.floor(Math.random() * charactersLength));
                        }
                       $("input[name=discount_code]").val(result);
            });

            $("body").on("click",".save-discount",function(){
                if($("#discount-form").valid()){
                    $("#discount-form").ajaxForm(function(response){
                        response = $.parseJSON(response);
                         if(response.type=="success"){
                            $.toast({
                                heading: "{{__('label.discount_updated')}}",
                                text: response.message,
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'success',
                                hideAfter: 3000,
                                stack: 1
                            });

                            setTimeout(function () {
                                window.location = "{!! env('APP_URL') !!}discounts";
                            },2000);
                        }else{
                            $.toast({
                                heading:"{{__('label.discount_updating_error')}}",
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
