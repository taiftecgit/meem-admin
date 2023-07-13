@extends('layouts.app')
@section('page-title')| Outlet - Address @endsection
@section('content')
    <link href="{!! env('APP_ASSETS') !!}/vendor_components/dropzone/dropzone.css" rel="stylesheet"/>
    <link href="{!! env('APP_ASSETS') !!}/vendor_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet"/>
    <style>
        .content{
            padding: 0;
        }
        .bootstrap-tagsinput {
            min-height: 60px; width: 100%;
        }
        h4{ margin-top: 40px}
        #map{
            width: 100%; height: 500px;
        }
        .form-control, .form-select {
            height: 46px !important;
            border-color: #E4E6EB !important;
            border-radius: 7px !important;
        }
        .vtabs .tab-content {
            display: block;}
        #pac-input{
            position: absolute;
            z-index: 9;
            top: 26px !important;
            right: 0px;
            left: 20px !important;
            width: 95%;
        }
        .search-location{
            position: absolute;
            right: -79px !important;
            top: 48px;
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
        @media (min-width: 820px) and (max-width: 1024px){
            html[dir="rtl"] .content-wrapper {
                width: 100% !important;
                margin-right: 0px !important;
                padding-left: 20px;
                padding-right: 10px;
                margin-top: 78px !important;
            }     
            html[dir="rtl"] .sidebar_div_main{
              padding-left: 0px;  
            }
        }
    </style>

    <!-- Content Wrapper. Contains page content -->
    @php
        $resto = \Illuminate\Support\Facades\Auth::user()->restaurants;

$lang = $resto->default_lang; 

app()->setLocale($lang);
if(session('app_lang') !==null){
$lang = session('app_lang');
app()->setLocale($lang);
}
        $address = isset($resto->places)?$resto->places->place_name:"Baghdad";

        if(!empty($outlet->address))
        $address = $outlet->address.', '.$outlet->place;
     //   dd($resto->places->place_name);
    @endphp
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
           {{-- <div class="content-header">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <h4 class="page-title">Outlets</h4>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{!! env('APP_URL') !!}dashboard"><i
                                                    class="mdi mdi-home-outline"></i></a></li>

                                    <li class="breadcrumb-item active" aria-current="page">Outlets</li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                </div>
            </div>--}}

            <!-- Main content -->
            <section class="content">
                <div class="row">

                    <div class="col-12 col-sm-4 sidebar_div_main" style="padding-right: 0;background-color: #F5F5F5">
                        @include('outlets.outlet-sidebar')
                    </div>
                    <div class="col-12 col-sm-8 p-15">
                        <form id="save-outlet" method="POST" action="{!! env('APP_URL') !!}save/addrss/outlet" enctype="multipart/form-data">
                            <input type="hidden" name="lat" value="" />
                            <input type="hidden" name="lng" value="" />
							<input type="hidden" name="address_google" value="" />
                            @csrf
                            <input type="hidden" value="{!! request()->get('o') !!}" name="unique_id" />

                            <div class="p-15">
                                <h4 style="margin-top: 0">{{__('label.address')}}</h4>
                                <div class="row position-relative">
                                    <div class="col-10">
                                        <div class="form-group position-relative">
<!--                                            <a href="#!" class="search-location"><i class="fa fa-location-arrow"></i> </a>-->
                                            <input  id="pac-input" class="form-control" type="text" placeholder="{{__('label.search_box')}}" />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div id="map"></div>
                                    </div>
                                </div>




                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>{{__('label.address')}}</label>
                                            <input type="text" class="form-control" name="address" value="{!! $outlet->address !!}"
                                                   required/>
                                        </div>
                                    </div>
                                </div>
								
								 <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>{{__('label.address_arabic')}}</label>
                                            <input type="text" class="form-control" name="address_arabic" value="{!! $outlet->address_arabic !!}"
                                                   required/>
                                        </div>
                                    </div>
                                </div>

<!--
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>{{__('label.address_area')}}</label>
                                            <input type="text" class="form-control" value="{!! empty($outlet->place)?(isset($resto->places)?$resto->places->place_name:"Baghdad"):$outlet->place !!}" name="area"
                                                   required/>
                                        </div>
                                    </div>
                                </div>
-->
								
								
								

                                <a href="#!" class="btn btn-primary save-changes mt-4">{{__('label.save_changes')}}</a>

                            </div>
                        </form>
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
        var marker_icon = "{!! env('APP_ASSETS') !!}images/marker.png";
        var uluru = null;
        var address = "{!! $address !!}";
        var marker = null;
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
                        zoom: 14,
                        overviewMapControl: false,
                        mapTypeControl: false,
                        streetViewControl: false,
                        fullScreenControl:false,
                        disableDefaultUI: true,
                    });
     $('<div/>').addClass('centerMarker').appendTo(map.getDiv())
                    /*marker = new google.maps.Marker({
                        position: uluru,
                        map: map,
                        icon:marker_icon,
                        draggable:true

                    });*/
                    map_center = map.getCenter();
                    google.maps.event.addListener(map, 'dragend', function(e) {
                          map_center = map.getCenter();
                        geocodePosition(map.getCenter());
                        var lat = map.getCenter().lat();
                        var lng = map.getCenter().lng();
                        $("input[name=lat]").val(lat);
                        $("input[name=lng]").val(lng);

                    } );
                    const input = document.getElementById("pac-input");
                    const searchBox = new google.maps.places.SearchBox(input);
                    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(input);
                    map.addListener("bounds_changed", () => {
                        searchBox.setBounds(map.getBounds());
                    });
                    let markers = [];
                    searchBox.addListener("places_changed", () => {
                        const places = searchBox.getPlaces();

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
                            /*marker.setMap(null);
                            marker = new google.maps.Marker({
                                map,
                                marker_icon,
                                title: place.name,
                                position: place.geometry.location,
                                draggable:true
                            });
                            markers.push(
                                marker
                            );
*/
                            if (place.geometry.viewport) {
                                // Only geocodes have viewport.
                                bounds.union(place.geometry.viewport);
                            } else {
                                bounds.extend(place.geometry.location);
                            }
                        });
                        google.maps.event.addListener(map, 'dragend', function(e) {
                              map_center = map.getCenter();
                            geocodePosition(map.getCenter());
                            var lat = map.getCenter().lat();
                            var lng = map.getCenter().lng();
                            $("input[name=lat]").val(lat);
                            $("input[name=lng]").val(lng);

                        } );
                        map.fitBounds(bounds);
                        map.setZoom(12);
                    });

                } else {
                  //  alert('Geocode was not successful for the following reason: ' + status);
                }
            });
            console.log(uluru);





        }


        function geocodePosition(pos) {
            geocoder.geocode({
                latLng: pos
            }, function(responses,status) {

                if (responses && responses.length > 0) {
                    console.log(responses[0]);
                    var address = (responses[0].formatted_address);
                    var area = (responses[0].address_components[1].long_name);
                        $("input[name=address_google]").val(address);
                   // $("input[name=area]").val(area);
                } else {
                 //   alert('Cannot determine address at this location.');
                }
            });
        }

        function codeAddress(address) {

            geocoder.geocode({ 'address': address }, function (results, status) {
              //  console.log(results);
                uluru = {lat: results[0].geometry.location.lat (), lng: results[0].geometry.location.lng ()};
             //   console.log (latLng);
                if (status == 'OK') {
                     marker = new google.maps.Marker({
                        position: uluru,
                        icon:marker_icon,
                        animation: google.maps.Animation.DROP,
                        map: map
                    });

                    marker.setMap(map);
                    //     console.log (map);
                } else {
                //    alert('Geocode was not successful for the following reason: ' + status);
                }
            });
        }

        $(document).on('pageshow', '#tab-content', function(e, data){
            initMap();
        });

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
                            google.maps.event.addListener(map, 'dragend', function(e) {
                                geocodePosition(map.getCenter());
                                var lat = map.getCenter().lat();
                                var lng = map.getCenter().lng();
                                $("input[name=lat]").val(lat);
                                $("input[name=lng]").val(lng);

                            } );
                        },
                        () => {
                            handleLocationError(true, infoWindow, map.getCenter());
                        }
                    );
                } else {
                    // Browser doesn't support Geolocation
                    handleLocationError(false, infoWindow, map.getCenter());
                }
            });

        $("body").on("click",".save-changes",function () {

            /*$.toast({
                heading: 'Welcome to my Riday Admin',
                text: 'Use the predefined ones, or specify a custom position object.',
                position: 'top-right',
                loaderBg: '#ff6849',
                icon: 'info',
                hideAfter: 3000,
                stack: 6
            });*/

            if($("#save-outlet").valid()){
                $("#save-outlet").ajaxForm(function (response) {

                    response = $.parseJSON(response);

                    if(response.type=="success"){
                        $.toast({
                            heading: 'Outlet Update.',
                            text: response.message,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 3000,
                            stack: 1
                        });

                        setTimeout(function () {
                           location.href.reload();
                        },2000);
                    }else{
                        $.toast({
                            heading: 'Outlet Update.',
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

 <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBFh6fzq8G7dgWLfz8kccvTlmPCSI_uWXQ&callback=initMapMeem&language=ar&libraries=places"></script>
@endsection
