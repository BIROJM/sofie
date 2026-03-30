<?php
session_start();
//var_dump($_SESSION['_sf2_attributes'] ['sofie_auth_user'] ['roles']);exit;
$_SESSION['roles_stat'] = $_SESSION['_sf2_attributes'] ['sofie_auth_user'] ['roles'];

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>STATISTIQUES SOFIE</title>
		<meta name="description" content="description">
		<meta name="author" content="DevOOPS">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">
		<link href="plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
		<!--<link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">-->
		<link href="plugins/Font-Awesome/css/font-awesome.css" rel="stylesheet">
		<!--<link href='http://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>-->
		<link href="plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
		<link href="plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
		<link href="plugins/xcharts/xcharts.min.css" rel="stylesheet">
		<link href="plugins/select2/select2.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet">
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
				<script src="http://getbootstrap.com/docs-assets/js/html5shiv.js"></script>
				<script src="http://getbootstrap.com/docs-assets/js/respond.min.js"></script>
		<![endif]-->
	</head>
<body>

<!--Start Header-->
<img id="anim" alt="" src="img/ajax-loader.gif" style="display:none; z-index:5000; position: fixed; left: 50%; top: 50%;">
<div id="screensaver">
	<canvas id="canvas"></canvas>
	<i class="fa fa-lock" id="screen_unlock"></i>
</div>
<div id="modalbox">
	<div class="devoops-modal">
		<div class="devoops-modal-header">
			<div class="modal-header-name">
				<span>Basic table</span>
			</div>
			<div class="box-icons">
				<a class="close-link">
					<i class="fa fa-times"></i>
				</a>
			</div>
		</div>
		<div class="devoops-modal-inner">
		</div>
		<div class="devoops-modal-bottom">
		</div>
	</div>
</div>
<header class="navbar">
	<div class="container-fluid expanded-panel">
		<div class="row">
			<div id="logo" class="col-xs-12 col-sm-2">
				<a href="index.html">STATISTIQUES</a>
			</div>
			
			
			
			<div id="top-panel" class="col-xs-12 col-sm-10">
				<div class="row">
					<div class="col-xs-8 col-sm-10">
						<a href="#" class="show-sidebar">
						  <i class="fa fa-bars"></i>
						</a>
						<div >
							&nbsp;&nbsp;&nbsp;&nbsp;<b>BIENVENUE SUR LE MODULE DE STATISTIQUES DU PROJET SOFIE</b>
						</div>
					</div>
					<div class="col-xs-4 col-sm-2 top-panel-right">
						<ul class="nav navbar-nav pull-right panel-menu">
													
							
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
			
	
		</div>
	</div>
</header>
<!--End Header-->
<!--Start Container-->
<div id="main" class="container-fluid">
	<div class="row">
		<div id="sidebar-left" class="col-xs-2 col-sm-2">
			<ul class="nav main-menu">
				<?php if(in_array('ROLE_STAT_GLOBALE', $_SESSION['roles_stat'])) { ?>				
				<li>
					<a href="forms/index.php" class="active ajax-link">
						<i class="fa fa-dashboard"></i>
						<span class="hidden-xs">Synthèse statistiques globales</span>
					</a>
				</li>
				<?php } ?>
				<?php if(in_array('ROLE_STAT_REPARATEUR', $_SESSION['roles_stat'])) { ?>	
				<li>
					<a class="ajax-link" href="forms/reparateurs.php">
						<span class="hidden-xs">Rendement des réparateurs</span>
					</a>
				</li>
				<?php } ?>
				<?php if(in_array('ROLE_STAT_AGENT_FORMEN', $_SESSION['roles_stat'])) { ?>	
				<li>
					<a class="ajax-link" href="forms/agents.php">
						<span class="hidden-xs">Rendement Agents FORMEN</span>
					</a>
				</li>
				<?php } ?>
				<?php if(in_array('ROLE_STAT_SOCIOLOGUE', $_SESSION['roles_stat'])) { ?>
				<li>
					<a class="ajax-link" href="forms/sociologues.php">
						<span class="hidden-xs">Sociologues</span>
					</a>
				</li>
				<?php } ?>
				<?php if(in_array('ROLE_STAT_DR', $_SESSION['roles_stat'])) { ?>
				<li>
					<a class="ajax-link" href="forms/dr.php">
						<span class="hidden-xs">Directeurs Regionaux</span>
					</a>
				</li>
				<?php } ?>
				<?php if(in_array('ROLE_STAT_POINT_FORAGE', $_SESSION['roles_stat'])) { ?>
				<li>
					<a class="ajax-link" href="forms/forages.php">
						<span class="hidden-xs">Point des forages</span>
					</a>
				</li>
				<?php } ?>
				<?php if(in_array('ROLE_STAT_COMITE', $_SESSION['roles_stat'])) { ?>
				<li>
					<a class="ajax-link" href="forms/comites.php">
						<span class="hidden-xs">Situation des comités eau</span>
					</a>
				</li>
				<?php } ?>
				<!--
				<li>
					<a class="ajax-link" href="ajax/forages.html">
						<span class="hidden-xs">Point par région</span>
					</a>
				</li>
				
				<li>
					<a class="ajax-link" href="ajax/forages.html">
						<span class="hidden-xs">Point par localité</span>
					</a>
				</li>
				-->
			
			</ul>
		</div>
		<!--Start Content-->
		<div id="content" class="col-xs-12 col-sm-10">
			<div class="preloader">
				<img src="img/devoops_getdata.gif" class="devoops-getdata" alt="preloader"/>
			</div>
			<div id="ajax-content"></div>
		</div>
		<!--End Content-->
	</div>
</div>
<!--End Container-->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!--<script src="http://code.jquery.com/jquery.js"></script>-->
<script src="plugins/jquery/jquery-2.1.0.min.js"></script>
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="plugins/jquery-ui/i18n/jquery.ui.datepicker-fr.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="plugins/bootstrap/bootstrap.min.js"></script>
<script src="plugins/justified-gallery/jquery.justifiedgallery.min.js"></script>
<script src="plugins/tinymce/tinymce.min.js"></script>
<script src="plugins/tinymce/jquery.tinymce.min.js"></script>
<script src="plugins/highcharts/js/highcharts.js"></script>
<script src="plugins/highcharts/js/modules/exporting.js"></script>
<script src="js/highchartsOptions.js"></script>
<!-- All functions for this theme + document.ready processing -->
<script src="js/devoops.js"></script>
<script>

$.ajaxSetup({
    beforeSend:function(){
        $("#anim").show();
    },
    complete:function(){
        $("#anim").hide();
    }
});	
</script>


</body>
</html>
