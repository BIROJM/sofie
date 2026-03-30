<?php

namespace Sofie\ExpBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Sofie\AdminBundle\Controller\SessionInfo;
use Sofie\AdminBundle\Model\ParameterFile;
use Sofie\AdminBundle\Model\Stati;
use Sofie\ExpBundle\Entity\AppelTelephonique;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AppelController extends Controller
{
    protected static $site;

    public function __construct()
    {
        self::$site = ParameterFile::loadSite();

    }

    /**
     * @Security("has_role('ROLE_APPEL')")
     */
    public function indexAction(Request $request, $page = 1)
    {
        if($page < 1){
            throw new \InvalidArgumentException("Page introuvable");
        }
        if(!$request->query->get(Stati::pagerMark(), null)) $page=1;
        $form = $this->critereForm();
        $em = $this->getDoctrine()->getManager();
        $offset = SessionInfo::getPaginationOffset($request->getSession());
        $criteres = array();
        $pdfCriteres = '';

        if($this->isCritere($request)){
            $validDate = $this->get('sofie.valid_date');

            $dmin = $request->query->get('dmin', null);
            if(!is_null($dmin)){
                $dmin = trim(urldecode($dmin));
                $dmin = $validDate->isDate(strval($dmin))?(\DateTime::createFromFormat('d/m/Y', $dmin)):null;
                $criteres['dmin'] = $dmin;
                if($form->has('dmin')){
                    $form->get('dmin')->setData($dmin);
                }
            }

            $dmax = $request->query->get('dmax', null);
            if(!is_null($dmax)){
                $dmax = trim(urldecode($dmax));
                $dmax = $validDate->isDate(strval($dmax))?(\DateTime::createFromFormat('d/m/Y', $dmax)):null;
                $criteres['dmax'] = $dmax;
                if($form->has('dmax')){
                    $form->get('dmax')->setData($dmax);
                }
            }

            $region = $em->getRepository('SofieExpBundle:Region')->find(intval(urldecode($request->query->get('region', null))));
            if(!is_null($region)){
                $criteres['region'] = $region;
                if($form->has('region')){
                    $form->get('region')->setData($region);
                }
            }

            $origine = $em->getRepository('SofieExpBundle:Profile')->find(intval(urldecode($request->query->get('origine', null))));
            if(!is_null($origine)){
                $criteres['origine'] = $origine;
                if($form->has('origine')){
                    $form->get('origine')->setData($origine);
                }
            }

            $motif = $request->query->get('motif', null);
            if(!is_null($motif)){
                $motif = intval(urldecode($motif));
                $criteres['motif'] = $motif;
                if($form->has('motif')){
                    $form->get('motif')->setData($motif);
                }
            }

            $ouvrage = $request->query->get('ouvrage', null);
            if(!is_null($ouvrage)){
                $ouvrage = trim(urldecode($ouvrage));
                $criteres['ouvrage'] = $ouvrage;
                if($form->has('ouvrage')){
                    $form->get('ouvrage')->setData($ouvrage);
                }
            }

            $panne = $request->query->get('panne', null);
            if(!is_null($panne)){
                $panne = trim(urldecode($panne));
                $criteres['panne'] = $panne;
                if($form->has('panne')){
                    $form->get('panne')->setData($panne);
                }
            }

            $numeroAppel = $request->query->get('numeroAppel', null);
            if(!is_null($numeroAppel)){
                $numeroAppel = trim(urldecode($numeroAppel));
                $criteres['numeroAppel'] = $numeroAppel;
                if($form->has('numeroAppel')){
                    $form->get('numeroAppel')->setData($numeroAppel);
                }
            }

            if(!is_null($request->query->get('pdf', null))) {
                $pdfCriteres .= ($dmin != null) ? 'Date de début="'.$dmin->format('d/m/Y').'", ' : '';
                $pdfCriteres .= ($dmax != null) ? 'Date de fin="'.$dmax->format('d/m/Y').'", ' : '';
                $pdfCriteres .= ($region != null) ? 'Région="'.$region->getNom().'", ' : '';
                $pdfCriteres .= ($motif != null) ? 'Motif="'.AppelTelephonique::getStaticNormMotif($motif).'", ' : '';
                $pdfCriteres .= ($origine != null) ? 'Origine="'.$origine->getDesignation().'", ' : '';
                $pdfCriteres .= ($ouvrage != null) ? 'C. ouvrage="'.$ouvrage.'", ' : '';
                $pdfCriteres .= ($panne != null) ? 'N° panne="'.$panne.'", ' : '';
                $pdfCriteres .= ($numeroAppel != null) ? 'N° appel="'.$numeroAppel.'", ' : '';
            }
        }
        if(!is_null($request->query->get('pdf', null))) {
            return $this->pdfExport($this, $criteres, $pdfCriteres);
        }
        $appels = $em->getRepository('SofieExpBundle:AppelTelephonique')->getByCriteres($criteres, $offset, $page);
        return $this->render('SofieExpBundle:Appel:index.html.twig', array(
            'appels'=>$appels,
            "page"=>$page,
            'form' => ($form) ? $form->createView() : null
        ));
    }

    static public function pdfExport(Controller $controller, array $criteres=array(), $pdfCriteres='')
    {
        $appels = $controller->getDoctrine()->getManager()->getRepository('SofieExpBundle:AppelTelephonique')
            ->getTotalByCriteres($criteres);
        $view = $controller->renderView('SofieExpBundle:Appel:export/list.pdf.twig', array(
            'appels'=>$appels, 'criteres'=>rtrim(trim($pdfCriteres), ';,')
        ));
        return Stati::exportToPdf($controller->get('tfox.mpdfport'), $view);
    }

    protected function isCritere(Request $request)
    {
        return (
            $request->query->get('dmin', null) || $request->query->get('dmax', null) || $request->query->get('region', null)
            || $request->query->get('motif', null) || !is_null($request->query->get('ouvrage', null))
            || !is_null($request->query->get('panne', null)) || !is_null($request->query->get('numeroAppel', null))
            || !is_null($request->query->get('origine', null))
        );
    }

    protected function critereForm()
    {
        $form = $this->createFormBuilder() //array('csrf_protection' => false)
            ->add('dmin', 'date', array('required'=>false, 'trim'=>true, 'label'=>'', 'widget'=>'single_text', 'format' => 'dd/MM/yyyy'))
            ->add('dmax', 'date', array('required'=>false, 'trim'=>true, 'label'=>'', 'widget'=>'single_text', 'format' => 'dd/MM/yyyy'))
            ->add('motif', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Motif',
                'choices'=>AppelTelephonique::getMotifArrayAssoc(),
                'placeholder'=>'',
                'empty_data'=>null
            ))
            ->add('ouvrage', 'text', array('required'=>false, 'trim'=>true, 'label'=>'C. ouvrage'))
            ->add('panne', 'text', array('required'=>false, 'trim'=>true, 'label'=>'N° panne'))
            ->add('numeroAppel', 'text', array('required'=>false, 'trim'=>true, 'label'=>'N° appel'))
            ->add('origine', 'entity', array(
                'required'=>false, 'trim'=>true, 'label'=>'Origine',
                'class'=>'SofieExpBundle:Profile',
                'choice_label'=>'designation',
                'placeholder'=>'',
                'empty_data'=>null,
                'query_builder'=>function(EntityRepository $er){
                    return $er->getByMustInitializedBuilder();
                }
            ))
        ;
        if(is_null(self::$site)){
            $form->add('region', 'entity', array(
                'required'=>false, 'trim'=>true, 'label'=>'Région',
                'class'=>'SofieExpBundle:Region',
                'choice_label'=>'nom',
                'placeholder'=>'',
                'empty_data'=>null
            ));
        }

        return $form->getForm();
    }
}
