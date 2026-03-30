<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 25/08/2015
 * Time: 12:19
 */

namespace Sofie\UserBundle\Entity;


use Doctrine\ORM\Event\LifecycleEventArgs;

class UserGroupe
{
    const SYNC = 'Y';
    const NO_SYNC = 'N';

    /**
     * @var \DateTime
     */
    private $deletedAt;

    /**
     * @var string
     */
    private $sync;

    /**
     * @var \Sofie\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \Sofie\UserBundle\Entity\Groupe
     */
    private $groupe;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sync = static::NO_SYNC;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return UserGroupe
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
     * Set sync
     *
     * @param string $sync
     * @return UserGroupe
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
     * Set user
     *
     * @param \Sofie\UserBundle\Entity\User $user
     * @return UserGroupe
     */
    public function setUser(\Sofie\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Sofie\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set groupe
     *
     * @param \Sofie\UserBundle\Entity\Groupe $groupe
     * @return UserGroupe
     */
    public function setGroupe(\Sofie\UserBundle\Entity\Groupe $groupe)
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * Get groupe
     *
     * @return \Sofie\UserBundle\Entity\Groupe 
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    public function synchronize()
    {
        $this->sync = self::SYNC;
    }

    public function unsynchronize()
    {
        $this->sync = self::NO_SYNC;
    }

    /**
     * ORM PreUpdate
     */
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
