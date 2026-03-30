<?php

namespace Sofie\ExpBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Sofie\AdminBundle\Model\ParameterFile;
use Sofie\ExpBundle\EnumData\EnumDataManager;

/**
 * Ouvrage
 */
class Ouvrage
{
    // statut Ouvrage
    const PANNE = 'Panne';
    const MARCHE = 'Marche';

    // Type Ouvrage
    const TYPE_FORAGE = 'Forage';
    const TYPE_PUIT = 'Puits';

    const SYNC = 'Y';
    const NO_SYNC = 'N';

    const VALIDATED_WORD = 'Validé';
    const NOT_VALIDATED_WORD = 'Non validé';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $statutOuvrage;

    /**
     * @var integer
     */
    private $statut;

    /**
     * @var integer
     */
    private $statutPanne;

    /**
     * @var \DateTime
     */
    private $dateMiseAJour;

    /**
     * @var string
     */
    private $latitude;

    /**
     * @var string
     */
    private $longitude;

    /**
     * @var string
     */
    private $altitude;

    /**
     * @var integer
     */
    private $idRegion;

    /**
     * @var integer
     */
    private $idReparateur;

    /**
     * @var integer
     */
    private $idAgentforma;

    /**
     * @var string
     */
    private $iddr;

    /**
     * @var \DateTime
     */
    private $dateStatut;

    /**
     * @var \Sofie\ExpBundle\Entity\CoupeGeologique
     */
    private $coupeGeologiques;

    /**
     * @var \Sofie\ExpBundle\Entity\EquipementForage
     */
    private $equipementForages;

    /**
     * @var \Sofie\ExpBundle\Entity\EssaisPompage
     */
    private $essaisPompages;

    /**
     * @var \Sofie\ExpBundle\Entity\SuiviPhysicoChimique
     */
    private $suiviPhysicoChimiques;

    /**
     * @var Collection
     */
    private $pannes;

    /**
     * @var Collection
     */
    private $collectes;

    /**
     * @var \Sofie\ExpBundle\Entity\Comite
     */
    private $comite;

    /**
     * @var \Sofie\ExpBundle\Entity\Localite
     */
    private $localite;

    /**
     * @var string
     */
    private $origin;

    /**
     * @var boolean
     */
    private $validated;

    /**
     * @var string
     */
    private $numIRH;

    /**
     * @var string
     */
    private $autreNumId;

    /**
     * @var string
     */
    private $designation;

    /**
     * @var string
     */
    private $operateurSaisie;

    /**
     * @var \DateTime
     */
    private $dateSaisie;

    /**
     * @var string
     */
    private $numLocaliteProgres;

    /**
     * @var string
     */
    private $etatInitialCaptage;

    /**
     * @var string
     */
    private $propriete;

    /**
     * @var string
     */
    private $usage;

    /**
     * @var string
     */
    private $nomDuProjet;

    /**
     * @var string
     */
    private $financement;

    /**
     * @var string
     */
    private $ingenieurConseil;

    /**
     * @var string
     */
    private $entreprise;

    /**
     * @var \DateTime
     */
    private $dateFinForation;

    /**
     * @var string
     */
    private $debit;

    /**
     * @var string
     */
    private $profondeurTotale;

    /**
     * @var string
     */
    private $profondeurEquipee;

    /**
     * @var string
     */
    private $niveauStatique;

    /**
     * @var \DateTime
     */
    private $dateNs;

    /**
     * @var string
     */
    private $geomorphologie;

    /**
     * @var string
     */
    private $epaisseurAlteration;

    /**
     * @var string
     */
    private $nomAquifere;

    /**
     * @var string
     */
    private $lithologieAquifere;

    /**
     * @var string
     */
    private $profondeurToit;

    /**
     * @var string
     */
    private $profondeurMur;

    /**
     * @var \DateTime
     */
    private $datePrelevement;

    /**
     * @var string
     */
    private $temperature;

    /**
     * @var string
     */
    private $conductivite;

    /**
     * @var string
     */
    private $ph;

    /**
     * @var string
     */
    private $ferTotal;

    /**
     * @var string
     */
    private $nitrates;

    /**
     * @var string
     */
    private $couleur;

    /**
     * @var string
     */
    private $turbidite;

    /**
     * @var string
     */
    private $marquePompe;

    /**
     * @var \DateTime
     */
    private $dateInstallPompe;

    /**
     * @var string
     */
    private $profondeurInstallPompe;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \DateTime
     */
    private $validatedAt;

    /**
     * @var string
     */
    private $sync;

    /**
     * @var \Sofie\ExpBundle\Entity\Agent
     */
    private $validatedBy;

    /**
     * @var string
     */
    private $prefecture;

    /**
     * @var string
     */
    private $canton;

    /**
     * @var \DateTime
     */
    private $deletedAt;

    /**
     * @var Collection
     */
    private $venuEauPrincipales;

    /**
     * @var string
     */
    private $quartier;


    /**
     * @var string
     */
    private $codeOuvrageProgres;

