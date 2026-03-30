var chart;
$(function () { chart = new Highcharts.Chart({
        chart: {
			//renderTo: 'container',
            plotBackgroundColor: null,
            plotBorderWidth: 1,//null,
            plotShadow: false,
			type: 'pie',
			//height: 650,
			renderTo: 'graph_globale'
			
        },
		 credits: {
            enabled: false
        },
        title: {
            text: 'Rendement'
        },
		subtitle : {
			text : $('#periode').text()
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
					['Pannes déclarées', parseInt($('#glob_panne_decl').text())],//parseInt($('#glob_panne_decl').text())
                    ['Reparations effectuées', parseInt($('#glob_rep_eff').text())], //parseInt($('#glob_rep_eff').text())
                    ['Reparations en cours', parseInt($('#glob_rep_encours').text())], //parseInt($('#glob_rep_encours').text())
                    ['Prise de commande', parseInt($('#glob_prise_cmd').text())] //parseInt($('#glob_prise_cmd').text())
				]
        }]
    });
});
	