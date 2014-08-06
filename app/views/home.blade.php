@extends('layout')
@section('content')
    <h4 class="text-center row top-buffer">Benvenuto {{ Auth::user()->name }}. Per iniziare, scegli un link dal menu in alto.</h4>

    <div class="row" style="margin-top:5%">
        <div class="col-md-offset-1 col-md-4" style="height:450px">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Ultime notizie dal mondo AIA</h3>
                </div>
                <div class="panel-body">
                    <a class="twitter-timeline" href="https://twitter.com/AIA_it" data-widget-id="494134225658851331" height="400">Tweets di @AIA_it</a>
                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                </div>
            </div>
        </div>
        <div class="col-md-offset-1 col-md-5">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Quiz recenti</h3>
                </div>
                <div id="last_quiz_datetime" class="panel-body">
                    </i>Caricamento...</i>
                </div>
            </div>
        </div>
        <div class="col-md-offset-1 col-md-5">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Grafico del rendimento</h3>
                </div>
                <div class="panel-body">
                    <div id="chartPlaceholder" style="height:284px">
                        <p class="text-center"><i>Caricamento del grafico in corso...</i></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

<script type="text/javascript">
    @if (Session::has('info'))
        showNotification('info', "{{ Session::get("info") }}");
    @endif
    @if (Session::has('error'))
        showNotification('danger', "{{ Session::get("error") }}");
    @endif

    $(function(){
        showHistoryChart();
        moment.lang('it');
        var last_quiz_datetime = moment.utc('{{ $last_quiz_datetime }}').local();
        if (!last_quiz_datetime.isValid())
            $('#last_quiz_datetime').html("Nessun quiz svolto finora.");
        else
            $('#last_quiz_datetime').html("<p> Ultimo quiz svolto alle " + last_quiz_datetime.format('H:m [di] dddd D MMMM') + " (" +
                last_quiz_datetime.fromNow() + ")</p>");
    });
</script>

@stop