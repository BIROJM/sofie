<?php 
session_start();
require_once(dirname(__FILE__).'/class/statAgent.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');

if(!empty($_GET['date_debut'])) StatAgent::$periodeDebut = $_GET['date_debut'];
else StatAgent::$periodeDebut = '2000-01-01';
if(!empty($_GET['date_fin'])) StatAgent::$periodeFin = $_GET['date_fin'];
else StatAgent::$periodeFin = '2030-01-01';
if(!empty($_GET['regions'])) StatAgent::$region = $_GET['regions'];
else StatAgent::$region ="";
if(!empty($_GET['localite'])) StatAgent::$localite = $_GET['localite'];
else StatAgent::$localite ="";

if(isset($_GET['getSociologues'])) 
{	
	$agents = StatAgent::getSociologues();	
}

?>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa"></i>
					<span>Liste des sociologues <span class="libStatGen"></span></span>
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
			<table class="table table-bordered table-striped table-hover table-heading " id="datatable-1">
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
							<td name="<?php echo $agent['IDAgent'] . "_nomPrenoms";?>"><?php echo $agent['NomAgent'] . ' ' . $agent['PrenomsAgent']; ?></td>
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
	TestTable1();
	LoadSelect2Script(MakeSelect2);
}

function MakeSelect2(){
	$('select').select2();
	$('.dataTables_filter').each(function(){
		$(this).find('label input[type=text]').attr('placeholder', 'Rechercher');
	});
}

$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	WinMove();
	$('tr').dblclick(function(e) {	
		var id = $(this).attr('name');
		var nom = $('td[name='+ id +'_nomPrenoms]').html();
		//alert(nom);
		$(".modal-title").html('Rendement du sociologue : ' + nom);
		$(".modal-body").load("graphSociologue.php?id=" + id);
		$("#basicModal").modal('show');
	});
});
</script>
<!--
<script src="js/graphReparateur.js"></script>
-->						
					