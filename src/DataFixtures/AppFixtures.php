<?php

namespace App\DataFixtures;

use App\Factory\NewsletterFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $userNewsletter = NewsletterFactory::createMany(200);

        $manager->flush();
    }
}
