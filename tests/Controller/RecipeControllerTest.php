<?php

namespace App\Tests\Controller;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\Topic;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 *
 * @coversNothing
 */
class RecipeControllerTest extends WebTestCase
{
    private $client;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();

        // Clean up database before each test
        $this->cleanDatabase();

        // Create test data
        $this->createTestData();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }

    private function cleanDatabase(): void
    {
        // Remove all recipes, topics, and ingredients
        $this->entityManager->createQuery('DELETE FROM App\Entity\Recipe')->execute();
        $this->entityManager->createQuery('DELETE FROM App\Entity\Topic')->execute();
        $this->entityManager->createQuery('DELETE FROM App\Entity\Ingredient')->execute();
    }

    private function createTestData(): void
    {
        // Create test topics
        $dessertTopic = new Topic();
        $dessertTopic->setName('Desserts');
        $this->entityManager->persist($dessertTopic);

        $mainDishTopic = new Topic();
        $mainDishTopic->setName('Main Dishes');
        $this->entityManager->persist($mainDishTopic);

        // Create test recipe
        $recipe = new Recipe();
        $recipe->setName('Test Chocolate Cake');
        $recipe->setContent('Test instructions for chocolate cake');
        $recipe->addTopic($dessertTopic);

        // Add test ingredients
        $ingredient1 = new Ingredient();
        $ingredient1->setName('Flour');
        $ingredient1->setQuantity('2 cups');
        $recipe->addIngredient($ingredient1);
        $this->entityManager->persist($ingredient1);

        $ingredient2 = new Ingredient();
        $ingredient2->setName('Sugar');
        $ingredient2->setQuantity('1 cup');
        $recipe->addIngredient($ingredient2);
        $this->entityManager->persist($ingredient2);

        $this->entityManager->persist($recipe);
        $this->entityManager->flush();
    }

    public function testRecipeIndex(): void
    {
        $this->client->request('GET', '/recipe');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Recipe index');
        $this->assertSelectorTextContains('body', 'Test Chocolate Cake');
    }

    public function testRecipeShow(): void
    {
        $recipe = $this->entityManager->getRepository(Recipe::class)->findOneBy(['name' => 'Test Chocolate Cake']);

        $this->client->request('GET', '/recipe/'.$recipe->getId());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'Test Chocolate Cake');
        $this->assertSelectorTextContains('body', 'Test instructions for chocolate cake');
        $this->assertSelectorTextContains('body', 'Flour (2 cups)');
        $this->assertSelectorTextContains('body', 'Sugar (1 cup)');
    }

    public function testRecipeNewForm(): void
    {
        $this->client->request('GET', '/recipe/new');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form[name="recipe"]');
        $this->assertSelectorExists('input[name="recipe[name]"]');
        $this->assertSelectorExists('textarea[name="recipe[content]"]');
        $this->assertSelectorExists('input[name="recipe[topics][]"]');
        $this->assertSelectorTextContains('button.btn-primary', 'Save');
    }

    public function testRecipeCreate(): void
    {
        // Get the topic ID from our test data
        $dessertTopic = $this->entityManager->getRepository(Topic::class)->findOneBy(['name' => 'Desserts']);

        $crawler = $this->client->request('GET', '/recipe/new');

        $form = $crawler->filter('button.btn-primary')->form([
            'recipe[name]' => 'New Test Recipe',
            'recipe[content]' => 'New test recipe instructions',
        ]);

        // Select the dessert topic checkbox
        $form['recipe[topics][0]']->tick();

        // Add ingredients using the collection prototype structure
        // First, we need to add ingredients dynamically since the form starts empty
        $this->client->submit($form);

        $this->assertResponseRedirects('/recipe');

        // Follow redirect and check if recipe was created
        $this->client->followRedirect();
        $this->assertSelectorTextContains('body', 'New Test Recipe');

        // Verify recipe was saved to database
        $recipe = $this->entityManager->getRepository(Recipe::class)->findOneBy(['name' => 'New Test Recipe']);
        $this->assertNotNull($recipe);
        $this->assertEquals('New test recipe instructions', $recipe->getContent());
        $this->assertCount(1, $recipe->getTopics());
    }

    public function testRecipeEditForm(): void
    {
        $recipe = $this->entityManager->getRepository(Recipe::class)->findOneBy(['name' => 'Test Chocolate Cake']);

        $this->client->request('GET', '/recipe/'.$recipe->getId().'/edit');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form[name="recipe"]');
        $this->assertSelectorExists('input[name="recipe[name]"]');
        $this->assertSelectorExists('textarea[name="recipe[content]"]');
        $this->assertSelectorExists('input[name="recipe[topics][]"]');

        // Check that form is pre-filled with existing data
        $this->assertSelectorExists('input[name="recipe[name]"][value="Test Chocolate Cake"]');
    }

    public function testRecipeEdit(): void
    {
        $recipe = $this->entityManager->getRepository(Recipe::class)->findOneBy(['name' => 'Test Chocolate Cake']);

        $crawler = $this->client->request('GET', '/recipe/'.$recipe->getId().'/edit');

        $form = $crawler->filter('button.btn-primary')->form([
            'recipe[name]' => 'Updated Chocolate Cake',
            'recipe[content]' => 'Updated instructions for chocolate cake',
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/recipe');

        // Follow redirect and check if recipe was updated
        $this->client->followRedirect();
        $this->assertSelectorTextContains('body', 'Updated Chocolate Cake');

        // Verify recipe was updated in database
        $updatedRecipe = $this->entityManager->getRepository(Recipe::class)->find($recipe->getId());
        $this->assertEquals('Updated Chocolate Cake', $updatedRecipe->getName());
        $this->assertEquals('Updated instructions for chocolate cake', $updatedRecipe->getContent());
    }

    public function testRecipeDelete(): void
    {
        $recipe = $this->entityManager->getRepository(Recipe::class)->findOneBy(['name' => 'Test Chocolate Cake']);
        $recipeId = $recipe->getId();

        // Get the recipe show page to extract CSRF token
        $crawler = $this->client->request('GET', '/recipe/'.$recipeId);

        // Find and submit the delete form
        $form = $crawler->selectButton('Delete')->form();
        $this->client->submit($form);

        $this->assertResponseRedirects('/recipe');

        // Follow redirect and verify recipe is no longer listed
        $this->client->followRedirect();
        $this->assertSelectorTextNotContains('body', 'Test Chocolate Cake');

        // Verify recipe was deleted from database
        $deletedRecipe = $this->entityManager->getRepository(Recipe::class)->find($recipeId);
        $this->assertNull($deletedRecipe);
    }

    public function testRecipeCreateWithInvalidData(): void
    {
        $crawler = $this->client->request('GET', '/recipe/new');

        // Test with very long name that might exceed database limits
        $form = $crawler->filter('button.btn-primary')->form([
            'recipe[name]' => str_repeat('A', 300), // Very long name
            'recipe[content]' => 'Valid content',
        ]);

        $this->client->submit($form);

        // Should either redirect (if validation passes) or stay on page with errors
        $this->assertTrue(
            $this->client->getResponse()->isRedirect()
            || $this->client->getResponse()->isSuccessful()
        );
    }

    public function testRecipeShowNotFound(): void
    {
        $this->client->request('GET', '/recipe/999999');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testRecipeEditNotFound(): void
    {
        $this->client->request('GET', '/recipe/999999/edit');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testRecipeDeleteNotFound(): void
    {
        $this->client->request('POST', '/recipe/999999', [
            '_token' => 'invalid_token',
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
