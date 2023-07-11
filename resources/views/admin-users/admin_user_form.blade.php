@extends('layouts.app')
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
@section('content')

   <div class="content-wrapper">
        <div class="container-full">
            <section class="content">
                <div class="row">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fa fa-plus mr-1"></i>
                        @if(isset($blog))
                            Edit 
                        @else
                            New User
                        @endif
                    </div>
                    <div class="card-body">
                     <form id="create-user" action="{!! env('APP_URL') !!}save/admin/user" method="POST"  enctype="multipart/form-data">
                        @csrf
                       
                   
                    
                    <h3 class="mt-10 mb-15">Create account as admin user</h3>

                    <div class="row">
                        <div class="col-sm-4 col-md-6">
                            <div class="form-group">
                                <label>First Name <span class="mandatory">*</span></label>
                                <input type="text" class="form-control" required name="first_name" placeholder="Enter your first name" />
                            </div>
                        </div>
						 </div>
						  <div class="row">
                        <div class="col-sm-4 col-md-6">
                            <div class="form-group">
                                <label>Last Name <span class="mandatory">*</span></label>
                                <input type="text" class="form-control" name="last_name" required placeholder="Enter your last name" />
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mobile Number <span class="mandatory">*</span></label>
                                <input type="text" class="form-control" name="mobile_number" required placeholder="Enter your mobile" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Email <span class="mandatory">*</span></label>
                                <input type="text" class="form-control" name="email" value="{!! isset($user)?$user->email:"" !!}"  required placeholder="Enter your mobile" />
                        </div>
                    </div>

                    <div class="row mt-10">
                        <div class="col-md-6">
                            <label>Address <span class="mandatory">*</span></label>
                                <textarea class="form-control" name="address"></textarea>
                        </div>
                    </div>

                    
                            <div class="row mt-10">
                                <div class="col-sm-12">
                                    <div class="alert alert-success"></div>
                                      <div class="alert alert-danger"></div>
                                </div>
                            </div>

                    <div class="row mt-10">
                        <div class="col-md-6 text-center">
                            <button class="btn btn-primary  create-user">Create User</button>
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

<script type="text/javascript">
    $(function(){

        $('#create-user').validate({
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

        $("body").on("click",".create-user",function(e){
            if($("#create-user").valid()){
                $("#create-user").ajaxForm(function(response){
 response = $.parseJSON(response);
                    if(response){
                            if(response.type=="success"){
                                $(".alert-success").html(response.message);
                                $(".alert-success").show();

                                setTimeout(function(){
                                   window.location = "{!! env('APP_URL') !!}admin/users";  
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
@endsection('js')