    /**
     * @var EnumDataManager
     */
    private static $edm;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->coupeGeologiques = new ArrayCollection();
        $this->equipementForages = new ArrayCollection();
        $this->essaisPompages = new ArrayCollection();
        $this->suiviPhysicoChimiques = new ArrayCollection();
        $this->pannes = new ArrayCollection();
        $this->collectes = new ArrayCollection();
        $this->sync = static::NO_SYNC;
        $this->origin = 'I';
        $this->venuEauPrincipales = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Ouvrage
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Ouvrage
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set statutOuvrage
     *
     * @param string $statutOuvrage
     * @return Ouvrage
     */
    public function setStatutOuvrage($statutOuvrage)
    {
        $this->statutOuvrage = $statutOuvrage;

        return $this;
    }

    /**
     * Get statutOuvrage
     *
     * @return string 
     */
    public function getStatutOuvrage()
    {
        return $this->statutOuvrage;
    }

    /**
     * Set statut
     *
     * @param integer $statut
     * @return Ouvrage
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return integer
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set statutPanne
     *
     * @param integer $statutPanne
     * @return Ouvrage
     */
    public function setStatutPanne($statutPanne)
    {
        $this->statutPanne = $statutPanne;

        return $this;
    }

    /**
     * Get statutPanne
     *
     * @return integer
     */
    public function getStatutPanne()
    {
        return $this->statutPanne;
    }

    /**
     * Set dateMiseAJour
     *
     * @param \DateTime $dateMiseAJour
     * @return Ouvrage
     */
    public function setDateMiseAJour($dateMiseAJour)
    {
        $this->dateMiseAJour = $dateMiseAJour;

        return $this;
    }

