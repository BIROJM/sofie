var chart;

function showEvolGraph(d1, d2, donnee)
{
var panne = donnee.panne;
var marche = donnee.marche;
var prisEncharge = donnee.prisEncharge;
var reparationEnCours = donnee.reparationEnCours;
var reparationHorsDelai = donnee.reparationHorsDelai;

var periode;
if(d1 == '' || d2 == '')
{
	periode = ' Année en cours';
}
else
{
	periode =  'du ' + d1 + ' au ' + d2
}
var options = {
					chart: {
						renderTo: 'graph_evol',
						type: 'spline'						
					},
					colors: ['#d9534f', '#5cb85c',  '#f0ad4e', '#428bca', 'gray'],
					title: {
						text: 'Courbe évolutive des signalisations par statut'
					},
					subtitle: {
						text: 'Période : ' + periode
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
							text: 'Nombre de signalisations'
						},
						min: 0
					},
					tooltip: {
						formatter: function() {
				                return '<b>'+ this.series.name +'</b><br/>'+
								Highcharts.dateFormat('%e. %b', this.x) +': '+ this.y +'';
						}
					},
					series: [{
						name: 'Pannes declarées',
						// Define the data points. All series have a dummy year
						// of 1970/71 in order to be compared on the same x axis. Note
						// that in JavaScript, months start at 0 for January, 1 for February etc.
						data: [	
								]
					}, {
						name: 'Réparations effectuées',
						data: [												
								]
					}, {
						name: 'Prises de commande',
						data: [											
								]
					},
					
					{
						name: 'Reparations en cours',
						data: [ 
								]
					}, 
					{name: 'Réparations hors délai',
						data: [ 													
								]
					}
					
					]
				}				

options.series[0].data = formatData(panne);
options.series[1].data = formatData(marche);
options.series[2].data = formatData(prisEncharge);
options.series[3].data = formatData(reparationEnCours);
options.series[4].data = formatData(reparationHorsDelai);


var prisEncharge = donnee.prisEncharge;
var reparationHorsDelai = donnee.reparationHorsDelai;

								
chart = new Highcharts.Chart(options);								
				
}

function formatData(data)
{
	var plotData = [], i, len;
	len = data.length;
	for (i = 0; i < len; i++)
	{		
		dateArray = splitDate(data[i].date);
		plotData.push({
		x: Date.UTC(parseInt(dateArray[0]),  parseInt(dateArray[1]) -1, parseInt(dateArray[2])),y: parseInt(data[i].nb)
		})		
	}
	return plotData;
}

function splitDate(dateValue)
{
		var finalDate;
		if(dateValue == "") finalDate = "";
		else
		{
			var dateArray = dateValue.split("-");		
		}
		return dateArray;	
}
	