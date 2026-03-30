@extends('layout')
@section('page')
@section('mainAdmin')
  <div>
	<div style="text-align:center">
		<h2>Interface d'administration</h2>
	</div>
	
	<div class="contourAdmin">
		<div class="well  adminMenu">
		<img id="anim" alt="" src="images/ajax-loader.gif" style="display:none; z-index:5000; position: fixed; left: 50%; top: 50%;">
		<a data-toggle="modal" data-target="#modal-sofie" class="ouvrirModal btn pull-right" data-title="Nouvel ouvrage" href="ouvrage/create"><i class="icon-plus" title="Nouveau"></i> Nouveau</a>
		<a href="ouvrage">Ouvrages</a>
		</div>
		<div class="well adminMenu">
		<a data-toggle="modal" data-target="#modal-sofie" class="ouvrirModal btn pull-right" data-title="Ajouter un reparateur" href="reparateur/create"><i class="icon-plus" title="Nouveau"></i> Nouveau</a>
		Reparateurs
		</div>
		<div class="well adminMenu">
		<a data-toggle="modal" data-target="#modal-sofie" class="ouvrirModal btn pull-right" data-title="Ajouter un agent" href="agent/create"><i class="icon-plus" title="Nouveau"></i> Nouveau</a>
		Agents
		</div>
		<div class="well adminMenu">
		<a data-toggle="modal" data-target="#modal-sofie" class="ouvrirModal btn pull-right" data-title="Ajouter un nouveau comité" href="comite/create"><i class="icon-plus" title="Nouveau"></i> Nouveau</a>
		Comités
		</div>
		<div class="well adminMenu">
		<a data-toggle="modal" data-target="#modal-sofie" class="ouvrirModal btn pull-right" data-title="Ajouter une nouvelle localité" href="localite/create"><i class="icon-plus" title="Nouveau"></i> Nouveau</a>
		Localités
		</div>
		<div class="well  adminMenu">
		<a data-toggle="modal" data-target="#modal-sofie" class="ouvrirModal btn pull-right" data-title="Ajouter un nouveau utilisateur" href="user/create"><i class="icon-plus" title="Nouveau"></i> Nouveau</a>
		Utilisateurs
		</div>
		<!--<div class="well  adminMenu">
		<a data-toggle="modal" data-target="#modal-sofie" class="ouvrirModal btn pull-right" data-title="Préferences" href=""><i class="icon-plus" title="Nouveau"></i> Nouveau</a>
		Préferences
		</div>
		-->
	</div>
  </div>
 @show
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
	@stop
@stop

@section('admin')
<script src="{{asset('inc/js/operations.js')}}"></script>
@stop	