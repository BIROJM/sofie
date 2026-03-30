<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 28/07/2015
 * Time: 16:25
 */

namespace Sofie\ExpBundle\EnumData;


use Doctrine\ORM\EntityManager;

class EnumDataManager
{
    protected $em;
    protected $connection;
    protected $collecteEnumValues = array();
    protected $ouvrageEnumValues = array();

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->connection = $this->em->getConnection();
//        $this->getCollecteEnumValues();
//        $this->getOuvrageEnumValues();
    }

    protected function getEnumValues($table){
        $vals = array();
        $results = $this->connection->query( "SHOW COLUMNS FROM {$table} WHERE Type LIKE 'enum%'" )->fetchAll();
        foreach($results as $result){
            preg_match('/enum\((.*)\)$/', $result['Type'], $matches);
            if(!empty($matches)){
                $vals[$result['Field']] = str_getcsv($matches[1], ",", "'");
                $vals[$result['Field']] = array_combine($vals[$result['Field']], $vals[$result['Field']]);
            }
        }
        return $vals;
    }

    /*
     * Start Table t_collecte
     * */
    public function getCollecteEnumValues()
    {
        $this->collecteEnumValues = $this->getEnumValues('t_collecte');
        return $this;
    }

    public function getEtatOuvrageCollecte()
    {
        if(array_key_exists('EtatOuvrage', $this->collecteEnumValues)){
            return $this->collecteEnumValues['EtatOuvrage'];
        }
        return array();
    }

    public function getCodeCauseSiAbandonCollecte()
    {
        return $this->collecteEnumValues['CodeCauseSiAbandon'];
    }

    public function getPereniteAnneeDsJourneeCollecte()
    {
        return $this->collecteEnumValues['PereniteAnneeDsJournee'];
    }

    public function getEtatAntiBourbierCollecte()
    {
        return $this->collecteEnumValues['EtatAntiBourbier'];
    }

    public function getEtatClotureCollecte()
    {
        return $this->collecteEnumValues['EtatCloture'];
    }

    public function getEtatRigoleEvacuationCollecte()
    {
        return $this->collecteEnumValues['EtatRigoleEvacuation'];
    }

    public function getEtatPuitPerduCollecte()
    {
        return $this->collecteEnumValues['EtatPuitPerdu'];
    }

    public function getSourcePollution1Collecte()
    {
        return $this->collecteEnumValues['SourcePollution1'];
    }

    public function getSourcePollution2Collecte()
    {
        return $this->collecteEnumValues['SourcePollution2'];
    }

    public function getEtatDeferiseurCollecte()
    {
        return $this->collecteEnumValues['EtatDeferiseur'];
    }

    public function getFinancementRemplacementCollecte()
    {
        return $this->collecteEnumValues['FinancementRemplacement'];
    }

    public function getEtatPompeCollecte()
    {
        return $this->collecteEnumValues['EtatPompe'];
    }

    public function getDureePanneCollecte()
    {
        return $this->collecteEnumValues['DureePanne'];
    }

    public function getCauseNonReparationCollecte()
    {
        return $this->collecteEnumValues['CauseNonReparation'];
    }

    public function getTurbiditeCollecte()
    {
        return $this->collecteEnumValues['Turbidite'];
    }

    public function getOdeurEauCollecte()
    {
        return $this->collecteEnumValues['OdeurEau'];
    }

    public function getGoutEauCollecte()
    {
        return $this->collecteEnumValues['GoutEau'];
    }

    public function getModeGestionOuvrageCollecte()
    {
        return $this->collecteEnumValues['ModeGestionOuvrage'];
    }

    public function getModePaiementEauCollecte()
    {
        return $this->collecteEnumValues['ModePaiementEau'];
    }

    public function getCahierEntretientPompeCollecte()
    {
        return $this->collecteEnumValues['CahierEntretientPompe'];
    }

    public function getTypeContratCollecte()
    {
        return $this->collecteEnumValues['TypeContrat'];
    }
    /*
     * End t_collecte
     * */


    /*
     * Start Table t_ouvrage
     * */
    public function getOuvrageEnumValues()
    {
        $this->ouvrageEnumValues = $this->getEnumValues('t_ouvrage');
        return $this;
    }

    public function getTypeOuvrage()
    {
        return $this->ouvrageEnumValues['TypeOuvrage'];
    }

    public function getStatutOuvrage()
    {
        return $this->ouvrageEnumValues['StatutOuvrage'];
    }

    public function getEtatInitialCaptageOuvrage()
    {
        return $this->ouvrageEnumValues['EtatInitialCaptage'];
    }

    public function getProprieteOuvrage()
    {
        return $this->ouvrageEnumValues['Propriete'];
    }

    public function getUsageOuvrage()
    {
        return $this->ouvrageEnumValues['Usage'];
    }
    /*
     * End t_ouvrage
     * */
}