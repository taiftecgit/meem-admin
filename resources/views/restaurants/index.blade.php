@extends('layout.app')
@section('content')
    <style type="text/css">
        .row{ margin-left: 0; margin-right: 0}
        .homepage-ads .item img
        {
            display: block;
            width: 100%;
            height:auto !important;
        }
        .quantity{
            background: #fff9ed;
            font-weight: bold;
            padding: 3px 13px;
        }

        .plus-minus-button{
            border: 1px solid #000;
            border-radius: 20px;
            width: 99px;
            width: 99px;
            margin-right: 32%;
        }
        .quantity-button{
            margin: 0 -4px;
        }
        .special-offer-tag{
            background: #fff;
            width: 40px;
            height: 40px;
            border: 1px solid;
            border-color: #f5f5f5;
            border-radius: 31px;
            position: absolute;
            top: 10px;
            bottom: 0;
            right: 0px;
            padding-left: 7px;
            padding-right: .5rem;
            padding-top: 7px;
            color: #ffa606;
            font-size: 26px;
        }
        .recipe-description{
            font-weight: 500; color:#000
        }
        .resto-basket-time{
            background: #fff9ed;
            width: 100px;
            margin-right: 15px;
            border-radius: 10px;
            padding: 1px 5px;
        }
        .section.special-offer{
            background: #ababab26; border-radius: 5px;min-height: 56px;
        }

        #recipe-detail .card-body{
            padding: 0 1rem;
        }

        #recipe-detail .card{ border: none; margin-bottom: 0}

        #out-of-order .modal-content{
            border-radius: 10px;
        }

        #out-of-order{
            top: 40%;
        }
        .recipe-img{
            background-position: center;
            background-size: cover;
            border-radius: 11px;
            margin-top: 10px;
            height: 200px;
        }

        #out-of-order h1{
            color:#ffa606;
            font-weight: 600;
            font-size: 21px;
        }
        .category-recipes{
            padding: 0 13px;
        }
        .today-tomorrow{
            width:100%
        }
        .today-tomorrow li{
            width: 100%;
            text-align: center;
            padding: 7px;
            font-size: 16px;
        }

        .today-tomorrow li.active{
            background: #fff9ed;
        }

        .time-slots a{ border: none}

        .recipe-overlay{
            background: rgb(255,255,255,0.5);
            position: absolute;
            right: 0;
            left: 0;
            top: 0;
            bottom: 0;
        }
        .img-div{
            max-height: 70px; overflow: hidden }
        @media (max-width: 575.98px)
        {
            .homepage-search-block {
                padding: 0rem 0 !important;
            }
        }
        @media (min-width: 360px)
        {
            .plus-minus-button{

                margin-right: 32%;
            }
        }

    </style>
<div class="mobile-container">
    @php
        $opening_timing = $resto_info['opening_timing'];
        $closing_timing = $resto_info['closing_timing'];
        $is_resto_opened = false;
        if(!empty($opening_timing) && !empty($closing_timing)){
      //  $opening_timing = "13:00";

            $opening_timing = date('H:i', strtotime($opening_timing));
            $closing_timing = date('H:i', strtotime($closing_timing));
            $current_time = date('H:i');

           if($current_time > $opening_timing && $current_time <= $closing_timing)
           $is_resto_opened = true;

                            $opening_timing = strtotime($opening_timing);
                            $closing_timing =strtotime($closing_timing);
                            $tStart = $opening_timing;
                            $tEnd = ($closing_timing);
                            $tNow = $tStart;

                            while($tNow <= $tEnd){
                                 $now = date("H:i",$tNow);

                                 $tNow = strtotime('+15 minutes',$tNow);

                                 $time_slots[] = $now;

                            }




        }
    @endphp



    <div class="row d-flex align-items-center">
        <div class="col-md-12">
            <div class="homepage-search-title" style="padding: 10px">
                <div class="owl-carousel homepage-ads">

                    @if(isset($resto_info['gallery']) && !empty($resto_info['gallery']))
                        @foreach($resto_info['gallery'] as $key => $value)
                            <div class="item text-center">
                                <img src="{{$value}}" width="199" height="160" class="img-fluid">
                            </div>
                        @endforeach
                    @endif

                </div>
            </div>
        </div>
    </div>
