@extends('simplelayout')
@section('content')
<div class="container">
<div class="row">
  <p class="big top-buffer text-center">Statistiche, revisione degli errori e tanto altro. Registrati ora gratuitamente!</p>
</div>
 
{{ Form::open(array('class' => 'form-horizontal', 'style' => 'margin-top:10%', 'url' => 'registration')) }}

   <div class="form-group" id="form-name">             
	<label class="col-lg-2 control-label">Nome</label>
	<div class="col-lg-4">
        {{ Form::text('name', Input::old('name'), array('placeholder' => 'Nome', 'class' => 'form-control')) }}
	</div>
</div>

  <div class="form-group" id="form-email">             
	<label class="col-lg-2 control-label">Email</label>
	<div class="col-lg-4">
        {{ Form::text('mail', Input::old('mail'), array('placeholder' => 'Email', 'class' => 'form-control')) }}
	</div>
</div>
<div class="form-group" id="form-password">             
	<label class="col-lg-2 control-label">Password</label>
	<div class="col-lg-4">
    	{{ Form::password('password', array('placeholder' => 'Password', 'class' => 'form-control')) }}
	</div>
</div>

 
<div class="form-group" id="form-submit"><div class="col-lg-4 col-lg-offset-2">
{{ Form::submit('Invia', array('class' => 'btn btn-default btn-block')) }} 
</div></div>
 
{{ Form::close() }}

</div>
<script type="text/javascript">
        @foreach ($errors->all() as $message)
           showNotification('danger', "{{ $message }}");
        @endforeach

</script>

@stop