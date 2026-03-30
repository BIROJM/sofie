@extends('layout')
@section('username')
Honorat Niamkey
@stop
@section('page')
  <div class="gauche" role="tabpanel">
				 <ul class="nav nav-tabs" id="tabRegion">
					@foreach ($regions as $region)
						<li>
							<a href="#{{$region->IDRegion}}"  data-toggle="tab"  name="{{$region->IDRegion}}" rel="txtTooltip" title="Double-cliquez pour afficher la carte" data-toggle="tooltip" data-placement="top">
								{{$region->NomRegion}}
							</a>
						</li>
					@endforeach
						<li>
							<a href="#6"  data-toggle="tab"  name="6" rel="txtTooltip" title="Double-cliquez pour afficher la carte" data-toggle="tooltip" data-placement="top">
								CARTE ENTIERE
							</a>
						</li>
					</ul>
				  
				  <!-- Tab panes -->
				<div class="tab-content">
					<div id="home">
					<p>
					Bienvenue sur l'Interface de visualisation des points de forages du projet SOFIE, Double-cliquez sur une des régions ci-dessus pour 
					faire apparaitre la carte correspondante.
					</p>
					</div>
					@foreach ($regions as $region)
						<div role="tabpanel" class="tab-pane fade in" id="{{$region->IDRegion}}">
							<div><h2>Région : {{$region->NomRegion}} <span id="{{$region->IDRegion}}Localite"></span> </h2>
								<img style="cursor: zoom-in;padding:px;" align ='right' id="zoomIconPlus" src="{{asset('inc/img/search.jpg')}}" height="20" width="20"/>
							</div>
							
							<div class="row">
															
								<div id="{{$region->IDRegion}}Map" class="spanPays" style="height: 840px;border: 1px solid #AAA;background:#F1EEE8"></div>
									<!-- <div id="zoomIconPlus" style="position:left;"><img src="{{asset('inc/img/search.jpg')}}"/></div> <!--<div id="zoomIconMoin">-</div>-->
									<input type="hidden" id="{{$region->IDRegion}}Carte" value="<?php echo $region["carte"]?>" />
									<input type="hidden" id="{{$region->IDRegion}}Lat" value="<?php echo $region["latitude"]?>"/>
									<input type="hidden" id="{{$region->IDRegion}}Lon" value="<?php echo $region["longitude"]?>"/>
									<input type="hidden" id="{{$region->IDRegion}}Id" value="<?php echo $region["IDRegion"]?>"/>
									<input type="hidden" id="{{$region->IDRegion}}Zoom" value="<?php echo $region["zoom"]?>"/>
									<input type="hidden" id="{{$region->IDRegion}}maxZoom" value="{{$region->maxZoom}}"/>
								
								
							</div>
						</div>
					@endforeach
					
					<div role="tabpanel" class="tab-pane fade in" id="6">
							<div><h2>CARTE ENTIERE <span id="6Localite"></span> </h2></div>
							
							<div class="row">
															
								<div id="6Map" class="spanPays" style="height: 840px;border: 1px solid #AAA;background:#F1EEE8"></div>
									 
									<input type="hidden" id="6Carte" value="cartes/entier" />
									<input type="hidden" id="6Lat" value="8.59634"/>
									<input type="hidden" id="6Lon" value="0.877325"/>
									<input type="hidden" id="6Id" value="6"/>
									<input type="hidden" id="6Zoom" value="7"/>
									<input type="hidden" id="6maxZoom" value="11"/>
								
								
							</div>
						</div>
				</div>
			</div>
@stop
@section('scriptCarte')
<script src="{{asset('inc/js/functions.js')}}"></script>
@stop