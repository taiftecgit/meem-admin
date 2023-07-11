@extends('layouts.app')
@section('content')
    <link href="{!! env('APP_ASSETS') !!}/vendor_components/dropzone/dropzone.css" rel="stylesheet"/>
    <link href="{!! env('APP_ASSETS') !!}/vendor_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet"/>
    <link href="{!! env('APP_ASSETS') !!}/css/jquery.timepicker.min.css" rel="stylesheet"/>


    <!-- Content Wrapper. Contains page content -->
    @php
        $resto = \App\Models\Restaurants::find(\App\Helpers\CommonMethods::getRestuarantID());

		$lang = $resto->default_lang;

app()->setLocale($lang);
if(session('app_lang') !==null){
$lang = session('app_lang');
app()->setLocale($lang);

}
        //$resto = \Illuminate\Support\Facades\Auth::user()->restaurants;

        $address = isset($resto->places)?$resto->places->place_name:"Baghdad";
        $polygons = isset($resto->places)?$resto->places->coordinates:NULL;


        if(!empty($outlet->address))
        $address = $outlet->address.', '.$outlet->place;
     //   dd($resto->places->place_name);
    @endphp
      @php
    $restuarant1 = $resto;
        $resto_meta = isset($restuarant1->resto_metas)?$restuarant1->resto_metas:null;
        if(isset($_GET['debug']))
        dump($resto_meta);

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

            $outlet_meta = isset($outlet->resto_metas)?$outlet->resto_metas:NULL;
$outlet_metas = [];
if(isset($outlet_meta)){
                foreach($outlet_meta as $meta){

                      $index_name = isset($meta->resto_meta_defs->parents)?$meta->resto_meta_defs->parents->meta_def_name:$meta->resto_meta_defs->meta_def_name;


                  //  dump($meta->resto_meta_defs);
                    if($index_name=="BILLING_GATEWAY"){
                   //     dump($meta->resto_meta_defs->meta_def_name);
                     // $resto_metas['BILLING_GATEWAY'][] = $meta->meta_val;
                        $billing[] = array('id'=>$meta->meta_id,'value'=>$meta->meta_val);
                    }
                    $outlet_metas[$index_name] = $meta->meta_val;
                }
            }




             $currency = isset($outlet_metas['BUSSINESS_CCY'])?$outlet_metas['BUSSINESS_CCY']:$resto_metas['BUSSINESS_CCY'];

@endphp
 <style>
        .vtabs .tabs-vertical {
            width: 229px;
        }
        .bootstrap-tagsinput {
            min-height: 60px; width: 100%;
        }
        h4{ margin-top: 40px}
        .bootstrap-timepicker-widget table td input{ width: 46px}
        #map{
            width: 100%; height: 100vh;
        }
	 @if(app()->getLocale()!="en")
        .delivery-section{
            width: 400px;
            height: auto;
            background: white;
            position: absolute;
            left: 10px; top: 40px;
            z-index: 10; padding: 10px;
        }
	 @else
		 .delivery-section{
            width: 400px;
            height: auto;
            background: white;
            position: absolute;
            right: 10px; top: 40px;
            z-index: 10; padding: 10px;
        }
		 @endif

        .input-group input[type=text]{
            border-left: 0; padding-left: 0;
        }
        #pac-input{
            position: absolute;
            z-index: 9;
            top: 26px !important;
            right: 20px !important;
            width: 300px;
            left: inherit !important;
        }
        .search-location{
            position: absolute;
            right: 33px;
            top: 28px;
            z-index: 88;
            font-size: 20px;
        }
        .centerMarker{
          position:absolute;
          /*url of the marker*/
          background:url({!! env('APP_ASSETS') !!}images/marker.png) no-repeat;
          /*center the marker*/
          top:49%;left:47%;

          z-index:1;
          /*fix offset when needed*/
          margin-left:-10px;
          margin-top:-34px;
          /*size of the image*/
          height:40px;
          width:37px;

          cursor:pointer;
        }
    </style>
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->


            <!-- Main content -->
            <section class="content">
                <div class="row">
					  <div class="col-12 col-sm-3 sidebar_div_main" style="padding-right: 0;background-color: #F5F5F5">
                        @include('outlets.outlet-sidebar')
                    </div>


                    <div class="col-12 col-sm-9 p-15">
                         <div class="delivery-section">
                                            <h3>{{__('label.add_delivery_area')}}</h3>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label>{{__('label.delivery_area_name')}}</label>
                                                        <input type="text" value="{!! isset($area)?$area->area_name:"" !!}" class="form-control" name="area_name" required/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label>{{__('label.min_basket_value')}}</label>
                                                        <div class="input-group" >

                                                            <div class="input-group-addon" style="font-size: 13px;position: relative;padding-top: 9px;">
                                                                {!! $currency !!}
                                                            </div>
                                                        <input type="text" value="{!! isset($area)?$area->min_price:"0" !!}" class="form-control" name="min_basket" required/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label>{{__('label.delivery_fee')}}</label>
                                                        <div class="input-group">

                                                            <div class="input-group-addon"  style="font-size: 13px;position: relative;padding-top: 9px;">
                                                                 {!! $currency !!}
                                                            </div>
                                                            <input type="text" value="{!! isset($area)?$area->delivery_fee:"0" !!}" class="form-control" name="delivery_fee" required/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <button class="btn btn-primary save-delivery-area">{{__('label.save_delivery_area')}}</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 position-relative">
                                                <div class="position-relative" >
                                                    <div class="form-group position-relative">
