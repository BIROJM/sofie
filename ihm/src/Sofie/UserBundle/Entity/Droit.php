<?php

namespace Sofie\UserBundle\Entity;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Droit
 */
class Droit implements RoleInterface
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
    private $role;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var string
     */
    private $sync;

    /**
     * @var \DateTime
     */
    private $deletedAt;

    /**
     * @var Collection
     */
    private $groupeDroits;

    /**
     * @var string
     */
    private $context;


    /**
     * @var \Sofie\UserBundle\Entity\DroitCategory
     */
    private $droitCategory;


    /**
     * @var \Sofie\UserBundle\Entity\Droit
     */
    private $parent;

    /**
     * @var Collection
     */
    private $children;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->groupeDroits = new ArrayCollection();
        $this->children = new ArrayCollection();
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
     * Set role
     *
     * @param string $role
     * @return Droit
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return Droit
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    public function __toString()
    {
        return $this->role;
    }

    /**
     * Set sync
     *
     * @param string $sync
     * @return Droit
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
     * @return Droit
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
     * Add groupeDroits
     *
     * @param \Sofie\UserBundle\Entity\GroupeDroit $groupeDroits
     * @return Droit
     */
    public function addGroupeDroit(\Sofie\UserBundle\Entity\GroupeDroit $groupeDroits)
    {
        if(!$this->hasGroupeDroit($groupeDroits)){
            $this->groupeDroits[] = $groupeDroits;
            $groupeDroits->setDroit($this);
        }

        return $this;
    }

    /**
     * Remove groupeDroits
     *
     * @param \Sofie\UserBundle\Entity\GroupeDroit $groupeDroits
     */
    public function removeGroupeDroit(\Sofie\UserBundle\Entity\GroupeDroit $groupeDroits)
    {
        $this->groupeDroits->removeElement($groupeDroits);
    }

    /**
     * Get groupeDroits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroupeDroits()
    {
        return $this->groupeDroits;
    }

    public function hasGroupeDroit(GroupeDroit $groupeDroit)
    {
        $result = false;
        foreach($this->groupeDroits as $groupeDroitOld){
            if($groupeDroit->getGroupe()->getId() == $groupeDroitOld->getGroupe()->getId()){
                $result = true;
                break;
            }
        }
        return $result;
    }

    /**
     * Set context
     *
     * @param string $context
     * @return Droit
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set droitCategory
     *
     * @param \Sofie\UserBundle\Entity\DroitCategory $droitCategory
     * @return Droit
     */
    public function setDroitCategory(\Sofie\UserBundle\Entity\DroitCategory $droitCategory = null)
    {
        $this->droitCategory = $droitCategory;

        return $this;
    }

    /**
     * Get droitCategory
     *
     * @return \Sofie\UserBundle\Entity\DroitCategory 
     */
    public function getDroitCategory()
    {
        return $this->droitCategory;
    }

    /**
     * Set parent
     *
     * @param \Sofie\UserBundle\Entity\Droit $parent
     * @return Droit
     */
    public function setParent(\Sofie\UserBundle\Entity\Droit $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Sofie\UserBundle\Entity\Droit 
     */
    public function getParent()
    {
        return $this->parent;
    }


    /**
     * Add children
     *
     * @param \Sofie\UserBundle\Entity\Droit $children
     * @return Droit
     */
    public function addChild(\Sofie\UserBundle\Entity\Droit $children)
    {
        $this->children[] = $children;
        $children->setParent($this);

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Sofie\UserBundle\Entity\Droit $children
     */
    public function removeChild(\Sofie\UserBundle\Entity\Droit $children)
    {
        $this->children->removeElement($children);
        $children->setParent(null);
    }

    /**
     * Get children
     *
     * @return Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    public function setUpdatedValue()
    {
        $this->unsynchronize();
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
     * ORM\PreRemove
     */
    public function setRemovedValue(LifecycleEventArgs $eventArgs)
    {
        $this->unsynchronize();
        $eventArgs->getEntityManager()->flush();
    }
}
