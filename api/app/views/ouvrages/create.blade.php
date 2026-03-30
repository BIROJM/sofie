 <div>
			<div class="titreModal">Information sur l'ouvrage</div>
			<div class="alert" id="alert_bar" style="display:none;"></div>
			<div class="alert alert-danger error_alert" role="alert" style="display: none">
              <ul></ul>
			</div>
			<div style="margin-bottom:10px; border: 1px solid black; padding:10px; FONT-WEIGHT: bold;"> 
				@if($operation != 'edit')
				{{ Form::open(array('url' => 'ouvrage/storeAjax')) }}
				@else
				{{ Form::open(array('url' => 'ouvrage/' . $ouvrage->IDOuvrage, 'method' => 'PUT')) }}
				@endif
					<small class="text-danger">{{ $errors->first('codeOuvrage') }}</small>
					<div class="form-group {{ $errors->has('codeOuvrage') ? 'has-error has-feedback' : '' }}">
						{{ Form::label('codeOuvrage', 'Code : ') }}
						@if($operation != 'edit')
						{{ Form::text('codeOuvrage', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('codeOuvrage', $ouvrage->CodeOuvrage, array('class' => 'form-control')) }}
						@endif
					</div>
					<small class="text-danger">{{ $errors->first('typeOuvrage') }}</small>	
					<div class="form-group {{ $errors->has('typeOuvrage') ? 'has-error' : '' }}">
						{{ Form::label('typeOuvrage', 'Type d\'ouvrage : ') }}
						@if($operation != 'edit')
						{{ Form::select('typeOuvrage', array ('Forage' => 'Forage', 'Puit' => 'Puit'), Input::get('typeOuvrage')) }}
						@else
						{{ Form::select('typeOuvrage', array ('Forage' => 'Forage', 'Puit' => 'Puit'), $ouvrage->TypeOuvrage) }}
						@endif
						
					</div>
					<small class="text-danger">{{ $errors->first('latitude') }}</small>	
					<div class="form-group {{ $errors->has('latitude') ? 'has-error' : '' }}">
						{{ Form::label('latitude', 'Latitude : ') }}
						@if($operation != 'edit')
						{{ Form::text('latitude', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('latitude', $ouvrage->Latitude, array('class' => 'form-control')) }}
						@endif
					</div>
					<small class="text-danger">{{ $errors->first('longitude') }}</small>	
					<div class="form-group {{ $errors->has('longitude') ? 'has-error' : '' }}">
						{{ Form::label('longitude', 'Longitude : ') }}
						@if($operation != 'edit')
						{{ Form::text('longitude', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('longitude',$ouvrage->Longitude, array('class' => 'form-control')) }}
						@endif
					</div>
					<div class="form-group {{ $errors->has('region') ? 'has-error' : '' }}">
					{{ Form::label('regions', 'Région : ') }}
					@if($operation != 'edit')
					{{ Form::select('regions', $regions, Input::get('regions'), array('id' => 'regions')) }}
					@else
					{{ Form::select('regions', $regions, $ouvrage->region->IDRegion, array('id' => 'regions')) }}
					@endif
					</div>
					<div class="form-group {{ $errors->has('localites') ? 'has-error' : '' }}">
					{{ Form::label('localites', 'Localité : ') }}
					@if($operation != 'edit')
				    {{ Form::select('localites', $localites, Input::get('localites'), array('id' => 'localites'))}}
					@else
					{{ Form::select('localites', $localites, $ouvrage->localite->IDLocalite, array('id' => 'localites'))}}
					@endif
					</div>									
					<div class="form-group {{ $errors->has('comites') ? 'has-error' : '' }}">
					{{ Form::label('comites', 'Comité eau: ')}}
					@if($operation != 'edit')
				    {{ Form::select('comites', $comites, Input::get('comites'), array('id' => 'comites')) }}
					@else
					{{ Form::select('comites', $comites, $ouvrage->IDComite, array('id' => 'comites')) }}
					@endif
					</div>
					<!--<div class="form-group {{ $errors->has('agentforma') ? 'has-error' : '' }}">
					{{-- Form::label('agentforma', 'Agent forma : ') --}}
					{{-- Form::select('agentforma', $AgentForma) --}}
					</div>-->
					<!--<div class="form-group {{ $errors->has('reparateur') ? 'has-error' : '' }}">
					{{-- Form::label('reparateur', 'Réparateur : ') --}}
					{{-- Form::select('reparateur', $reparateur, Input::get('reparateurs'), array('id' => 'reparateurs')) --}}
					</div>-->
					@if($operation != 'edit')
					<input type="hidden" name="operation" value="ajout_ouvrage"/>
					@else
					<input type="hidden" name="operation" value="modif_ouvrage"/>
					@endif
					
					<input type="hidden" name="operation" value="ajout_ouvrage"/>			
					
				{{ Form::close() }}
			</div>
</div>
<script  type="text/javascript">
//Ajout de fonctionnalite ajax 

jQuery(document).ready(function() {
	@if($operation != 'edit')
	$("#localites").html('');
	@endif
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
		   console.log('val =' + val.IDLocalite + ' - '+val.NomLocalite);
        });                   
               $("#localites").html(options);
            }
			
		});

	});
	
	@if($operation != 'edit')
	$("#comites").html('');
	@endif
	$("#localites").on('change', function(e) {
	
		$.ajax({
			type:"GET",
			url : "ouvrage/selectComites/"+$(this).val(),
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
	
});

</script>
</body>
</html>
