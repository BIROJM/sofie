<?php 
session_start();
require_once(dirname(__FILE__).'/class/statForage.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');

$regionName = 'NATIONALE';
$periode = 'Année en cours';
$localite = 'Toutes';
$ouvrageList = 'Tous';

if(!empty($_GET['date_debut'])) StatForage::$periodeDebut = $_GET['date_debut'];
else StatForage::$periodeDebut = date('Y') .'-01-01';
if(!empty($_GET['date_fin'])) StatForage::$periodeFin = $_GET['date_fin'];
else StatForage::$periodeFin = date('Y') .'-12-31';
if(!empty($_GET['regions'])) 
{
	StatForage::$region = $_GET['regions'];
	$regionName = Stat::getRegionName($_GET['regions']);
}
else StatForage::$region ="";
if(!empty($_GET['localite'])) 
{
	StatForage::$localite = $_GET['localite'];
	$localite = Stat::getlocaliteName(StatForage::$localite);
}
else StatForage::$localite ="";

if(!empty($_GET['id'])) 
{
	StatForage::$id = $_GET['id'];
	$ouvrageList = Stat::getForageCode(StatForage::$id);
}
else StatForage::$id ="";

if(isset($_GET['getForages'])) 
{	
	$forages = StatForage::getForages();
	
	//var_dump($reparateurs);
}

if(!empty($_GET['date_debut']) && (!empty($_GET['date_fin'])))
{
	$periode = 'Du ' . Stat::getDateFormat($_GET['date_debut']) .  ' au ' . Stat::getDateFormat($_GET['date_fin']);
}

$_SESSION['criteria'] = "Période : " . $periode ." - Etendu : " . $regionName . " - Localité : " . $localite . ' - Ouvrage : ' . $ouvrageList;

?>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa"></i>
					<span>Liste des ouvrages <span class="libStatGen">- Critères : [<?php echo $_SESSION['criteria']; ?>]</span></span>
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
//globalV={idRep:1}; 
// Run Datables plugin and create 3 variants of settings
function AllTables(){
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
	var link = 'http://<?php echo $_SESSION['localIp'] . $_SESSION['url_stat']; ?>print/printListForage.php'+ '<?php echo '?date_debut=' . $_GET['date_debut'] . '&date_fin=' . $_GET['date_fin'] . '&localite=' . StatForage::$localite. '&regions=' . StatForage::$region . '&id=' . StatForage::$id; ?>';
	url = typeof url !== 'undefined' ? link : link;
	window.open(url);
}

$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	WinMove();
	/*
	$('tr').dblclick(function(e) {	
		var id = $(this).attr('name');
		var nom = $('td[name='+ id +'_codeOuvrage]').html();
		//alert(nom);
		$(".modal-title").html('Point du forage : ' + nom);
		$(".modal-body").load("graphForage.php?id=" + id);
		$("#basicModal").modal('show');
		//OpenModalBox('test', 'ret');
	});
	*/
});
</script>
<!--
<script src="js/graphReparateur.js"></script>
-->						
					