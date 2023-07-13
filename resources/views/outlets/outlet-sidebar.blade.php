<style>
    #main{
        position: relative;
    }
    #main .disabled{
        content: "";
        position: absolute;
        top: 0;
        left: -12.5px;
        width: calc(100% + 25px);
        height: 100%;
        z-index: 5;
        background: hsla(0,0%,100%,.9);
    }

    #main.active .disabled{
        display: none;}
    .btn-toggle.btn-sm,.btn-toggle.btn-sm > .handle{
        border-radius: 16px;
    }
    .btn-toggle.btn-sm.active
    {
      
    }
    .btn-toggle.btn-lg,.btn-toggle.btn-lg > .handle{
        border-radius: 20px;
    }

    .btn-toggle,.btn-toggle > .handle{
        border-radius: 16px;
    }
    .outlet-status{
        background: #C1C1C1;
        padding: 17px 45px 18px;
    }
    .fixed .content-wrapper{
        padding-top: 0 !important;
    }

    .resto-menu{

    }
    .resto-menu li{
        display: inline-block;
        width: 100%;
        font-size: 1rem;
        padding: 7px;
    }
    .resto-menu li .nav-link {
        font-size: 14px;
        font-weight: 400;
        color:#000

    }

    .resto-menu li:hover,.resto-menu li.active{
        border-right: 4px solid;
        border-color: #FFAB00;
    }

    .resto-menu li:hover,.resto-menu li.active{
        border-right: 4px solid;
        border-color: #FFAB00;
    }

    .resto-menu li.active span{
        font-weight: 600;
    }


.outlet-active{
    background-color: #FFAB00 !important;
}
    .nav-link:hover, .nav-link:focus{

    }
    .p-outletnameP{
        padding-left: 47px;
        padding-top: 22px;
        padding-bottom: 25px;
    }
    .outlet-info-section{
        padding:30px;
    }
    /*.outlet-active{
        height: 30px !important;
        width: 90px !important;
    }

    .outlet-active:after{
        font-size: 13px !important;
        content: "Yes" !important;
    }

    .outlet-active > .handle{
        width: 2.125rem !important;
        height: 2.125rem !important;
        top:0rem !important;
    }
    .outlet-active.active > .handle {
        left: 2.1rem !important;
        transition: left .25s !important;
    }*/
</style>
@php
$resto = \App\Models\Restaurants::find(\App\Helpers\CommonMethods::getRestuarantID());
$lang = $resto->default_lang;
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
$business_type = trim($business_type);
app()->setLocale($lang);
if(session('app_lang') !==null){
$lang = session('app_lang');
app()->setLocale($lang);
}
@endphp
<div class="row" style="--bs-gutter-x:0">
    <div class="col-12 outlet-status">
        <div class="d-flex justify-content-start">

            <div class="">
                @if(isset($outlet))
                <p style="margin-top: 0; margin-bottom: 5px">{{__('label.outlet_is_active')}}</p>
                    @endif

            </div>
            <div class="" style="margin-right: 10px; margin-left: 7px; margin-top: -4px">
                @if(isset($outlet))

                <button type="button" class="btn btn-sm btn-toggle btn-success @if(isset($outlet) &&$outlet->active=="1") active @endif outlet-active" data-bs-toggle="button" aria-pressed="@if(isset($outlet) && $outlet->active=="1") true @else false @endif" autocomplete="off">
                    <div class="handle"></div>
                </button>
                    @endif
            </div>
        </div>
    </div>
</div>

