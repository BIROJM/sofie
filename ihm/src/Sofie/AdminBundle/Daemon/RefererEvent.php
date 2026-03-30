<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 21/09/2015
 * Time: 15:16
 */

namespace Sofie\AdminBundle\Daemon;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class RefererEvent extends Event
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * RefererEvent constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

}