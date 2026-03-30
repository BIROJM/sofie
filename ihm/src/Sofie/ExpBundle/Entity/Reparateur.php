<?php

namespace Sofie\ExpBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Sofie\AdminBundle\Model\ParameterFile;

/**
 * Reparateur
 */
class Reparateur
{
    const SYNC = 'Y';
    const NO_SYNC = 'N';
    const PROFILE = 2;

    const INIT_WORD = 'Initialisé';
    const NOINIT_WORD = 'Non initialisé';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nom;

    /**
     * @var string
     */
    private $prenoms;

    /**
     * @var string
     */
    private $codeInit;

    /**
     * @var boolean
     */
    private $initStatus;

    /**
     * @var \Sofie\ExpBundle\Entity\NumeroAppel
     */
    private $numeroAppel;

    /**
     * @var Collection
     */
    private $localites;

    /**
     * @var \DateTime
     */
    private $deletedAt;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var string
     */
    private $sync;

    /**
     * Cette variable n'est pas persister en base de données
     *
     * @var string
     */
    private $fullname;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->localites = new ArrayCollection();
        $this->sync = static::NO_SYNC;
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

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Reparateur
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenoms
     *
     * @param string $prenoms
     * @return Reparateur
     */
    public function setPrenoms($prenoms)
    {
        $this->prenoms = $prenoms;

        return $this;
    }

    /**
     * Get prenoms
     *
     * @return string 
     */
    public function getPrenoms()
    {
        return $this->prenoms;
    }

    /**
     * Set codeInit
     *
     * @param string $codeInit
     * @return Reparateur
     */
    public function setCodeInit($codeInit)
    {
        $this->codeInit = $codeInit;

        return $this;
    }

    /**
     * Get codeInit
     *
     * @return string 
     */
    public function getCodeInit()
    {
        return $this->codeInit;
    }

    /**
     * Set initStatus
     *
     * @param boolean $initStatus
     * @return Reparateur
     */
    public function setInitStatus($initStatus)
    {
        $this->initStatus = $initStatus;

        return $this;
    }

    /**
     * Get initStatus
     *
     * @return boolean 
     */
    public function getInitStatus()
    {
        return $this->initStatus;
    }

    /**
     * Set numeroAppel
     *
     * @param \Sofie\ExpBundle\Entity\NumeroAppel $numeroAppel
     * @return Reparateur
     */
    public function setNumeroAppel(\Sofie\ExpBundle\Entity\NumeroAppel $numeroAppel = null)
    {
        $this->numeroAppel = $numeroAppel;

        return $this;
    }

    /**
     * Get numeroAppel
     *
     * @return \Sofie\ExpBundle\Entity\NumeroAppel 
     */
    public function getNumeroAppel()
    {
        return $this->numeroAppel;
    }

    /**
     * Add localites
     *
     * @param \Sofie\ExpBundle\Entity\Localite $localites
     * @return Reparateur
     */
    public function addLocalite(\Sofie\ExpBundle\Entity\Localite $localites)
    {
        $this->localites[] = $localites;

        return $this;
    }

    /**
     * Remove localites
     *
     * @param \Sofie\ExpBundle\Entity\Localite $localites
     */
    public function removeLocalite(\Sofie\ExpBundle\Entity\Localite $localites)
    {
        $this->localites->removeElement($localites);
    }

    /**
     * Get localites
     *
     * @return Collection
     */
    public function getLocalites()
    {
        return $this->localites;
    }

    public function __toString()
    {
        return $this->getFullname();
    }

    public function detachNumeroAppel()
    {
        if(is_object($this->numeroAppel)){
            if(!$this->numeroAppel->getId() && is_null($this->numeroAppel->getNumero())){
                return true;
            }
        }
        return false;
    }

    public function removeNumeroAppel()
    {
        if(is_object($this->numeroAppel)){
            if($this->numeroAppel->getId() && is_null($this->numeroAppel->getNumero())){
                return true;
            }
        }
        return false;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return Reparateur
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

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Reparateur
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
     * @return Reparateur
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
     * Set sync
     *
     * @param string $sync
     * @return Reparateur
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

    public function setCreatedValue()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = $this->createdAt;
    }

    public function setUpdatedValue()
    {
        $this->updatedAt = new \DateTime();
        $this->unsynchronize();
    }

    public function setCustomId(LifecycleEventArgs $eventArgs)
    {
        /*if($this->id == null){
            $this->id = $eventArgs->getEntityManager()->getRepository('SofieExpBundle:Reparateur')->getNextId();
        }*/
    }

    public function synchronize()
    {
        $this->sync = static::SYNC;
    }

    public function unsynchronize()
    {
        $this->sync = static::NO_SYNC;
    }

    public function initialize(LifecycleEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        if(!$this->codeInit || !$this->initStatus){
            $code = $em->getRepository('SofieExpBundle:Code')->findOneBy(array(
                'status'=>Code::STATUS_NO,
                'profile'=>self::PROFILE
            ));
            if($code && ($code instanceof Code)){
                $this->codeInit = $code->getCode();
                $code->attribCode();
            }
        }
    }

    public function logString()
    {
        $msg = 'Identifiant: '.$this->id;
        if($this->nom) $msg .= ', Nom : '.$this->nom;
        if($this->prenoms) $msg .= ', Prénoms : '.$this->prenoms;
        if($this->codeInit) $msg .= ', Code d\'initialisation : '.$this->codeInit;
        return $msg;
    }

    public function getFullname(){
        $this->fullname = trim($this->nom.' '.$this->prenoms);
        return $this->fullname;
    }

    static public function getInitWordArrayAssoc()
    {
        return array('0'=>self::NOINIT_WORD, '1'=>self::INIT_WORD);
    }

    public function isInitializable()
    {
        return (
            is_null(ParameterFile::loadSite()) && !empty($this->codeInit)
        );
    }

    public function titleInitialize()
    {
        $msg = '';
        if(!is_null(ParameterFile::loadSite())){
            $msg .= 'Fonction disponible uniquement au site central';
        }else{
            if(empty($this->codeInit)){
                $msg .= 'Le code d\'initialisation ne doit pas être vide';
            }
        }
        return $msg;
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
