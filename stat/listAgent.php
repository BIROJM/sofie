<?php 
session_start();
require_once(dirname(__FILE__).'/class/statAgent.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');

$regionName = 'NATIONALE';
$periode = 'Année en cours';
$localite = 'Toutes';
$agentList = 'Tous';

if(!empty($_GET['date_debut'])) StatAgent::$periodeDebut = $_GET['date_debut'];
else StatAgent::$periodeDebut = date('Y') .'-01-01';
if(!empty($_GET['date_fin'])) StatAgent::$periodeFin = $_GET['date_fin'];
else StatAgent::$periodeFin = date('Y') .'-12-31';
if(!empty($_GET['regions'])) 
{
	StatAgent::$region = $_GET['regions'];
	$regionName = Stat::getRegionName($_GET['regions']);
}
else StatAgent::$region ="";
if(!empty($_GET['localite'])) 
{	
	StatAgent::$localite = $_GET['localite'];
	$localite = Stat::getlocaliteName($_GET['localite']);
}
else StatAgent::$localite ="";
if(!empty($_GET['id']))
{
	StatAgent::$id = $_GET['id'];
	$agentList = Stat::getAgentName($_GET['id']);
}
else StatAgent::$id ="";

if(isset($_GET['getAgents'])) 
{	
	$agents = StatAgent::getAgentsFormen();	
}

if(!empty($_GET['date_debut']) && (!empty($_GET['date_fin'])))
{
	$periode = 'Du ' . Stat::getDateFormat($_GET['date_debut']) .  ' au ' . Stat::getDateFormat($_GET['date_fin']);
}

$_SESSION['criteria'] = "Période : " . $periode ." - Etendu : " . $regionName . " - Localité : " . $localite . ' - Agent Formen : ' . $agentList;

?>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa"></i>
					<span>Liste détaillée des agents <span class="libStatGen"> - Critères : [<?php echo $_SESSION['criteria']; ?>]</span></span>
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
								<th class="libStatFooter"><?php echo number_format(Stat::divide(@$cumul_efficacite,count($agents)), 2, '.', ''); ?> %</th>								
								<th class="libStatFooter"><?php echo number_format(Stat::divide(@$cumul_reactivitePC,count($agents)), 2, '.', ''); ?> %</th>
								<th class="libStatFooter"><?php echo number_format(Stat::divide(@$cumul_reactiviteREP,count($agents)), 2, '.', ''); ?> %</th>
								<th class="libStatFooter"><?php echo Stat::divide(@$cumul_delaiMoyReparation,count($agents)); ?></th>
								
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
	var link = 'http://<?php echo $_SESSION['localIp'] . $_SESSION['url_stat']; ?>print/printListAgent.php'+ '<?php echo '?date_debut=' . $_GET['date_debut'] . '&date_fin=' . $_GET['date_fin'] . '&localite=' . StatAgent::$localite. '&regions=' . StatAgent::$region . '&id=' . StatAgent::$id; ?>';
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
		var nom = $('td[name='+ id +'_nomPrenoms]').html();
		//alert(nom);
		$(".modal-title").html('Rendement de l\'agent Formen : ' + nom);
		$(".modal-body").load("graphAgent.php?id=" + id);
		$("#basicModal").modal('show');
	});
	*/
});
</script>
<!--
<script src="js/graphReparateur.js"></script>
-->						
					