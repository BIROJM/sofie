/**
 * Created by Eugene on 09/06/2015.
 */

var xhrObj = {
    active: null,
    setActive: function(elt){
        this.active = "this."+elt;
    },
    tryAbort: function(){
        var current = eval(this.active);
        if(current && current.readyState && current.readyState != 4){
            current.abort();
        }
    },
    setXhr: function(xhr){
        eval(this.active+" = xhr");
    }
};

$(function(){
    $('.ajax-loader').removeClass('hidden').hide();
    $('.preloader').hide();
    backLocation();
    allDatePickers();
    createSelect2();
    autoCloseFlashBag();
    confirmDelete();
    customSwitch();
});

function allDatePickers(){
    $('.datetime-picker').datetimepicker({});
    $('.time-picker').timepicker({
        hourGrid: 4,
        minuteGrid: 10,
        timeFormat: 'hh:mm tt'
    });
    //$('#date3_example').datepicker({ numberOfMonths: 3, showButtonPanel: true});
    //$('#date3-1_example').datepicker({ numberOfMonths: 3, showButtonPanel: true});
    $('.date-picker').datepicker({
        showButtonPanel: true
    });
}

// AJAX LOADER GIF
function chowAjaxLoader()
{
    $('.ajax-loader').show();
}

function hideAjaxLoader()
{
    $('.ajax-loader').hide();
}

// SweetAlert
function swalSuccess(msg){
    if(msg == null){
        msg = ''
    }
    swal({
        title: '',
        text: msg,
        type: 'success',
        timer: 3000,
        showConfirmButton: false,
        html: true,
        allowOutsideClick: true
    });
}
function swalError(msg){
    if(msg == null){
        msg = ''
    }
    swal({
        title: '',
        text: msg,
        type: 'error',
        showConfirmButton: false,
        showCancelButton: true,
        cancelButtonText: "Fermer",
        html: true,
        allowOutsideClick: true
    });
}
function swalWarning(msg){
    if(msg == null){
        msg = ''
    }
    swal({
        title: '',
        text: msg,
        type: 'warning',
        showConfirmButton: false,
        showCancelButton: true,
        cancelButtonText: "Fermer",
        html: true,
        allowOutsideClick: true
    });
}
function swalLoading(){
    swal({
        title: '',
        text: 'Traitement en cours...',
        imageUrl: base_asset+"img/ajax_loader2.gif",
        imageSize: "48x48",
        html: true,
        showConfirmButton: false
    });
}
function swalClose()
{
    swal({timer: 5});
}
function swalConfirmWarning(text, callback){
    swal({
        title: '',
        html: text,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Oui",
        cancelButtonText: "Non",
        closeOnConfirm: true
    },
    function(isConfirm){
        if(isConfirm){
            eval(callback);
        }
    });
}

// CKeditor
function CKupdate(){
    for ( instance in CKEDITOR.instances ){
        CKEDITOR.instances[instance].updateElement();
    }
}

// Retour sur la page précédente
function backLocation(){
    $('.back-location').bind({
        click: function(){
            window.history.back();
        }
    });
}

// Search
function sendQuery(sData){
    window.location.search = sData;
}

function getQueryObjet(){
    var oParametre = {};

    if (window.location.search.length > 1) {
        for (var aItKey, nKeyId = 0, aCouples = window.location.search.substr(1).split("&"); nKeyId < aCouples.length; nKeyId++) {
            aItKey = aCouples[nKeyId].split("=");
            oParametre[decodeURIComponent(aItKey[0])] = aItKey.length > 1 ? decodeURIComponent(aItKey[1]) : "";
        }
    }
    return oParametre;
}

function getQueryString(oParametre){
    var query = '';
    for(param in oParametre){
        if(query != ''){
            query += '&'
        }
        query += param+'='+oParametre[param];
    }
    return query;
}

function createQueryString(oParametre){
    var query = '';
    for(param in oParametre){
        if(query != ''){
            query += '&'
        }
        query += encodeURIComponent(param)+'='+encodeURIComponent(oParametre[param]);
    }
    return query;
}

/* Select 2 */
function createSelect2(){
    $('.select2, .inline-select2').select2({allowClear: true, placeholder: '', width:'style'});
    $('.simple-select2').select2({width:'style'});
}

/* Date Time */
function validDate(text) {
    var date = Date.parse(text);
    if (isNaN(date)) {
        return false;
    }
    return true;
}

function dateRange(start, end){
    $( start ).datepicker({
        defaultDate: "+1w",
        dateFormat: 'dd/mm/yy',
        //changeMonth: true,
        //numberOfMonths: 3,
        onClose: function( selectedDate ) {
            $( end ).datepicker( "option", "minDate", selectedDate );
        }
    });
    $( end ).datepicker({
        defaultDate: "+1w",
        dateFormat: 'dd/mm/yy',
        //changeMonth: true,
        //numberOfMonths: 3,
        onClose: function( selectedDate ) {
            $( start ).datepicker( "option", "maxDate", selectedDate );
        }
    });
}

/* Flash */
function autoCloseFlashBag()
{
    setTimeout(function(){
        $('#flash_render').empty().css({display: 'none'});
    }, 60000);
}

/* Scripts de confirmation */
function confirmAction(elt, msg){
    $(elt).bind({
       click: function(){
           if(!confirm(msg)){
               return false;
           }
       }
    });
}

function confirmDelete(){
    confirmAction($('.confirm-delete'), 'Êtes-vous certain de vouloir supprimer cet élément ?');
}


