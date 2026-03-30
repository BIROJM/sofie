<?php

namespace Sofie\ExpBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Profile
 */
class Profile
{
    const PROFILE_COMITE = 1;
    const PROFILE_REPARATEUR = 2;
    const PROFILE_AGENT_FORMEN = 3;
    const PROFILE_SOCIOLOGUE = 4;
    const PROFILE_DIRECTEUR = 5;
    const PROFILE_ITINERANT = 100;
    const PROFILE_ADMIN = 140;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $designation;
	
	/**
     * @var string
     */
    private $commentaire;

    /**
     * @var Collection
     */
    private $numeroAppels;

    /**
     * @var Collection
     */
    private $agents;

    /**
     * @var Collection
     */
    private $codes;

    /**
     * @var string
     */
    private $sync;

    /**
     * @var \DateTime
     */
    private $deletedAt;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->numeroAppels = new ArrayCollection();
        $this->agents = new ArrayCollection();
        $this->codes = new ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Profile
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set designation
     *
     * @param string $designation
     * @return Profile
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
     * Set commentaire
     *
     * @param string $commentaire
     * @return Profile
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string 
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Add numeroAppels
     *
     * @param \Sofie\ExpBundle\Entity\NumeroAppel $numeroAppels
     * @return Profile
     */
    public function addNumeroAppel(\Sofie\ExpBundle\Entity\NumeroAppel $numeroAppels)
    {
        $this->numeroAppels[] = $numeroAppels;

        return $this;
    }

    /**
     * Remove numeroAppels
     *
     * @param \Sofie\ExpBundle\Entity\NumeroAppel $numeroAppels
     */
    public function removeNumeroAppel(\Sofie\ExpBundle\Entity\NumeroAppel $numeroAppels)
    {
        $this->numeroAppels->removeElement($numeroAppels);
    }

    /**
     * Get numeroAppels
     *
     * @return Collection
     */
    public function getNumeroAppels()
    {
        return $this->numeroAppels;
    }

    /**
     * Add agents
     *
     * @param \Sofie\ExpBundle\Entity\Agent $agents
     * @return Profile
     */
    public function addAgent(\Sofie\ExpBundle\Entity\Agent $agents)
    {
        $this->agents[] = $agents;

        return $this;
    }

    /**
     * Remove agents
     *
     * @param \Sofie\ExpBundle\Entity\Agent $agents
     */
    public function removeAgent(\Sofie\ExpBundle\Entity\Agent $agents)
    {
        $this->agents->removeElement($agents);
    }

    /**
     * Get agents
     *
     * @return Collection
     */
    public function getAgents()
    {
        return $this->agents;
    }

    /**
     * Add codes
     *
     * @param \Sofie\ExpBundle\Entity\Code $codes
     * @return Profile
     */
    public function addCode(\Sofie\ExpBundle\Entity\Code $codes)
    {
        $this->codes[] = $codes;

        return $this;
    }

    /**
     * Remove codes
     *
     * @param \Sofie\ExpBundle\Entity\Code $codes
     */
    public function removeCode(\Sofie\ExpBundle\Entity\Code $codes)
    {
        $this->codes->removeElement($codes);
    }

    /**
     * Get codes
     *
     * @return Collection
     */
    public function getCodes()
    {
        return $this->codes;
    }

    public function __toString()
    {
        return $this->designation;
    }

    /**
     * Set sync
     *
     * @param string $sync
     * @return Profile
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
     * @return Profile
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

    public function isAgentFormen()
    {
        return $this->id == self::PROFILE_AGENT_FORMEN;
    }

    public function isSociologue()
    {
        return $this->id == self::PROFILE_SOCIOLOGUE;
    }

    public function isDirecteur()
    {
        return $this->id == self::PROFILE_DIRECTEUR;
    }

    public function isAdmin()
    {
        return $this->id == self::PROFILE_ADMIN;
    }

    public function isItinerant()
    {
        return $this->id == self::PROFILE_ITINERANT;
    }

    public function mustInitialized()
    {
        return in_array($this->id, self::getMustInitializedArray(), true);
    }

    static public function getMustInitializedArray()
    {
        return array_unique(array_merge(
            array(self::PROFILE_REPARATEUR, self::PROFILE_COMITE),
            self::getAgentsInitializedArray()
        ));
    }

    static public function getForUserActionArray()
    {
        return array(self::PROFILE_AGENT_FORMEN, self::PROFILE_SOCIOLOGUE, self::PROFILE_DIRECTEUR, self::PROFILE_ITINERANT);
    }

    static public function getForAdminActionArray()
    {
        return array_unique(array_merge(self::getForUserActionArray(), array(self::PROFILE_ADMIN)));
    }

    static public function getNotInAgentListArray()
    {
        return array(self::PROFILE_ADMIN);
    }

    static public function getAgentsInitializedArray()
    {
        return array(self::PROFILE_AGENT_FORMEN, self::PROFILE_SOCIOLOGUE, self::PROFILE_DIRECTEUR);
    }
	
	
}
