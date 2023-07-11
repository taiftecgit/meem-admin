<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic&display=swap" rel="stylesheet">
<style>

@media print {

	body{
		font-family: 'Noto Sans Arabic', sans-serif;
	}


    #invoice-POS {
        direction: rtl;
        padding: 2mm;
        width: 80mm;
        background: #FFF;

    }

    ::selection {
        background: #f31544;
        color: #FFF;
    }

    ::moz-selection {
        background: #f31544;
        color-adjust: exact;  -webkit-print-color-adjust: exact; print-color-adjust: exact;
        color: #FFF;
    }

    h1 {
        font-size: 1.5em;
        color: #222;
		font-family: 'Noto Sans Arabic', sans-serif;
    }

    h2 {
        font-size: .9em;
		font-family: 'Noto Sans Arabic', sans-serif;
    }

    h3 {
        font-size: 1.2em;
        font-weight: 300;
        line-height: 2em;
		font-family: 'Noto Sans Arabic', sans-serif;
    }

    p {
        font-size: .7em;
        color: #666;
        line-height: 1.2em;
		font-family: 'Noto Sans Arabic', sans-serif;
    }

    #top, #mid, #bot { /* Targets all id with 'col-' */
        border-bottom: 1px solid #EEE;
    }

    #top {
        min-height: 100px;
    }

    #mid {
        min-height: 80px;
    }

    #bot {
        min-height: 50px;
    }

    #top .logo {
        margin: auto;
        height: 60px;
        width: 60px;
        /* background: url(
    {!! env('APP_URL').'public/uploads/logo/'.\Illuminate\Support\Facades\Auth::user()->restaurants->photos->file_name !!} ) no-repeat;
           */
        background-size: 60px 60px;
    }

    .clientlogo {
        float: left;
        height: 60px;
        width: 60px;
        /* background: url(
    {!! env('APP_URL').'public/uploads/logo/'.\Illuminate\Support\Facades\Auth::user()->restaurants->photos->file_name !!} ) no-repeat;
            */
        background-size: 60px 60px;
        border-radius: 50px;
    }

    .info {
        display: block;
        margin-left: 0;
    }

    .title {
        float: right;
    }

    .title p {
        text-align: right;
    }

    .table1 {
        width: 100%;
        border-collapse: collapse;
    }

    .table1 td {
        padding: 5px;
        border: 1px solid #EEE
    }

    .no-border td{
        border: 0 !important; font-size: 9px;
    }
    .tabletitle {
        padding: 5px;
        font-size: .5em;
        background: #FFF;
        color-adjust: exact;  -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }

    .service {
        border-bottom: 1px solid #EEE;
    }

    .item {
        width: 24mm;
    }

    .itemtext {
        font-size: .5em;
        margin: 0 !important;
    }

    #legalcopy {
        margin-top: 5mm;
    }


}

</style>


<div id="invoice-POS">
@php
	$resto = \App\Models\Restaurants::find(\App\Helpers\CommonMethods::getRestuarantID());

	$resto_meta = isset($resto->resto_metas)?$resto->resto_metas:null;
	//dd($resto_meta);

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

