<?php 
session_start();
ini_set("max_execution_time", 0);
require_once(dirname(__FILE__).'/class/statForage.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');

if(!empty($_GET['date_debut'])) StatForage::$periodeDebut = $_GET['date_debut'];
else StatForage::$periodeDebut = date('Y') .'-01-01';
if(!empty($_GET['date_fin'])) StatForage::$periodeFin = $_GET['date_fin'];
else StatForage::$periodeFin = date('Y') .'-12-31';
if(!empty($_GET['regions']))
{ 
	StatForage::$region = $_GET['regions'];
//	$regionName = Stat::getRegionName($_GET['regions']);
}
else StatForage::$region ="";
if(!empty($_GET['localite'])) StatForage::$localite = $_GET['localite'];
else StatForage::$localite ="";

if(!empty($_GET['id'])) StatForage::$id = $_GET['id'];
else StatForage::$id ="";

//if(isset($_GET['getForages'])) 
//{	
	$forages = StatForage::getForages();
	
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
                <strong>Liste de données</strong> : Etat forage
            </div>
            <div>
                <strong>Nombre de lignes</strong> : <?php echo count($forages);  ?>
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
							<th style="text-align:center;width:80px">Code Ouvrage</th>
							<th style="text-align:center;width:100px">Pannes Déclarées</th>
							<th style="text-align:center;width:100px">Réparations effectuées</th>
							<th style="text-align:center;width:100px">Efficacité</th>
							<th style="text-align:center;width:100px">Nb Hors délai Prise CDE</th>							
							<th style="text-align:center;width:100px">Nb Hors délai Réparation</th>
							<th style="text-align:center;width:100px">Nb Collectes</th>
							<th style="text-align:center;width:100px">Délai moyen de reparation (Jours)</th>
							
						</tr>
					</thead>
					<tbody style="font-size: 12px;text-align:center;">
					<?php 
					$i = 1;
					foreach($forages as $forage)
				    {
						$nbrePanne = StatForage::nbrePannes($forage['IDOuvrage']);
						$nbreReparation = StatForage::nbreReparations($forage['IDOuvrage']);
						$efficacite = number_format((float) Stat::efficacite($nbreReparation, $nbrePanne), 2, '.', ''); 
						$nbCollecte = StatForage::nbreCollectes($forage['IDOuvrage']);
						$nbrePCHD = StatForage::nbrePriseCommandeHorsDelai($forage['IDOuvrage']);
						$nbreREPHD = StatForage::nbreReparationHorsDelai($forage['IDOuvrage']);
						$delaiMoyReparation = Stat::FormatTime(StatForage::delaiMoyReparation($forage['IDOuvrage'])); 
						if ($delaiMoyReparation == 0)  $delaiMoyReparation = '';
						
						@$cumul_nbrePanne += $nbrePanne;
						@$cumul_nbreReparation += $nbreReparation;
						@$cumul_efficacite += $efficacite;
						@$cumul_nbrePCHD += $nbrePCHD;
						@$cumul_nbreREPHD += $nbreREPHD;
						@$cumul_nbCollecte += $nbCollecte;						
						@$cumul_delaiMoyReparation += $delaiMoyReparation; 
						
					?>
					<tr name="<?php echo $forage['IDOuvrage']; ?>">
							<td><?php echo $i++; ?></td>
							<td name="<?php echo $forage['IDOuvrage'] . "_codeOuvrage";?>"><?php echo $forage['CodeOuvrage']; ?></td>
							<td name="<?php echo $forage['IDOuvrage'] . "_nbrePanne";?>"><span class="badge"><?php echo $nbrePanne;?></span></td>
							<td name="<?php echo $forage['IDOuvrage'] . "_nbreReparation";?>"><span class="badge"><?php echo $nbreReparation;?></span></td>
							<td name="<?php echo $forage['IDOuvrage'] . "_efficacite";?>"><span class="badge"><?php echo $efficacite;?> %</span></td>
							<td name="<?php echo $forage['IDOuvrage'] . "_nbrePCHD";?>"><span class="badge"><?php echo $nbrePCHD;?></span></td>
							<td name="<?php echo $forage['IDOuvrage'] . "_nbreREPHD";?>"><span class="badge"><?php echo $nbreREPHD;?></span></td>
							<td name="<?php echo $forage['IDOuvrage'] . "_nbreREPHD";?>"><span class="badge"><?php echo $nbCollecte;?></span></td>
							<td name="<?php echo $forage['IDOuvrage'] . "_delaiMoyReparation";?>"><span class="badge"><?php echo $delaiMoyReparation;?></span></td>
							
						</tr>
										
						<?php }?>
					</tbody>
					
					<tfoot>
							<tr>
								<th colspan= "2" class="libStatFooter">Total</th>
								<th class="libStatFooter"><?php echo @$cumul_nbrePanne; ?></th>
								<th class="libStatFooter"><?php echo @$cumul_nbreReparation; ?></th>
								<th class="libStatFooter"><?php echo number_format(Stat::divide(@$cumul_efficacite, count($forages)), 2, '.', ''); ?> %</th>	
								<th class="libStatFooter"><?php echo @$cumul_nbrePCHD; ?></th>
								<th class="libStatFooter"><?php echo @$cumul_nbreREPHD; ?></th>
								<th class="libStatFooter"><?php echo @$cumul_nbCollecte; ?> </th>
								<th class="libStatFooter"><?php echo number_format(Stat::divide(@$cumul_delaiMoyReparation, count($forages)), 2, '.', ''); ?></th>
								
							</tr>
						</tfoot>
					
</table>
