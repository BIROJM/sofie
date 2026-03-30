<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 27/09/2015
 * Time: 22:47
 */

namespace Sofie\AdminBundle\DataFixtures\ORM;



use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sofie\ExpBundle\Entity\Reparateur;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadReparateurData implements FixtureInterface, ContainerAwareInterface
{
    const FIXTURE_PREFIXE = 'Fixture';
    const NB_FIXTURE = 120;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->myAdd($manager);
//        $this->myDelelete($manager);
    }

    private function myAdd(ObjectManager $manager)
    {
        $words = 'AZERTYUIOPQSDFGHJKLMWXCVBN';
        $words .= 'azertyuiopqsdfghjklmwxcvbn';
        $words = str_shuffle($words);
        $prenoms=''; $a=''; $b=''; $min=''; $max='';
        for($i=1; $i <= self::NB_FIXTURE; $i++){
            $a = mt_rand(0,51)%51;
            $b = mt_rand(0,51)%51;
            $min = min($a, $b);
            $max = max($a, $b);
            $prenoms = str_shuffle(substr($words, $min, ($max-$min+1)%8));
            $reparateur = new Reparateur();
            $reparateur
                ->setNom(self::FIXTURE_PREFIXE)
                ->setPrenoms($prenoms)
            ;
            $manager->persist($reparateur);
            $manager->flush();
        }
    }

    private function myDelelete(ObjectManager $manager)
    {
        $this->container->get('doctrine.orm.entity_manager')->getConnection()
            ->exec("DELETE FROM t_reparateur WHERE NomRep = '".self::FIXTURE_PREFIXE."'");
        ;
    }
}