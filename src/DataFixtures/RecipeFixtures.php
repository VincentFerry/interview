<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\Topic;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RecipeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $recipesData = $this->getRecipesData();

        foreach ($recipesData as $recipeData) {
            $this->createRecipe($manager, $recipeData);
        }

        $manager->flush();
    }

    private function getRecipesData(): array
    {
        return [
            [
                'name' => 'Chocolate Cake',
                'content' => "Instructions:\n1. Preheat oven to 350°F (175°C). Grease and flour two 9-inch round cake pans.\n2. In a large bowl, combine flour, sugar, cocoa, baking soda, baking powder and salt.\n3. Add eggs, milk, oil and vanilla. Beat on medium speed for 2 minutes.\n4. Stir in boiling water. The batter will be thin.\n5. Pour into prepared pans.\n6. Bake for 30-35 minutes until a toothpick inserted comes out clean.\n7. Cool in pans for 10 minutes, then remove and cool completely on wire racks.",
                'topics' => [TopicFixtures::TOPIC_DESSERTS, TopicFixtures::TOPIC_BAKING],
                'ingredients' => [
                    ['name' => 'All-purpose flour', 'quantity' => '2 cups'],
                    ['name' => 'Sugar', 'quantity' => '2 cups'],
                    ['name' => 'Unsweetened cocoa powder', 'quantity' => '3/4 cup'],
                    ['name' => 'Baking soda', 'quantity' => '2 teaspoons'],
                    ['name' => 'Baking powder', 'quantity' => '1 teaspoon'],
                    ['name' => 'Salt', 'quantity' => '1 teaspoon'],
                    ['name' => 'Eggs', 'quantity' => '2'],
                    ['name' => 'Milk', 'quantity' => '1 cup'],
                    ['name' => 'Vegetable oil', 'quantity' => '1/2 cup'],
                    ['name' => 'Vanilla extract', 'quantity' => '2 teaspoons'],
                    ['name' => 'Boiling water', 'quantity' => '1 cup'],
                ],
            ],
            [
                'name' => 'Caesar Salad',
                'content' => "Instructions:\n1. Make the dressing: Whisk together garlic, anchovy paste, lemon juice, Dijon mustard, Worcestershire sauce, mayonnaise, and Parmesan cheese until well combined.\n2. In a large bowl, toss chopped lettuce with desired amount of dressing.\n3. Top with croutons, additional Parmesan cheese, and fresh cracked pepper.\n4. Serve immediately.",
                'topics' => [TopicFixtures::TOPIC_SALADS, TopicFixtures::TOPIC_HEALTHY],
                'ingredients' => [
                    ['name' => 'Romaine lettuce', 'quantity' => '1 large head, chopped'],
                    ['name' => 'Caesar dressing', 'quantity' => '1/2 cup'],
                    ['name' => 'Croutons', 'quantity' => '1/2 cup'],
                    ['name' => 'Parmesan cheese', 'quantity' => '1/4 cup grated'],
                    ['name' => 'Black pepper', 'quantity' => 'to taste'],
                    ['name' => 'Garlic', 'quantity' => '2 cloves, minced'],
                    ['name' => 'Anchovy paste', 'quantity' => '1 teaspoon'],
                    ['name' => 'Lemon juice', 'quantity' => '2 tablespoons'],
                    ['name' => 'Dijon mustard', 'quantity' => '1 teaspoon'],
                    ['name' => 'Worcestershire sauce', 'quantity' => '1 teaspoon'],
                    ['name' => 'Mayonnaise', 'quantity' => '1/2 cup'],
                    ['name' => 'Salt', 'quantity' => 'to taste'],
                ],
            ],
            [
                'name' => 'Tomato Soup',
                'content' => "Instructions:\n1. Melt butter in a large pot over medium heat. Add onion and cook until soft (5-7 minutes).\n2. Add garlic and cook for 1 minute more.\n3. Add tomatoes, chicken broth, and sugar. Bring to a simmer.\n4. Simmer for 30 minutes, stirring occasionally.\n5. Use an immersion blender to puree until smooth.\n6. Stir in heavy cream.\n7. Season with salt and pepper to taste.\n8. Serve hot, garnished with fresh basil.",
                'topics' => [TopicFixtures::TOPIC_SOUPS, TopicFixtures::TOPIC_HEALTHY],
                'ingredients' => [
                    ['name' => 'Butter', 'quantity' => '4 tablespoons'],
                    ['name' => 'Onion', 'quantity' => '1, diced'],
                    ['name' => 'Garlic', 'quantity' => '4 cloves, minced'],
                    ['name' => 'Whole peeled tomatoes', 'quantity' => '2 (28 oz) cans'],
                    ['name' => 'Chicken broth', 'quantity' => '2 cups'],
                    ['name' => 'Heavy cream', 'quantity' => '1/2 cup'],
                    ['name' => 'Sugar', 'quantity' => '1 tablespoon'],
                    ['name' => 'Salt', 'quantity' => 'to taste'],
                    ['name' => 'Black pepper', 'quantity' => 'to taste'],
                    ['name' => 'Fresh basil', 'quantity' => 'for garnish'],
                ],
            ],
            [
                'name' => 'Grilled Chicken',
                'content' => "Instructions:\n1. In a bowl, combine olive oil, garlic, oregano, basil, paprika, lemon juice, salt and pepper.\n2. Place chicken in a large zip-top bag and pour in marinade.\n3. Marinate for at least 2 hours or overnight in refrigerator.\n4. Preheat grill to medium-high heat.\n5. Grill chicken for 6-8 minutes per side, or until internal temperature reaches 165°F.\n6. Let rest for 5 minutes before serving.\n7. Serve with your favorite sides.",
                'topics' => [TopicFixtures::TOPIC_MAIN_DISHES, TopicFixtures::TOPIC_HEALTHY],
                'ingredients' => [
                    ['name' => 'Chicken breasts', 'quantity' => '4'],
                    ['name' => 'Olive oil', 'quantity' => '1/4 cup'],
                    ['name' => 'Garlic', 'quantity' => '3 cloves, minced'],
                    ['name' => 'Dried oregano', 'quantity' => '1 teaspoon'],
                    ['name' => 'Dried basil', 'quantity' => '1 teaspoon'],
                    ['name' => 'Paprika', 'quantity' => '1/2 teaspoon'],
                    ['name' => 'Lemon', 'quantity' => '1, juiced'],
                    ['name' => 'Salt', 'quantity' => 'to taste'],
                    ['name' => 'Black pepper', 'quantity' => 'to taste'],
                ],
            ],
            [
                'name' => 'Spaghetti Carbonara',
                'content' => "Instructions:\n1. Cook spaghetti according to package directions until al dente. Reserve 1 cup pasta water before draining.\n2. While pasta cooks, fry pancetta in a large skillet until crispy.\n3. In a bowl, whisk together eggs, Parmesan cheese, and black pepper.\n4. Add hot drained pasta to the skillet with pancetta.\n5. Remove from heat and quickly stir in egg mixture, adding pasta water as needed to create a creamy sauce.\n6. Serve immediately with additional Parmesan and black pepper.",
                'topics' => [TopicFixtures::TOPIC_MAIN_DISHES],
                'ingredients' => [
                    ['name' => 'Spaghetti', 'quantity' => '1 lb'],
                    ['name' => 'Pancetta', 'quantity' => '6 oz, diced'],
                    ['name' => 'Large eggs', 'quantity' => '4'],
                    ['name' => 'Parmesan cheese', 'quantity' => '1 cup grated'],
                    ['name' => 'Black pepper', 'quantity' => '1 teaspoon freshly ground'],
                    ['name' => 'Salt', 'quantity' => 'to taste'],
                ],
            ],
            [
                'name' => 'Beef Tacos',
                'content' => "Instructions:\n1. Heat oil in a large skillet over medium-high heat.\n2. Add ground beef and cook, breaking it up with a spoon, until browned (about 6-8 minutes).\n3. Add onion and garlic, cook for 2-3 minutes until softened.\n4. Stir in chili powder, cumin, paprika, salt, and pepper. Cook for 1 minute.\n5. Add tomato sauce and simmer for 5 minutes until thickened.\n6. Warm tortillas and fill with beef mixture.\n7. Top with lettuce, tomatoes, cheese, and sour cream.",
                'topics' => [TopicFixtures::TOPIC_MAIN_DISHES],
                'ingredients' => [
                    ['name' => 'Ground beef', 'quantity' => '1 lb'],
                    ['name' => 'Vegetable oil', 'quantity' => '1 tablespoon'],
                    ['name' => 'Onion', 'quantity' => '1 small, diced'],
                    ['name' => 'Garlic', 'quantity' => '2 cloves, minced'],
                    ['name' => 'Chili powder', 'quantity' => '1 tablespoon'],
                    ['name' => 'Ground cumin', 'quantity' => '1 teaspoon'],
                    ['name' => 'Paprika', 'quantity' => '1/2 teaspoon'],
                    ['name' => 'Tomato sauce', 'quantity' => '1/2 cup'],
                    ['name' => 'Corn tortillas', 'quantity' => '8'],
                    ['name' => 'Lettuce', 'quantity' => '2 cups shredded'],
                    ['name' => 'Tomatoes', 'quantity' => '2, diced'],
                    ['name' => 'Cheddar cheese', 'quantity' => '1 cup shredded'],
                    ['name' => 'Sour cream', 'quantity' => '1/2 cup'],
                    ['name' => 'Salt', 'quantity' => 'to taste'],
                    ['name' => 'Black pepper', 'quantity' => 'to taste'],
                ],
            ],
            [
                'name' => 'Apple Pie',
                'content' => "Instructions:\n1. Preheat oven to 425°F (220°C).\n2. Roll out bottom pie crust and place in 9-inch pie pan.\n3. In a large bowl, combine sliced apples, sugar, flour, cinnamon, and nutmeg.\n4. Fill pie crust with apple mixture and dot with butter pieces.\n5. Cover with top crust, seal edges, and cut vents.\n6. Brush with beaten egg and sprinkle with sugar.\n7. Bake for 45-50 minutes until crust is golden and filling is bubbly.\n8. Cool on wire rack before serving.",
                'topics' => [TopicFixtures::TOPIC_DESSERTS, TopicFixtures::TOPIC_BAKING],
                'ingredients' => [
                    ['name' => 'Pie crusts', 'quantity' => '2 (9-inch)'],
                    ['name' => 'Granny Smith apples', 'quantity' => '6-8, peeled and sliced'],
                    ['name' => 'Sugar', 'quantity' => '3/4 cup'],
                    ['name' => 'All-purpose flour', 'quantity' => '2 tablespoons'],
                    ['name' => 'Ground cinnamon', 'quantity' => '1 teaspoon'],
                    ['name' => 'Ground nutmeg', 'quantity' => '1/4 teaspoon'],
                    ['name' => 'Butter', 'quantity' => '2 tablespoons, cut into pieces'],
                    ['name' => 'Egg', 'quantity' => '1, beaten'],
                    ['name' => 'Sugar for sprinkling', 'quantity' => '1 tablespoon'],
                ],
            ],
            [
                'name' => 'Greek Salad',
                'content' => "Instructions:\n1. Cut tomatoes into wedges and cucumber into thick slices.\n2. Slice red onion thinly.\n3. In a large bowl, combine tomatoes, cucumber, onion, and olives.\n4. Add feta cheese chunks.\n5. In a small bowl, whisk together olive oil, lemon juice, oregano, salt, and pepper.\n6. Pour dressing over salad and toss gently.\n7. Let stand for 10 minutes before serving to allow flavors to meld.",
                'topics' => [TopicFixtures::TOPIC_SALADS, TopicFixtures::TOPIC_HEALTHY],
                'ingredients' => [
                    ['name' => 'Tomatoes', 'quantity' => '4 large, cut into wedges'],
                    ['name' => 'Cucumber', 'quantity' => '1 large, sliced'],
                    ['name' => 'Red onion', 'quantity' => '1/2 medium, thinly sliced'],
                    ['name' => 'Kalamata olives', 'quantity' => '1/2 cup'],
                    ['name' => 'Feta cheese', 'quantity' => '4 oz, cut into chunks'],
                    ['name' => 'Extra virgin olive oil', 'quantity' => '1/4 cup'],
                    ['name' => 'Lemon juice', 'quantity' => '2 tablespoons'],
                    ['name' => 'Dried oregano', 'quantity' => '1 teaspoon'],
                    ['name' => 'Salt', 'quantity' => 'to taste'],
                    ['name' => 'Black pepper', 'quantity' => 'to taste'],
                ],
            ],
        ];
    }

    private function createRecipe(ObjectManager $manager, array $recipeData): void
    {
        $recipe = new Recipe();
        $recipe->setName($recipeData['name']);
        $recipe->setContent($recipeData['content']);

        // Add topics
        foreach ($recipeData['topics'] as $topicReference) {
            $topic = $this->getReference($topicReference, Topic::class);
            $recipe->addTopic($topic);
        }

        // Add ingredients
        foreach ($recipeData['ingredients'] as $ingredientData) {
            $ingredient = new Ingredient();
            $ingredient->setName($ingredientData['name']);
            $ingredient->setQuantity($ingredientData['quantity']);
            $recipe->addIngredient($ingredient);
            $manager->persist($ingredient);
        }

        $manager->persist($recipe);
    }

    public function getDependencies(): array
    {
        return [
            TopicFixtures::class,
        ];
    }
}
