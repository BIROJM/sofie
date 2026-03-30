<?php

namespace Sofie\ExpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Code
 */
class Code
{
    const STATUS_YES = 'Y';
    const STATUS_NO = 'N';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $code;

    /**
     * @var string
     */
    private $status;

    /**
     * @var \Sofie\ExpBundle\Entity\Profile
     */
    private $profile;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param integer $code
     * @return Code
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return integer 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Code
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set profile
     *
     * @param \Sofie\ExpBundle\Entity\Profile $profile
     * @return Code
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

    public function attribCode()
    {
        $this->status = static::STATUS_YES;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Code
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
     *
     */
    public function setUpdatedValue()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * Set sync
     *
     * @param string $sync
     * @return Code
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
     * @return Code
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