<!--                                                        <a href="#!" class="search-location"><i class="fa fa-location-arrow"></i> </a>-->
                                                        <input
                                                                id="pac-input"
                                                                class="form-control"
                                                                type="text"
                                                                placeholder="Search Box"
                                                        />
                                                    </div>
                                                </div>
                                                <div id="map"></div>
                                            </div>
                                        </div>
                        <!-- /.box -->
                    </div>
                </div>
            </section>
            <!-- /.content -->

        </div>
    </div>
    <!-- /.content-wrapper -->



@endsection

@section('js')

<script>
    let map;
    var uluru = { lat: 33.325, lng: 44.422 };
    var marker_icon = "{!! env('APP_ASSETS') !!}images/marker.png";
    var address = "{!! $address !!}";
    window.existingArea = {!! !empty($polygons)?$polygons:"1" !!};
    var distance_plus = 0.2;
    var distance_minus = 0.2;
    var polygons = null;
    var redCoords = null;
    var marker=null;
    var map_center = null;
    function initMapMeem() {
        geocoder = new google.maps.Geocoder();
        //codeAddress(address);

        geocoder.geocode({ 'address': address }, function (results, status) {
            //  console.log(results);
            uluru = {lat: results[0].geometry.location.lat (), lng: results[0].geometry.location.lng ()};
            //   console.log (latLng);
            if (status == 'OK') {

                map = new google.maps.Map(document.getElementById("map"), {
                    center: uluru,

                    zoom: 12,
                    disableDefaultUI: true,
                });
               //  $('<div/>').addClass('centerMarker').appendTo(map.getDiv())
                /*marker = new google.maps.Marker({
                    position: uluru,
                    map: map,
                    icon:marker_icon,
                    draggable:true
                });*/

                map_center = map.getCenter();
                @if(isset($area))
                    redCoords = {!! str_replace('"','',$area->lat_lag) !!};
                @else
                    /*redCoords = [
                    { lat: 33.3332, lng: 44.4283 },//North
                    { lat: 33.2722, lng: 44.4283 },//sound

                    { lat: 33.2722, lng: 44.3283 },//sound
                    { lat: 33.3332, lng: 44.3283 },//North


                ];*/
                    var lat_1 = uluru.lat;
                    var lat_2 = parseFloat(uluru.lat) - 0.0610000;
                    var lat_3 = lat_2;
                    var lat_4 = lat_1;

                    var lng_1 = uluru.lng;
                    var lng_2 = lng_1;
                    var lng_3 = parseFloat(uluru.lng) - 0.1000000;
                    var lng_4 = lng_3;
                    redCoords = [
                    { lat: lat_1, lng: lng_1 },//North
                    { lat: lat_2, lng: lng_2 },//sound

                    { lat: lat_3, lng: lng_3 },//sound
                    { lat: lat_4, lng: lng_4 },//North


                ];
                @endif
                // Construct a draggable red triangle with geodesic set to true.
                polygons=  new google.maps.Polygon({
                    map,
                    paths: redCoords,
                    strokeColor: "#FF0000",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: "#FF0000",
                    fillOpacity: 0.35,
                    draggable: true,
                    editable:true,
                    geodesic: true,
                });

                @if(isset($areas) && $areas->count() > 0)
                        @foreach($areas as $a)

                    polygonss=  new google.maps.Polygon({
                    map,
                    paths: {!! str_replace('"','',$a->lat_lag) !!},
                    strokeColor: "#616161",
                    strokeOpacity: 0.5,
                    strokeWeight: 1,
                    fillColor: "#616161",
                    fillOpacity: 0.35,
                    draggable: false,
                    editable:false,
                    geodesic: true,
                });

                @endforeach
                    @endif

                const input = document.getElementById("pac-input");
                const searchBox = new google.maps.places.SearchBox(input);
                map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
                map.addListener("bounds_changed", () => {
                    searchBox.setBounds(map.getBounds());
                });
                let markers = [];
                searchBox.addListener("places_changed", () => {
                    const places = searchBox.getPlaces();
					//console.log("places");
					//console.log(places);

                    if (places.length == 0) {
                        return;
                    }
                    // Clear out the old markers.
                    markers.forEach((marker) => {
                        marker.setMap(null);
                    });
                    markers = [];
                    // For each place, get the icon, name and location.
                    const bounds = new google.maps.LatLngBounds();
					geocoder.geocode({
							'address': places[0].formatted_address
       			 }, function (results, status) {
						 var sw = results[0].geometry.bounds.getSouthWest();
                		 var ne = results[0].geometry.bounds.getNorthEast();

						//console.log('sw');console.log(sw);
						var padding = 0.000000001;
							var boxCoords = [
								new google.maps.LatLng(ne.lat()+padding, ne.lng()+padding),
								new google.maps.LatLng(ne.lat()+padding, sw.lng()-padding),
								new google.maps.LatLng(sw.lat()-padding, sw.lng()-padding),
								new google.maps.LatLng(sw.lat()-padding, ne.lng()+padding),
								new google.maps.LatLng(ne.lat()+padding, ne.lng()+padding)
							];

						polygons.setMap(null);

						  polygons=  new google.maps.Polygon({
							map,
							paths: boxCoords,
							strokeColor: "#FF0000",
							strokeOpacity: 0.8,
							strokeWeight: 2,
							fillColor: "#FF0000",
							fillOpacity: 0.35,
							draggable: true,
							editable:true,
							geodesic: true,
						});
					});
                    places.forEach((place) => {
                        if (!place.geometry || !place.geometry.location) {
                            console.log("Returned place contains no geometry");
                            return;
                        }
                        const icon = {
                            url: place.icon,
                            size: new google.maps.Size(71, 71),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(17, 34),
                            scaledSize: new google.maps.Size(25, 25),
                        };
                        // Create a marker for each place.
                       /* marker = new google.maps.Marker({
                            map,
                            marker_icon,
                            title: place.name,
                            position: place.geometry.location,

                            draggable:true
                        });

                        markers.push(
                            marker
                        );*/

                        if (place.geometry.viewport) {
                            // Only geocodes have viewport.
                            bounds.union(place.geometry.viewport);
                        } else {
                            bounds.extend(place.geometry.location);
                        }
                    });
                    google.maps.event.addListener(map, 'dragend', function(e) {
                          map_center = map.getCenter();
                        //geocodePosition(map.getCenter());
                        var lat = map.getCenter().lat();
                        var lng = map.getCenter().lng();
                        $("input[name=lat]").val(lat);
                        $("input[name=lng]").val(lng);

                    } );
                    map.fitBounds(bounds);
                    map.setZoom(12);
                });

            } else {
                alert('{{__("label.geocode_error")}}: ' + status);
            }
        });

        // console.log(uluru);






    }



    function codeAddress(address) {

        geocoder.geocode({ 'address': address }, function (results, status) {
            //  console.log(results);
            uluru = {lat: results[0].geometry.location.lat (), lng: results[0].geometry.location.lng ()};
            //   console.log (latLng);
            if (status == 'OK') {
               /* var marker = new google.maps.Marker({
                    position: uluru,
                    animation: google.maps.Animation.DROP,
                    icon:marker_icon,
                    map: map
                });

                marker.setMap(map);*/
                //     console.log (map);
            } else {
                alert('{{__("label.geocode_error")}}: ' + status);
            }
        });
    }
