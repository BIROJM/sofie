/**
 * Created by Eugene on 29/06/2015.
 */

var xhr;

$(function(){
    critereListener();
    onChangeCheckAndDisabled($('select[name*=qualification]'), [$('#sofie_adminbundle_user_admin')], [adminProfileId]);
    onChangeCheckAndDisabled($('select[name*=qualification]'), [$('#sofie_adminbundle_user_isMobile')], [itinerantProfileId]);
});

function statusActifListener(){
    $("input[name*=isMobile]").change(function(){
        //if($(this).prop("checked"));
    });
}

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

    var qualification = form.elements.namedItem('form[qualification]');
    qualification = (qualification)?parseInt(qualification.value, 10):0;
    qualification = (isNaN(qualification))?0:qualification;
    if(parseInt(qualification) > 0){
        oParametre['qualification'] = qualification;
    }

    var status = form.elements.namedItem('form[status]');
    status = (status) ? $.trim(status.value) : '';
    if(status != '' || status.length > 0){
        oParametre['status'] = status;
    }

    var email = form.elements.namedItem('form[email]');
    email = (email) ? $.trim(email.value) : '';
    if(email != '' || email.length > 0){
        oParametre['email'] = email;
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

function regionForAgentListener(region, agent){
    $(region).bind({
        change: function(){
            var idRegion = (region)?parseInt($(region).val()):0;
            idRegion = (isNaN(idRegion))?0:idRegion;
            loadAgentByRegion(idRegion, agent);
        }
    });
}

function loadAgentByRegion(idRegion, agent){
    if(xhr && xhr.readyState != 4){
        xhr.abort();
    }
    $(agent).html('<option>chargement...</option>');
    $(agent).attr('disabled', 'disabled');
    select2Change(agent);
    chowAjaxLoader();
    xhr = $.ajax({
        method: 'GET',
        url: Routing.generate('sofieexp_agent_ajax_by_region_not_user', {'idRegion': idRegion})
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