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

class PanneController extends Controller
{
    static public function pdfExport(Controller $controller, array $criteres=array(), $pdfCriteres='')
    {
        $pannes = $controller->getDoctrine()->getManager()->getRepository('SofieExpBundle:Panne')
            ->getFullByCriteres($criteres);
        $view = $controller->renderView('SofieExpBundle:Panne:export/list.pdf.twig', array(
            'pannes'=>$pannes, 'criteres'=>rtrim(trim($pdfCriteres), ';,')
        ));
        return Stati::exportToPdf($controller->get('tfox.mpdfport'), $view);
    }
}