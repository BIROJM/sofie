<?php
session_start();
require_once('../plugins/mpdf/mpdf.php');

//echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; exit;

//$html = "<b>hello</b>";
//transfert de session
$opts = array( 'http'=>array( 'method'=>"GET",
              'header'=>"Accept-language: fr\r\n" .
               "Cookie: ".session_name()."=".session_id()."\r\n" ) );
$context = stream_context_create($opts);
session_write_close();

$html = file_get_contents('http://' .$_SESSION['localIp'] . $_SESSION['url_stat'] . 'listAgentPdf.php?date_debut=' . $_GET['date_debut'] . '&date_fin=' . $_GET['date_fin'] . '&regions=' . $_GET['regions'] . '&localite=' .  $_GET['localite'] . '&id=' . $_GET['id'], false, $context);
$mpdf=new mPDF(); 
$stylesheet = file_get_contents("../css/pdf.css");
$mpdf->WriteHTML($stylesheet, 1);
$mpdf->WriteHTML($html);
$mpdf->Output();
exit;
?>