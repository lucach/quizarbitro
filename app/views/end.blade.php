@extends('layout')
@section('content')

<div class="container">
    <div class="row top-buffer">
        <div class="span8">
                <div id="main-content">
                    <p>Hai risposto correttamente a {{ $points }} domande su 30.</p> <br>
                    <p> {{ $outcome_str }} </p>
                    <div id="input" class="top-buffer col-md-6 col-md-offset-3">
                            <button class="btn btn-block btn-default" id="btn-filter" onclick="filter(0)" > Mostra solo gli errori </button>
                    </div> <br> <br> <br>
                    @yield('results-table')
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

$(function(){
    $(window).scroll(function() {
        if ($(document).height()*0.66 <= ($(window).height() + $(window).scrollTop()))
        {
            if ($(".bootstrap-growl").length == 0)
                $.bootstrapGrowl("Hai dei suggerimenti? Scrivi a <a href=\"mailto:{{$_ENV['PUBLIC_MAIL_ADDRESS']}}\">{{$_ENV['PUBLIC_MAIL_ADDRESS']}}</a>!", {
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
</script>

@stop
