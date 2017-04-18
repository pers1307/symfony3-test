<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.04.2017
 * Time: 19:52
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Genus;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $genus = new Genus();

        $genus->setName('Oct');
        $genus->setSubFamily('Octopodinae');
        $genus->setSpeciesCount(56);

        $manager->persist($genus);
        $manager->flush();
    }
}