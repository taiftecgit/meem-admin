@extends('layouts.app')
@section('css')
    <link href="{!! env('APP_ASSETS') !!}css/outlets.css?v=1.3" rel="stylesheet" type="text/css">
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

                <div class="row ">
                    <div class="col-md-6">
                        <div class="m-15">
                            <h3 class="title">{{__('label.invite_user')}}</h3>
                            <p>{{__('label.You_can_add_new_users')}}</p>
                        </div>
                    </div>

                </div>


                <div class="row ">
                    <div class="col-md-7">
                        <div class="m-15">
                            <h3 class="title">{{__('label.send_invite')}}</h3>
                            <div class="form-group">
                                <label>{{__('label.email_addresses')}}</label><br />
                                <input type="text" placeholder="{{__('label.valid_email_note')}}" name="email" id="emails" class="form-control" data-role="tagsinput" />
                                <br /><em><small>{{__('label.you_can_add_up_to')}}</small></em>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="row ">
                    <div class="col-md-7">
                        <div class="m-15">
                            <h3 class="title">{{__('label.user_role')}}</h3>

                            <div class="row user-type-section mb-25 m-0"  data-role="administrator">
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input " id="administrator-role" name="access-role" type="radio"/>
                                            <label class="custom-control-label" for="administrator-role"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <h4>{{__('label.administrator')}}  </h4>
                                    <p>{{__('label.can_access_and_manage_all_dashboard_features_for_selected_businesses')}}</p>

                                </div>

                                <div class="row  option-section">
                                    <div class="col-md-6">

                                        <div class="d-flex justify-content-center">
                                            <div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input choose-role-option" id="administrator-full-access"  data-access="all-outlets"  name="administrator-access" checked  type="radio"/>
                                                        <label class="custom-control-label" for="administrator-full-access"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <h5>{{__('label.access_all_business')}}</h5>
                                                <p>{{__('label.all_businesses_including_future_ones')}}</p>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-center">
                                            <div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input choose-role-option" id="administrator-selected-access"  data-access="selected-outlets" name="administrator-access" type="radio"/>
                                                        <label class="custom-control-label" for="administrator-selected-access"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <h5>{{__('label.selected_access')}}</h5>
                                                <p>{{__('label.can_access_selected_businesses')}}</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>






                            <div class="row user-type-section mb-25 m-0" data-role="manager">
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" id="manager-role" name="access-role" type="radio"/>
                                            <label class="custom-control-label" for="manager-role"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <h4>{{__('label.manager')}}</h4>
                                    <p>{{__('label.can_access_and_manage_live_orders_inventory_order_history_marketing_features_and_crm_from_selected_outlets')}}</p>

                                </div>

                                <div class="row option-section">
                                    <div class="col-md-6">

                                        <div class="d-flex justify-content-center">
                                            <div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input choose-role-option" value="administrator" id="manager-full-access" data-access="all-outlets"  name="manager-access" checked type="radio"/>
                                                        <label class="custom-control-label" for="manager-full-access"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <h5>{{__('label.access_all_outlets')}}</h5>
                                                <p>{{__('label.all_outlets_including_future_ones')}}</p>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-center role-based-option">
                                            <div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input choose-role-option" id="manager-selected-access"  data-access="selected-outlets"  value="selected" name="manager-access" type="radio"/>
                                                        <label class="custom-control-label" for="manager-selected-access"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <h5>{{__('label.selected_access')}}</h5>
                                                <p>{{__('label.can_access_selected_outlets')}} </p>

                                                 <div class="col-md-12 outlet-section">
                                                    @if(isset($outlets) && $outlets->count() > 0)
                                                    <ul  class="list-unstyled">
                                                        @foreach($outlets as $outlet)
                                                        <li>
                                                            <div class="custom-control custom-checkbox">
                                                                <input class="custom-control-input selected-outlets" id="outlet-manager-{!! $outlet->id !!}"  value="{!! $outlet->name !!}" name="" type="checkbox"/>
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


                            <div class="row user-type-section mb-25 m-0"  data-role="staff">
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" id="staff-role" name="access-role" type="radio"/>
                                            <label class="custom-control-label" for="staff-role"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <h4>{{__('label.staff')}}</h4>
                                    <p>{{__('label.can_manage_live_orders_inventory_and_store_operation_status_from_selected_outlets')}}</p>

                                </div>

                                <div class="row  option-section">
                                    <div class="col-md-6">

                                        <div class="d-flex justify-content-center">
                                            <div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input choose-role-option" value="administrator" id="staff-full-access" data-access="all-outlets" name="staff-access" checked type="radio"/>
                                                        <label class="custom-control-label" for="staff-full-access"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <h5>{{__('label.access_all_outlets')}}</h5>
                                                <p>{{__('label.all_outlets_including_future_ones')}}</p>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-center role-based-option">
                                            <div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input choose-role-option" id="staff-selected-access" data-access="selected-outlets" value="selected" name="staff-access" type="radio"/>
                                                        <label class="custom-control-label" for="staff-selected-access"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <h5>{{__('label.selected_access')}}</h5>
                                                <p>{{__('label.can_access_selected_outlets')}} </p>
                                                <div class="col-md-12 outlet-section">
                                                    @if(isset($outlets) && $outlets->count() > 0)
                                                    <ul  class="list-unstyled">
                                                        @foreach($outlets as $outlet)
                                                        <li>
                                                            <div class="custom-control custom-checkbox">
                                                                <input class="custom-control-input selected-outlets" id="outlet-staff-{!! $outlet->id !!}" value="{!! $outlet->name !!}" name="" type="checkbox"/>
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
                                    <button class="btn btn-primary send-invite">{{__('label.send_invite')}}</button>
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

            $("body").on("click",".send-invite",function(){
                $(".alert").hide();
                var role = $(".user-type-section.selected").data('role');
                var role_based_access = $(".user-type-section.selected .choose-role-option:checked").data('access');
                var selected_outlets = null;
                var emails = $("#emails").val();
                var errors = [];
                if(emails==""){
                    errors.push("No email address is added.");
                }

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

                $.ajax({
                    url:"{!! env('APP_URL') !!}send/invitation",
                    data:{
                        role:role,
                        role_based_access:role_based_access,
                        selected_outlets:selected_outlets,
                        emails:emails,
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
                                   window.location = "{!! env('APP_URL') !!}users";
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


        })
    </script>
@endsection
