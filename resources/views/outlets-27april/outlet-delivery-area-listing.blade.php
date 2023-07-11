@extends('layouts.app')
@section('content')
    <link href="{!! env('APP_ASSETS') !!}/vendor_components/dropzone/dropzone.css" rel="stylesheet"/>
    <link href="{!! env('APP_ASSETS') !!}/vendor_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet"/>
    <link href="{!! env('APP_ASSETS') !!}/css/jquery.timepicker.min.css" rel="stylesheet"/>
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
        .delivery-section{
            width: 400px;
            height: auto;
            background: white;
            position: absolute;
            left: 10px; top: 10px;
            z-index: 10; padding: 10px;
        }

        .input-group input[type=text]{
            border-left: 0; padding-left: 0;
        }

    </style>

    <!-- Content Wrapper. Contains page content -->
    @php
     $resto = \App\Restaurants::find(\App\Helpers\CommonMethods::getRestuarantID());

$lang = $resto->default_lang; 

app()->setLocale($lang);
if(session('app_lang') !==null){
$lang = session('app_lang');
app()->setLocale($lang);

}
       // $resto = \Illuminate\Support\Facades\Auth::user()->restaurants;

        $address = isset($resto->places)?$resto->places->place_name:"Baghdad";
        $polygons = isset($resto->places)?$resto->places->coordinates:NULL;


        if(!empty($outlet->address))
        $address = $outlet->address.', '.$outlet->place;
     //   dd($resto->places->place_name);
    @endphp
    @php
    $restuarant1 = $resto;
        $resto_meta = isset($restuarant1->resto_metas)?$restuarant1->resto_metas:null;

       
        //dump($outlet->resto_metas);
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
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
            <div class="content-header">
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
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                @if(isset($outlet))
                                    <h4 class="box-title">{{__('label.update')}} {!! $outlet->name !!}</h4>
                                @else
                                    <h4 class="box-title">{{__('label.create_new_outlet')}}</h4>
                                @endif{{--{{--
                                <h6 class="box-subtitle">{{__('label.use_default_tab_with_class')}} <code>{{__('label.vtabs')}} &amp; {{__('label.tabs-vertical')}}</code></h6>--}}
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <!-- Nav tabs -->
                                <div class="vtabs">
                                @include('outlets.outlet-sidebar')
                                <!-- Tab panes -->

                                <div class="tab-content" style="width: 84%">
                                    <div class="tab-pane active" id="basic-information" role="tabpanel" style="position: relative">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="box">
                                                    <div class="box-header"><a href="{!! env('APP_URL') !!}new/outlet/area?o={!! $outlet->unique_key !!}" class="btn btn-primary">{{__('label.add_new_outlet_area')}}</a> </div>
                                                    <div class="box-body">
                                                        <div class="table-responsive rounded card-table">
                                                            <table class="table border-no" id="dataTable">
                                                                <thead>
                                                                <tr class="">
                                                                    <th>{{__('label.status')}}</th>
                                                                    <th>{{__('label.name')}}</th>
                                                                    <th>{{__('label.delivery_fee')}} </th>
                                                                    <th>{{__('label.min_price')}}</th>


                                                                    <th></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @if(isset($areas) && $areas->count() > 0)
                                                                    @foreach($areas as $area)
                                                                        <tr>
                                                                            <td>
                                                                                <button type="button"  data-on-text="Open"  data-off-text="Closed" class="btn  btn-toggle btn-sm btn-success @if($area->status=="1") active @endif switch-me" data-id="{!! $area->id !!}" data-bs-toggle="button" aria-pressed="@if($outlet->status=="1") true @else false @endif" autocomplete="off">
                                                                                    <div class="handle"></div>
                                                                                </button> {{__('label.active')}}
                                                                            </td>
                                                                            <td>{!! $area->area_name !!}</td>
                                                                            <td>{!! $currency !!} {!! $area->delivery_fee !!}</td>
                                                                            <td>{!! $currency !!} {!! $area->min_price !!}</td>
                                                                            <td>
                                                                                <a href="{!! env('APP_URL') !!}area/edit/{!! $area->id !!}?o={!! $outlet->unique_key !!}" type="button" class="waves-effect waves-circle btn btn-circle btn-primary btn-xs mb-5"><i class="mdi mdi-check"></i></a>
                                                                                <a type="button" data-id="{!! $area->id !!}" class="waves-effect waves-circle btn btn-circle delete-area btn-danger btn-xs mb-5"><i class="mdi mdi-delete"></i></a>

                                                                            </td>
                                                                        </tr>
                                                                        @endforeach
                                                                @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div></div>
                            </div>
                            <!-- /.box-body -->
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
    $(function () {
        $("body").on("click",".delete-area",function () {
            var id = $(this).data('id');
            var _this = $(this);

            $.ajax({
                url:"{!! env('APP_URL') !!}area/delete/"+id,
                success:function (response) {
                    $.toast({
                        heading: '{{__("label.outlet_area_update")}}',
                        text: "{{__('label.area_is_deleted')}}",
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 3000,
                        stack: 1
                    });

                    _this.parents('tr').remove();

                }
            });
        });

        $("body").on("click",".switch-me",function () {
            var is_active = $(this).attr("aria-pressed");
            var id = $(this).data('id');
            is_active = $.trim(is_active);
            var status = 0;
            if(is_active=="false"){
                status = 0;
            }else{
                status = 1;
            }

            $.ajax({
                url:"{!! env('APP_URL') !!}area/update/status",
                type:"POST",
                data:{
                    id:id,
                    status:status,
                    "_token":'{!! csrf_token() !!}'
                },
                success:function () {
                    if(is_active=="false"){
                        $.toast({
                            heading: '{{__("label.area_status")}}',
                            text: '{{__("label.area_is_deactive")}}',
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 3000,
                            stack: 1
                        });
                    }else{
                        $.toast({
                            heading:'{{__("label.area_status")}}',
                            text: '{{__("label.area_is_deactive")}}',
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 3000,
                            stack: 1
                        });
                    }
                }
            });




        });
    })
</script>
@endsection