<div class="p-3">
    <div class=" d-flex flex-column  leading-normal mt-0 mb-0">
        <h3 class=""><span class="resto_title">{{$resto_info['name']}}</span></h3>

        <div class="address p-2" style="margin-right: 6px">
           {!! nl2br($resto_info['address']) !!}
        </div>
    </div>

    <div class="  text-sm leading-normal mt-3 mb-3 resto-time-basket">

        <div class="d-flex flex-row resto-basket-time">
            <div class="bd-highlight">  <i class="icofont-wall-clock fs-2"></i></div>
            <div class="bd-highlight">
                @if(!empty($resto_info['delivery_time_range']))
                    &nbsp;{{  $resto_info['delivery_time_range'] }}
                @else
                    No time available
                @endif
            </div>

        </div>

        <div class="d-flex flex-row resto-basket-time" style="width: 185px">
            <div class="bd-highlight">
                <i class="icofont-basket fs-2"></i>
            </div>
            <div class="bd-highlight">
                @if(!empty($resto_info['min_basket_price']))
                    {!!  number_format($resto_info['min_basket_price'],2) !!} د. ع اقل قيمة للطلب
                @else
                    No minimum basket
                @endif</div>

        </div>


    </div>

    {{--<div class="section loyality-program">

        <p class="m-0"><img src="{!! env('APP_URL_ASSETS') !!}img/checkbox.png" style="margin-top: -3px; margin-right: 2px"> Loyality Program</p>
        <small>available</small>

    </div>--}}
    {{--@if($resto_info['special_offers'] && count($resto_info['special_offers']))

            <div class="section special-offer">
                <div class="d-flex">
                    <div class="flex-shrink-0 position-relative mr-3" style="margin-left: 40px">
                        <i class="icofont-tags special-offer-tag"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="fw-bold">{!! $resto_info['special_offers']['offer_title'] !!}</p>
                        <p>{!! $resto_info['special_offers']['offer_text'] !!}</p>
                    </div>
                </div>
            </div>


        @endif--}}
</div>


    <div class="row  delivery-to">
        <div class="d-flex flex-row">
            <div class="mr-4">
                <img src="{!! env('APP_URL_ASSETS') !!}img/location.png" class="img-delivery" style=""><strong>التوصيل الى</strong>
                <ul class="list-inline adjust-location">
                    <li class="list-inline-item"><span id="location"> Select Location</span></li>
                    <li class="list-inline-item"><span id="time">(اقرب وقت)</span></li>
                    <li class="list-inline-item"><i class="icofont-thin-down"></i></li>
                </ul>

            </div>
            <div class="text-end">
                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
                    <label class="btn btn-secondary action-buttons" for="btnradio1">توصيل</label>
                    <input type="radio" class="btn-check" name="btnradio" id="btnradio2" aut

                           ocomplete="off">
                    <label class="btn btn-secondary  action-buttons" for="btnradio2">استلام</label>



                </div>
            </div>

        </div>
    </div>





    <div class="section">
        <section class="section bg-white">

                <div class="row">
                    <div class="col-md-12 p-0 mt-3 ">
                        <nav id="navbar-example2" class="navbar navbar-light bg-white scrollSpy-section p-0 filters button-group js-radio-button-group filters-button-group">
                            <ul class="scrollSpy-elements scrollmenu">
                                @if(isset($categories) && count($categories))
                                    @foreach($categories as $k=>$category)
                                        <li class="nav-item p-2">
                                            <a class="nav-link filter_btn @if($k==0) active @endif" href="#category-{!! $category['id'] !!}">

                                                <span class="scrollSpy-text">{!! $category['name'] !!}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                @endif

                            </ul>
                        </nav>
                        <div data-spy="scroll" data-target="#navbar-example2" data-offset="0" data-method="position"  tabindex="1" class="pt-4 pb-4 spy_div">
                            @if(isset($categories) && count($categories))
                                @foreach($categories as $category)

                                    <div class="row" style="background-color: #f3f3f3;padding: 10px 10px 3px 10px;">
                                        <div class="col-10">
                                            <h5 class="" id="category-{!! $category['id'] !!}"><strong>{!! $category['name'] !!}</strong></h5>
                                        </div>

                                        <div class="col-2 text-center">
                                                <p class="fs-3 fw-bold"><strong>{!! count($category['recipes']) !!}</strong> </p>
                                        </div>

                                    </div>

                                    @if(isset($category['recipes']) && count($category['recipes']))
                                    <div id="categorys-{!! $category['id'] !!}" class="category-recipes">
                                        @foreach ($category['recipes'] as $key => $value)
                                            <div class="food-items-wrap row mt-2 position-relative ">
                                                @if(!$is_resto_opened)
                                                    <div class="recipe-overlay"></div>
                                                @endif
                                                <div class="col-8 d-flex justify-content-between @if($is_resto_opened) get-recipe_detail @endif p-0" data-recipe-id="{!! $value['shared_unique_id'] !!}">
                                                    <div class="d-flex justify-content-end">
                                                        <div>
                                                            <img  class="food-img food_img_list lazyload" data-iscustomize="<?php echo $value['is_customized'];?>" width="85px" height="85px" data-srcset="<?php echo $value['thumbnail'];?>">
                                                        </div>
                                                        <div class="food-item-name-category">
                                                            <p class="food-name"><b><?php echo $value['name'];?></b></p>
                                                            <p class="food-category custome_category">{!! mb_substr($value['description'],0,50,'utf-8') !!}</p>
                                                            <p class="food-price" data-price="{!! str_replace(',','',$value['price'] ) !!}">{!! number_format(str_replace(',','',$value['price'] ),2) !!}  د. ع  </p>
                                                        </div>
                                                    </div>
                                                </div>



                                            </div>
                                            @endforeach

                                    </div>
                                    @endif
                                    @endforeach
                                @endif

                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="view-basket">
        <div class="view-cart-wrap">
            <div class="pl-3">
                <span class="cart-item total-recipe-selected"></span>
                <span class="t-price" id="basket-price" ></span>
            </div>
            <div class="vc-name-image">
                <p class="next-page">
                    <span class="vc-name">  سلتي </span>
                    <i class="icofont-swoosh-left"></i>
                </p>
            </div>
        </div>
    </div>


