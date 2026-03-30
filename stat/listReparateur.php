<?php 
session_start();
require_once(dirname(__FILE__).'/class/statReparateur.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');

$regionName = 'NATIONALE';
$periode = 'Année en cours';
$localite = 'Toutes';
$reparateurList = 'Tous';

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
if(!empty($_GET['localite'])) 
{
	StatReparateur::$localite = $_GET['localite'];
	$localite = Stat::getlocaliteName(StatReparateur::$localite);
}
else StatReparateur::$localite ="";
if(!empty($_GET['id'])) 
{
	StatReparateur::$id = $_GET['id'];
	$reparateurList = Stat::getReparateurName(StatReparateur::$id);
}
else StatReparateur::$id ="";


if(!empty($_GET['date_debut']) && (!empty($_GET['date_fin'])))
{
	$periode = 'Du ' . Stat::getDateFormat($_GET['date_debut']) .  ' au ' . Stat::getDateFormat($_GET['date_fin']);
}

if(isset($_GET['getReparateurs'])) 
{	
	$reparateurs = StatReparateur::getReparateurs();
	
	//var_dump($reparateurs);
}
$_SESSION['criteria'] = "Période : " . $periode ." - Etendu : " . $regionName . " - Localité : " . $localite . ' - Reparateur : ' . $reparateurList;
?>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa"></i>
					<span>Liste détaillée des reparateurs <span class="libStatGen"> - Critères : [<?php echo $_SESSION['criteria']; ?>]</span></span>
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
			<div class="box-content no-padding">
			<table class="table table-bordered table-striped table-hover table-heading " id="datatable-3">
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
			</div>
		</div>
	</div>
</div>

 
<!-- Modal HTML -->
<div id="basicModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Graphique</h4>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
//window.idRep = 1;
globalV={idRep:1}; 
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	//TestTable1();
	TestTable3();
	LoadSelect2Script(MakeSelect2);
}

function MakeSelect2(){
	$('select').select2();
	$('.dataTables_filter').each(function(){
		$(this).find('label input[type=text]').attr('placeholder', 'Rechercher');
	});
}

function LoadPdf(url)
{
	var link = 'http://<?php echo $_SESSION['localIp'] . $_SESSION['url_stat']; ?>print/printListReparateur.php'+ '<?php echo '?date_debut=' . $_GET['date_debut'] . '&date_fin=' . $_GET['date_fin'] . '&localite=' . StatReparateur::$localite. '&regions=' . StatReparateur::$region . '&id=' . StatReparateur::$id; ?>';
	
	//alert(link);
	url = typeof url !== 'undefined' ? link : link;
	window.open(url);
}

$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	WinMove
	/*
	$('tr').dblclick(function(e) {	
		var id = $(this).attr('name');
		//top.idRep = $(this).attr('name');
		//setId($(this).attr('name'));
		//globalV={idRep:$(this).attr('name')}
		var nom = $('td[name='+ id +'_nomPrenoms]').html();
		//alert(nom);
		$(".modal-title").html('Rendement du réparateur : ' + nom);
		$(".modal-body").load("graphReparateur.php?id=" + id);
		$("#basicModal").modal('show');
		//OpenModalBox('test', 'ret');
	});
	*/
});
</script>
<!--
<script src="js/graphReparateur.js"></script>
-->						
					