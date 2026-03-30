@extends('admin.index')
@section('mainAdmin')
<div>
	<div style="text-align:center">
		<h2>Liste des ouvrages</h2>
		<a data-toggle="modal" data-target="#modal-sofie" class="ouvrirModal btn pull-right" data-title="Nouvel ouvrage" href="ouvrage/create"><i class="icon-plus" title="Nouveau"></i> Nouveau</a>
	</div>
	<div class="contourTab">
		{{ $table->render() }}
		{{ $table->script() }}
	</div>
</div>
@stop
