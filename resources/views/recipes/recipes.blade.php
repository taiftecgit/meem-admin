@extends('layouts.app')
@section('content')
@php
$resto = \App\Models\Restaurants::find(\App\Helpers\CommonMethods::getRestuarantID());
$lang = $resto->default_lang;



app()->setLocale($lang);
if(session('app_lang') !==null){
$lang = session('app_lang');
app()->setLocale($lang);
}
$restuarant1  =$resto;
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
            $is_allow_print_preview = isset($resto_metas['PRINT_PREVIEW_ON_ACCEPT_ORDER'])?$resto_metas['PRINT_PREVIEW_ON_ACCEPT_ORDER']:"Disabled";
            $imgix = isset($resto_metas['IMGIX_SOURCE'])?$resto_metas['IMGIX_SOURCE'].'/':"https://meemappaws.imgix.net";
            $allow_pre_order = isset($resto_metas['ALLOW_PRE_ORDERS'])?$resto_metas['ALLOW_PRE_ORDERS']:"No";


@endphp
<link href="{!! env('APP_ASSETS') !!}css/jquery-ui.min.css" rel="stylesheet" />
<style>
.pagination li.active a.page-link {
    color: white !important;
    background: #ffa505 !important;
}
.accordion-button{
 background-color: #ffab00 !important;
    color: #fff !important;
}
.page-link{
            padding: .5em 1em !important;
            border-radius: 2px;
            border: 0;
            margin: 0;
            min-width: 50px !important;
            text-align: center;
        }

        .page-item.active .page-link{
            background-color: #4c95dd;
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


        .top-title{
            font-size: 1.7142857142857142rem;
        }
        .mr-12{
            margin-right: 12px;
        }
        .flex-item{
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }
</style>

<div class="content-wrapper">
        <div class="container-full">
            <section class="content">



                 <div class="">
                     <div class="row m-0">

                             <div class="col-6"><h2 class="top-title" style="margin-left: 10px">{{__('label.items')}}</h3></div>
                             <div class="col-6 text-end flex-item"><a href="{!! env('APP_URL') !!}recipe/new" class="btn btn-sm btn-danger float-right mr-12"><i class="glyphicon glyphicon-plus"></i> {{__('label.add_new')}} </a></div>

                     </div>
                     <div class="card-body pt-0">
                         <div class="row mb-4">
                             <div class="col-sm-12">

                             </div>
                         </div>
                         <div class="jumbotron p-0">


                             <div class="row">
                                 <div class="col-md-12">
                                     <div class="d-flex">
                                         <div>
                                             <button type="button"  data-on-text="Open"  data-off-text="Closed" class="btn mt-1 ms-0  btn-toggle btn-sm btn-success switch-me" data-id="sorting" data-bs-toggle="button" aria-pressed="false" autocomplete="off">
                                                 <div class="handle"></div>
                                             </button> <span class="mt-1">Sorting Enabled?</span>
                                         </div>
{{--                                         <div class="ms-auto">--}}
{{--                                             <button class="btn btn-primary save-pre-order" style="display: none">Save Pre Order Items</button>--}}
{{--                                         </div>--}}
                                     </div>

                                     <div class="accordion" id="category-items">
                                         @if(isset($categories) && $categories->count() > 0)
                                             @foreach($categories as $k=>$category)
                                                    <div class="accordion-item">
{{--                                             <h2 class="accordion-header" id="category-{!! $category->id !!}-heading">--}}
{{--                                                 <div class="accordion-button">--}}
{{--                                                     <div>--}}
{{--                                                         <div class=" @if($k>0) collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#category-{!! $category->id !!}" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">--}}
{{--                                                             {!! $category->name !!}--}}



{{--                                                         </div>--}}
{{--                                                     </div>--}}
{{--                                                 @if(  $allow_pre_order=="Yes")--}}

{{--                                                    <div class="ms-auto">--}}
{{--                                                     <input @if(isset($recipe) && $recipe->allow_pre_order=="Yes")   checked @endif  style="position: inherit; left: 0; opacity: 1; margin: 0 10px" id="allow_pre_order{!! $category->id !!}" name="allow_pre_order_category[]" class="allow_pre_order_category" value="{!! $category->id !!}" type="checkbox" />{{__('label.allow_pre_order')}}?--}}
{{--                                                    </div>--}}


{{--                                                 @endif--}}

{{--                                                 <div style="clear: both"></div>--}}
{{--                                                 </div>--}}
{{--                                             </h2>--}}
                                                        <h2 class="accordion-header" id="category-{!! $category->id !!}-heading">
                                                            <button class="accordion-button @if($k>0) collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#category-{!! $category->id !!}" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                                                {!! $category->name !!}
                                                            </button>
                                                        </h2>
                                             <div id="category-{!! $category->id !!}" class="accordion-collapse collapse  @if($k==0) show @endif" aria-labelledby="category-{!! $category->id !!}-heading">
                                                 <div class="accordion-body">
                                                     @if(isset($category->categories_has_recipes) && $category->categories_has_recipes->count() > 0)

                                                         <ul class="list-group make-sortable">
                                                             @foreach($category->categories_has_recipes as $item)
                                                                 @php
                                                                     $categories = isset($item->categories)?$item->categories->pluck('category_id'):NULL;
                                                                     $row_id = $item->id.'-'.(isset($categories[0])?$categories[0]:0);


                                                                 @endphp
                                                                 <li class="list-group-item"  id="{!! $row_id !!}">

                                                                     <div class="d-flex align-content-start flex-wrap">
                                                                         <div class="flex-shrink-0 me-3">
                                                                             @php
                                                                                 $img = isset($item->main_images)?$item->main_images->file_name:env('APP_ASSETS').'img/user/1.png';
                                                                                 $img = str_replace(env('AWS_URL'),$imgix,$img).'?fm=webp&h=60&w=60&q=50&fit=center&crop=center';

                                                                             @endphp
                                                                             <img src="{!! $img !!}" alt="{!! $item->name !!}">
                                                                         </div>
                                                                         <div class="flex-grow-1">


                                                                                <div class="d-flex">
                                                                                    <div>
                                                                                     <p class="mt-1">{!! $item->name !!} @if(!empty($item->arabic_name)) (
                                                                                         {!! $item->arabic_name !!} ) @endif </p>
                                                                                    </div>
                                                                                    <div class="ms-4 mt-1">( <strong> {!! number_format($item->price,2) !!} </strong>) </div>
                                                                                </div>

                                                                             <p>
                                                                                 {!! ($item->status==1)?
                                                                                                                                                 '<span class="badge badge-success m-1">Status: '.__('label.active').' </span>':'<span class="badge badge-danger m-1">Status:  '.__('label.deactive').' </span>'
                                                                                                                                                 !!}</span>
                                                                                 {!! $item->is_customized?'<span class="badge badge-success">Customizable: '.__('label.yes').'</span>':'<span class="badge badge-danger">Customizable: '.__('label.no').'</span>' !!}

                                                                             </p>

                                                                         </div>
                                                                         @if(  $allow_pre_order=="Yes")

                                                                             <div style="margin: 5px 20px">
                                                                                 <input disabled @if(isset($item) && $item->allow_pre_order=="Yes")   checked @endif  style="position: inherit; left: 0; opacity: 1; margin: 0 10px" id="allow_pre_order{!! $item->id !!}" class="allow_pre_order_item" name="allow_pre_order_item[]" value="{!! $item->id !!}" type="checkbox" />{{__('label.allow_pre_order')}}?
                                                                             </div>


                                                                         @endif
                                                                         <div class="  ms-auto ">
                                                                             <a href="{!! env('APP_URL') !!}recipe/edit/{!! $item->id !!}" class="mr-2 badge badge-primary m-1"  data-toggle="tooltip" data-placement="top" title="{{__('label.edit')}}"><i class="glyphicon glyphicon-edit"></i></a>
                                                                             <a href="javascript:;" data-id="{!! $item->id !!}" class="delete-recipe badge badge-danger m-1"  data-toggle="tooltip" data-placement="top" title="{{__('label.delete')}}">   <i class="glyphicon glyphicon-trash"></i>
                                                                             </a>
                                                                             <a href="{!! env('QRCODE_HOST_ORDER') !!}{!! $item->restuarants->resto_unique_name !!}/item/{!! $item->unique_shared_key !!}" class="btn btn-sm btn-primary mb-1" target="_blank" data-toggle="tooltip" data-placement="top" title="Direct Link"><i class="  glyphicon glyphicon-paperclip"></i></a>
                                                                             <a href="{!! env('QRCODE_HOST_ORDER_SP') !!}/f/{!! $item->restuarants->resto_unique_name !!}?id={!! $item->unique_shared_key !!}" class="btn btn-sm btn-primary mb-1" target="_blank" data-toggle="tooltip" data-placement="top" title="Direct Link"><i class="  glyphicon glyphicon-paperclip"></i>&nbsp;FB&nbsp;</a>
                                                                             <a href="{!! env('QRCODE_HOST_ORDER_SP') !!}/t/{!! $item->restuarants->resto_unique_name !!}?id={!! $item->unique_shared_key !!}" class="btn btn-sm btn-primary mb-1" target="_blank" data-toggle="tooltip" data-placement="top" title="Direct Link"><i class="  glyphicon glyphicon-paperclip"></i>&nbsp;Ticktok&nbsp;</a>
                                                                             <a href="{!! env('QRCODE_HOST_ORDER_SP') !!}/i/{!! $item->restuarants->resto_unique_name !!}?id={!! $item->unique_shared_key !!}" class="btn btn-sm btn-primary mb-1" target="_blank" data-toggle="tooltip" data-placement="top" title="Direct Link"><i class="  glyphicon glyphicon-paperclip"></i>&nbsp;Insta&nbsp;</a>
                                                                             <a href="{!! env('QRCODE_HOST_ORDER_SP') !!}/g/{!! $item->restuarants->resto_unique_name !!}?id={!! $item->unique_shared_key !!}" class="btn btn-sm btn-primary mb-1" target="_blank" data-toggle="tooltip" data-placement="top" title="Direct Link"><i class="  glyphicon glyphicon-paperclip"></i>&nbsp;Google&nbsp;</a>


                                                                         </div>
                                                                     </div>


                                                                 </li>
                                                                 @endforeach
                                                         </ul>
                                                         @endif
                                                 </div>
                                             </div>
                                         </div>
                                             @endforeach
                                             @endif

                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>

                    </div>
            </section>
        </div>
    </div>








@endsection

@section('js')
    <script src="{!! env('APP_ASSETS') !!}js/jquery-ui.min.js"></script>
    <script src="{!! env('APP_ASSETS') !!}js/jquery.ui.touch-punch.min.js"></script>
    <script>
        var resto_id = 0;
        $(function () {

            $("body").on("change",".allow_pre_order_category",function (){
                $(".save-pre-order").show();
                var _this = $(this);
                if(_this.is(":checked")){
                    _this.parents(".accordion-item").find("input.allow_pre_order_item").prop("checked","checked");
                }else{
                    _this.parents(".accordion-item").find("input.allow_pre_order_item").prop("checked",false);
                }
            });

            $("body").on("click",".save-pre-order",function(){
                var allow_selected_categories = $.map($('.allow_pre_order_category:checked'), function(c){return c.value; });
                var allow_selected_items = $.map($('.allow_pre_order_item:checked'), function(c){return c.value; });
                console.log(allow_selected_items);
            });

            $(window).resize(function(){
                if($(window).width() < 500){
                    $("#captions").addClass('flex-column');
                }else{
                    $("#captions").removeClass('flex-column');
                }
            });
            $("body").on("click",".switch-me",function (e) {
                var is_active = $(this).attr("aria-pressed");

                is_active = $.trim(is_active);

                if(is_active=="false"){
                    $( ".make-sortable" ).sortable("disable");
                }else{
                    $( ".make-sortable" ).each(function(){
                        var _this = $(this);
                        _this.sortable({
                            olerance: 'pointer' ,
                            disabled:false,
                            items: "li",
                            helper: fixHelper,
                            cursor: 'move',
                            start:function(event,ui){
                                var data = _this.sortable('toArray');

                            },

                            update: function(event, ui) {
                                //alert("New position: " + ui.item.index());
                                // $('#dataTable').DataTable().destroy();
                                // var table  =  data_table .draw();
                                var data = _this.sortable('toArray');
                                // $('#dataTable').DataTable();
                                var recipe = [];

                                var rows = _this.find('li');

                                $(rows).each(function(index,element) {

                                    recipe.push({
                                        id: $(this).attr('id'),
                                        // id:element.DT_RowId,
                                        position: index+1
                                    });
                                });
                                //  $
                                console.log(recipe);

                                $.ajax({
                                    url:'{!! env('APP_URL') !!}update/recipe/order',

                                    type:"POST",
                                    data:{
                                        ids:recipe,
                                        "_token":"{!! csrf_token() !!}"
                                    },
                                    success:function () {

                                    }

                                });

                            }
                        });
                    });


                    $( "#sortable" ).disableSelection();
                }
            });

  var item_table = $('#example').DataTable({
                "bSort": false,
                "searching": true,
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

            var _item_categories = item_table.column(2, { search: 'applied' });


            // $("#category_list").html('<option value="">Choose category</option>');
            // _item_categories.data().unique().sort().each(function (d, j) {
            //     $("#category_list").append('<option value="' + d + '">' + d + '</option>')
            //
            // });

            $("body").on("change","#category_list",function(){

                item_table = item_table.search($(this).val()).draw();
              //  item_table.bPaginate = false;
               // item_table.draw;
             //   var oSettings = item_table.settings();

              //  oSettings.bPaginate = false;
            });



            var fixHelper = function(e, ui) {
                ui.children().each(function() {
                    $(this).width($(this).width());

                });
                return ui;
            };

            $("body").on('click','.delete-recipe',function () {
                var id = $(this).data('id');
                //alert();
                swal({
                        title: " Confirm?",
                        text: "Do you want delete?",
                        type: "error",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: " Confirm, delete it!",
                        cancelButtonText: "No, cancel please!",
                        closeOnConfirm: true,
                        closeOnCancel: true
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url:"{!! env('APP_URL') !!}recipe/delete/"+id,
                                success:function (response) {
                                    location.reload();
                                }
                            });
                        }
                    });


            });



            $("body").on('click','.save-credentails',function () {

                var password =  $("#password").html();

                $.ajax({
                    url:"{!! env('APP_URL') !!}update/password",
                    type:"POST",
                    data:{
                        resto_id:resto_id,
                        password:password,
                        '_token':"{!! csrf_token() !!}"
                    },
                    success:function (response) {
                        $("#create-credentials .alert").show();

                        setTimeout(function () {
                            location.reload();
                        },1500)
                    }
                });

            });



        })
    </script>
@endsection
