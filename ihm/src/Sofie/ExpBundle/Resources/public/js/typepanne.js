/**
 * Created by Eugene on 29/06/2015.
 */

$(function(){
    critereListener();
});

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

    var libelle = form.elements.namedItem('form[libelle]');
    libelle = (libelle) ? $.trim(libelle.value) : '';
    if(libelle != '' || libelle.length > 0){
        oParametre['libelle'] = libelle;
    }

    sendQuery(createQueryString(oParametre));
}