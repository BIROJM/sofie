<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 30/09/2015
 * Time: 19:15
 */

namespace Sofie\ExpBundle\Controller;


use Sofie\AdminBundle\Model\Stati;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NotificationController extends Controller
{
    static public function pdfExport(Controller $controller, array $criteres=array(), $pdfCriteres='')
    {
        $notifications = $controller->getDoctrine()->getManager()->getRepository('SofieExpBundle:Notification')
            ->getFullByCriteres($criteres);
        $view = $controller->renderView('SofieExpBundle:Notification:export/list.pdf.twig', array(
            'notifications'=>$notifications, 'criteres'=>rtrim(trim($pdfCriteres), ';,')
        ));
        return Stati::exportToPdf($controller->get('tfox.mpdfport'), $view);
    }
}