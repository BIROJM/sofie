<?php

namespace Sofie\AdminBundle\Entity;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Sofie\AdminBundle\Model\ParameterFile;

/**
 * Config
 */
class Config
{
    const SESSION_NAME = 'sofie_session_config';

    const SYNC = 'Y';
    const NO_SYNC = 'N';

    const DEFAULT_NB_PAGINATOR = 5;
    const DEFAULT_OFFSET_PAGINATOR = 50;
    const DEFAULT_STAT_URL = 'http://localhost/sofiestat/';
    const DEFAULT_API_GET_KEY_URL = 'http://localhost/sofieapi/public/getEntityKey';
    const DEFAULT_SMS_GW = 'http://localhost/sms/sendsms.php';
    const DEFAULT_CARTE_REGION_URL = 'http://localhost/sofieapi/public/carte/selectRegion/';
    const DEFAULT_CARTE_CENTRAL_URL = 'http://localhost/sofieapi/public/carte';

    const SESSNAME_NB_PAGINATOR = 'nb_paginator';
    const SESSNAME_OFFSET_PAGINATOR = 'offset_paginator';
    const SESSNAME_STAT_URL = 'stat_url';
    const SESSNAME_SMS_GW = 'sms_gw';
    const SESSNAME_SMS_SOA = 'sms_soa';
    const SESSNAME_CARTE_REGION_URL = 'carte_region_url';
    const SESSNAME_CARTE_CENTRAL_URL = 'carte_central_url';
    const SESSNAME_API_GET_KEY_URL = 'api_get_key_url';
    const SESSNAME_CONFIG_ID = 'config_id';
    const SESSNAME_REGION_ID = 'region_id';


    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $phoneNumber;

    /**
     * @var boolean
     */
    private $isCentral;

    /**
     * @var string
     */
    private $smsGw;

    /**
     * @var string
     */
    private $smsSoa;

    /**
     * @var integer
     */
    private $nbPaginator;

    /**
     * @var integer
     */
    private $offsetPaginator;

    /**
     * @var string
     */
    private $statUrl;

    /**
     * @var string
     */
    private $carteRegionUrl;

    /**
     * @var string
     */
    private $carteCentralUrl;

    /**
     * @var string
     */
    private $apiGetKeyUrl;

    /**
     * @var string
     */
    private $sync;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \Sofie\ExpBundle\Entity\Region
     */
    private $region;


    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->nbPaginator = self::DEFAULT_NB_PAGINATOR;
        $this->offsetPaginator = self::DEFAULT_OFFSET_PAGINATOR;
//        $this->apiGetKeyUrl = self::DEFAULT_API_GET_KEY_URL;
//        $this->statUrl = self::DEFAULT_STAT_URL;
//        $this->smsGw = self::DEFAULT_SMS_GW;
//        $this->carteCentralUrl = self::DEFAULT_CARTE_CENTRAL_URL;
//        $this->carteRegionUrl = self::DEFAULT_CARTE_REGION_URL;

        if(is_null(ParameterFile::loadSite())){
            $this->isCentral = true;
        }

        $this->sync = self::NO_SYNC;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Config
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
     * Set phoneNumber
     *
     * @param string $phoneNumber
     * @return Config
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string 
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set isCentral
     *
     * @param boolean $isCentral
     * @return Config
     */
    public function setIsCentral($isCentral)
    {
        $this->isCentral = $isCentral;

        return $this;
    }

    /**
     * Get isCentral
     *
     * @return boolean 
     */
    public function getIsCentral()
    {
        return $this->isCentral;
    }

    /**
     * Set smsGw
     *
     * @param string $smsGw
     * @return Config
     */
    public function setSmsGw($smsGw)
    {
        $this->smsGw = $smsGw;

        return $this;
    }

    /**
     * Get smsGw
     *
     * @return string 
     */
    public function getSmsGw()
    {
        return $this->smsGw;
    }

    /**
     * Set smsSoa
     *
     * @param string $smsSoa
     * @return Config
     */
    public function setSmsSoa($smsSoa)
    {
        $this->smsSoa = $smsSoa;

        return $this;
    }

    /**
     * Get smsSoa
     *
     * @return string 
     */
    public function getSmsSoa()
    {
        return $this->smsSoa;
    }

