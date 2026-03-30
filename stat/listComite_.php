<?php 
session_start();
require_once(dirname(__FILE__).'/class/statComite.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');

if(!empty($_GET['date_debut'])) statComite::$periodeDebut = $_GET['date_debut'];
else statComite::$periodeDebut = '2015-01-01';
if(!empty($_GET['date_fin'])) statComite::$periodeFin = $_GET['date_fin'];
else statComite::$periodeFin = '2015-12-31';
if(!empty($_GET['regions'])) statComite::$region = $_GET['regions'];
else statComite::$region ="";
if(!empty($_GET['localite'])) statComite::$localite = $_GET['localite'];
else statComite::$localite ="";
if(!empty($_GET['id'])) statComite::$id = $_GET['id'];
else statComite::$id ="";

if(isset($_GET['getComites'])) 
{	
	$comites = statComite::getComites();
	
	//var_dump($Comites);
}

?>

<div class="row" align='center'>
	<div class="col-xs-10">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa"></i>
					<span>Liste des comités eau<span class="libStatGen"></span></span>
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
							<th style="text-align:center;width:200px">Nom et prénoms du sécretaire</th>
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
							<td name="<?php echo $comite['IDComite'] . "_ouvrage";?>"><span class="badge alert-primary"><?php echo $comite['CodeOuvrage']; ?></span></td>
							<td name="<?php echo $comite['IDComite'] . "_panne";?>"><span class="badge alert-danger"><?php echo StatComite::nbrePannes($comite['IDNumAppel']); ?></span></td>
							<td name="<?php echo $comite['IDComite'] . "_nbreReparation";?>"><span class="badge alert-success"><?php echo StatComite::nbreReparation($comite['IDNumAppel']); ?></span></td>
							<!--<td name="<?php //echo $comite['IDComite'] . "_nbreAvisPanne";?>"><span class="badge alert-warning"><?php //echo StatComite::nbreAvisPriseEnComptePanne($comite['IDNumAppel']); ?></span></td> -->
							
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
		$(".modal-title").html('Rendement du comité eau : ' + nom);
		$(".modal-body").load("graphComite.php?id=" + id);
		$("#basicModal").modal('show');
		//OpenModalBox('test', 'ret');
	});
});
</script>
<!--
<script src="js/graphComite.js"></script>
-->						
					