//	dump($resto->default_lang);

		app()->setLocale($resto->default_lang);


	@endphp
    <center id="top">
        <p class="legal" style="text-align: center; font-size: 16px"><strong>{{__('label.thankyou')}}</strong> 
        <div class="logo"><img src="{!! \Illuminate\Support\Facades\Auth::user()->restaurants->photos->file_name !!}" height="60" /> </div>
        <div class="info">{!! $resto->name !!}</h2>
        </div><!--End Info-->
    </center><!--End InvoiceTop-->

    <div id="mid" @if($resto->default_lang=="en") style="direction:ltr" @endif>
        <div class="info">

            <h2>Order ID: {!! $order->order_ref !!} </h2>
            <h6 style="margin: 0">{{__('label.customer_name')}} :


                {!! $order->customer_name !!}</h6>
            <table class="no-border" border="0" style="border: 0 !important;" @if($resto->default_lang=="en") style="direction:ltr" @endif>
                <tr>
                    <td><strong> {{__('label.address')}} :</strong></td>
                    <td>

                        @if(isset($order->customers) && isset($order->customers->customer_addresses) && $order->customers->customer_addresses->count() > 0 ))

                        {!! ucwords($order->customers->customer_addresses[0]->label) !!}
                        ،{!! $order->customers->customer_addresses[0]->area !!}
                        {!! $order->customers->customer_addresses[0]->address !!}
                        @else
                            {!! $order->order_instructions !!}
                            @endif
                    </td>
                </tr>
                <tr>
                    <td><strong> {{__('label.mobile_number')}} :</strong></td>
                    <td> {!! isset($order->customers)?(str_replace(env('COUNTRY_CODE'),'',$order->customers->users->email)):"" !!} </td>
                </tr>
            </table>

        </div>
    </div><!--End Invoice Mid-->

    <div id="bot">

        <div id="table">
            <table class="table1" @if($resto->default_lang=="en") style="direction:ltr" @endif>

                @if(isset($order->orderItems) && $order->orderItems->count() > 0)

                <tr class="tabletitle">
                    <th class="item">{{__('label.item_name')}}</th>
                    <th class="Hours">{{__('label.quantity')}}</th>
                    <th class="Hours">{{__('label.item_price')}}</th>
                    <th class="Rate">{{__('label.total_price')}}</th>
                </tr>

                        @php

				$total_price = 0;
                        @endphp
                        @foreach($order->orderItems as $item)
                <tr class="service">

                    <td class="tableitem"><p class="itemtext">
                            @php
                                $recipe  = $item->recipes;
						  $extra_price = 0;
						  $total_price = $total_price + $item->price;

                            $cate_name = "";
                            $categories = isset($recipe->categories)?$recipe->categories->pluck('category_id'):NULL;

                            if($categories){

                            $categories = \App\Models\Categories::whereIn('id',$categories)->pluck('name')->toArray();
                            $cate_name = $categories[0];


                            }
                            @endphp
                            @php
                                $extra_options = NULL;

                                   $opt = [];

                               if(!empty($item->extra_options)){

                               $extra_options = json_decode($item->extra_options);

						if($business_type!="ClothsStore"){

                               //$opt = "<ul>";
                               foreach($extra_options as $option){


                                $itm = \App\ExtraOptionItems::find($option->id);
                                if(isset($itm)){

                                    $opt[] = $itm->name;
                                 //  $opt.="<li>".$itm->name.' <span class="ml-2 badge badge-danger">'.number_format($itm->price,2).'</span>';
                                    $extra_price = $extra_price+$itm->price;
						$total_price = $total_price+$itm->price;
                                  //  dump($extra_price);
                                  if(isset($option->sub_items)){

                                        foreach($option->sub_items as $sub){

                                           $itm = \App\ExtraOptionItems::find($sub->sub_item_id);


                                           $extra_price = $extra_price+$itm->price;
											$total_price = $total_price+$itm->price;
                                        }

                                   }
                                   //$opt.="</li>";
                                }
                               }
                              // $opt.="</ul>";
						}

						if($business_type=="ClothsStore"){
						 foreach($extra_options as $option){

						if(!empty($option->size) && empty($option->color))
							echo ($option->size);

						if(empty($option->size) && !empty($option->color)){
						$color = '<div style="width: 15px; height: 15px; background-color:'.$option->color.'; border-radius: 20px"></div>';
						echo ($color);
						}



						if(!empty($option->size) && !empty($option->color)){
						$color = '<div style="width: 15px; height: 15px; background-color:'.$option->color.'; border-radius: 20px;"></div>';
						echo ($option->size. " : ".$color);
						}



						}
						}
                               }
                            @endphp
                            {!! isset($item->recipes)?$item->recipes->name:"" !!}</p>
                        </td>

                    <td class="tableitem"><p class="itemtext">{!! $item->qty !!}</td>
                    <td class="tableitem"><p class="itemtext">{!! $currency !!} {!! number_format($extra_price+$item->price) !!}</td>
                    <td class="tableitem"><p class="itemtext">{!! $currency !!} {!! number_format($item->qty*($extra_price+$item->price)) !!}</td>
                </tr>
		@php
			$total_price = $item->qty * $total_price ;
		@endphp
                    @endforeach



                <tr class="tabletitle">
                    <td></td>
                    <td></td>
                    <th class="Rate">{{__('label.delivery_fee')}}</th>
                    <th class="payment">{!! $currency !!} {!! $order->delivery_fee !!}</th>
                </tr>

                <tr class="tabletitle">
                    <td></td>
                    <td></td>
                    <th class="Rate">{{__('label.total_amount')}}</th>
                     <th class="payment">{!! $currency !!} {!! number_format($order->delivery_fee+$total_price) !!}</td>
                </tr>
                    @endif
            </table>
        </div><!--End Table-->

        <div id="legalcopy">

            </p>
            @if(isset($order->customers) && isset($order->customers->customer_addresses) && $order->customers->customer_addresses->count() > 0 ))
            <p class="legal"><strong>{{__('label.delivery_notes')}}:</strong> @if(isset($order->customers) && isset($order->customers->customer_addresses))
                        {!! $order->customers->customer_addresses[0]->instructions !!} @endif </p>

                @endif
        </div>
        <div style="border-top: 2px; -webkit-print-color-adjust: exact; print-color-adjust: exact; font-size: 8px">
            {!! \Illuminate\Support\Facades\Auth::user()->restaurants->name !!} , {!! \Illuminate\Support\Facades\Auth::user()->restaurants->address !!} {!! \Illuminate\Support\Facades\Auth::user()->restaurants->phone_number !!}
        </div>

    </div><!--End InvoiceBot-->
</div><!--End Invoice-->
