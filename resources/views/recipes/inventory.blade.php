@extends('layouts.app')
@section('css')
    <link href="{!! env('APP_ASSETS') !!}css/dashboard.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        .btn-toggle.btn-sm,.btn-toggle.btn-sm > .handle{
            border-radius: 16px;
        }
         .btn-toggle.btn-sm:focus, .btn-toggle.btn-sm.focus, .btn-toggle.btn-sm:focus.active, .btn-toggle.btn-sm.focus.active{
            box-shadow: none;
        }

        .btn-outlet{

        }

        .btn-toggle.active{
            color: white !important;
            background: #ffa505;
        }
         .fixed .content-wrapper{
            margin-left: 259px !important;
        }
    .margin-left-20{
        margin-left: 20px;
    }
    .margin-left-30{
        margin-left: 30px;
    }
    .margin-left-40{
        margin-left: 40px;
    }


        table.dataTable {
            clear: both;
            margin-top: 6px !important;
            margin-bottom: 6px !important;
            max-width: none !important;
            border-collapse: collapse !important;
            font-family: 'Open Sans';
        }
        /*table.dataTable td{
            border-width: 1px;
        }*/
        .theme-primary .pagination li a:hover {

            background-color: #000 !important;
        }
        .table > :not(:last-child) > :last-child > * {
            border-bottom-color: transparent;
        }
        table.dataTable th{font-weight: 700 !important;}
        .dataTables_paginate {
            width: 100%;
            text-align: center;
        }
        div.dataTables_wrapper div.dataTables_paginate ul.pagination{
            justify-content: center !important;
        }


    /*table tr:hover{
        background: #FFD684;
    }*/
    </style>
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
    @php
      //$resto = \App\Models\Restaurants::find(\App\Helpers\CommonMethods::getRestuarantID());

        $outlets = \App\Models\Outlets::whereNull('deleted_at')->where('resto_id',$resto->id)->get();
        $o = NULL;
        if(isset($outlets) && $outlets->count() > 0){
        foreach($outlets as $outlet){
        $o[] = array(
            'outlet_name' => $outlet->name,
            'orders'=>rand(100,999)
            );
        }

        }








    @endphp
             @php
$restuarant1 = $resto;
        $resto_meta = isset($restuarant1->resto_metas)?$restuarant1->resto_metas:null;
         $resto_metas = [];
            $billing = [];
            if(isset($resto_meta)){
                foreach($resto_meta as $meta){
                  //  dump($meta->resto_meta_defs);
                    if($meta->resto_meta_defs->meta_def_name=="BILLING_GATEWAY"){
                   //     dump($meta->resto_meta_defs->meta_def_name);
                     // $resto_metas['BILLING_GATEWAY'][] = $meta->meta_val;
                        $billing[] = array('id'=>$meta->meta_id,'value'=>$meta->meta_val);
                    }
                    $resto_metas[$meta->resto_meta_defs->meta_def_name] = $meta->meta_val;
                }
            }
            $resto_metas['BILLING_GATEWAY'] = $billing;
            $currency = isset($resto_metas['BUSSINESS_CCY'])?$resto_metas['BUSSINESS_CCY']:"IQD";