/*
* Script for select2 manipulation
* */
function select2Change(elt){
    $(elt).trigger('change', [true]);
}

function selectIHMChange(elt){
    $(elt).trigger('change', [true, true]);
}

/* Open BootstrapModalBox */
function OpenBootModalBox(header, body, bottom){
    var bootmodalbox = $('#bootmodalbox');
    bootmodalbox.find('.modal-title').html(header);
    bootmodalbox.find('.modal-body').html(body);
    bootmodalbox.find('.modal-footer').html(bottom);
    bootmodalbox.on('hidden.bs.modal', function(e){
        bootmodalbox.find('.modal-title').children().remove();
        bootmodalbox.find('.modal-body').children().remove();
        bootmodalbox.find('.modal-footer').children().remove();
    });
    bootmodalbox.modal('show');
    return bootmodalbox;
}
//
//  Close BootstrapModalBox
function CloseBootModalBox(){
    $('#bootmodalbox').modal('hide');
}

/* bootstrap switch */
function customSwitch(){
    bonMauvais();
    bonneMauvaise();
}
function bonMauvais()
{
    $('.bon-mauvais-switch').bootstrapSwitch({
        size: 'mini',
        onColor: 'success',
        offColor: 'danger',
        onText: 'Bon',
        offText: 'Mauvais'
    });
}
function bonneMauvaise()
{
    $('.bonne-mauvaise-switch').bootstrapSwitch({
        size: 'mini',
        onColor: 'success',
        offColor: 'danger',
        onText: 'Bonne',
        offText: 'Mauvaise'
    });
}

/*
* AJAX Functions
* */
function ajaxFormPostResponseJson(f, path, obj){
    obj.tryAbort();
    swalLoading();
    xhr = $.ajax({
        'url': path,
        'method': 'POST',
        'data':$(f).serialize(),
        'dataType': 'json'
    }).success(function(data){
        if(data.success){
            swalSuccess('Succès !');
        }else{
            swalError('Erreur survenue !');
        }
        if(data.form){
            $(f).replaceWith(data.form);
            if(obj.callback) obj.callback();
            if(!data.success){
                swalClose();
            }
        }
    }).error(function(jqXHR, textStatus, errorThrown){
        swalError(errorThrown);
    });
    obj.setXhr(xhr);
}

function ajaxLoadContent(elt, path, callback){
    $(elt).load(path, function(){
        if(callback)callback();
    });
}

/**
 * Autres fonctions
 */

function onChangeCheckAndDisabled(elt, objects, values){
    checkAndDisabledForSelect(elt, objects, values);
    $(elt).bind({
        change: function(event){
            checkAndDisabledForSelect(elt, objects, values);
        },
        select: function(){
            checkAndDisabledForSelect(elt, objects, values);
        }
    });
}

function checkAndDisabledForSelect(elt, objects, values){
    objects = (objects) ? objects : [];
    values = (values) ? values : [];
    if(~values.indexOf($(elt).val())){
        checkAndDisabled(objects);
    }else{
        undisabled(objects);
    }
}

function checkAndDisabled(objects){
    objects = (objects) ? objects : [];
    for(var i=0; i < objects.length; i++){
        $(objects[i]).prop('checked', true).prop('disabled', true);
    }
}

function undisabled(objects){
    objects = (objects) ? objects : [];
    for(var i=0; i < objects.length; i++){
        $(objects[i]).prop('disabled', false);
    }
}

/**
 * Notify
*/

function configNotify(msg, path){
    $.notify({
            icon: 'fa fa-warning',
            message: msg,
            url: path,
            target: '_self'
        },
        {
            type: 'danger',
            placement: {
                from: 'top',
                align: 'center'
            },
            delay: 0
        }
    );
}

/**
 * JjQuery daterangepicker
* */

function classicDaterangepicker(elt){
    $(elt).daterangepicker({
        presetRanges: [
            {text: 'Aujourd\'ui', dateStart: function() { return moment() }, dateEnd: function() { return moment() } },
            {text: 'Hier', dateStart: function() { return moment().subtract(1, 'days') }, dateEnd: function() { return moment().subtract(1, 'days') } },
            {text: 'Les 7 derniers jours', dateStart: function() { return moment().subtract(6, 'days') }, dateEnd: function() { return moment() } },
            {text: 'La semaine passée', dateStart: function() { return moment().subtract(7, 'days').isoWeekday(1) }, dateEnd: function() { return moment().subtract(7, 'days').isoWeekday(7) } },
            {text: 'Le mois en cours', dateStart: function() { return moment().startOf('month') }, dateEnd: function() { return moment() } },
            {text: 'Le mois passé', dateStart: function() { return moment().subtract(1, 'month').startOf('month') }, dateEnd: function() { return moment().subtract(1, 'month').endOf('month') } },
            {text: 'L\'année en cours', dateStart: function() { return moment().startOf('year') }, dateEnd: function() { return moment() } }
        ],
        initialText: "Selectionnez un intervalle...",
        applyButtonText: 'Appliquer',
        clearButtonText: 'Effacer',
        cancelButtonText: 'Annuler',
        rangeSplitter: ' - ', // string to use between dates
        dateFormat: 'dd M yy', // displayed date format. Available formats: http://api.jqueryui.com/datepicker/#utility-formatDate
        altFormat: 'yy-mm-dd', // submitted date format - inside JSON {"start":"...","end":"..."}
        datepickerOptions : {
            numberOfMonths : 2
        }
    });
}