<div class="row"  style=";--bs-gutter-x:0">

    <div class="col-md-12 p-outletnameP">
        <a href="{!! env('APP_URL') !!}outlets" style="color: #000;">
        <svg style="margin-right: 10px" id="Shape_707" data-name="Shape 707" xmlns="http://www.w3.org/2000/svg" width="21.079" height="13.138" viewBox="0 0 21.079 13.138">
            <path id="Shape_707-2" data-name="Shape 707" d="M1992.041,272.576a1.21,1.21,0,0,1-1.319.855c-7.25-.011-8.5-.007-15.75-.007h-.379c.1.1.16.17.224.234q1.722,1.718,3.446,3.436a1.037,1.037,0,0,1,.288,1.091,1,1,0,0,1-.8.725,1.056,1.056,0,0,1-1-.339q-1.945-1.944-3.9-3.883c-.456-.454-.924-.9-1.362-1.368a6.5,6.5,0,0,1-.526-.744v-.424a5.306,5.306,0,0,1,.526-.744c1.74-1.751,3.494-3.488,5.24-5.233a1.076,1.076,0,0,1,1.023-.357,1,1,0,0,1,.8.725,1.023,1.023,0,0,1-.271,1.072c-.654.656-1.312,1.306-1.965,1.962-.559.561-1.114,1.125-1.712,1.728h.341c7.259,0,8.518,0,15.778-.007a1.207,1.207,0,0,1,1.319.855Z" transform="translate(-1970.962 -265.795)" fill="#6d6d6d" opacity="0.569"/>
        </svg>  {{__('label.outlets')}}</a>
        <h3>
            @if(isset($outlet))    {!! $outlet->name !!}@endif
        </h3>
    </div>

        <div class="col-md-12">


            <ul class="resto-menu mt-5" role="tablist">

                <li class="nav-item @if(\Illuminate\Support\Facades\Route::currentRouteName()=="outlets-form" || \Illuminate\Support\Facades\Route::currentRouteName()=="OutletEdit") active @endif">
                    <a @if(isset($outlet)) href="{!! env('APP_URL') !!}outlet/edit/{!! $outlet->unique_key !!}" @else href="{!! env('APP_URL') !!}new/outlet" @endif class="nav-link @if(\Illuminate\Support\Facades\Route::currentRouteName()=="outlets-form") active @endif"><span>{{__('label.basic_information')}}</span></a> </li>
                <li class="nav-item @if(\Illuminate\Support\Facades\Route::currentRouteName()=="outlets-address") active @endif"> <a @if(isset($outlet)) href="{!! env('APP_URL') !!}outlet/address?o={!! $outlet->unique_key !!}" @else href="#!" @endif class="nav-link  @if(\Illuminate\Support\Facades\Route::currentRouteName()=="outlets-address") active @endif"><span>{{__('label.address')}}</span></a> </li>{{--
                <li class="nav-item"> <a @if(isset($outlet)) href="{!! env('APP_URL') !!}outlet/ordering-mode?o={!! $outlet->unique_key !!}" @else href="#!" @endif class="nav-link   @if(\Illuminate\Support\Facades\Route::currentRouteName()=="outlets-ordering-mode") active @endif"><span>{{__('label.ordering_mode')}}</span></a> </li>--}}
                <li class="nav-item @if(\Illuminate\Support\Facades\Route::currentRouteName()=="outlets-delivery") active @endif"> <a @if(isset($outlet)) href="{!! env('APP_URL') !!}outlet/delivery?o={!! $outlet->unique_key !!}" @else href="#!" @endif class="nav-link   @if(\Illuminate\Support\Facades\Route::currentRouteName()=="outlets-delivery") active @endif"><span>{{__('label.delivery')}}</span></a> </li>


                <li class="nav-item  @if(\Illuminate\Support\Facades\Route::currentRouteName()=="outlets-delivery-area") active @endif"> <a @if(isset($outlet)) href="{!! env('APP_URL') !!}outlet/area/delivery?o={!! $outlet->unique_key !!}" @else href="#!" @endif class="nav-link   @if(\Illuminate\Support\Facades\Route::currentRouteName()=="outlets-delivery-area") active @endif"><span>{{__('label.delivery_area')}}</span></a> </li>
                <li class="nav-item @if(\Illuminate\Support\Facades\Route::currentRouteName()=="outlets-pickup") active @endif"> <a @if(isset($outlet)) href="{!! env('APP_URL') !!}outlet/pickup?o={!! $outlet->unique_key !!}" @else href="#!" @endif class="nav-link   @if(\Illuminate\Support\Facades\Route::currentRouteName()=="outlets-pickup") active @endif"><span>{{__('label.pickup')}}</span></a> </li>
				@if( $business_type=="Restaurants")
                <li class="nav-item  @if(\Illuminate\Support\Facades\Route::currentRouteName()=="outlets-contactless-dining") active @endif"> <a @if(isset($outlet)) href="{!! env('APP_URL') !!}outlet/contactless/dining?o={!! $outlet->unique_key !!}" @else href="#!" @endif class="nav-link   @if(\Illuminate\Support\Facades\Route::currentRouteName()=="outlets-contactless-dining") active @endif"><span>{{__('label.contactless_dining')}}</span></a> </li>
				@endif
                    <li class="nav-item  @if(\Illuminate\Support\Facades\Route::currentRouteName()=="outlets-digital-menu") active @endif"> <a @if(isset($outlet)) href="{!! env('APP_URL') !!}outlet/digital/menu?o={!! $outlet->unique_key !!}" @else href="#!" @endif class="nav-link   @if(\Illuminate\Support\Facades\Route::currentRouteName()=="outlets-digital-menu") active @endif"><span>{{__('label.qr_code_digital_menu')}}</span></a> </li>



            </ul>
        </div>



</div>


