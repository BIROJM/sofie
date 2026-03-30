	var regions = '';
	var localites = '';
	var agentForma = '';
	var reparateur = '';
	var codeOuvrage = '';
	var page;

	
	function showAlert(status, message)
	{	
		if(status == 0) 
		{ 				
			$('#alert_bar').removeClass('alert-danger');
			$('#alert_bar').addClass('alert-success'); 
		}
		else if(status == 1)
		{ 
			$('#alert_bar').removeClass('alert-success');
			$('#alert_bar').addClass('alert-danger'); 
		}
		$('#alert_bar').html(message);				
		$('#alert_bar').show();
	}
	
	
	function loadTab(page)
	{
	//var link = 'tab.php?page=' + page;
	var link = 'ouvrage/search?page=' + page + '&regions=' + regions + '&localites=' + localites + '&codeOuvrage=' + codeOuvrage + '&agentForma=' + agentForma + '&reparateur=' + reparateur;
		//alert(page);
		$.ajax({
			type:"GET",
			url : link,
			dataType : 'html',
			//data : data, 
			success : function(response){
				$("#tabl").html(response);
				/*getCount();*/
				$('#pageNumber').val(page);
				if(page > 1)
				{
					//alert('f');
					$('#prec').attr('href',parseInt(page-1));
					$('li .prec').removeClass('disabled');
					if(page == $('#totalPage').val())
					$('#suiv').hide();
				}
				else if($('#totalPage').val() == 1)
				{
					$('#prec').hide();
					$('#suiv').hide();
				}
				else 
				{
					$('#prec').hide();
					//$('#suiv').hide();
				}
				$('#suiv').attr('href',parseInt(page)+1);
				/**/
			}
						
		});
	}
			 
		 
	$.ajaxSetup({
    beforeSend:function(){
        // show image here
        $("#anim").show();
    },
    complete:function(){
        // hide image here
        $("#anim").hide();
    }
	});
	
	
	$('.fermer').on('click', function(e) {
		$('#modal-sofie').on('hidden', function() { $(this).removeData('modal'); })
		$('.modal').modal('hide');
	});
		
	$('.close').on('click', function(e) {
		$('#modal-sofie').on('hidden', function() { $(this).removeData('modal'); })
		$('.modal').modal('hide');
	});
	
			
	$(".ouvrirModal").click(function() {
	$('#modal-sofie').removeData('modal'); 
	//$('#modal-sofie .modal-body').load($(this).attr("href"),function(e){$('#modal-sofie').modal('show');});
	//$(this).html('');
		 //$('.modal').attr({width: '500px', height:  '200px'});
	//$('#modal-sofie').modal({remote: $(this).attr("href")})
	$('#myModalLabel').html($(this).attr("data-title"));
		 
	});
		/*
		$('#modal-extrait').modal({
			keyboard: false,
			backdrop: false,
			show: false
			});
		*/
	/*	
	$(".modal").draggable({
	handle: ".modal-header"
	}); 
	*/	
$("#divRegion").hide();
	$("#role").on('change', function(e) {
		if($(this).val() == 3) 
		{
			$("#divRegion").show();	
		}
		else $("#divRegion").hide();
	});
	
$("#btnSave").click(function() {
	
	//var action = $("input[name=operation]").val();
	
	var link = $("form").attr('action');
	
	var method = $("form").attr('method');
	//alert(method);
	var info = $('.error_alert');	
		
		var data = $("form").serialize();
		$.ajax({
			type: 'POST',
			url : link,
			//dataType : 'xml',
			data : data, 
			success : function(response){
			showAlert(response.status, response.message);
			info.hide().find('ul').empty();
			if(response.status == 0)
			{	
				setTimeout(function() {$('#modal-sofie').modal('hide');}, 3000);
				location.reload();
			} 
			else
			{	
				$.each(response.errors, function(index, error){
				 info.find('ul').append('<li>' + error + '</li>');
            });
				 info.slideDown();
			}
		}
						
		});
			//e.preventDefault();	
});


/*

	//$("#localites").attr('disabled','disabled');
	$("#localites").html('');
	
	$("#regions").on('change', function(e) {
	
		var DATA = 'id=' + $(this).val();
		$.ajax({
			type:"GET",
			url : "ouvrage/selectLocalites/"+$(this).val(),
			dataType : 'json',
			//data : DATA, 
			success : function(donnee){
			var options = '';
			options+='<option value="0" selected> </option>';
				$.each(donnee, function (key,val) {
           options +='<option value="'+val.IDLocalite+'"> '+val.NomLocalite+'</option>';
		   $("#localites").removeAttr('disabled');
		   console.log('val =' + val.IDLocalite + ' - '+val.NomLocalite);
        });                   
               $("#localites").html(options);
            }
			
		});

	});
	
	
	$("#comites").html('');
	
	$("#localites").on('change', function(e) {
	
		$.ajax({
			type:"GET",
			url : "../ouvrage/selectComites/"+$(this).val(),
			dataType : 'json',
			//data : DATA, 
			success : function(donnee){
			var options = '';
			options+='<option value="0" selected> </option>';
				$.each(donnee, function (key,val) {
           options +='<option value="'+val.IDComite+'"> '+val.NomComite+ '</option>';
		   //console.log('rep =' + val.IDReparateur + ' - '+val.NomRep);
        });                   
               $("#comites").html(options);
            }
			
		});

	});
	*/

$('#searchSubmit').on('click', function(e) {
		 codeOuvrage = $('#codeOuvrage').val();
		 regions = $('#regions').val();
		 localites = $('#localites').val();
		 agentForma = $('#agentForma').val();
		 reparateur = $('#reparateur').val();
		//if(numPv == '' && sd == '' && tc == '') alert('Au moin le numéro de PV doit être renseigné');
		//else 
			loadTab(1);		
			//searchPv(numPv, sd, tc);
		
		$('#pagin').val(page);
		//alert($(this).attr('href'));
		e.preventDefault();
	});

loadTab(1);