$(function () {

    $("body").on("click",".search-location",function () {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };
                    marker.setMap(null);
                    marker = new google.maps.Marker({
                        position: pos,
                        map: map,
                        icon:marker_icon,
                        draggable:true

                    });
                    map.setCenter(pos);
                      map_center = map.getCenter();
                    map.setZoom(12);
                    google.maps.event.addListener(marker, 'dragend', function(e) {
                        geocodePosition(marker.getPosition());
                        var lat = marker.getPosition().lat();
                        var lng = marker.getPosition().lng();
                        $("input[name=lat]").val(lat);
                        $("input[name=lng]").val(lng);


                    } );
                },
                () => {
               //     handleLocationError(true, infoWindow, map.getCenter());
                }
            );
        } else {
            // Browser doesn't support Geolocation
          //  handleLocationError(false, infoWindow, map.getCenter());
        }
    });

    $("body").on("click",".save-delivery-area",function () {
		var _this = $(this);

		_this.attr('disabled','disabled');
        var coordinates =polygons.getPath().getArray();

        var area_name = $("input[name=area_name]").val();
        var min_basket = $("input[name=min_basket]").val();
        var delivery_fee = $("input[name=delivery_fee]").val();
        if(area_name==""){
            $("input[name=area_name]").focus();return;
        }


        if(min_basket==""){
            $("input[name=min_basket]").focus();return;
        }

        if(delivery_fee==""){
            $("input[name=delivery_fee]").focus();return;
        }






        coordinates = (JSON.stringify(coordinates));

        $.ajax({
            url:"{!! env('APP_URL') !!}save/outlet/area",
            type:"POST",
            data:{
                coordinates:coordinates,
                area_name:area_name,
                min_basket:min_basket,
                delivery_fee:delivery_fee,
                center_radius:uluru,
                unique_id:"{!! $outlet->unique_key !!}",
                "_token":"{!! csrf_token() !!}",
                id:"{!! isset($area)?$area->id:0 !!}"

            },success:function (response) {
                response = $.parseJSON(response);

                if(response.type=="success"){
                    $.toast({
                        heading: '{{__("label.outlet_area_update")}}',
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
                        heading: '{{__("label.outlet_area_update")}}',
                        text: response.message,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 3000,
                        stack: 1
                    });
                }
            }
        });


    });




})
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBFh6fzq8G7dgWLfz8kccvTlmPCSI_uWXQ&callback=initMapMeem&libraries=drawing,places&language=ar"></script>
@endsection
