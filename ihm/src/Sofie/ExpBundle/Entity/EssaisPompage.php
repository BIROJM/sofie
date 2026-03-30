<?php

namespace Sofie\ExpBundle\Entity;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;


/**
 * EssaisPompage
 */
class EssaisPompage
{
    const SYNC = 'Y';
    const NO_SYNC = 'N';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $typeEssai;

    /**
     * @var string
     */
    private $dureeEssai;

    /**
     * @var string
     */
    private $debitMax;

    /**
     * @var string
     */
    private $rabattement;

    /**
     * @var string
     */
    private $debitCritique;

    /**
     * @var string
     */
    private $transmissivite;

    /**
     * @var string
     */
    private $emmagasinage;

    /**
     * @var \Sofie\ExpBundle\Entity\Ouvrage
     */
    private $ouvrage;

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
     * @var \DateTime
     */
    private $deletedAt;

    /**
     * Ces deux variables sont utilisées pour l'ajout via l'api
     */
    static private $indice = 0;
    private $position;


    public function __construct()
    {
        $this->sync = static::NO_SYNC;
        $this->position = static::$indice++;
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
     * Set date
     *
     * @param \DateTime $date
     * @return EssaisPompage
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set typeEssai
     *
     * @param string $typeEssai
     * @return EssaisPompage
     */
    public function setTypeEssai($typeEssai)
    {
        $this->typeEssai = $typeEssai;

        return $this;
    }

    /**
     * Get typeEssai
     *
     * @return string 
     */
    public function getTypeEssai()
    {
        return $this->typeEssai;
    }

    /**
     * Set dureeEssai
     *
     * @param string $dureeEssai
     * @return EssaisPompage
     */
    public function setDureeEssai($dureeEssai)
    {
        $this->dureeEssai = $dureeEssai;

        return $this;
    }

    /**
     * Get dureeEssai
     *
     * @return string 
     */
    public function getDureeEssai()
    {
        return $this->dureeEssai;
    }

    /**
     * Set debitMax
     *
     * @param string $debitMax
     * @return EssaisPompage
     */
    public function setDebitMax($debitMax)
    {
        $this->debitMax = $debitMax;

        return $this;
    }

    /**
     * Get debitMax
     *
     * @return string 
     */
    public function getDebitMax()
    {
        return $this->debitMax;
    }

    /**
     * Set rabattement
     *
     * @param string $rabattement
     * @return EssaisPompage
     */
    public function setRabattement($rabattement)
    {
        $this->rabattement = $rabattement;

        return $this;
    }

    /**
     * Get rabattement
     *
     * @return string 
     */
    public function getRabattement()
    {
        return $this->rabattement;
    }

    /**
     * Set debitCritique
     *
     * @param string $debitCritique
     * @return EssaisPompage
     */
    public function setDebitCritique($debitCritique)
    {
        $this->debitCritique = $debitCritique;

        return $this;
    }

    /**
     * Get debitCritique
     *
     * @return string 
     */
    public function getDebitCritique()
    {
        return $this->debitCritique;
    }

    /**
     * Set transmissivite
     *
     * @param string $transmissivite
     * @return EssaisPompage
     */
    public function setTransmissivite($transmissivite)
    {
        $this->transmissivite = $transmissivite;

        return $this;
    }

    /**
     * Get transmissivite
     *
     * @return string 
     */
    public function getTransmissivite()
    {
        return $this->transmissivite;
    }

    /**
     * Set emmagasinage
     *
     * @param string $emmagasinage
     * @return EssaisPompage
     */
    public function setEmmagasinage($emmagasinage)
    {
        $this->emmagasinage = $emmagasinage;

        return $this;
    }

    /**
     * Get emmagasinage
     *
     * @return string 
     */
    public function getEmmagasinage()
    {
        return $this->emmagasinage;
    }

    /**
     * Set ouvrage
     *
     * @param \Sofie\ExpBundle\Entity\Ouvrage $ouvrage
     * @return EssaisPompage
     */
    public function setOuvrage(\Sofie\ExpBundle\Entity\Ouvrage $ouvrage = null)
    {
        $this->ouvrage = $ouvrage;

        return $this;
    }

    /**
     * Get ouvrage
     *
     * @return \Sofie\ExpBundle\Entity\Ouvrage 
     */
    public function getOuvrage()
    {
        return $this->ouvrage;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return EssaisPompage
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
     * @return EssaisPompage
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
     * @return EssaisPompage
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

    public function setPreFlushValue(PreFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        if($this->ouvrage==null){
            $em->remove($this);
            $em->flush();
        }
    }

    public function setCustomId(LifecycleEventArgs $eventArgs)
    {
        /*if($this->id == null){
            $this->id = $eventArgs->getEntityManager()->getRepository('SofieExpBundle:EssaisPompage')->getNextId()+$this->position;
        }*/
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function synchronize()
    {
        $this->sync = static::SYNC;
    }

    public function unsynchronize()
    {
        $this->sync = static::NO_SYNC;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return EssaisPompage
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
     * ORM\PreRemove
     */
    public function setRemovedValue(LifecycleEventArgs $eventArgs)
    {
        $this->unsynchronize();
        $eventArgs->getEntityManager()->flush();
    }
}
