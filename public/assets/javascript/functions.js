var BASE_URL = window.location.origin;

function showNotification(chosen_type,msg)
{
	$.bootstrapGrowl(msg, {
		ele: 'body',
		type: chosen_type,
		offset: {from: 'bottom', amount: 80},
		align: 'right',
		width: 'auto',
		allow_dismiss: true,
		delay: 30000,
		stackup_spacing: 10
	});
}

function showSaveResult(good)
{
	var msg = "Il risultato del tuo quiz è stato salvato!", msg_type = "success";
	if (!good)
	{
		msg = "Errore durante il salavataggio del tuo quiz.";
		msg_type = "danger";
	}
	$.bootstrapGrowl(msg, {
      ele: 'body', // which element to append to
      type: msg_type, // (null, 'info', 'error', 'success')
      offset: {from: 'bottom', amount: 80}, // 'top', or 'bottom'
      align: 'right', // ('left', 'right', or 'center')
      width: 300, // (integer, or 'auto')
      allow_dismiss: true,
      delay: 5000,
      stackup_spacing: 10 // spacing between consecutively stacked growls.
    });
    localStorage.setItem("saved",1);
}

function filter(all)
{
	$("#btn-filter").blur();
	if (all == 0)
	{
		$("tr.success").hide();
		$("#btn-filter").html("Mostra tutte le domande");
		$("#btn-filter").attr("onclick", "filter(1)");
	}
	else
	{
		$("tr.success").show();
		$("#btn-filter").html("Mostra solo gli errori");
		$("#btn-filter").attr("onclick", "filter(0)");
	}
}

function getDescriptionByLawID(law)
{
	var descriptions = [
		"",
		"Regola 1 - Il terreno di gioco",
		"Regola 2 - Il pallone",
		"Regola 3 - Il numero dei calciatori",
		"Regola 4 - L'equipaggiamento dei calciatori",
		"Regola 5 - L'arbitro",
		"Regola 6 - Gli assistenti dell'arbitro",
		"Regola 7 - La durata della gara",
		"Regola 8 - L'inizio e la ripresa del gioco",
		"Regola 9 - Il pallone in gioco e non in gioco",
		"Regola 10 - La segnatura di una rete",
		"Regola 11 - Il fuorigioco",
		"Regola 12 - Falli e scorrettezze",
		"Regola 13 - Calci di punizione",
		"Regola 14 - Il calcio di rigore",
		"Regola 15 - La rimessa dalla linea laterale",
		"Regola 16 - Il calcio di rinvio",
		"Regola 17 - Il calcio d'angolo",
		"Procedure per determinare la squadra vincente di una gara"
	];
	if (law < 1 || law > 18)
		return null;
	else
		return descriptions[law];
}

var arrMonthNames = ["gen", "feb", "mar", "apr", "mag", "giu", "lug", "ago", "set", "ott", "nov", "dic"]


function showTooltip(x, y, contents) {
    $('<div id="tooltip">' + contents + '</div>').css( {
        position: 'absolute',
        display: 'none',
        top: y + 5,
        left: x + 10,
        border: '1px solid #fdd',
        padding: '2px',
        'background-color': '#fee',
        opacity: 0.80
    }).appendTo("body").fadeIn(200);
}


function showHistoryChart()
{
	$.ajax({
        type: "GET",
        url: BASE_URL + "/history/json",
        async: true,
        success: function (data)
        {
            var val = JSON.parse(data);
            if (val.length == 0)
                $("#chartPlaceholder").html("<p class='text-center'><i>Nessun quiz svolto finora. Grafico non mostrato</i></p>");
            else
            {
                $.plot("#chartPlaceholder", [val], {
	                xaxis: { mode: "time", timezone: "browser",
	                         timeformat: "%e %b %y",
	                         monthNames: arrMonthNames },
	                lines: { show: true },
	                points: { show: true },
	                grid: { hoverable: true, clickable: true }
	            });
	            var previousPoint = null;
	            $("#chartPlaceholder").bind("plothover", function (event, pos, item) {
	                    if (item) {
	                        if (previousPoint != item.dataIndex) {
	                            previousPoint = item.dataIndex;
	                            $("#tooltip").remove();
	                            var x = item.datapoint[0],
	                                y = item.datapoint[1];
	                            var date = new Date(x);
	                            var d = date.getDate(), m = date.getMonth()+1, yy = date.getYear()+1900;
	                            showTooltip(item.pageX, item.pageY, d + "/" + m + "/" + yy + ": " + y);
	                        }
	                    }
	                    else {
	                        $("#tooltip").remove();
	                        previousPoint = null;
	                    }
	            });
            }
        }
    });
}
