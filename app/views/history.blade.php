@extends('layout')
@section('assets')
<script type="text/javascript" src="{{asset('assets/javascript/jquery.tablesorter.min.js')}}"></script>

    <script type="text/javascript">

    function load(questions, answers)
    {
           $.ajax({
            type: "POST",
            url: "{{url('/')}}/result",
            async: true,
            data: "questions=" + questions + "&answers=" + answers,
            success : function(html_data) {
                $("#questions").html(html_data);
                $("#myModal").modal("show");
            },
        });
    }

    $(function() {
        showHistoryChart();
        $("#table-hist").tablesorter(); 
    });

    </script>
    <style type="text/css">
        #myModal .modal-dialog
        {
          width: 75%;
        }
    </style>
@stop
@section('content')
    <h1 class="text-center top-buffer">Cronologia dei quiz svolti</h1>
    <p class="big text-center">Ecco il tuo l'andamento nei quiz!</p>
       
    <div class="row top-buffer">
        <div class="col-md-6 col-md-push-3">
        @if ($num == 0)
            <p class="center">Nessun quiz svolto finora. Iniziane subito uno!</p>
        @else
        <div id="chartPlaceholder" style="width:100%;height:300px;margin-bottom:5%">
            <p class="text-center"><i>Caricamento del grafico in corso...</i></p>            
        </div>

        <table id="table-hist" class="table tablesorter table-striped">
            <thead>
              <tr>
                <th>Data e ora</th>
                <th>Esito</th>
              </tr>
            </thead>
            <tbody> 
                @foreach ($rows as $row)
                <tr>
                    <td> {{ $row->created_at }} </td> <!-- TODO We're always using UTC thus confusing the user -->
                    <td> {{ $row->good_answers }}/30
                        <input style="margin-right:50px; float:right" value="Dettagli &raquo;" type="button" class="btn btn-default" 
                            onclick='load( "{{ str_replace('"','\"',$row['questions']) }}" ,"{{ str_replace('"','\"',$row['answers']) }}")' /></td>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Dettagli del quiz</h4>
          </div>
          <div class="modal-body">
            <div id="questions"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
          </div>
        </div>
      </div>
    </div>
@stop