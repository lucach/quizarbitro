@extends('layout')
@section('assets')
    <style type="text/css">
        p {
            vertical-align: bottom;
            line-height: 4ex;
        }
    </style>
    <script type="text/javascript">
    function changePassword()
    {
        $("#submit-btn").prop('disabled', true);
        $("#submit-btn").attr('value', "Caricamento...");
        $.ajax({
        type: "GET",
        url: '{{ url('/') }}' + '/password/reset',
        success: function()
        {   
            $("#submit-btn").attr('value', "Mail con le istruzioni invata");
            showNotification('success', 'Ti è stata inviata una mail con le informazioni per cambiare la password.');
        }
    });

    }
    </script>
@stop
@section('content')
    <div class="container">
        <div class="row">
          <p class="big top-buffer text-center">Alcune informazioni sul tuo profilo</p>
        </div>
        <form class="form-horizontal" action="password/reset" role="form" style="margin-top:10%">
          <div class="form-group">
            <label class="col-lg-2 control-label">Nome</label>
            <div class="col-lg-4">
              <p>{{ $name }}</p>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Mail</label>
            <div class="col-lg-4">
              <p>{{ $mail }}</p>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Password</label>
            <div class="col-lg-4">
              <input id="submit-btn" class="form-control btn btn-default" value="Modifica la password" onclick="changePassword()" />
            </div>
          </div>
        </div>
      </form>
    </div>
@stop