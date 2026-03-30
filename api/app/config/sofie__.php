<?php
/***
Fichier de configuration des URL des webservices de synchronisation SOFIE
***/
$region = 1;
$url = "http://192.168.1.109/sofiev4/api/public/"; //41.202.72.77 - 192.168.1.109

return array(
//	'checkOuvrageUrl' => $url . 'synchroGetOuvrage/' . $region, //Recuperation des ouvrages du site central vers le site regional
//	'checkCollecteUrl' => $url . 'synchroGetCollecte/' . $region, //Recuperation des ouvrages du site central vers le site regional
//	'checkPanneUrl' => $url . 'synchroGetPanne/' . $region, //Recuperation des ouvrages du site central vers le site regional
//	'checkNotificationUrl' => $url . 'synchroGetNotification/' . $region, //Recuperation des ouvrages du site central vers le site regional
//	'checkAppelTelephoniqueUrl' => $url . 'synchroGetAppelTelephonique/' . $region, //Recuperation des ouvrages du site central vers le site regional
	'checkEntityUrl' => $url . 'postGenericEntity/' . $region,
	'sendLocalEntityUrl' => $url . 'dataSynchroRegionaleCentrale/' . $region, //Envoi des entités locales non synchronisées au site centrale
	'notifyUrl' => $url . 'notifyEntity', //Notification de mise à jour effective du site régional au site central 
	'localSyncEntity' => array('Profile', 'NumAppel', 'Reparateur', 'Agent', 'Localite',  'Comite', 'Ouvrage', 'SuiviPhysicoChimique', 'EssaisPompage', 'CoupeGeologique', 'VenuEauPrincipale', 'Collecte'),
	'remoteSyncEntity' => array('Profile', 'NumAppel','Reparateur', 'Agent', 'Localite', 'Comite', 'Ouvrage', 'SuiviPhysicoChimique', 'EssaisPompage', 'CoupeGeologique', 'VenuEauPrincipale', 'Collecte', 'Panne', 'Notification', 'AppelTelephonique'),
);
