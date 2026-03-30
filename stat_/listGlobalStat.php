<?php
require_once(dirname(__FILE__).'/class/statGlobale.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');

if(!empty($_GET['date_debut'])) StatGlobale::$periodeDebut = $_GET['date_debut'];
else StatGlobale::$periodeDebut = '2015-01-01';
if(!empty($_GET['date_fin'])) StatGlobale::$periodeFin = $_GET['date_fin'];
else StatGlobale::$periodeFin = '2015-12-31';
if(!empty($_GET['regions'])) StatGlobale::$region = $_GET['regions'];
else StatGlobale::$region ="";
if(!empty($_GET['localite'])) StatGlobale::$localite = $_GET['localite'];
else StatGlobale::$localite ="";


if(isset($_GET['getGlobalStat'])) 
{
	$nbrePanne = StatGlobale::nbrePanneReparation(2);

	$nbreReparation = StatGlobale::nbrePanneReparation(5);

	$nbreMoyPanne  = $nbrePanne / Stat::dateRange(StatGlobale::$periodeDebut, StatGlobale::$periodeFin);

	$nbreMoyReparation = $nbreReparation / Stat::dateRange(StatGlobale::$periodeDebut, StatGlobale::$periodeFin);

	$delaiMoyReparation = StatGlobale::delaiMoyReparation();

	//$nbreReparationEnCours = StatGlobale::nbrePanneReparation(3);

	$nbrePriseCommande = StatGlobale::nbrePanneReparation(3);

	$nbreCmdHorsDelai = StatGlobale::cmdHorsDelai();


}
?>
<div class="rows">
	<div class="col-xs-12 col-sm-8">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Etat global des pannes et réparations <span class="libStatGen"></span></span>
				</div>
				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content">
				<!--<p>Nombre de réparation par typologie de panne</p>-->
				<table class="table table-bordered table-striped table-hover table-heading">
					<thead style="font-size: 11px;valign:center;">
						<tr>
							<th>Nombre de panne</th>
							<th>Nombre de réparations</th>
							<th>Nombre moyen de pannes</th>
							<th>Nombre moyen de réparations</th>
							<th>Délai moyen de réparations</th>
							<!--<th>%</th>-->
						</tr>
					</thead>
					<tbody style="font-size: 12px;text-align:center;">
						<tr>
							<td><span class="badge alert-danger"><?php echo $nbrePanne; ?></span></td>
							<td><span class="badge alert-success"><?php echo $nbreReparation; ?></span></td>
														
							<td><span class="badge"><?php  echo number_format((float) $nbreMoyPanne , 3, '.', ''); ?> </td>
							
							<td><span class="badge"><?php echo number_format((float) $nbreMoyReparation , 3, '.', ''); ?> </td>
							<td><?php echo Stat::formatTime($delaiMoyReparation) . ' Jour(s)'; ?></td>
							<!--<td>47 %</td>-->
							
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	
	<div class="col-xs-12 col-sm-4">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Statut des prises de commande <span class="libStatGen"></span></span>
				</div>
				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content">
				<!--<p>Nombre de réparation par typologie de panne</p>-->
				<table class="table table-bordered table-striped table-hover table-heading">
					<thead style="font-size: 11px;valign:center;">
						<tr>
							<th>Prise de commande</th>
							<th>Réparation hors délai</th>							
							<!--<th>%</th>-->
						</tr>
					</thead>
					<tbody style="font-size: 12px;text-align:center;">
						<tr>
							<td><?php echo $nbrePriseCommande; ?></td>
							<td><span class="badge alert-danger"><?php echo $nbreCmdHorsDelai; ?></span></td>
													
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
		

</div>