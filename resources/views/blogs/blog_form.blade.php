@extends('layouts.app')
@section('css')
    <link href="{!! env('APP_ASSETS') !!}vendor_components/summernote/summernote.min.css" rel="stylesheet" type="text/css">

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
    </style>
 <div class="content-wrapper">
        <div class="container-full">
            <section class="content">
                <div class="row">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fa fa-plus mr-1"></i>
                        @if(isset($blog))
                            Edit {!! $blog->name !!}
                        @else
                            New Blog
                        @endif
                    </div>
                    <div class="card-body"> @if(\Illuminate\Support\Facades\Auth::user()->role=="administrator" || \Illuminate\Support\Facades\Auth::user()->role=="admin_user" )
                        <form id="category-form" method="POST" action="{!! env('APP_URL') !!}blog/save" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{!! isset($blog)?$blog->id:'' !!}" />

                            <!-- <div class="row mb-4">
                                <div class="col-sm-12">
                                    <p style="font-size: 14px">Cover Image</p>
                                <div id="image-preview" @if(isset($category) && isset($category->main_images) && !empty($category->main_images->file_name)) style="background: url({!! $category->main_images->file_name !!})" @endif>
                                    <label for="image-upload" id="image-label">Choose File</label>
                                    <input type="file" name="main_image" id="image-upload" />
                                </div>
                                </div>
                            </div> -->


                            <div class="row">
                                <div class="col-sm-4 col-md-6">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" class="form-control" placeholder="" name="title" value="{!! isset($blog)?$blog->title:'' !!}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                            <div class="col-sm-4 col-md-6">
                                <div class="form-group">
                                    <label>Content</label>
                                    <textarea class="form-control" id="summernote"  name="content" required>{!! isset($blog)?$blog->content:'' !!}</textarea>
                                </div>
                            </div>
                        </div>
							<div class="row">
							<div class="col-sm-4 col-md-6">
                                <div class="form-group">
                                    <label>Short Description (<small>For meta</small>)</label>
                                    <textarea class="form-control"   name="short_description" required>{!! isset($blog)?$blog->short_description:'' !!}</textarea>
                                </div>
                            </div>

                        </div>
                             <div class="row">
                            <div class="col-sm-4 col-md-6">
                                <div class="form-group">
                                    <label>Main Image</label>
                                    <input type="file" class="form-control" @if(!isset($blog)) required @endif name="media" />
                                </div>

								@if(isset($blog) && !empty($blog->media))

                                        <img style="max-width: 350px" src="{!! $blog->media !!}"
                                             class="img-fluid mb-2" alt="{!! $blog->slug !!}">

                                @endif
                            </div>
                        </div>

                            <div class="row">


                                         <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" id="is_published" @if(isset($blog)) @if($blog->is_published=="Yes") checked @endif @else checked @endif name="is_published" type="checkbox" />
                                                <label class="custom-control-label" for="is_published">Published</label>
                                            </div>
                                        </div>


                                </div>


                            <div class="row">
                                <div class="col-sm-12">
                                    <a href="#!" class="btn btn-primary save">Save</a>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="alert alert-success success"></div>
                                    <div class="alert alert-danger error"></div>
                                </div>
                            </div>

                        </form>
                        @else
                            <div class="p-3 text-white bg-danger text-center">You are not authorized for this page</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
            </section>
        </div>
</div>




@endsection

@section('js')


<script src="{!! env('APP_ASSETS') !!}vendor_components/summernote/summernote.min.js"></script>

    <script>
        $(function () {

             /* $.uploadPreview({
                input_field: "#image-upload",   // Default: .image-upload
                preview_box: "#image-preview",  // Default: .image-preview
                label_field: "#image-label",    // Default: .image-label
                label_default: "Choose File",   // Default: Choose File
                label_selected: "Change File",  // Default: Change File
                no_label: true    ,
                success_callback: function() {

                }// Default: false
            });*/
$('#summernote').summernote({
	height: 300
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
                                    window.location = '{!! env('APP_URL') !!}blogs';
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
