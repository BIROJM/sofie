<?php
require_once(dirname(__FILE__).'/class/statGlobale.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');

if(!empty($_GET['date_debut'])) StatGlobale::$periodeDebut = $_GET['date_debut'];
else StatGlobale::$periodeDebut = date('Y') .'-01-01';
if(!empty($_GET['date_fin'])) StatGlobale::$periodeFin = $_GET['date_fin'];
else StatGlobale::$periodeFin = date('Y') .'-12-31';
if(!empty($_GET['regions'])) StatGlobale::$region = $_GET['regions'];
else StatGlobale::$region ="";
if(!empty($_GET['localite'])) StatGlobale::$localite = $_GET['localite'];
else StatGlobale::$localite ="";


if(isset($_GET['getGlobalStat'])) 
{
	$nbrePanne = StatGlobale::nbrePanneReparation(1);

	$nbreReparation = StatGlobale::nbrePanneReparation(4);

	$nbreMoyPanne  = $nbrePanne / Stat::dateRange(StatGlobale::$periodeDebut, StatGlobale::$periodeFin);

	$nbreMoyReparation = $nbreReparation / Stat::dateRange(StatGlobale::$periodeDebut, StatGlobale::$periodeFin);

	$delaiMoyReparation = StatGlobale::delaiMoyReparation();

	$tauxRealisation = StatGlobale::tauxRealisation($nbreReparation, $nbrePanne);
	//$nbreReparationEnCours = StatGlobale::nbrePanneReparation(3);

	$nbrePriseCommande = StatGlobale::nbrePanneReparation(2);

	$nbreCmdHorsDelai = StatGlobale::cmdHorsDelai(6);
	
	$nbreRepHorsDelai = StatGlobale::cmdHorsDelai(9);

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
							<th>Nombre de pannes</th>
							<th>Nombre de réparations</th>
							<th>Taux de réalisation</th>
							<!--<th>Nombre moyen de pannes</th>-->
							<!--<th>Nombre moyen de réparations</th>-->
							<th>Délai moyen de réparations</th>
						</tr>
					</thead>
					<tbody style="font-size: 12px;text-align:center;">
						<tr>
							<td><span class="badge alert-danger"><?php echo $nbrePanne; ?></span></td>
							<td><span class="badge alert-success"><?php echo $nbreReparation; ?></span></td>
							<td><span class="badge"><?php  echo number_format((float) $tauxRealisation, 2, '.', ''); ?> % </td>							
							<!--<td><span class="badge"><?php  //echo number_format((float) $nbreMoyPanne , 3, '.', ''); ?> </td>-->
							
							<!--<td><span class="badge"><?php //echo number_format((float) $nbreMoyReparation , 3, '.', ''); ?> </td>--> 
							<td><?php echo Stat::formatTime($delaiMoyReparation) . ' Jour(s)'; ?></td>
							<!--<td>47 %</td>-->
							
						</tr>
					</tbody>
				</table>
			</div>		
						
		</div>
		
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Moyenne des pannes et réparations <span class="libStatGen"></span></span>
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
				<p>				
				De : <select type="text" value="test">
							<option value="1">Janvier</option>
							<option value="2">Février</option>
							<option value="3">Mars</option>
							<option value="4">Avril</option>
							<option value="5">Mai</option>
							<option value="6">Juin</option>
							<option value="7">Juillet</option>
							<option value="8">Aout</option>
							<option value="9">Septembre</option>
							<option value="10">octobre</option>
							<option value="11">Novembre</option>
							<option value="12">Décembre</option>
						</select>
						
						<select type="text" value="test">
							<?php 
								$subDate = "20";
								for($i=16; $i<50; $i++)
								{
									echo "<option value= '" . $subDate . $i . "'>" . $subDate . $i . "</option>";
								}
							?>							
						</select>
						
				
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; A : <select type="text" value="test">
							<option value="1">Janvier</option>
							<option value="2">Février</option>
							<option value="3">Mars</option>
							<option value="4">Avril</option>
							<option value="5">Mai</option>
							<option value="6">Juin</option>
							<option value="7">Juillet</option>
							<option value="8">Aout</option>
							<option value="9">Septembre</option>
							<option value="10">octobre</option>
							<option value="11">Novembre</option>
							<option value="12">Décembre</option>
						</select>
						
						<select type="text" value="test">
							<?php 
								$subDate = "20";
								for($i=16; $i<50; $i++)
								{
									echo "<option value= '" . $subDate . $i . "'>" . $subDate . $i . "</option>";
								}
							?>							
						</select>
					<button name="SubmitMoy" value="Afficher" class="btn btn-primary" style="height:25px"> Afficher</button>
				</p>
				<table class="table table-bordered table-striped table-hover table-heading">
					<thead style="font-size: 11px;valign:center;">
						<tr>
							<th>Nombre moyen de pannes</th>
							<th>Nombre moyen de réparations</th>
							<th>Délai moyen de prise de commande</th>
						</tr>
					</thead>
					<tbody style="font-size: 12px;text-align:center;">
						<tr>
							<td><span class="badge alert-danger"><?php echo $nbrePanne; ?></span></td>
							<td><span class="badge alert-success"><?php echo $nbreReparation; ?></span></td>
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
					<span>Hors délai<span class="libStatGen"></span></span>
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
							<th>Prises de commande</th>
							<th>Réparations</th>							
							<!--<th>%</th>-->
						</tr>
					</thead>
					<tbody style="font-size: 12px;text-align:center;">
						<tr>
							<td><?php echo $nbreCmdHorsDelai; ?></td>
							<td><span class="badge alert-danger"><?php echo $nbreRepHorsDelai; ?></span></td>
													
						</tr>
						<tr>
							<td><span class="badge "><?php echo number_format((float) StatGlobale::tauxRealisation($nbreCmdHorsDelai, $nbrePanne), 2, '.', ''); ?> %</span></td>
							<td><span class="badge "><?php echo number_format((float) StatGlobale::tauxRealisation($nbreRepHorsDelai, $nbrePanne) , 2, '.', ''); ?> %</span></td>
													
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>