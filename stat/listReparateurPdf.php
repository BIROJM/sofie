<?php 
session_start();
require_once(dirname(__FILE__).'/class/statReparateur.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');

$regionName = 'Nationale';
$periode = 'Année en cours';

if(!empty($_GET['date_debut'])) StatReparateur::$periodeDebut = $_GET['date_debut'];
else StatReparateur::$periodeDebut = date('Y') .'-01-01';
if(!empty($_GET['date_fin'])) StatReparateur::$periodeFin = $_GET['date_fin'];
else StatReparateur::$periodeFin = date('Y') .'-12-31';

if(!empty($_GET['regions'])) 
{
	StatReparateur::$region = $_GET['regions'];
	$regionName = Stat::getRegionName($_GET['regions']);
}
else StatReparateur::$region ="";
if(!empty($_GET['localite'])) StatReparateur::$localite = $_GET['localite'];
else StatReparateur::$localite ="";
if(!empty($_GET['id'])) StatReparateur::$id = $_GET['id'];
else StatReparateur::$id ="";

if(!empty($_GET['date_debut']) && (!empty($_GET['date_fin'])))
{
	$periode = 'Du ' . Stat::getDateFormat($_GET['date_debut']) .  ' au ' . Stat::getDateFormat($_GET['date_fin']);
}


//if(isset($_GET['getReparateurs'])) 
//{	
	$reparateurs = StatReparateur::getReparateurs();
	
	//var_dump($reparateurs);
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
                <strong>Liste de données</strong> : Etat reparateurs 
            </div>
            <div>
                <strong>Nombre de lignes</strong> : <?php echo count($reparateurs);  ?>
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
							<th style="text-align:center;width:100px">Commandes passées</th>
							<th style="text-align:center;width:100px">Prises de commandes</th>
							<th style="text-align:center;width:100px">Réparations effectuées</th>							
							<th style="text-align:center;width:100px">Efficacité</th>
							<th style="text-align:center;width:100px">Reactivité / Prises de commandes</th>
							<th style="text-align:center;width:100px">Reactivité / Réparation</th>
							<th style="text-align:center;width:100px">Délai moyen de réparation (Jours)</th>
							
						</tr>
					</thead>
					<tbody style="font-size: 12px;text-align:center;">
					<?php 
					$i = 1;
					foreach($reparateurs as $reparateur)
				    {
						$nbrePanne = StatReparateur::nbrePannes($reparateur['IDNumAppel']);						
						$nbrePC = StatReparateur::nbrePriseCommande($reparateur['IDNumAppel']);						
						$nbreReparation = StatReparateur::nbreReparations($reparateur['IDNumAppel']);						
						$efficacite = Stat::efficacite($nbreReparation, $nbrePanne); 
						$nbrePCHD = StatReparateur::nbrePriseCommandeHorsDelai($reparateur['IDNumAppel']);
						$nbreREPHD = StatReparateur::nbreReparationHorsDelai($reparateur['IDNumAppel']);
						$reactivitePC = Stat::reactivite($nbrePanne, $nbrePCHD);
						$reactiviteREP = Stat::reactivite($nbrePanne, $nbreREPHD); 
						$delaiMoyReparation = Stat::FormatTime(StatReparateur::delaiMoyReparation($reparateur['IDNumAppel'])); 
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
						<tr name="<?php echo $reparateur['IDReparateur']; ?>">
							<td><?php echo $i++; ?></td>
							<td  style="text-align:left;" name="<?php echo $reparateur['IDReparateur'] . "_nomPrenoms";?>"><?php echo $reparateur['NomRep'] . ' ' . $reparateur['PrenomsRep']; ?></td>
							<td name="<?php echo $reparateur['IDReparateur'] . "_nbrePanne";?>"><span class="badge"><?php  echo $nbrePanne;?></span></td>
							<td name="<?php echo $reparateur['IDReparateur'] . "_nbrePriseCommande";?>"><span class="badge"><?php echo $nbrePC; ?></span></td>
							<td name="<?php echo $reparateur['IDReparateur'] . "_nbreReparation";?>"><span class="badge"><?php  echo $nbreReparation;?></span></td>
							<td><span class="badge alert-success"><?php echo number_format((float) $efficacite , 2, '.', ''); ?> %</td>
							<td name="<?php echo $reparateur['IDReparateur'] . "_reactivitePC";?>"><span class="badge alert-danger"><?php  echo number_format((float) $reactivitePC , 2, '.', ''); ?> %</span></td>
							<td name="<?php echo $reparateur['IDReparateur'] . "_reactiviteREP";?>"><span class="badge alert-danger"><?php  echo number_format((float) $reactiviteREP , 2, '.', ''); ?> %</span></td>
							<td name="<?php echo $reparateur['IDReparateur'] . "_delaiMoyReparation";?>"><span class="badge"><?php echo $delaiMoyReparation; ?> </span></td>
							
						</tr>
										
						<?php }?>
					</tbody>
					<tfoot>
							<tr>
								<th colspan= "2" class="libStatFooter">Total</th>
								<th class="libStatFooter"><?php echo $cumul_nbrePanne; ?></th>
								<th class="libStatFooter"><?php echo @$cumul_nbrePC; ?></th>
								<th class="libStatFooter"><?php echo @$cumul_nbreReparation; ?></th>
								<th class="libStatFooter"><?php echo number_format(Stat::divide(@$cumul_efficacite,count($reparateurs)), 2, '.', ''); ?> %</th>								
								<th class="libStatFooter"><?php echo number_format(Stat::divide(@$cumul_reactivitePC,count($reparateurs)), 2, '.', ''); ?> %</th>
								<th class="libStatFooter"><?php echo number_format(Stat::divide(@$cumul_reactiviteREP,count($reparateurs)), 2, '.', ''); ?> %</th>
								<th class="libStatFooter"><?php echo number_format(Stat::divide(@$cumul_delaiMoyReparation,count($reparateurs)), 2, '.', ''); ?></th>
								
							</tr>
						</tfoot>
					
</table>
 