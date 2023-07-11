<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:image" itemprop="image" content="https://admin.meemorder.io/public/assets/images/meem_meta.png">    <title>meem</title>
    <meta name="description" content="meem food ordering system">
    <meta property="og:type" content="website" />

    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" href="{!! env('APP_ASSETS') !!}images/favicon.ico">
    <link rel="apple-touch-icon" sizes="128x128" href="{!! env('APP_ASSETS') !!}images/favicon.ico">
    <link rel="shortcut icon" sizes="128x128" href="{!! env('APP_ASSETS') !!}images/favicon.ico">
    <!-- Feather Icon-->
    <link href="{!! env('APP_ASSETS') !!}css/vendors_css.css" rel="stylesheet" type="text/css">
    <!-- Fontawesome Icon-->
    <link href="{!! env('APP_ASSETS') !!}css/style.css" rel="stylesheet" type="text/css">
    <link href="{!! env('APP_ASSETS') !!}css/style_new.css" rel="stylesheet" type="text/css">
    <style>
        .user-section{
            max-width: 440px; margin: 0 auto; width: 100%;
        }
        .register-form{
                display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-flex: 1;
    -ms-flex: 1 0 500px;
    flex: 1 0 500px;
    max-width: 100%;
    padding: 3vmax 2.5vmax;
    min-height: 100vh;
        }
        .form-control, .form-select {
    height: 46px !important;
    border-color: #E4E6EB !important;
    border-radius: 7px !important;
}
label.error{
    color: #F00;
}
.alert{
    display: none;
}
    </style>


</head>

    <body class="light-skin sidebar-mini theme-primary fixed">
    <div class="wrapper" style="overflow-y: auto !important">

        <div class="row">
            <div class="col-md-6 register-form">

                @if(isset($user))
               
                    @csrf
                <div class="user-section">
                     <form id="reset-password-form" action="{!! env('APP_URL') !!}update/password/user" method="POST"  enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="user_id" value="{!! request()->get('u') !!}">
                    <h3 class="mb-50"><img src="{!! env('APP_ASSETS') !!}images/logo-dashboard.png" style="margin-right: 10px;" />Meem</h3>
                    
                    <h3 class="mt-30 mb-15">Reset Password</h3>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Password <span class="mandatory">*</span></label>
                                <input type="password" class="form-control" required name="password" minlength="8" id="password" placeholder="Enter your password" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Confirm Password <span class="mandatory">*</span></label>
                                <input type="password" class="form-control" name="confirm_password" id="confirm_password" minlength="8" required placeholder="Enter your confirm password" />
                            </div>
                        </div>
                    </div>


                   
                            <div class="row mt-10">
                                <div class="col-sm-12">
                                    <div class="alert alert-success"></div>
                                      <div class="alert alert-danger"></div>
                                </div>
                            </div>

                    <div class="row mt-10">
                        <div class="col-md-12 text-center">
                            <button class="btn btn-primary w-100 reset-password" style="width: 100% !important;">Reset Password</button>
                        </div>
                    </div>
                </form>

                </div>
                @else
                    <div class="user-section text-center">
                        <p class="text-danger">Invitation link is expired.</p>
                    </div>
                @endif
                
            </div>
            <div class="col-md-6" style="background: #ffab00;">
                
            </div>
        </div>

    </div>
</body>

<script src="{!! env('APP_ASSETS') !!}js/vendors.min.js"></script>

<script src="{!! env('APP_ASSETS') !!}js/jquery.validate.min.js"></script>
<script src="{!! env('APP_ASSETS') !!}js/jquery.form.js"></script>

<script type="text/javascript">
    $(function(){

        $('#reset-password-form').validate({
            rules : {
                password : {
                    minlength : 8
                },
                confirm_password : {
                    minlength : 8,
                    equalTo : "#password"
                }
            }
            });

        $("body").on("click",".reset-password",function(e){
            if($("#reset-password-form").valid()){
                $("#reset-password-form").ajaxForm(function(response){

                    if(response){
                            if(response.type=="success"){
                                $(".alert-success").html(response.message);
                                $(".alert-success").show();

                                setTimeout(function(){
                                   window.location = "{!! env('APP_URL') !!}dashboard";  
                                },2500);
                               
                            }else{
                                 $(".alert-danger").html(response.message);
                                $(".alert-danger").show();
                            }
                         
                        }

                }).submit();
            }

            e.preventDefault();

            
        });

    })
</script>
</head>