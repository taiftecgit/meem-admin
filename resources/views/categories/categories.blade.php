@extends('layouts.app')
@section('content')

<style>
/*.pagination li.active a.page-link {
    color: white !important;
    background: #ffa505 !important;
}*/
.category_title{
    margin-top: 17px;
    margin-bottom: 17px;
}
.add_btn_div{
    display: flex;
    align-items: center;
    justify-content: end;
}
.dataTables_filter input[type='search']
{
    margin-right: 0;
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
     .category_title {
        padding-left: 0px;
     }
     .cate_add_btn{
        margin-right: 30px;
     }
     .fixed .content-wrapper{
        padding-top: 0px;
     }
    @media (max-width:767px) {

       .category_title {
            padding-left: 0px;
        }
         .cate_add_btn{
            margin-right: 0px;

        }

    }
    @media (max-width:1024px) {

       .content {
            padding-top: 30px;
        }
         .cate_add_btn{
            margin-top: 10px;
        }

    }
.category_title{
    font-size: 1.7142857142857142rem;
}
</style>

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
            <section class="content">
                <div class="row">
                    <div class="col-6"><h2 class="category_title" style="margin-left: 15px">{{__('label.categories')}}</h3></div>
                    <div class="col-6 text-end add_btn_div"><a href="{!! env('APP_URL') !!}category/new{{ isset($_GET['menu'])?'?menu=tablet':''}}" class="btn btn-sm btn-danger float-right cate_add_btn"><i class="glyphicon glyphicon-plus"></i> {{__('label.add_new')}} </a></div>
                </div>


                 <div class=" p-15 rounded-1">
                     <div class="row mb-4">
                         <div class="col-sm-12">
                             <button type="button"  data-on-text="Open"  data-off-text="Closed" class="btn ms-0  btn-toggle btn-sm btn-success switch-me" data-id="sorting" data-bs-toggle="button" aria-pressed="false" autocomplete="off">
                                 <div class="handle"></div>
                             </button> Sorting Enabled?
                         </div>
                     </div>
                        <div class="jumbotron p-0">



                            <div class="row">
                                <div class="col-md-12">

									<ul  class="list-group" id="sortable">
										 @if(isset($categories) && $categories->count() > 0)
											@foreach($categories as $k=>$category)
										<li  class="list-group-item" @if(\Illuminate\Support\Facades\Auth::user()->role=="restaurant" && \Illuminate\Support\Facades\Auth::user()->restaurants->id==$category->resto_id) class="data-row" @endif id="{!! $category->id !!}">
											<div class="d-flex">
												<div class="flex-column p-1">
													<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list-ul" viewBox="0 0 16 16">
															  <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm-3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm0 4a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm0 4a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
															</svg>
												</div>
												<div class="flex-column p-1" style="width: 50%">{!! $category->name !!} ( {!! $category->arabic_name !!} )</div>
                                                <div class="flex-column p-1">
                                                    @if(isset($category->parent_category))
                                                       <span class="badge badge-dark">{!! $category->parent_category->name !!}</span>
                                                        @endif
                                                </div>

												<div class="flex-column @if($lang=="en") ms-auto @else ml-auto @endif">
												@if(\Illuminate\Support\Facades\Auth::user()->role=="restaurant" && \Illuminate\Support\Facades\Auth::user()->restaurants->id==$category->resto_id)

                                                    <a href="{!! env('APP_URL') !!}category/edit/{!! $category->id !!}{{ isset($_GET['menu'])?'?menu=tablet':''}}" class="btn btn-sm btn-primary" ><i class="glyphicon glyphicon-edit"></i></a>
                                                    <a href="javascript:;" data-id="{!! $category->id !!}" class="btn btn-sm btn-danger delete-category"><i class="glyphicon glyphicon-trash"></i></a>
        @elseif(\Illuminate\Support\Facades\Auth::user()->role=="restaurant")
        <span class="badge badge-info">{{__('label.not_editable_by_super_admin')}}</span>
        @else
                                                    @if(\Illuminate\Support\Facades\Auth::user()->role=="administrator")
    <a href="{!! env('APP_URL') !!}category/edit/{!! $category->id !!}" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-edit"></i></a>
    <a href="javascript:;" data-id="{!! $category->id !!}" class="btn btn-sm btn-danger delete-category"><i class="glyphicon glyphicon-trash" ></i></a>
                                                        @endif
        @endif
												</div>

											</div>
										</li>
										@endforeach
										@endif
									</ul>



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
$(function () {
    var fixHelper = function(e, ui) {
        ui.children().each(function() {
            $(this).width($(this).width());

        });
        return ui;
    };

    $("body").on("click",".switch-me",function (e) {
        var is_active = $(this).attr("aria-pressed");

        is_active = $.trim(is_active);

        if(is_active=="false"){
            $( "#sortable" ).sortable("disable");
        }else{
            $( "#sortable" ).sortable({
                placeholder: "ui-state-highlight",
                disabled:false,
                cursor: 'move',
                start:function(event,ui){
                    var data = $("#sortable").sortable('toArray');
                    console.log(data);
                },
                stop: function(event, ui) {
                    //alert("New position: " + ui.item.index());
                    var data = $("#sortable").sortable('toArray');
                    var category = [];

                    var rows = $(".list-group-item.data-row");
                    console.log(rows);

                    $(data).each(function(index,element) {

                        category.push({
                            id: element,
                            // id:element.DT_RowId,
                            position: index+1
                        });
                    });
                    //  $
                    //console.log(category);
                    //return false;
                    $.ajax({
                        url:'{!! env('APP_URL') !!}update/category/order',

                        type:"POST",
                        data:{
                            ids:category,
                            "_token":"{!! csrf_token() !!}"
                        },
                        success:function () {

                        }

                    });

                }

            });
            $( "#sortable" ).disableSelection();
        }
    });



    /*$( "#sortable" ).sortable({
            olerance: 'pointer' ,
            items: "tr",
            helper: fixHelper,
            cursor: 'move',
        start:function(event,ui){
            var data = $("#sortable").sortable('toArray');
            console.log(data);
        },
        stop: function(event, ui) {
            //alert("New position: " + ui.item.index());
            var data = $("tbody").sortable('toArray');
            var category = [];

            var rows = $("tbody tr.data-row");

            $(rows).each(function(index,element) {

                category.push({
                    id: $(this).attr('id'),
                    // id:element.DT_RowId,
                    position: index+1
                });
            });
            //  $
         //   console.log(category);
            $.ajax({
                url:'{!! env('APP_URL') !!}update/category/order',

                type:"POST",
                data:{
                    ids:category,
                    "_token":"{!! csrf_token() !!}"
                },
                success:function () {

                }

            });

        }

    }); */


$('#example').DataTable({
                "bSort": true,
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

    $("body").on('click','.delete-category',function () {
        var id = $(this).data('id');
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
                        url:"{!! env('APP_URL') !!}category/delete/"+id,
                        success:function (response) {
                            location.reload();
                        }
                    });
                }
            });



        });
    })
</script>
@endsection
