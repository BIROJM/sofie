<?php

namespace Sofie\ExpBundle\Entity;


/**
 * AppelTelephonique
 */
class AppelTelephonique
{
    const DECLA_PANNE = 1;
    const PRISE_CHARGE_PANNE = 2;
    const REP_PANNE_ENCOURS = 3;
    const PANNE_REPAREE = 4;
    const ABSENCE = 5;
    const REPRISE_SERVICE = 6;

    const STR_DECLA_PANNE = 'Déclaration de panne';
    const STR_PRISE_CHARGE_PANNE = 'Prise en charge de panne';
    const STR_REP_PANNE_ENCOURS = 'Réparation de panne en cours';
    const STR_PANNE_REPAREE = 'Panne réparée';
    const STR_ABSENCE = 'Absence';
    const STR_REPRISE_SERVICE = 'Reprise de service';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $dateAppel;

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
     * Set dateAppel
     *
     * @param \DateTime $dateAppel
     * @return AppelTelephonique
     */
    public function setDateAppel($dateAppel)
    {
        $this->dateAppel = $dateAppel;

        return $this;
    }

    /**
     * Get dateAppel
     *
     * @return \DateTime 
     */
    public function getDateAppel()
    {
        return $this->dateAppel;
    }

    /**
     * Set motif
     *
     * @param integer $motif
     * @return AppelTelephonique
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
     * @return AppelTelephonique
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
     * @return AppelTelephonique
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
     * Set sync
     *
     * @param string $sync
     * @return AppelTelephonique
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
     * @return AppelTelephonique
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

    static public function getMotifArrayAssoc()
    {
        return array(
            self::DECLA_PANNE => self::STR_DECLA_PANNE,
            self::PRISE_CHARGE_PANNE => self::STR_PRISE_CHARGE_PANNE,
            self::REP_PANNE_ENCOURS => self::STR_REP_PANNE_ENCOURS,
            self::PANNE_REPAREE => self::STR_PANNE_REPAREE,
            self::ABSENCE => self::STR_ABSENCE,
            self::REPRISE_SERVICE => self::STR_REPRISE_SERVICE
        );
    }

    /**
     * @param string $motif
     * @return string
     */
    public function getNormMotif($motif = '')
    {
        $motifArrayAssoc = self::getMotifArrayAssoc();
        if(array_key_exists(strval($this->motif), $motifArrayAssoc)){
            return $motifArrayAssoc[strval($this->motif)];
        }
        return '';
    }

    static public function getStaticNormMotif($motif)
    {
        $motifArrayAssoc = self::getMotifArrayAssoc();
        if(array_key_exists(strval($motif), $motifArrayAssoc)){
            return $motifArrayAssoc[strval($motif)];
        }
        return '';
    }
}
