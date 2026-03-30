   <div>
		<div class="titreModal">Information sur l'utilisateur</div>
			<div class="alert" id="alert_bar" style="display:none;"></div>
			<div class="alert alert-danger error_alert" role="alert" style="display: none">
              <ul></ul>
			</div>
			<div style="margin-bottom:10px; border: 1px solid black; padding:10px; FONT-WEIGHT: bold;"> 
					{{ Form::open(array('url' => 'user/storeAjax', 'method' => 'post', 'class' => 'form-horizontal panel')) }}	
						<small class="text-danger">{{ $errors->first('name') }}</small>
					  <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
						{{ Form::label('name', 'Login : ') }}
					  	{{ Form::text('name', null, array('class' => 'form-control')) }}
					  </div>
					  <small class="text-danger">{{ $errors->first('password') }}</small>
					  <div class="form-group {{ $errors->has('password') ? 'has-error has-feedback' : '' }}">
					   {{ Form::label('password', 'Mot de passe : ') }}
					  	{{ Form::password('password', array('class' => 'form-control')) }}
					  </div>
					  <div class="form-group">
						 {{ Form::label('Confirmation_mot_de_passe', 'Confirmer : ') }}
					  	{{ Form::password('Confirmation_mot_de_passe', array('class' => 'form-control')) }}
					  </div>
					  <div>
						 {{ Form::label('role', 'Role : ') }}
						  {{ Form::select('role', array('1' => 'Administrateur', '2' => 'Utilisateur Direction centrale', '3' => 'Utilisateur Direction regionale' ), Input::get('role'), array('id' => 'role')) }}	
					  </div>
					  <div id="divRegion" class="form-group {{ $errors->has('regions') ? 'has-error' : '' }}">
					  {{ Form::label('regions', 'Région : ') }}
					   {{ Form::select('regions', $regions, Input::get('regions'), array('id' => 'regions')) }}
					  </div>						
					{{ Form::close() }}
			</div>
</div>

