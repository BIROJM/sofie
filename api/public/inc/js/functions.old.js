//jQuery(document).ready(function() {
	var refreshTimer = 10000; //Temps de rafraichissement des points en ms.
	var updateTimer = 5000; //Temps de mise à jour des update en base de données.
	var url = 'http://192.168.2.108/sofieapi/public'; // URL de l'application.
	var urlCarte = 'http://192.168.2.108/sofieapi/public';
	
	var map = null;
	var idRegion = null;
	var marker;
	var markers = new L.FeatureGroup();
	$('a[rel="txtTooltip"]').tooltip();
	
	var xhr; // Pour avorter la requête ajax en cours avant la nouvelle
	var cleanTime; // Pour désavtiver le timer avant le nouveau
	
	$('#tabRegion a').dblclick(function() { 
		var id = $(this).attr('name');
		
		var mapId = id + 'Map';
		var mapArea = $('#' + id + 'Carte').val();
		var lat = parseFloat($('#' + id + 'Lat').val());
		var lon = parseFloat($('#' + id + 'Lon').val());		
		var zmin = parseFloat($('#' + id + 'Zoom').val());
		var zmax = parseFloat($('#' + id + 'maxZoom').val());
		idRegion = id;
		$('#' +idRegion+ 'Localite').html('');
		$('#home').hide();
		if(map != null)
		{
			map.remove();
		}
		loadMap(mapId,mapArea,lat,lon,zmin,zmax);
		
		loadPit();
		if(cleanTime){
			clearInterval(cleanTime);
		}
		cleanTime = setInterval(loadPit,refreshTimer);
	});
	
	
	/* $('#tabRegion a').on('click', function(e) {
		
		$(this).dblclick();		
	}); */
	
	function setIcon(path)
	{	
		var myIcon;
		if(idRegion !='6')
		{
			myIcon = L.icon({
			iconUrl: path,
			iconSize: [14, 20], //Taille d'icone pour la carte des regions
			iconAnchor: [9, 21],
			popupAnchor: [0, -14]
		});
		}
		else 
		{
			myIcon = L.icon({
			iconUrl: path,
			iconSize: [6, 10], //Taille d'icone pour la carte entiere
			iconAnchor: [9, 21],
			popupAnchor: [0, -14]
		});
		}
		
		return myIcon;
	}
	
	function setMarker(code, lat, lon, libelle, status, path, localite, last_update)
	{
		var dt = last_update.split(' ');
		var day = dt[0].split('-');
		day = day[2] + '/' + day[1] + '/' +day[0];
		
		marker = L.marker([lat, lon], {icon: setIcon(path)}).addTo(map).on('click', function(e){ this.bindPopup("<b>Code : " + code +"</b><br><b>Type : " + libelle +"</b><br><b>Localité : " + localite +"</b><br>Etat : " + status + "<br>Date : "  + day + " à : " + dt[1]).openPopup();});
		//marker.bindPopup("<b>" + libelle +"</b><br>" + status).openPopup();
		markers.addLayer(marker);
	}
	
	var UpdatePit = function()
	{
		$.ajax({
			type:"GET",
			url : url + "/load.php?update=yes",
			})
	}
	
	var loadPit = function()
	{
			if(xhr && xhr.readyState != 4){
				xhr.abort();
			}
			//map.removeLayer();
			//markers.clearLayers();
			urlPost =  "/carte/selectOuvrageByRegion/"+idRegion+"";
			if(idRegion == 6 ) urlPost =  "/carte/selectAllOuvrages";
			xhr = $.ajax({
				type:"GET",
				url : url + urlPost,
				success : function(donnee){
					$.each(donnee, function (key,val) {
						 setMarker(val.CodeOuvrage, val.Latitude, val.Longitude, val.TypeOuvrage, val.libelleStatut, url + '/images/' + val.icone, val.NomLocalite, val.dateStatut);
					   // console.log('img =' + val.icone + ' lib - '+val.libelleStatut);
						});
					}	
													
				})
				map.addLayer(markers);
	}
	
	function loadMap(mapId,mapPath,lat,lon,zoom,zmax)
	{
		map = new L.map(mapId, {fullscreenControl: true});
		var osmUrl= urlCarte + '/' + mapPath + '/{z}/{x}/{y}.png';
		var osmAttrib='Map data © <a href="#">SOFIE</a> Togo';
		var osm = new L.TileLayer(osmUrl, {minZoom: zoom, maxZoom: zmax, attribution: osmAttrib}).addTo(map);
		//alert(osmUrl);
	    map.setView(new L.LatLng(lat, lon),zoom);
		map.addLayer(osm);
		//alert(mapPath);
	}
	
	function removeMap()
	{
		map.remove();
	}
	
	//setInterval(showWarning,3000);
	
	//Ligne à commenter pour eviter les mise à jour de pannes en base de données.
	//setInterval(UpdatePit,updateTimer);
//});
	
