<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="csrf-token" content="MOPUhVXZcUk6jStOhUNBIjlzjQOz911kSUPoP7gN">

    <meta property="og:type" content="website" />
      <title>{!! env('APP_NAME') !!} | Login</title>
    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" href="{!! env('APP_ASSETS') !!}images/favicon.png">
    <!-- Feather Icon-->
    <link href="{!! env('APP_ASSETS') !!}vendor_components/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="{!! env('APP_ASSETS') !!}icons/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css">


    <!-- Fontawesome Icon-->
<!--    <link href="{!! env('APP_ASSETS') !!}css/style.css" rel="stylesheet" type="text/css">-->
    <link href="{!! env('APP_ASSETS') !!}css/login.css" rel="stylesheet">
    <style>
    .do-login{
            background: #ffab00 !important;
            border-color: #ffab00 !important;
    }
		label.error{
    color: #F00;
}
.alert{
    display: none;
}
		.w-200 {
    width: 200px !important;
}
		.rounded10 {
    border-radius: 10px;
}
		.h-p100 {
    height: 100% !important;
}
		form p:last-child {
    margin-bottom: 0;
}
		form p {
    margin-bottom: 10px;
    text-align: left;
			font-size:14px;
}
		h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
    font-family: "Open Sans", sans-serif;
    font-weight: 400;
    line-height: 1.2;
			font-size: 25px;
    margin: 6px 0px;
}
		.p-40{
			padding:40px;
		}
		.form-group label {
    font-weight: 500;
}

label {
    display: inline-block;
}
		form input[type=email], input[type=password], input[type=text]{
			font-size:13px;
		}
		.form-group {
    margin-bottom: 1rem;
}
    .theme-primary .btn-danger:hover, .theme-primary .btn-danger:active, .theme-primary .btn-danger:focus, .theme-primary .btn-danger.active {
     background: #FFAD12 !important;
    border-color:  #FFAD12 !important;
    color: #ffffff !important;
}

        .input-group .input-group-text {
                border-color: #000 !important;
        }
        .form-control, .form-select {

            border-color: #000  !important;

        }
        form input[type=email], input[type=password], input[type=text]{
            color: #000 !important;
        }

            @media (min-width: 850px) and (max-width:  1020px){
                body{
                    padding-top: 5px;
                }
                .p-40{
                    padding: 31px !important;
                }
            }
    </style>

</head>
<body class="hold-transition theme-primary bg-img" >

<div class="container h-p100">
    <div class="row align-items-center justify-content-md-center h-p100">

              <div class="row justify-content-center g-0">
                <div class="col-lg-5 col-md-8 col-12 p-25">
                    <div class="bg-white rounded10 shadow-lg">

                        <div class="p-40">
                            <div class="logo ">
                                 <div class="col-md-12">
                                    <img class="image-fluid w-200 ml-5" src="{!! env('APP_ASSETS') !!}images/icons/meem.png">
                                 </div>
                            </div>
                            <form method="POST" action="{{ route('login') }}" id="login-form">
                                @csrf
                            <div class="form-group ">
                                <h3 class="balck-h3"><b>Welcome to {!! env('APP_NAME') !!}</b></h3>
                                <p>it's good to see you again!</p>
                                <p class="mt-2">Type your login information, and we'll take you to your dashboard right away.</p>
                            </div>
                           <div class="form-group">
                              <label for="exampleInputEmail1">User name *</label>
                              <input type="text" name="username" required=""  class="form-control @error('username') is-invalid @enderror" id="username" aria-describedby="emailHelp" placeholder="Enter user name">
                           </div>
                           <div class="form-group has-search">



                              <div class="form-group has-search">
                                <label for="exampleInputEmail1">Password *</label>
                                 <a href="#!" class="float-end reset-password">Forgot password?</a>
                                <span class="fa fa-eye form-control-feedback reveal show-password"></span>
                                <input type="password" class="form-control pwd @error('password') is-invalid @enderror" name="password" required="" placeholder="Enter password">
                              </div>


                           </div>



                           <div class="col-md-12 text-center ">
                            <div class="d-grid gap-2">
                              <button type="submit" class=" btn btn-block  btn-primary tx-tfm do-login">Login</button>
                            </div>
                           </div>
                           <div class="col-md-12 ">
                              <div class="login-or">
                                 @if (count($errors) > 0)
                                        <div class="alert alert-dark-danger alert-dismissible fade show" style="display: block;">
                                            <ul style="list-style: none; padding:0; margin :0">
                                                @foreach ($errors->all() as $error)
                                                    <li class="text-danger text-center">{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                              </div>
                           </div>


                        </form>

                        </div>
                    </div>

                </div>
            </div>
    </div>
</div>

	   <div class="modal center-modal fade" id="forgot-password-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-body">
                    <h4>Forgot Password?</h4>
					<form id="reset-password-form" method="post" action="{!! env('APP_URL') !!}reset/send/link/password">
						<input type="hidden" name="_token" value="{!! csrf_token() !!}" />
						<div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Username / Email Address</label>
                                <input type="text" name="email" class="form-control"  required />
                            </div>
                        </div>

                    </div>
					</form>
 							<div class="row mt-10">
                                <div class="col-sm-12">
                                    <div class="alert alert-success"></div>
                                      <div class="alert alert-danger"></div>
                                </div>
                            </div>


                </div>
                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary float-end send-reset-password">Reset password</button>
                </div>
            </div>
        </div>
    </div>


<!-- Jquery -->
<script src="{!! env('APP_ASSETS') !!}js/vendors.min.js"></script>
<!-- Fontawesome -->
<!--
<script src="{!! env('APP_ASSETS') !!}vendor/fontawesome/js/all.min.js"></script>
<script src="{!! env('APP_ASSETS') !!}js/scripts.js"></script>
<script src="{!! env('APP_ASSETS') !!}vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
-->
<!-- Bootstrap -->

<!-- Custom Js -->

	<script src="{!! env('APP_ASSETS') !!}js/jquery.validate.min.js"></script>
<script src="{!! env('APP_ASSETS') !!}js/jquery.form.js"></script>
<!-- Ajax Chart Js -->
<script>
    $(function () {
        $("body").on('click','.do-login',function () {
            $("#login-form").submit();
        });

        var showen = true;
        $("body").on("click",".show-password",function(){

            $("input[name=password]").attr('type','text');

            if(showen){
               $("input[name=password]").attr('type','text');
               $(this).removeClass('fa-eye');
               $(this).addClass('fa-eye-slash');
               showen = false;
            }else{
                $("input[name=password]").attr('type','password');
               $(this).removeClass('fa-eye-slash');
               $(this).addClass('fa-eye');
                showen = true;
            }

        });

		$("body").on("click",".reset-password",function(){
			$("#forgot-password-modal").modal('show');
		});

		$("body").on("click",".send-reset-password",function(){
			if($("#reset-password-form").valid()){
				$("#reset-password-form").ajaxForm(function(response){
					if(response.type=="success"){
                                $(".alert-success").html(response.message);
                                $(".alert-success").show();

                                setTimeout(function(){
                                  $(".alert-success").hide();
									location.reload();
                                },2500);

                            }else{
                                 $(".alert-danger").html(response.message);
                                $(".alert-danger").show();
                            }
				}).submit();
			}
		});

    })
</script>
</body>
</html>
