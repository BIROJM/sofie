$(document).ready(function() {

	var date_debut = "";
	var date_fin = "";
	var region = "";
	var localite = "";
	var reparateur = "";
	//if($(".actualPage").val()) alert ('yes');
	
	//Desactivation des localites
	$("#localites").attr('disabled','disabled');
	$("#localites").html('');
	
	//Premier chargement des stats globales
	getStatGlobale();
	
	//Manipulation du datepicker
	$(".date").datepicker({ 
	
		 changeMonth: true,
         changeYear: true,
         showButtonPanel: false,
		 dateFormat: "dd/mm/yy",
		 firstDay: 1 
	});
	
	//Recuperation de la liste des regions
	
	var DATA = "getRegion=y";
		$.ajax({
			type:"GET",
			url : "stat_globale_req.php",
			dataType : 'json',
			data : DATA,
			async: true,
			success : function(donnee){
			var options = '';
			options+='<option value="" selected> NATIONALE </option>';
				$.each(donnee, function (key,val) {
				   options +='<option value="'+val.IDRegion+'"> '+val.NomRegion+'</option>';
				  });                   
               $("#regions").html(options);
            }
			
		});
	
	
	//Clique sur le bouton d'affichage des stats
	
	$('#showStat').on('click', function(e) {	
		date_debut = splitDate($("#date_debut").val());
		date_fin = splitDate($("#date_fin").val());
		region = $("#regions").val();
		if($("#localites").val() == null) localite ="";
		else localite = $("#localites").val();
		//localite;
		 getStatGlobale();
		//alert(date_debut + "-" + date_fin + "-" + region);	
	});
	
	$('#showStat_reparateur').on('click', function(e) {	
		date_debut = splitDate($("#date_debut").val());
		date_fin = splitDate($("#date_fin").val());
		region = $("#regions").val();
		if($("#localites").val() == null) localite ="";
		else localite = $("#localites").val();
		//var libelle = 
		//localite;
		getReparateurs();		
	});
	
	$('#showStat_agent').on('click', function(e) {	
		date_debut = splitDate($("#date_debut").val());
		date_fin = splitDate($("#date_fin").val());
		region = $("#regions").val();
		if($("#localites").val() == null) localite ="";
		else localite = $("#localites").val();
		getAgents();
	});
	
	$('#showStat_sociologue').on('click', function(e) {	
		date_debut = splitDate($("#date_debut").val());
		date_fin = splitDate($("#date_fin").val());
		region = $("#regions").val();
		getSociologues();
	});
	
	$('#showStat_dr').on('click', function(e) {	
		date_debut = splitDate($("#date_debut").val());
		date_fin = splitDate($("#date_fin").val());
		region = $("#regions").val();
		getDr();
	});
	
	$('#showStat_forage').on('click', function(e) {	
		date_debut = splitDate($("#date_debut").val());
		date_fin = splitDate($("#date_fin").val());
		region = $("#regions").val();
		if($("#localites").val() == null) localite ="";
		else localite = $("#localites").val();
		//var libelle = 
		//localite;
		getForages();
		//alert(date_debut + "-" + date_fin + "-" + region);	
	});
	
	$('#showStat_comite').on('click', function(e) {	
		date_debut = splitDate($("#date_debut").val());
		date_fin = splitDate($("#date_fin").val());
		region = $("#regions").val();
		if($("#comites").val() == null) localite ="";
		else localite = $("#comites").val();
		getComites();
	});
	
	
	//Manipulation de la zone de region 
		
	$("#regions").on('change', function(e) {
		
		var libRegion = $(".libStatGen").html(" Région  : " + $("#regions option:selected").text());
		
		var DATA = 'regions=' + $(this).val() + "&viewLocalite=y&libelle=" + libRegion;
		$.ajax({
			type:"GET",
			url : "stat_globale_req.php",
			dataType : 'json',
			data : DATA,
			async: true,
			success : function(donnee){
			var options = '';
			options+='<option value="" selected> </option>';
				$.each(donnee, function (key,val) {
				   options +='<option value="'+val.IDLocalite+'"> '+val.NomLocalite+'</option>';
				   $("#localites").removeAttr('disabled');
				   console.log('val =' + val.IDLocalite + ' - '+val.NomLocalite);
				});                   
               $("#localites").html(options);
			   $("#localites").trigger("change",[true]);
            }
			
		});
		
		if($(".actualPage").val() == "reparateur")
		{
			var DATA = 'regions=' + $(this).val() + "&getReparateurs=y";
			$.ajax({
			type:"GET",
			url : "stat_reparateur_req.php",
			dataType : 'json',
			data : DATA,
			async: true,
			success : function(donnee){
			var options = '';
			options+='<option value="" selected> </option>';
				$.each(donnee, function (key,val) {
				   options +='<option value="'+val.IDReparateur+'"> '+val.NomRep+' ' + val.PrenomsRep + '</option>';
				   $("#reparateurs").removeAttr('disabled');
				  });                   
               $("#reparateurs").html(options);
			   $("#reparateurs").trigger("change");
            }
			
			});
		
		}
		
		if($(".actualPage").val() == "forage")
		{
			var DATA = 'regions=' + $(this).val() + "&getForages=y";
			$.ajax({
			type:"GET",
			url : "stat_forage_req.php",
			dataType : 'json',
			data : DATA,
			async: true,
			success : function(donnee){
			var options = '';
			options+='<option value="" selected> </option>';
				$.each(donnee, function (key,val) {
				   options +='<option value="'+val.IDOuvrage+'"> '+val.CodeOuvrage+'</option>';
				   $("#forages").removeAttr('disabled');
				  });                   
               $("#forages").html(options);
			   $("#forages").trigger("change");
            }
			
			});
		
		}
		
		
		if($(".actualPage").val() == "agent")
		{
			var DATA = 'regions=' + $(this).val() + "&getAgents=y";
			$.ajax({
			type:"GET",
			url : "stat_agent_req.php",
			dataType : 'json',
			data : DATA,
			async: true,
			success : function(donnee){
			var options = '';
			options+='<option value="" selected> </option>';
				$.each(donnee, function (key,val) {
				   options +='<option value="'+val.IDAgent+'"> '+val.NomAgent+' ' + val.PrenomsAgent + '</option>';
				   $("#agents").removeAttr('disabled');
				  });                   
               $("#agents").html(options);
			   $("#agents").trigger("change");
            }
			
			});		
		}
		
		
		if($(".actualPage").val() == "sociologue")
		{
			var DATA = 'regions=' + $(this).val() + "&getSociologues=y";
			$.ajax({
			type:"GET",
			url : "stat_agent_req.php",
			dataType : 'json',
			data : DATA,
			async: true,
			success : function(donnee){
			var options = '';
			options+='<option value="" selected> </option>';
				$.each(donnee, function (key,val) {
				   options +='<option value="'+val.IDAgent+'"> '+val.NomAgent+' ' + val.PrenomsAgent+ '</option>';
				   $("#sociologues").removeAttr('disabled');
				  });                   
               $("#sociologues").html(options);
			   $("#sociologues").trigger("change");
            }
			
		});
		
		}
		
		
		if($(".actualPage").val() == "dr")
		{
			var DATA = 'regions=' + $(this).val() + "&getDr=y";
			$.ajax({
			type:"GET",
			url : "stat_agent_req.php",
			dataType : 'json',
			data : DATA,
			async: true,
			success : function(donnee){
			var options = '';
			options+='<option value="" selected> </option>';
				$.each(donnee, function (key,val) {
				   options +='<option value="'+val.IDAgent+'"> '+val.NomAgent+' ' + val.PrenomsAgent+ '</option>';
				   $("#dr").removeAttr('disabled');
				  });                   
               $("#dr").html(options);
			   $("#dr").trigger("change");
            }
			
		});
		
		}
		
		
		if($(".actualPage").val() == "comite")
		{
			var DATA = 'regions=' + $("#regions").val() + "&getComites=y";
			$.ajax({
				type:"GET",
				url : "stat_comite_req.php",
				dataType : 'json',
				data : DATA,
				async: true,
				success : function(donnee){
				var options = '';
				options+='<option value="" selected> </option>';
					$.each(donnee, function (key,val) {
					   options +='<option value="'+val.IDComite+'"> '+val.NomSecretaire+' ' + val.PrenomsSecretaire + '</option>';
					   $("#comites").removeAttr('disabled');
					  });                   
				   $("#comites").html(options);
				   $("#comites").trigger("change");
				}
			
			});
		
		}

	});
	
		
	//Manipulation de la zone de la localite
		
	$("#localites").on('change', function(e, machine) {
		
		if(machine) return;
		
		//$(".libStatGen").html(" Localité  : " + $("#localites option:selected").text());
		
		if($(".actualPage").val() == "reparateur")
		{
			var DATA = 'regions=' + $("#regions").val() + '&localites=' + $(this).val() + "&getReparateurs=y";
			$.ajax({
			type:"GET",
			url : "stat_reparateur_req.php",
			dataType : 'json',
			data : DATA,
			async: true,
			success : function(donnee){
			var options = '';
			options+='<option value="" selected> </option>';
				$.each(donnee, function (key,val) {
				   options +='<option value="'+val.IDReparateur+'"> '+val.NomRep+' ' + val.PrenomsRep + '</option>';
				   $("#reparateurs").removeAttr('disabled');
				  });                   
               $("#reparateurs").html(options);
			   $("#reparateurs").trigger("change");
            }
			
		});
		
		}
		
		if($(".actualPage").val() == "forage")
		{
			var DATA = 'regions=' + $(this).val() + '&localites=' + $(this).val() + "&getForages=y";
			$.ajax({
			type:"GET",
			url : "stat_forage_req.php",
			dataType : 'json',
			data : DATA,
			async: true,
			success : function(donnee){
			var options = '';
			options+='<option value="" selected> </option>';
				$.each(donnee, function (key,val) {
				   options +='<option value="'+val.IDOuvrage+'"> '+val.CodeOuvrage+'</option>';
				   $("#forages").removeAttr('disabled');
				  });                   
               $("#forages").html(options);
			   $("#forages").trigger("change");
            }
			
			});
		
		}
		
		
		if($(".actualPage").val() == "agent")
		{
			var DATA = 'regions=' + $("#regions").val() + '&localites=' + $(this).val() + "&getAgents=y";
			$.ajax({
			type:"GET",
			url : "stat_agent_req.php",
			dataType : 'json',
			data : DATA,
			async: true,
			success : function(donnee){
			var options = '';
			options+='<option value="" selected> </option>';
				$.each(donnee, function (key,val) {
				   options +='<option value="'+val.IDAgent+'"> '+val.NomAgent+' ' + val.PrenomsAgent+ '</option>';
				   $("#agents").removeAttr('disabled');
				  });                   
               $("#agents").html(options);
			   $("#agents").trigger("change");
            }
			
		});
		
		}
		
		
		if($(".actualPage").val() == "comite")
		{
			var DATA = 'regions=' + $("#regions").val() + '&localites=' + $(this).val() + "&getComites=y";
			$.ajax({
				type:"GET",
				url : "stat_comite_req.php",
				dataType : 'json',
				data : DATA,
				async: true,
				success : function(donnee){
				var options = '';
				options+='<option value="" selected> </option>';
					$.each(donnee, function (key,val) {
					    options +='<option value="'+val.IDComite+'"> '+val.NomSecretaire+' ' + val.PrenomsSecretaire + '</option>';
					   $("#comites").removeAttr('disabled');
					  });                   
				   $("#comites").html(options);
				   $("#comites").trigger("change");
				}
			
			});
		
		}
		
		
	});
	
	//Affichage des boutons circulaire statistiques globales
	
		
	function getStatGlobale()
	{		
		var DATA = "viewGlobalStat&regions=" + region + "&localite=" + localite + "&date_debut=" + date_debut + "&date_fin=" + date_fin;
		$.ajax({
			type:"GET",
			url : "stat_globale_req.php",
			data : DATA,
			dataType : 'json',
			success : function(donnee){
			
			$("#glob_panne_decl").html(donnee.nbrePanne);
			$("#glob_panne_decl_moy").html(donnee.nbreMoyPanne);
			$("#glob_rep_eff").html(donnee.nbreReparation);
			$("#glob_rep_encours").html(donnee.nbreReparationEnCours);
			$("#glob_prise_cmd").html(donnee.nbrePriseCommande);
			$("#glob_cmd_horsdelai").html(donnee.nbreCmdHorsDelai);
			$("#glob_rep_eff_moy").html(donnee.nbreMoyReparation);			
			$("#glob_rep_eff_moy_delai").html(donnee.delaiMoyReparation);
			
			//response = donnee;					
            }			
		});
		
		//return response;
		getPalmares();
	}
	
	
	function getPalmares()
	{
		var DATA = "getPalmares=y&regions=" + region + "&localite=" + localite + "&date_debut=" + date_debut + "&date_fin=" + date_fin;
		$("#listPalmares").load("listPalmares.php?" + DATA);
	}
	
	function getReparateurs()
	{
		var DATA = "getReparateurs=y&regions=" + region + "&localite=" + localite + "&date_debut=" + date_debut + "&date_fin=" + date_fin;
		$("#listReparateur").load("listReparateur.php?" + DATA);
	}
	
	function getComites()
	{
		var DATA = "getComites=y&regions=" + region + "&localite=" + localite + "&date_debut=" + date_debut + "&date_fin=" + date_fin;
		$("#listComite").load("listComite.php?" + DATA);
	}
	
	function getAgents()
	{
		var DATA = "getAgents=y&regions=" + region + "&localite=" + localite + "&date_debut=" + date_debut + "&date_fin=" + date_fin;
		$("#listAgent").load("listAgent.php?" + DATA);
	}
	
	function getSociologues()
	{
		var DATA = "getSociologues=y&regions=" + region + "&date_debut=" + date_debut + "&date_fin=" + date_fin;
		$("#listSociologue").load("listSociologue.php?" + DATA);
	}
	
	function getDr()
	{
		var DATA = "getDr=y&regions=" + region + "&date_debut=" + date_debut + "&date_fin=" + date_fin;
		$("#listDr").load("listDr.php?" + DATA);
	}
	
	function getForages()
	{
		var DATA = "getForages=y&regions=" + region + "&localite=" + localite + "&date_debut=" + date_debut + "&date_fin=" + date_fin;
		$("#listForage").load("listForage.php?" + DATA);
	}
	
	function splitDate(dateValue)
	{
		var finalDate;
		if(dateValue == "") finalDate = "";
		else
		{
			var dateArray = dateValue.split("/");		
			finalDate = dateArray[2] + "-" + dateArray[1] + "-" + dateArray[0];
		}
		return finalDate;	
	}
	
	function UnSplitDate(dateValue)
	{
		var finalDate;
		if(dateValue == "") finalDate = "";
		else
		{
			var dateArray = dateValue.split("-");		
			finalDate = dateArray[0] + "-" + dateArray[1] + "-" + dateArray[2];
		}
		return finalDate;	
	}
	
	function loadSearchBar()
	{
		var page = $(".pageToLoad").val();
		$(".searchBar").load("form/simpleSearchMenu.html");
	}
	
	//loadSearchBar();
	
	
});