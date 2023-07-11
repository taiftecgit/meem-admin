<div style="max-width: 600px;">

<p>Hello! {!! $name !!}, </p>

<p>Your login credentials for {!! $shop_name !!} are:  </p>
<table border="0">
	<tr>
		<th>Username: </th>
		<td>{!! $username !!}</td>
	</tr>
	<tr>
		<th>Username: </th>
		<td>{!! $password !!}</td>
	</tr>
</table>


<div class="" style="text-align: center; margin-top: 10px;">

	<a href="{!! env('APP_URL') !!}" style="padding:7px 15px; text-align: center; background: orange;">Login here</a>
</div>
</div>



