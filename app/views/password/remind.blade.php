@extends('simplelayout')
@section('content')
<div class="container">
<div class="row">
  <p class="big top-buffer text-center">Reset della password</p>
</div>
 
{{ Form::open(array('class' => 'form-horizontal', 'style' => 'margin-top:10%', 'route' => 'password.request')) }}
 
  <div class="form-group" id="form-password">             
    <label class="col-lg-2 control-label">Email</label>
    <div class="col-lg-4">
        {{ Form::text('mail', Input::old('mail'), array('placeholder' => 'Email', 'class' => 'form-control')) }}
    </div>
</div>
 
<div class="form-group" id="form-submit"><div class="col-lg-4 col-lg-offset-2">
{{ Form::submit('Invia', array('class' => 'btn btn-default btn-block')) }} 
</div></div>
 
{{ Form::close() }}

</div>
<script type="text/javascript">
@if (Session::has('error'))
    showNotification('danger', "{{ Session::get("error") }}");
@endif
@if (Session::has('info'))
    showNotification('info', "{{ Session::get("info") }}");
@endif

</script>

@stop