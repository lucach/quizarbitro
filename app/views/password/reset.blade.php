@extends('simplelayout')
@section('content')
<div class="container">
<div class="row">
  <p class="big top-buffer text-center">Modifica della password</p>
</div>
 
{{ Form::open(array('class' => 'form-horizontal', 'style' => 'margin-top:10%', 'route' => array('password.update', $token))) }}
 
  <div class="form-group" id="form-password">             
	<label class="col-lg-2 control-label">Email</label>
	<div class="col-lg-4">
        {{ Form::text('email', Input::old('email'), array('placeholder' => 'Email', 'class' => 'form-control')) }}
	</div>
</div>
 
<div class="form-group" id="form-password">             
	<label class="col-lg-2 control-label">Password</label>
	<div class="col-lg-4">
    	{{ Form::password('password', array('placeholder' => 'Password', 'class' => 'form-control')) }}
	</div>
</div>
<div class="form-group" id="form-password-confirm">             
	<label class="col-lg-2 control-label">Reinserisci password</label>
	<div class="col-lg-4">
    	{{ Form::password('password_confirmation', array('placeholder' => 'Reinserisci password', 'class' => 'form-control')) }}
	</div>
</div>
  
  {{ Form::hidden('token', $token) }}
 
<div class="form-group" id="form-submit"><div class="col-lg-4 col-lg-offset-2">
{{ Form::submit('Invia', array('class' => 'btn btn-default btn-block')) }} 
</div></div>
 
{{ Form::close() }}

</div>
<script type="text/javascript">
@if (Session::has('error'))
    showNotification('danger', "{{ Session::get("error") }}");
@endif
</script>

@stop