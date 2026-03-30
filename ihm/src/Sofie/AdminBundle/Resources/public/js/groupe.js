/**
 * Created by Eugene on 29/06/2015.
 */

var xhr;

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

    var nom = form.elements.namedItem('form[nom]');
    nom = (nom) ? $.trim(nom.value) : '';
    if(nom != '' || nom.length > 0){
        oParametre['nom'] = nom;
    }

    sendQuery(createQueryString(oParametre));
}