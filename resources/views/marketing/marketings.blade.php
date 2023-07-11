@extends('layouts.app')
@section('content')

@php
$resto = \App\Models\Restaurants::find(\App\Helpers\CommonMethods::getRestuarantID());
$lang = $resto->default_lang;

app()->setLocale($lang);
if(session('app_lang') !==null){
$lang = session('app_lang');
app()->setLocale($lang);

}
@endphp

<style>
    .vtabs .tab-content {
        display: table-cell;
        padding: 10px;
        vertical-align: top;
        width: 900px;
    }
</style>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <h4 class="page-title">{{__('label.marketings')}}</h4>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{!! env('APP_URL') !!}dashboard"><i class="mdi mdi-home-outline"></i></a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{__('label.marketings')}}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                </div>
            </div>


            <!-- Main content -->
            <section class="content">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">{{__('label.marketings')}}</h4>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Nav tabs -->
                            <div class="vtabs customvtab">
                                <ul class="nav nav-tabs tabs-vertical" role="tablist">
                                    <li class="nav-item"> <a class="nav-link active" data-bs-toggle="tab" href="#home3" role="tab" aria-expanded="true" aria-selected="false"><span class="hidden-sm-up"><i class="mdi-facebook"></i></span> <span class="hidden-xs-down">{{__('label.facebook')}}</span> </a> </li>
                                    <!-- <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#profile3" role="tab" aria-expanded="false" aria-selected="false"><span class="hidden-sm-up"><i class="ion-person"></i></span> <span class="hidden-xs-down">Whatsapp</span></a> </li> -->
                                    <li class="nav-item"> <a class="nav-link " data-bs-toggle="tab" href="#messages3" role="tab" aria-expanded="false" aria-selected="true"><span class="hidden-sm-up"><i class="ion-email"></i></span> <span class="hidden-xs-down">{{__('label.instagram')}}</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link " data-bs-toggle="tab" href="#messages4" role="tab" aria-expanded="false" aria-selected="true"><span class="hidden-sm-up"><i class="ion-email"></i></span> <span class="hidden-xs-down">{{__('label.google_business')}}</span></a> </li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane active" id="home3" role="tabpanel" aria-expanded="true">
                                        <div class="p-15">
                                            <h3>{{__('label.facebook_campaign_link')}}</h3>
                                            <input type="hidden" name="campaign_type" value="facebook" />
                                            <!-- <div class="row">
                                                <div class="form-group">
                                                    <label>Campaign Name</label>
                                                    <input type="text" name="campaign_name" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group position-relative">
                                                    <label>Campaign Date</label>
                                                    <input type="text" name="campaign_date" class="form-control date" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <a href="#!" class="btn btn-sm create btn-danger">Create Link</a>
                                                </div>
                                            </div> -->

                                            <div class="row mt-3">
                                                <div class="col-11">
                                                    <input type="text"  readonly id="link1" value="{!!  env('QRCODE_HOST_ORDER').'fd/'.$resto->resto_unique_name !!}" class="link form-control input-lg" />
                                                </div>
                                                <div class="col-1"><button class="btn btn-sm  btn-primary" onclick="copyme1('link1')"><i class="fa  fa-copy"></i> </button> </div>
                                            </div>
                                             </div>
                                    </div>
                                    <div class="tab-pane" id="profile3" role="tabpanel" aria-expanded="false">
                                        <div class="p-15">
                                            <h3>{{__('label.whatsapp_campaign_link')}}</h3>
                                            <input type="hidden" name="campaign_type" value="whatsapp" />
                                           <!--  <div class="row">
                                                <div class="form-group">
                                                    <label>Campaign Name</label>
                                                    <input type="text" name="campaign_name" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <label>Campaign Date</label>
                                                    <input type="text" name="campaign_date" class="form-control date" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <a href="#!" class="btn btn-sm create btn-danger">Create Link</a>
                                                </div>
                                            </div> -->
                                            <div class="row mt-3">
                                                <div class="col-11">
                                                    <input type="text"  readonly id="link2" value="{!!  env('QRCODE_HOST_ORDER').'d/'.$resto->resto_unique_name !!}?a=whatsapp" class="link form-control input-lg" />
                                                </div>
                                                <div class="col-1"><button class="btn btn-sm btn-primary" onclick="copyme2('link2')"><i class="fa  fa-copy"></i> </button> </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane " id="messages3" role="tabpanel" aria-expanded="false">
                                        <div class="p-15">
                                            <h3>{{__('label.instagram_campaign_link')}}</h3>
                                            <input type="hidden" name="campaign_type" value="instagram" />
                                            <!-- <div class="row">
                                                <div class="form-group">
                                                    <label>Campaign Name</label>
                                                    <input type="text" name="campaign_name" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <label>Campaign Date</label>
                                                    <input type="text" name="campaign_date" class="form-control date" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <a href="#!" class="btn btn-sm create btn-danger">Create Link</a>
                                                </div>
                                            </div> -->
                                            <div class="row mt-3">
                                                <div class="col-11">
                                                    <input type="text"  readonly id="link3" value="{!!  env('QRCODE_HOST_ORDER').'id/'.$resto->resto_unique_name !!}" class="link form-control input-lg" />
                                                </div>
                                                <div class="col-1"><button class="btn btn-sm  btn-primary" onclick="copyme3('link3')"><i class="fa  fa-copy"></i> </button> </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane " id="messages4" role="tabpanel" aria-expanded="false">
                                        <div class="p-15">
                                            <h3>{{__('label.google_campaign_link')}}</h3>
                                            <input type="hidden" name="campaign_type" value="instagram" />
                                            <!-- <div class="row">
                                                <div class="form-group">
                                                    <label>Campaign Name</label>
                                                    <input type="text" name="campaign_name" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <label>Campaign Date</label>
                                                    <input type="text" name="campaign_date" class="form-control date" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <a href="#!" class="btn btn-sm create btn-danger">Create Link</a>
                                                </div>
                                            </div> -->
                                            <div class="row mt-3">
                                                <div class="col-11">
                                                    <input type="text"  readonly id="link4" value="{!!  env('QRCODE_HOST_ORDER').'gd/'.$resto->resto_unique_name !!}" class="link form-control input-lg" />
                                                </div>
                                                <div class="col-1"><button class="btn btn-sm  btn-primary" onclick="copyme4('link4')"><i class="fa  fa-copy"></i> </button> </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
            </section>
            <!-- /.content -->

        </div>
    </div>
    <!-- /.content-wrapper -->




