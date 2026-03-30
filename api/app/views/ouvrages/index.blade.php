@extends('admin.index')
@section('mainAdmin')
<div>
	<div style="text-align:center">
		<h2>Liste des ouvrages</h2>
		<a data-toggle="modal" data-target="#modal-sofie-medium" class="ouvrirModalMedium btn" data-title="Nouvel ouvrage" href="ouvrage/create"><i class="icon-plus" title="Nouveau"></i>Ajouter un Ouvrage</a>
	</div>
	<div class="contourTab">
	<img id="anim" alt="" src="{{asset('images/ajax-loader.gif')}}" style="display:none; z-index:5000; position: fixed; left: 50%; top: 50%;">
	
	<div class="pull-left" id="rechercher">
						<span style="text-align:center; font-size:14px; color:green;" class="titre">&nbsp;&nbsp;Rechercher&nbsp;</span>
								
						<!--: &nbsp;&nbsp;<input class="input-pv input-small" type="text" name="codeOuvrage" id="codeOuvrage" value="" placeholder="Code Forage"/>
						<!--
						&nbsp;&nbsp; Statut :  &nbsp;&nbsp;
						<select class="select input-search" id="searchStatut">
							<option value=""></option>
							<option value="SMS">En marche</option>
							<option value="STYLO">En panne</option> 
							<option value="SVI">Réparé</option>
						</select>
						-->
					{{ Form::open(array('url' => 'ouvrage/search')) }}
						
						{{ Form::text('codeOuvrage', null, array('id' => 'codeOuvrage', 'class' => 'input-small input-search' )) }}
						
						&nbsp;&nbsp; Régions :  &nbsp;&nbsp;
						{{ Form::select('regions', $regions, Input::get('regions'), array('id' => 'regions', 'class' => 'select input-search' )) }}
						
						&nbsp;&nbsp; Localité :  &nbsp;&nbsp;
						{{ Form::select('localites', $localites, Input::get('localites'), array('id' => 'localites')) }}
						
						&nbsp;&nbsp; Agent forma :  &nbsp;&nbsp;
						{{ Form::select('agentForma', $agentForma, Input::get('agentForma'), array('id' => 'agentForma')) }}
						
						&nbsp;&nbsp; Réparateur :  &nbsp;&nbsp;
						{{ Form::select('reparateur', $reparateur, Input::get('reparateur'), array('id' => 'reparateur')) }}
						
						&nbsp;&nbsp; 
						<!--<input type="submit" id="searchSubmit" >-->

						<a id="searchSubmit" href=""><img src="{{asset('inc/images/loupe4.jpg')}}" style="width:30px; vertical-align: top;" /></a>
					{{ Form::close() }}
				</div>
	
		<div id="tabl" >
			
		</div>
	
		
	</div>
</div>

@stop

@section('modal')


<!-- Fenetre modale de base -->
		<div class="modal hide fade" id="modal-sofie" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

			<div class="modal-header titre" style="background-color:green">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true" >X</button>
				<h3 id="myModalLabel"></h3>
			</div>
			<div class="modal-body" style="background-color:#FAFBFC;">
					
			</div>
			<div class="modal-footer">
				<a data-dismiss="modal" class="btn" >Fermer</a>
				<a class="btn btn-primary" id="btnSave">Enregistrer</a>
		    </div>
			
		</div>	


	<!-- Fenetre modale medium-->
		<div class="modal hide fade" style="width:95%;margin-left: -700px;" id="modal-sofie-medium" tabindex="-2" role="dialog" aria-labelledby="myModalLabelMedium" aria-hidden="true">

			<div class="modal-header titre" style="background-color:green">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true" >X</button>
				<h3 id="myModalLabelMedium"></h3>
			</div>
			<div class="modal-body" style="background-color:#FAFBFC;">
					
			</div>
			<div class="modal-footer">
				<a data-dismiss="modal" class="btn" >Fermer</a>
		  </div>
						
		</div>	
	@stop
	