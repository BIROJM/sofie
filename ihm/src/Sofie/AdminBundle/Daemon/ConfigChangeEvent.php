<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 21/09/2015
 * Time: 15:16
 */

namespace Sofie\AdminBundle\Daemon;

use Sofie\AdminBundle\Entity\Config;
use Symfony\Component\EventDispatcher\Event;

class ConfigChangeEvent extends Event
{
    /**
     * @var string
     */
    protected $message;

    /**
     * @var Config
     */
    protected $config;

    /**
     * ConfigChangeEvent constructor.
     * @param string $message
     * @param Config $config
     */
    public function __construct($message, Config $config)
    {
        $this->message = $message;
        $this->config = $config;
    }


    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param Config $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }
}