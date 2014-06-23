@extends('layout')
@section('content')
<script type="text/javascript">
localStorage.setItem("currentQuestion", document.URL.substr(document.URL.lastIndexOf('/') + 1));

function goToId(id)
{
	$("#backButton").prop('disabled', true);
	$("#nextButton").prop('disabled', true);

	$("#current_question_string").html("Domanda corrente: " + parseInt(id+1) + " / 25");
	$.ajax({
		url: '{{ url('/') }}' + '/quiz/' + id + "/text",
		success:function(result){
		  array = JSON.parse(result);
	      $("#question_text").html(array[0]);
	      if (array[1] == -1) // array[1] is -1 if no answer has been given, 0 or 1 otherwise (according to the answer)
	      {
	      	$("#btn-true").removeClass("btn-success");
	      	$("#btn-false").removeClass("btn-danger");
	      }
	      else if (array[1] == 0)
	      {
	      	$("#btn-true").removeClass("btn-success");
	      	$("#btn-false").addClass("btn-danger");
	      }
	      else if (array[1] == 1)
	      {
	      	$("#btn-true").addClass("btn-success");
	       	$("#btn-false").removeClass("btn-danger");
			}
	      else
	      	showNotification("warning", "Problemi durante il recupero delle informazioni sul quesito.");

	      if (id != 0)
	      	$("#backButton").prop('disabled', false);
		  $("#nextButton").prop('disabled', false);

		  if (id == 24)
			{
				$("#nextButton").attr("onclick","endQuiz()");
				$("#nextButton").html("Termina");
				$("#nextButton").addClass("btn-primary");
			}
			else
			{	$("#nextButton").html(">");
				$("#nextButton").removeClass("btn-primary");
				$("#nextButton").attr("onclick", "saveGoNext(1)");
			}


	      localStorage.setItem("currentQuestion", id);
	    }
	});
	showProgress();

}

function endQuiz()
{
	var answers = localStorage.getItem("answers");
	var index = answers.indexOf("2");
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
	var requested = localStorage.getItem("currentQuestion");
	requested++;
	goToId(requested);
}

function saveGoNext(val)
{
	var current = parseInt(localStorage.getItem("currentQuestion"));
	var answers = localStorage.getItem("answers");
	localStorage.setItem("answers", answers.substr(0, current) + val + answers.substr(current+1));
	$.ajax({
        type: "POST",
        data: "answer=" + val,
        url: '{{ url('/') }}' + '/quiz/' + current,
        error: function()
        {   
            showNotification('danger', 'Si è verificato un problema durante il salvataggio delle risposte.')
        }
    });
    // TODO: This may lead to sync problem(s) if the AJAX request is completed with a certain delay. Needed further investigation
    if (answers[current+1] == 2) // go to next question iff hasn't been answered yet.
		goNext();
	else
		goToId(current);

}

function goBack()
{
	var requested = localStorage.getItem("currentQuestion");
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

var lastIntervalID = null;

function showProgress() {
	var tot = 25;
	var answers = localStorage.getItem("answers");
	var htmlcode = "<div class=\"progress\">";
	for (var i = 0; i < tot; )
		if (answers.substring(i,i+1) == 2) //Blue, no answer 
			htmlcode += "<div id=\"progress" + i + "\" class=\"progress-single progress-bar progress-bar-info\" onclick=\"goToId(" + i + ") \" style='width: " + 100/tot + "%;'>" + ++i + "</div>";
		else
			htmlcode += "<div id=\"progress" + i + "\" class=\"progress-single progress-bar progress-bar-warning\" onclick=\"goToId(" + i + ") \" style=\"width: " + 100/tot + "%;\">" + ++i + "</div>";
	htmlcode += "</div>";
	$("#progress_bar").html(htmlcode);
	if (lastIntervalID)
		clearInterval(lastIntervalID);
	lastIntervalID = setInterval(function(){blinkCurrentQuestion(parseInt(localStorage.getItem("currentQuestion")))}, 500);
}


</script>

    <div class="container">

		<div class="row top-buffer">
			<div id="backButtonDiv" class="span2" style="float:left; margin-right: 3%">
				<button class="btn btn-small" id="backButton" disabled="disabled" onClick="goBack()">&lt;</button>
			</div>
			<div id="nextButtonDiv" class="span2 text-right"> 
				<button class="btn btn-small" id="nextButton" onClick="goNext()">></button> 
			</div>
			<div class="span8 top-buffer">
					<div id="question_text">{{ $question_text }}</div>
			</div>
		</div>
		<div class="row top-buffer">
			<div id="input" class="span6">
					<button class="btn btn-large btn-block btn-default" style="float:left; margin-left:15%; width:30%; margin-right:10%" id="btn-true" onClick="saveGoNext(1)"> Vero </button>
					<button class="btn btn-large btn-block btn-default" style="width:30%" id="btn-false" onClick="saveGoNext(0)"> Falso </button>
			</div>
		</div>
		<!-- TODO-UI: Maybe remove the following div? -->
		<div class="row top-buffer">
			<div id="current_question_string" class="span8 text-center">Domanda corrente: 1 / 25</div>
		</div>
		<div class="row top-buffer" id="progress_bar"> </div>
   </div>
	
<script type="text/javascript">
showProgress();
</script>
@stop