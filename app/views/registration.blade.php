@extends('simplelayout')
@section('content')
<div class="container">
<div class="row">
  <p class="big top-buffer text-center">Statistiche, revisione degli errori e tanto altro. Registrati ora gratuitamente!</p>
</div>
 
{{ Form::open(array('class' => 'form-horizontal', 'style' => 'margin-top:10%', 'url' => 'registration')) }}

  <div class="form-group" id="form-username">
    <label class="col-lg-2 control-label">Username</label>
    <div class="col-lg-4">
        {{ Form::text('username', Input::old('username'), array('placeholder' => 'Scegli un username', 'class' => 'form-control')) }}
    </div>
  </div>

  <div class="form-group" id="form-name">
    <label class="col-lg-2 control-label">Nome</label>
    <div class="col-lg-4">
        {{ Form::text('name', Input::old('name'), array('placeholder' => 'Il tuo nome', 'class' => 'form-control')) }}
    </div>
    <label id="name-warning" class="col-lg-2 control-label" style="display:none; text-align:left; font-weight: normal">Non sarà pubblico.</label>
  </div>

  <div class="form-group" id="form-email">             
    <label class="col-lg-2 control-label">Email</label>
    <div class="col-lg-4">
        {{ Form::text('mail', Input::old('mail'), array('placeholder' => 'La tua mail', 'class' => 'form-control')) }}
    </div>
  </div>
  <div class="form-group" id="form-password">
      <label class="col-lg-2 control-label">Password</label>
      <div class="col-lg-4">
          {{ Form::password('password', array('placeholder' => 'Scegli una password', 'class' => 'form-control')) }}
      </div>
  </div>

  <div class="form-group" id="form-title">
      <label class="col-lg-2 control-label">Qualifica</label>
      <div class="col-lg-4">
          {{ Form::select('titles', $titles , Input::old('titles'), array('class' => 'form-control')) }}
      </div>
  </div>

  <div class="form-group" id="form-section">
      <label class="col-lg-2 control-label">Sezione d'appartenenza</label>
      <div class="col-lg-4">
          {{ Form::select('sections', $sections , Input::old('sections'), array('class' => 'form-control')) }}
      </div>
  </div>

  <div class="form-group" id="form-section">
      <label class="col-lg-2 control-label">Categoria</label>
      <div class="col-lg-4">
          {{ Form::select('categories', $categories , Input::old('categories'), array('class' => 'form-control')) }}
      </div>
  </div>

  <div class="form-group" id="form-submit">
    <div class="col-lg-4 col-lg-offset-2">
      {{ Form::submit('Invia', array('class' => 'btn btn-default btn-block')) }}
    </div>
  </div>

{{ Form::close() }}

</div>
<script type="text/javascript">
@foreach ($errors->all() as $message)
    showNotification('danger', "{{ $message }}");
@endforeach

$('#form-name').focusin(function() {
    $('#name-warning').show();
});

</script>

@stop
