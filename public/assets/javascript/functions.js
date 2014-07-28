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
