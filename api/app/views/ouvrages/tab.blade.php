<?php

//$nbLigne = 10;
$nbPage = ceil($totalOuvrage / (int)Config::get('settings.nbOfLine')); 
if($nbPage == 0) $nbPage = 1;
?>

<table class="table table-bordered table-condensed">
					<thead class="tab" >
						<tr>
							<th width="30" style="text-align:center;">Code</th>
							<th width="50"  style="text-align:center;">Statut</th>
							<th width="50"  style="text-align:center;">Type</th>
							<th width="50"  style="text-align:center;">Region</th>
							<th width="50"  style="text-align:center;">Localité</th>							
							<th width="50"  style="text-align:center;">Agent Forma</th> 
							<th width="50"  style="text-align:center;">Reparateur</th> 
							<th width="50"  style="text-align:center;">Comité Eau</th> 
							<th width="10"  style="text-align:center; "></th>
						</tr>
					</thead>
					<tbody>
					<?php $i =0; ?>
					@foreach ($ouvrages as $ouvrage)
						<tr class="ligne ligneTab <?php if($i%2==0)  { echo 'interligne'; }?>">
							<td>{{ $ouvrage->CodeOuvrage }}</td>
							<td>
							@if($ouvrage->StatutOuvrage == 1) En marche @endif
							@if($ouvrage->StatutOuvrage == 2) En panne @endif
							@if($ouvrage->StatutOuvrage == 3) Prise en charge @endif
							@if($ouvrage->StatutOuvrage == 4) En réparation @endif						
													
							{{-- $ouvrage->StatutOuvrage --}}
							</td>
							<td>{{{ $ouvrage->TypeOuvrage }}}</td>
							<td>{{{ $ouvrage->region->NomRegion or 'Aucun'}}}</td>
							<td>{{{ $ouvrage->localite->NomLocalite or 'Aucun'}}}</td>
							<td>{{{ $ouvrage->agentForma->agentFullName or 'Aucun'}}} <span class="pull-right">[N° : {{{$ouvrage->agentForma->numeroAppel->NumAppel or '-'}}}]</span></td> 
							<td>{{{ $ouvrage->reparateur->reparateurFullName or 'Aucun'}}} <span class="pull-right">[N° : {{{$ouvrage->reparateur->numeroAppel->NumAppel or '-'}}}]</span></td> 
							<td>{{{ $ouvrage->comite->NomComite or 'Aucun'}}} <span class="pull-right">[N° : {{{$ouvrage->comite->numeroAppel->NumAppel or '-'}}}]</span></td> 
							<td>
							<a href="#">
									
									<a title="Modifier les informations de l'ouvrage" data-toggle="modal" data-target="#modal-sofie-medium" class="ouvrirModalMedium" data-title="Modifier les informations de l'ouvrage" href="ouvrage/{{ $ouvrage->IDOuvrage }}/edit">
										<img style="width:20px; height:20px" src="{{asset('inc/images/modif1.jpg')}}" class="">
									</a>
									&nbsp;&nbsp;
									<a title="Visualier les pannes" data-toggle="modal" data-target="#modal-sofie-medium" class="ouvrirModalMedium" data-title="Liste des pannes enregistrées pour l'ouvrage N° {{ $ouvrage->CodeOuvrage}}" href="ouvrage/listPannes/{{ $ouvrage->IDOuvrage }}">
										<img style="width:20px; height:20px" src="{{asset('inc/images/loupe3.jpg')}}" class="">
									</a>
									&nbsp;&nbsp;
									<img style="width:20px; height:20px" src="{{asset('inc/images/icon_no.gif')}}" class="">
								</a>
							</td>
						</tr>
						<?php $i++; ?>
					@endforeach
					</tbody>	
		</table>
		
		<div><b><?php echo $totalOuvrage; ?> Ouvrage(s) au total  - Page <?php echo $page . ' / ' . $nbPage; ?></b></div>
		<div class="pagination pull-right" style=""> 
			  <ul>
				  <li name="prec"><a href="#" id="prec">Prec</a></li>
				  <select id="pagin" class="select">
				  <?php 
					for($i=1; $i<=$nbPage; $i++)
					{
				  ?>				
					<option value="<?php echo $i;?>" <?php if($page == $i)  echo 'selected'; ?>><?php echo $i;?></option>
					
					<!--<li ><a href="<?php //echo $i;?>"><?php //echo $i;?></a></li>-->
				  <?php	
					}
				  ?>
				  </select>
				  <li name="suiv" class="pull-right"><a href="#" id="suiv">Suiv</a></li>
			  </ul>
		</div>
		<input type='hidden' id='totalPage' value='<?php //echo $nbPage; ?>'>
		
<script>
		
		
$('.pagination a').on('click', function(e) {
	    page = $(this).attr('href');
		loadTab(page);
		//$('#pagin').val(page);
		//alert(page);
		//alert($(this).attr('href'));
		e.preventDefault();
	});
	
	$('#pagin').on('change', function(e) {
	    page = $(this).val();
		loadTab(page);
		//$('#pagin').val(page);
		e.preventDefault();
	});
		
		
	$('.fermer').on('click', function(e) {
		$('#modal').on('hidden', function() { $(this).removeData('modal'); })
		$('.modal').modal('hide');
	});
		
	$('.close').on('click', function(e) {
		$('#modal').on('hidden', function() { $(this).removeData('modal'); })
		$('.modal').modal('hide');
	});
	
			
	$(".ouvrirModal").click(function() {
	$('#modal').removeData('modal'); 
	
	$('#myModalLabel').html($(this).attr("data-title"));
	
	$('#modal-sofie .modal-body').load($(this).attr("href"),function(e){$('#modal-sofie').modal('show');})
		 
	});
	
	$(".ouvrirModalMedium").click(function() {
	$('#modalMedium').removeData('modal'); 
	
	$('#myModalLabelMedium').html($(this).attr("data-title"));
	
	//$('#modal-sofie-medium').modal({remote: $(this).attr("href")})
	$('#modal-sofie-medium .modal-body').load($(this).attr("href"),function(e){$('#modal-sofie-medium').modal('show');})
	});
		
	
	$("#btnSave").click(function() {
	
	var link = $("form").attr('action');
	//alert(link);
	var info = $('.error_alert');	
		
		var data = $("form").serialize();
		//alert(data);
		$.ajax({
			type:"POST",
			url : link,
			dataType : 'json',
			data : data, 
			success : function(response){
			showAlert(response.status, response.message);
			//alert(response);
			info.hide().find('ul').empty();
			if(response.status == 0)
			{	
				setTimeout(function() {$('#modal').modal('hide');}, 3000);
				//location.reload();
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
	

</script>