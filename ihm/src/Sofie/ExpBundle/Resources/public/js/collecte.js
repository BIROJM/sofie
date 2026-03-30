/**
 * Created by Eugene on 29/06/2015.
 */
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

    var validated = form.elements.namedItem('form[validated]');
    validated = (validated) ? validated.value : '';
    if(validated != '' || validated.length > 0){
        oParametre['validated'] = validated;
    }

    var operateur = form.elements.namedItem('form[operateur]');
    operateur = (operateur) ? operateur.value : '';
    if(operateur != '' || operateur.length > 0){
        oParametre['operateur'] = operateur;
    }

    var agentSaisie = form.elements.namedItem('form[agentSaisie]');
    agentSaisie = (agentSaisie) ? agentSaisie.value : '';
    if(agentSaisie != '' || agentSaisie.length > 0){
        oParametre['agentSaisie'] = agentSaisie;
    }

    sendQuery(createQueryString(oParametre));
}
