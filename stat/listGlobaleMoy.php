<?php
require_once(dirname(__FILE__).'/class/statGlobale.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');

if(!empty($_GET['date_debut'])) StatGlobale::$periodeDebut = $_GET['date_debut'];
else StatGlobale::$periodeDebut = date('Y') .'-01-01';
if(!empty($_GET['date_fin'])) StatGlobale::$periodeFin = $_GET['date_fin'];
else StatGlobale::$periodeFin = date('Y') .'-12-31';
if(!empty($_GET['regions'])) StatGlobale::$region = $_GET['regions'];
else StatGlobale::$region ="";
if(!empty($_GET['localite'])) StatGlobale::$localite = $_GET['localite'];
else StatGlobale::$localite ="";


if(isset($_GET['getPalmares'])) 
{
		//$typologiePannes = StatGlobale::typologiePanne();	
}
?>

	<div class="col-xs-12 col-sm-4" style="top:0px">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Moyenne des pannes et réparations <span class="libStatGen"></span></span>
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
				<p>Nombre de réparations par typologie de pannes</p>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Type de pannes</th>
							<th>Nombre</th>
							<!--<th>%</th>-->
						</tr>
					</thead>
					<tbody>
					<?php
					foreach($typologiePannes as $typologiePanne)
					{
					?>
						<tr>
							<td><?php echo $typologiePanne['libelle']; ?></td>
							<td><?php echo $typologiePanne['nb']; ?></td>
							<!--<td>47 %</td>-->
							
						</tr>
					<?php
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	
