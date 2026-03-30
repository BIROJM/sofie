/**
 * Created by Eugene on 29/06/2015.
 */

$(function(){
    dateRange($('#form_dmin'), $('#form_dmax'));
    critereListener();
});

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

    var dmin = form.elements.namedItem('form[dmin]');
    dmin = (dmin) ? $.trim(dmin.value) : '';

    var dmax = form.elements.namedItem('form[dmax]');
    dmax = (dmax) ? $.trim(dmax.value) : '';

    var region = form.elements.namedItem('form[region]');
    region = (region)?parseInt(region.value, 10):0;
    region = (isNaN(region))?0:region;

    var origine = form.elements.namedItem('form[origine]');
    origine = (origine)?parseInt(origine.value, 10):0;
    origine = (isNaN(origine))?0:origine;

    var motif = form.elements.namedItem('form[motif]');
    motif = (motif) ? parseInt(motif.value, 10) : 0;
    motif = (isNaN(motif)) ? 0 : motif;

    var ouvrage = form.elements.namedItem('form[ouvrage]');
    ouvrage = (ouvrage) ? $.trim(ouvrage.value) : '';

    var panne = form.elements.namedItem('form[panne]');
    panne = (panne) ? $.trim(panne.value) : '';

    var numeroAppel = form.elements.namedItem('form[numeroAppel]');
    numeroAppel = (numeroAppel) ? $.trim(numeroAppel.value) : '';

    if(validDate(dmin)){
        oParametre['dmin'] = dmin;
    }
    if(validDate(dmax)){
        oParametre['dmax'] = dmax;
    }
    if(parseInt(region) > 0){
        oParametre['region'] = region;
    }
    if(parseInt(origine) > 0){
        oParametre['origine'] = origine;
    }
    if(parseInt(motif) > 0){
        oParametre['motif'] = motif;
    }
    if(ouvrage != '' || ouvrage.length > 0){
        oParametre['ouvrage'] = ouvrage;
    }
    if(panne != '' || panne.length > 0){
        oParametre['panne'] = panne;
    }
    if(numeroAppel != '' || numeroAppel.length > 0){
        oParametre['numeroAppel'] = numeroAppel;
    }

    sendQuery(createQueryString(oParametre));
}