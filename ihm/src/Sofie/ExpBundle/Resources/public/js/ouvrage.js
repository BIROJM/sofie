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

    var localite = form.elements.namedItem('form[localite]');
    localite = (localite)?parseInt(localite.value, 10):0;
    localite = (isNaN(localite))?0:localite;
    if(parseInt(localite) > 0){
        oParametre['localite'] = localite
    }

    var statut = form.elements.namedItem('form[statutOuvrage]');
    statut = (statut) ? statut.value : '';
    if(statut != '' || statut.length > 0){
        oParametre['statut'] = statut;
    }

    var code = form.elements.namedItem('form[code]');
    code = (code) ? code.value : '';
    if(code != '' || code.length > 0){
        oParametre['code'] = code;
    }

    var numIRH = form.elements.namedItem('form[numIRH]');
    numIRH = (numIRH) ? numIRH.value : '';
    if(numIRH != '' || numIRH.length > 0){
        oParametre['numIRH'] = numIRH;
    }

    var validated = form.elements.namedItem('form[validated]');
    validated = (validated) ? validated.value : '';
    if(validated != '' || validated.length > 0){
        oParametre['validated'] = validated;
    }

    sendQuery(createQueryString(oParametre));
}

function localiteForFreeComiteListener(localite, comite){
    $(localite).bind({
        change: function(){
            if(xhr && xhr.readyState != 4){
                xhr.abort();
            }
            var id = parseInt($(this).val(), 10);
            if(isNaN(id) || id == 0){
                comite.html('<option></option>');
                select2Change(comite);
                return;
            }
            var route = Routing.generate('sofieexp_comite_ajax_by_localite_and_free', {
                'id':id, 'current':$(comite).data('current-id')
            });
            ajaxLoadBySelect(comite, route, []);
        }
    });
}

/* view appels by pannes */
var xhrAppels;
function panneAppelsListener(elt)
{
    $(elt).click(function(event){
        panneAppelsView($(this).data('href'));
        event.preventDefault();
    });
}

function panneAppelsView(path){
    if(xhrAppels && xhrAppels.readyState != 4){
        xhrAppels.abort();
    }
    swalLoading();
    xhrAppels = $.ajax({
        url: path,
        dataType: 'json'
    }).done(function(response){
        OpenModalBox(response.title, response.content);
    }).always(function(){
        swalClose();
    });
}

/* view notifications by pannes */
var xhrNotifications;
function panneNotificationsListener(elt)
{
    $(elt).click(function(event){
        panneNotificationsView($(this).data('href'));
        event.preventDefault();
    });
}

function panneNotificationsView(path){
    if(xhrNotifications && xhrNotifications.readyState != 4){
        xhrNotifications.abort();
    }
    swalLoading();
    xhrNotifications = $.ajax({
        url: path,
        dataType: 'json'
    }).done(function(response){
        OpenModalBox(response.title, response.content);
    }).always(function(){
        swalClose();
    });
}


/* Edit type pannes by panne */
var xhrTypePanne;
var col;
function typePanneListener(typePanne){
    $(typePanne).click(function(event){
        col = $(this).prev('.content-type-panne');
        editTypePanne($(this).data('href'), 'GET', '');
        event.preventDefault();
    });
}

function typePanneFormListener(f){
    $(f).submit(function(){
        editTypePanne($(this).attr('action'), 'POST', $(this).serialize());
        return false;
    });
}

function editTypePanne(path, method, data){
    if(xhrTypePanne && xhrTypePanne.readyState != 4){
        xhrTypePanne.abort();
    }
    swalLoading();
    xhrTypePanne = $.ajax({
        method: method,
        url: path,
        data: data,
        dataType: 'json'
    }).done(function(response){
        CloseBootModalBox();
        if(response.success){
            col.html(response.content);
            col = null;
            swalSuccess('Succès !');
        }else{
            var footerModal = '';
            if(response.msg){
                footerModal = '<div class="text-danger">'+ response.msg +'</div>';
            }
           OpenBootModalBox(response.title, response.content, footerModal).on('shown.bs.modal', function (e) {
                createSelect2();
            });
        }
    }).fail(function(){
        CloseBootModalBox();
    }).always(function(){
        swalClose();
    });
}

function prototypesListeners()
{
    // Coupes géologiques
    $('#add_coupe').click(function(event){
        addNewRow($('#coupes'));
        event.preventDefault();
    });
    // Equipements de forages
    $('#add_equip_forage').click(function(event){
        addNewRow($('#equip_forages'));
        event.preventDefault();
    });
    // Essais de pompages
    $('#add_essai_pompage').click(function(event){
        addNewRow($('#essais_pompage'));
        event.preventDefault();
    });
    // Suivis physicochimiques
    $('#add_suivi_physico').click(function(event){
        addNewRow($('#suivis_physico'));
        event.preventDefault();
    });
    // Suivis physicochimiques
    $('#add_venu_eau').click(function(event){
        addNewRow($('#venus_eau'));
        event.preventDefault();
    });
    removeRow();
}

function addNewRow(elt){
    var prototype = $(elt).data('prototype').replace(/__name__/g, $(elt).children().length);
    var newForm = $.parseHTML(prototype);
    var newRow = document.createElement('tr');
    $(newForm).find('*[name]').each(function(){
        var newCol = document.createElement('td');
        $(this).addClass('form-control');
        newCol.appendChild(this);
        newRow.appendChild(newCol);
    });
    addRemoveRow(newRow);
    $(elt)[0].appendChild(newRow);
    removeRow();
    allDatePickers();
}

function addRemoveRow(row){
    var col = document.createElement('td');
    $(col).append('<a href="#" class="remove-row"><i class="fa fa-minus-square text-danger"></i></a>');
    $(row)[0].appendChild(col);

}

function removeRow()
{
    $('.remove-row').click(function(event){
        $(this).closest('tr').remove();
        event.preventDefault();
    });
}