<?php

namespace App\DataFixtures;

use App\Entity\Topic;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TopicFixtures extends Fixture
{
    public const TOPIC_DESSERTS = 'topic-desserts';
    public const TOPIC_MAIN_DISHES = 'topic-main-dishes';
    public const TOPIC_APPETIZERS = 'topic-appetizers';
    public const TOPIC_SOUPS = 'topic-soups';
    public const TOPIC_SALADS = 'topic-salads';
    public const TOPIC_BAKING = 'topic-baking';
    public const TOPIC_BEVERAGES = 'topic-beverages';
    public const TOPIC_HEALTHY = 'topic-healthy';

    public function load(ObjectManager $manager): void
    {

        $topicDesserts = new Topic();
        $topicDesserts->setName('Desserts');
        $manager->persist($topicDesserts);
        $this->addReference(self::TOPIC_DESSERTS, $topicDesserts);

        $topicMainDishes = new Topic();
        $topicMainDishes->setName('Main Dishes');
        $manager->persist($topicMainDishes);
        $this->addReference(self::TOPIC_MAIN_DISHES, $topicMainDishes);

        $topicAppetizers = new Topic();
        $topicAppetizers->setName('Appetizers');
        $manager->persist($topicAppetizers);
        $this->addReference(self::TOPIC_APPETIZERS, $topicAppetizers);

        $topicSoups = new Topic();
        $topicSoups->setName('Soups');
        $manager->persist($topicSoups);
        $this->addReference(self::TOPIC_SOUPS, $topicSoups);

        $topicSalads = new Topic();
        $topicSalads->setName('Salads');
        $manager->persist($topicSalads);
        $this->addReference(self::TOPIC_SALADS, $topicSalads);

        $topicBaking = new Topic();
        $topicBaking->setName('Baking');
        $manager->persist($topicBaking);
        $this->addReference(self::TOPIC_BAKING, $topicBaking);

        $topicBeverages = new Topic();
        $topicBeverages->setName('Beverages');
        $manager->persist($topicBeverages);
        $this->addReference(self::TOPIC_BEVERAGES, $topicBeverages);

        $topicHealthy = new Topic();
        $topicHealthy->setName('Healthy');
        $manager->persist($topicHealthy);
        $this->addReference(self::TOPIC_HEALTHY, $topicHealthy);

        $manager->flush();
    }
}