@endsection

@section('js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(function () {
        var site_url = "{!! env('QRCODE_HOST_ORDER')."delivery/".$resto->resto_unique_name !!}";
        $("input[name=campaign_date]").datepicker({dateFormat:"yy-mm-dd"});


        $("body").on("click",".create",function () {
            var _this = $(this);

            var _parent = _this.parents('.tab-pane');

            var campaign_name = _parent.find('input[name=campaign_name]').val();
            var campaign_date = _parent.find('input[name=campaign_date]').val();
            var campaign_type = _parent.find('input[name=campaign_type]').val();

            $.ajax({
                url:"{!! env('APP_URL') !!}create/campaign_link",
                data:{
                    campaign_name:campaign_name,
                    campaign_date:campaign_date,
                    campaign_type:campaign_type,
                    site_url:site_url,
                    "_token":'{!! csrf_token() !!}'

                },
                type:"POST",
                success:function (response) {
                    _parent.find('.link').val(response);
                }
            });


        });

        $("body").on("click",".copy",function (response) {
            var _parent = $(this).parents('.tab-pane');
           var _link =  _parent.find('.link').val().select();

            _link.select();
            _link.setSelectionRange(0, 99999); /* For mobile devices */

            /* Copy the text inside the text field */
            document.execCommand("copy");
            alert("Copied the text: " + _link.value);
        });
    })

    function copyme1() {
        /* Get the text field */

        var copyText = document.getElementById('link1');

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */

        /* Copy the text inside the text field */
        document.execCommand("copy");

        /* Alert the copied text */
        alert("Copied the text: " + copyText.value);
    }

    function copyme2() {
        /* Get the text field */

        var copyText = document.getElementById('link2');

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */

        /* Copy the text inside the text field */
        document.execCommand("copy");

        /* Alert the copied text */
        alert("Copied the text: " + copyText.value);
    }
    function copyme3() {
        /* Get the text field */

        var copyText = document.getElementById('link3');

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */

        /* Copy the text inside the text field */
        document.execCommand("copy");

        /* Alert the copied text */
        alert("Copied the text: " + copyText.value);
    }


function copyme4() {
        /* Get the text field */

        var copyText = document.getElementById('link4');

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */

        /* Copy the text inside the text field */
        document.execCommand("copy");

        /* Alert the copied text */
        alert("Copied the text: " + copyText.value);
    }

</script>
@endsection
