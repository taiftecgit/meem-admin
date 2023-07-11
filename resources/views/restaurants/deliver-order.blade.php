@extends('layout.app')
@section('content')
    <style type="text/css">
        p,h4{
            direction: rtl;
        }
        .card{ margin-bottom: 2px !important; border: 0;box-shadow:none}
        .card .card-header{
            background: #f3f3f3;
            height: 46px;
            border: none;
        }
        .homepage-ads .item img
        {
            display: block;
            width: 100%;
            height:auto !important;
        }

        .address-label{
            font-size: 17px;
            font-weight: bold;
            margin: 0;
        }

        .adjust-adress,.change-button1{
            border: 1px solid #faf1e0;
            padding: 5px 10px;
            border-radius: 20px;
            background: #faf1e0;
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


        .img-div{
            max-height: 70px; overflow: hidden }
        @media (max-width: 575.98px)
        {
            .homepage-search-block {
                padding: 0rem 0 !important;
            }
        }
    </style>
<div class="mobile-container">
    <div class="card border-bottom">
        <h5 class="card-header p-4 text-center fw-bold">
            طلبك  <a href="#!" onclick="window.history.go(-1); return false;" class="back-button  icofont-simple-right float-start"></a>
        </h5>


    </div>
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
    <div id="order-section">
    <div class="row p-1" style="direction: ltr">
        <div class="col-12 mt-5">
            <h4 class="font-black text-lg leading-normal mx-4 fw-bold mb-2">
                التوصيل الى

            </h4>
            <div class="mx-4 d-flex mb-2" style="background: #f9f9f9; padding: 10px;border-radius: 6px;">
                <div class="p-2 bd-highlight"> <div class="ml-auto" style="margin-top: 10%;">
                        <a href="#!" class="adjust-adress" data-action="delivery">غير</a>
                    </div></div>
                <div class="w-100 p-2 bd-highlight" id="select-location" style="direction: rtl"> اختر عنوان</div>
                <div class="p-2 bd-highlight">  <img src="{!! env('APP_URL_ASSETS') !!}img/location.png" style="margin-top: -3px; margin-right: 8px"></div>

            </div>


            <h4 class="font-black text-lg leading-normal m-2 mx-4  fw-bold mb-1 mt-5">
                وقت التوصيل

            </h4>
            <div class="mx-4 d-flex mb-2" style="background: #f9f9f9; padding: 10px;border-radius: 6px;">
                <div class="p-2 bd-highlight"> <div class="ml-auto" style="margin-top: 10%;">
                        <a href="#!" class="change-button1" data-action="time">غير</a>
                    </div></div>
                <div class="w-100 p-2 bd-highlight" style="margin-top: 2px;direction: rtl" id="select-time"> اقرب وقت</div>

                <div class="p-2 bd-highlight">  <i class="icofont-wall-clock primary-text-color" style="font-size: 20px"></i> </div>
            </div>

        </div>
    </div>
    <div class="card p-3" id="price-section" style="margin-top: 130px">



        <div class="row mb-2" style="padding-right: 10px">
            <div class="col-8 fs-4">قيمة الطلب


              </div>
            <div class="col-4 fs-4 basket-total"> <span></span>  د. ع </div>
        </div>

        <div class="row mb-4" style="padding-right: 10px">
            <div class="col-8 fs-4">قيمة التوصيل
            </div>
            <div class="col-4 fs-4"> <span id="delivery-fee"></span>  د. ع  </div>
        </div>

        <div class="row mt-2" style="padding-right: 10px">
            <div class="col-8 fs-4 fw-bolder">المبلغ الكلي


            </div>
            <div class="col-4 fs-4 fw-bolder"> <span class="grand-total"></span>  د. ع  </div>
        </div>

    </div>
    </div>


</div>

    <div class="my-custom-buttom  place-order " style="display: block">
        <div class="row">

            <div class="col-12 text-center"><span class="view-basket-label">ارسل طلبك</span> </div>

        </div>
    </div>

</div>

    <div class="modal fade" id="address-options" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-body">


                    <div id="customer-address">

                    </div>

                    <div class="d-flex">

                        <div class="flex-grow-1 ms-3 p-2">
                            <div class="row">
                                <div class="col-10"><a href="{!! env('APP_URL') !!}new/address"> اضف عنوان جديد


                                    </a> </div>
                                <div class="col-2 text-center fw-bold fs-2"><i class="icofont-rounded-left"></i></div>
                            </div>

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

    @endsection
@section('js')
<script>
    var label_array = [];
    var home = 0;
    var office = 0;
    var other = 0;
    $(function () {

        var customer_id = localStorage.getItem('customer_id');
        var customer_name = localStorage.getItem('customer_name');
       // alert(customer_name);
        var is_basket_has_items = localStorage.getItem('cart');
        var mobile_number = localStorage.getItem('mobile_number');
        var time             = localStorage.getItem('selected_time');
        var selected_area = localStorage.getItem('selected_city');
        var _has_address = false;
        var _total_price = 0;



        if(!is_basket_has_items || is_basket_has_items==""){
            window.location = localStorage.getItem('site_url');
            return false;
        }
      //  $("#address-options").modal();

        if(time && time!=""){
            $("#select-time").text(time);

        }else{
            $("#select-time").text("(اقرب وقت)");
        }
        $("body").on("click",".change-button1",function (e) {
            var action = $(this).data("action");


            if(action=="time"){

                $("#time-options").modal();
            }

            e.preventDefault(); e.stopPropagation();
        });

        $("body").on("click",".place-order",function () {
            var order_instructions = localStorage.getItem('order_instructions');
            $.ajax({
                url:"{!! env('APP_URL') !!}order/confirmed",
                type:"POST",
                data:{
                    cart:is_basket_has_items,
                    total_price:_total_price,
                    mobile_number:mobile_number,
                    customer_name,customer_name,
                    customer_id,customer_id,
                    order_instructions:order_instructions,
                    time:time,
                    selected_area:selected_area,
                    "_token":"{!! csrf_token() !!}"
                },success:function (response) {
                    response = $.parseJSON(response);

                    if(response.type=="success"){
                        localStorage.removeItem('cart');
                        localStorage.removeItem('total_price');
                        localStorage.removeItem('customer_id');
                        localStorage.removeItem('mobile_number');
                        localStorage.removeItem('selected_time');
                        //localStorage.removeItem('selected_city');
                        window.location = "{!! env('APP_URL') !!}thankyou?order="+response.order_id+"&ref="+response.order_ref;



                    }
                }
            });
        });

        if(customer_id!=""){
            $.ajax({
                url:"{!! env('APP_URL') !!}customer/address",
                type:"POST",
                data:{
                    customer_id:customer_id,
                    "_token":"{!! csrf_token() !!}"
                },
                success:function (response) {

                    response = $.parseJSON(response);

                    if(response && response.type=="success"){
                        var addresses = response.data;


                        $.each(addresses,function (i,address) {
                            if(i!="count_labels"){
                                _has_address = true;
                                var a = address_template(address,addresses['count_labels']);
                                if(address.is_set=="1"){
                                    $("#select-location").html('<span  class="fw-bold">'+address.label+'</span><br />\n' +
                                        '                    <span >'+address.address+'</span>');
                                    localStorage.setItem('delivery_fee',address.delivery_fee);
                                }
                                $("#customer-address").append(a);
                            }

                        });

                        if(_has_address)
                            $(".review-order").show();
                    }

                }
            });
        }


        var vat =  localStorage.getItem('delivery_fee');
        $("#delivery-fee").html(parseInt(vat).toFixed(2));
        if($.parseJSON(is_basket_has_items).length > 0){
            var orders = $.parseJSON(is_basket_has_items);

            var t = orders.map(orders => orders.id).join(',');


            $.ajax({
                url:"{!! env('APP_URL') !!}get/recipes",
                type:"POST",
                data:{
                    "_token":"{!! csrf_token() !!}",
                    recipes:JSON.stringify(orders),

                },
                success:function (response) {
                    $("#view-orders").html('');
                    response = $.parseJSON(response);

                    if(response){
                        $.each(response.recipe,function (i,v) {
                            var order = v;
                            _total_price+= (parseInt(order.price_in_number) * parseInt(order.requested_quantity));
                        });

                        $(".basket-total").html(format(_total_price+ "  د. ع   "));
                        $(".grand-total").html(format((_total_price+parseInt(vat)).toFixed(2)));
                        localStorage.setItem('total_price',format((_total_price+parseInt(vat)).toFixed(2)));
                    }
                }
            });
            /* var t = a.map(a => a.price).reduce((acc, a) => a + acc);
             $("#basket-price").html(format(t));
             $(".total-recipe-selected").html(a.length);
             $(".view-basket").show();*/
            /*if(orders){
                $.each(orders,function (i,v) {
                    var order = order_template(order);
                    $("#view-orders").append(order);
                })
            }*/
        }
        $("body").on("click",".adjust-adress",function () {


            $("#address-options").modal();
            return false;
        });

        $("body").on("click",".address",function () {
            var address_id = $(this).data('address-id');
            var customer_id = localStorage.getItem('customer_id');
            var delivery_fee = $(this).data('fee');
            var _this = $(this);


            $.ajax({
                url:"{!! env('APP_URL') !!}set/customer/address",
                type:"POST",
                data:{
                    address_id:address_id,
                    customer_id:customer_id,
                    "_token":"{!! csrf_token() !!}"
                },
                success:function (response) {
                    var label = _this.find('.address-label').text();
                    var address = _this.find('small').text();

                    $("#select-location").html('<span  class="fw-bold">'+label+'</span><br />\n' +
                        '                    <span >'+address+'</span>');
                    localStorage.setItem('delivery_fee',delivery_fee);
                    $("#delivery-fee").html(parseFloat(delivery_fee).toFixed(2));
                    var basket_price = parseFloat($(".basket-total").html().replace('د. ع'));
                   var total_price = delivery_fee+parseFloat(basket_price);
                    $(".grand-total").html(format(total_price.toFixed(2)));
                    localStorage.setItem('total_price',format((total_price).toFixed(2)));
                    $(".check_address").hide();
                    $(".delete_address").show();
                //    _this.find(".delete_address").hide();
                    _this.find(".check_address").show();
                    $("#address-options").modal('hide');
                }
            });
        });

       /* $("body").on("click",".list-group-item-action",function () {
            var city = $(this).find('p').text();
            localStorage.setItem('selected_city',city);
            window.history.back();
        });*/


       $("body").on("click",".delete-address",function (e) {
           var address_id = $(this).data('id');
           var _this = $(this);

               $.ajax({
                   url:"{!! env('APP_URL') !!}delete/customer/address",
                   type:"POST",
                   data:{
                       address_id:address_id,
                        "_token":"{!! csrf_token() !!}"
                   },
                   success:function (response) {
                       _this.parents('.address').remove();
                   }
               });

           e.preventDefault();
           e.stopPropagation();
       });

        $("body").on("click",".time-slots a",function () {
             time = $(this).text();

            $("#select-time").html(time);

            localStorage.setItem('selected_time',time);
            $("#time-options").modal('hide');
        });

        $(".search-area input").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".list-group a").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        $("body").on("click",".delivery-action-buttons",function () {
            var action = $(this).data('action');
            if(action=="delivery"){
                $("#for-delivery").addClass('active');
                $("#for-pickup").removeClass('active')
            }

            if(action=="pickup"){
                $("#for-delivery").removeClass('active');
                $("#for-pickup").addClass('active')
            }

        });

    });

    function address_template(address,count_labels) {

      //  console.log(address);
        var img = "{!! env('APP_URL_ASSETS') !!}img/location.png";

        var label  = "";

        if(address.label=="البيت"){
            if(parseInt(count_labels.البيت) > 1){
              home++;
              label = address.label+" "+home;
            }else{
                label = address.label;
            }
        }

        if(address.label=="العمل"){
            if(parseInt(count_labels.العمل) > 1){
               office++;
                label = address.label+" "+office;
            }else{
                label = address.label;
            }
        }

        if(address.label=="اخرى"){
            if(parseInt(count_labels.اخرى) > 1){
               other++;
                label = address.label+" "+other;
            }else{
                label = address.label;
            }
        }

    var str = ' <div class="d-flex address mb-3 pb-3  border-bottom" data-fee="'+address.delivery_fee+'" data-address-id="'+address.id+'">\n' +
        '                            <div class="flex-shrink-0">\n' +
        '                                <img src="'+img+'" alt="...">\n' +
        '                            </div>\n' +
        '                            <div class="flex-grow-1 ms-3">\n' +
            '<p class="address-label">'+label+'</p>' +
        '<small> '+address.address+'</small>' +
        '                            </div>\n ';
    if(address.is_set==0){
        str+='<div class="ms-auto p-2 bd-highlight check_address" style="display: none;" ><a href="#!" class="" data-id="'+address.id+'"> <i class="icofont-check fs-1 text-success position-relative top-50"></i></a> </div>';

        str+='<div class="ms-auto p-2 bd-highlight delete_address"><a href="#!" class="delete-address" data-id="'+address.id+'"> <i class="icofont-trash fs-2 position-relative top-50"></i></a> </div>';
         }else{
        str+='<div class="ms-auto p-2 bd-highlight check_address" ><a href="#!" class="" data-id="'+address.id+'"> <i class="icofont-check fs-1 text-success position-relative top-50"></i></a> </div>';
        str+='<div class="ms-auto p-2 bd-highlight delete_address"><a href="#!" class="delete-address" data-id="'+address.id+'"> <i class="icofont-trash fs-2 position-relative top-50"></i></a> </div>';


    }
      //  str+='<div class="ms-auto p-2 bd-highlight"><a href="#!" class="delete-address" data-id="'+address.id+'"> <i class="icofont-check fs-1 text-success position-relative top-50"></i></a> </div>';
       str+= '                        </div>';

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