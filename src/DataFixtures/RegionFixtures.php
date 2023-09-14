<?php

namespace App\DataFixtures;

use App\Entity\Region;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RegionFixtures extends Fixture
{
    public const REGION_KANTO = 'REGION_KANTO';
    public const REGION_JOTHO = 'REGION_JOTHO';
    public const REGION_SINHO = 'REGION_SINHO';

    public function load(ObjectManager $manager): void
    {
        $region = new Region();
        $region->setName('Kanto');
        $manager->persist($region);
        $this->addReference(self::REGION_KANTO, $region);
        
        $region = new Region();
        $region->setName('Jotho');
        $manager->persist($region);
        $this->addReference(self::REGION_JOTHO, $region);

        $region = new Region();
        $region->setName('Sinho');
        $manager->persist($region);
        $this->addReference(self::REGION_SINHO, $region);


        $manager->flush();
    }
}