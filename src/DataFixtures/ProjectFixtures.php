<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $project1 = new Project();
        $project1->setTitle('Identity project');
        $project1->setCategory($this->getReference('category_identity'));
        $project1->setContent('Description');
        $project1->setPicture($this->getReference('media_1'));
        $manager->persist($project1);

        $project2 = new Project();
        $project2->setTitle('Second identity project');
        $project2->setCategory($this->getReference('category_identity'));
        $project2->setContent('Description');
        $project2->setPicture($this->getReference('media_2'));
        $manager->persist($project2);

        $project3 = new Project();
        $project3->setTitle('Digital project');
        $project3->setCategory($this->getReference('category_digital'));
        $project3->setContent('Description');
        $project3->setPicture($this->getReference('media_3'));
        $manager->persist($project3);


        $project4 = new Project();
        $project4->setTitle('Industrial project');
        $project4->setCategory($this->getReference('category_industrial'));
        $project4->setContent('Description');
        $project4->setPicture($this->getReference('media_4'));
        $manager->persist($project4);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            SonataMediaMediaFixtures::class,
        ];
    }
}
