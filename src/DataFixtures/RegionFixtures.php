<?php

namespace App\DataFixtures;

use App\Entity\Region;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RegionFixtures extends Fixture
{
    public const REGION_KANTO = 'REGION_KANTO';

    public function load(ObjectManager $manager): void
    {
        $region = new Region();
        $region->setName('Kanto');
        $manager->persist($region);
        $this->addReference(self::REGION_KANTO, $region);

        $manager->flush();
    }
}
