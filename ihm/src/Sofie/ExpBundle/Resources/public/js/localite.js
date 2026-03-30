/**
 * Created by Eugene on 29/06/2015.
 */

var xhr;

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

    var nom = form.elements.namedItem('form[nom]');
    nom = (nom) ? nom.value : '';

    if(parseInt(region) > 0){
        oParametre['region'] = region;
    }
    if(nom != '' || nom.length > 0){
        oParametre['nom'] = nom;
    }

    sendQuery(createQueryString(oParametre));
}

function regionForAgentFormenListener(region, agent){
    $(region).bind({
        change: function(){
            var idRegion = (region)?parseInt($(region).val()):0;
            idRegion = (isNaN(idRegion))?0:idRegion;
            loadAgentFormenByRegion(idRegion, agent);
        }
    });
}

function loadAgentFormenByRegion(idRegion, agent){
    if(xhr && xhr.readyState != 4){
        xhr.abort();
    }
    $(agent).html('<option>chargement...</option>');
    $(agent).attr('disabled', 'disabled');
    select2Change(agent);
    chowAjaxLoader();
    xhr = $.ajax({
        method: 'GET',
        url: Routing.generate('sofieexp_agent_ajax_formen_by_region', {'idRegion': idRegion})
    }).done(function(response){
        $(agent).html(response);
    }).fail(function(){
        $(agent).html('<option></option>');
    }).always(function(){
        $(agent).removeAttr('disabled');
        select2Change(agent);
        hideAjaxLoader();
    });
}
