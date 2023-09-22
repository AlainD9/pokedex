<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('JohnDoe@pokemen.com');
        $user->setPassword('$2y$13$a9FRAhcpmKv8s/ZVY7Wofej3XbRA.m.FvyiG0OFCtpKeNdOU062Ke');
        $user->setFirstname('John');
        $user->setLastname('DOE');
        $user->setBirthday('18/07/1996');
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        $manager->flush();
    }
}
