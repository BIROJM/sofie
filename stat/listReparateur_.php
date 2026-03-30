<?php 
session_start();
require_once(dirname(__FILE__).'/class/statReparateur.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');

if(!empty($_GET['date_debut'])) StatReparateur::$periodeDebut = $_GET['date_debut'];
else StatReparateur::$periodeDebut = '2015-01-01';
if(!empty($_GET['date_fin'])) StatReparateur::$periodeFin = $_GET['date_fin'];
else StatReparateur::$periodeFin = '2015-12-31';
if(!empty($_GET['regions'])) StatReparateur::$region = $_GET['regions'];
else StatReparateur::$region ="";
if(!empty($_GET['localite'])) StatReparateur::$localite = $_GET['localite'];
else StatReparateur::$localite ="";
if(!empty($_GET['id'])) StatReparateur::$id = $_GET['id'];
else StatReparateur::$id ="";


if(isset($_GET['getReparateurs'])) 
{	
	$reparateurs = StatReparateur::getReparateurs();
	
	//var_dump($reparateurs);
}

?>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa"></i>
					<span>Liste détaillée des reparateurs <span class="libStatGen"></span></span>
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
							<th style="text-align:center;width:100px">Réparations effectuées</th>
							<th style="text-align:center;width:100px">Nombre moyen de réparation</th>
							<th style="text-align:center;width:100px">Taux de réalisation</th>
							<th style="text-align:center;width:100px">Délai moyen de réparation (Jours)</th>
							<th style="text-align:center;width:100px">Transfert de charge pour absence</th>
							<th style="text-align:center;width:100px">Moyenne de transfert de charge pour absence</th>
							<th style="text-align:center;width:100px">Prise de commande</th>
							<th style="text-align:center;width:100px">Prise de commande hors délais</th>
							<th style="text-align:center;width:100px">Réparation hors délais</th>
							<!--<th style="text-align:center;width:100px">Avis de réparation reçus</th> -->
							<th style="text-align:center;width:100px">Palmares</th>
							
						</tr>
					</thead>
					<tbody style="font-size: 12px;text-align:center;">
					<?php 
					$i = 1;
					foreach($reparateurs as $reparateur)
				    {
					?>
						<tr name="<?php echo $reparateur['IDReparateur']; ?>">
							<td><?php echo $i++; ?></td>
							<td  style="text-align:left;" name="<?php echo $reparateur['IDReparateur'] . "_nomPrenoms";?>"><?php echo $reparateur['NomRep'] . ' ' . $reparateur['PrenomsRep']; ?></td>
							<td name="<?php echo $reparateur['IDReparateur'] . "_nbreReparation";?>"><span class="badge"><?php $nbreReparation = StatReparateur::nbreReparation($reparateur['IDNumAppel']); echo $nbreReparation;?></span></td>
							<td><span class="badge"><?php  $nbreMoyReparation = $nbreReparation / Stat::dateRange(StatReparateur::$periodeDebut, StatReparateur::$periodeFin);echo number_format((float) $nbreMoyReparation , 3, '.', ''); ?> </td>
							<td name="<?php echo $reparateur['IDReparateur'] . "_tauxRealisation";?>"><span class="badge alert-danger"><?php $tauxRealisation = StatReparateur::tauxRealisation($nbreReparation, StatReparateur::nbrePannes($reparateur['IDReparateur'])); echo number_format((float) $tauxRealisation , 2, '.', ''); ?>%</span></td>
							<td name="<?php echo $reparateur['IDReparateur'] . "_delaiMoyReparation";?>"><span class="badge"><?php $delaiMoyReparation = Stat::FormatTime(StatReparateur::delaiMoyReparation($reparateur['IDNumAppel'])); if ($delaiMoyReparation > 0) echo $delaiMoyReparation; else echo '';?></span></td>
							<td name="<?php echo $reparateur['IDReparateur'] . "_nbreTransfertAbsence";?>"><span class="badge"><?php $nbreTransfertAbsence = StatReparateur::nbreTransfertAbsence($reparateur['IDNumAppel']); echo $nbreTransfertAbsence;?></span></td>
							<td><span class="badge"><?php  $nbreMoyTransfertAbsence = $nbreTransfertAbsence / Stat::dateRange(StatReparateur::$periodeDebut, StatReparateur::$periodeFin); echo number_format((float) $nbreMoyTransfertAbsence , 3, '.', ''); ?> </span></td>
							<td name="<?php echo $reparateur['IDReparateur'] . "_nbrePriseCommande";?>"><span class="badge"><?php echo StatReparateur::nbrePriseCommande($reparateur['IDNumAppel']); ?></span></td>
							<td name="<?php echo $reparateur['IDReparateur'] . "_nbrePriseCommandeHorsDelai";?>"><span class="badge"><?php echo StatReparateur::nbrePriseCommandeHorsDelai($reparateur['IDNumAppel']); ?></span></td>
							<td name="<?php echo $reparateur['IDReparateur'] . "_nbreReparationHorsDelai";?>"><span class="badge"><?php echo StatReparateur::nbreReparationHorsDelai($reparateur['IDNumAppel']); ?></span></td>
							<!--<td name="<?php //echo $reparateur['IDReparateur'] . "_nbreAvisReparation";?>"><span class="badge"><?php //echo StatReparateur::nbreAvisReparation($reparateur['IDNumAppel']); ?></span></td> -->
							<td name="<?php echo $reparateur['IDReparateur'] . "_palmares";?>"><span class="badge alert-danger"><?php echo StatReparateur::palmares($tauxRealisation,$delaiMoyReparation); ?></span></td>
							
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
	TestTable3();
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
});
</script>
<!--
<script src="js/graphReparateur.js"></script>
-->						
					