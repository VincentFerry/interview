<?php

namespace App\Transformer;

use App\Dto\RecipeCreateDto;
use App\Dto\RecipeEditDto;
use App\Entity\Recipe;

class RecipeTransformer
{
    public function fromEntity(Recipe $recipe, string $dtoClass): object
    {
        return match ($dtoClass) {
            RecipeEditDto::class => $this->recipeToEditDto($recipe),
            default => throw new \InvalidArgumentException('Unsupported DTO class: '.$dtoClass),
        };
    }

    public function fromDto(object $dto, string|Recipe $entityClassOrInstance): Recipe
    {
        if (is_string($entityClassOrInstance)) {
            // Create new entity
            return match (true) {
                $dto instanceof RecipeCreateDto && Recipe::class === $entityClassOrInstance => $this->createDtoToRecipe($dto),
                default => throw new \InvalidArgumentException('Unsupported transformation from '.get_class($dto).' to '.$entityClassOrInstance),
            };
        } else {
            // Update existing entity
            return match (true) {
                $dto instanceof RecipeEditDto && $entityClassOrInstance instanceof Recipe => $this->editDtoToRecipe($dto, $entityClassOrInstance),
                default => throw new \InvalidArgumentException('Unsupported transformation from '.get_class($dto).' to '.get_class($entityClassOrInstance)),
            };
        }
    }

    private function recipeToEditDto(Recipe $recipe): RecipeEditDto
    {
        $dto = new RecipeEditDto();
        $dto->name = $recipe->getName();
        $dto->content = $recipe->getContent();

        foreach ($recipe->getTopics() as $topic) {
            $dto->topics->add($topic);
        }

        return $dto;
    }

    private function createDtoToRecipe(RecipeCreateDto $dto): Recipe
    {
        $recipe = new Recipe();
        $recipe->setName($dto->name);
        $recipe->setContent($dto->content);

        foreach ($dto->topics as $topic) {
            $recipe->addTopic($topic);
        }

        return $recipe;
    }

    private function editDtoToRecipe(RecipeEditDto $dto, Recipe $recipe): Recipe
    {
        $recipe->setName($dto->name);
        $recipe->setContent($dto->content);

        // Clear existing topics and add new ones
        foreach ($recipe->getTopics() as $topic) {
            $recipe->removeTopic($topic);
        }

        foreach ($dto->topics as $topic) {
            $recipe->addTopic($topic);
        }

        return $recipe;
    }
}
