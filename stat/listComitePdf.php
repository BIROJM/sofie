<?php 
session_start();
require_once(dirname(__FILE__).'/class/statComite.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');

$regionName = 'Nationale';
$periode = 'Année en cours';

if(!empty($_GET['date_debut'])) statComite::$periodeDebut = $_GET['date_debut'];
else statComite::$periodeDebut = date('Y') .'-01-01';
if(!empty($_GET['date_fin'])) statComite::$periodeFin = $_GET['date_fin'];
else statComite::$periodeFin =  date('Y') .'-12-31';

if(!empty($_GET['regions'])) 
{
	statComite::$region = $_GET['regions'];
	$regionName = Stat::getRegionName($_GET['regions']);
}
else statComite::$region ="";
if(!empty($_GET['localite'])) statComite::$localite = $_GET['localite'];
else statComite::$localite ="";

if(!empty($_GET['id'])) statComite::$id = $_GET['id'];
else statComite::$id ="";
//if(isset($_GET['getComites'])) 
//{	
$comites = statComite::getComites();
	
	//var_dump($Comites);
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
                <strong>Liste de données</strong> : Etat comité eau
            </div>
            <div>
                <strong>Nombre de lignes</strong> : <?php echo count($comites);  ?>
            </div>
            <div>
                <strong>Critères</strong> :
                 <span id="criteres"><?php echo $_SESSION['criteria'];?></span>
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
							<th style="text-align:center;width:200px">Nom et prénoms du sécretaire</th>
							<th style="text-align:center;width:150px">Localité</th>
							<th style="text-align:center;width:100px">Code Ouvrage tributaire</th>
							<th style="text-align:center;width:100px">Pannes déclarées</th>
							<th style="text-align:center;width:100px">Réparations signalées</th>
							<!--<th style="text-align:center;width:100px">Avis de prise en compte de panne</th> -->								
						</tr>
					</thead>
					<tbody style="font-size: 12px;text-align:center;">
					<?php 
					$i = 1;
					foreach($comites as $comite)
				    {
					?>
					<tr name="<?php echo $comite['IDComite']; ?>">
							<td><?php echo $i++; ?></td>
							<td style="text-align:left;" name="<?php echo $comite['IDComite'] . "_nomPrenoms";?>"><?php echo $comite['NomSecretaire'] . ' ' . $comite['PrenomsSecretaire']; ?></td>
							<td name="<?php echo $comite['IDComite'] . "_nomLocalite";?>"><span><?php echo $comite['NomLocalite']; ?></span></td>
							<td name="<?php echo $comite['IDComite'] . "_ouvrage";?>"><span class="badge alert-primary"><?php echo $comite['CodeOuvrage']; ?></span></td>
							<td name="<?php echo $comite['IDComite'] . "_panne";?>"><span class="badge alert-danger"><?php echo StatComite::nbrePannes($comite['IDNumAppel']); ?></span></td>
							<td name="<?php echo $comite['IDComite'] . "_nbreReparation";?>"><span class="badge alert-success"><?php echo StatComite::nbreReparation($comite['IDNumAppel']); ?></span></td>
							<!--<td name="<?php //echo $comite['IDComite'] . "_nbreAvisPanne";?>"><span class="badge alert-warning"><?php //echo StatComite::nbreAvisPriseEnComptePanne($comite['IDNumAppel']); ?></span></td> -->
							
						</tr>
										
						<?php }?>
					</tbody>
					
					<tfoot>
							<tr>
								<th colspan= "4" class="libStatFooter">Total</th>
								<th class="libStatFooter"><?php echo @$cumul_nbrePanne; ?></th>
								<th class="libStatFooter"><?php echo @$cumul_nbreReparation; ?></th>
								<th class="libStatFooter"><?php echo Stat::divide((float) @$cumul_efficacite,count($comites)); ?> %</th>	
								<th class="libStatFooter"><?php echo Stat::divide((float) @$cumul_delaiMoyReparation,count($comites)); ?></th>
								
							</tr>
						</tfoot>
					
</table>
