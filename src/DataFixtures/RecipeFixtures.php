<?php

namespace App\DataFixtures;

use App\Entity\Recipe;
use App\Entity\Topic;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RecipeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $recipe1 = new Recipe();
        $recipe1->setName('Chocolate Cake');
        $recipe1->setContent('A rich chocolate cake recipe...');
        $recipe1->addTopic($this->getReference(TopicFixtures::TOPIC_DESSERTS, Topic::class));
        $recipe1->addTopic($this->getReference(TopicFixtures::TOPIC_BAKING, Topic::class));
        $manager->persist($recipe1);

        $recipe2 = new Recipe();
        $recipe2->setName('Caesar Salad');
        $recipe2->setContent('Classic caesar salad with homemade dressing...');
        $recipe2->addTopic($this->getReference(TopicFixtures::TOPIC_SALADS, Topic::class));
        $recipe2->addTopic($this->getReference(TopicFixtures::TOPIC_HEALTHY, Topic::class));
        $manager->persist($recipe2);

        $recipe3 = new Recipe();
        $recipe3->setName('Tomato Soup');
        $recipe3->setContent('Creamy tomato soup recipe...');
        $recipe3->addTopic($this->getReference(TopicFixtures::TOPIC_SOUPS, Topic::class));
        $recipe3->addTopic($this->getReference(TopicFixtures::TOPIC_HEALTHY, Topic::class));
        $manager->persist($recipe3);

        $recipe4 = new Recipe();
        $recipe4->setName('Grilled Chicken');
        $recipe4->setContent('Juicy grilled chicken recipe...');
        $recipe4->addTopic($this->getReference(TopicFixtures::TOPIC_MAIN_DISHES, Topic::class));
        $recipe4->addTopic($this->getReference(TopicFixtures::TOPIC_HEALTHY, Topic::class));
        $manager->persist($recipe4);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TopicFixtures::class,
        ];
    }
}
