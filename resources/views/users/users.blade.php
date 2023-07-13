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
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Main content -->
            <section class="content">

                <div class="row ">
                    <div class="col-md-6">
                        <div class="m-15">
                            <h3 class="title">{{__('label.users')}}</h3>
                            <p>{{__('label.invite_users_and_managers_their_access_to_your_account')}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <a href="{!! env('APP_URL') !!}invite" class="form-control btn btn-primary btn-md add-outlet  text-center">
                            <i class="fa  fa-plus mr-2"></i>
                            <!-- <i class="mdi mdi-plus-circle"></i> --> {{__('label.invite_user')}}
                        </a>
                    </div>
                </div>


                <div class="row mt-15">
                    <div class="col-md-12 pt-5">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="orange_text p-5" style="margin-left: 10px">{!! $users->count() !!} Users</h4>
                            <div class="form-group has-search position-relative">

                                <input type="text" class="form-control search-outlet" name="search" placeholder="{{__('label.search_user')}}">
                                <a href="#!" class="search-buttom position-absolute"><i class="fa fa-search"></i> </a>
                            </div>
                        </div>

                    </div>
                </div>


                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">{{__('label.user')}}</th>
                                    <th scope="col">{{__('label.role')}}</th>
                                    <th scope="col">{{__('label.access')}}</th>
                                    <th></th>

                                </tr>
                                </thead>
                                <tbody>
									 @php
                                                  $level = "All";
									@endphp
                                @if(isset($invited_users) && $invited_users->count() > 0)
                                    @foreach($invited_users as $user)
                                        <tr>
                                            <td>{!! $user->email !!}<span class="badge badge-warning" style="margin-left:10px">pending</span></td>
                                            <td><span class="badge badge-dark">{!! $user->role !!}</span></td>
                                            <td>
                                                @php
                                                  $level = "All";
                                                    if($user->access_level=="all")
                                                    $level = "All";
                                                    elseif($user->access_level=="selected-outlets"){
                                                        $s = explode(',',$user->selected_outlets);
                                                        if(count($s) > 1){
                                                         $level = (count($s)). ' Outlet(s)';;
                                                     }else{

                                                        $level = (count($s)). ' Outlet';;
                                                     }


                                                    }
                                                @endphp
                                                {!! $level !!}
                                            </td>
                                            <td>
                                                <a href="javascript:;" data-id="{!! $user->unique_key !!}" class="btn btn-sm btn-danger delete-user"  data-toggle="tooltip" data-placement="top" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
                                                <a href="javascript:;" data-id="{!! $user->unique_key !!}" class="btn btn-sm btn-danger resend-link"  data-toggle="tooltip" data-placement="top" title="Resend Invite"><i class="glyphicon glyphicon-repeat"></i></a>
                                            </td>
                                        </tr>

                             @endforeach
                                    @endif

                                     @if(isset($users) && $users->count() > 0)
                                    @foreach($users as $user)
                                        <tr data-id="{!! $user->unique_key !!}">
                                            <td><h5>{!! $user->first_name.' '.$user->last_name !!}</h5> <p>{!! $user->email !!}</p></td>
                                            <td><span class="badge badge-dark">{!! $user->role !!}</span></td>
                                            <td>
                                                @php

                                                    if($user->access_level=="all")
                                                    $level = "All";
                                                    elseif($user->access_level=="selected-outlets"){
                                                        $s = explode(',',$user->selected_outlets);
                                                        if(count($s) > 1){
                                                         $level = (count($s)). ' Outlet(s)';;
                                                     }else{

                                                        $level = (count($s)). ' Outlet';;
                                                     }


                                                    }
                                                @endphp
                                                {!! $level !!}
                                            </td>
                                            <td>
                                                <a href="javascript:;" data-id="{!! $user->id !!}" class="btn btn-sm btn-danger delete-saved-user"  data-toggle="tooltip" data-placement="top" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
                                                <a href="javascript:;" data-id="{!! $user->id !!}" class="btn btn-sm btn-danger credentials"  data-toggle="tooltip" data-placement="top" title="New Credentials"><i class="glyphicon glyphicon-lock"></i></a>
                                            </td>
                                        </tr>

                             @endforeach
                                    @endif

                                </tbody>
                            </table>
                        </div>

            </section>
            <!-- /.content -->
        </div>
    </div>
    <!-- /.content-wrapper -->

<div class="modal" id="send-credentials" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">{{__('label.user_new_credentails')}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
           <p>{{__('label.you_credentails_submitting_by')}} <strong>{{__('label.save_credentails')}}</strong></p>
                <table border="0" style="width: 100%">
                    <tr>
                        <th width="30%"></th>
                        <td id="username"></td>
                    </tr>
                    <tr>
                        <th>{{__('label.password')}}</th>
                        <td id="password"></td>
                    </tr>
                </table>
                <div class="alert alert-success" style="display: none;">{{__('label.credentails_successful')}}</div>
      </div>
      <div class="modal-footer">
                    <button type="button" class="btn btn-primary save-credentails">{{__('label.save_credentials')}}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>

  </div>
</div>
</div>

<div class="modal" id="send-invitation-link" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">{{__('label.resend_invitation_link')}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

                <table border="0" style="width: 100%">
                    <tr>

                        <td id="link">
                            <img src="{!! env('APP_ASSETS') !!}images/preloader-1.svg" style="height: 25px;">
                        </td>
                    </tr>

                </table>

      </div>


  </div>
</div>
</div>



@endsection

@section('js')
    <script>
        var user_id = 0;
        $(function () {


            $('.table').DataTable({
                paging: true,
                bLengthChange:false,
                searching: false,
                 language: {
                    @if($lang=='ar')
                    url:`{{asset('public/assets/js/dataTablear.json')}}`,
                    @endif
                },

            });


 $("body").on('click','.save-credentails',function () {
            var password =  $("#password").html();

               $.ajax({
                   url:"{!! env('APP_URL') !!}user/update/password",
                   type:"POST",
                   data:{
                       user_id:user_id,
                       password:password,
                       '_token':"{!! csrf_token() !!}"
                   },
                   success:function (response) {
                        $("#create-credentials .alert").show();

                        setTimeout(function () {
                            location.reload();
                        },1500)
                   }
               });

            });


            $("body").on("click",".credentials",function(e){
                var _id = $(this).data('id');
                user_id = _id;
                        $("#username").html('<img src="{!! env('APP_ASSETS') !!}images/preloader-1.svg" style="height: 25px;">');
                        $("#password").html('<img src="{!! env('APP_ASSETS') !!}images/preloader-1.svg" style="height: 25px;">');
                $.ajax({
                    url:"{!! env('APP_URL') !!}user/get/credentials/"+_id,
                    success:function (response) {
                        $("#username").html(response.username);
                        $("#password").html(response.password);

                    }
                });

                $("#send-credentials").modal('show');
                e.preventDefault();
                e.stopPropagation();
            });



        $("body").on("click",".resend-link",function(e){
                var _id = $(this).data('id');

$("#send-invitation-link").modal('show');
                $.ajax({
                    url:"{!! env('APP_URL') !!}get/invitation/info/"+_id,
                    success:function (response) {

                        $("#link").html(response.message);


                        setTimeout(function(){
                            location.reload();
                        },2500);
                    }
                });


                e.preventDefault();
                e.stopPropagation();
            });




            $("body").on("click",".delete-user",function(e){
                var id = $(this).data('id');
                var _this = $(this);

                $.ajax({
                    url:"{!! env('APP_URL') !!}delete/invitation/"+id,
                    success:function(response){
                        _this.parents('tr').remove();

                    }
                });
                e.preventDefault();
                e.stopPropagation();
            });

            $("body").on("click","tr",function(){
                var id = $(this).data('id');
              //  alert(id);

                if(id)
                window.location.href = "{!! env('APP_URL') !!}user/tanent/"+id;
            });

            $("body").on("click",".delete-saved-user",function(e){
                var id = $(this).data('id');
                var _this = $(this);

                $.ajax({
                    url:"{!! env('APP_URL') !!}delete/saved/user/"+id,
                    success:function(response){
                        _this.parents('tr').remove();

                    }
                });

                 e.preventDefault();
                e.stopPropagation();
            });


        })
    </script>
@endsection
