<?php 
session_start();
require_once(dirname(__FILE__).'/class/statAgent.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');

if(!empty($_GET['date_debut'])) StatAgent::$periodeDebut = $_GET['date_debut'];
else StatAgent::$periodeDebut = date('Y') .'-01-01';
if(!empty($_GET['date_fin'])) StatAgent::$periodeFin = $_GET['date_fin'];
else StatAgent::$periodeFin = date('Y') .'-12-31';
if(!empty($_GET['regions'])) 
{
	StatAgent::$region = $_GET['regions'];
	$regionName = Stat::getRegionName($_GET['regions']);
}

if(!empty($_GET['id'])) 
{
	StatAgent::$id = $_GET['id'];
}
else StatAgent::$id ="";


//if(isset($_GET['getSociologues'])) 
//{	
	$agents = StatAgent::getSociologues();	
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
                <strong>Liste de données</strong> : Etat Sociologues
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
							<th style="text-align:center;width:200px">Nom et prénoms</th>
							<th style="text-align:center;width:100px">Région</th>
							<th style="text-align:center;width:100px">Créations d'ouvrage validés</th>
							<th style="text-align:center;width:100px">Créations d'ouvrage non validés</th>
							<th style="text-align:center;width:100px">Suivis d'ouvrage validés</th>
							<th style="text-align:center;width:100px">Suivis d'ouvrage non validés</th>
							<th style="text-align:center;width:100px">Prise de commande</th>
							<th style="text-align:center;width:100px">Prise de commande hors délai</th>	
							<th style="text-align:center;width:100px">Taux de réalisation</th>							
						</tr>
					</thead>
					<tbody style="font-size: 12px;text-align:center;">
					<?php 
					$i = 1;
					foreach($agents as $agent)
				    {
					?>
					<tr name="<?php echo $agent['IDAgent']; ?>">
							<td><?php echo $i++; ?></td>
							<td style="text-align:left;" name="<?php echo $agent['IDAgent'] . "_nomPrenoms";?>"><?php echo $agent['NomAgent'] . ' ' . $agent['PrenomsAgent']; ?></td>
							<td name="<?php echo $agent['IDAgent'] . "_region";?>"><?php echo $agent['NomRegion']; ?></td>
							<td name="<?php echo $agent['IDAgent'] . "_nbreCreationValide";?>"><span class="badge"><?php $nbreCreationValide =  StatAgent::creationValide($agent['IDAgent']); echo $nbreCreationValide;?></span></td>
							<td name="<?php echo $agent['IDAgent'] . "_nbreCreationNonValide";?>"><span class="badge"><?php $nbreCreationNonValide =  StatAgent::creationNonValide($agent['IDRegion']); echo $nbreCreationNonValide;?></span></td>
							<td name="<?php echo $agent['IDAgent'] . "_nbreSuiviValide";?>"><span class="badge"><?php $nbreSuiviValide = StatAgent::suiviValide($agent['IDAgent']); echo $nbreSuiviValide; ?></span></td>
							<td name="<?php echo $agent['IDAgent'] . "_nbreSuiviNonValide";?>"><span class="badge"><?php $nbreSuiviNonValide = StatAgent::suiviNonValide($agent['IDRegion']); echo $nbreSuiviNonValide; ?></span></td>
								<td name="<?php echo $agent['IDAgent'] . "_nbrePriseCommande";?>"><span class="badge"><?php echo (int)StatAgent::nbrePriseCommande($agent['IDNumAppel']);?></span></td>
							<td name="<?php echo $agent['IDAgent'] . "_nbrePriseCommandeHorsDelai";?>"><span class="badge"><?php echo (int)StatAgent::nbrePriseCommandeHorsDelaiRegion($agent['IDNumAppel']); ?></span></td>
							<td name="<?php echo $agent['IDAgent'] . "_tauxRealisation";?>"><span class="badge alert-danger"><?php $tauxRealisation = StatAgent::tauxRealisationSociologue($nbreCreationValide, $nbreCreationNonValide, $nbreSuiviValide, $nbreSuiviNonValide); echo number_format((float) $tauxRealisation , 2, '.', ''); ?>%</span></td>
						
							
						</tr>
										
						<?php }?>
					</tbody>
					
</table>
