/**
 * Created by Eugene on 29/06/2015.
 */

$(function(){
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
    var numero = (form.elements.namedItem('form[numero]')) ? form.elements.namedItem('form[numero]').value : '';
    var profile = (form.elements.namedItem('form[profile]')) ? parseInt(form.elements.namedItem('form[profile]').value) : 0;
    var acteur = (form.elements.namedItem('form[acteur]'))? parseInt(form.elements.namedItem('form[acteur]').value) : 0;
    if(numero.length > 0){
        oParametre['numero'] = numero;
    }
    if(!isNaN(profile) && profile > 0){
        oParametre['profile'] = profile;
    }
    if(!isNaN(acteur) && acteur > 0){
        oParametre['acteur'] = acteur;
    }
    sendQuery(createQueryString(oParametre));
}