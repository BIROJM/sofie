getRealTimeStatus();

$("#courbe_evol").hide();
$("#graph_glob").hide();

setInterval(getRealTimeStatus,10000);

function getRealTimeStatus()
{
		var DATA = "viewRealTime";
		$.ajax({
			type:"GET",
			url : "stat_globale_req.php",
			data : DATA,
			dataType : 'json',
			success : function(donnee){
				if(donnee.nbrePanne == null) $("#glob_panne").html('0');
				else $("#glob_panne").html(parseInt(donnee.nbrePanne));
				
				if(donnee.nbreMarche == null) $("#glob_marche").html('0');
			    else $("#glob_marche").html(parseInt(donnee.nbreMarche));
				
				if(donnee.nbreReparationEnCours == null) $("#glob_rep_encours").html('0');
				else $("#glob_rep_encours").html(parseInt(donnee.nbreReparationEnCours));
				
				if(donnee.nbrePrisEncharge == null) $("#glob_prise_en_charge").html('0');
				else $("#glob_prise_en_charge").html(parseInt(donnee.nbrePrisEncharge));
								
			}			
		});		
}
