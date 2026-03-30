<?php 
session_start();
require_once(dirname(__FILE__).'/class/statAgent.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');
require_once(dirname(__FILE__).'/class/statForage.class.php');
require_once(dirname(__FILE__).'/class/statReparateur.class.php');

if(!empty($_GET['date_debut'])) StatAgent::$periodeDebut = $_GET['date_debut'];
else StatAgent::$periodeDebut = date('Y') .'-01-01';
if(!empty($_GET['date_fin'])) StatAgent::$periodeFin = $_GET['date_fin'];
else StatAgent::$periodeFin = date('Y') .'-12-31';
if(!empty($_GET['regions'])) StatAgent::$region = $_GET['regions'];
else StatAgent::$region ="";
if(!empty($_GET['id'])) 
{
	StatAgent::$id = $_GET['id'];
}
else StatAgent::$id ="";


//if(isset($_GET['getDr'])) 
//{	
	$agents = StatAgent::getDr();	
//}


//$agents = $_SESSION['DR']['agents']);

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
                <strong>Date</strong> : <?php echo date('d/m/Y H:i:s'); ?>
            </div>
            <div>
                <strong>Liste de données</strong> : Etat des Directeurs régionaux 
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
							<th style="text-align:center;width:100px">Ouvrages gérés</th>
							<th style="text-align:center;width:100px">Pannes Signalées</th>
							<th style="text-align:center;width:100px">Réparations effectuées</th>
							<th style="text-align:center;width:100px">Efficacité</th>							
						</tr>
					</thead>
					<tbody style="font-size: 12px;text-align:center;">
					<?php 
					$i = 1;
					$cumul_nbrePanne = 0;
					$cumul_nbreReparation = 0;
					$cumul_efficacite = 0;
					$cumul_ouvrage = 0;

					foreach($agents as $agent)
				    {
						$nbrePanne = StatAgent::nbrePannesRegion($agent['IDRegion']);
						$cumul_nbrePanne += $nbrePanne;
						$nbreReparation = StatAgent::nbreReparationsRegion($agent['IDRegion']);
						$cumul_nbreReparation += $nbreReparation;
						$efficacite = Stat::efficacite($nbreReparation, $nbrePanne); 
						$cumul_efficacite += $efficacite;
						StatForage::$region = $agent['IDRegion'];
						$nbOuvrages =  count(StatForage::getForages()); 
						$cumul_ouvrage += $nbOuvrages;
					?>
					<tr name="<?php echo $agent['IDAgent']; ?>">
							<td><?php echo $i++ ?></td>
							<td style="text-align:left;" name="<?php echo $agent['IDAgent'] . "_nomPrenoms";?>"><?php echo $agent['NomAgent'] . ' ' . $agent['PrenomsAgent']; ?></td>
							<td name="<?php echo $agent['IDAgent'] . "_region";?>"><?php echo $agent['NomRegion']; ?></td>
							<td name="<?php echo $agent['IDAgent'] . "_ouvrage";?>"><span class="badge alert-success"><?php echo $nbOuvrages; ?></span></td>
							<td name="<?php echo $agent['IDAgent'] . "_nbrePanne";?>"><span class="badge"><?php echo $nbrePanne;?></span></td>
							<td name="<?php echo $agent['IDAgent'] . "_nbreReparation";?>"><span class="badge"><?php echo $nbreReparation; ?></span></td>
							<td name="<?php echo $agent['IDAgent'] . "_efficacite";?>"><span class="badge alert-success"><?php echo $efficacite; ?> %</span></td>
							
						</tr>
										
						<?php }?>
											
					</tbody>
					
					<tfoot>
							<tr>
								<th colspan= "3" class="libStatFooter">Total</th>
								<th class="libStatFooter"><?php echo $cumul_ouvrage; ?></th>
								<th class="libStatFooter"><?php echo $cumul_nbrePanne; ?></th>
								<th class="libStatFooter"><?php echo $cumul_nbreReparation; ?></th>
								<th class="libStatFooter"><?php echo Stat::divide($cumul_efficacite, count($agents)); ?> %</th>
							</tr>
						</tfoot>
					
					
</table>
				