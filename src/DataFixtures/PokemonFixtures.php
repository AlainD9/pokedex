<?php

namespace App\DataFixtures;

use App\Entity\Pokemon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PokemonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $pokemon1 = new Pokemon();
        $pokemon1->setName('Bulbasaur');
        $pokemon1->setNumber('001');
        $pokemon1->setImage('https://projectpokemon.org/images/normal-sprite/bulbasaur.gif');
        $pokemon1->setType($this->getReference(TypeFixtures::TYPE_GRASSPOISON));
        $pokemon1->setRegion($this->getReference(RegionFixtures::REGION_KANTO));
        $manager->persist($pokemon1);

        $pokemon2 = new Pokemon();
        $pokemon2->setName('Ivysaur');
        $pokemon2->setNumber('002');
        $pokemon2->setImage('https://projectpokemon.org/images/normal-sprite/ivysaur.gif');
        $pokemon2->setType($this->getReference(TypeFixtures::TYPE_GRASSPOISON));
        $pokemon2->setRegion($this->getReference(RegionFixtures::REGION_KANTO));
        $manager->persist($pokemon2);

        $pokemon3 = new Pokemon();
        $pokemon3->setName('Venusaur');
        $pokemon3->setNumber('003');
        $pokemon3->setImage('https://projectpokemon.org/images/normal-sprite/venusaur.gif');
        $pokemon3->setType($this->getReference(TypeFixtures::TYPE_GRASSPOISON));
        $pokemon3->setRegion($this->getReference(RegionFixtures::REGION_KANTO));
        $manager->persist($pokemon3);
        
        $pokemon4 = new Pokemon();
        $pokemon4->setName('Charmander');
        $pokemon4->setNumber('004');
        $pokemon4->setImage('https://projectpokemon.org/images/normal-sprite/charmander.gif');
        $pokemon4->setType($this->getReference(TypeFixtures::TYPE_FIRE));
        $pokemon4->setRegion($this->getReference(RegionFixtures::REGION_KANTO));
        $manager->persist($pokemon4);

        $pokemon5 = new Pokemon();
        $pokemon5->setName('Charmeleon');
        $pokemon5->setNumber('005');
        $pokemon5->setImage('https://projectpokemon.org/images/normal-sprite/charmeleon.gif');
        $pokemon5->setType($this->getReference(TypeFixtures::TYPE_FIRE));
        $pokemon5->setRegion($this->getReference(RegionFixtures::REGION_KANTO));
        $manager->persist($pokemon5);

        $pokemon6 = new Pokemon();
        $pokemon6->setName('Charizard');
        $pokemon6->setNumber('006');
        $pokemon6->setImage('https://projectpokemon.org/images/normal-sprite/charizard.gif');
        $pokemon6->setType($this->getReference(TypeFixtures::TYPE_FIREBIRD));
        $pokemon6->setRegion($this->getReference(RegionFixtures::REGION_KANTO));
        $manager->persist($pokemon6);

        $pokemon7 = new Pokemon();
        $pokemon7->setName('Squirtle');
        $pokemon7->setNumber('007');
        $pokemon7->setImage('https://projectpokemon.org/images/normal-sprite/squirtle.gif');
        $pokemon7->setType($this->getReference(TypeFixtures::TYPE_WATER));
        $pokemon7->setRegion($this->getReference(RegionFixtures::REGION_KANTO));
        $manager->persist($pokemon7);

        $pokemon8 = new Pokemon();
        $pokemon8->setName('Wartortle');
        $pokemon8->setNumber('008');
        $pokemon8->setImage('https://projectpokemon.org/images/normal-sprite/wartortle.gif');
        $pokemon8->setType($this->getReference(TypeFixtures::TYPE_WATER));
        $pokemon8->setRegion($this->getReference(RegionFixtures::REGION_KANTO));
        $manager->persist($pokemon8);

        $pokemon9 = new Pokemon();
        $pokemon9->setName('Blastoise');
        $pokemon9->setNumber('009');
        $pokemon9->setImage('https://projectpokemon.org/images/normal-sprite/blastoise.gif');
        $pokemon9->setType($this->getReference(TypeFixtures::TYPE_WATER));
        $pokemon9->setRegion($this->getReference(RegionFixtures::REGION_KANTO));
        $manager->persist($pokemon9);
        
        $pokemon10 = new Pokemon();
        $pokemon10->setName('Caterpie');
        $pokemon10->setNumber('0010');
        $pokemon10->setImage('https://projectpokemon.org/images/normal-sprite/caterpie.gif');
        $pokemon10->setType($this->getReference(TypeFixtures::TYPE_BUG));
        $pokemon10->setRegion($this->getReference(RegionFixtures::REGION_KANTO));
        $manager->persist($pokemon10);

        $pokemon11 = new Pokemon();
        $pokemon11->setName('Metapod');
        $pokemon11->setNumber('0011');
        $pokemon11->setImage('https://projectpokemon.org/images/normal-sprite/metapod.gif');
        $pokemon11->setType($this->getReference(TypeFixtures::TYPE_BUG));
        $pokemon11->setRegion($this->getReference(RegionFixtures::REGION_KANTO));
        $manager->persist($pokemon11);

        $pokemon12 = new Pokemon();
        $pokemon12->setName('Butterfree');
        $pokemon12->setNumber('0012');
        $pokemon12->setImage('https://projectpokemon.org/images/normal-sprite/butterfree.gif');
        $pokemon12->setType($this->getReference(TypeFixtures::TYPE_BUG));
        $pokemon12->setRegion($this->getReference(RegionFixtures::REGION_KANTO));
        $manager->persist($pokemon12);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TypeFixtures::class,
            RegionFixtures::class,
        ];
    }
}
