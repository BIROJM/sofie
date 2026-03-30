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
   
 var idRep =  $('#id').val();
 
  var nbreReparation = $('td[name='+idRep +'_nbreReparation]').text();
  
  var nbrePanne = $('td[name='+idRep +'_nbrePanne]').text();
  
  var tauxRealisation = $('td[name='+idRep +'_tauxRealisation]').text();
  
  var nbreReparationHorsDelai = $('td[name='+idRep +'_nbreReparationHorsDelai]').text();
  
    /*
    _palmares
   */
   
  $('#graph_forage').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Pannes et réparations signalées sur l\'ouvrage'
        },
        subtitle: {
            text: 'Période : ' + setDate()
        },
		 credits: {
            enabled: false
        },
        
		 xAxis: {
            categories: [
                'Pannes enregistrées',
                'Reparations effectuées',
                ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Quantité'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0"> </td>' +
                '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
		data: [
			  ['Pannes enregistrées', parseInt(nbrePanne)],
			  ['Reparations effectuées',  parseInt(nbreReparation)],
			   
			]

        }]
    });
});
 
	