</div>


    <div class="modal fade" id="delivery-options" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <h4 class="font-black text-lg leading-normal m-2 mx-4 font-weight-bolder mb-5">
                        التوصيل الى
                    </h4>

                    <div class="mx-4 d-flex">
                        <div class="w-100">
                            <ul class="list-inline">
                                <li class="list-inline-item"><img src="{!! env('APP_URL_ASSETS') !!}img/location.png" > </li>
                                <li class="list-inline-item"> <span id="pre-selected">Select Location</span></li>
                            </ul>

                        </div>
                        <div class="ml-auto">
                            <a href="#!" class="change-button" data-action="delivery">غير</a>
                        </div>

                    </div>
                    <hr class="mt-5 mb-5" />
                    <h4 class="font-black text-lg leading-normal m-2 mx-4 font-weight-bolder mb-5 mt-5">
                        وقت التوصيل
                    </h4>

                    <div class="mx-4 d-flex mb-5">
                        <div class="w-100">

                            @if($is_resto_opened)
                                <ul class="list-inline">
                                    <li class="list-inline-item"><i class="icofont-wall-clock"></i></li>
                                    <li class="list-inline-item"><span class="selected-time-slot">(اقرب وقت)</span></li>
                                </ul>


                                @else
                             Closed
                            @endif

                        </div>
                        <div class="ml-auto">
                            <a href="#!"  class="change-button" data-action="time">غير</a>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="time-options" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-body" style=" padding: 0;">

                    <ul class="list-inline today-tomorrow border-bottom">
                        <li class="list-inline-item active">Today</li>

                    </ul>


                    @if(isset($time_slots) && count($time_slots))
                        <div  class="list-group list-group-flush time-slots" style="max-height: 250px; overflow: auto;">
                            <a href="#" class="list-group-item list-group-item-action text-center">اقرب وقت</a>
                            @foreach($time_slots as $slot)
                                <a href="#" class="list-group-item list-group-item-action text-center">{!! $slot !!}</a>
                            @endforeach
                        </div>

                    @endif

                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="out-of-order" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-body text-center">

                    <h1 class="text-center mt-5 mb-3">You order is out of Delivery</h1>

                    <a class="btn btn-light">View Detail</a>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="recipe-detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-body p-0">

                </div>
                <div class="modal-footer add-to-basket my-custom-buttom">
                    <div class="row">
                        <div class="col-9 text-center">أضف
                            <span id="user-quantity" style="padding: 7px">1</span> الى سلتي  </div>
                        <div class="col-3 text-left "><small> <span id="user-price"></span> د. ع </small> </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endsection
