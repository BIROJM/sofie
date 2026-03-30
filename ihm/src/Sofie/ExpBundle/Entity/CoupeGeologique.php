<?php

namespace Sofie\ExpBundle\Entity;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;


/**
 * CoupeGeologique
 */
class CoupeGeologique
{
    const SYNC = 'Y';
    const NO_SYNC = 'N';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $coteSup;

    /**
     * @var string
     */
    private $coteInf;

    /**
     * @var string
     */
    private $lithographie;

    /**
     * @var string
     */
    private $description;

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

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set coteSup
     *
     * @param string $coteSup
     * @return CoupeGeologique
     */
    public function setCoteSup($coteSup)
    {
        $this->coteSup = $coteSup;

        return $this;
    }

    /**
     * Get coteSup
     *
     * @return string 
     */
    public function getCoteSup()
    {
        return $this->coteSup;
    }

    /**
     * Set coteInf
     *
     * @param string $coteInf
     * @return CoupeGeologique
     */
    public function setCoteInf($coteInf)
    {
        $this->coteInf = $coteInf;

        return $this;
    }

    /**
     * Get coteInf
     *
     * @return string 
     */
    public function getCoteInf()
    {
        return $this->coteInf;
    }

    /**
     * Set lithographie
     *
     * @param string $lithographie
     * @return CoupeGeologique
     */
    public function setLithographie($lithographie)
    {
        $this->lithographie = $lithographie;

        return $this;
    }

    /**
     * Get lithographie
     *
     * @return string 
     */
    public function getLithographie()
    {
        return $this->lithographie;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return CoupeGeologique
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set ouvrage
     *
     * @param \Sofie\ExpBundle\Entity\Ouvrage $ouvrage
     * @return CoupeGeologique
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
     * @return CoupeGeologique
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
     * @return CoupeGeologique
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
     * @return CoupeGeologique
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
            $this->id = $eventArgs->getEntityManager()->getRepository('SofieExpBundle:CoupeGeologique')->getNextId()+$this->position;
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
        return $this;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return CoupeGeologique
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function unsynchronize()
    {
        $this->sync = self::NO_SYNC;
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
