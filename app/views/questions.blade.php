@extends('layout')
@section('content')
<script type="text/javascript">

var quizArbitro = quizArbitro || {};

quizArbitro = function(){

	function init()
	{
		quizArbitro.currentQuestion = 0;
		quizArbitro.answers = "2222222222222222222222222";
		quizArbitro.lastIntervalID = null;
		// Retrieve all questions text
		$.ajax({ 
			url: '{{ url('/') }}' + '/quiz/all',
			success: function(result) {
				quizArbitro.questions = $.parseJSON(result);
			}
		});
	}

	function goToId(id)
	{
		$("#backButton").prop('disabled', true);
		$("#nextButton").prop('disabled', true);

		$("#current_question_string").html("Domanda corrente: " + parseInt(id+1) + " / 25");
		$("#question_text").html(quizArbitro.questions[id]);

		var currentAnswer = quizArbitro.answers[id];

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

		if (id == 24)
		{
			$("#nextButton").attr("onclick","quizArbitro.endQuiz()");
			$("#nextButton").html("Termina");
			$("#nextButton").addClass("btn-primary");
		}
		else
		{	$("#nextButton").html(">");
			$("#nextButton").removeClass("btn-primary");
			$("#nextButton").attr("onclick", "quizArbitro.next(1)");
		}

		quizArbitro.currentQuestion = id;
		showProgress();
	}

	function endQuiz()
	{
		var index = quizArbitro.answers.indexOf("2");
		if (index != -1)
			alert("Impossibile terminare il quiz. Ci sono domande senza risposta.");
		else
		{
			var confirm_end = confirm("Sei sicuro di voler terminare?");
			if (confirm_end)
				location.href = "{{ url('/') }}" + "/" + "end";
		}
	}

	function goNext()
	{
		var requested = quizArbitro.currentQuestion;
		requested++;
		goToId(requested);
	}

	function saveGoNext(val)
	{
		quizArbitro.answers = quizArbitro.answers.substr(0, quizArbitro.currentQuestion) + val + quizArbitro.answers.substr(quizArbitro.currentQuestion + 1);

		$.ajax({
	        type: "POST",
	        data: "answer=" + val,
	        url: '{{ url('/') }}' + '/quiz/' + quizArbitro.currentQuestion,
	        error: function()
	        {   
				showNotification("warning", "Sembrano esserci problemi con il salvataggio delle risposte...");
	        }
	    });
	    // TODO: This may lead to sync problem(s) if the AJAX request is completed with a certain delay. Needed further investigation
	    if (quizArbitro.answers[quizArbitro.currentQuestion+1] == 2) // go to next question iff hasn't been answered yet.
			goNext();
		else
			goToId(quizArbitro.currentQuestion);

	}

	function goBack()
	{
		var requested = quizArbitro.currentQuestion;
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
		var tot = 25;
		var htmlcode = "<div class=\"progress\">";
		for (var i = 0; i < tot; )
			if (quizArbitro.answers.substring(i,i+1) == 2) //Blue, no answer 
				htmlcode += "<div id=\"progress" + i + "\" class=\"progress-single progress-bar progress-bar-info\" onclick=\"quizArbitro.goToId(" + i + ") \" style='width: " + 100/tot + "%;'>" + ++i + "</div>";
			else
				htmlcode += "<div id=\"progress" + i + "\" class=\"progress-single progress-bar progress-bar-warning\" onclick=\"quizArbitro.goToId(" + i + ") \" style=\"width: " + 100/tot + "%;\">" + ++i + "</div>";
		htmlcode += "</div>";
		$("#progress_bar").html(htmlcode);
		if (quizArbitro.lastIntervalID)
			clearInterval(quizArbitro.lastIntervalID);
		quizArbitro.lastIntervalID = setInterval(function(){blinkCurrentQuestion(quizArbitro.currentQuestion)}, 500);
	}

	return { // we expose only some pointers to the functions needed from the external
		showProgress:showProgress,
		next:saveGoNext,
		goToId:goToId,
		goNext:goNext,
		goBack:goBack,
		init:init
	}

}();

quizArbitro.init();

</script>

    <div class="container">

		<div class="row top-buffer">
			<div id="backButtonDiv" class="span2" style="float:left; margin-right: 3%">
				<button class="btn btn-small" id="backButton" disabled="disabled" onClick="quizArbitro.goBack()">&lt;</button>
			</div>
			<div id="nextButtonDiv" class="span2 text-right"> 
				<button class="btn btn-small" id="nextButton" onClick="quizArbitro.goNext()">></button> 
			</div>
			<div class="span8 top-buffer">
					<div id="question_text">{{ $question_text }}</div>
			</div>
		</div>
		<div class="row top-buffer">
			<div id="input" class="span6">
					<button class="btn btn-large btn-block btn-default" style="float:left; margin-left:15%; width:30%; margin-right:10%" id="btn-true" onClick="quizArbitro.next(1)"> Vero </button>
					<button class="btn btn-large btn-block btn-default" style="width:30%" id="btn-false" onClick="quizArbitro.next(0)"> Falso </button>
			</div>
		</div>
		<!-- TODO-UI: Maybe remove the following div? -->
		<div class="row top-buffer">
			<div id="current_question_string" class="span8 text-center">Domanda corrente: 1 / 25</div>
		</div>
		<div class="row top-buffer" id="progress_bar"> </div>
   </div>
	
<script type="text/javascript">
quizArbitro.showProgress();
</script>
@stop