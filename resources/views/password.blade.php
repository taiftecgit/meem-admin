@extends('layouts.app')
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
<style type="text/css">
    .top-breadcrumb{
        padding: 0 10px;
    }
</style>

    <div class="content-wrapper">
    <div class="container-fluid">
        <div class="row m-0">
            <div class="page-top-title">
                <h3 class="title m-0">{{__('label.change_password')}}</h1>
            </div>
            <ol class="breadcrumb mb-4 top-breadcrumb">
                <li class="breadcrumb-item"><a href="{!! env('APP_URL') !!}dashboard">{{__('label.dashboard')}}</a></li>

                <li class="breadcrumb-item active">{{__('label.change_password')}}</li>
            </ol>
        </div>
        
        <div class="row m-0">
            <div class="col-xl-12">
                <div class="card mb-4">

                    <div class="card-body">
                        <form id="password-form" method="POST" action="{!! env('APP_URL') !!}reset/password" enctype="multipart/form-data">
                            @csrf
                        <div class="row">
                            <div class="col-sm-4 col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.old_password')}}</label>
                                    <input type="password" class="form-control" placeholder="" name="old_password" value="" autocomplete="off" required>
                                    <br /><span class="text-danger">{{__('label.forgot_old_password')}}?</span> <a href="#!">{{__('label.send_alert')}}</a>{{__('label.to')}}  {{__('label.administrator')}}.
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.password')}}</label>
                                    <input type="password" class="form-control" placeholder="" name="password" value="" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.confirm_password')}}</label>
                                    <input type="password" class="form-control" placeholder="" name="confirm_password" value="" required>
                                </div>
                            </div>
                        </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <a href="#!" class="btn btn-primary save">{{__('label.save')}}</a>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="alert alert-success success" style="display: none"></div>
                                    <div class="alert alert-danger error" style="display: none"></div>
                                </div>
                            </div>

                        </form>


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