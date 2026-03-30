<div class="row">
	<div id="breadcrumb" class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="index.html">Statistiques SOFIE</a></li>
			<li><a href="#">Synthèse statistiques globales</a></li>
		</ol>
	</div>
</div>
<h4 class="page-header">Synthèse statistiques globales</h4>
<?php var_dump($_SESSION); ?>
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
									Période de debut ff : <input type="text" class=" date" id="date_debut" placeholder="">
								</div>
								
								<!--<label for="date_example" class="col-sm-2 control-label">Période de fin </label>-->
								<div class="col-sm-2">
									Période de fin  : <input type="text" class=" date" id="date_fin" placeholder="">
								
								</div>
								
								<!-- <label class="col-sm-2 control-label">Etendu</label>-->
                                <div class="col-sm-2">
                                   Etendue :  <select id="regions" class=" populate placeholder"></select>
                                </div>
								
								<div class="col-sm-3">
                                   Localité :  <select id="localites" class="populate placeholder">
                                              </select>
                                </div>
								<div class="col-sm-2 pull-right">
									<button id="showStat" type="button" class="btn btn-primary" style="margin-top : 20px; float: right;">Afficher</button>
								</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-table"></i>
                    <span>Statistiques générales <span class="libStatGen"></span></span>
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
                <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <thead>
                     <tr >
                        <th>Pannes déclarées</th>
                        <th>Réparations effectuées</th>
                        <th>Réparation en cours</th>
                      <!--  <th>réparations</th> -->
						<th>Prise de commande</th>
						<th>Commande hors délai</th>
						
                    </tr>
                    </thead>
                    <tbody>
                    <tr align="center">
                        <td>
							<button type="button" class="btn btn-danger btn-app btn-circle"><span id="glob_panne_decl"></span></button><br>
							<i>Moyenne : </i> <span id="glob_panne_decl_moy"></span>
							
						</td>
                        <td>
								<button type="button" class="btn btn-success btn-app btn-circle"><span id="glob_rep_eff"></span></button><br>
								<i>Moyenne : </i> <span id="glob_rep_eff_moy"></span> <br> <i>Délai moyen : </i> <span id="glob_rep_eff_moy_delai">0</span> H
						</td>
                        <td>
								<button type="button" class="btn btn-warning btn-app btn-circle"><span id="glob_rep_encours"></span></button>
						</td>
					<!--                       
					   <td>
								<button type="button" class="btn btn-info btn-app btn-circle">27</button>
						</td>
					-->
						 <td>
							<button type="button" class="btn btn-primary btn-app btn-circle"><span id="glob_prise_cmd"></span></button>
						 </td>
						 
						  <td>
							<button type="button" class="btn  btn-app btn-circle"><span id="glob_cmd_horsdelai"></span></button>
						 </td>
					
                    </tr>
                   
                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">

<div id="listPalmares"></div>

<div class="col-xs-12 col-sm-8">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Graphique <span class="libStatGen"></span></span>
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
				<div id="graph_globale">
				
				</div>
			</div>
		</div>
	</div>

</div>


<script src="js/script.js"></script>
<script src="js/graph.js"></script>

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
