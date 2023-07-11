@extends('layouts.app')
@section('css')
    <link href="{!! env('APP_ASSETS') !!}css/dashboard.css" rel="stylesheet" type="text/css">
    @endsection


    @section('content')
    <div class="content-wrapper">
        <div class="container-full">
             <section class="content">
                <div class="row">
                    <div class="col-6"><h3 style="margin-left: 10px">Lable Translations for :  <span class="badge badge-info">{!! $translation_for !!}</span>
					</h3></div>
                    <div class="col-6 text-end">
						
						<a href="#!" class="btn btn-sm btn-danger float-right add-new"><i class="glyphicon glyphicon-plus"></i> Add New </a>
						
						<a href="#!" class="btn btn-sm btn-primary float-right download" data-lang="en"><i class="glyphicon glyphicon-download"></i> 
						@if($translation_for=='admin')
						<span>UPDATE EN</span>
						@else
							<span>Download EN</span>
						@endif
					
					</a>
					
						<a href="#!" class="btn btn-sm btn-primary float-right download" data-lang="ar"><i class="glyphicon glyphicon-download"></i> 
						@if($translation_for=='admin')
						<span>UPDATE AR</span>
						@else
							<span>Download AR</span>
						@endif
						</a>
					
					</div>
                </div>

                <div class="row">
        <div class="col-xl-12">
            <div class="card mb-4">
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr class="text-uppercase">
                                <th width="20%">Key</th>
                                <th width="25%">EN</th>
                                <th width="25%">AR</th>
                                
                                <th  width="20%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!----><!---->
                            @if(isset($translations) && count($translations) > 0)
                                @foreach($translations as $k=>$translation)
								
                            <tr>
                             
                               <td>{!!  $k !!}</td>
                                <td width="45%">{!! isset($translation['en'])?$translation['en']:"" !!}</td>
                                <td>{!!  isset($translation['ar'])?$translation['ar']:"" !!}</td>
                                
                                <td>
                                   <a href="#!" class="btn btn-sm btn-primary  edit-translation" data-id="{!! $k !!}"  data-toggle="tooltip" data-placement="top" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
                                   
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
      <div class="modal" id="create-translation" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add/Update Translation </h5>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body">
        <form id="translation-form" action="{!! env('APP_URL') !!}save/translation" enctype="multipart/form-data" method="POST">
				@csrf
			<input type="hidden" name="type" value="{!! $translation_for !!}" />
			<input type="hidden" name="id" />
 			 <div class="form-group" id="item_key">
				 <label>Item Key</label>
				 <input type="text" class="form-control" name="item_key" required />
			 </div>
			<div class="form-group">
				 <label>Text in English</label>
				 <input type="text" class="form-control" name="item_en" required />
			 </div>
			<div class="form-group">
				 <label>Text in Arabic</label>
				 <input type="text" class="form-control" name="item_ar" required />
			 </div>
			
			
				</form>
				 <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="alert alert-success success"></div>
                                    <div class="alert alert-danger error"></div>
                                </div>
                  </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary save-translation">Save</button>
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
     @endsection



@section('js')
<script src="{!! env("APP_ASSETS") !!}js/dataTables.editor.min.js"></script> 
    <script>
        var resto_id = 0;
		
		
		
        $(function () {
			
			
			
			$("body").on("click",".edit-translation",function(){
				var _key = $(this).data('id');
				$("#create-translation input[name=id]").val(_key);
				var _this = $(this);
				$("input[name=item_key]").removeAttr('required');
				$("#item_key").hide();
				
				var _english = $(this).parents("tr").find("td:eq(1)").text();
				var _arabic = $(this).parents("tr").find("td:eq(2)").text();
				
				$("input[name=item_en]").val(_english);
				$("input[name=item_ar]").val(_arabic);
				
				
			$("#create-translation").modal('show');
				
			});
			
			$("body").on("click",".add-new",function(){
				$("input[name=item_en]").val('');
				$("input[name=item_ar]").val('');
				
				
				$("input[name=item_key]").attr('required','required');
				$("#item_key").show();
				
				$("#create-translation").modal('show');
				
			});
			
			$("body").on("click",".close-modal",function(){
				
				$("#create-translation").modal('hide');
				
			});
			
			$("body").on("click",".download",function(){
				var _lang = $(this).data('lang');
				var _type = "{!! $translation_for !!}";
				
				if(_type=="admin"){
					$.ajax({
					url:"{!! env('APP_URL') !!}download/translation/"+_lang+"/"+_type,
					success:function(response){
						if(_type=="admin")
						alert('Downloaded');
						else{
							//window.location=response;
//							var a = document.createElement('a')
//							a.href=response;
//							a.download='translation.json';
//							document.body.append(a);
						}
							
					}
				});
				}else{
					window.location = "{!! env('APP_URL') !!}download/translation/"+_lang+"/"+_type;
				}
				
				
				
			});
			
			$("body").on("click",".save-translation",function(){
				if($("#translation-form").valid()){
					$("#translation-form").ajaxForm(function(response){
						if(response){

                            if(response.type=="success"){
                                $('#translation-form .alert.success').html(response.message);
                                $('#translation-form .alert.success').show();

                                setTimeout(function(){
                                    //window.location = '{!! env('APP_URL') !!}recipes';
                                    location.reload();
                                },2000)
                            }else{
                                $('#translation-form .alert.error').html(response.message);
                                $('#translation-form .alert.error').show();
                            }
                        }
					}).submit();
				}
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