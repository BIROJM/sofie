<?php

namespace Sofie\ExpBundle\Form\Type;

use Sofie\ExpBundle\Entity\Collecte;
use Sofie\ExpBundle\EnumData\EnumDataManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollecteType extends AbstractType
{
    protected $edm;

    public function __construct(EnumDataManager $edm)
    {
        $this->edm = $edm;
        $this->edm->getCollecteEnumValues();
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateSaisie', 'date', array(
                'required'=>true, 'trim'=>true, 'label'=>'Date de saisie',
                'widget'=>'single_text', 'format'=>'dd/MM/yyyy'
            ))
            ->add('dateRemplissage', 'date', array(
                'required'=>true, 'trim'=>true, 'label'=>'Date de remplissage de la fiche',
                'widget'=>'single_text', 'format'=>'dd/MM/yyyy'
            ))
            ->add('nomOperateur', 'text', array('required'=>true, 'trim'=>true, 'label'=>'Nom opérateur'))
            ->add('nomAgentSaisie', 'text', array(
                'required'=>true, 'trim'=>true, 'label'=>'Nom de l\'agent ayant rempli la fiche'
            ))
            ->add('service', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Service'))
            ->add('etatOuvrage', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Etat de l\'ouvrage',
                'choices' => $this->edm->getEtatOuvrageCollecte(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('codeCauseSiAbandon', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Si abandon, cause principale',
                'choices' => $this->edm->getCodeCauseSiAbandonCollecte(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('causeDestruction', 'text', array(
                'required'=>false, 'trim'=>true, 'label'=>'Si destruction, cause'
            ))
            ->add('pereniteAnneeNbMois', 'text', array(
                'required'=>false, 'trim'=>true, 'label'=>'Nombre de mois'
            ))
            ->add('pereniteAnneeDsJournee', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Pérennité dans la journée',
                'choices' => $this->edm->getPereniteAnneeDsJourneeCollecte(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('superStructureRehabilite', 'checkbox', array(
                'required'=>false, 'trim'=>true, 'label'=>'Super structure réhabilité'
            ))
            ->add('etatMargelle', 'checkbox', array('required'=>false, 'trim'=>true, 'label'=>'Etat margelle'))
            ->add('etancheiteforage', 'checkbox', array('required'=>false, 'trim'=>true, 'label'=>'Etanchéité forage'))
            ->add('etatFixationPompe', 'checkbox', array('required'=>false, 'trim'=>true, 'label'=>'Etat fixation pompe'))
            ->add('etatAntiBourbier', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Etat anti bourbier',
                'choices' => $this->edm->getEtatAntiBourbierCollecte(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('etatCloture', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Etat clôture',
                'choices' => $this->edm->getEtatClotureCollecte(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('etatRigoleEvacuation', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Etat rigole évacuation',
                'choices' => $this->edm->getEtatRigoleEvacuationCollecte(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('etatPuitPerdu', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Etat puit perdu',
                'choices' => $this->edm->getEtatPuitPerduCollecte(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('propreteInterieurCloture', 'checkbox', array(
                'required'=>false, 'trim'=>true, 'label'=>'Propreté intérieur clôture'
            ))
            ->add('propreteExterieurCloture', 'checkbox', array(
                'required'=>false, 'trim'=>true, 'label'=>'Propreté extérieur clôture'
            ))
            ->add('sourcePollution1', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Source de pollution 1',
                'choices' => $this->edm->getSourcePollution1Collecte(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('sourcePollution2', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Source de pollution 2',
                'choices' => $this->edm->getSourcePollution2Collecte(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('presenceDeferiseur', 'checkbox', array(
                'required'=>false, 'trim'=>true, 'label'=>'Présence défériseur'
            ))
            ->add('deferiseurUtilise', 'checkbox', array('required'=>false, 'trim'=>true, 'label'=>'Défériseur utilisé'))
            ->add('etatDeferiseur', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Etat défériseur',
                'choices' => $this->edm->getEtatDeferiseurCollecte(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('nbPompe', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Nombre de pompe'))
            ->add('marquePompe', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Marque de la pompe'))
            ->add('pompeRemplace', 'checkbox', array('required'=>false, 'trim'=>true, 'label'=>'Pompe remplacée'))
            ->add('anneePosePompe', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Année de pose de la pompe'))
            ->add('financementRemplacement', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Financement du remplacement',
                'choices' => $this->edm->getFinancementRemplacementCollecte(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('nomProjet', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Nom du projet'))
            ->add('etatPompe', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Etat de la pompe',
                'choices' => $this->edm->getEtatPompeCollecte(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('dureePanne', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Durée de la panne',
                'choices' => $this->edm->getDureePanneCollecte(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('causeNonReparation', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Cause de non réparation',
                'choices' => $this->edm->getCauseNonReparationCollecte(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('turbidite', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Turbidité',
                'choices' => $this->edm->getTurbiditeCollecte(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('odeurEau', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Odeur de l\'eau',
                'choices' => $this->edm->getOdeurEauCollecte(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('goutEau', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Goût de l\'eau',
                'choices' => $this->edm->getGoutEauCollecte(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('pelliculeEnSurface', 'checkbox', array(
                'required'=>false, 'trim'=>true, 'label'=>'Pellicule en surface'
            ))
            ->add('presenceVers', 'checkbox', array(
                'required'=>false, 'trim'=>true, 'label'=>'Présence de vers'
            ))
            ->add('conductivite', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Conductivité'))
            ->add('ph', 'text', array('required'=>false, 'trim'=>true, 'label'=>'PH'))
            ->add('nitratesNo3', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Nitrates NO3'))
            ->add('nitritesNo2', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Nitrites NO2'))
            ->add('ferTotal', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Fer total'))
            ->add('modeGestionOuvrage', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Mode gestion de l\'ouvrage',
                'choices' => $this->edm->getModeGestionOuvrageCollecte(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('presenceUniteGestion', 'checkbox', array(
                'required'=>false, 'trim'=>true, 'label'=>'Présence d\'une unité de gestion',
            ))
            ->add('villageUe', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Village UE'))
            ->add('numVillageUe', 'text', array('required'=>false, 'trim'=>true, 'label'=>'N° UE dans le village'))
            ->add('assistanceBienfaiteur', 'checkbox', array(
                'required'=>false, 'trim'=>true, 'label'=>'Assistance bienfaiteur'
            ))
            ->add('modePaiementEau', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Mode de paiement de l\'eau',
                'choices' =>$this->edm->getModePaiementEauCollecte(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('prixSeau20Litres', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Seau 20 litres'))
            ->add('prixBassine35Litres', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Bassine 35 litres'))
            ->add('nomArtisanReparateur', 'text', array(
                'required'=>false, 'trim'=>true, 'label'=>'Nom de l\'artisan réparateur'
            ))
            ->add('villageResidenceReparateur', 'text', array(
                'required'=>false, 'trim'=>true, 'label'=>'Village de résidence de l\'artisan'
            ))
            ->add('cahierEntretientPompe', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Cahier d\'entretien de la pompe',
                'choices' => $this->edm->getCahierEntretientPompeCollecte(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('contratEntretienArtisan', 'checkbox', array(
                'required'=>false, 'trim'=>true,
                'label'=>"Existence d'un contrat d'entretien avec l'artisan en plus des interventionsen cas de panne"
            ))
            ->add('typeContrat', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Si oui, type de contrat',
                'choices' => $this->edm->getTypeContratCollecte(), 'placeholder'=>'', 'empty_data'=>null
            ))
            ->add('commentaires', 'textarea', array('required'=>false, 'trim'=>true, 'label'=>'Commentaires'))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sofie\ExpBundle\Entity\Collecte',
            'intention' => 'sofie_expbundle_collecte_type'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sofie_expbundle_collecte';
    }
}
