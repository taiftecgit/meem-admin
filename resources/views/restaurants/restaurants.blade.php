@extends('layouts.app')
@section('css')
    <link href="{!! env('APP_ASSETS') !!}css/dashboard.css" rel="stylesheet" type="text/css">
    @endsection


    @section('content')
    <div class="content-wrapper">
        <div class="container-full">
             <section class="content">
                <div class="row">
                    <div class="col-6"><h3 style="margin-left: 10px">Businesses</h3></div>
                    <div class="col-6 text-end"><a href="{!! env('APP_URL') !!}restaurant/new" class="btn btn-sm btn-danger float-right"><i class="glyphicon glyphicon-plus"></i> Add New </a></div>
                </div>

                <div class="row">
        <div class="col-xl-12">
            <div class="card mb-4">
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr class="text-uppercase">
                               <!-- <th>Resto</th>-->
                                <th width="30%">Resto Name</th>{{--
                                <th>Short Description</th>--}}
                                <th width="30%">Address</th>{{--
                                <th>Phone</th>--}}

                                <td>Status</td>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!----><!---->
                            @if(isset($restaurants) && $restaurants->count() > 0)
                                @foreach($restaurants as $restaurant)
                            <tr>

                                <td> {!! $restaurant->name !!}  </td>
                               {{-- <td>{!! $restaurant->short_description !!}</td>--}}
                                <td>{!! nl2br($restaurant->address) !!}</td>{{--
                                <td>{!! $restaurant->phone !!}</td>--}}

                                <td>{!! $restaurant->users->is_active?'<span class="badge badge-success">Active</span>':'<span class="badge badge-danger">In-active</span>' !!}</td>
                                <td>
                                    <!-- <a href="{!! env('APP_URL') !!}restaurant/show/{!! \App\Helpers\CommonMethods::encrypt($restaurant->id) !!}" class="btn btn-success btn-sm"  data-toggle="tooltip" data-placement="top" title="View"><i class="feather-eye"></i></a> -->
                                    <a href="{!! env('APP_URL') !!}restaurant/edit/{!! \App\Helpers\CommonMethods::encrypt($restaurant->id) !!}" class="btn btn-sm btn-primary"  data-toggle="tooltip" data-placement="top" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
                                    <a href="javascript:;" data-id="{!! $restaurant->id !!}" class="btn btn-sm btn-danger delete-restaurant"  data-toggle="tooltip" data-placement="top" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
                                    <a href="#!" class="btn btn-sm btn-info create-credentials" data-id="{!! $restaurant->id !!}"  data-toggle="tooltip" data-placement="top" title="New Credentials"><i class="glyphicon glyphicon-lock"></i></a>
                                </td>
                            </tr>
                            @endforeach
								
                          @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

             </section>
         </div>
     </div>
     <div class="modal" id="create-credentials" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rest Login Details </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body">
                <p>You can reset login credentails after submitting by <strong>Save Credentails</strong></p>
                <table border="0" style="width: 100%">
                    <tr>
                        <th width="30%">Username</th>
                        <td id="username"></td>
                    </tr>
                    <tr>
                        <th>Password</th>
                        <td id="password"></td>
                    </tr>
                </table>
                <div class="alert alert-success" style="display: none;">Credentails are created successfully.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary save-credentails">Save Credentails</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
     @endsection



@section('js')
    <script>
        var resto_id = 0;
        $(function () {
             $('#dataTable').DataTable({
                "bSort": true,
                "searching": true,
                "paging": true,
                "info": true,
				 lengthMenu: [
					[25, 50, 100, -1],
					[25, 50, 100, 'All'],
				],

                language: {
                    paginate: {
                        next: '<img src="{!! env("APP_ASSETS") !!}images/icons/next.png">', // or '→'
                        previous: '<img src="{!! env("APP_ASSETS") !!}images/icons/preivew.png">' // or '←'
                    }
                },

            });
            $("body").on('click','.delete-restaurant',function () {
                var id = $(this).data('id');
                
                $.ajax({
                    url:"{!! env('APP_URL') !!}restaurant/delete/"+id,
                    success:function (response) {
                        location.reload();
                    }
                });
            });

            $("body").on('click','.create-credentials',function () {
                var id = $(this).data('id');
                resto_id = id;
                $.ajax({
                    url:"{!! env('APP_URL') !!}restaurant/get/credentials/"+id,
                    success:function (response) {
                        $("#username").html(response.username);
                        $("#password").html(response.password);
                        $("#create-credentials").modal('show');
                    }
                });
            });

            $("body").on('click','.save-credentails',function () {
              
               var password =  $("#password").html();
               
               $.ajax({
                   url:"{!! env('APP_URL') !!}update/password",
                   type:"POST",
                   data:{
                       resto_id:resto_id,
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
        })
    </script>
    @endsection