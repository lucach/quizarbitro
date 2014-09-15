@extends('layout')
@section('assets')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery.fileupload.css') }}">
<script type="text/javascript" src="assets/javascript/vendor/jquery.ui.widget.js"></script>
<script type="text/javascript" src="assets/javascript/jquery.iframe-transport.js"></script>
<script type="text/javascript" src="assets/javascript/jquery.fileupload.js"></script>
<script type="text/javascript" src="assets/javascript/jquery.fileupload-process.js"></script>
<script type="text/javascript" src="assets/javascript/jquery.fileupload-validate.js"></script>

<script type="text/javascript">
    function changePassword()
    {
        $('#submit-btn').prop('disabled', true);
        $('#submit-btn').html('Elaborazione in corso...');
        $.ajax({
            type: 'GET',
            url: '{{ url('/') }}' + '/password/reset',
            success: function()
            {
                $('#submit-btn').html('Mail con le istruzioni invata');
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
        <div class="row top-buffer">
          <div class="col-md-8">
            <form class="form-horizontal" action="profile" method="POST" role="form">
              <div class="form-group">
                <label class="col-lg-3 control-label">Username</label>
                <div class="col-lg-3">
                  <p>{{ $username }}</p>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 text-right control-label">Nome e Cognome</label>
                <div class="col-lg-3">
                  <p>
                    @if ($name != "")
                      {{ $name }}
                    @else
                      <i>Non impostato</i>
                    @endif
                  </p>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Email</label>
                <div class="col-lg-3">
                  <p>{{ $mail }}</p>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Qualifica</label>
                <div class="col-lg-5">
                    {{ Form::select('titles', $titles , null, array('class' => 'form-control')) }}
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Sezione</label>
                <div class="col-lg-5">
                    {{ Form::select('sections', $sections , null, array('class' => 'form-control')) }}
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Categoria</label>
                <div class="col-lg-5">
                    {{ Form::select('categories', $categories , null, array('class' => 'form-control')) }}
                </div>
              </div>

              <div class="form-group" id="form-submit">
                <div class="col-lg-5 col-lg-offset-3">
                  {{ Form::submit('Aggiorna informazioni', array('class' => 'btn btn-primary btn-block')) }}
                </div>
              </div>

              <hr>

              <div class="form-group">
                <label class="col-lg-3 control-label">Password</label>
                <div class="col-lg-5">
                  <button id="submit-btn" class="form-control btn btn-default" onclick="changePassword()">Modifica la password</button>
                </div>
              </div>
            </form>
          </div> <!-- End of the left column -->
          <div class="col-md-4">
            {{ HTML::image('profile-images/'.$profile_image_path, 'Immagine del profilo', array('style' => 'max-width:100%')) }}
            <div class="top-buffer">
              <button type="button" class="form-control btn btn-warning" onclick="$('#image-upload-modal').modal('show')">
                Cambia l'immagine del profilo
              </button>
            </div>
          </div>
        </div>
    </div>
    <!-- Modal for image uploading-->
    <div class="modal fade" id="image-upload-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Modifica dell'immagine del profilo</h4>
          </div>
          <div class="modal-body" id="modal-body">
            <span class="btn btn-success fileinput-button">
                <i class="glyphicon glyphicon-plus"></i>
                <span>Scegli un'immagine...</span>
                <input id="fileupload" type="file" name="files[]" multiple>
            </span>
            <br>
            <div id="error"></div>
            <br>
            <div id="progress" class="progress">
                <div class="progress-bar progress-bar-success"></div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
        if (sessionStorage.getItem("notify-success"))
        {
          showNotification('success', sessionStorage.getItem("notify-success"));
          sessionStorage.removeItem("notify-success");
        }
        if (sessionStorage.getItem("notify-error"))
        {
          showNotification('danger', sessionStorage.getItem("notify-error"));
          sessionStorage.removeItem("notify-error");
        }
        @if (Session::has('success'))
            showNotification('success', "{{ Session::get("success") }}");
        @endif
        $(function() {
          $('#fileupload').fileupload({
              url: '/profile/images/' + '{{ md5( Auth::user()->mail )}}',
              acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
              maxFileSize: 5000000
          }).on('fileuploadprocessalways', function (e, data) {
            var file = data.files[0];
            if (file.error)
              $("#error").html($('<span class="text-danger"/>').text(file.error));
          }).on('fileuploadprogressall', function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress .progress-bar').css('width', progress + '%');
          }).on('fileuploaddone', function (e, data) {
            sessionStorage.setItem("notify-success",
              "Immagine del profilo cambiata con successo.");
            location.reload();
          }).on('fileuploadfail', function (e, data) {
            sessionStorage.setItem("notify-error",
              "Errore durante il caricamento dell'immagine.")
          }).prop('disabled', !$.support.fileInput)
              .parent().addClass($.support.fileInput ? undefined : 'disabled');
        });
    </script>

@stop
