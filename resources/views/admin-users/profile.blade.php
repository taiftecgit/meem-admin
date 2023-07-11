@extends('layouts.app')
@section('css')
    <link href="{!! env('APP_ASSETS') !!}css/outlets.css?v=1.3" rel="stylesheet" type="text/css">
@endsection
@section('content')
    <style>
        @-webkit-keyframes special {
            from { background-color: rgba(255, 121, 77, 0.27); }
            to { background-color: inherit; }
        }
        @-moz-keyframes special {
            from { background-color: rgba(255, 121, 77, 0.27);; }
            to { background-color: inherit; }
        }
        @-o-keyframes special {
            from { background-color: rgba(255, 121, 77, 0.27);; }
            to { background-color: inherit; }
        }
        @keyframes special {
            from { background-color: rgba(255, 121, 77, 0.27);; }
            to { background-color: inherit; }
        }
        .special {
            -webkit-animation: special 1s infinite; /* Safari 4+ */
            -moz-animation:    special 1s infinite; /* Fx 5+ */
            -o-animation:      special 1s infinite; /* Opera 12+ */
            animation:         special 1s infinite; /* IE 10+ */
        }
        .btn-toggle.btn-sm,.btn-toggle.btn-sm > .handle{
            border-radius: 16px;
        }
        table.dataTable {
            clear: both;
            margin-top: 6px !important;
            margin-bottom: 6px !important;
            max-width: none !important;
            border-collapse: collapse !important;
            font-family: 'Open Sans';
        }
        .theme-primary .paging_simple_numbers .pagination .paginate_button.active a,.theme-primary .pagination li a:hover,.theme-primary .paging_simple_numbers .pagination .paginate_button:hover a{
            color: white !important;
            background: #ffa505 !important;
        }
        .search-outlet{
            width: 400px;
            background-color: white;

        }
        .search-buttom{
            top: 8px;
            right: 15px;
            font-size: 20px;
            color: #e1e1e1;
        }
        .btn-toggle.btn-sm:focus, .btn-toggle.btn-sm.focus, .btn-toggle.btn-sm:focus.active, .btn-toggle.btn-sm.focus.active{
            box-shadow: none;
        }

        .btn-outlet{

        }

        .btn-toggle.active{
            color: white !important;
            background: #ffa505;
        }
        .bootstrap-tagsinput{
            min-width: 100%;
        }
        .user-type-section{
              padding: 25px;
    background: #fff8ec;
    border: 1px solid #ffab00;
    border-radius: 11px;
        }
        .option-section{
            display: none;
        }
        .user-type-section.selected .option-section{
            display: inline-flex !important;
        }
        .role-based-option .outlet-section{
            display: none;
        }
        .role-based-option.selected .outlet-section{
            display: inline-flex;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Main content -->
            <section class="content">

                <div class="row mt-30 ">
                    <div class="col-md-12">
                        <a href="{!! env('APP_URL') !!}users">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#ffab00" class="bi bi-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                        </svg> <span class="fw-400" style="color:#6c757d; margin-left: 5px;">Users</span>
                    </a>
                    </div>
                    <div class="col-md-6">
                        <div class="m-15">
                            <h3 class="title">{!! $user->first_name.' '.$user->last_name !!}</h3>
                            <p><span style="margin-right: 10px;color:#6c757d">{!! $user->email !!}</span> / <span style="margin-left: 10px;color:#6c757d">{!! $user->mobile_number !!}</span></p>
                        </div>
                    </div>

                    <div class="col-md-4 text-end">
                        <a href="#!" data-id="{!! $user->id !!}" class=" btn btn-primary btn-md  delete-saved-user  text-center">
                            <i class="fa  fa-plus mr-2"></i>
                            <!-- <i class="mdi mdi-plus-circle"></i> --> Delete User
                        </a>
                    </div>
                    
                </div>


                


                <div class="row ">
                    <div class="col-md-7">
                        <div class="m-15">
                            <h3 class="title">User Role</h3>

                            <div class="row user-type-section @if($user->role=="administrator") selected @endif mb-25"  data-role="administrator">
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" @if($user->role=="administrator") checked @endif id="administrator-role" name="access-role" type="radio"/>
                                            <label class="custom-control-label" for="administrator-role"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <h4> Administrator </h4>
                                    <p> Can access and manage all dashboard features for selected businesses. </p>
                                    
                                </div>

                                <div class="row  option-section">
                                    <div class="col-md-6">

                                        <div class="d-flex justify-content-center">
                                            <div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input choose-role-option" data-access="all-outlets" @if($user->role=="administrator" && $user->access_level=="all-outlets") checked @endif  id="administrator-full-access" name="administrator-access"   type="radio"/>
                                                        <label class="custom-control-label" for="administrator-full-access"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <h5>Access All Business</h5>
                                                <p> All businesses including future ones </p>
                                            </div>
                                        </div>

                                        
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-center">
                                            <div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input choose-role-option" id="administrator-selected-access" data-access="selected-outlets" @if($user->role=="administrator" && $user->access_level=="selected-outlets") checked @endif  name="administrator-access" type="radio"/>
                                                        <label class="custom-control-label" for="administrator-selected-access"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <h5>Selected Access</h5>
                                                <p> Can access selected businesses </p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>






                            <div class="row user-type-section @if($user->role=="manager") selected @endif mb-25" data-role="manager">
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" @if($user->role=="manager") checked @endif  id="manager-role" name="access-role" type="radio"/>
                                            <label class="custom-control-label" for="manager-role"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <h4> Manager </h4>
                                    <p>  Can access and manage Live orders, Inventory, Order history, Marketing features, and CRM from selected outlets. . </p>
                                    
                                </div>

                                <div class="row option-section">
                                    <div class="col-md-6">

                                        <div class="d-flex justify-content-center">
                                            <div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input choose-role-option" value="administrator" id="manager-full-access" data-access="all-outlets" @if($user->role=="manager" && $user->access_level=="all-outlets") checked @endif    name="manager-access"   type="radio"/>
                                                        <label class="custom-control-label" for="manager-full-access"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <h5>Access All outlets</h5>
                                                <p> All outlets including future ones </p>
                                            </div>
                                        </div>

                                        
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-center role-based-option @if($user->role=="manager" && $user->access_level=="selected-outlets") selected @endif">
                                            <div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input choose-role-option" id="manager-selected-access" @if($user->role=="manager" && $user->access_level=="selected-outlets") checked @endif    data-access="selected-outlets"  value="selected" name="manager-access" type="radio"/>
                                                        <label class="custom-control-label" for="manager-selected-access"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <h5>Selected Access</h5>
                                                <p> Can access selected outlets </p>
                                                @php
                                                    $outlt = $user->selected_outlets;
                                                    $o = [];
                                                    if(!empty($outlt)){
                                                        $o = explode(",",$outlt);
                                                       
                                                    }
                                                @endphp

                                                 <div class="col-md-12 outlet-section">
                                                    @if(isset($outlets) && $outlets->count() > 0)
                                                    <ul  class="list-unstyled">
                                                        @foreach($outlets as $outlet)
                                                        <li>
                                                            <div class="custom-control custom-checkbox">
                                                                <input class="custom-control-input selected-outlets" id="outlet-manager-{!! $outlet->id !!}" @if($user->role=="manager" && in_array($outlet->name,$o)) checked @endif  value="{!! $outlet->name !!}" name="" type="checkbox"/>
                                                                <label class="custom-control-label" for="outlet-manager-{!! $outlet->id !!}"> {!! $outlet->name !!}</label>
                                                            </div>
                                                   </li>
                                                        @endforeach
                                                    </ul>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                   
                                </div>
                            </div>


                            <div class="row user-type-section  @if($user->role=="staff") selected @endif  mb-25"  data-role="staff">
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" id="staff-role" @if($user->role=="staff") checked @endif  name="access-role" type="radio"/>
                                            <label class="custom-control-label" for="staff-role"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <h4> Staff </h4>
                                    <p>   Can manage Live orders, Inventory, and store operation status from selected outlets.  </p>
                                    
                                </div>

                                <div class="row  option-section">
                                    <div class="col-md-6">

                                        <div class="d-flex justify-content-center">
                                            <div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input choose-role-option" value="staff" id="staff-full-access" data-access="all-outlets" name="staff-access" @if($user->role=="staff" && $user->access_level=="all-outlets") checked @endif  type="radio"/>
                                                        <label class="custom-control-label" for="staff-full-access"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <h5>Access All outlets</h5>
                                                <p> All outlets including future ones </p>
                                            </div>
                                        </div>

                                        
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-center role-based-option @if($user->role=="staff" && $user->access_level=="selected-outlets") selected @endif ">
                                            <div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input choose-role-option" id="staff-selected-access" @if($user->role=="staff" && $user->access_level=="selected-outlets") checked @endif  data-access="selected-outlets" value="selected" name="staff-access" type="radio"/>
                                                        <label class="custom-control-label" for="staff-selected-access"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <h5>Selected Access</h5>
                                                <p> Can access selected outlets </p>
                                                <div class="col-md-12 outlet-section">
                                                     @php
                                                    $outlt = $user->selected_outlets;
                                                    $o = [];
                                                    if(!empty($outlt)){
                                                        $o = explode(",",$outlt);
                                                       
                                                    }
                                                @endphp
                                                    @if(isset($outlets) && $outlets->count() > 0)
                                                    <ul  class="list-unstyled">
                                                        @foreach($outlets as $outlet)
                                                        <li>
                                                            <div class="custom-control custom-checkbox">
                                                                <input class="custom-control-input selected-outlets" id="outlet-staff-{!! $outlet->id !!}" @if($user->role=="staff" && in_array($outlet->name,$o)) checked @endif   value="{!! $outlet->name !!}" name="" type="checkbox"/>
                                                                <label class="custom-control-label" for="outlet-staff-{!! $outlet->id !!}"> {!! $outlet->name !!}</label>
                                                            </div>
                                                   </li>
                                                        @endforeach
                                                    </ul>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="alert alert-success"></div>
                                      <div class="alert alert-danger"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary save-changes">Save Changes</button>
                                </div>
                            </div>



                            
                        </div>
                    </div>
                    
                </div>


              

            </section>
            <!-- /.content -->
        </div>
    </div>
    <!-- /.content-wrapper -->




@endsection

@section('js')
<script src="{!! env('APP_ASSETS') !!}/vendor_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
    <script>
        $(function () {

            $("input[name=access-role]").click(function(){
                $(".user-type-section").removeClass("selected");
                $(this).parents('.user-type-section').addClass('selected');

            });

            $("body").on("click",".save-changes",function(){
               
                $(".alert").hide();
                var role = $(".user-type-section.selected").data('role');
                var role_based_access = $(".user-type-section.selected .choose-role-option:checked").data('access');
                var selected_outlets = null;
               
                var errors = [];
                

                if(!role){
                    errors.push("No role is selected");
                }


                if(role_based_access=="selected-outlets"){
                    
                    selected_outlets = $( ".role-based-option.selected .selected-outlets:checked" ).map(function() { return this.value; }).get().join();
                    
                    if(selected_outlets==""){
                        errors.push("No outlet is selected");
                    }
                }

                if(errors.length > 0){
                    var str="<ul>";
                        $.each(errors,function(i,v){
                          str+='<li>'+v+'</li>';  
                        });
                        
                     str+="</ul>";

                     $(".alert-danger").html(str);
                     $(".alert-danger").show();

                    return false;
                }

           //     console.log(role+":"+role_based_access+":"+selected_outlets+":"+"{!! request()->route('id') !!}");
            //    return false;

                $.ajax({
                    url:"{!! env('APP_URL') !!}tanent/save/changes",
                    data:{
                        user:"{!! request()->route('id') !!}",
                        role:role,
                        role_based_access:role_based_access,
                        selected_outlets:selected_outlets,
                        "_token":"{!! csrf_token() !!}"
                    },
                    type:"POST",
                    success:function(response){
                        response = $.parseJSON(response);
                        if(response){
                            if(response.type=="success"){
                                $(".alert-success").html(response.message);
                                $(".alert-success").show();

                                setTimeout(function(){
                                   location.reload(); 
                                },2500);
                               
                            }else{
                                 $(".alert-danger").html(response.message);
                                $(".alert-danger").show();
                            }
                         
                        }
                     
                    }
                });

                

            });

            $("body").on("click",".choose-role-option",function(){
                var value = $(this).val();
                var _this = $(this);
                $('.role-based-option').removeClass('selected');
                if(value=="selected"){
                    _this.parents('.role-based-option').addClass('selected');
                }


            });

            $("body").on("click",".delete-saved-user",function(e){
               // alert();return false;
                var id = $(this).data('id');
                var _this = $(this);

                $.ajax({
                    url:"{!! env('APP_URL') !!}delete/saved/user/"+id,
                    success:function(response){
                        window.location.href = "{!! env('APP_URL') !!}users";

                    }
                });

                 e.preventDefault();
                e.stopPropagation();
            });

           
        })
    </script>
@endsection