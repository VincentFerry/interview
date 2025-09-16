<?php

namespace App\Service;

use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NutritionixService
{
    private HttpClientInterface $client;
    private string $appId;
    private string $appKey;
    private EntityManagerInterface $entityManager;

    public function __construct(
        HttpClientInterface $client,
        string $appId,
        string $appKey,
        EntityManagerInterface $entityManager,
    ) {
        $this->client = $client;
        $this->appId = $appId;
        $this->appKey = $appKey;
        $this->entityManager = $entityManager;
    }

    /**
     * Set recipe calories (calculate via API if needed).
     */
    public function setRecipeCalories(Recipe $recipe): ?string
    {
        // Check database first
        if (null !== $recipe->getCalories()) {
            return null;
        }

        // If not in database, calculate via API
        $query = $this->buildNutritionQuery($recipe);
        $result = $this->getNutrients($query);

        // Handle API errors
        if (isset($result['error'])) {
            $recipe->setCalories(0);

            return $result['error'];
        }

        $totalCalories = 0;
        foreach ($result['foods'] as $food) {
            $totalCalories += $food['nf_calories'] ?? 0;
        }

        $calories = (int) round($totalCalories);

        // Save result to database for next time
        $recipe->setCalories($calories);
        $this->entityManager->persist($recipe);
        $this->entityManager->flush();

        return null;
    }

    /**
     * Invalidate recipe calories (call when modifying ingredients).
     */
    public function invalidateRecipeCalories(Recipe $recipe): void
    {
        $recipe->setCalories(null);
        $this->entityManager->persist($recipe);
        // Don't flush here, let the controller handle it
    }

    /**
     * Force recalculation of calories.
     */
    public function refreshRecipeCalories(Recipe $recipe): ?string
    {
        $this->invalidateRecipeCalories($recipe);

        return $this->setRecipeCalories($recipe);
    }

    /**
     * @return array<string, mixed>
     */
    public function getNutrients(string $query): array
    {
        try {
            $response = $this->client->request('POST', 'https://trackapi.nutritionix.com/v2/natural/nutrients', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-app-id' => $this->appId,
                    'x-app-key' => $this->appKey,
                ],
                'json' => [
                    'query' => $query,
                    'timezone' => 'Europe/Paris',
                ],
            ]);

            return $response->toArray();
        } catch (
            TransportExceptionInterface|
            ClientExceptionInterface|
            ServerExceptionInterface|
            RedirectionExceptionInterface $e
        ) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Build nutrition query from recipe ingredients.
     */
    private function buildNutritionQuery(Recipe $recipe): string
    {
        $ingredientNames = [];
        foreach ($recipe->getIngredients() as $ingredient) {
            $ingredientNames[] = $ingredient->getName().' ('.$ingredient->getQuantity().')';
        }

        return implode(', ', $ingredientNames);
    }
}
