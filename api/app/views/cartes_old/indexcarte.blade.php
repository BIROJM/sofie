@extends('layout')
@section('username')
Honorat Niamkey
@stop
@section('page')
  <div class="gauche" role="tabpanel">
					  
				  <!-- Tab panes -->
				<div>
					<div class="region" id="{{$region->IDRegion}}">
							<div><h2>Région : {{$region->NomRegion}} <span id="{{$region->IDRegion}}Localite"></span> </h2></div>
							
							<div class="row">
															
								<div id="{{$region->IDRegion}}Map" class="spanPays" style="height: 840px;border: 1px solid #AAA;background:#F1EEE8"></div>
									 
									<input type="hidden" id="{{$region->IDRegion}}Carte" value="<?php echo $region["carte"]?>" />
									<input type="hidden" id="{{$region->IDRegion}}Lat" value="<?php echo $region["latitude"]?>"/>
									<input type="hidden" id="{{$region->IDRegion}}Lon" value="<?php echo $region["longitude"]?>"/>
									<input type="hidden" id="{{$region->IDRegion}}Id" value="<?php echo $region["IDRegion"]?>"/>
									<input type="hidden" id="{{$region->IDRegion}}Zoom" value="<?php echo $region["zoom"]?>"/>
									<input type="hidden" id="{{$region->IDRegion}}maxZoom" value="{{$region->maxZoom}}"/>
										
							</div>
					</div>
				</div>
			</div>
		
@stop

@section('scriptCarte')
<script src="{{asset('inc/js/functions.js')}}"></script>
@stop

@section('scriptCarte1')
	<script>
		var map = null;
		var id = $('.region').attr('id');
		var mapId = id + 'Map';
		var mapArea = $('#' + id + 'Carte').val();
		var lat = parseFloat($('#' + id + 'Lat').val());
		var lon = parseFloat($('#' + id + 'Lon').val());		
		var zmin = parseFloat($('#' + id + 'Zoom').val());
		var zmax = parseFloat($('#' + id + 'maxZoom').val());
		var idRegion = id;
		if(map != null)
		{
			map.remove();
		}
		loadMap(mapId,mapArea,lat,lon,zmin,zmax);
		loadPit(); 
		setInterval(loadPit,refreshTimer);
	</script>
@stop