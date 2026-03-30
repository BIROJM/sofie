<?php

namespace Sofie\ExpBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * NumeroAppel
 */
class NumeroAppel
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
    private $numero;

    /**
     * @var \Sofie\ExpBundle\Entity\Agent
     */
    private $agent;

    /**
     * @var Collection
     */
    private $appels;

    /**
     * @var Collection
     */
    private $notifications;

    /**
     * @var \Sofie\ExpBundle\Entity\Profile
     */
    private $profile;

    /**
     * @var string
     */
    private $sync;

    /**
     * @var \DateTime
     */
    private $deletedAt;

    /**
     * @var \Sofie\ExpBundle\Entity\Comite
     */
    private $comite;

    /**
     * @var \Sofie\ExpBundle\Entity\Reparateur
     */
    private $reparateur;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->appels = new ArrayCollection();
        $this->notifications = new ArrayCollection();
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

    /**
     * Set numero
     *
     * @param string $numero
     * @return NumeroAppel
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set agent
     *
     * @param \Sofie\ExpBundle\Entity\Agent $agent
     * @return NumeroAppel
     */
    public function setAgent(\Sofie\ExpBundle\Entity\Agent $agent = null)
    {
        $this->agent = $agent;

        return $this;
    }

    /**
     * Get agent
     *
     * @return \Sofie\ExpBundle\Entity\Agent 
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * Add appels
     *
     * @param \Sofie\ExpBundle\Entity\AppelTelephonique $appels
     * @return NumeroAppel
     */
    public function addAppel(\Sofie\ExpBundle\Entity\AppelTelephonique $appels)
    {
        $this->appels[] = $appels;

        return $this;
    }

    /**
     * Remove appels
     *
     * @param \Sofie\ExpBundle\Entity\AppelTelephonique $appels
     */
    public function removeAppel(\Sofie\ExpBundle\Entity\AppelTelephonique $appels)
    {
        $this->appels->removeElement($appels);
    }

    /**
     * Get appels
     *
     * @return Collection
     */
    public function getAppels()
    {
        return $this->appels;
    }

    /**
     * Add notifications
     *
     * @param \Sofie\ExpBundle\Entity\Notification $notifications
     * @return NumeroAppel
     */
    public function addNotification(\Sofie\ExpBundle\Entity\Notification $notifications)
    {
        $this->notifications[] = $notifications;

        return $this;
    }

    /**
     * Remove notifications
     *
     * @param \Sofie\ExpBundle\Entity\Notification $notifications
     */
    public function removeNotification(\Sofie\ExpBundle\Entity\Notification $notifications)
    {
        $this->notifications->removeElement($notifications);
    }

    /**
     * Get notifications
     *
     * @return Collection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Set profile
     *
     * @param \Sofie\ExpBundle\Entity\Profile $profile
     * @return NumeroAppel
     */
    public function setProfile(\Sofie\ExpBundle\Entity\Profile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \Sofie\ExpBundle\Entity\Profile 
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Set sync
     *
     * @param string $sync
     * @return NumeroAppel
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
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return NumeroAppel
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
     * Set comite
     *
     * @param \Sofie\ExpBundle\Entity\Comite $comite
     * @return NumeroAppel
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
     * Set reparateur
     *
     * @param \Sofie\ExpBundle\Entity\Reparateur $reparateur
     * @return NumeroAppel
     */
    public function setReparateur(\Sofie\ExpBundle\Entity\Reparateur $reparateur = null)
    {
        $this->reparateur = $reparateur;

        return $this;
    }

    /**
     * Get reparateur
     *
     * @return \Sofie\ExpBundle\Entity\Reparateur 
     */
    public function getReparateur()
    {
        return $this->reparateur;
    }

    public function getEmetteur()
    {
        $emetteur = "";
        if(!is_null($this->agent)){
            $emetteur = $this->agent->__toString();
        }elseif(!is_null($this->comite)){
            $emetteur = $this->comite->__toString();
        }elseif(!is_null($this->reparateur)){
            $emetteur = $this->reparateur->__toString();
        }
        return $emetteur;
    }

    public function getProprietaire(){
        return $this->getEmetteur();
    }

    public function synchronize()
    {
        $this->sync = static::SYNC;
    }

    public function unsynchronize()
    {
        $this->sync = static::NO_SYNC;
    }

    public function setUpdatedValue()
    {
        $this->unsynchronize();
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
