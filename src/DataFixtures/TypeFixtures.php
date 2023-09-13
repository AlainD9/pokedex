<?php

namespace App\DataFixtures;

use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeFixtures extends Fixture
{
    public const TYPE_GRASSPOISON = 'TYPE_GRASSPOISON';
    public const TYPE_FIRE = 'TYPE_FIRE';
    public const TYPE_FIREBIRD = 'TYPE_FIREBIRD';
    public const TYPE_WATER = 'TYPE_WATER';
    public const TYPE_BUG = 'TYPE_BUG';

    public function load(ObjectManager $manager): void
    {
        $type0 = new Type();
        $type0->setName('Grass Poison');
        $manager->persist($type0);
        $this->addReference(self::TYPE_GRASSPOISON, $type0);

        $type1 = new Type();
        $type1->setName('Fire');
        $manager->persist($type1);
        $this->addReference(self::TYPE_FIRE, $type1);

        $type2 = new Type();
        $type2->setName('Fire Bird');
        $manager->persist($type2);
        $this->addReference(self::TYPE_FIREBIRD, $type2);

        $type3 = new Type();
        $type3->setName('Water');
        $manager->persist($type3);
        $this->addReference(self::TYPE_WATER, $type3);

        $type4 = new Type();
        $type4->setName('Bug');
        $manager->persist($type4);
        $this->addReference(self::TYPE_BUG, $type4);

        $manager->flush();
    }
}
