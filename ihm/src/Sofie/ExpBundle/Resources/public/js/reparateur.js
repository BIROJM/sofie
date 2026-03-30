/**
 * Created by Eugene on 29/06/2015.
 */

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

    var region = form.elements.namedItem('form[region]');
    region = (region)?parseInt(region.value, 10):0;
    region = (isNaN(region))?0:region;
    if(parseInt(region) > 0){
        oParametre['region'] = region;
    }

    var initStatus = form.elements.namedItem('form[initStatus]');
    initStatus = (initStatus) ? $.trim(initStatus.value) : '';
    if(initStatus != '' || initStatus.length > 0){
        oParametre['initStatus'] = initStatus;
    }

    var codeInit = form.elements.namedItem('form[codeInit]');
    codeInit = (codeInit) ? $.trim(codeInit.value) : '';
    if(codeInit != '' || codeInit.length > 0){
        oParametre['codeInit'] = codeInit;
    }

    var numeroAppel = form.elements.namedItem('form[numeroAppel]');
    numeroAppel = (numeroAppel) ? $.trim(numeroAppel.value) : '';
    if(numeroAppel != '' || numeroAppel.length > 0){
        oParametre['numeroAppel'] = numeroAppel;
    }

    var nom = form.elements.namedItem('form[nom]');
    nom = (nom) ? $.trim(nom.value) : '';
    if(nom != '' || nom.length > 0){
        oParametre['nom'] = nom;
    }

    var prenoms = form.elements.namedItem('form[prenoms]');
    prenoms = (prenoms) ? $.trim(prenoms.value) : '';
    if(prenoms != '' || prenoms.length > 0){
        oParametre['prenoms'] = prenoms;
    }

    sendQuery(createQueryString(oParametre));
}