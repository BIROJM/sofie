/**
 * Created by Eugene on 29/06/2015.
 */

var xhr;

function checkAll(){
    $('.check-all').bind({
          change: function(){
              if(xhr && xhr.readyState != 4){
                  xhr.abort();
                  hideAjaxLoader();
              }
              var parent = $(this).closest('div').find('select');
              var nextSelect = $(this).closest('div.form-group').nextAll().find('select');
              if(/localites/.test($(this).prop('name'))){
                  nextSelect = $(this).closest('div.form-group').nextAll()
                      .find('select[name*="comites"], select[name*="reparateurs"], select[name*="agents"]');
              }
              if($(this).prop('checked')) {
                  $(parent).children('option').prop('selected', false);
                  select2Change(parent);
                  $(parent).prop('disabled', true);
                  if(/(region|localite)/.test($(this).prop('name'))){
                      $(nextSelect).html('');
                      select2Change(nextSelect);
                      $(nextSelect).prop('disabled', true);
                      $(nextSelect).nextAll('.check-all').prop('checked', true);
                  }
              }else{
                  if(/(region|localite)/.test($(this).prop('name'))) {
                      $(nextSelect).prop('disabled', false);
                      $(nextSelect).nextAll('.check-all').prop('checked', false);
                  }else{
                      var prevFormGroup =  $(this).closest('div.form-group').prevAll();
                      $(prevFormGroup).find('select[name*=region]').prop('disabled', false);
                      $(prevFormGroup).find('input[name*=region]').prop('checked', false);
                      if(/(comites|reparateurs|agents)/.test($(this).prop('name'))){
                          $(prevFormGroup).find('select[name*=localite]').prop('disabled', false);
                          $(prevFormGroup).find('input[name*=localite]').prop('checked', false);
                      }
                  }
                  $(parent).children('option').prop('selected', false);
                  $(parent).prop('disabled', false);
              }
          }
    });
}


/**
 * Created by Eugene on 30/06/2015.
 */

/* Load Cascade Dependencies by region */
function regionMultiListener(region, localite, reparateur, comite, agent, sociologue, directeur) {
    $(region).bind({
        change: function(event, computer, action){
            if(computer && !action){
                return;
            }
            var listRegion = $(this).val() || [];
            loadByRegion(listRegion, localite, reparateur, comite, agent, sociologue, directeur);
        }
    });
}

/* Load Cascade Dependencies by localite */
function localiteMultiListener(localite, reparateur, comite, agent) {
    $(localite).bind({
        change: function(event, computer, action){
            if(computer && !action){
                return;
            }
            var listLocalite = $(this).val() || [];
            loadByLocalite(listLocalite, reparateur, comite, agent);
        }
    });
}

function loadByRegion(listRegion, localite, reparateur, comite, agent, sociologue, directeur) {
    if(xhr && xhr.readyState != 4){
        xhr.abort();
    }
    clearAll(localite, reparateur, comite, agent, sociologue, directeur);
    changeAll(localite, reparateur, comite, agent, sociologue, directeur);
    chowAjaxLoader();
    xhr = $.ajax({
        url: Routing.generate('sofieexp_region_load_dependencies'),
        method: 'POST',
        data: 'list='+listRegion,
        dataType: 'json'
    }).done(function(response){
        localite.html(response.localites);
        reparateur.html(response.reparateurs);
        comite.html(response.comites);
        agent.html(response.agents);
        sociologue.html(response.sociologues);
        directeur.html(response.directeurs);
        changeAll(localite, reparateur, comite, agent, sociologue, directeur);
        hideAjaxLoader();
    });
}

function loadByLocalite(listLocalite, reparateur, comite, agent) {
    if(xhr && xhr.readyState != 4){
        xhr.abort();
    }
    clearLocaliteAll(reparateur, comite, agent);
    changeLocaliteAll(reparateur, comite, agent);
    chowAjaxLoader();
    xhr = $.ajax({
        url: Routing.generate('sofieexp_localite_load_dependencies'),
        method: 'POST',
        data: 'list='+listLocalite,
        dataType: 'json'
    }).done(function(response){
        reparateur.html(response.reparateurs);
        comite.html(response.comites);
        agent.html(response.agents);
        changeLocaliteAll(reparateur, comite, agent);
        hideAjaxLoader();
    });
}

function loadingAll(localite, reparateur, comite, agent, sociologue, directeur){
    localite.html('<option value="">chargement..</option>');
    loadingLocaliteAll(reparateur, comite, agent);
    sociologue.html('<option value="">chargement..</option>');
    directeur.html('<option value="">chargement..</option>');
}

function loadingLocaliteAll(reparateur, comite, agent){
    reparateur.html('<option value="">chargement..</option>');
    comite.html('<option value="">chargement..</option>');
    agent.html('<option value="">chargement..</option>');
}

function changeAll(localite, reparateur, comite, agent, sociologue, directeur){
    select2Change(localite);
    changeLocaliteAll(reparateur, comite, agent);
    select2Change(sociologue);
    select2Change(directeur);
}

function changeLocaliteAll(reparateur, comite, agent){
    select2Change(reparateur);
    select2Change(comite);
    select2Change(agent);
}

function clearAll(localite, reparateur, comite, agent, sociologue, directeur){
    localite.html('');
    clearLocaliteAll(reparateur, comite, agent);
    sociologue.html('');
    directeur.html('');
}

function clearLocaliteAll(reparateur, comite, agent){
    reparateur.html('');
    comite.html('');
    agent.html('');
}