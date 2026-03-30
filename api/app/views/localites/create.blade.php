<div>
			<div class="titreModal">Information sur la localité</div>
				<div class="alert" id="alert_bar" style="display:none;"></div>
				<div class="alert alert-danger error_alert" role="alert" style="display: none">
				  <ul></ul>
				</div>
				<div style="margin-bottom:10px; border: 1px solid black; padding:10px; FONT-WEIGHT: bold;"> 
				{{ Form::open(array('url' => 'localite/storeAjax')) }}
					<small class="text-danger">{{ $errors->first('nomLocalite') }}</small>
					<div class="form-group {{ $errors->has('nom') ? 'has-error has-feedback' : '' }}">
						{{ Form::label('nomLocalite', 'Nom de la localité : ') }}
						{{ Form::text('nomLocalite', null, array('class' => 'form-control', 'placeholder' => 'nom localite')) }}
					</div>
					<small class="text-danger">{{ $errors->first('nbHabitant') }}</small>	
					<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
						{{ Form::label('nbHabitant', 'Nombre d\'habitants : ') }}
						{{ Form::text('nbHabitant', null, array('class' => 'form-control', 'placeholder' => 'Nombre habitant')) }}
					</div>
					<div class="form-group {{ $errors->has('region') ? 'has-error' : '' }}">
					{{ Form::label('regions', 'Région : ') }}
					{{ Form::select('regions', $regions) }}
					</div>
					<div class="form-group {{ $errors->has('agentforma') ? 'has-error' : '' }}">
					{{ Form::label('agentforma', 'Agent forma :') }}
					{{ Form::select('agentforma', $AgentForma) }}
					</div>
					<div class="form-group {{ $errors->has('reparateur') ? 'has-error' : '' }}">
					{{ Form::label('reparateur', 'Reparateur :') }}
					{{ Form::select('reparateur', $reparateur) }}
					</div>
				<input type="hidden" name="operation" value="ajout_localite"/>
				{{ Form::close() }}
			</div>
</div>

<script  type="text/javascript">
//Ajout de fonctionnalite ajax 

jQuery(document).ready(function() {

	$("#agentforma").html('');
	$("#regions").on('change', function(e) {
	
		var DATA = 'id=' + $(this).val();
		$.ajax({
			type:"GET",
			url : "localite/selectAgentsForma/"+$(this).val(),
			dataType : 'json',
			//data : DATA, 
			success : function(donnee){
			var options = '';
			options+='<option value="0" selected> </option>';
				$.each(donnee, function (key,val) {
           options +='<option value="' + val.IDAgent +'"> '+ val.PrenomsAgent + ' ' + val.NomAgent + '</option>';
		  // console.log('val =' + val.IDLocalite + ' - '+val.NomLocalite);
        });                   
               $("#agentforma").html(options);
            }
			
		});

	});

});

</script>