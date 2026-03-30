 <div>
			<div class="titreModal">Information sur la panne N° {{$panne->NumPanne}}</div>
			<div class="alert" id="alert_bar" style="display:none;"></div>
			<div class="alert alert-danger error_alert" role="alert" style="display: none">
              <ul></ul>
			</div>
			<div style="margin-bottom:10px; border: 1px solid black; padding:10px; FONT-WEIGHT: bold;"> 
				{{ Form::open(array('url' => 'panne/' . $panne->IDPanne, 'method' => 'PUT')) }}
				
					<small class="text-danger">{{ $errors->first('NumPanne') }}</small>
					<div class="form-group {{ $errors->has('codeOuvrage') ? 'has-error has-feedback' : '' }}">
						{{ Form::label('numPanne', 'N° Panne : ') }}
						{{ Form::text('numPAnne', $panne->NumPanne, array('class' => 'form-control')) }}
					</div>
					
					<small class="text-danger">{{ $errors->first('description')}}</small>	
					<div class="form-group {{ $errors->has('latitude') ? 'has-error' : '' }}">
						{{ Form::label('description', 'Description : ') }}
						{{ Form::text('description', Input::get('description'), array('class' => 'form-control')) }}
						
					</div>
										
					<input type="hidden" name="operation" value="modif_panne"/>
					<input type="hidden" name="IDPanne" value="{{$panne->IDPanne}}"/>
					<input type="submit" value="modifier" />
				{{ Form::close() }}
			</div>
</div>

</body>
</html>
