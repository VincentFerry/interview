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
        $recipe1->setContent(
            "Ingredients:\n- 2 cups all-purpose flour\n- 2 cups sugar\n- 3/4 cup unsweetened cocoa powder\n- 2 teaspoons baking soda\n- 1 teaspoon baking powder\n- 1 teaspoon salt\n- 2 eggs\n- 1 cup milk\n- 1/2 cup vegetable oil\n- 2 teaspoons vanilla extract\n- 1 cup boiling water\n\nInstructions:\n1. Preheat oven to 350°F (175°C). Grease and flour two 9-inch round cake pans.\n2. In a large bowl, combine flour, sugar, cocoa, baking soda, baking powder and salt.\n3. Add eggs, milk, oil and vanilla. Beat on medium speed for 2 minutes.\n4. Stir in boiling water. The batter will be thin.\n5. Pour into prepared pans.\n6. Bake for 30-35 minutes until a toothpick inserted comes out clean.\n7. Cool in pans for 10 minutes, then remove and cool completely on wire racks.",
        );
        $recipe1->addTopic($this->getReference(TopicFixtures::TOPIC_DESSERTS, Topic::class));
        $recipe1->addTopic($this->getReference(TopicFixtures::TOPIC_BAKING, Topic::class));
        $manager->persist($recipe1);

        $recipe2 = new Recipe();
        $recipe2->setName('Caesar Salad');
        $recipe2->setContent(
            "Ingredients:\n- 1 large head romaine lettuce, chopped\n- 1/2 cup Caesar dressing\n- 1/2 cup croutons\n- 1/4 cup grated Parmesan cheese\n- Black pepper to taste\n\nFor the dressing:\n- 2 cloves garlic, minced\n- 1 teaspoon anchovy paste\n- 2 tablespoons lemon juice\n- 1 teaspoon Dijon mustard\n- 1 teaspoon Worcestershire sauce\n- 1/2 cup mayonnaise\n- 1/4 cup grated Parmesan cheese\n- Salt and pepper to taste\n\nInstructions:\n1. Make the dressing: Whisk together all dressing ingredients until well combined.\n2. In a large bowl, toss chopped lettuce with desired amount of dressing.\n3. Top with croutons, additional Parmesan cheese, and fresh cracked pepper.\n4. Serve immediately.",
        );
        $recipe2->addTopic($this->getReference(TopicFixtures::TOPIC_SALADS, Topic::class));
        $recipe2->addTopic($this->getReference(TopicFixtures::TOPIC_HEALTHY, Topic::class));
        $manager->persist($recipe2);

        $recipe3 = new Recipe();
        $recipe3->setName('Tomato Soup');
        $recipe3->setContent(
            "Ingredients:\n- 4 tablespoons butter\n- 1 onion, diced\n- 4 cloves garlic, minced\n- 2 (28 oz) cans whole peeled tomatoes\n- 2 cups chicken broth\n- 1/2 cup heavy cream\n- 1 tablespoon sugar\n- Salt and pepper to taste\n- Fresh basil for garnish\n\nInstructions:\n1. Melt butter in a large pot over medium heat. Add onion and cook until soft (5-7 minutes).\n2. Add garlic and cook for 1 minute more.\n3. Add tomatoes, chicken broth, and sugar. Bring to a simmer.\n4. Simmer for 30 minutes, stirring occasionally.\n5. Use an immersion blender to puree until smooth.\n6. Stir in heavy cream.\n7. Season with salt and pepper to taste.\n8. Serve hot, garnished with fresh basil.",
        );
        $recipe3->addTopic($this->getReference(TopicFixtures::TOPIC_SOUPS, Topic::class));
        $recipe3->addTopic($this->getReference(TopicFixtures::TOPIC_HEALTHY, Topic::class));
        $manager->persist($recipe3);

        $recipe4 = new Recipe();
        $recipe4->setName('Grilled Chicken');
        $recipe4->setContent(
            "Ingredients:\n- 4 chicken breasts\n- 1/4 cup olive oil\n- 3 cloves garlic, minced\n- 1 teaspoon dried oregano\n- 1 teaspoon dried basil\n- 1/2 teaspoon paprika\n- 1 lemon, juiced\n- Salt and pepper to taste\n\nInstructions:\n1. In a bowl, combine olive oil, garlic, oregano, basil, paprika, lemon juice, salt and pepper.\n2. Place chicken in a large zip-top bag and pour in marinade.\n3. Marinate for at least 2 hours or overnight in refrigerator.\n4. Preheat grill to medium-high heat.\n5. Grill chicken for 6-8 minutes per side, or until internal temperature reaches 165°F.\n6. Let rest for 5 minutes before serving.\n7. Serve with your favorite sides.",
        );
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
