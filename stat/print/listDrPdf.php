<?php 
session_start();
echo dirname(__FILE__).'/class/statAgent.class.php';

require_once(dirname(__FILE__).'/class/statAgent.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');
echo dirname(__FILE__).'/class/statAgent.class.php';

if(!empty($_GET['date_debut'])) StatAgent::$periodeDebut = $_GET['date_debut'];
else StatAgent::$periodeDebut = '2015-01-01';
if(!empty($_GET['date_fin'])) StatAgent::$periodeFin = $_GET['date_fin'];
else StatAgent::$periodeFin = '2015-12-31';
if(!empty($_GET['regions'])) StatAgent::$region = $_GET['regions'];
else StatAgent::$region ="";
if(!empty($_GET['localite'])) StatAgent::$localite = $_GET['localite'];
else StatAgent::$localite ="";

//if(isset($_GET['getDr'])) 
//{	
	$agents = StatAgent::getDr();	
//}

?>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa"></i>
					<span>Liste des Directeurs Régionaux <span class="libStatGen"></span></span>
				</div>
								
			</div>
			<div class="box-content no-padding">
			<table class="table table-bordered table-striped table-hover table-heading " id="datatable-3">
					<thead style="font-size: 11px;valign:center;">
						<tr>
							<th style="text-align:center;width:50px" >#</th>
							<th style="text-align:center;width:200px">Nom et prénoms</th>
							<th style="text-align:center;width:100px">Région</th>
							<th style="text-align:center;width:100px">Ouvrages gérés</th>
							<th style="text-align:center;width:100px">Sociologues</th>
							<th style="text-align:center;width:100px">Agent formen</th>
							<th style="text-align:center;width:100px">Reparateurs</th>
							<th style="text-align:center;width:100px">Comité eau</th>
							<th style="text-align:center;width:100px">Réparations hors délai</th>	
							
						</tr>
					</thead>
					<tbody style="font-size: 12px;text-align:center;">
					<?php 
					$i = 1;
					foreach($agents as $agent)
				    {
					?>
					<tr name="<?php echo $agent['IDAgent']; ?>">
							<td><?php echo $i++ ?></td>
							<td style="text-align:left;" name="<?php echo $agent['IDAgent'] . "_nomPrenoms";?>"><?php echo $agent['NomAgent'] . ' ' . $agent['PrenomsAgent']; ?></td>
							<td name="<?php echo $agent['IDAgent'] . "_region";?>"><?php echo $agent['NomRegion']; ?></td>
							
							<td name="<?php echo $agent['IDAgent'] . "_ouvrage";?>"><span class="badge alert-success"><?php StatForage::$region = $agent['IDRegion']; echo count(StatForage::getForages()); ?></span></td>
							<td name="<?php echo $agent['IDAgent'] . "_sociologue";?>"><span class="badge"><?php StatAgent::$region = $agent['IDRegion']; echo count(StatAgent::getSociologues()); ?></span></td>
							<td name="<?php echo $agent['IDAgent'] . "_agentFormen";?>"><span class="badge"><?php StatAgent::$region = $agent['IDRegion']; echo count(StatAgent::getAgentsFormen()); ?></span></td>
														
							<td name="<?php echo $agent['IDAgent'] . "_reparateur";?>"><span class="badge"><?php StatReparateur::$region = $agent['IDRegion']; echo count(StatReparateur::getReparateurs());?></span></td>
							<td name="<?php echo $agent['IDAgent'] . "_comite";?>"><span class="badge"><?php  StatAgent::$region = $agent['IDRegion']; echo StatAgent::nbreComiteEau();?></span></td>
							<td name="<?php echo $agent['IDAgent'] . "_nbrePriseCommandeHorsDelai";?>"><span class="badge alert-danger"><?php echo (int)StatAgent::nbrePriseCommandeHorsDelaiRegion($agent['IDNumAppel']); ?></span></td>
							
						</tr>
										
						<?php }?>
					</tbody>
					
				</table>
			</div>
		</div>
	</div>
</div>
					