<?php 
session_start();
require_once(dirname(__FILE__).'/class/statAgent.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');

if(!empty($_GET['date_debut'])) StatAgent::$periodeDebut = $_GET['date_debut'];
else StatAgent::$periodeDebut = date('Y') .'-01-01';
if(!empty($_GET['date_fin'])) StatAgent::$periodeFin = $_GET['date_fin'];
else StatAgent::$periodeFin = date('Y') .'-12-31';
if(!empty($_GET['regions'])) StatAgent::$region = $_GET['regions'];
else StatAgent::$region ="";
if(!empty($_GET['localite'])) StatAgent::$localite = $_GET['localite'];
else StatAgent::$localite ="";
if(!empty($_GET['id'])) StatAgent::$id = $_GET['id'];
else StatAgent::$id ="";

//if(isset($_GET['getAgents'])) 
//{	
	$agents = StatAgent::getAgentsFormen();	
//}

?>


<pageheader name="PdfHeader" content-left="Minitère de l'Equipement Rural" content-center="" line="on"
            content-right="SOFIE"
            header-style="font-family: serif; font-size: 10pt; font-weight: bold; color: #000000;" />

<pagefooter name="PdfFooter" content-right="{PAGENO}/{nbpg}" line="on"
            footer-style="font-family: serif; font-size: 8pt; font-weight: bold; font-style: italic; color: #000000;" />

<setpageheader name="PdfHeader" value="on" show-this-page="1" />
<setpagefooter name="PdfFooter" value="on" />

<table style="width: 100%">
    <tr>
        <td width="88%">
            <div>
                <strong>Date d'édition</strong> : <?php echo date('d/m/Y H:i:s'); ?>
            </div>
            <div>
                <strong>Liste de données</strong> : Etat Agents formen 
            </div>
            <div>
                <strong>Nombre de lignes</strong> : <?php echo count($agents);  ?>
            </div>
            <div>
                <strong>Critères</strong> :
                <span id="criteres"><?php echo $_SESSION['criteria'];?> </span>
            </div>
        </td>
        <td>
            <img src="../img/armoirie.jpg" style="height: 68px; width: 75px;" />
        </td>
    </tr>
</table>

<table class="pdftable pdftable-striped pdftable-heading pdftable-bordered">
					<thead style="font-size: 11px;valign:center;">
						<tr>
							<th style="text-align:center;width:50px" >#</th>
							<th style="text-align:center;width:200px">Nom et prénom</th>
							<th style="text-align:center;width:100px">Pannes signalées</th>
							<th style="text-align:center;width:100px">Confirmations Prises de commandes</th>
							<th style="text-align:center;width:100px">Réparations</th>
							<th style="text-align:center;width:100px">Efficacité</th>
							<th style="text-align:center;width:100px">Réactivité / Prises Commande</th>
							<th style="text-align:center;width:100px">Réactivité / Réparation</th>
							<th style="text-align:center;width:100px">Délai Moyen de Réparation (jours)</th>														
						</tr>
					</thead>
					<tbody style="font-size: 12px;text-align:center;">
					<?php 
					$i = 1;
					foreach($agents as $agent)
				    {
						
						$nbrePanne = StatAgent::nbrePannes($agent['IDNumAppel']);
						$nbrePC = StatAgent::nbrePriseCommande($agent['IDNumAppel']);
						$nbreReparation = StatAgent::nbreReparations($agent['IDNumAppel']);
						$efficacite = Stat::efficacite($nbreReparation, $nbrePanne); 
						$nbrePCHD = StatAgent::nbrePriseCommandeHorsDelai($agent['IDNumAppel']);
						$nbreREPHD = StatAgent::nbreReparationHorsDelai($agent['IDNumAppel']);
						$reactivitePC = Stat::reactivite($nbrePanne, $nbrePCHD);
						$reactiviteREP = Stat::reactivite($nbrePanne, $nbreREPHD); 
						$delaiMoyReparation = Stat::FormatTime(StatAgent::delaiMoyReparation($agent['IDNumAppel'])); 
						if ($delaiMoyReparation == 0)  $delaiMoyReparation = '';
						
						
						@$cumul_nbrePanne += $nbrePanne;
						@$cumul_nbrePC += $nbrePC;
						@$cumul_nbreReparation += $nbreReparation;
						@$cumul_efficacite += $efficacite;
						@$cumul_nbrePCHD += $nbrePCHD;
						@$cumul_nbreREPHD += $nbreREPHD;
						@$cumul_reactivitePC += $reactivitePC;
						@$cumul_reactiviteREP += $reactiviteREP;
						@$cumul_delaiMoyReparation += $delaiMoyReparation; 
						
					?>
					<tr name="<?php echo $agent['IDAgent']; ?>">
							<td><?php echo $i++; ?></td>
							<td  style="text-align:left;" name="<?php echo $agent['IDAgent'] . "_nomPrenoms";?>"><?php echo $agent['NomAgent'] . ' ' . $agent['PrenomsAgent']; ?></td>
							<td name="<?php echo $agent['IDAgent'] . "_nbrePanne";?>"><span class="badge"><?php  echo $nbrePanne;?></span></td>
							<td name="<?php echo $agent['IDAgent'] . "_nbrePriseCommande";?>"><span class="badge"><?php echo $nbrePC; ?></span></td>
							<td name="<?php echo $agent['IDAgent'] . "_nbreReparation";?>"><span class="badge"><?php  echo $nbreReparation;?></span></td>
							<td><span class="badge alert-success"><?php echo number_format((float) $efficacite , 2, '.', ''); ?> %</td>
							<td name="<?php echo $agent['IDAgent'] . "_reactivitePC";?>"><span class="badge alert-danger"><?php  echo number_format((float) $reactivitePC , 2, '.', ''); ?> %</span></td>
							<td name="<?php echo $agent['IDAgent'] . "_reactiviteREP";?>"><span class="badge alert-danger"><?php  echo number_format((float) $reactiviteREP , 2, '.', ''); ?> %</span></td>
							<td name="<?php echo $agent['IDAgent'] . "_delaiMoyReparation";?>"><span class="badge"><?php echo $delaiMoyReparation; ?> </span></td>
							
						</tr>
										
						<?php }?>
					</tbody>
					
					<tfoot>
							<tr>
								<th colspan= "2" class="libStatFooter">Total</th>
								<th class="libStatFooter"><?php echo @$cumul_nbrePanne; ?></th>
								<th class="libStatFooter"><?php echo @$cumul_nbrePC; ?></th>
								<th class="libStatFooter"><?php echo @$cumul_nbreReparation; ?></th>
								<th class="libStatFooter"><?php echo number_format(Stat::efficacite(@$cumul_efficacite,count($agents)), 2, '.', ''); ?> %</th>								
								<th class="libStatFooter"><?php echo number_format(Stat::efficacite(@$cumul_reactivitePC,count($agents)), 2, '.', ''); ?> %</th>
								<th class="libStatFooter"><?php echo number_format(Stat::efficacite(@$cumul_reactiviteREP,count($agents)), 2, '.', ''); ?> %</th>
								<th class="libStatFooter"><?php echo Stat::efficacite(@$cumul_delaiMoyReparation,count($agents)); ?></th>
								
							</tr>
						</tfoot>
					
					
				</table>
