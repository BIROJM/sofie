<?php

namespace Sofie\AdminBundle\Model;

use Sofie\UserBundle\Entity\User;

/**
 * ChangePassword
 *
 */
class ChangePassword
{
    /**
     * @var string
     */
     protected $currentPassword;
     
     /**
     * @var string
     */
     protected $newPassword;
     
     
     /**
     * Set currentPassword
     *
     * @param string $currentPassword
     * @return User
     */
    public function setCurrentPassword($currentPassword)
    {
        $this->currentPassword = $currentPassword;

        return $this;
    }

    /**
     * Get currentPassword
     *
     * @return string 
     */
    public function getCurrentPassword()
    {
        return $this->currentPassword;
    }

    /**
     * Set newPassword
     *
     * @param string $newPassword
     * @return User
     */
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    /**
     * Get newPassword
     *
     * @return string 
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }
}
