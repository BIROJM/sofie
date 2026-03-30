<?php

namespace Sofie\ExpBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sofie\AdminBundle\Model\ParameterFile;
use Sofie\ExpBundle\EnumData\EnumDataManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sofie\ExpBundle\Form\EventListener\AddLocaliteSubscriber;

class OuvrageType extends AbstractType
{
    private $edm;
    public $site;
    protected $em;

    public function __construct(EntityManager $em, EnumDataManager $edm)
    {
        $this->edm = $edm;
        $this->edm->getOuvrageEnumValues();
        $this->em = $em;
        $this->site = ParameterFile::loadSite();
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', 'text', array(
                'required'=>false, 'trim'=>true, 'read_only'=>true,
                'attr'=>array('placeholder'=>'Généré automatiquement')
            ))
            ->add('type', 'choice', array(
                'required'=>true, 'trim'=>true, 'label'=>'Type d\'ouvrage',
                'choices' => $this->edm->getTypeOuvrage(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('statutOuvrage', 'choice', array(
                'required' => true, 'trim' => true,
                'placeholder' => '',
                'choices' => $this->edm->getStatutOuvrage(),
                'empty_data'=>null
            ))
            ->add('longitude', 'text', array('required'=>true, 'trim'=>true))
            ->add('latitude', 'text', array('required'=>true, 'trim'=>true))
            ->add('altitude', 'text', array('required'=>false, 'trim'=>true))
            ->add('numIRH', 'text', array('required'=>true, 'trim'=>true, 'label'=>'N° IRH'))
            ->add('autreNumId', 'text', array(
                'required'=>false, 'trim'=>true, 'label'=>'Autre N° d\'identification'
            ))
            ->add('designation', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Désignation (lieu dit)'))
            ->add('operateurSaisie', 'text', array('required'=>true, 'trim'=>true, 'label'=>'Opérateur de saisie'))
            ->add('dateSaisie', 'date', array(
                'required'=>true, 'trim'=>true, 'label'=>'Date de saisie',
                'widget'=>'single_text', 'format'=>'dd/MM/yyyy',
                'attr'=>array('placeholder'=>'dd/mm/yyyy')
            ))
            ->add('numLocaliteProgres', 'text', array('required'=>false, 'trim'=>true, 'label'=>'N° localité PROGRES'))
            ->add('quartier', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Quartier'))
            ->add('etatInitialCaptage', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Etat initial captage',
                'choices' => $this->edm->getEtatInitialCaptageOuvrage(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('propriete', 'choice', array(
                'required'=>false, 'trim'=>true,'label'=>'Propriété',
                'choices' => $this->edm->getProprieteOuvrage(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('usage', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Usage',
                'choices' => $this->edm->getUsageOuvrage(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('nomDuProjet', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Nom du projet'))
            ->add('financement', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Financement'))
            ->add('ingenieurConseil', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Ingénieur Conseil'))
            ->add('entreprise', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Entreprise'))
            ->add('dateFinForation', 'date', array(
                'required'=>false, 'trim'=>true, 'label'=>'Date de fin de foration',
                'widget'=>'single_text', 'format'=>'dd/MM/yyyy'
            ))
            ->add('debit', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Débit'))
            ->add('profondeurTotale', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Profondeur totale'))
            ->add('profondeurEquipee', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Profondeur équipée'))
            ->add('niveauStatique', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Niveau statique'))
            ->add('dateNs', 'date', array(
                'required'=>false, 'trim'=>true, 'label'=>'Date NS',
                'widget'=>'single_text', 'format'=>'dd/MM/yyyy'
            ))
            ->add('geomorphologie', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Géomorphologie'))
            ->add('epaisseurAlteration', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Epaisseur altération'))
            ->add('nomAquifere', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Nom aquifère'))
            ->add('lithologieAquifere', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Lithologie aquifère'))
            ->add('profondeurToit', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Profondeur toît'))
            ->add('profondeurMur', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Profondeur mur'))
            ->add('datePrelevement', 'date', array(
                'required'=>false, 'trim'=>true, 'label'=>'Date de prélèvement',
                'widget'=>'single_text', 'format'=>'dd/MM/yyyy'
            ))
            ->add('temperature', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Température'))
            ->add('conductivite', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Conductivité'))
            ->add('ph', 'text', array('required'=>false, 'trim'=>true, 'label'=>'PH'))
            ->add('ferTotal', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Fer total'))
            ->add('nitrates', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Nitrates'))
            ->add('couleur', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Couleur'))
            ->add('turbidite', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Turbidité'))
            ->add('marquePompe', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Marque pompe'))
            ->add('dateInstallPompe', 'date', array(
                'required'=>false, 'trim'=>true, 'label'=>'Date d\'installation pompe',
                'widget'=>'single_text', 'format'=>'dd/MM/yyyy'
            ))
            ->add('profondeurInstallPompe', 'text', array(
                'required'=>false, 'trim'=>true, 'label'=>'Profondeur d\'installation pompe'
            ))
            ->add('prefecture', 'text', array(
                'required'=>false, 'trim'=>true, 'label'=>'Préfecture'
            ))
            ->add('canton', 'text', array(
                'required'=>false, 'trim'=>true, 'label'=>'Canton'
            ))
            ->add('coupeGeologiques', 'collection', array(
                'type'=>'sofie_expbundle_coupegeologique',
                'allow_add'=>true,
                'allow_delete'=>true,
                'by_reference'=>false,
                'options'=>array(
                    'required'=>false,
                )
            ))
            ->add('equipementForages', 'collection', array(
                'type'=>'sofie_expbundle_equipementforage',
                'allow_add'=>true,
                'allow_delete'=>true,
                'by_reference'=>false,
                'options'=>array(
                    'required'=>false,
                )
            ))
            ->add('essaisPompages', 'collection', array(
                'type'=>'sofie_expbundle_essaispompage',
                'allow_add'=>true,
                'allow_delete'=>true,
                'by_reference'=>false,
                'options'=>array(
                    'required'=>false,
                )
            ))
            ->add('suiviPhysicoChimiques', 'collection', array(
                'type'=>'sofie_expbundle_suiviphysicochimique',
                'allow_add'=>true,
                'allow_delete'=>true,
                'by_reference'=>false,
                'options'=>array(
                    'required'=>false,
                )
            ))
            ->add('venuEauPrincipales', 'collection', array(
                'type'=>'sofie_expbundle_venueauprincipale',
                'allow_add'=>true,
                'allow_delete'=>true,
                'by_reference'=>false,
                'options'=>array(
                    'required'=>false,
                )
            ))
        ;

        if(is_null($this->site)){
            $builder->add('region', 'entity', array(
                'required'=>true, 'trim'=>true, 'label'=>'Région',
                'class'=>'SofieExpBundle:Region',
                'choice_label' => 'nom',
                'placeholder' => '',
                'empty_data' => null,
                'query_builder' => function(EntityRepository $er){
                    return $er->getByUserRegionBuilder();
                },
                'mapped'=>false
            ));
        }

        $builder->addEventSubscriber(new AddLocaliteSubscriber($builder, $this->em, $this->site));

    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sofie\ExpBundle\Entity\Ouvrage',
            'intention' => 'sofie_expbundle_ouvrage_type'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sofie_expbundle_ouvrage';
    }
}
