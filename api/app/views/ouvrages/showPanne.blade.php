<?php 
//var_dump($pannes);
if(count($pannes) == 0) die ('Aucune pannes déclarées pour cet ouvrage');
?>
<div>
	
	<div class="contourTab">
		<table class="table table-bordered table-condensed">
					<thead class="tabmedium" >
						<tr>
							<th width="30" style="text-align:center;">N° Panne </th>
							<th width="30" style="text-align:center;">Statut actuel </th>
							<th width="50"  style="text-align:center;">Apparu le</th>
							<th width="50"  style="text-align:center;">Pris en charge le</th>
							<th width="50"  style="text-align:center;">Debut reparation</th>
							<th width="50"  style="text-align:center;">Reparé le</th>							
							<th width="50"  style="text-align:center;">Reparateur</th> 
							<th width="50"  style="text-align:center;">Description</th> 
							<th width="50"  style="text-align:center;">Saisie par</th> 
							<th width="10"  style="text-align:center; "></th>
						</tr>
					</thead>
					<tbody>
					<?php $i =0; ?>
					@foreach ($pannes as $panne)
						<tr class="ligne ligneTab 
						<?php if($i%2==0)  { echo 'interligne'; }
							//echo $panne->statut->couleur;
						?>">
							<td>{{ $panne->NumPanne }}</td>
							<td>{{ $panne->statut->libelleStatut}}</td>
							<td>{{{ $panne->DateApparution }}}</td>
							<td>{{{ $panne->DatePriseCharge}}}</td>
							<td>{{{ $panne->DateDebutRep}}}</td>
							<td>{{{ $panne->DateReparation}}}</td> 
							<td>{{{ $panne->ouvrage->reparateur->reparateurFullName or 'Aucun'}}} <span class="pull-right">[N° : {{{$panne->ouvrage->reparateur->numeroAppel->NumAppel or '-'}}}]</span></td> 
							<td>{{{ $panne->Description or 'Aucun'}}}</td> 
							<td>{{{ $panne->agent->agentFullName or 'Aucun'}}}</td> 
							<td>
							<a href="#">
									
									<a data-toggle="modal" data-target="#modal-sofie" class="ouvrirModal" data-title="Saisir les informations sur une panne" href="panne/{{ $panne->IDPanne }}/edit">
										<img style="width:20px; height:20px" src="{{asset('inc/images/modif2.jpg')}}" class="">
									</a>
									&nbsp;&nbsp;
									<img style="width:20px; height:20px" src="{{asset('inc/images/icon_no.gif')}}" class="">
							</a>
							</td>
						</tr>
						<?php $i++; ?>
					@endforeach
					</tbody>	
		</table>
	</div>
</div>
