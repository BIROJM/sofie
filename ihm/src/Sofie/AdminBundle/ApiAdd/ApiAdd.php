<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 13/08/2015
 * Time: 13:07
 */

namespace Sofie\AdminBundle\ApiAdd;


use Doctrine\ORM\EntityManager;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use Ras\Bundle\FlashAlertBundle\Model\AlertReporter;
use Sofie\AdminBundle\Controller\SessionInfo;
use Sofie\AdminBundle\Model\ConfigFile;
use Sofie\AdminBundle\Model\Logging;
use Sofie\AdminBundle\Model\ParameterFile;
use Sofie\ExpBundle\CheckUrl\CheckUrl;
use Sofie\ExpBundle\Entity\Ouvrage;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ApiAdd
{
    private $serializer;
    private $em;
    private $checkUrl;
    private $apiUrl;
    private $alertReporter;
    private $session;

    private $keyUrl;

    static private $site;

    public function __construct(
        Serializer $serializer, EntityManager $em, CheckUrl $checkUrl, $apiUrl,
        AlertReporter $alertReporter, RequestStack $requestStack
    )
    {
        $this->serializer = $serializer;
        $this->em = $em;
        $this->checkUrl = $checkUrl;
        $this->apiUrl = $apiUrl;
        $this->alertReporter = $alertReporter;
        $this->session = $requestStack->getCurrentRequest()->getSession();
        $this->keyUrl = SessionInfo::getApiGetKeyUrl($this->session);
        self::$site = ParameterFile::loadSite();
    }

    /*
     * Proccess
     * */

    /**
     * @param $object
     * @param string $model
     * @param string $username
     * @return bool
     */
    public function proccess($object, $model='', $username='')
    {
        try{
            if(ConfigFile::loadAddMode()['mode'] == ConfigFile::ADD_GET_ENTITY){
                $result = $this->send($object, $model, $username);
            }else{
                $result = $this->sendKey($object, $model, $username);
                if($result){
                    if($model == "User"){
                        if(method_exists($object, "getAgent") && !is_null($object->getAgent())){
                            $result = $this->sendKey($object->getAgent(), "Agent", $username);
                            if(!is_null(self::$site) && is_null($object->getAgent()->getRegion())){
                                $object->getAgent()->setRegion($this->em->getRepository('SofieExpBundle:Region')->find(intval(self::$site)));
                            }
                        }
                    }
                    if($model == "Agent"){
                        if(method_exists($object, "getUser") && !is_null($object->getUser())){
                            $result = $this->sendKey($object->getUser(), "User", $username);
                        }
                        if(!is_null(self::$site) && method_exists($object, "getRegion")){
                            if(is_null($object->getRegion())){
                                $object->setRegion($this->em->getRepository('SofieExpBundle:Region')->find(intval(self::$site)));
                            }
                        }
                    }
                    $this->em->persist($object);
                    $this->em->flush();
                    $this->alertReporter->addSuccess("Succès !");
                }
            }

            if($result){
                $logMsg = '['.$username.'] '.$model.' créé avec succès';
                if(method_exists($object, 'logString')){
                    $logMsg .= ' - infos['.$object->logString().']';
                }
                Logging::write($logMsg);
            }
            return $result;
        }catch (\Exception $e){
            $this->alertReporter->addWarning("Erreur survenue : Veiller contacter votre administrateur !");
            $this->alertReporter->addWarning($e->getMessage());
            return null;
        }
    }

    /**
     * @param $object
     * @param string $model
     * @param string $username
     * @return array
     */
    public function ajaxProccess($object, $model='', $username='')
    {
        try{
            if(ConfigFile::loadAddMode()['mode'] == ConfigFile::ADD_GET_ENTITY){
                $result = $this->ajaxSend($object, $model, $username);
            }else{
                $result = $this->ajaxSendKey($object, $model, $username);
            }

            if($result['success']){
                $logMsg = '['.$username.'] '.$model.' créé avec succès';
                if(method_exists($object, 'logString')){
                    $logMsg .= ' - infos['.$object->logString().']';
                }
                Logging::write($logMsg);
            }

            return $result;
        }catch (\Exception $e){
            $result['success'] = false;
            $this->alertReporter->addWarning("Erreur survenue : Veiller contacter votre administrateur !");
            return $result;
        }
    }

    /**
     * @param $ouvrage
     * @param string $username
     * @return bool
     */
    public function proccessOuvrage($ouvrage, $username='')
    {
        try{
            if(ConfigFile::loadAddMode()['mode'] == ConfigFile::ADD_GET_ENTITY){
                $result = $this->sendOuvrage($ouvrage, $username);
            }else{
                $result = $this->sendOuvrageKey($ouvrage, $username);
            }

            if($result){
                $logMsg = '['.$username.'] Ouvrage créé avec succès';
                if(method_exists($ouvrage, 'logString')){
                    $logMsg .= ' - infos ['.$ouvrage->logString().']';
                }
                Logging::write($logMsg);
            }

            return $result;
        }catch (\Exception $e){
            $this->alertReporter->addWarning("Erreur survenue : Veiller contacter votre administrateur !");
            return null;
        }
    }

    /*
     * Send
     * */

    public function sendKey($object, $model='', $username='')
    {
        $content = array(
            'model'=>$model,
            'username'=>$username,
            'region'=>ParameterFile::loadSite()
        );
        $opts = array(
            'http'=>array(
                'method'=>'POST',
                'header'=>'Content-type: application/json',
                'content'=> json_encode($content, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
            )
        );
        $context = stream_context_create($opts);
        if($this->checkUrl->check($this->keyUrl)){
            if (($response = file_get_contents($this->keyUrl, false, $context)) != false){
                $id = intval($response);
                if($response > 0){
                    if(method_exists($object, 'setId')){
                        $object->setId($id);
                    }
                    return true;
                }else{
                    $this->alertReporter->addWarning("Impossible d'obtenir la clé !");
                }
            }else{
                $this->alertReporter->addWarning("Une erreur s'est produit !");
            }
        }else{
            $this->alertReporter->addWarning("Impossible de contacter le serveur !");
        }
        return false;
    }

    protected function ajaxSendKey($object, $model='', $username='')
    {
        $result = array('success'=>false);
        $content = array(
            'model'=>$model,
            'username'=>$username,
            'region'=>ParameterFile::loadSite()
        );
        $opts = array(
            'http'=>array(
                'method'=>'POST',
                'header'=>'Content-type: application/json',
                'content'=> json_encode($content, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
            )
        );
        $context = stream_context_create($opts);
        if($this->checkUrl->check($this->keyUrl)){
            if (($response = file_get_contents($this->keyUrl, false, $context)) !== false){
                $id = intval($response);
                if($response > 0){
                    if(method_exists($object, 'setId')){
                        $object->setId($id);
                    }
                    if($model == 'Groupe'){
                        foreach($object->getUsers() as $user){
                            $user->addGroupe($object);
                            $this->em->persist($user);
                        }
                    }
                    $this->em->persist($object);
                    $this->em->flush();
                    $result['msg'] = "Succès !";
                    $result['success'] = true;
                }else{
                    $result['msg'] = "Impossible d'obtenir la clé !";
                }
            }else{
                $result['msg'] = "Une erreur s'est produit !";
            }
        }else{
            $result['msg'] = "Impossible de contacter le serveur !";
        }
        return $result;
    }

    protected function sendOuvrageKey(Ouvrage $ouvrage, $username='')
    {
        $essaisPompageArray = $ouvrage->getEssaisPompages()->count();
        $coupeGeologiqueArray = $ouvrage->getCoupeGeologiques()->count();
        $equipementForageArray = $ouvrage->getEquipementForages()->count();
        $suiviPhysicoChimiqueArray = $ouvrage->getSuiviPhysicoChimiques()->count();
        $venuEauPrincipaleArray = $ouvrage->getVenuEauPrincipales()->count();
        $content = array(
            'model'=>'Ouvrage',
            'username'=>$username,
            'region'=>ParameterFile::loadSite(),
            'essaisPompageArray'=>$essaisPompageArray,
            'coupeGeologiqueArray'=>$coupeGeologiqueArray,
            'equipementForageArray'=>$equipementForageArray,
            'suiviPhysicoChimiqueArray'=>$suiviPhysicoChimiqueArray,
            'venuEauPrincipaleArray'=>$venuEauPrincipaleArray
        );
        $opts = array(
            'http'=>array(
                'method'=>'POST',
                'header'=>'Content-type: application/json',
                'content'=> json_encode($content, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
            )
        );
        $context = stream_context_create($opts);
        if($this->checkUrl->check($this->keyUrl)){
            if (($response = file_get_contents($this->keyUrl, false, $context)) !== false){
                $arrayResp = json_decode($response, true);
                if(array_key_exists('idOuvrage', $arrayResp) && intval($arrayResp['idOuvrage'])>0){
                    if(array_key_exists('idOuvrage', $arrayResp)){
                        $ouvrage->setId($arrayResp['idOuvrage']);
                        $ouvrage->generateCode();
                        $inc = 0;
                        foreach($ouvrage->getCoupeGeologiques() as $coupe){
                            $coupe->setId($arrayResp['elementsId']['idCoupeGeologique'][$inc++]);
                        }
                        $inc = 0;
                        foreach($ouvrage->getEquipementForages() as $equip){
                            $equip->setId($arrayResp['elementsId']['idEquipementForage'][$inc++]);
                        }
                        $inc = 0;
                        foreach($ouvrage->getEssaisPompages() as $essais){
                            $essais->setId($arrayResp['elementsId']['idEssaisPompage'][$inc++]);
                        }
                        $inc = 0;
                        foreach($ouvrage->getSuiviPhysicoChimiques() as $suivi){
                            $suivi->setId($arrayResp['elementsId']['idSuiviPhysicoChimique'][$inc++]);
                        }
                        $inc = 0;
                        foreach($ouvrage->getVenuEauPrincipales() as $venuEau){
                            $venuEau->setId($arrayResp['elementsId']['idVenuEauPrincipale'][$inc++]);
                        }
                        $this->em->persist($ouvrage);
                        $this->em->flush();
                        $this->alertReporter->addSuccess('Succès !<br />Code:'.$ouvrage->getCode());
                        return true;
                    }
                }else{
                    $this->alertReporter->addWarning("Impossible d'obtenir la clé !");
                }
            }else{
                $this->alertReporter->addWarning("Une erreur s'est produit !");
            }
        }else{
            $this->alertReporter->addWarning("Impossible de contacter le serveur !");
        }
        return false;
    }


    /**
     * @param $object
     * @param string $model
     * @param string $username
     * @return bool
     */
    protected function send($object, $model='', $username='')
    {
        $this->em->persist($object);
        $entityArray = json_decode($this->serializer->serialize($object, 'json', SerializationContext::create()->enableMaxDepthChecks()), true);
        $content = array(
            'model'=>$model,
            'username'=>$username,
            'region'=>ParameterFile::loadSite()
        );
        if($model == 'User'){
            $content['userArray'] = $entityArray;
            $groupeArray = array();
            foreach($object->getGroupes() as $groupe){
                $groupeArray[] = array('groupe_id'=>$groupe->getId());
            }
            $content['userGroupArray'] = $groupeArray;

        }elseif($model == 'Groupe'){
            $content['groupeArray'] = $entityArray;
            $userArray = array();
            $droitArray = array();
            foreach($object->getUsers() as $user){
                $userArray[] = array('user_id'=>$user->getId());
            }
            foreach($object->getDroits() as $droit){
                $droitArray[] = array('droit_id'=>$droit->getId());
            }
            $content['userArray'] = $userArray;
            $content['droitArray'] = $droitArray;
        }else{
            $content['entityArray'] = $entityArray;
        }
        $opts = array(
            'http'=>array(
                'method'=>'POST',
                'header'=>'Content-type: application/json',
                'content'=> json_encode($content, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
            )
        );
//                dump(json_encode($content, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));die;
//        dump(json_encode($content, JSON_UNESCAPED_UNICODE));die;
        $context = stream_context_create($opts);
        if($this->checkUrl->check($this->apiUrl)){
            if (($response = file_get_contents($this->apiUrl, false, $context)) != false){
                /*dump(json_encode($content, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
                dump($response);die;*/
                $arrayResp = json_decode($response, true);
                if(array_key_exists('status', $arrayResp) && strtoupper($arrayResp['status'])=='OK'){
                    if(array_key_exists('id', $arrayResp)){
                        if(method_exists($object, 'setId')){
                            $object->setId($arrayResp['id']);
                        }
                        if(method_exists($object, 'synchronize')){
                            $object->synchronize();
                        }
                        $this->em->detach($object);
                        if($model == 'Groupe'){
                            foreach($object->getUsers() as $user){
                                $user->addGroupe($object);
                                $this->em->persist($user);
                            }
                        }
                        $this->em->persist($object);
                        $this->em->flush();
                        $this->alertReporter->addSuccess("Succès !");
                        return true;
                    }
                }else{
                    $this->alertReporter->addWarning($arrayResp['error']);
                }
            }else{
                $this->alertReporter->addWarning("Une erreur s'est produit !");
            }
        }else{
            $this->alertReporter->addWarning("Impossible de contacter le serveur !");
        }
        return false;
    }

    protected function ajaxSend($object, $model='', $username='')
    {
        $result = array('success'=>false);
        $this->em->persist($object);
        $content = array(
            'model'=>$model,
            'username'=>$username,
            'region'=>ParameterFile::loadSite(),
            'entityArray' => json_decode($this->serializer->serialize($object, 'json', SerializationContext::create()->enableMaxDepthChecks()), true)
        );
        $opts = array(
            'http'=>array(
                'method'=>'POST',
                'header'=>'Content-type: application/json',
                'content'=> json_encode($content, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
            )
        );
//                dump(json_encode($content, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));die;
        $context = stream_context_create($opts);
        if($this->checkUrl->check($this->apiUrl)){
            if (($response = file_get_contents($this->apiUrl, false, $context)) !== false){
                /*dump(json_encode($content, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
                dump($response);die;*/
                $arrayResp = json_decode($response, true);
                if(array_key_exists('status', $arrayResp) && strtoupper($arrayResp['status'])=='OK'){
                    if(array_key_exists('id', $arrayResp)){
                        if(method_exists($object, 'setId')){
                            $object->setId($arrayResp['id']);
                        }
                        if(method_exists($object, 'synchronize')){
                            $object->synchronize();
                        }
                        $this->em->detach($object);
                        $this->em->persist($object);
                        $this->em->flush();
                        $result['success'] = true;
                        $result['msg'] = 'Succès !';
                    }
                }else{
                    $result['msg'] = $arrayResp['error'];
                }
            }else{
                $result['msg'] = "Une erreur s'est produit !";
            }
        }else{
            $result['msg'] = "Impossible de contacter le serveur !";
        }
        return $result;
    }

    protected function sendOuvrage(Ouvrage $ouvrage, $username='')
    {
        $this->em->persist($ouvrage);
        $ouvrageArray = json_decode($this->serializer->serialize($ouvrage, 'json', SerializationContext::create()->enableMaxDepthChecks()), true);
        $coupeGeologiqueArray = json_decode($this->serializer->serialize($ouvrage->getcoupeGeologiques(), 'json', SerializationContext::create()->enableMaxDepthChecks()), true);
        $essaisPompageArray = json_decode($this->serializer->serialize($ouvrage->getEssaisPompages(), 'json', SerializationContext::create()->enableMaxDepthChecks()), true);
        $equipementForageArray = json_decode($this->serializer->serialize($ouvrage->getEquipementForages(), 'json', SerializationContext::create()->enableMaxDepthChecks()), true);
        $suiviPhysicoChimiqueArray = json_decode($this->serializer->serialize($ouvrage->getSuiviPhysicoChimiques(), 'json', SerializationContext::create()->enableMaxDepthChecks()), true);
        $venuEauPrincipaleArray = json_decode($this->serializer->serialize($ouvrage->getVenuEauPrincipales(), 'json', SerializationContext::create()->enableMaxDepthChecks()), true);
        $content = array(
            'model'=>'Ouvrage',
            'username'=>$username,
            'region'=>ParameterFile::loadSite(),
            'ouvrageArray' => $ouvrageArray,
            'essaisPompageArray'=>$essaisPompageArray,
            'coupeGeologiqueArray'=>$coupeGeologiqueArray,
            'equipementForageArray'=>$equipementForageArray,
            'suiviPhysicoChimiqueArray'=>$suiviPhysicoChimiqueArray,
            'venuEauPrincipaleArray'=>$venuEauPrincipaleArray
        );
        $opts = array(
            'http'=>array(
                'method'=>'POST',
                'header'=>'Content-type: application/json',
                'content'=> json_encode($content, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
            )
        );
//        dump(json_encode($content, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));die;
        $context = stream_context_create($opts);
        if($this->checkUrl->check($this->apiUrl)){
            if (($response = file_get_contents($this->apiUrl, false, $context)) !== false){
                $arrayResp = json_decode($response, true);
                if(array_key_exists('status', $arrayResp) && strtoupper($arrayResp['status'])=='OK'){
                    if(array_key_exists('idOuvrage', $arrayResp)){
                        $ouvrage->setId($arrayResp['idOuvrage']);
                        $ouvrage->setCode($arrayResp['codeOuvrage']);
                        $ouvrage->synchronize();
                        foreach($ouvrage->getCoupeGeologiques() as $coupe){
                            $posIndex = $this->recursive_array_search($coupe->getPosition(), $arrayResp['elementsId']['idCoupeGeologique']);
                            if(method_exists($coupe, 'setId')){
                                $coupe->setId($arrayResp['elementsId']['idCoupeGeologique'][$posIndex]['insertedId']);
                            }
                            if(method_exists($coupe, 'synchronize')){
                                $coupe->synchronize();
                            }
                        }
                        foreach($ouvrage->getEquipementForages() as $equip){
                            $posIndex = $this->recursive_array_search($equip->getPosition(), $arrayResp['elementsId']['idEquipementForage']);
                            if(method_exists($equip, 'setId')){
                                $equip->setId($arrayResp['elementsId']['idEquipementForage'][$posIndex]['insertedId']);
                            }
                            if(method_exists($equip, 'synchronize')){
                                $equip->synchronize();
                            }
                        }
                        foreach($ouvrage->getEssaisPompages() as $essais){
                            $posIndex = $this->recursive_array_search($essais->getPosition(), $arrayResp['elementsId']['idEssaisPompage']);
                            if(method_exists($essais, 'setId')){
                                $essais->setId($arrayResp['elementsId']['idEssaisPompage'][$posIndex]['insertedId']);
                            }
                            if(method_exists($essais, 'synchronize')){
                                $essais->synchronize();
                            }
                        }
                        foreach($ouvrage->getSuiviPhysicoChimiques() as $suivi){
                            $posIndex = $this->recursive_array_search($suivi->getPosition(), $arrayResp['elementsId']['idSuiviPhysicoChimique']);
                            if(method_exists($suivi, 'setId')){
                                $suivi->setId($arrayResp['elementsId']['idSuiviPhysicoChimique'][$posIndex]['insertedId']);
                            }
                            if(method_exists($suivi, 'synchronize')){
                                $suivi->synchronize();
                            }
                        }
                        foreach($ouvrage->getVenuEauPrincipales() as $venuEau){
                            $posIndex = $this->recursive_array_search($venuEau->getPosition(), $arrayResp['elementsId']['idVenuEauPrincipale']);
                            if(method_exists($venuEau, 'setId')){
                                $venuEau->setId($arrayResp['elementsId']['idVenuEauPrincipale'][$posIndex]['insertedId']);
                            }
                            if(method_exists($venuEau, 'synchronize')){
                                $venuEau->synchronize();
                            }
                        }
                        $this->em->detach($ouvrage);
                        $this->em->persist($ouvrage);
                        $this->em->flush();
                        $this->alertReporter->addSuccess("Succès !");
                        return true;
                    }
                }else{
                    $this->alertReporter->addWarning($arrayResp['error']);
                }
            }else{
                $this->alertReporter->addWarning("Une erreur s'est produit !");
            }
        }else{
            $this->alertReporter->addWarning("Impossible de contacter le serveur !");
        }
        return false;
    }

    protected function recursive_array_search($needle,$haystack) {
        foreach($haystack as $key=>$value) {
            $current_key=$key;
            if($needle===$value OR (is_array($value) && $this->recursive_array_search($needle,$value) !== false)) {
                return $current_key;
            }
        }
        return false;
    }
}