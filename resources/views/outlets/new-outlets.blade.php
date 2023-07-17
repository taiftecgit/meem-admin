@extends('layouts.app')
@if(isset($outlet))
@section('page-title')| {!! isset($outlet)?$outlet->name:"" !!}@endsection
@else
@section('page-title')| New Outlet  @endsection
@endif

@section('content')
    <link href="{!! env('APP_ASSETS') !!}/vendor_components/dropzone/dropzone.css" rel="stylesheet"/>
    <link href="{!! env('APP_ASSETS') !!}/vendor_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet"/>

    <link href="{!! env('APP_ASSETS') !!}vendor_components/select2/dist/css/select2.min.css" rel="stylesheet">
<!--

    <link href="{!! env('APP_ASSETS') !!}css/outlets-inner-section.css?v=1" rel="stylesheet" type="text/css">
-->
    <style>

        body {
            padding: 0px !important;
        }
        .vtabs .tabs-vertical {
            width: 229px;
        }
        .bootstrap-tagsinput {
            min-height: 46px; width: 100%;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
        h4{ margin-top: 40px}
       .select2-container{
        width: 100% !important;
       }
    </style>
    <style type="text/css">
        .bootstrap-tagsinput{
            border: 1px solid #E4E6EB;
        }

        .bootstrap-tagsinput .label-info{
            background-color: #ffab00 !important;
        }
        .theme-primary [type=checkbox].filled-in:checked.chk-col-primary + label:after{
            border: 1px solid #ffab00;
            background-color: #ffab00 !important;
        }
		.form-control, .form-select {
            height: 46px !important;
            border-color: #E4E6EB !important;
            border-radius: 7px !important;
        }
        #image-preview {
            width: 100%;
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

             .select2-container--default .select2-selection--single{
                        height: 40px !important;
                         border-color: #E4E6EB !important;
                        border-radius: 7px !important;
                        padding: 9px 12px;
                }

            @media (min-width: 850px) and (max-width:  1020px)
            {
              .content-wrapper 
              {
                width: calc(100% - 1px) !important;
              }

              .fixed .content-wrapper
              {
                margin-top: 80px !important;
                padding: 0 !important;
              }
            }
            .sidebar_div_main{
                padding-left: 8px;
            }
            html[dir="rtl"] .sidebar_div_main {
                padding-right: 6px !important;
                padding-left: 0;
            }
    </style>
    <!-- Content Wrapper. Contains page content -->
@php
$resto = \App\Models\Restaurants::find(\App\Helpers\CommonMethods::getRestuarantID());
$lang = $resto->default_lang;

app()->setLocale($lang);
if(session('app_lang') !==null){
$lang = session('app_lang');
app()->setLocale($lang);

}
@endphp
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
           {{-- <div class="content-header">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <h4 class="page-title">{{__('label.outlets')}}</h4>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{!! env('APP_URL') !!}dashboard"><i
                                                    class="mdi mdi-home-outline"></i></a></li>

                                    <li class="breadcrumb-item active" aria-current="page">{{__('label.outlets')}}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                </div>
            </div>--}}

            <!-- Main content -->
            <section class="content">
                <div class="row ">
                   <div class="col-12 col-sm-4 sidebar_div_main" style="padding-right: 0;background-color: #F5F5F5">
                        @include('outlets.outlet-sidebar')
                    </div>
                    <div class="col-12 col-sm-8 p-15">
                        <form id="save-outlet" method="POST" action="{!! env('APP_URL') !!}save/outlet" enctype="multipart/form-data">

                            <input type="hidden" name="id" value="{!! isset($outlet)?$outlet->id:"" !!}" />
                            @php
                             //$resto = \App\Restaurants::find(\App\Helpers\CommonMethods::getRestuarantID());
                           // $image = null;
                            //    if(isset($outlet)){
                            //       $image = \App\Photos::where('resto_id',\App\Helpers\CommonMethods::getRestuarantID())->where('branch_id',$outlet->id)->first();

                             //   }

                            @endphp
                            @csrf
                            <div class="outlet-info-section">
<!--
                                <h2 style="margin-top: 0">{{__('label.basic_information')}}</h2>
                                <p style="font-size: 14px">{{__('label.cover_image')}}</p>
                                <div id="image-preview" @if(isset($image) && !empty($image->file_name)) style="background: url({!! $image->file_name !!})" @endif>
                                    <label for="image-upload" id="image-label">Choose File</label>
                                    <input type="file" name="image" id="image-upload" />
                                </div>
                                <p class="text-danger text-end">{{__('label.max_upload_notes')}}</p>
-->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>{{__('label.name')}}</label>
                                            <input type="text" value="{!! isset($outlet)?$outlet->name:"" !!}" class="form-control" name="outlet_name"
                                                   required/>
                                        </div>
                                        <div class="form-group">
                                            <label>{{__('label.arabic_name')}}</label>
                                            <input style="direction: rtl" type="text" value="{!! isset($outlet)?$outlet->outlet_arabic_name:"" !!}" class="form-control" name="outlet_arabic_name"
                                                   />
                                        </div>

                                        <h4>{{__('label.contact_information')}}</h4>
                                        <div class="form-group">
                                            <label>{{__('label.email')}}</label> <br />
                                            <input type="text" placeholder="{{__('label.valid_email_note')}}" name="email" value="{!! isset($outlet)?$outlet->email:"" !!}" class="form-control" data-role="tagsinput" />
                                        </div>

                                        <div class="form-group">
                                            <label>{{__('label.phone')}}</label> <br />
                                            <input type="text" class="form-control" name="phone" value="{!! isset($outlet)?$outlet->phone_number:"" !!}" required />
                                        </div>

										<div class="form-group">
                                            <label>{{__('label.whatsapp')}}</label> <br />
                                            <input type="text" class="form-control" name="whatsapp_number" value="{!! isset($outlet)?$outlet->whatsapp_number:"" !!}" required />
                                        </div>

                            @php
                                $countries = \App\Models\Countries::whereNull('deleted_at')->get();

                            @endphp

                                    <div class="form-group">
                                        <label>{{__('label.country')}}</label> <br />
                                        <select class="form-select" required name="country_id">
                                            <option value="">{{__('label.country')}}</option>
                                            @if(isset($countries) && $countries->count() > 0)
                                                @foreach($countries as $country)
                                                <option value="{!! $country->id !!}" @if(isset($outlet) && $outlet->country_id==$country->id) selected @endif>{!! $country->country_name !!}</option>
                                                @endforeach
                                            @endif


                                        </select>
                                    </div>

@php
    $time_zones = \App\Models\TimeZones::all();
@endphp

                                <div class="form-group">
                                    <label>{{__('label.time_zone')}}</label> <br />

                                    <select name="time_zone" class="form-control" required>
                                        <option value="">{{__('label.time_zone')}}</option>
                                        @if(isset($time_zones) && $time_zones->count() > 0)
                                            @foreach($time_zones as $timezone)
                                            <option value="{!! $timezone->timezone !!}" @if(isset($outlet) && $outlet->time_zone==$timezone->timezone) selected @endif>{!! $timezone->timezone !!}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>




                                        @php
                                        $restaurant = \Illuminate\Support\Facades\Auth::user()->restaurants;
                                        $resto_metas = \App\Models\RestoMetaDefs::where('parent_meta_def_id',0)->whereIn('meta_def_name',['BUSSINESS_CCY','DEFAUT_LANGUAGE'])->get();


                                        $existing_resto_meta = [];
                                        if(isset($outlet))
                                        $existing_resto_meta = \App\Models\RestoMetas::where('bussiness_id',$restaurant->id)->where('outlet_id',$outlet->id)->pluck('meta_def_id')->toArray();

                                       // dump($existing_resto_meta);

                                        $existing_resto_meta = isset($existing_resto_meta )?$existing_resto_meta:[];
                                        $existing_resto_meta_value = null;
                                         if(isset($outlet))
                                        $existing_resto_meta_value = \App\Models\RestoMetas::where('bussiness_id',$restaurant->id)->where('outlet_id',$outlet->id)->get();
                                        $v = [];
                                        if(isset($existing_resto_meta_value) && $existing_resto_meta_value->count() > 0){
                                            foreach($existing_resto_meta_value as $value){
                                                    $v[$value->meta_def_id] = $value->meta_val;
                                            }
                                        }




                                        @endphp

                                        @if(isset($resto_metas) && $resto_metas->count() > 0)
                                        @foreach($resto_metas as $meta)





                                    <div class="form-group">

                                        <label>{!! str_replace('_',' ',$meta->meta_def_name) !!}</label> <br />

                                        @if(isset($meta->childern) && $meta->childern->count() > 0)
                                        <select class="form-control" name="resto_meta[]" @if($meta->is_required=="Yes") required @endif>

                                            <option>{{__('label.select_option')}}</option>

                                            @foreach($meta->childern as $childern)
                                            <option  value="{!! $childern->meta_def_id !!}" @if(in_array($childern->meta_def_id,$existing_resto_meta)) selected @endif>{!! $childern->meta_def_name !!}</option>
                                            @endforeach
                                        </select>
                                        @else
                                            <input type="hidden" name="resto_meta[]" value="{!! $meta->meta_def_id !!}">
                                            <input type="text" class="form-control" name="resto_meta_value[{!! $meta->meta_def_id !!}]" @if(isset($restaurant) && isset($v[$meta->meta_def_id])) value="{!! $v[$meta->meta_def_id] !!}" @endif  @if($meta->is_required=="Yes") required @endif placeholder="{!! $meta->meta_def_desc !!}">
                                        @endif

                                    </div>




                            @endforeach
                            @endif




                                        <h4>{{__('label.order_setting')}}</h4>
                                        <p>{{__('label.select_the_dates_customers_can_place_orders_for')}}</p>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <input type="checkbox" id="md_checkbox_21" class="filled-in chk-col-primary" checked />
                                                <label for="md_checkbox_21">{{__('label.same_day')}}</label>			 <br />
                                                <input type="checkbox" id="md_checkbox_21" class="filled-in chk-col-primary" checked />
                                                <label for="md_checkbox_21">{{__('label.next_day')}}</label>
                                            </div>
                                        </div>



                                        <a href="#!" class="btn btn-primary save-changes mt-4">{{__('label.save_changes')}}</a>

                                    </div>
                                </div>


                            </div>
                        </form>
                    </div>

                        <!-- /.box -->
                </div>

            </section>
            <!-- /.content -->

        </div>
    </div>
    <!-- /.content-wrapper -->

    <div class="modal fade" id="crop-image" tabindex="-1" role="dialog" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('js')
    <script src="{!! env('APP_ASSETS') !!}/vendor_components/dropzone/dropzone.js"></script>
    <script src="{!! env('APP_ASSETS') !!}/vendor_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
     <script src="{!! env('APP_ASSETS') !!}vendor_components/select2/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("select[name=time_zone]").select2();
            $.uploadPreview({
                input_field: "#image-upload",   // Default: .image-upload
                preview_box: "#image-preview",  // Default: .image-preview
                label_field: "#image-label",    // Default: .image-label
                label_default: "Choose File",   // Default: Choose File
                label_selected: "Change File",  // Default: Change File
                no_label: true    ,
                success_callback: function() {
                   // alert();
                   $("#crop-image").modal();
                }// Default: false
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
                                window.location = "{!! env('APP_URL') !!}outlet/edit/"+response.unique_key
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
        });
    </script>
@endsection