    /**
     * Get dateMiseAJour
     *
     * @return \DateTime 
     */
    public function getDateMiseAJour()
    {
        return $this->dateMiseAJour;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Ouvrage
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return Ouvrage
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set altitude
     *
     * @param string $altitude
     * @return Ouvrage
     */
    public function setAltitude($altitude)
    {
        $this->altitude = $altitude;

        return $this;
    }

    /**
     * Get altitude
     *
     * @return string
     */
    public function getAltitude()
    {
        return $this->altitude;
    }

    /**
     * Set idRegion
     *
     * @param integer $idRegion
     * @return Ouvrage
     */
    public function setIdRegion($idRegion)
    {
        $this->idRegion = $idRegion;

        return $this;
    }

    /**
     * Get idRegion
     *
     * @return integer 
     */
    public function getIdRegion()
    {
        return $this->idRegion;
    }

    /**
     * Set idReparateur
     *
     * @param integer $idReparateur
     * @return Ouvrage
     */
    public function setIdReparateur($idReparateur)
    {
        $this->idReparateur = $idReparateur;

        return $this;
    }

    /**
     * Get idReparateur
     *
     * @return integer 
     */
    public function getIdReparateur()
    {
        return $this->idReparateur;
    }

    /**
     * Set idAgentforma
     *
     * @param integer $idAgentforma
     * @return Ouvrage
     */
    public function setIdAgentforma($idAgentforma)
    {
        $this->idAgentforma = $idAgentforma;

        return $this;
    }

    /**
     * Get idAgentforma
     *
     * @return integer 
     */
    public function getIdAgentforma()
    {
        return $this->idAgentforma;
    }

    /**
     * Set iddr
     *
     * @param string $iddr
     * @return Ouvrage
     */
    public function setIddr($iddr)
    {
        $this->iddr = $iddr;

        return $this;
    }

    /**
     * Get iddr
     *
     * @return string
     */
    public function getIddr()
    {
        return $this->iddr;
    }

    /**
     * Set dateStatut
     *
     * @param \DateTime $dateStatut
     * @return Ouvrage
     */
    public function setDateStatut($dateStatut)
    {
        $this->dateStatut = $dateStatut;

        return $this;
    }

    /**
     * Get dateStatut
     *
     * @return \DateTime 
     */
    public function getDateStatut()
    {
        return $this->dateStatut;
    }

    /**
     * Add pannes
     *
     * @param \Sofie\ExpBundle\Entity\Panne $pannes
     * @return Ouvrage
     */
    public function addPanne(\Sofie\ExpBundle\Entity\Panne $pannes)
    {
        $this->pannes[] = $pannes;

        return $this;
    }

    /**
     * Remove pannes
     *
     * @param \Sofie\ExpBundle\Entity\Panne $pannes
     */
    public function removePanne(\Sofie\ExpBundle\Entity\Panne $pannes)
    {
        $this->pannes->removeElement($pannes);
    }

    /**
     * Get pannes
     *
     * @return Collection
     */
    public function getPannes()
    {
        return $this->pannes;
    }

    /**
     * Add collectes
     *
     * @param \Sofie\ExpBundle\Entity\Collecte $collectes
     * @return Ouvrage
     */
    public function addCollecte(\Sofie\ExpBundle\Entity\Collecte $collectes)
    {
        $this->collectes[] = $collectes;

        return $this;
    }

    /**
     * Remove collectes
     *
     * @param \Sofie\ExpBundle\Entity\Collecte $collectes
     */
    public function removeCollecte(\Sofie\ExpBundle\Entity\Collecte $collectes)
    {
        $this->collectes->removeElement($collectes);
    }

    /**
     * Get collectes
     *
     * @return Collection
     */
    public function getCollectes()
    {
        return $this->collectes;
    }

    /**
     * Set comite
     *
     * @param \Sofie\ExpBundle\Entity\Comite $comite
     * @return Ouvrage
     */
    public function setComite(\Sofie\ExpBundle\Entity\Comite $comite = null)
    {
        $this->comite = $comite;

        return $this;
    }

    /**
     * Get comite
     *
     * @return \Sofie\ExpBundle\Entity\Comite 
     */
    public function getComite()
    {
        return $this->comite;
    }

    /**
     * Set localite
     *
     * @param \Sofie\ExpBundle\Entity\Localite $localite
     * @return Ouvrage
     */
    public function setLocalite(\Sofie\ExpBundle\Entity\Localite $localite = null)
    {
        $this->localite = $localite;

        return $this;
    }

    /**
     * Get localite
     *
     * @return \Sofie\ExpBundle\Entity\Localite 
     */
    public function getLocalite()
    {
        return $this->localite;
    }
    /**
     * @var integer
     */
    private $idSociologue;


    /**
     * Set idSociologue
     *
     * @param integer $idSociologue
     * @return Ouvrage
     */
    public function setIdSociologue($idSociologue)
    {
        $this->idSociologue = $idSociologue;

        return $this;
    }

    /**
     * Get idSociologue
     *
     * @return integer 
     */
    public function getIdSociologue()
    {
        return $this->idSociologue;
    }

    public function getRegion()
    {
        if(is_object($this->localite)){
            return $this->localite->getRegion();
        }
        return null;
    }

    public function optimizedValue()
    {
        if(is_object($this->localite)){
            if(is_object($this->localite->getRegion())){
                $this->idRegion = $this->getRegion()->getId();
            }
            if(is_object($this->localite->getAgentForma())){
                $this->idAgentforma = $this->localite->getAgentForma()->getId();
            }
            if(is_object($this->localite->getReparateur())){
                $this->idReparateur = $this->localite->getReparateur()->getId();
            }
        }
    }

    /**
     * Set origin
     *
     * @param string $origin
     * @return Ouvrage
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Get origin
     *
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Set validated
     *
     * @param boolean $validated
     * @return Ouvrage
     */
    public function setValidated($validated)
    {
        $this->validated = $validated;

        return $this;
    }

    /**
     * Get validated
     *
     * @return boolean 
     */
    public function getValidated()
    {
        return $this->validated;
    }

    /**
     * Set numIRH
     *
     * @param string $numIRH
     * @return Ouvrage
     */
    public function setNumIRH($numIRH)
    {
        $this->numIRH = $numIRH;

        return $this;
    }

    /**
     * Get numIRH
     *
     * @return string 
     */
    public function getNumIRH()
    {
        return $this->numIRH;
    }

    /**
     * Set autreNumId
     *
     * @param string $autreNumId
     * @return Ouvrage
     */
    public function setAutreNumId($autreNumId)
    {
        $this->autreNumId = $autreNumId;

        return $this;
    }

    /**
     * Get autreNumId
     *
     * @return string 
     */
    public function getAutreNumId()
    {
        return $this->autreNumId;
    }

    /**
     * Set designation
     *
     * @param string $designation
     * @return Ouvrage
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * Get designation
     *
     * @return string 
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * Set operateurSaisie
     *
     * @param string $operateurSaisie
     * @return Ouvrage
     */
    public function setOperateurSaisie($operateurSaisie)
    {
        $this->operateurSaisie = $operateurSaisie;

        return $this;
    }

    /**
     * Get operateurSaisie
     *
     * @return string 
     */
    public function getOperateurSaisie()
    {
        return $this->operateurSaisie;
    }

    /**
     * Set dateSaisie
     *
     * @param \DateTime $dateSaisie
     * @return Ouvrage
     */
    public function setDateSaisie($dateSaisie)
    {
        $this->dateSaisie = $dateSaisie;

        return $this;
    }

    /**
     * Get dateSaisie
     *
     * @return \DateTime 
     */
    public function getDateSaisie()
    {
        return $this->dateSaisie;
    }

    /**
     * Set numLocaliteProgres
     *
     * @param string $numLocaliteProgres
     * @return Ouvrage
     */
    public function setNumLocaliteProgres($numLocaliteProgres)
    {
        $this->numLocaliteProgres = $numLocaliteProgres;

        return $this;
    }

    /**
     * Get numLocaliteProgres
     *
     * @return string 
     */
    public function getNumLocaliteProgres()
    {
        return $this->numLocaliteProgres;
    }

    /**
     * Set etatInitialCaptage
     *
     * @param string $etatInitialCaptage
     * @return Ouvrage
     */
    public function setEtatInitialCaptage($etatInitialCaptage)
    {
        $this->etatInitialCaptage = $etatInitialCaptage;

        return $this;
    }

    /**
     * Get etatInitialCaptage
     *
     * @return string 
     */
    public function getEtatInitialCaptage()
    {
        return $this->etatInitialCaptage;
    }

    /**
     * Set propriete
     *
     * @param string $propriete
     * @return Ouvrage
     */
    public function setPropriete($propriete)
    {
        $this->propriete = $propriete;

        return $this;
    }

    /**
     * Get propriete
     *
     * @return string 
     */
    public function getPropriete()
    {
        return $this->propriete;
    }

    /**
     * Set usage
     *
     * @param string $usage
     * @return Ouvrage
     */
    public function setUsage($usage)
    {
        $this->usage = $usage;

        return $this;
    }

    /**
     * Get usage
     *
     * @return string 
     */
    public function getUsage()
    {
        return $this->usage;
    }

    /**
     * Set nomDuProjet
     *
     * @param string $nomDuProjet
     * @return Ouvrage
     */
    public function setNomDuProjet($nomDuProjet)
    {
        $this->nomDuProjet = $nomDuProjet;

        return $this;
    }

    /**
     * Get nomDuProjet
     *
     * @return string 
     */
    public function getNomDuProjet()
    {
        return $this->nomDuProjet;
    }

    /**
     * Set financement
     *
     * @param string $financement
     * @return Ouvrage
     */
    public function setFinancement($financement)
    {
        $this->financement = $financement;

        return $this;
    }

    /**
     * Get financement
     *
     * @return string 
     */
    public function getFinancement()
    {
        return $this->financement;
    }

    /**
     * Set ingenieurConseil
     *
     * @param string $ingenieurConseil
     * @return Ouvrage
     */
    public function setIngenieurConseil($ingenieurConseil)
    {
        $this->ingenieurConseil = $ingenieurConseil;

        return $this;
    }

    /**
     * Get ingenieurConseil
     *
     * @return string 
     */
    public function getIngenieurConseil()
    {
        return $this->ingenieurConseil;
    }

    /**
     * Set entreprise
     *
     * @param string $entreprise
     * @return Ouvrage
     */
    public function setEntreprise($entreprise)
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    /**
     * Get entreprise
     *
     * @return string 
     */
    public function getEntreprise()
    {
        return $this->entreprise;
    }

    /**
     * Set dateFinForation
     *
     * @param \DateTime $dateFinForation
     * @return Ouvrage
     */
    public function setDateFinForation($dateFinForation)
    {
        $this->dateFinForation = $dateFinForation;

        return $this;
    }

    /**
     * Get dateFinForation
     *
     * @return \DateTime 
     */
    public function getDateFinForation()
    {
        return $this->dateFinForation;
    }

    /**
     * Set debit
     *
     * @param string $debit
     * @return Ouvrage
     */
    public function setDebit($debit)
    {
        $this->debit = $debit;

        return $this;
    }

    /**
     * Get debit
     *
     * @return string 
     */
    public function getDebit()
    {
        return $this->debit;
    }

    /**
     * Set profondeurTotale
     *
     * @param string $profondeurTotale
     * @return Ouvrage
     */
    public function setProfondeurTotale($profondeurTotale)
    {
        $this->profondeurTotale = $profondeurTotale;

        return $this;
    }

    /**
     * Get profondeurTotale
     *
     * @return string 
     */
    public function getProfondeurTotale()
    {
        return $this->profondeurTotale;
    }

    /**
     * Set profondeurEquipee
     *
     * @param string $profondeurEquipee
     * @return Ouvrage
     */
    public function setProfondeurEquipee($profondeurEquipee)
    {
        $this->profondeurEquipee = $profondeurEquipee;

        return $this;
    }

    /**
     * Get profondeurEquipee
     *
     * @return string 
     */
    public function getProfondeurEquipee()
    {
        return $this->profondeurEquipee;
    }

    /**
     * Set niveauStatique
     *
     * @param string $niveauStatique
     * @return Ouvrage
     */
    public function setNiveauStatique($niveauStatique)
    {
        $this->niveauStatique = $niveauStatique;

        return $this;
    }

    /**
     * Get niveauStatique
     *
     * @return string 
     */
    public function getNiveauStatique()
    {
        return $this->niveauStatique;
    }

    /**
     * Set dateNs
     *
     * @param \DateTime $dateNs
     * @return Ouvrage
     */
    public function setDateNs($dateNs)
    {
        $this->dateNs = $dateNs;

        return $this;
    }

    /**
     * Get dateNs
     *
     * @return \DateTime 
     */
    public function getDateNs()
    {
        return $this->dateNs;
    }

    /**
     * Set geomorphologie
     *
     * @param string $geomorphologie
     * @return Ouvrage
     */
    public function setGeomorphologie($geomorphologie)
    {
        $this->geomorphologie = $geomorphologie;

        return $this;
    }

    /**
     * Get geomorphologie
     *
     * @return string 
     */
    public function getGeomorphologie()
    {
        return $this->geomorphologie;
    }

    /**
     * Set epaisseurAlteration
     *
     * @param string $epaisseurAlteration
     * @return Ouvrage
     */
    public function setEpaisseurAlteration($epaisseurAlteration)
    {
        $this->epaisseurAlteration = $epaisseurAlteration;

        return $this;
    }

    /**
     * Get epaisseurAlteration
     *
     * @return string 
     */
    public function getEpaisseurAlteration()
    {
        return $this->epaisseurAlteration;
    }

    /**
     * Set nomAquifere
     *
     * @param string $nomAquifere
     * @return Ouvrage
     */
    public function setNomAquifere($nomAquifere)
    {
        $this->nomAquifere = $nomAquifere;

        return $this;
    }

    /**
     * Get nomAquifere
     *
     * @return string 
     */
    public function getNomAquifere()
    {
        return $this->nomAquifere;
    }

    /**
     * Set lithologieAquifere
     *
     * @param string $lithologieAquifere
     * @return Ouvrage
     */
    public function setLithologieAquifere($lithologieAquifere)
    {
        $this->lithologieAquifere = $lithologieAquifere;

        return $this;
    }

    /**
     * Get lithologieAquifere
     *
     * @return string 
     */
    public function getLithologieAquifere()
    {
        return $this->lithologieAquifere;
    }

    /**
     * Set profondeurToit
     *
     * @param string $profondeurToit
     * @return Ouvrage
     */
    public function setProfondeurToit($profondeurToit)
    {
        $this->profondeurToit = $profondeurToit;

        return $this;
    }

    /**
     * Get profondeurToit
     *
     * @return string 
     */
    public function getProfondeurToit()
    {
        return $this->profondeurToit;
    }

    /**
     * Set profondeurMur
     *
     * @param string $profondeurMur
     * @return Ouvrage
     */
    public function setProfondeurMur($profondeurMur)
    {
        $this->profondeurMur = $profondeurMur;

        return $this;
    }

    /**
     * Get profondeurMur
     *
     * @return string 
     */
    public function getProfondeurMur()
    {
        return $this->profondeurMur;
    }

    /**
     * Set datePrelevement
     *
     * @param \DateTime $datePrelevement
     * @return Ouvrage
     */
    public function setDatePrelevement($datePrelevement)
    {
        $this->datePrelevement = $datePrelevement;

        return $this;
    }

    /**
     * Get datePrelevement
     *
     * @return \DateTime 
     */
    public function getDatePrelevement()
    {
        return $this->datePrelevement;
    }

    /**
     * Set temperature
     *
     * @param string $temperature
     * @return Ouvrage
     */
    public function setTemperature($temperature)
    {
        $this->temperature = $temperature;

        return $this;
    }

    /**
     * Get temperature
     *
     * @return string 
     */
    public function getTemperature()
    {
        return $this->temperature;
    }

    /**
     * Set conductivite
     *
     * @param string $conductivite
     * @return Ouvrage
     */
    public function setConductivite($conductivite)
    {
        $this->conductivite = $conductivite;

        return $this;
    }

    /**
     * Get conductivite
     *
     * @return string 
     */
    public function getConductivite()
    {
        return $this->conductivite;
    }

    /**
     * Set ph
     *
     * @param string $ph
     * @return Ouvrage
     */
    public function setPh($ph)
    {
        $this->ph = $ph;

        return $this;
    }

    /**
     * Get ph
     *
     * @return string 
     */
    public function getPh()
    {
        return $this->ph;
    }

    /**
     * Set ferTotal
     *
     * @param string $ferTotal
     * @return Ouvrage
     */
    public function setFerTotal($ferTotal)
    {
        $this->ferTotal = $ferTotal;

        return $this;
    }

    /**
     * Get ferTotal
     *
     * @return string 
     */
    public function getFerTotal()
    {
        return $this->ferTotal;
    }

    /**
     * Set nitrates
     *
     * @param string $nitrates
     * @return Ouvrage
     */
    public function setNitrates($nitrates)
    {
        $this->nitrates = $nitrates;

        return $this;
    }

    /**
     * Get nitrates
     *
     * @return string 
     */
    public function getNitrates()
    {
        return $this->nitrates;
    }

    /**
     * Set couleur
     *
     * @param string $couleur
     * @return Ouvrage
     */
    public function setCouleur($couleur)
    {
        $this->couleur = $couleur;

        return $this;
    }

    /**
     * Get couleur
     *
     * @return string 
     */
    public function getCouleur()
    {
        return $this->couleur;
    }

    /**
     * Set turbidite
     *
     * @param string $turbidite
     * @return Ouvrage
     */
    public function setTurbidite($turbidite)
    {
        $this->turbidite = $turbidite;

        return $this;
    }

    /**
     * Get turbidite
     *
     * @return string 
     */
    public function getTurbidite()
    {
        return $this->turbidite;
    }

    /**
     * Set marquePompe
     *
     * @param string $marquePompe
     * @return Ouvrage
     */
    public function setMarquePompe($marquePompe)
    {
        $this->marquePompe = $marquePompe;

        return $this;
    }

    /**
     * Get marquePompe
     *
     * @return string 
     */
    public function getMarquePompe()
    {
        return $this->marquePompe;
    }

    /**
     * Set dateInstallPompe
     *
     * @param \DateTime $dateInstallPompe
     * @return Ouvrage
     */
    public function setDateInstallPompe($dateInstallPompe)
    {
        $this->dateInstallPompe = $dateInstallPompe;

        return $this;
    }

    /**
     * Get dateInstallPompe
     *
     * @return \DateTime 
     */
    public function getDateInstallPompe()
    {
        return $this->dateInstallPompe;
    }

    /**
     * Set profondeurInstallPompe
     *
     * @param string $profondeurInstallPompe
     * @return Ouvrage
     */
    public function setProfondeurInstallPompe($profondeurInstallPompe)
    {
        $this->profondeurInstallPompe = $profondeurInstallPompe;

        return $this;
    }

    /**
     * Get profondeurInstallPompe
     *
     * @return string 
     */
    public function getProfondeurInstallPompe()
    {
        return $this->profondeurInstallPompe;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Ouvrage
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Ouvrage
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set validatedAt
     *
     * @param \DateTime $validatedAt
     * @return Ouvrage
     */
    public function setValidatedAt($validatedAt)
    {
        $this->validatedAt = $validatedAt;

        return $this;
    }

    /**
     * Get validatedAt
     *
     * @return \DateTime 
     */
    public function getValidatedAt()
    {
        return $this->validatedAt;
    }

    /**
     * Set sync
     *
     * @param string $sync
     * @return Ouvrage
     */
    public function setSync($sync)
    {
        $this->sync = $sync;

        return $this;
    }

    /**
     * Get sync
     *
     * @return string 
     */
    public function getSync()
    {
        return $this->sync;
    }

    /**
     * Add coupeGeologiques
     *
     * @param \Sofie\ExpBundle\Entity\CoupeGeologique $coupeGeologiques
     * @return Ouvrage
     */
    public function addCoupeGeologique(\Sofie\ExpBundle\Entity\CoupeGeologique $coupeGeologiques = null)
    {
        if($coupeGeologiques != null){
            $coupeGeologiques->setOuvrage($this);
            $this->coupeGeologiques[] = $coupeGeologiques;
        }

        return $this;
    }

    /**
     * Remove coupeGeologiques
     *
     * @param \Sofie\ExpBundle\Entity\CoupeGeologique $coupeGeologiques
     */
    public function removeCoupeGeologique(\Sofie\ExpBundle\Entity\CoupeGeologique $coupeGeologiques)
    {
        $coupeGeologiques->setOuvrage(null);
        $this->coupeGeologiques->removeElement($coupeGeologiques);
    }

    /**
     * Get coupeGeologiques
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCoupeGeologiques()
    {
        return $this->coupeGeologiques;
    }

    /**
     * Add equipementForages
     *
     * @param \Sofie\ExpBundle\Entity\EquipementForage $equipementForages
     * @return Ouvrage
     */
    public function addEquipementForage(\Sofie\ExpBundle\Entity\EquipementForage $equipementForages = null)
    {
        if($equipementForages != null){
            $equipementForages->setOuvrage($this);
            $this->equipementForages[] = $equipementForages;
        }

        return $this;
    }

    /**
     * Remove equipementForages
     *
     * @param \Sofie\ExpBundle\Entity\EquipementForage $equipementForages
     */
    public function removeEquipementForage(\Sofie\ExpBundle\Entity\EquipementForage $equipementForages)
    {
        $equipementForages->setOuvrage(null);
        $this->equipementForages->removeElement($equipementForages);
    }

    /**
     * Get equipementForages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEquipementForages()
    {
        return $this->equipementForages;
    }

    /**
     * Add essaisPompages
     *
     * @param \Sofie\ExpBundle\Entity\EssaisPompage $essaisPompages
     * @return Ouvrage
     */
    public function addEssaisPompage(\Sofie\ExpBundle\Entity\EssaisPompage $essaisPompages = null)
    {
        if($essaisPompages != null){
            $essaisPompages->setOuvrage($this);
            $this->essaisPompages[] = $essaisPompages;
        }

        return $this;
    }

    /**
     * Remove essaisPompages
     *
     * @param \Sofie\ExpBundle\Entity\EssaisPompage $essaisPompages
     */
    public function removeEssaisPompage(\Sofie\ExpBundle\Entity\EssaisPompage $essaisPompages)
    {
        $essaisPompages->setOuvrage(null);
        $this->essaisPompages->removeElement($essaisPompages);
    }

    /**
     * Get essaisPompages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEssaisPompages()
    {
        return $this->essaisPompages;
    }

    /**
     * Add suiviPhysicoChimiques
     *
     * @param \Sofie\ExpBundle\Entity\SuiviPhysicoChimique $suiviPhysicoChimiques
     * @return Ouvrage
     */
    public function addSuiviPhysicoChimique(\Sofie\ExpBundle\Entity\SuiviPhysicoChimique $suiviPhysicoChimiques = null)
    {
        if($suiviPhysicoChimiques!=null){
            $suiviPhysicoChimiques->setOuvrage($this);
            $this->suiviPhysicoChimiques[] = $suiviPhysicoChimiques;
        }

        return $this;
    }

    /**
     * Remove suiviPhysicoChimiques
     *
     * @param \Sofie\ExpBundle\Entity\SuiviPhysicoChimique $suiviPhysicoChimiques
     */
    public function removeSuiviPhysicoChimique(\Sofie\ExpBundle\Entity\SuiviPhysicoChimique $suiviPhysicoChimiques)
    {
        $suiviPhysicoChimiques->setOuvrage(null);
        $this->suiviPhysicoChimiques->removeElement($suiviPhysicoChimiques);
    }

    /**
     * Get suiviPhysicoChimiques
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSuiviPhysicoChimiques()
    {
        return $this->suiviPhysicoChimiques;
    }

    public function setCreatedValue()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = $this->createdAt;
        $this->dateStatut = $this->createdAt;
        if($this->statutOuvrage == static::PANNE){
            $this->statut = 2;
            $this->statutPanne = 2;
        }else{
            $this->statut = 1;
            $this->statutPanne = 1;
        }
    }

    public function setUpdatedValue(PreUpdateEventArgs $eventArgs)
    {
        $this->updatedAt = new \DateTime();
        if($eventArgs->hasChangedField('validated')){
            $this->validatedAt = $this->updatedAt;
        }
        $this->unsynchronize();
        if($eventArgs->hasChangedField('statutOuvrage')){
            if($this->statutOuvrage == static::PANNE){
                $this->statut = 2;
                $this->statutPanne = 2;
            }else{
                $this->statut = 1;
                $this->statutPanne = 1;
            }
        }
        $this->unsynchronize();
    }

    /**
     * Set validatedBy
     *
     * @param \Sofie\ExpBundle\Entity\Agent $validatedBy
     * @return Ouvrage
     */
    public function setValidatedBy(\Sofie\ExpBundle\Entity\Agent $validatedBy = null)
    {
        $this->validatedBy = $validatedBy;

        return $this;
    }

    /**
     * Get validatedBy
     *
     * @return \Sofie\ExpBundle\Entity\Agent 
     */
    public function getValidatedBy()
    {
        return $this->validatedBy;
    }

    public function __toString()
    {
        return $this->code;
    }

    /**
     * Set prefecture
     *
     * @param string $prefecture
     * @return Ouvrage
     */
    public function setPrefecture($prefecture)
    {
        $this->prefecture = $prefecture;

        return $this;
    }

    /**
     * Get prefecture
     *
     * @return string 
     */
    public function getPrefecture()
    {
        return $this->prefecture;
    }

    /**
     * Set canton
     *
     * @param string $canton
     * @return Ouvrage
     */
    public function setCanton($canton)
    {
        $this->canton = $canton;

        return $this;
    }

    /**
     * Get canton
     *
     * @return string 
     */
    public function getCanton()
    {
        return $this->canton;
    }

    public static function getArrayAssocStatut()
    {
        return array(
            static::MARCHE => static::MARCHE,
            static::PANNE => static::PANNE
        );
    }

    public function setCustomId(LifecycleEventArgs $eventArgs)
    {
        /*if($this->id == null){
            $this->id = $eventArgs->getEntityManager()->getRepository('SofieExpBundle:Ouvrage')->getNextId();
        }*/
    }

    public function getComiteId()
    {
        if($this->comite != null){
            return $this->comite->getId();
        }
        return null;
    }

    public function getLocaliteId()
    {
        if($this->localite != null){
            return $this->localite->getId();
        }
        return null;
    }

    public function getValidatedById()
    {
        if($this->validatedBy != null){
            return $this->validatedBy->getId();
        }
        return null;
    }

    public function synchronize()
    {
        $this->sync = static::SYNC;
    }

    public function unsynchronize()
    {
        $this->sync = static::NO_SYNC;
        return $this;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return Ouvrage
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime 
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function generateCode()
    {
        if($this->id != null && $this->getRegion() != null){
            $this->code = $this->getRegion()->getId().''.$this->id;
        }
    }

    public function logString()
    {
        $msg = 'Identifiant: '.$this->id;
        if($this->code) $msg .= ', Code : '.$this->code;
        if($this->type) $msg .= ', Type : '.$this->type;
        if($this->coupeGeologiques->count() > 0){
            $msg .= ', Coupes géologiques : {';
            $idList = '';
            foreach($this->coupeGeologiques as $coupe){
                if(!empty($idList)) $idList .= ',';
                $idList .= $coupe->getId();
            }
            $msg .= $idList.'}';
        }
        if($this->equipementForages->count() > 0){
            $msg .= ', Equipements forages : {';
            $idList = '';
            foreach($this->equipementForages as $equip){
                if(!empty($idList)) $idList .= ',';
                $idList .= $equip->getId();
            }
            $msg .= $idList.'}';
        }
        if($this->essaisPompages->count() > 0){
            $msg .= ', Essais pompages : {';
            $idList = '';
            foreach($this->essaisPompages as $essais){
                if(!empty($idList)) $idList .= ',';
                $idList .= $essais->getId();
            }
            $msg .= $idList.'}';
        }
        if($this->suiviPhysicoChimiques->count() > 0){
            $msg .= ', Suivis physicochimiques : {';
            $idList = '';
            foreach($this->suiviPhysicoChimiques as $suivi){
                if(!empty($idList)) $idList .= ',';
                $idList .= $suivi->getId();
            }
            $msg .= $idList.'}';
        }
        return $msg;
    }

    /**
     * Add venuEauPrincipales
     *
     * @param \Sofie\ExpBundle\Entity\VenuEauPrincipale $venuEauPrincipales
     * @return Ouvrage
     */
    public function addVenuEauPrincipale(\Sofie\ExpBundle\Entity\VenuEauPrincipale $venuEauPrincipales = null)
    {
        if($venuEauPrincipales != null){
            $this->venuEauPrincipales[] = $venuEauPrincipales;
            $venuEauPrincipales->setOuvrage($this);
        }

        return $this;
    }

    /**
     * Remove venuEauPrincipales
     *
     * @param \Sofie\ExpBundle\Entity\VenuEauPrincipale $venuEauPrincipales
     */
    public function removeVenuEauPrincipale(\Sofie\ExpBundle\Entity\VenuEauPrincipale $venuEauPrincipales)
    {
        $this->venuEauPrincipales->removeElement($venuEauPrincipales);
        $venuEauPrincipales->setOuvrage(null);
    }

    /**
     * Get venuEauPrincipales
     *
     * @return Collection
     */
    public function getVenuEauPrincipales()
    {
        return $this->venuEauPrincipales;
    }

    /**
     * Set quartier
     *
     * @param string $quartier
     * @return Ouvrage
     */
    public function setQuartier($quartier)
    {
        $this->quartier = $quartier;

        return $this;
    }

    /**
     * Get quartier
     *
     * @return string 
     */
    public function getQuartier()
    {
        return $this->quartier;
    }

    public function isValidatable()
    {
        return (
            is_null(ParameterFile::loadSite()) && ($this->validated
            || ( !is_null($this->statutOuvrage) && !empty($this->statutOuvrage) && !is_null($this->type)
            && !empty($this->type) && (!is_null($this->comite) && !is_null($this->comite->getNumeroAppel()))
            && (!is_null($this->getRegion()) && !is_null($this->getRegion()->getIddr()) && !is_null($this->getRegion()->getSociologue()))
            ))
        );
    }

    public function titleValidate()
    {
        $msg = '';
        if(!is_null(ParameterFile::loadSite())){
            $msg .= 'Fonction disponible uniquement au site central';
        }else{
            if($this->validated) return '';
            if(is_null($this->statutOuvrage) || empty($this->statutOuvrage)) $this->addSubject($msg, "le statut de l'ouvrage");
            if(is_null($this->type) || empty($this->type)){
                $this->addSubject($msg, "le type de l'ouvrage");
            }
            if(is_null($this->comite)){
                $this->addSubject($msg, "le comité");
            }else{
                if(is_null($this->comite->getNumeroAppel())){
                    $this->addSubject($msg, "le numéro du comité");
                }
            }
            if(is_null($this->localite)){
                $this->addSubject($msg, "la localité");
            }else{
                if(is_null($this->getRegion())){
                    $this->addSubject($msg, "la région");
                }else{
                    if(is_null($this->getRegion()->getIddr())){
                        $this->addSubject($msg, "le directeur de la région");
                    }
                    if(is_null($this->getRegion()->getSociologue())){
                        $this->addSubject($msg, "le sociologue de la région");
                    }
                }
            }
            if(!empty($msg)){
                $lastPos = strrpos($msg, ',');
                if($lastPos === false){
                    $msg .= ' ne doit pas être vide !';
                }else{
                    $msg = substr_replace($msg, ' et', $lastPos, 1);
                    $msg .= ' ne doivent pas être vides !';
                }
            }
        }

        return $msg;
    }

    protected function addSubject(&$msg, $subject)
    {
        if(!empty($msg)){
            $msg .= ', ';
            $subject = lcfirst($subject);
        }else{
            $msg .= ' ';
            $subject = ucfirst($subject);
        }
        $msg .= $subject;
    }

    /**
     * ORM\PostLoad
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postLoadValue(LifecycleEventArgs $eventArgs)
    {
        /*if(self::$edm == null){
            self::initializeEDM($eventArgs->getEntityManager());
        }*/
    }

    static public function initializeEDM(EntityManager $em)
    {
        if(self::$edm == null){
            self::$edm = new EnumDataManager($em);
            self::$edm->getOuvrageEnumValues();
        }
    }

    static public function getTypes()
    {
        return (self::$edm instanceof EnumDataManager) ? self::$edm->getTypeOuvrage() : array();
    }

    static public function getStatutsOuvrage()
    {
        return (self::$edm instanceof EnumDataManager) ? self::$edm->getStatutOuvrage(): array();
    }

    /**
     * Set codeOuvrageProgres
     *
     * @param string $codeOuvrageProgres
     * @return Ouvrage
     */
    public function setCodeOuvrageProgres($codeOuvrageProgres)
    {
        $this->codeOuvrageProgres = $codeOuvrageProgres;

        return $this;
    }

    /**
     * Get codeOuvrageProgres
     *
     * @return string 
     */
    public function getCodeOuvrageProgres()
    {
        return $this->codeOuvrageProgres;
    }

    static public function getValidatedWordArrayAssoc(){
        return array('0'=>self::NOT_VALIDATED_WORD, '1'=>self::VALIDATED_WORD);
    }

    /**
     * ORM\PreRemove
     */
    public function setRemovedValue(LifecycleEventArgs $eventArgs)
    {
        $this->unsynchronize();
        $eventArgs->getEntityManager()->flush();
    }
}
