@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <h1 class="mt-4">{!! $recipe->name !!}</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{!! env('APP_URL') !!}dashboard">Dashboard</a></li>

            <li class="breadcrumb-item active">{!! $recipe->name !!}</li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div class="card mb-4">

                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th width="20%">Name:</th>
                                <td>{!! $recipe->name !!}</td>
                            </tr>
                            <tr>
                                <th width="20%">Categories:</th>
                                <td>{!! $categories !!}</td>
                            </tr>

                            <tr>
                                <th width="20%">Price:</th>
                                <td>{!! number_format($recipe->price) !!}</td>
                            </tr>
                            <tr>
                                <th width="20%">Status:</th>
                                <td>@if($recipe->status=="1")<span class="badge badge-success">active</span> @else <span class="badge badge-danger">disabled</span> @endif</td>
                            </tr>
                            <tr>
                                <th width="20%">Customizeable?:</th>
                                <td>@if($recipe->is_customized=="1")<span class="badge badge-success">Yes</span> @else <span class="badge badge-danger">No</span> @endif</td>
                            </tr>
                            <tr>
                                <th width="20%">Description:</th>
                                <td>{!! $recipe->short_description !!}</td>
                            </tr>
                            <tr>
                                <th width="20%">Main Image:</th>
                                <td>
                                    @if(isset($recipe) && isset($recipe->main_images))
                                        <div class="col-1">
                                            <img src="{!! $recipe->main_images->file_name !!}" class="img-fluid mb-2" alt="{!! $recipe->name !!}">
                                        </div>


                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th width="20%">Gallery Images:</th>
                                <td>
                                    @if(isset($recipe)  && isset($recipe->galleries))
                                        <div class="row mb-2">
                                            @foreach($recipe->galleries as $gallery)
                                                <div class="col-sm-1">
                                                    <div class="mb-1">
                                                        <img style="max-height: 110px" src="{!! $gallery->file_name !!}" class="img-fluid mb-2" alt="{!! $recipe->name !!}">
                                                    </div>

                                                </div>
                                            @endforeach
                                        </div>

                                    @endif
                                </td>
                            </tr>
    @if(\Illuminate\Support\Facades\Auth::user()->role=="restaurant")
    <tr>
        <td colspan="2">
            <a href="{!! env('APP_URL') !!}recipe/edit/{!! $recipe->id !!}" class="btn btn-primary">Edit</a>
        </td>
    </tr>
        @endif
</table>
</div>

</div>
</div>
</div>
</div>
</div>
@endsection

@section('js')
<script>
$(function () {
$("body").on('click','.save',function () {
$(".alert").hide();
if($("#password-form").valid()){
$("#password-form").ajaxForm(function (response) {
response = $.parseJSON(response);
if(response){
    if(response.type=="success"){
        $('#password-form .alert.success').html(response.message);
        $('#password-form .alert.success').show();

        setTimeout(function(){

            location.reload();

        },2000)
    }else{
        $('#password-form .alert.error').html(response.message);
        $('#password-form .alert.error').show();
    }
}
}).submit();
}
})

})
</script>
@endsection