@endphp
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Main content -->

            <section class="content">
                <div class="row ">
                    <div class="col-md-10">
                        <div class="m-15">
                            <h3 class="title margin-left-20">{{__('label.inventory')}}</h3>
                        </div>
                    </div>
                </div>
                @php
                                $outlets = \App\Models\Outlets::whereNull('deleted_at')->where('active',1)->where('resto_id',$restuarant1->id)->get();


                            @endphp
                <div class="row ">
                    <div class="col-md-6 ">
                        <select class="form-control form-select margin-left-30" id="outlets">
                            <option value="">{{__('label.outlets')}}</option>

                            @if(isset($outlets) && $outlets->count() > 0)
                                @foreach($outlets as $outlet)
                                    <option value="{!! $outlet->id !!}" @if(isset($_GET['outlet_id']) && $_GET['outlet_id']==$outlet->id) selected @endif>{!! $outlet->name !!}</option>
                                @endforeach
                            @endif

                        </select>
                    </div>
                    <!--<div class="col-md-6">
                       <button class="form-control btn  btn-md shadow-none ">Launch marketing activity</button>
                       </div>-->
                </div>

                <div class="row mt-2">
                    <div class="col-sm-8">
                        <input type="text" placeholder="{{__('label.search')}}" class="form-control col-md-6 margin-left-30">
                    </div>
                    <div class="col-sm-3"><p class="fw-bold text-end mt-2">{{__('label.availablity')}}</p></div>
                </div>

                 <div class="row p-0 mt-4">
                     <div class="col-sm-12">
                         <table class="table table-borderless" id="recipe-lists">
                            <thead>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                            @if(isset($_GET['outlet_id']) && !empty($_GET['outlet_id']))
                             @if(isset($recipes) && $recipes->count() > 0)
                                    @foreach($recipes as $recipe)
                             <tr>
                                <td width="3%"></td>
                                <td>  <div style="height: 48px; width: 48px; background-image: url(@if(isset($recipe->main_images)) {!! $recipe->main_images->file_name !!} @else {!! env('APP_ASSETS') !!}img/user/1.png @endif); background-size: cover; background-position: center;"></div></td>
                                 <td width="80%">{!! $recipe->name !!}</td>
                                 <td>
                                    @php
                                        $ex = [];
                                        $exclude_outlets = $recipe->exclude_outlets;
                                        if(!empty($exclude_outlets)){
                                            $ex = explode(",",$exclude_outlets);
                                        }
                                    @endphp
<button type="button"  data-on-text="Open"  data-off-text="Closed" class=" btn-toggle btn-sm btn-outlet @if(!in_array($_GET['outlet_id'],$ex)) active @endif  switch-me" data-id="{!! $recipe->id !!}" data-outlet-id="{!! isset($_GET['outlet_id']) && !empty($_GET['outlet_id'])?$_GET['outlet_id']:0 !!}" data-bs-toggle="button" aria-pressed="true" autocomplete="off">
                                            <div class="handle"></div>
                                        </button>
                                 </td>
                             </tr>
                             @endforeach

                             @endif
                             @endif
                         </tbody>
                         </table>
                     </div>
                 </div>



            </section>
            <!-- /.content -->
        </div>
    </div>

    <!-- /.content-wrapper -->
    @endsection

@section('js')

<script type="text/javascript">
    $(function(){

        $("#outlets").on('change',function(){
            if($(this).val()=="")
            window.location.href = "{!! env('APP_URL') !!}inventory";
            else
            window.location.href = "{!! env('APP_URL') !!}inventory?outlet_id="+$(this).val();
        });

        $("body").on("click",".switch-me",function () {
              var is_active = $(this).attr("aria-pressed");

                var id = $(this).data('id');
                var outlet_id = $(this).data('outlet-id');
                is_active = $.trim(is_active);
                var _this = $(this);
                var status = 0;

                $.ajax({
                    url:"{!! env('APP_URL') !!}exclude/recipe/outlet",
                    type:"POST",
                    data:{
                        recipe_id:id,
                        outlet_id:outlet_id,
                        is_exclude:is_active,
                        "_token":'{!! csrf_token() !!}'
                    },
                    success:function(){

                    }
                });
        });

        $('#recipe-lists').DataTable({
                "bSort": true,
                "searching": false,
                "paging": true,
                "info": false,
                "bLengthChange": false,

                language: {
                    @if($lang=='ar')
                    url:`{{asset('public/assets/js/dataTablear.json')}}`,
                    @endif
                    paginate: {
                        next: '<img src="{!! env("APP_ASSETS") !!}images/icons/next.png">', // or '→'
                        previous: '<img src="{!! env("APP_ASSETS") !!}images/icons/preivew.png">' // or '←'
                    }
                },

            });
            $("td nav").addClass('d-flex justify-content-center');

    })
</script>

@endsection
