var chart;

var chart;

var periode;

var date_deb;

var date_fin;

function setDate()
{
	date_deb = $('#date_debut').val();
	
	date_fin = $('#date_fin').val();
	
	if (date_fin != "" && date_deb != "")
		periode = date_deb + ' au ' + date_fin;
	else periode = " Toute";
	
	return periode;
}

$(function () {
//alert($('#glob_rep_encours').text());
   // $('#graph_globale').highcharts({
   //alert(id);
   
 var idRep =  $('#id').val();
 
   var nom = $('td[name='+idRep +'_nomPrenoms]').text();
  
  var nbreReparation = $('td[name='+idRep +'_nbreReparation]').text();
  
  var tauxRealisation = $('td[name='+idRep +'_tauxRealisation]').text();
  
  var nbreTransfertAbsence = $('td[name='+idRep +'_nbreTransfertAbsence]').text();
   
  var nbrePriseCommande = $('td[name='+idRep +'_nbrePriseCommande]').text();
  
  var nbrePriseCommandeHorsDelai = $('td[name='+idRep +'_nbrePriseCommandeHorsDelai]').text();
  
  var nbreReparationHorsDelai = $('td[name='+idRep +'_nbreReparationHorsDelai]').text();
  
  var nbreAvisReparation = $('td[name='+idRep +'_nbreAvisReparation]').text();
   
   /*
    _palmares
   */
   
   chart = new Highcharts.Chart({
        chart: {
			//renderTo: 'container',
            plotBackgroundColor: null,
            plotBorderWidth: 1,//null,
            plotShadow: false,
			type: 'pie',
			//height: 650,
			renderTo: 'graph_agent'
			
        },
		 credits: {
            enabled: false
        },
        title: {
            text: 'Rendement'
        },
		subtitle : {
			text : 'Période : ' + setDate()
			},
        tooltip: {
    	   // pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
				//showInLegend: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
					//padding: 0,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
		/*legend: {
 					  layout: 'horizontal',
					   //align:'right',
					   style: 
					   {
						 left: 'auto',
						 top: 'auto',
						 bottom: '20px',
						 right: '10px'
						 },
					   floating: false
                    }, 
		*/
        series: [{
            name: 'Nombre',
            data: [
					['Reparations suivies', parseInt(nbreReparation)],
					['Prise de commande', parseInt(nbrePriseCommande)],
					//['Prise de commande hors délais', parseInt(nbrePriseCommandeHorsDelai)],
					//['Réparation hors délais', parseInt(nbreReparationHorsDelai)],
					['Avis de réparation reçus', parseInt(nbreAvisReparation)],
				]
        }]
    });
});
	