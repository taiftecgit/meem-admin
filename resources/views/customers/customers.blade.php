@extends('layouts.app')
@section('content')
 <div class="content-wrapper">
        <div class="container-full">
			<section class="content">
				<div class="card p-15 rounded-1">
					 <div class="card-header">
                        
						 
						 Reset Or Remove Customer in Meem
					  </div>
					<div class="card-body">
					<form id="customer-rest-phone-form" method="POST" action="{!! env('APP_URL') !!}reset/phone/customer" enctype="multipart/form-data">
						  @csrf
						<div class="row">
                                <div class="col-sm-4 col-md-6">
                                    <div class="form-group">
                                        <label>Enter Customer Mobile Number with Country Code ( Ex: 971500000000 )</label>
                                        <input type="number" class="form-control" placeholder="" name="mobile_number" required>
                                    </div>
                                </div>
                         </div>
						<div class="row">
                                <div class="col-sm-12">
                                    <a href="#!" class="btn btn-primary save">Reset Or Remove Customer</a>
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
			</section>
	 	</div>
</div>
@endsection


@section('js')
    <script>
        $(function () {

            

            $("body").on('click','.save',function () {
                if($("#customer-rest-phone-form").valid()){
                    $("#customer-rest-phone-form").ajaxForm(function (response) {
                        response = $.parseJSON(response);
                        if(response){
                            if(response.type=="success"){
                                $('#customer-rest-phone-form .alert.success').html(response.message);
                                $('#customer-rest-phone-form .alert.success').show();

                                setTimeout(function(){
                                   location.reload();
                                },2000)
                            }else{
                                $('#customer-rest-phone-form .alert.error').html(response.message);
                                $('#customer-rest-phone-form .alert.error').show();
                            }
                        }
                    }).submit();
                }
            })

        })
    </script>
@endsection