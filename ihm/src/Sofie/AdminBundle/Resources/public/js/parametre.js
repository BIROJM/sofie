/**
 * Created by Eugene on 29/06/2015.
 */

$(function(){
    ajaxLoadAll();
});

function ajaxLoadAll(){
    ajaxLoadContent($('#appliConfigHtml'), Routing.generate('sofieadmin_param_appliconfig'), appliConfigListener);
    ajaxLoadContent($('#regionsModemConfigHtml'), Routing.generate('sofieadmin_param_regionsmodemconfig'), regionsModemConfigListener);
    setTimeout(function(){
        ajaxLoadContent($('#smsConfigHtml'), Routing.generate('sofieadmin_param_sms_config'), smsConfigListener);
        ajaxLoadContent($('#smsContentConfigHtml'), Routing.generate('sofieadmin_param_sms_content_config'), smsContentConfigListener);
    }, 2000);
    ajaxLoadContent($('#delaisNotifConfigHtml'), Routing.generate('sofieadmin_param_edit_delaisnotification'), delaisNotificationListener);
    setTimeout(function(){
        ajaxLoadContent($('#couleurPanneConfigHtml'), Routing.generate('sofieadmin_param_edit_couleurpanne'), couleurPanneListenner);
        ajaxLoadContent($('#addModeConfigHtml'), Routing.generate('sofieadmin_param_edit_addmode'), editAddModeListener);
    }, 3500);
}

function appliConfigListener(){
    $('#appliConfig').bind({
        submit: function(event){
            event.preventDefault();
            xhrObj.setActive("appliConfig");
            xhrObj.callback = appliConfigListener;
            ajaxFormPostResponseJson(this, Routing.generate('sofieadmin_param_appliconfig'), xhrObj);
            return false;
        }
    });
}

function regionsModemConfigListener(){
    $('#regionsModemConfig').bind({
        submit: function(event){
            event.preventDefault();
            xhrObj.setActive("regionsModemConfig");
            xhrObj.callback = regionsModemConfigListener;
            ajaxFormPostResponseJson(this, Routing.generate('sofieadmin_param_regionsmodemconfig'), xhrObj);
            return false;
        }
    });
}

function regionsConfigListener(){
    $('#regionsConfig').bind({
        submit: function(event){
            event.preventDefault();
            xhrObj.setActive("regionsConfig");
            xhrObj.callback = regionsConfigListener;
            ajaxFormPostResponseJson(this, Routing.generate('sofieadmin_param_regionsconfig'), xhrObj);
            return false;
        }
    });
}

function delaisNotificationListener(){
    $('#editDelaisNotificationForm').bind({
        submit: function(event){
            event.preventDefault();
            xhrObj.setActive("delaisNotif");
            xhrObj.callback = delaisNotificationListener;
            ajaxFormPostResponseJson(this, Routing.generate('sofieadmin_param_edit_delaisnotification'), xhrObj);
            return false;
        }
    });
}

function couleurPanneListenner(){
    $('#editCouleurPanneForm').bind({
        submit: function(event){
            event.preventDefault();
            xhrObj.setActive("couleurPanne");
            xhrObj.callback = couleurPanneListenner;
            ajaxFormPostResponseJson(this, Routing.generate('sofieadmin_param_edit_couleurpanne'), xhrObj);
            return false;
        }
    });

    $('#editCouleurPanneForm select[name^="couleurs"]').bind({
        change: function(){
            var imgChanged = $('#'+$(this).attr('id')+'_imgChanged');
            var imgPath = imgChanged.attr('src').substring(0, imgChanged.attr('src').lastIndexOf('/')+1);
            imgChanged.attr('src', imgPath+$(this).val());
        }
    });
}

function smsConfigListener(){
    $('#smsConfig').bind({
        submit: function(event){
            event.preventDefault();
            xhrObj.setActive("sms");
            xhrObj.callback = smsConfigListener;
            ajaxFormPostResponseJson(this, Routing.generate('sofieadmin_param_sms_config'), xhrObj);
            return false;
        }
    });
}

function editAddModeListener(){
    $('#editAddMode').bind({
        submit: function(event){
            event.preventDefault();
            xhrObj.setActive("addMode");
            xhrObj.callback = editAddModeListener;
            ajaxFormPostResponseJson(this, Routing.generate('sofieadmin_param_edit_addmode'), xhrObj);
            return false;
        }
    });
}

function smsContentConfigListener(){
    $('#smsContentConfig').bind({
        submit: function(event){
            event.preventDefault();
            xhrObj.setActive("smsContent");
            xhrObj.callback = smsContentConfigListener;
            ajaxFormPostResponseJson(this, Routing.generate('sofieadmin_param_sms_content_config'), xhrObj);
            return false;
        }
    });
}