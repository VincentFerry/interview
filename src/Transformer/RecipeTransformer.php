<?php

namespace App\Transformer;

use App\Dto\RecipeDto;
use App\Entity\Recipe;

class RecipeTransformer
{
    public function fromEntity(Recipe $recipe, string $dtoClass): object
    {
        return match ($dtoClass) {
            RecipeDto::class => $this->recipeToDto($recipe),
            default => throw new \InvalidArgumentException('Unsupported DTO class: '.$dtoClass),
        };
    }

    public function fromDto(object $dto, string|Recipe $entityClassOrInstance): Recipe
    {
        if (is_string($entityClassOrInstance)) {
            // Create new entity
            return match (true) {
                $dto instanceof RecipeDto && Recipe::class === $entityClassOrInstance => $this->dtoToRecipe($dto),
                default => throw new \InvalidArgumentException('Unsupported transformation from '.get_class($dto).' to '.$entityClassOrInstance),
            };
        } else {
            // Update existing entity
            return match (true) {
                $dto instanceof RecipeDto && $entityClassOrInstance instanceof Recipe => $this->dtoToRecipe($dto, $entityClassOrInstance),
                default => throw new \InvalidArgumentException('Unsupported transformation from '.get_class($dto).' to '.get_class($entityClassOrInstance)),
            };
        }
    }

    private function recipeToDto(Recipe $recipe): RecipeDto
    {
        $dto = new RecipeDto();
        $dto->name = $recipe->getName();
        $dto->content = $recipe->getContent();
        $dto->calories = null !== $recipe->getCalories() ? $recipe->getCalories() : null;

        foreach ($recipe->getTopics() as $topic) {
            $dto->topics->add($topic);
        }

        return $dto;
    }

    private function dtoToRecipe(RecipeDto $dto, ?Recipe $recipe = null): Recipe
    {
        if (null === $recipe) {
            $recipe = new Recipe();
        }

        $recipe->setName($dto->name);
        $recipe->setContent($dto->content);
        $recipe->setCalories($dto->calories);

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
