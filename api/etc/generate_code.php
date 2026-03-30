<?php

//$setting = array("3" => 50,  )

ini_set('max_execution_time', 0);

$profile = 1;
$status = 'N';
$nbCode = 500;

try 
{
	$db = new PDO('mysql:host=localhost;dbname=db_sofie', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch (Exception $e)
{
	die('Erreur : ' . $e->getMessage());
}	
	
	for($i=0; $i < $nbCode; $i++)
	{
		$random = mt_rand(10000,99999);
		$code = $profile . $random;
		$sql = "select code from t_code where code = " . $code;
		$lines = $db->query($sql);
		$allCode = $lines->fetchAll();
		
		if(count($allCode) == 0)
		{		
			$request = "INSERT INTO t_code(code, status, profile) VALUES(" .$code . ", '" . $status . "', " . $profile . ")";
			$db->exec($request);
		}		
	}
exit;
