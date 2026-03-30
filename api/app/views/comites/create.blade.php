 <div>
			<div class="titreModal">Information sur le comité</div>
			<div class="alert" id="alert_bar" style="display:none;"></div>
			<div class="alert alert-danger error_alert" role="alert" style="display: none">
              <ul></ul>
			</div>
			<div style="margin-bottom:10px; border: 1px solid black; padding:10px; FONT-WEIGHT: bold;"> 
				{{ Form::open(array('url' => 'comite/storeAjax')) }}
					<small class="text-danger">{{ $errors->first('nomcomite') }}</small>
					<div class="form-group {{ $errors->has('nomcomite') ? 'has-error has-feedback' : '' }}">
						{{ Form::label('nomcomite', 'Nom comité : ') }}
						{{ Form::text('nomcomite', null, array('class' => 'form-control')) }}
					</div>
					<small class="text-danger">{{ $errors->first('nom') }}</small>
					<div class="form-group {{ $errors->has('nom') ? 'has-error has-feedback' : '' }}">
						{{ Form::label('nom', 'Nom du sécretaire : ') }}
						{{ Form::text('nom', null, array('class' => 'form-control')) }}
					</div>
					<small class="text-danger">{{ $errors->first('prenoms') }}</small>	
					<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
						{{ Form::label('prenoms', 'Prénoms sécretaire : ') }}
						{{ Form::text('prenoms', null, array('class' => 'form-control')) }}
					</div>
					
					<div class="form-group {{ $errors->has('localites') ? 'has-error' : '' }}">
					{{ Form::label('localites', 'Localité : ') }}
				    {{ Form::select('localites', $localites, Input::get('localites'), array('id' => 'localites')) }}
					</div>				
					
				
				{{ Form::close() }}
			</div>
</div>
