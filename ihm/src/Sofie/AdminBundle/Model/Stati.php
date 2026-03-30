<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 25/09/2015
 * Time: 11:24
 */

namespace Sofie\AdminBundle\Model;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use TFox\MpdfPortBundle\Service\MpdfService;

class Stati
{
//    static public function exportToPdf(\mPDF $mpdf, $view='')
    static public function exportToPdf(MpdfService $mpdfService, $view='')
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '1200');
        $mpdf = $mpdfService->getMpdf();
        $mpdf->WriteHTML($view);
        $filename = preg_replace('/\W+/', '_', utf8_decode(strtolower($mpdf->title)));
        $response = new Response($mpdf->Output($filename.'.pdf', 'S'));
        $response->headers->set('Content-Type', 'application/pdf');
        return $response;
    }

    static public function generatePdfCriteres(array $criteres)
    {

    }

    static public function pagerMark()
    {
        return '_sfp';
    }
}