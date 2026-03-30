<div>
			<div class="titreModal">Information sur l'agent</div>
				<div class="alert" id="alert_bar" style="display:none;"></div>
				<div class="alert alert-danger error_alert" role="alert" style="display: none">
				  <ul></ul>
				</div>
				<div style="margin-bottom:10px; border: 1px solid black; padding:10px; FONT-WEIGHT: bold;"> 
					{{ Form::open(array('url' => 'agent/storeAjax')) }}
						<small class="text-danger">{{ $errors->first('nom') }}</small>
						<div class="form-group {{ $errors->has('nom') ? 'has-error has-feedback' : '' }}">
							{{ Form::label('nom', 'Nom : ') }}
							{{ Form::text('nom', null, array('class' => 'form-control')) }}
						</div>
						<small class="text-danger">{{ $errors->first('prenoms') }}</small>	
						<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
							{{ Form::label('prenoms', 'Prénoms : ') }}
							{{ Form::text('prenoms', null, array('class' => 'form-control')) }}
						</div>
						<div class="form-group {{ $errors->has('region') ? 'has-error' : '' }}">
						{{ Form::label('regions', 'Région : ') }}
						{{ Form::select('regions', $regions) }}
						</div>
						<div class="form-group {{ $errors->has('profiles') ? 'has-error' : '' }}">
						{{ Form::label('profiles', 'Fonction : ') }}
						{{ Form::select('profiles', $profiles) }}
						</div>
						
						
						<input type="hidden" name="operation" value="ajout_agent"/>
						
					{{ Form::close() }}
				</div>
</div>
