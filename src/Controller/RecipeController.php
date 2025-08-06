<?php

namespace App\Controller;

use App\Dto\IngredientDto;
use App\Dto\RecipeDto;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use App\Transformer\IngredientTransformer;
use App\Transformer\RecipeTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/recipe')]
final class RecipeController extends AbstractController
{
    #[Route(name: 'app_recipe_index', methods: ['GET'])]
    public function index(RecipeRepository $recipeRepository): Response
    {
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recipe_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        RecipeTransformer $recipeTransformer,
        IngredientTransformer $ingredientTransformer,
    ): Response {
        $recipeDto = new RecipeDto();
        $form = $this->createForm(RecipeType::class, $recipeDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $recipeTransformer->fromDto($recipeDto, Recipe::class);

            // Handle ingredients mapping manually
            foreach ($recipeDto->ingredients as $ingredientDto) {
                if (!empty($ingredientDto->name)) {
                    $ingredient = $ingredientTransformer->fromDto($ingredientDto, Ingredient::class);
                    $recipe->addIngredient($ingredient);
                    $entityManager->persist($ingredient);
                }
            }

            $entityManager->persist($recipe);
            $entityManager->flush();

            return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_show', methods: ['GET'])]
    public function show(Recipe $recipe): Response
    {
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recipe_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Recipe $recipe,
        EntityManagerInterface $entityManager,
        RecipeTransformer $recipeTransformer,
        IngredientTransformer $ingredientTransformer,
    ): Response {
        $recipeDto = $recipeTransformer->fromEntity($recipe, RecipeDto::class);

        // Map existing ingredients to DTOs
        foreach ($recipe->getIngredients() as $ingredient) {
            $ingredientDto = $ingredientTransformer->fromEntity($ingredient, IngredientDto::class);
            $recipeDto->ingredients->add($ingredientDto);
        }

        $form = $this->createForm(RecipeType::class, $recipeDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipeTransformer->fromDto($recipeDto, $recipe);

            // Clear existing ingredients and add new ones
            foreach ($recipe->getIngredients() as $ingredient) {
                $recipe->removeIngredient($ingredient);
            }

            // Add ingredients from DTO
            foreach ($recipeDto->ingredients as $ingredientDto) {
                if (!empty($ingredientDto->name)) {
                    $ingredient = $ingredientTransformer->fromDto($ingredientDto, Ingredient::class);
                    $recipe->addIngredient($ingredient);
                    $entityManager->persist($ingredient);
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_delete', methods: ['POST'])]
    public function delete(Request $request, Recipe $recipe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($recipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
    }
}
