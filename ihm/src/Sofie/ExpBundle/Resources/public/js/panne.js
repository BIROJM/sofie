/* Soumission du formulaire */
function critereListener()
{
    $('#critere').bind({
        submit: function(){
            printCritere(this);
            return false;
        }
    });
}
function printCritere(form){
    var oParametre = {};

    var dateApp = form.elements.namedItem('form[dateApp]');
    dateApp = (dateApp) ? dateApp.value : '';
    if(dateApp != '' || dateApp.length > 0){
        oParametre['dateApp'] = dateApp;
    }

    var datePriseCharge = form.elements.namedItem('form[datePriseCharge]');
    datePriseCharge = (datePriseCharge) ? datePriseCharge.value : '';
    if(datePriseCharge != '' || datePriseCharge.length > 0){
        oParametre['datePriseCharge'] = datePriseCharge;
    }

    var dateDebutRep = form.elements.namedItem('form[dateDebutRep]');
    dateDebutRep = (dateDebutRep) ? dateDebutRep.value : '';
    if(dateDebutRep != '' || dateDebutRep.length > 0){
        oParametre['dateDebutRep'] = dateDebutRep;
    }

    var dateReparation = form.elements.namedItem('form[dateReparation]');
    dateReparation = (dateReparation) ? dateReparation.value : '';
    if(dateReparation != '' || dateReparation.length > 0){
        oParametre['dateReparation'] = dateReparation;
    }

    var type = form.elements.namedItem('form[type]');
    type = (type)?parseInt(type.value, 10):0;
    type = (isNaN(type))?0:type;
    if(parseInt(type) > 0){
        oParametre['type'] = type
    }

    var numero = form.elements.namedItem('form[numero]');
    numero = (numero) ? numero.value : '';
    if(numero != '' || numero.length > 0){
        oParametre['numero'] = numero;
    }

    sendQuery(createQueryString(oParametre));
}