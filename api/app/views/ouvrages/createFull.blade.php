 <div>
			<div class="titreModal">Information sur l'ouvrage</div>
			<div class="alert" id="alert_bar" style="display:none;"></div>
			<div class="alert alert-danger error_alert" role="alert" style="display: none">
              <ul></ul>
			</div>
			<div class="span4" style="margin-bottom:10px; border: 1px solid black; padding:10px; FONT-WEIGHT: bold;"> 
				
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
			
			<div class="span4" style="margin-bottom:10px; border: 1px solid black; padding:10px; FONT-WEIGHT: bold;">
			
			<small class="text-danger">{{ $errors->first('codeOuvrage') }}</small>
					<div class="form-group {{ $errors->has('codeOuvrage') ? 'has-error has-feedback' : '' }}">
						{{ Form::label('numIRH', 'N° IRH : ') }}
						@if($operation != 'edit')
						{{ Form::text('numIRH', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('numIRH', $ouvrage->NumIRH, array('class' => 'form-control')) }}
						@endif
					</div>
					<small class="text-danger">{{ $errors->first('autreNumID') }}</small>	
					<div class="form-group {{ $errors->has('typeOuvrage') ? 'has-error' : '' }}">
						{{ Form::label('autreNumID', 'N° Autre Num ID : ') }}
						@if($operation != 'edit')
						{{ Form::text('autreNumID', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('autreNumID', $ouvrage->AutreNumID, array('class' => 'form-control')) }}
						@endif
						
					</div>
					<small class="text-danger">{{ $errors->first('designation') }}</small>	
					<div class="form-group {{ $errors->has('latitude') ? 'has-error' : '' }}">
						{{ Form::label('designation', 'Designation : ') }}
						@if($operation != 'edit')
						{{ Form::text('designation', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('designation', $ouvrage->Designation, array('class' => 'form-control')) }}
						@endif
					</div>
					<small class="text-danger">{{ $errors->first('operateurSaisie') }}</small>	
					<div class="form-group {{ $errors->has('longitude') ? 'has-error' : '' }}">
						{{ Form::label('operateurSaisie', 'Opérateur de saisie : ') }}
						@if($operation != 'edit')
						{{ Form::text('operateurSaisie', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('operateurSaisie',$ouvrage->OperateurSaisie, array('class' => 'form-control')) }}
						@endif
					</div>
					<div class="form-group {{ $errors->has('region') ? 'has-error' : '' }}">
						{{ Form::label('numLocaliteProgress', 'N° Localite Progress : ') }}
						@if($operation != 'edit')
						{{ Form::text('numLocaliteProgress', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('numLocaliteProgress',$ouvrage->NumLocaliteProgress, array('class' => 'form-control')) }}
						@endif
					</div>
					<div class="form-group {{ $errors->has('localites') ? 'has-error' : '' }}">
						{{ Form::label('typeLocalite', 'Type de Localite : ') }}
						@if($operation != 'edit')
						{{ Form::text('typeLocalite', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('typeLocalite',$ouvrage->TypeLocalite, array('class' => 'form-control')) }}
						@endif
					</div>									
					<div class="form-group {{ $errors->has('comites') ? 'has-error' : '' }}">
						{{ Form::label('etatInitialCaptage', 'Etat initial captage : ') }}
						@if($operation != 'edit')
						{{ Form::text('etatInitialCaptage', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('etatInitialCaptage',$ouvrage->EtatInitialCaptage, array('class' => 'form-control')) }}
						@endif
					</div>
					<div class="form-group {{ $errors->has('propriete') ? 'has-error' : '' }}">
						{{ Form::label('propriete', 'Propriete : ') }}
						@if($operation != 'edit')
						{{ Form::text('propriete', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('propriete',$ouvrage->propriete, array('class' => 'form-control')) }}
						@endif
					</div>
			</div>
			
			<div class="span4" style="margin-bottom:10px; border: 1px solid black; padding:10px; FONT-WEIGHT: bold;">
			
			<small class="text-danger">{{ $errors->first('usage') }}</small>
					<div class="form-group {{ $errors->has('codeOuvrage') ? 'has-error has-feedback' : '' }}">
						{{ Form::label('usage', 'Usage : ') }}
						@if($operation != 'edit')
						{{ Form::text('usage', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('usage', $ouvrage->Usage, array('class' => 'form-control')) }}
						@endif
					</div>
					<small class="text-danger">{{ $errors->first('autreNumID') }}</small>	
					<div class="form-group {{ $errors->has('typeOuvrage') ? 'has-error' : '' }}">
						{{ Form::label('nomDuProjet', 'Nom du projet : ') }}
						@if($operation != 'edit')
						{{ Form::text('nomDuProjet', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('nomDuProjet', $ouvrage->NomDuProjet, array('class' => 'form-control')) }}
						@endif
						
					</div>
					<small class="text-danger">{{ $errors->first('financement') }}</small>	
					<div class="form-group {{ $errors->has('latitude') ? 'has-error' : '' }}">
						{{ Form::label('financement', 'Financement : ') }}
						@if($operation != 'edit')
						{{ Form::text('financement', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('financement', $ouvrage->Financement, array('class' => 'form-control')) }}
						@endif
					</div>
					<small class="text-danger">{{ $errors->first('ingenieurConseil') }}</small>	
					<div class="form-group {{ $errors->has('longitude') ? 'has-error' : '' }}">
						{{ Form::label('ingenieurConseil', 'Ingenieur conseil : ') }}
						@if($operation != 'edit')
						{{ Form::text('ingenieurConseil', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('ingenieurConseil',$ouvrage->IngenieurConseil, array('class' => 'form-control')) }}
						@endif
					</div>
					<div class="form-group {{ $errors->has('entreprise') ? 'has-error' : '' }}">
						{{ Form::label('entreprise', 'Entreprise : ') }}
						@if($operation != 'edit')
						{{ Form::text('entreprise', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('entreprise',$ouvrage->Entreprise, array('class' => 'form-control')) }}
						@endif
					</div>
					<div class="form-group {{ $errors->has('dateFinForation') ? 'has-error' : '' }}">
						{{ Form::label('dateFinForation', 'Date fin foration : ') }}
						@if($operation != 'edit')
						{{ Form::text('dateFinForation', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('dateFinForation',$ouvrage->DateFinForation, array('class' => 'form-control')) }}
						@endif
					</div>									
					<div class="form-group {{ $errors->has('debit') ? 'has-error' : '' }}">
						{{ Form::label('debit', 'Debit : ') }}
						@if($operation != 'edit')
						{{ Form::text('debit', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('debit',$ouvrage->Debit, array('class' => 'form-control')) }}
						@endif
					</div>
					<div class="form-group {{ $errors->has('propriete') ? 'has-error' : '' }}">
						{{ Form::label('profondeurTotale', 'Profondeur totale : ') }}
						@if($operation != 'edit')
						{{ Form::text('profondeurTotale', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('profondeurTotale',$ouvrage->ProfondeurTotale, array('class' => 'form-control')) }}
						@endif
					</div>
			</div>
			
			<div class="span4" style="margin-bottom:10px; border: 1px solid black; padding:10px; FONT-WEIGHT: bold;">
			
			
					<div class="form-group {{ $errors->has('profondeurEquipee') ? 'has-error' : '' }}">
						{{ Form::label('profondeurEquipee', 'Profondeur équipée : ') }}
						@if($operation != 'edit')
						{{ Form::text('profondeurEquipee', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('profondeurEquipee',$ouvrage->ProfondeurEquipee, array('class' => 'form-control')) }}
						@endif
					</div>
					<small class="text-danger">{{ $errors->first('niveauStatique') }}</small>
					<div class="form-group {{ $errors->has('niveauStatique') ? 'has-error has-feedback' : '' }}">
						{{ Form::label('niveauStatique', 'Niveau Statique : ') }}
						@if($operation != 'edit')
						{{ Form::text('niveauStatique', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('niveauStatique', $ouvrage->NiveauStatique, array('class' => 'form-control')) }}
						@endif
					</div>
					<small class="text-danger">{{ $errors->first('dateNs') }}</small>	
					<div class="form-group {{ $errors->has('dateNs') ? 'has-error' : '' }}">
						{{ Form::label('dateNs', 'Date Ns : ') }}
						@if($operation != 'edit')
						{{ Form::text('dateNs', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('dateNs', $ouvrage->DateNs, array('class' => 'form-control')) }}
						@endif
						
					</div>
					<small class="text-danger">{{ $errors->first('epaisseurAlteration') }}</small>	
					<div class="form-group {{ $errors->has('epaisseurAlteration') ? 'has-error' : '' }}">
						{{ Form::label('geomorphologie', 'Geomorphologie : ') }}
						@if($operation != 'edit')
						{{ Form::text('geomorphologie', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('geomorphologie', $ouvrage->Geomorphologie, array('class' => 'form-control')) }}
						@endif
					</div>
					<small class="text-danger">{{ $errors->first('epaisseurAlteration') }}</small>	
					<div class="form-group {{ $errors->has('epaisseurAlteration') ? 'has-error' : '' }}">
						{{ Form::label('epaisseurAlteration', 'Epaisseur Alteration : ') }}
						@if($operation != 'edit')
						{{ Form::text('epaisseurAlteration', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('epaisseurAlteration',$ouvrage->EpaisseurAlteration, array('class' => 'form-control')) }}
						@endif
					</div>
					<div class="form-group {{ $errors->has('NomAquifere') ? 'has-error' : '' }}">
						{{ Form::label('nomAquifere', 'Nom aquifere : ') }}
						@if($operation != 'edit')
						{{ Form::text('nomAquifere', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('nomAquifere',$ouvrage->NomAquifere, array('class' => 'form-control')) }}
						@endif
					</div>
					<div class="form-group {{ $errors->has('dateFinForation') ? 'has-error' : '' }}">
						{{ Form::label('dateFinForation', 'Date fin foration : ') }}
						@if($operation != 'edit')
						{{ Form::text('dateFinForation', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('dateFinForation',$ouvrage->DateFinForation, array('class' => 'form-control')) }}
						@endif
					</div>									
					<div class="form-group {{ $errors->has('lithologieAquifere') ? 'has-error' : '' }}">
						{{ Form::label('lithologieAquifere', 'Lithologie aquifere : ') }}
						@if($operation != 'edit')
						{{ Form::text('lithologieAquifere', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('lithologieAquifere',$ouvrage->LithologieAquifere, array('class' => 'form-control')) }}
						@endif
					</div>
					
			</div>
			
			
			<div class="span4" style="margin-bottom:10px; border: 1px solid black; padding:10px; FONT-WEIGHT: bold;">
			
					<div class="form-group {{ $errors->has('profondeurToit') ? 'has-error' : '' }}">
						{{ Form::label('profondeurToit', 'profondeur Toit : ') }}
						@if($operation != 'edit')
						{{ Form::text('profondeurToit', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('profondeurToit',$ouvrage->ProfondeurToit, array('class' => 'form-control')) }}
						@endif
					</div>
					
					<div class="form-group {{ $errors->has('profondeurEquipee') ? 'has-error' : '' }}">
						{{ Form::label('profondeurMur', 'Profondeur mur : ') }}
						@if($operation != 'edit')
						{{ Form::text('profondeurMur', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('profondeurMur',$ouvrage->ProfondeurMur, array('class' => 'form-control')) }}
						@endif
					</div>
					<small class="text-danger">{{ $errors->first('datePrelevement') }}</small>
					<div class="form-group {{ $errors->has('datePrelevement') ? 'has-error has-feedback' : '' }}">
						{{ Form::label('datePrelevement', 'Date de prelevement : ') }}
						@if($operation != 'edit')
						{{ Form::text('datePrelevement', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('datePrelevement', $ouvrage->DatePrelevement, array('class' => 'form-control')) }}
						@endif
					</div>
					<small class="text-danger">{{ $errors->first('temperature') }}</small>	
					<div class="form-group {{ $errors->has('temperature') ? 'has-error' : '' }}">
						{{ Form::label('temperature', 'Temperature : ') }}
						@if($operation != 'edit')
						{{ Form::text('Temperature', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('temperature', $ouvrage->Temperature, array('class' => 'form-control')) }}
						@endif
						
					</div>
					<small class="text-danger">{{ $errors->first('conductivite') }}</small>	
					<div class="form-group {{ $errors->has('epaisseurAlteration') ? 'has-error' : '' }}">
						{{ Form::label('conductivite', 'Conductivite : ') }}
						@if($operation != 'edit')
						{{ Form::text('conductivite', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('conductivite', $ouvrage->Conductivite, array('class' => 'form-control')) }}
						@endif
					</div>
					<small class="text-danger">{{ $errors->first('ph') }}</small>	
					<div class="form-group {{ $errors->has('ph') ? 'has-error' : '' }}">
						{{ Form::label('ph', 'PH : ') }}
						@if($operation != 'edit')
						{{ Form::text('ph', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('ph',$ouvrage->Ph, array('class' => 'form-control')) }}
						@endif
					</div>
					<div class="form-group {{ $errors->has('ferTotal') ? 'has-error' : '' }}">
						{{ Form::label('ferTotal', 'Fer total : ') }}
						@if($operation != 'edit')
						{{ Form::text('ferTotal', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('ferTotal',$ouvrage->FerTotal, array('class' => 'form-control')) }}
						@endif
					</div>
					<div class="form-group {{ $errors->has('nitrates') ? 'has-error' : '' }}">
						{{ Form::label('Nitrates', 'Nitrates : ') }}
						@if($operation != 'edit')
						{{ Form::text('Nitrates', null, array('class' => 'form-control')) }}
						@else
						{{ Form::text('Nitrates',$ouvrage->Nitrates, array('class' => 'form-control')) }}
						@endif
					</div>									
					
					
			</div>
</div>
<script  type="text/javascript">
//Ajout de fonctionnalite ajax 

jQuery(document).ready(function() {

	@if($operation != 'edit')
	$("#localites").html('');
	@endif
	$("#regions").on('change', function(e) {
	//alert($("#regions").val());
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
