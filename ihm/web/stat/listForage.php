<?php 
session_start();
require_once(dirname(__FILE__).'/class/statForage.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');

if(!empty($_GET['date_debut'])) StatForage::$periodeDebut = $_GET['date_debut'];
else StatForage::$periodeDebut = '2000-01-01';
if(!empty($_GET['date_fin'])) StatForage::$periodeFin = $_GET['date_fin'];
else StatForage::$periodeFin = '2030-01-01';
if(!empty($_GET['regions'])) StatForage::$region = $_GET['regions'];
else StatForage::$region ="";
if(!empty($_GET['localite'])) StatForage::$localite = $_GET['localite'];
else StatForage::$localite ="";

if(isset($_GET['getForages'])) 
{	
	$forages = StatForage::getForages();
	
	//var_dump($reparateurs);
}

?>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa"></i>
					<span>Liste des ouvrages <span class="libStatGen"></span></span>
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
							<th style="text-align:center;width:80px">Code Ouvrage</th>
							<th style="text-align:center;width:100px">Nombre de pannes</th>
							<th style="text-align:center;width:100px">Nombre moyen de pannes</th>
							<th style="text-align:center;width:100px">Nombre de réparation </th>
							<th style="text-align:center;width:100px">Nombre moyen de réparation</th>
							<th style="text-align:center;width:100px">Taux de réalisation</th>
							<th style="text-align:center;width:100px">Délai moyen de réparation</th>
							<th style="text-align:center;width:100px">Délai moyen d’indisponibilité</th>
						</tr>
					</thead>
					<tbody style="font-size: 12px;text-align:center;">
					<?php 
					foreach($forages as $forage)
				    {
					?>
					<tr name="<?php echo $forage['IDOuvrage']; ?>">
							<td><?php echo $forage['IDOuvrage']; ?></td>
							<td name="<?php echo $forage['IDOuvrage'] . "_codeOuvrage";?>"><?php echo $forage['CodeOuvrage']; ?></td>
							<td name="<?php echo $forage['IDOuvrage'] . "_nbrePanne";?>"><span class="badge"><?php $nbrePanne = StatForage::nbrePanne($forage['IDOuvrage']); echo $nbrePanne;?></span></td>
							<td name="<?php echo $forage['IDOuvrage'] . "_nbreMoyenPanne";?>"><span class="badge"><?php echo number_format((StatForage::nbreMoyenPanne($forage['IDOuvrage'])), 2,'.', ''); ?></span></td>
							<td name="<?php echo $forage['IDOuvrage'] . "_nbreReparation";?>"><span class="badge"><?php $nbreReparation = StatForage::nbreReparation($forage['IDOuvrage']); echo $nbreReparation;?></span></td>
							<td name="<?php echo $forage['IDOuvrage'] . "_nbreMoyReparation";?>"><span class="badge"><?php $nbreMoyReparation =  number_format(StatForage::nbreMoyenReparation($forage['IDOuvrage']) , 2,'.', ''); echo $nbreMoyReparation;?></span></td>
							<td name="<?php echo $forage['IDOuvrage'] . "_tauxRealisation";?>"><span class="badge alert-danger"><?php $tauxRealisation = StatForage::tauxRealisation($nbreReparation, $nbrePanne); echo number_format((float) $tauxRealisation , 2, '.', ''); ?>%</span></td>
							<td name="<?php echo $forage['IDOuvrage'] . "_delaiMoyReparation";?>"><span class="badge"><?php $delaiMoyReparation = Stat::FormatTime(StatForage::delaiMoyReparation($forage['IDOuvrage'])); echo $delaiMoyReparation;?></span></td>
							<td name="<?php echo $forage['IDOuvrage'] . "_delaiMoyIndisponibilite";?>"><span class="badge"><?php $delaiMoyIndisponibilite = Stat::FormatTime(StatForage::delaiMoyIndisponibilite($forage['IDOuvrage'])); echo $delaiMoyIndisponibilite;?></span></td>
							
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
		var nom = $('td[name='+ id +'_codeOuvrage]').html();
		//alert(nom);
		$(".modal-title").html('Point du forage : ' + nom);
		$(".modal-body").load("graphForage.php?id=" + id);
		$("#basicModal").modal('show');
		//OpenModalBox('test', 'ret');
	});
});
</script>
<!--
<script src="js/graphReparateur.js"></script>
-->						
					