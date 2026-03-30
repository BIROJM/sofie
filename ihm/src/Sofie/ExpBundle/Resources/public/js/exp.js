/**
 * Created by Eugene on 30/06/2015.
 */

var xhr;


/* Validate */
function validateActionListener(){
    $('td input[name="action_validated"]').bind({
        change: function(event, computer){
            if(!computer){
                validateAction(this);
            }
            return false;
        }
    });

    $('*[id^=action_validated]').bind({
        click: function(event, computer){
            if(!computer){
                var msg = 'Êtes-vous certain de vouloir';
                if($(this).hasClass('fa-toggle-on')){
                    msg += ' dévalidé ';
                }else{
                    msg += ' validé ';
                }
                msg += '?';
                if(confirm(msg)){
                    validateAction(this);
                }
            }
            return false;
        }
    });
}

function validateAction(elt){
    //swalLoading();
    var currentClass = $(elt).attr('class');
    var spinnerClass = 'fa fa-spinner fa-pulse text-primary cursor-pointer';
    var validatedClass = 'fa fa-toggle-on fa-lg text-success cursor-pointer';
    var noValidatedClass = 'fa fa-toggle-off fa-lg text-danger cursor-pointer';
    $(elt).attr('class', spinnerClass);
    xhr = $.ajax({
        method: 'GET',
        url: $(elt).data('href'),
        async: false,
        dataType: 'json'
    }).done(function(response){
        if(response.success){
            if(response.validated){
                $(elt).attr('class', validatedClass);
            }else{
                $(elt).attr('class', noValidatedClass);
            }
        }else{
            swalWarning(response.message);
            $(elt).attr('class', currentClass);
        }
    }).fail(function(){
        swalError('Erreur survenue.');
        $(elt).attr('class', currentClass);
    });
}

/* Load localite by region */
function regionListener(region, localite) {
    $(region).bind({
        change: function(){
            var id = parseInt($(this).val(), 10);
            loadLocaliteByRegion((isNaN(id))?0:id, localite);
        }
    });
}

function loadLocaliteByRegion(idRegion, localite) {
    if(xhr && xhr.readyState != 4){
        xhr.abort();
    }
    if(idRegion <= 0){
        localite.html('<option></option>');
        select2Change(localite);
        return;
    }
    $(localite).html('<option>chargement...</option>');
    $(localite).attr('disabled', 'disabled');
    select2Change(localite);
    chowAjaxLoader();
    xhr = $.ajax({
        url: Routing.generate('sofieexp_localite_ajax_by_region', {'id':parseInt(idRegion, 10)}),
        method: 'GET'
    }).done(function(response){
        $(localite).html(response);
       /* if(localite.hasClass('select2')){
            alert('ok');
            createSelect2();
        }else{
            alert('non');
        }*/
    }).fail(function(){
        $(localite).html('<option></option>');
    }).always(function(){
        $(localite).removeAttr('disabled');
        select2Change(localite);
        hideAjaxLoader();
    });
}

/* Load comite by localite form */
function localiteListener(localite, comite) {
    $(localite).bind({
        change: function(){
            var id = parseInt($(this).val(), 10);
            loadComiteByLocalite((isNaN(id))?0:id, comite);
        }
    });
}
function loadComiteByLocalite(idLocalite, comite) {
    if(xhr && xhr.readyState != 4){
        xhr.abort();
    }
    if(idLocalite <= 0){
        comite.html('<option></option>');
        select2Change(comite);
        return;
    }
    $(comite).html('<option>chargement...</option>');
    $(comite).attr('disabled', 'disabled');
    select2Change(comite);
    chowAjaxLoader();
    xhr = $.ajax({
        url: Routing.generate('sofieexp_comite_ajax_by_localite', {'id':parseInt(idLocalite, 10)}),
        method: 'GET'
    }).done(function(response){
        $(comite).html(response);
    }).fail(function(){
        $(comite).html('<option></option>');
    }).always(function(){
        $(comite).removeAttr('disabled');
        select2Change(comite);
        hideAjaxLoader();
    });
}


function ajaxLoadBySelect(changed, route, options){
    if(xhr && xhr.readyState != 4){
        xhr.abort();
    }
    $(changed).html('<option>chargement...</option>');
    $(changed).attr('disabled', 'disabled');
    select2Change(changed);
    chowAjaxLoader();
    xhr = $.ajax({
        url: route,
        method: 'GET'
    }).done(function(response){
        $(changed).html(response);
    }).fail(function(){
        $(changed).html('<option></option>');
    }).always(function(){
        $(changed).removeAttr('disabled');
        select2Change(changed);
        hideAjaxLoader();
    });
}

/**
 * init profile
* */
function initProfileListener()
{
    $('*[id^=action_initialize]').bind({
        click: function(event, computer){
            if(!computer){
                var number='';
                if($(this).hasClass('fa-toggle-on')){
                    if(confirm('Êtes-vous certain de vouloir annuler l\'initialisation ?')){
                        initProfile(this, number);
                    }
                }else{
                    var msg = 'Entrez un numéro de téléphone valide';
                    number = prompt(msg);
                    while(!(/^(\d|\+)\d{7,14}[\s|,;-]*$/.test(number)) && number!==null){
                        number = prompt(msg);
                    }
                    if(number === null){
                        return false;
                    }else{
                        initProfile(this, number);
                    }
                }
            }
            return false;
        }
    });
}

function initProfile(elt, number){
    var currentClass = $(elt).attr('class');
    var spinnerClass = 'fa fa-spinner fa-pulse text-primary cursor-pointer';
    var initClass = 'fa fa-toggle-on fa-lg text-success cursor-pointer';
    var noInitClass = 'fa fa-toggle-off fa-lg text-danger cursor-pointer';
    $(elt).attr('class', spinnerClass);
    xhr = $.ajax({
        method: 'POST',
        url: $(elt).data('href'),
        data: 'numero='+number,
        async: false,
        dataType: 'json'
    }).done(function(response){
        if(response.success){
            if(response.initialize){
                $(elt).attr('class', initClass);
            }else{
                $(elt).attr('class', noInitClass);
            }
            $(elt).closest('tr').find('.profile-number').html(response.numero);
        }else{
            swalWarning(response.message);
            $(elt).attr('class', currentClass);
        }
    }).fail(function(){
        swalError('Erreur survenue.');
        $(elt).attr('class', currentClass);
    });
}