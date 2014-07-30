@extends('layout')
@section('content')

<div class="container">
    <div class="row top-buffer">
        <div class="span8">
                <div id="main-content">
                    <p> Hai risposto correttamente a {{ $points }} domande su 25 </p> <br>
                    <p> {{ $outcome_str }} </p>
                    <div id="input" class="top-buffer col-md-6 col-md-offset-3">
                            <button class="btn btn-block btn-default" id="btn-filter" onclick="filter(0)" > Mostra solo gli errori </button>
                    </div> <br> <br> <br>
                    <div id="questions" class="row top-buffer">
                        <table class='table table-condensed'> 
                            <thead><tr>
                                <th class='large'>Domanda</th>
                                <th class='small'>Tua risposta</th>
                                <th class='large'>Esito</th>
                            </tr></thead>
                            <tbody>
                                @foreach ($questions as $index => $question)
                                    @if ($results[$index] == 1)
                                        <tr class="success">
                                    @else
                                        <tr class="danger">
                                    @endif
                                    <td> {{ $question[0]->question }} </td>
                                    @if ($answers[$index] == 1)
                                        <td> Vero </td>
                                    @else
                                        <td> Falso </td>
                                    @endif
                                    @if ($results[$index] == 1)
                                        <td> Corretto </td>
                                    @else
                                        <td>
                                            <b> Errato </b> <br>
                                            @if ($question[0]->isTrue == 1)
                                                Il quesito è vero
                                            @else
                                                {{ $question[0]->correction }}
                                            @endif
                                        </td>
                                    @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>
    <div class="row top-buffer">
        <div class="row">
            <div id="begindiv">
                <button style="float:left; width:30%; margin-left:15%" type="button" id="beginquiz" class="btn btn-primary btn-large btn-block" onclick='location.href="{{ url('/') }}/newquiz/0"'>Nuovo quiz (facile)</button>
                <button style="width:30%; margin-left:55%"type="button" id="beginquiz" class="btn btn-primary btn-large btn-block" onclick='location.href="{{ url('/') }}/newquiz/1"'>Nuovo quiz (difficile)</button> <br>
            </div>
        </div>
        <div id="footer">
            <div class="container">
                <p class="muted credit text-center">Creato da Luca Marchesi e Luca Chiodini.</p>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

$(document).ready(function() {
    $(window).scroll(function() {
        if ($(document).height()*0.66 <= ($(window).height() + $(window).scrollTop()))
        {
            if ($(".bootstrap-growl").length == 0)
                $.bootstrapGrowl("Hai dei suggerimenti? Scrivi a <a href=\"mailto:{{$_ENV['GMAIL_ADDRESS']}}\">{{$_ENV['GMAIL_ADDRESS']}}</a>!", {
                  ele: 'body', // which element to append to
                  type: 'info', // (null, 'info', 'error', 'success')
                  offset: {from: 'bottom', amount: 80}, // 'top', or 'bottom'
                  align: 'right', // ('left', 'right', or 'center')
                  width: 300, // (integer, or 'auto')
                  allow_dismiss: true,
                  delay: 60000,
                  stackup_spacing: 10 // spacing between consecutively stacked growls.
                });
        }
        else 
            $(".bootstrap-growl").alert('close');                                    
    });
}); 

$.ajax({
        type: "GET",
        url: "{{url('/')}}/savequiz",
        async: true,
        success : function() {
            // TODO Subsequential update needed
            showSaveResult(true);
        },
});

</script>

@stop