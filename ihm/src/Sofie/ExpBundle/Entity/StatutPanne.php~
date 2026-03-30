<?php

namespace Sofie\ExpBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * StatutPanne
 */
class StatutPanne
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var string
     */
    private $icone;

    /**
     * @var string
     */
    private $couleur;

    /**
     * @var Collection
     */
    private $pannes;

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
        $this->pannes = new ArrayCollection();
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
     * Set libelle
     *
     * @param string $libelle
     * @return StatutPanne
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

    /**
     * Set icone
     *
     * @param string $icone
     * @return StatutPanne
     */
    public function setIcone($icone)
    {
        $this->icone = $icone;

        return $this;
    }

    /**
     * Get icone
     *
     * @return string 
     */
    public function getIcone()
    {
        return $this->icone;
    }

    /**
     * Set couleur
     *
     * @param string $couleur
     * @return StatutPanne
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
     * Add pannes
     *
     * @param \Sofie\ExpBundle\Entity\Panne $pannes
     * @return StatutPanne
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

    public function getWebPath()
    {
        return 'uploads/pannes/'.$this->icone;
    }

    /**
     * Set sync
     *
     * @param string $sync
     * @return StatutPanne
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
     * @return StatutPanne
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
}
