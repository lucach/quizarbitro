@extends('layout')
@section('content')
    <div class="container">
        <div class="row">
          <p class="big top-buffer text-center">Alcune informazioni sul tuo profilo</p>
        </div>
        <form class="form-horizontal" action="profile" method="POST" role="form" style="margin-top:10%">
          <div class="form-group">
            <label class="col-lg-2 control-label">Username</label>
            <div class="col-lg-4">
              <p>{{ $username }}</p>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Nome</label>
            <div class="col-lg-4">
              <p>{{ $name }}</p>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Email</label>
            <div class="col-lg-4">
              <p>{{ $mail }}</p>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Qualifica</label>
            <div class="col-lg-4">
                {{ Form::select('titles', $titles , null, array('class' => 'form-control')) }}
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Sezione</label>
            <div class="col-lg-4">
                {{ Form::select('sections', $sections , null, array('class' => 'form-control')) }}
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Categoria</label>
            <div class="col-lg-4">
                {{ Form::select('categories', $categories , null, array('class' => 'form-control')) }}
            </div>
          </div>

          <div class="form-group" id="form-submit">
            <div class="col-lg-4 col-lg-offset-2">
              {{ Form::submit('Aggiorna informazioni', array('class' => 'btn btn-primary btn-block')) }}
            </div>
          </div>

          <hr>

          <div class="form-group">
            <label class="col-lg-2 control-label">Password</label>
            <div class="col-lg-4">
              <input id="submit-btn" class="form-control btn btn-default" value="Modifica la password" onclick="changePassword()" />
            </div>
          </div>

        </div>
      </form>
    </div>
    <script type="text/javascript">
        @if (Session::has('success'))
            showNotification('success', "{{ Session::get("success") }}");
        @endif
    </script>
@stop