@section('js')
<script>
    var selected_city = localStorage.getItem('selected_city');
    if(selected_city && selected_city!=""){
        $("#location").text(selected_city);
        $("#pre-selected").text(selected_city);
    }else{

       // window.location = "{!! env('APP_URL')."delivery/".\Illuminate\Support\Str::slug($resto_info['name']) !!}";
        $("#location").text("Select Location");
        $("#pre-selected").text("Select Location");
    }
    $(function () {

        @if(!$is_resto_opened)
                $("#out-of-order").modal();
        @endif


        localStorage.setItem('site_url','{!! env('APP_URL').'?id='.request()->get('id') !!}');

        $("body").on("click",".time-slots a",function () {
            var time = $(this).text();

            $("#time").html(time);
            $(".selected-time-slot").html(time);

            localStorage.setItem('selected_time',time);
            $("#time-options").modal('hide');
            $("#delivery-options").modal('hide');
        });




        var selected_time = localStorage.getItem('selected_time');
        var recent_selected_recipe_id = 0;
        var selected_recipe_array = [];
        var total_price = 0;
        var selected_recipe_object_array = [];
        var is_require = true;
        var is_basket_has_items = localStorage.getItem('cart');
        var is_new_resto = "{!! $new_resto !!}";
        var _total_quantity = 0;

        if(is_new_resto=="Yes"){
            localStorage.removeItem('cart');
            localStorage.removeItem('total_price');
            localStorage.removeItem('customer_id');
            localStorage.removeItem('mobile_number');
            localStorage.removeItem('access_token');
        }

        if(is_basket_has_items){

            var a = $.parseJSON(is_basket_has_items);
            if(a.length > 0){
var p_t = 0;
                $.each(a, function (b,c) {
                    selected_recipe_array.push(c.id);
                    selected_recipe_object_array.push(c);
                    var tt = parseInt(c.quantity) * parseInt(c.price);
                    _total_quantity+=parseInt(c.quantity);
                    //console.log(_total_quantity);
                 p_t+=tt;
                   // var tt = parseInt(quantity) * parseInt(price);
                   // console.log(recent_selected_recipe_id+":"+tt);

                });
                //  selected_recipe_object_array.push(a);

              //  var t = a.map(a => a.price).reduce((acc, a) => a + acc);
               // console.log(t);
                $("#basket-price").html(format(p_t.toFixed(2))+" د. ع ");
               // $(".total-recipe-selected").html(a.length);
                $(".total-recipe-selected").html(_total_quantity);
                $(".view-basket").show();
            }

        }

        $("body").on("click",".view-basket",function () {
            window.location = "{!! env('APP_URL')."basket/".\Illuminate\Support\Str::slug($resto_info['name']) !!}";
        })

        $("body").on("click",".get-recipe_detail",function () {

            var id = $(this).data('recipe-id');
            recent_selected_recipe_id = id;
            $("#recipe-detail").modal();

            $("#recipe-detail .modal-body").html(overlay);
            $("#recipe-detail .modal-footer").hide();

            $.ajax({
                url:"{!! env('APP_URL') !!}recipe",
                type:"POST",
                data:{
                    id:id,

                    "_token":"{!! csrf_token() !!}"
                },
                success:function (response) {
                    response = $.parseJSON(response);
                    var recipe = response.recipe;

                    var data = recipe_detail(recipe);
                    $("#recipe-detail .modal-body").html(data);
                    $("#user-price").html(recipe.price);
                    $("#user-quantity").html('1');
                    $("#recipe-detail .modal-footer").show();
                }
            });
        });

        $("body").on("click",".add-to-basket",function () {

            var extra_single_options = null;
            is_require = true;
            $(".is_required").each(function (i,v) {
              var _this = $(this);

              var amount = parseInt(_this.data("amount"));
              var per_click = parseInt(_this.attr("per-click"));

              if(per_click < amount){
                  is_require = false;
                  _this.find('.require').addClass('error');
              }else{
                  _this.find('.require').removeClass('error');
              }
            //    console.log(amount+"|"+is_require+"|"+per_click);
            });



            if(is_require){
                if(!selected_recipe_array.includes(recent_selected_recipe_id)){
                    selected_recipe_array.push(recent_selected_recipe_id);
                    var quantity =  $("#user-quantity").text();
                    var price = $(".item-price").data('price');
                    var tt = parseInt(quantity) * parseInt(price);

                    total_price+=(parseInt(tt));

                    var extra_single_options =  $( ".single_options:checked" )
                        .map(function() {
                            return this.value;
                        })
                        .get()
                        .join();

                    var ex = [];
                    var extra_multiple_options =  $( ".selected-checkbox-counter.active" );
                    if(extra_multiple_options){

                        $.each(extra_multiple_options,function (i,v) {
                            var _this_extra = $(this);
                            var x = {id:$(this).data('item-id'),quantity:$(this).attr('selected-counter')};
                            ex.push(x);
                        });
                    }

                    _total_quantity+=parseInt(quantity);
                   // console.log(_total_quantity);

                    var recipe = {id:recent_selected_recipe_id,quantity:quantity,price:price,single_options:extra_single_options,multiple_option:ex};

                    selected_recipe_object_array.push(recipe);
                }




             //   console.log(selected_recipe_object_array.length);
                localStorage.removeItem('cart');
                localStorage.setItem('cart',JSON.stringify(selected_recipe_object_array));
              //  console.log("Items in local storage");
              //  console.log(localStorage.getItem('cart'));

                $("#recipe-detail").modal('hide');
                $("#basket-price").html(format(total_price.toFixed(2))+" د. ع ");
               // $(".total-recipe-selected").html(selected_recipe_object_array.length);
                $(".total-recipe-selected").html(_total_quantity);

                $(".view-basket").show();
            }


        });





        if(selected_time && selected_time!=""){
            $("#time").text(selected_time);
            $(".selected-time-slot").text(selected_time);
        }else{
            $("#time").text("(اقرب وقت)");
            $(".selected-time-slot").text("(اقرب وقت)");
        }






        $("body").on("click",".adjust-location",function () {
            $("#delivery-options").modal();
        });

        $("body").on("click",".change-button",function (e) {
            var action = $(this).data("action");

            if(action=="delivery"){
            //    alert("d");
                window.location = "{!! env('APP_URL')."delivery/".\Illuminate\Support\Str::slug($resto_info['name']) !!}";
            }else if(action=="time"){
          //      alert("t");
                $("#time-options").modal();
            }
            e.stopPropagation();
            e.preventDefault();
        });

        $("body").on("click",".multi-select",function () {
            var _this = $(this);
            var _current_counter = _this.find('.selected-checkbox-counter').text();
            var _parent = _this.parents('.is_required');
            var _maximum_selection = parseInt(_parent.data('amount'));
            var _per_click = parseInt(_parent.attr('per-click'));

            if(_per_click>=_maximum_selection)
                return false;



            _per_click = _per_click +1;
            _parent.attr('per-click',_per_click);


            var _total_selection = 0;



            if(_current_counter=="")
                _current_counter = 1;
            else{
                _current_counter =  parseInt(_current_counter);
                _current_counter++;
            }

            _this.find('.selected-checkbox-counter').attr('selected-counter',_current_counter);
            _this.find('.selected-checkbox-counter').addClass('active');
            _this.find('.selected-checkbox-counter').text(_current_counter+"x");
            _this.find('.delete-selection').addClass('icofont-trash');
        });

        $("body").on("click",".single_options",function () {
            var _this = $(this);
            var _current_counter = _this.find('.selected-checkbox-counter').text();
            var _parent = _this.parents('.is_required');
            var _maximum_selection = parseInt(_parent.data('amount'));
            var _per_click = parseInt(_parent.attr('per-click'));

            if(_per_click>=_maximum_selection)
                return false;



            _per_click = _per_click +1;
            _parent.attr('per-click',_per_click);


            var _total_selection = 0;



            if(_current_counter=="")
                _current_counter = 1;
            else{
                _current_counter =  parseInt(_current_counter);
                _current_counter++;
            }

            _this.find('.selected-checkbox-counter').attr('selected-counter',_current_counter);
            _this.find('.selected-checkbox-counter').addClass('active');
            _this.find('.selected-checkbox-counter').text(_current_counter+"x");
            _this.find('.delete-selection').addClass('icofont-trash');
        });

        $("body").on("click",".delete-selection",function (e) {

            var  _this = $(this);
            _this.parents('.row-section').find(".selected-checkbox-counter").html('');
            _this.parents('.row-section').find(".selected-checkbox-counter").attr('selected-counter',"0");
            _this.find('.selected-checkbox-counter').removeClass('active');
            _this.removeClass('icofont-trash');
            var _parent = _this.parents('.is_required');
            _parent.attr('per-click',"0");
            e.preventDefault();
            e.stopPropagation();


        });

        $("body").on("click",".quantity-button",function () {
            var action = $(this).data('action');
            var current_quantity = parseInt($(".quantity").text());
            var current_price = parseInt($(".item-price").data('price'));
            if(action == "plus-button")
                current_quantity++;
            if(action == "minus-button" && current_quantity>1)
                current_quantity--;
            $(".quantity").html(current_quantity);
            $("#user-quantity").html(current_quantity);
            $("#user-price").html(format(current_quantity * current_price));
        });
    });
    function recipe_detail(recipe) {
        var str ='<div class="row mb-5">\n' +
            '                        <div class="col-12">\n' +
            '                            <button type="button" class="btn-close" data-dismiss="modal"  aria-label="Close"></button>\n' +
                '<div class="recipe-img" style="background-image:url('+recipe.main_image+')">' +
            ''+'</div>\n' +
            '                    </div>\n' +
            '\n' +
            '                    <div class="row p-1">\n' +
            '                        <div class="col-12">\n' +
            '                            <h3 style="margin:0" class="resto-title mt-2">'+recipe.name+'</h3>\n' +
            '                        </div>\n' +
            '                    </div>\n' +
            '\n' +
            '                    <div class="row  p-1">\n' +
            '                        <div class="col-12">\n' +
            '                            <p class="h5 fs-5 mt-2 fw-bolder item-price primary-text-color" data-price="'+recipe.price.replace(",","")+'">AED '+recipe.price+'</p>\n' +
            '                        </div>\n' +
            '                    </div>\n' +
            '\n' +
            '                    <div class="row  p-1">\n' +
            '                        <div class="col-12">\n' +
            '                            <p class="recipe-description">'+recipe.description+'</p>\n' +
            '                        </div>\n' +
            '                    </div>\n' +
            '\n' +
            '\n';
                $.each(recipe.extra_options,function (i,v) {
                    if(v.is_mandatory=="Yes") {
                        str += '<div  class="card is_required" data-card-id="'+v.id+'" per-click="0" data-amount="'+v.mandatory_amount+'">';
                    }else{
                        str += '<div  class="card">';
                    }
                            str+= '<div class="card-body"><div class="card-header p-3">'+v.name;
                            if(v.is_mandatory=="Yes"){
                                str+='<span class="float-end require"> '+'Choose at least '+v.mandatory_amount+' </span><br /><small></small>';
                            }
                                str+='</div>';
                          if(v.items){
                              str+=' <div  class="list-group list-group-flush">';
                              $.each(v.items,function (j,k) {
                                  if(k.item_type=="option"){
                                      str+='<label class="list-group-item chk_container"><div class="row row-section"><div class="col-2 text-center"></div> <div class="col-8">'+k.name+'</div><div class="col-2 text-center"> ' +
                                          '<input class="form-check-input me-1 single_options" name="single_option_'+v.id+'"  type="radio" value="'+v.id+'">' +
                                          '<span class="checkmark"></span></div></div>' +
                                          '</label>';
                                  }else{
                                      str+='<label class="list-group-item multi-select"><div class="row row-section"><div class="col-2 text-end"><span class="selected-checkbox-counter" data-item-id="'+k.id+'" selected-counter="0"></span> </div> <div class="col-8">'+k.name+'</div><div class="col-2 text-center"> ' +
                                          '<span class="delete-selection text-danger"></span> </div></div>' +

                                          '</label>';
                                  }

                              });
                              str+='</div >';

                          }
                            str+='</div></div>';
                })

            str+='\n' +
            '                    <div class="d-flex justify-content-center bd-highlight mb-3  plus-minus-button">\n' +
            '                        <div class="p-2 bd-highlight"><button class="quantity-button" data-action="plus-button"><i class="icofont-plus"></i> </button> </div>\n' +
            '                        <div class="p-2 bd-highlight"><span class="quantity">1</span> </div>\n' +
            '                        <div class="p-2 bd-highlight"><button class="quantity-button" data-action="minus-button"><i class="icofont-minus"></i></button> </div>\n' +
            '                    </div>';

        return str;
    }



    var format = function(num){
        var str = num.toString().replace("$", ""), parts = false, output = [], i = 1, formatted = null;
        if(str.indexOf(".") > 0) {
            parts = str.split(".");
            str = parts[0];
        }
        str = str.split("").reverse();
        for(var j = 0, len = str.length; j < len; j++) {
            if(str[j] != ",") {
                output.push(str[j]);
                if(i%3 == 0 && j < (len - 1)) {
                    output.push(",");
                }
                i++;
            }
        }
        formatted = output.reverse().join("");
        return("" + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
    };
</script>
    @endsection