@extends('layouts.app')
@section('css')
    <link href="{!! env('APP_ASSETS') !!}vendor/dropzone/dist/dropzone.css" rel="stylesheet">

@endsection
@php
$resto_id = \App\Helpers\CommonMethods::getRestuarantID();
$resto = \App\Models\Restaurants::find($resto_id );
$lang = $resto->default_lang;

app()->setLocale($lang);
if(session('app_lang') !==null){
$lang = session('app_lang');
app()->setLocale($lang);

}
$mta = \App\Models\RestoMetas::where('bussiness_id',$resto_id)->where('meta_def_id',84)->first();
@endphp
@section('content')
<style>
        .alert{
            display: none;
        }
        .card-header{
            display: inline-block;
        }
         .container-full,.content-wrapper{
            background-color: transparent !important;
        }
        #image-preview {
            width: 700px;
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
        .form-control, .form-select {
            height: 46px !important;
            border-color: #E4E6EB !important;
            border-radius: 7px !important;
        }
        html[dir="rtl"]  .ar-m-0{
            margin: 0 !important;
        }
    </style>
 <div class="content-wrapper">
        <div class="container-full">
            <section class="content">
                <div class="row ar-m-0">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fa fa-plus mr-1"></i>
                        @if(isset($category))
                            {{__('label.edit')}}
                        @else
                            {{__('label.new_category')}}
                        @endif
                    </div>
                    <div class="card-body">
                        <form id="category-form" method="POST" action="{!! env('APP_URL') !!}category/save" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{!! isset($category)?$category->id:'' !!}" />
							@if(isset($mta) && isset($_GET['menu']))
                             <div class="row mb-4">
                                <div class="col-sm-12">
                                    <p style="font-size: 14px">Cover Image</p>
                                <div id="image-preview" @if(isset($category) && isset($category->main_images) && !empty($category->main_images->file_name)) style="background: url({!! $category->main_images->file_name !!})" @endif>
                                    <label for="image-upload" id="image-label">Choose File</label>
                                    <input type="file" name="main_image" id="image-upload" />
                                </div>
                                </div>
                            </div>
							@endif

                            <div class="row">
                                <div class="col-sm-4 col-md-6">
                                    <div class="form-group">
                                        <label>{{__('label.name')}}</label>
                                        <input type="text" class="form-control" placeholder="" name="name" value="{!! isset($category)?$category->name:'' !!}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                            <div class="col-sm-4 col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.arabic_name')}}</label>
                                    <input type="text" class="form-control" placeholder="" name="arabic_name" value="{!! isset($category)?$category->arabic_name:'' !!}" required>
                                </div>
                            </div>

                        </div>
                            @if(isset($mta) && isset($_GET['menu']))
                                @php
                                    $super_categories = $categories = \App\Models\Categories::whereNull('deleted_at')->whereIn('resto_id',[$resto_id])->where('parent_id',0)->get();

                                @endphp
                            <div class="row">
                                <div class="col-sm-4 col-md-6">
                                    <div class="form-group">
                                        <label>{{__('label.super_category')}}</label>
                                        <select class="form-control" name="parent_id">
                                            <option value="0">Choose Category</option>
                                            @if(isset($super_categories) && $super_categories->count() > 0)
                                                @foreach($super_categories as $super_category)
                                                    <option value="{!! $super_category->id !!}">{!! $super_category->name !!}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                            </div>
                                </div>

                            </div>
                            @endif
                            <div class="row">

                                    @if(\Illuminate\Support\Facades\Auth::User()->role=="administrator")
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" id="active" checked name="active" type="checkbox" />
                                                <label class="custom-control-label" for="active">{{__('label.active')}}</label>
                                            </div>
                                        </div>
                                    @endif

                                </div>


                            <div class="row">
                                <div class="col-sm-12">
                                    <a href="#!" class="btn btn-primary save">{{__('label.save')}}</a>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="alert alert-success success"></div>
                                    <div class="alert alert-danger error"></div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
            </section>
        </div>
</div>




@endsection

@section('js')
    <script>
        $(function () {

              $.uploadPreview({
                input_field: "#image-upload",   // Default: .image-upload
                preview_box: "#image-preview",  // Default: .image-preview
                label_field: "#image-label",    // Default: .image-label
                label_default: "Choose File",   // Default: Choose File
                label_selected: "Change File",  // Default: Change File
                no_label: true    ,
                success_callback: function() {

                }// Default: false
            });

            $("body").on('click','.save',function () {
                if($("#category-form").valid()){
                    $("#category-form").ajaxForm(function (response) {
                        response = $.parseJSON(response);
                        if(response){
                            if(response.type=="success"){
                                $('#category-form .alert.success').html(response.message);
                                $('#category-form .alert.success').show();

                                setTimeout(function(){
									@if(isset($mta) && isset($_GET['menu']))
									window.location = '{!! env('APP_URL') !!}categories?menu=tablet';
									@else
                                    window.location = '{!! env('APP_URL') !!}categories';
									@endif
                                },2000)
                            }else{
                                $('#category-form .alert.error').html(response.message);
                                $('#category-form .alert.error').show();
                            }
                        }
                    }).submit();
                }
            })

        })
    </script>
@endsection
