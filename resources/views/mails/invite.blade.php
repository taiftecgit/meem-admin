<div style="max-width: 600px;">

<p>Hello! </p>

<p><strong>{!! $shop_name !!}</strong> has invited you to access {!! env('MAIL_FROM_NAME') !!} as {!! $role !!}. You will be able to see information from the following business(es): </p>
@if($access_level=="all")
<p>All outlets</p>
@else
	<p style="margin-bottom: 20px">{!! $selected_outlets !!}</p>
@endif


<p>To accept the invitation and create your profile, click on the button below or copy and paste the following link on your browser: </p>


{!! $link !!}

<div class="" style="text-align: center; margin-top: 10px;">
	
	<a href="{!! $link !!}" style="padding:7px 15px; text-align: center; background: orange;">Create Account</a>
</div>
</div>



