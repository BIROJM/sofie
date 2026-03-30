<input type="hidden" name="actualPage" class="actualPage" value="dr">
<div class="row">
	<div id="breadcrumb" class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="index.html">Statistiques SOFIE</a></li>
			<li><a href="#">Directeurs régionaux</a></li>
		</ol>
	</div>
</div>
<h4 class="page-header">Directeurs régionaux</h4>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-search"></i>
                    <span>Critères d'affichage</span>
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
            <div class="box-content">
                <div class="row">
                
                    <div class="col-sm-12">
                        <form class="form-horizontal" role="form">
                            
                            	<!--<label for="date_example" class="col-sm-2 control-label" style="padding-left : 0" >Période de debut </label>-->
								<div class="col-sm-2">
									Période de debut  : <input type="text" class="form-control date" id="date_debut" placeholder="">
								</div>
								
								<!--<label for="date_example" class="col-sm-2 control-label">Période de fin </label>-->
								<div class="col-sm-2">
									Période de fin  : <input type="text" class="form-control date" id="date_fin" placeholder="">
								
								</div>
								
								<!-- <label class="col-sm-2 control-label">Etendu</label>-->
                                <div class="col-sm-2">
                                   Etendue :  <select id="regions" class=" populate placeholder"></select>
                                </div>
								<!--
								<div class="col-sm-3">
                                   Localité :  <select id="localites" class=" populate placeholder">
                                              </select>
                                </div>
								-->
								<div class="col-sm-3">
                                  Directeur Régional :  <select id="dr" class=" populate placeholder">
                                              </select>
                                </div>
								
								<div class="col-sm-2 pull-right">
									<button id="showStat_dr" type="button" class="btn btn-primary" style="margin-top : 20px; float: right;">Afficher</button>
								</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/script.js"></script>
<!--<script src="js/graph.js"></script>-->
<div id="listDr"></div>

<?php //include_once('listReparateur.php'); ?>

<script type="text/javascript">
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
});
</script>