    /**
     * Set nbPaginator
     *
     * @param integer $nbPaginator
     * @return Config
     */
    public function setNbPaginator($nbPaginator)
    {
        $this->nbPaginator = $nbPaginator;

        return $this;
    }

    /**
     * Get nbPaginator
     *
     * @return integer 
     */
    public function getNbPaginator()
    {
        return $this->nbPaginator;
    }

    /**
     * Set offsetPaginator
     *
     * @param integer $offsetPaginator
     * @return Config
     */
    public function setOffsetPaginator($offsetPaginator)
    {
        $this->offsetPaginator = $offsetPaginator;

        return $this;
    }

    /**
     * Get offsetPaginator
     *
     * @return integer 
     */
    public function getOffsetPaginator()
    {
        return $this->offsetPaginator;
    }

    /**
     * Set statUrl
     *
     * @param string $statUrl
     * @return Config
     */
    public function setStatUrl($statUrl)
    {
        $this->statUrl = $statUrl;

        return $this;
    }

    /**
     * Get statUrl
     *
     * @return string 
     */
    public function getStatUrl()
    {
        return $this->statUrl;
    }

    /**
     * Set carteRegionUrl
     *
     * @param string $carteRegionUrl
     * @return Config
     */
    public function setCarteRegionUrl($carteRegionUrl)
    {
        $this->carteRegionUrl = $carteRegionUrl;

        return $this;
    }

    /**
     * Get carteRegionUrl
     *
     * @return string 
     */
    public function getCarteRegionUrl()
    {
        return $this->carteRegionUrl;
    }

    /**
     * Set carteCentralUrl
     *
     * @param string $carteCentralUrl
     * @return Config
     */
    public function setCarteCentralUrl($carteCentralUrl)
    {
        $this->carteCentralUrl = $carteCentralUrl;

        return $this;
    }

    /**
     * Get carteCentralUrl
     *
     * @return string 
     */
    public function getCarteCentralUrl()
    {
        return $this->carteCentralUrl;
    }

    /**
     * Set apiGetKeyUrl
     *
     * @param string $apiGetKeyUrl
     * @return Config
     */
    public function setApiGetKeyUrl($apiGetKeyUrl)
    {
        $this->apiGetKeyUrl = $apiGetKeyUrl;

        return $this;
    }

    /**
     * Get apiGetKeyUrl
     *
     * @return string 
     */
    public function getApiGetKeyUrl()
    {
        return $this->apiGetKeyUrl;
    }

    /**
     * Set sync
     *
     * @param string $sync
     * @return Config
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Config
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
     * Set region
     *
     * @param \Sofie\ExpBundle\Entity\Region $region
     * @return Config
     */
    public function setRegion(\Sofie\ExpBundle\Entity\Region $region = null)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return \Sofie\ExpBundle\Entity\Region 
     */
    public function getRegion()
    {
        return $this->region;
    }

    public function setCustomId(LifecycleEventArgs $eventArgs)
    {
        /*if($this->id == null){
            $this->id = $eventArgs->getEntityManager()->getRepository('SofieAdminBundle:Config')->getNextId();
        }*/
    }

    public function setCreatedValue()
    {
        $this->updatedAt = new \DateTime();
    }

    public function setUpdatedValue()
    {
        $this->updatedAt = new \DateTime();
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

    public function sessionArray()
    {
        return array(
            'config_id' => $this->id,
            'sms_gw' => $this->smsGw,
            'sms_soa' => $this->smsSoa,
            'is_central' => $this->isCentral,
            'nb_paginator' => $this->nbPaginator,
            'offset_paginator' => $this->offsetPaginator,
            'stat_url' => $this->statUrl,
            'carte_central_url' => $this->carteCentralUrl,
            'carte_region_url' => $this->carteRegionUrl,
            'api_get_key_url' => $this->apiGetKeyUrl,
            'region_id' => ($this->getRegion() !== null) ? $this->getRegion()->getId() : null,
            'region_infos' => ($this->getRegion() !== null)
                ? array('id'=>$this->getRegion()->getId(), 'name'=>$this->getRegion()->getNom())
                : null
        );
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
