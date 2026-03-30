<?php

namespace Sofie\ExpBundle\Entity;


/**
 * Notification
 */
class Notification
{
    const ACCUSE_DECLA_PANNE = 1;
    const PANNE_DECLAREE = 2;
    const PRISE_CHARGE_PANNE = 3;
    const REPARATION_ENCOURS = 4;
    const PANNE_REPAREE = 5;
    const ALERTE_PANNE_SOUFFRANCE = 6;
    const ABSENCE = 7;
    const REPRISE_SERVICE = 8;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $dateHeureNotif;

    /**
     * @var integer
     */
    private $motif;

    /**
     * @var \Sofie\ExpBundle\Entity\Panne
     */
    private $panne;

    /**
     * @var \Sofie\ExpBundle\Entity\NumeroAppel
     */
    private $numeroAppel;

    /**
     * @var string
     */
    private $sync;

    /**
     * @var \DateTime
     */
    private $deletedAt;


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
     * Set dateHeureNotif
     *
     * @param \DateTime $dateHeureNotif
     * @return Notification
     */
    public function setDateHeureNotif($dateHeureNotif)
    {
        $this->dateHeureNotif = $dateHeureNotif;

        return $this;
    }

    /**
     * Get dateHeureNotif
     *
     * @return \DateTime 
     */
    public function getDateHeureNotif()
    {
        return $this->dateHeureNotif;
    }

    /**
     * Set motif
     *
     * @param integer $motif
     * @return Notification
     */
    public function setMotif($motif)
    {
        $this->motif = $motif;

        return $this;
    }

    /**
     * Get motif
     *
     * @return integer 
     */
    public function getMotif()
    {
        return $this->motif;
    }

    /**
     * Set panne
     *
     * @param \Sofie\ExpBundle\Entity\Panne $panne
     * @return Notification
     */
    public function setPanne(\Sofie\ExpBundle\Entity\Panne $panne = null)
    {
        $this->panne = $panne;

        return $this;
    }

    /**
     * Get panne
     *
     * @return \Sofie\ExpBundle\Entity\Panne 
     */
    public function getPanne()
    {
        return $this->panne;
    }

    /**
     * Set numeroAppel
     *
     * @param \Sofie\ExpBundle\Entity\NumeroAppel $numeroAppel
     * @return Notification
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
     * @param string $motif
     * @return string
     */
    public static function getNormMotif($motif = '')
    {
        $norm = '';
        switch($motif){
            case static::ACCUSE_DECLA_PANNE:
                $norm = 'Accusé de réception de déclaration de panne';
                break;
            case static::PANNE_DECLAREE:
                $norm = 'Notification de panne déclarée';
                break;
            case static::PRISE_CHARGE_PANNE:
                $norm = 'Notification de prise en charge d’une panne';
                break;
            case static::REPARATION_ENCOURS:
                $norm = 'Notification de réparation en cours';
                break;
            case static::PANNE_REPAREE:
                $norm = 'Notification de panne réparée';
                break;
            case static::ALERTE_PANNE_SOUFFRANCE:
                $norm = 'Alerte panne en souffrance';
                break;
            case static::ABSENCE:
                $norm = 'Notification d’absence';
                break;
            case static::REPRISE_SERVICE:
                $norm = 'Notification de reprise de service';
                break;
        }

        return $norm;
    }

    /**
     * Set sync
     *
     * @param string $sync
     * @return Notification
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
     * @return Notification
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
