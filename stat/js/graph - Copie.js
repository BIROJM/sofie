//var chart;
function showGlobalGrap(panne, reparation, reparationEnCours, priseEnCharge)
 { 
 chart = new Highcharts.Chart({
        chart: {
			//renderTo: 'container',
            plotBackgroundColor: null,
            plotBorderWidth: 1,//null,
            plotShadow: false,
			type: 'pie',
			//height: 650,
			renderTo: 'graph_globale'
			
        },
		colors: ['#d9534f', '#5cb85c',  '#f0ad4e', '#428bca', 'gray'],
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
					['Pannes déclarées', parseInt(panne)],//parseInt($('#glob_prise_en_charge').text())
                    ['Reparations effectuées', parseInt(reparation)], //parseInt($('#glob_rep_eff').text())
                    ['Reparations en cours', parseInt(reparationEnCours)], //parseInt($('#glob_rep_encours').text())
                    ['Prise de commande', parseInt(priseEnCharge)], //parseInt($('#glob_prise_cmd').text())
				    ['Commande hors délai', parseInt($('#glob_cmd_horsdelai').text())]
				]
        }]
    });
}

function showEvolGraph(panne)
{
//alert(panne);
chart = new Highcharts.Chart({
					chart: {
						renderTo: 'graph_evol',
						type: 'spline'						
					},
					colors: ['#d9534f', '#5cb85c',  '#f0ad4e', '#428bca', 'gray'],
					title: {
						text: 'Evolution des signalements par statut'
					},
					subtitle: {
						text: 'Période du 01/01/2015 au 0/10/2015'
					},
					xAxis: {
						type: 'datetime',
						dateTimeLabelFormats: { // don't display the dummy year
							month: '%e. %b',
							year: '%b'
						}
					},
					yAxis: {
						title: {
							text: 'Nombre de signalements'
						},
						min: 0
					},
					tooltip: {
						formatter: function() {
				                return '<b>'+ this.series.name +'</b><br/>'+
								Highcharts.dateFormat('%e. %b', this.x) +': '+ this.y +' m';
						}
					},
					series: [{
						name: 'Pannes declarées',
						// Define the data points. All series have a dummy year
						// of 1970/71 in order to be compared on the same x axis. Note
						// that in JavaScript, months start at 0 for January, 1 for February etc.
						data: [
								
							[Date.UTC(2014,  9, 27), 0   ],
							[Date.UTC(2014, 10, 10), 0.6 ],
							[Date.UTC(2014, 10, 18), 0.7 ],
							[Date.UTC(2014, 11,  2), 0.8 ],
							[Date.UTC(2014, 11,  9), 0.6 ],
							[Date.UTC(2014, 11, 16), 0.6 ],
							[Date.UTC(2014, 11, 28), 0.67],
							[Date.UTC(2015,  0,  1), 0.81],
							[Date.UTC(2015,  0,  8), 0.78],
							[Date.UTC(2015,  0, 12), 0.98],
							[Date.UTC(2015,  0, 27), 1.84],
							[Date.UTC(2015,  1, 10), 1.80],
							[Date.UTC(2015,  1, 18), 1.80],
							[Date.UTC(2015,  1, 24), 1.92],
							[Date.UTC(2015,  2,  4), 2.49],
							[Date.UTC(2015,  2, 11), 2.79],
							[Date.UTC(2015,  2, 15), 2.73],
							[Date.UTC(2015,  2, 25), 2.61],
							[Date.UTC(2015,  3,  2), 2.76],
							[Date.UTC(2015,  3,  6), 2.82],
							[Date.UTC(2015,  3, 13), 2.8 ],
							[Date.UTC(2015,  4,  3), 2.1 ],
							[Date.UTC(2015,  4, 26), 1.1 ],
							[Date.UTC(2015,  5,  9), 0.25],
							[Date.UTC(2015,  5, 12), 0   ]										
							
							]
					}, {
						name: 'Réparations effectuées',
						data: [
							[Date.UTC(2014,  9, 18), 0   ],
							[Date.UTC(2014,  9, 26), 0.2 ],
							[Date.UTC(2014, 11,  1), 0.47],
							[Date.UTC(2014, 11, 11), 0.55],
							[Date.UTC(2014, 11, 25), 1.38],
							[Date.UTC(2015,  0,  8), 1.38],
							[Date.UTC(2015,  0, 15), 1.38],
							[Date.UTC(2015,  1,  1), 1.38],
							[Date.UTC(2015,  1,  8), 1.48],
							[Date.UTC(2015,  1, 21), 1.5 ],
							[Date.UTC(2015,  2, 12), 1.89],
							[Date.UTC(2015,  2, 25), 2.0 ],
							[Date.UTC(2015,  3,  4), 1.94],
							[Date.UTC(2015,  3,  9), 1.91],
							[Date.UTC(2015,  3, 13), 1.75],
							[Date.UTC(2015,  3, 19), 1.6 ],
							[Date.UTC(2015,  4, 25), 0.6 ],
							[Date.UTC(2015,  4, 31), 0.35],
							[Date.UTC(2015,  5,  7), 0   ]
							
						]
					}, {
						name: 'Prise en charge',
						data: [
							[Date.UTC(2014,  9,  9), 0   ],
							[Date.UTC(2014,  9, 14), 0.15],
							[Date.UTC(2014, 10, 28), 0.35],
							[Date.UTC(2014, 11, 12), 0.46],
							[Date.UTC(2015,  0,  1), 0.59],
							[Date.UTC(2015,  0, 24), 0.58],
							[Date.UTC(2015,  1,  1), 0.62],
							[Date.UTC(2015,  1,  7), 0.65],
							[Date.UTC(2015,  1, 23), 0.77],
							[Date.UTC(2015,  2,  8), 0.77],
							[Date.UTC(2015,  2, 14), 0.79],
							[Date.UTC(2015,  2, 24), 0.86],
							[Date.UTC(2015,  3,  4), 0.8 ],
							[Date.UTC(2015,  3, 18), 0.94],
							[Date.UTC(2015,  3, 24), 0.9 ],
							[Date.UTC(2015,  4, 16), 0.39],
							[Date.UTC(2015,  4, 21), 0   ]
							
						]
					},
					
					{
						name: 'Reparation en cours',
						data: [ 
							[Date.UTC(2014,  9,  9), 0   ],
							[Date.UTC(2014,  9, 14), 0.15],
							[Date.UTC(2014, 10, 28), 0.35],
							[Date.UTC(2014, 11, 12), 0.46],
							[Date.UTC(2015,  0,  1), 0.59],
							[Date.UTC(2015,  0, 24), 0.58],
							[Date.UTC(2015,  1,  1), 0.62],
							[Date.UTC(2015,  1,  7), 0.65],
							[Date.UTC(2015,  1, 23), 0.77],
							[Date.UTC(2015,  2,  8), 0.77],
							[Date.UTC(2015,  2, 14), 0.79],
							[Date.UTC(2015,  2, 24), 0.86],
							[Date.UTC(2015,  3,  4), 0.8 ],
							[Date.UTC(2015,  3, 18), 0.94],
							[Date.UTC(2015,  3, 24), 0.9 ],
							[Date.UTC(2015,  4, 16), 0.39],
							[Date.UTC(2015,  4, 21), 0   ]
							
						]
					}, 
					{name: 'Hors délai de réparation',
						data: [ 
							[Date.UTC(2014,  9,  9), 0   ],
							[Date.UTC(2014,  9, 14), 0.15],
							[Date.UTC(2014, 10, 28), 0.35],
							[Date.UTC(2014, 11, 12), 0.46],
							[Date.UTC(2015,  0,  1), 0.59],
							[Date.UTC(2015,  0, 24), 0.58],
							[Date.UTC(2015,  1,  1), 0.62],
							[Date.UTC(2015,  1,  7), 0.65],
							[Date.UTC(2015,  1, 23), 0.77],
							[Date.UTC(2015,  2,  8), 0.77],
							[Date.UTC(2015,  2, 14), 0.79],
							[Date.UTC(2015,  2, 24), 0.86],
							[Date.UTC(2015,  3,  4), 0.8 ],
							[Date.UTC(2015,  3, 18), 0.94],
							[Date.UTC(2015,  3, 24), 0.9 ],
							[Date.UTC(2015,  4, 16), 0.39],
							[Date.UTC(2015,  4, 21), 0   ]
							
						]
					}
					
					]
				});
				
}
	