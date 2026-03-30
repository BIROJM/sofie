<?php

namespace Sofie\ExpBundle\Entity;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;


/**
 * SuiviPhysicoChimique
 */
class SuiviPhysicoChimique
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
    private $ph;

    /**
     * @var string
     */
    private $cond;

    /**
     * @var string
     */
    private $resSec;

    /**
     * @var string
     */
    private $ca;

    /**
     * @var string
     */
    private $mg;

    /**
     * @var string
     */
    private $na;

    /**
     * @var string
     */
    private $k;

    /**
     * @var string
     */
    private $cl;

    /**
     * @var string
     */
    private $no2;

    /**
     * @var string
     */
    private $no3;

    /**
     * @var string
     */
    private $so4;

    /**
     * @var string
     */
    private $hco3;

    /**
     * @var string
     */
    private $feTot;

    /**
     * @var string
     */
    private $f;

    /**
     * @var string
     */
    private $as;

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
     * Ces deux variables sont utilis�es pour l'ajout via l'api
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
     * @return SuiviPhysicoChimique
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
     * Set ph
     *
     * @param string $ph
     * @return SuiviPhysicoChimique
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
     * Set cond
     *
     * @param string $cond
     * @return SuiviPhysicoChimique
     */
    public function setCond($cond)
    {
        $this->cond = $cond;

        return $this;
    }

    /**
     * Get cond
     *
     * @return string 
     */
    public function getCond()
    {
        return $this->cond;
    }

    /**
     * Set resSec
     *
     * @param string $resSec
     * @return SuiviPhysicoChimique
     */
    public function setResSec($resSec)
    {
        $this->resSec = $resSec;

        return $this;
    }

    /**
     * Get resSec
     *
     * @return string 
     */
    public function getResSec()
    {
        return $this->resSec;
    }

    /**
     * Set ca
     *
     * @param string $ca
     * @return SuiviPhysicoChimique
     */
    public function setCa($ca)
    {
        $this->ca = $ca;

        return $this;
    }

    /**
     * Get ca
     *
     * @return string 
     */
    public function getCa()
    {
        return $this->ca;
    }

    /**
     * Set mg
     *
     * @param string $mg
     * @return SuiviPhysicoChimique
     */
    public function setMg($mg)
    {
        $this->mg = $mg;

        return $this;
    }

    /**
     * Get mg
     *
     * @return string 
     */
    public function getMg()
    {
        return $this->mg;
    }

    /**
     * Set na
     *
     * @param string $na
     * @return SuiviPhysicoChimique
     */
    public function setNa($na)
    {
        $this->na = $na;

        return $this;
    }

    /**
     * Get na
     *
     * @return string 
     */
    public function getNa()
    {
        return $this->na;
    }

    /**
     * Set k
     *
     * @param string $k
     * @return SuiviPhysicoChimique
     */
    public function setK($k)
    {
        $this->k = $k;

        return $this;
    }

    /**
     * Get k
     *
     * @return string 
     */
    public function getK()
    {
        return $this->k;
    }

    /**
     * Set cl
     *
     * @param string $cl
     * @return SuiviPhysicoChimique
     */
    public function setCl($cl)
    {
        $this->cl = $cl;

        return $this;
    }

    /**
     * Get cl
     *
     * @return string 
     */
    public function getCl()
    {
        return $this->cl;
    }

    /**
     * Set no2
     *
     * @param string $no2
     * @return SuiviPhysicoChimique
     */
    public function setNo2($no2)
    {
        $this->no2 = $no2;

        return $this;
    }

    /**
     * Get no2
     *
     * @return string 
     */
    public function getNo2()
    {
        return $this->no2;
    }

    /**
     * Set no3
     *
     * @param string $no3
     * @return SuiviPhysicoChimique
     */
    public function setNo3($no3)
    {
        $this->no3 = $no3;

        return $this;
    }

    /**
     * Get no3
     *
     * @return string 
     */
    public function getNo3()
    {
        return $this->no3;
    }

    /**
     * Set so4
     *
     * @param string $so4
     * @return SuiviPhysicoChimique
     */
    public function setSo4($so4)
    {
        $this->so4 = $so4;

        return $this;
    }

    /**
     * Get so4
     *
     * @return string 
     */
    public function getSo4()
    {
        return $this->so4;
    }

    /**
     * Set hco3
     *
     * @param string $hco3
     * @return SuiviPhysicoChimique
     */
    public function setHco3($hco3)
    {
        $this->hco3 = $hco3;

        return $this;
    }

    /**
     * Get hco3
     *
     * @return string 
     */
    public function getHco3()
    {
        return $this->hco3;
    }

    /**
     * Set feTot
     *
     * @param string $feTot
     * @return SuiviPhysicoChimique
     */
    public function setFeTot($feTot)
    {
        $this->feTot = $feTot;

        return $this;
    }

    /**
     * Get feTot
     *
     * @return string 
     */
    public function getFeTot()
    {
        return $this->feTot;
    }

    /**
     * Set f
     *
     * @param string $f
     * @return SuiviPhysicoChimique
     */
    public function setF($f)
    {
        $this->f = $f;

        return $this;
    }

    /**
     * Get f
     *
     * @return string 
     */
    public function getF()
    {
        return $this->f;
    }

    /**
     * Set as
     *
     * @param string $as
     * @return SuiviPhysicoChimique
     */
    public function setAs($as)
    {
        $this->as = $as;

        return $this;
    }

    /**
     * Get as
     *
     * @return string 
     */
    public function getAs()
    {
        return $this->as;
    }

    /**
     * Set ouvrage
     *
     * @param \Sofie\ExpBundle\Entity\Ouvrage $ouvrage
     * @return SuiviPhysicoChimique
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
     * @return SuiviPhysicoChimique
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
     * @return SuiviPhysicoChimique
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
     * @return SuiviPhysicoChimique
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
            $this->id = $eventArgs->getEntityManager()->getRepository('SofieExpBundle:SuiviPhysicoChimique')->getNextId()+$this->position;
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
     * @return SuiviPhysicoChimique
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
