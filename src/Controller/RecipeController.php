<?php

namespace App\Controller;

use App\Dto\RecipeCreateDto;
use App\Dto\RecipeEditDto;
use App\Entity\Recipe;
use App\Form\RecipeCreateType;
use App\Form\RecipeEditType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
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
        ObjectMapperInterface $objectMapper,
    ): Response {
        $recipeDto = new RecipeCreateDto();
        $form = $this->createForm(RecipeCreateType::class, $recipeDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $objectMapper->map($recipeDto);

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
        ObjectMapperInterface $objectMapper,
    ): Response {
        $recipeDto = $objectMapper->map($recipe, RecipeEditDto::class);
        $form = $this->createForm(RecipeEditType::class, $recipeDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $objectMapper->map($recipeDto, $recipe);
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
