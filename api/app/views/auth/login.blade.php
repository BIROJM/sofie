@extends('layout')
@section('page')

	<div class="well" style="width:450px;  margin:0 auto;margin-top:20px;">
		@if(Session::has('error'))
			<div class="alert alert-danger">{{ Session::get('error') }}</div>
		@endif		
		<div>	
			{{ Form::open(array('url' => 'auth/login', 'method' => 'post', 'class' => 'form-horizontal panel')) }}	
					<fieldset>
					<legend align="center">Entrez vos identifiants de connexion</legend>
						<small class="text-danger">{{ $errors->first('name') }}</small>
					  <div class="form-group login {{ $errors->has('name') ? 'has-error' : '' }}">
					  	{{ Form::label('name', 'Login: ') }}
						{{ Form::text('name', null, array('class' => 'form-control', 'placeholder' => 'Identifiant')) }}
					  </div>
					  <small class="text-danger">{{ Session::get('pass') }}</small>
					  <div class="form-group login  {{ Session::has('pass') ? 'has-error' : '' }}">
						{{ Form::label('password', 'Mot de passe: ') }}
					  	{{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Mot de passe')) }}
					  </div>
						<hr>
						
						{{ Form::submit('Connexion', array('class' => 'btn btn-primary pull-right')) }}
					</fieldset>
					{{ Form::close() }}
		</div>
			
	</div>
@stop
