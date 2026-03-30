    <div>
			<div class="titreModal">Information sur le reparateur</div>
			<div class="alert" id="alert_bar" style="display:none;"></div>
			<div class="alert alert-danger error_alert" role="alert" style="display: none">
              <ul></ul>
			</div>
			<div style="margin-bottom:10px; border: 1px solid black; padding:10px; FONT-WEIGHT: bold;"> 
				{{ Form::open(array('url' => 'reparateur/storeAjax')) }}
					<span class="text-danger" id="errorNom">{{ $errors->first('nom') }}</span>
					<div class="form-group {{ $errors->has('nom') ? 'has-error has-feedback' : '' }}">
						{{ Form::label('nom', 'Nom : ') }}
						{{ Form::text('nom', null, array('class' => 'form-control')) }}
					</div>
					<small class="text-danger" id="errorPrenoms">{{ $errors->first('prenoms') }}</small>	
					<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
						{{ Form::label('prenoms', 'Prénoms : ') }}
						{{ Form::text('prenoms', null, array('class' => 'form-control')) }}
					</div>
									
					<input type="hidden" name="operator" value="reparateur/storeAjax"/>
					
				{{ Form::close() }}
			</div>
		</div>