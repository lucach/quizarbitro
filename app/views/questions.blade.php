@extends('layout')
@section('content')
<script type="text/javascript">

var quizRef = quizRef || {};

quizRef = function(){

    function init()
    {
        quizRef.currentQuestion = 0;
        quizRef.answers = "222222222222222222222222222222";
        quizRef.lastIntervalID = null;
        $(function() { // wait until the page is ready
            $("#question_law").html(getDescriptionByLawID($("#question_law").html()));
            quizRef.showProgress();
            // Retrieve all questions text.
            $.ajax({
                url: '{{ url('/') }}' + '/quiz/all',
                success: function(result) {
                    quizRef.questions = $.parseJSON(result);
                    // Write extracted IDs to console log if the logged user is
                    // administrator. This can be useful for debugging purposes.
                    @if (Auth::user()->admin == true)
                        quizRef.questions.forEach(function(question) {
                            console.log("ID: #" + question["id"]);
                        });
                    @endif
                }
            });
        });
    }

    function goToId(id)
    {
        $("#backButton").prop('disabled', true);
        $("#nextButton").prop('disabled', true);

        $("#btn-true").blur();
        $("#btn-false").blur();

        $("#current_question_string").html("Domanda corrente: " + parseInt(id+1) + " / 30");
        $("#question_law").html(getDescriptionByLawID(quizRef.questions[id].law));
        $("#question_text").html(quizRef.questions[id].question);

        var currentAnswer = quizRef.answers[id];

        if (currentAnswer == 2) // no answer has been given
        { 
              $("#btn-true").removeClass("btn-success");
              $("#btn-false").removeClass("btn-danger");
        }
        else if (currentAnswer == 0)
        {
            $("#btn-true").removeClass("btn-success");
            $("#btn-false").addClass("btn-danger");
        }
        else if (currentAnswer == 1)
        {
            $("#btn-true").addClass("btn-success");
            $("#btn-false").removeClass("btn-danger");
        }
        else
            showNotification("warning", "Sembrano esserci problemi con il salvataggio delle risposte...");

        if (id != 0)
            $("#backButton").prop('disabled', false);
        $("#nextButton").prop('disabled', false);

        if (id == 29)
        {
            $("#nextButton").attr("onclick","quizRef.endQuiz()");
            $("#nextButton").html("Termina");
            $("#nextButton").addClass("btn-primary");
        }
        else
        {
            $("#nextButton").html(">");
            $("#nextButton").removeClass("btn-primary");
            $("#nextButton").attr("onclick", "quizRef.goNext()");
        }

        quizRef.currentQuestion = id;
        showProgress();
    }

    function endQuiz()
    {
        var index = quizRef.answers.indexOf("2");
        if (index != -1)
            alert("Impossibile terminare il quiz. Ci sono domande senza risposta.");
        else
        {
            var confirm_end = confirm("Sei sicuro di voler terminare?");
            if (confirm_end)
            {
                $('#loading').modal('show');
                $.ajax({
                    type: 'POST',
                    url: '{{ url('/') }}' + '/answers',
                    data: 'answers=' + quizRef.answers,
                    success: function()
                    {
                        location.href = "{{ url('/') }}" + "/" + "end";
                    },
                    error: function()
                    {
                        $('#loading-modal-text').html('Si è verificato un errore nel salvataggio delle risposte. <br> Verrai reindirizzato alla pagina iniziale entro 5 secondi.')
                        setTimeout(function(){location.href="{{ url('/') }}"}, 5000);
                    }
                });
            }
        }
    }

    function goNext()
    {
        var requested = quizRef.currentQuestion;
        requested++;
        goToId(requested);
    }

    function saveGoNext(val)
    {
        quizRef.answers = quizRef.answers.substr(0, quizRef.currentQuestion) + val + quizRef.answers.substr(quizRef.currentQuestion + 1);

        // Go to next question iff hasn't been answered yet.
        if (quizRef.answers[quizRef.currentQuestion+1] == 2)
            goNext();
        else
            goToId(quizRef.currentQuestion);
    }

    function goBack()
    {
        var requested = quizRef.currentQuestion;
        requested--;
        goToId(requested);
    }

    function blinkCurrentQuestion(current)
    {
        var id = "progress" + current;
        if ($("#" + id).hasClass('progress-bar-warning')) 
        {
            $("#" + id).removeClass('progress-bar-warning');
            $("#" + id).addClass('progress-bar-info');
        }
        else
        {
            $("#" + id).addClass('progress-bar-warning');
            $("#" + id).removeClass('progress-bar-info');
        }
    }

    function showProgress() {
        var tot = 30;
        var htmlcode = "<div class=\"progress\">";
        for (var i = 0; i < tot; )
            if (quizRef.answers.substring(i,i+1) == 2) //Blue, no answer 
                htmlcode += "<div id=\"progress" + i + "\" class=\"progress-single progress-bar progress-bar-info\" onclick=\"quizRef.goToId(" + i + ") \" style='width: " + 100/tot + "%;'>" + ++i + "</div>";
            else
                htmlcode += "<div id=\"progress" + i + "\" class=\"progress-single progress-bar progress-bar-warning\" onclick=\"quizRef.goToId(" + i + ") \" style=\"width: " + 100/tot + "%;\">" + ++i + "</div>";
        htmlcode += "</div>";
        $("#progress_bar").html(htmlcode);
        if (quizRef.lastIntervalID)
            clearInterval(quizRef.lastIntervalID);
        quizRef.lastIntervalID = setInterval(function(){blinkCurrentQuestion(quizRef.currentQuestion)}, 500);
    }

    return { // we expose only some pointers to the functions needed from the external
        showProgress:showProgress,
        next:saveGoNext,
        goToId:goToId,
        goNext:goNext,
        goBack:goBack,
        endQuiz:endQuiz,
        init:init
    }

}();

quizRef.init();

</script>

    <div class="container">

        <div class="row top-buffer">
            <div class="row">
                <div id="backButtonDiv" class="col-md-2">
                    <button class="btn btn-small" id="backButton" disabled="disabled" onClick="quizRef.goBack()">&lt;</button>
                </div>
                <div id="question_law" class="col-md-8 text-center">{{ $question_law }}</div>
                <div id="nextButtonDiv" class="col-md-2 text-right">
                    <button class="btn btn-small" id="nextButton" onClick="quizRef.goNext()">&gt;</button>
                </div>
            </div>
            <hr>
            <div class="span8 top-buffer">
                    <div id="question_text">{{ $question_text }}</div>
            </div>
        </div>
        <div class="row top-buffer">
            <div id="input" class="span6">
                    <button class="btn btn-large btn-block btn-default" style="float:left; margin-left:15%; width:30%; margin-right:10%" id="btn-true" onClick="quizRef.next(1)"> Vero </button>
                    <button class="btn btn-large btn-block btn-default" style="width:30%" id="btn-false" onClick="quizRef.next(0)"> Falso </button>
            </div>
        </div>
        <!-- TODO-UI: Maybe remove the following div? -->
        <div class="row top-buffer">
            <div id="current_question_string" class="span8 text-center">Domanda corrente: 1 / 30</div>
        </div>
        <div class="row top-buffer" id="progress_bar"> </div>
   </div>

    <div id="loading" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <p id="loading-modal-text" class="text-center">Salvataggio del quiz in corso...</p>
                </div>
            </div>
        </div>
    </div>

    
@stop