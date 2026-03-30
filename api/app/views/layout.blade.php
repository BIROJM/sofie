<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf8">
		<link rel="stylesheet" href="{{asset('inc/js/leaflet/leaflet.css')}}" />
		<link rel="stylesheet" href="{{asset('inc/js/leaflet/leaflet.fullscreen.css')}}" />
		<!--
		<link rel="stylesheet" href="{{asset('inc/css/bootstrap.min.css')}}">
		<link rel="stylesheet" href="{{asset('inc/css/jquery.dataTables.css')}}">
		-->
		<link rel="stylesheet" href="{{asset('inc/css/style.css')}}">
		
		
		
		<title>SOFIE [SUIVI DES OUVRAGES DE FORAGES ET DES INDICATEURS POUR L'EAU]</title>

		<script type="text/javascript">
			var baseUrl = "{{ url('/') }}";
			var laraPublicPath = '';
			if(~baseUrl.lastIndexOf('index.php')){
				laraPublicPath = baseUrl.substring(0, baseUrl.lastIndexOf('/'));
			}else{
				laraPublicPath = baseUrl;
			}
			laraPublicPath = laraPublicPath.replace(/(\/+)$/, "");
		</script>
	</head>
	<body>
	
		<div id="conteneur">
			<!--
			<div id="entete" >
				<img src="{{asset('inc/img/armoirie.jpg')}}" class="pull-left">
				<span class="pull-right">
				 @if(Auth::check())
				
				  <i class="icon-user"></i> {{Auth::user()->name}} (<a href="{{ URL::to('auth/logout')}}">Deconnexion</a>)
				
				@endif
				</span>
				<h1>&nbsp;SOFIE</h1>
				<h3>&nbsp;&nbsp;SUIVI DES OUVRAGES DE FORAGES ET DES INDICATEURS POUR L'EAU</h3> 
				
				
				
				
			</div>
			<hr class="trait" />
			-->
			 @section('page')
			 @show
			
		<!--
		<div id="pieds">
			Designed by Mobisoft  &copy; 2015
			</div>
		</div>
		@section('modal')
		@show
		-->
		<script src="{{asset('inc/js/leaflet/leaflet.js')}}"></script>
		<!--
		<script src="{{asset('inc/js/jquery.min.js')}}"></script>
		<script src="{{asset('inc/js/bootstrap.min.js')}}"></script>
		-->
	<!--	<script src="{{--asset('inc/js/jquery.dataTables.js')--}}"></script> -->
		<script src="{{asset('inc/js/leaflet/leaflet.fullscreen.js')}}"></script>
		@section('scriptCarte')
		@show
		@section('scriptCarte1')
		@show
		@section('admin')
		@show
		@section('user')
		@show
	</body>
</html>
