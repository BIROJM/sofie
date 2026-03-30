<?php
require_once(dirname(__FILE__).'/class/statGlobale.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');

if(!empty($_GET['date_debut'])) StatGlobale::$periodeDebut = $_GET['date_debut'];
else StatGlobale::$periodeDebut = '2013-01-01';
if(!empty($_GET['date_fin'])) StatGlobale::$periodeFin = $_GET['date_fin'];
else StatGlobale::$periodeFin = '2017-01-01';
if(!empty($_GET['regions'])) StatGlobale::$region = $_GET['regions'];
else StatGlobale::$region ="";
if(!empty($_GET['localite'])) StatGlobale::$localite = $_GET['localite'];
else StatGlobale::$localite ="";


if(isset($_GET['getGlobalStat'])) 
{
	$nbrePanne = StatGlobale::nbrePanneReparation(1);

	$nbreReparation = StatGlobale::nbrePanneReparation(4);

	$nbreMoyPanne = StatGlobale::nbreMoyenPanneReparation(1);

	$nbreMoyPanne = number_format($nbreMoyPanne, 2, '.', '');

	$nbreMoyReparation = StatGlobale::nbreMoyenPanneReparation(4);

	$nbreMoyReparation = number_format($nbreMoyReparation, 2, '.', '');

	$delaiMoyReparation = StatGlobale::delaiMoyReparation();

	$nbreReparationEnCours = StatGlobale::nbrePanneReparation(3);

	$nbrePriseCommande = StatGlobale::nbrePanneReparation(2);

	$nbreCmdHorsDelai = StatGlobale::cmdHorsDelai();


}
?>

	<div class="col-xs-12 col-sm-6">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Etat global des signalisations <span class="libStatGen"></span></span>
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
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Nombre de panne</th>
							<th>Nombre de réparations</th>
							<th>Nombre moyen de pannes</th>
							<th>Nombre moyen de réparations</th>
							<th>Délai moyen de réparations</th>
							<!--<th>%</th>-->
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $nbrePanne; ?></td>
							<td><?php echo $nbreReparation; ?></td>
							<td><?php echo $nbreMoyPanne; ?></td>
							<td><?php echo $nbreMoyReparation; ?></td>
							<td><?php echo $delaiMoyReparation; ?></td>
							<!--<td>47 %</td>-->
							
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
		
