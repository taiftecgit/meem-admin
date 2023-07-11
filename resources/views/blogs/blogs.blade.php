@extends('layouts.app')
@section('css')
    <link href="{!! env('APP_ASSETS') !!}css/dashboard.css" rel="stylesheet" type="text/css">
    @endsection


    @section('content')
    <div class="content-wrapper">
        <div class="container-full">
             <section class="content">
                <div class="row">
                    <div class="col-6"><h3 style="margin-left: 10px">Blogs  
					</h3></div>
                    @if(\Illuminate\Support\Facades\Auth::user()->role=="administrator" || \Illuminate\Support\Facades\Auth::user()->role=="admin_user" )
					<div class="col-6 text-end"><a href="{!! env('APP_URL') !!}blog/new" class="btn btn-sm btn-danger float-right"><i class="glyphicon glyphicon-plus"></i> Add New </a></div>
                   @endif
                </div>

                <div class="row">
        <div class="col-xl-12">
            <div class="card mb-4">
                
                <div class="card-body">
                    @if(\Illuminate\Support\Facades\Auth::user()->role=="administrator" || \Illuminate\Support\Facades\Auth::user()->role=="admin_user" )
                    <div class="table-responsive">
                        <table class="table table-stripped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr class="text-uppercase">
                                <th width="60%">Blog</th>
                                <th width="10%">Is Published</th>
                                <th width="10%">created At</th>
                                
                                <th  width="10%">Action</th> 
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($blogs) && $blogs->count() > 0)
								@foreach($blogs as $blog)
								<tr>
									<td><span class="fw-bold">{!! $blog->title !!}</span><br />
										<span class="text-muted"><small>{!! strip_tags(\Illuminate\Support\Str::substr($blog->content,0,150)) !!}</small></span>
									</td>
									<td>{!! $blog->is_published !!}</td>
									<td>{!! \Carbon\Carbon::parse($blog->created_at)->format('d F, Y H:i') !!}</td>
									<td>
									<a href="{!! env('APP_URL') !!}blog/edit/{!! $blog->id !!}" class="btn btn-sm btn-primary"  data-toggle="tooltip" data-placement="top" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
                                    <a href="javascript:;" data-id="{!! $blog->id !!}" class="btn btn-sm btn-danger delete-blog"  data-toggle="tooltip" data-placement="top" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
									</td>
								</tr>
								@endforeach
								@endif
                            
                            </tbody>
                        </table>
                    </div>
                    @else
                            <div class="p-3 text-white bg-danger text-center">You are not authorized for this page</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

             </section>
         </div>
     </div>
      
     @endsection



@section('js')
<script src="{!! env("APP_ASSETS") !!}js/dataTables.editor.min.js"></script> 
    <script>
        var resto_id = 0;
		
		
		
        $(function () {
			
			
			
			
			$("body").on("click",".close-modal",function(){
				
				$("#create-translation").modal('hide');
				
			});
			
			$("body").on("click",".delete-blog",function(){
				var _id = $(this).data('id');
				
				
swal({  
  title: " Confirm?",  
  text: "Do you want delete?",  
  type: "error",  
  showCancelButton: true,  
  confirmButtonClass: "btn-danger",  
  confirmButtonText: " Confirm, delete it!",  
  cancelButtonText: "No, cancel please!",  
  closeOnConfirm: true,  
  closeOnCancel: true  
},  
function(isConfirm) {  
  if (isConfirm) {  
    $.ajax({
		url:"{!! env('APP_URL') !!}blog/delete/"+_id,
		success:function(){
			location.reload();
		}
	});
  } 
});  
				
			});
          
            $('#dataTable').DataTable({
                "bSort": true,
                "searching": true,
                "paging": true,
                "info": true,

                language: {
                    paginate: {
                        next: '<img src="{!! env("APP_ASSETS") !!}images/icons/next.png">', // or '→'
                        previous: '<img src="{!! env("APP_ASSETS") !!}images/icons/preivew.png">' // or '←'
                    }
                },

            });
        })
    </script>
    @endsection