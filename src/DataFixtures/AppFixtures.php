<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {

    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $user1 = new User();
        $user1->setEmail("test@test.com");
        $user1->setPassword($this->passwordHasher->hashPassword($user1, "12345678"));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail("john@test.com");
        $user2->setPassword($this->passwordHasher->hashPassword($user2, "12345678"));
        $manager->persist($user2);

        $microPost = new MicroPost();
        $microPost->setTitle("Welcome to socialzila");
        $microPost->setText("Welcome to socialzila text");
        $microPost->setCreated(new DateTime());
        $manager->persist($microPost);

        $microPost1 = new MicroPost();
        $microPost1->setTitle("Welcome to socialzila again!");
        $microPost1->setText("Welcome to socialzila text again!");
        $microPost1->setCreated(new DateTime());
        $manager->persist($microPost1);

        $microPost2 = new MicroPost();
        $microPost2->setTitle("Welcome to socialzila again again!");
        $microPost2->setText("Welcome to socialzila text again again!");
        $microPost2->setCreated(new DateTime());
        $manager->persist($microPost2);

        $manager->flush();
    }
}
