<?php

namespace App\Transformer;

use App\Dto\IngredientDto;
use App\Entity\Ingredient;

class IngredientTransformer
{
    public function fromEntity(Ingredient $ingredient, string $dtoClass): object
    {
        return match ($dtoClass) {
            IngredientDto::class => $this->ingredientToDto($ingredient),
            default => throw new \InvalidArgumentException('Unsupported DTO class: '.$dtoClass),
        };
    }

    public function fromDto(object $dto, string|Ingredient $entityClassOrInstance): Ingredient
    {
        if (is_string($entityClassOrInstance)) {
            // Create new entity
            return match (true) {
                $dto instanceof IngredientDto && Ingredient::class === $entityClassOrInstance => $this->dtoToIngredient($dto),
                default => throw new \InvalidArgumentException('Unsupported transformation from '.get_class($dto).' to '.$entityClassOrInstance),
            };
        } else {
            // Update existing entity - for ingredients we don't have separate edit DTOs, so we use the same DTO
            return match (true) {
                $dto instanceof IngredientDto && $entityClassOrInstance instanceof Ingredient => $this->updateIngredientFromDto($dto, $entityClassOrInstance),
                default => throw new \InvalidArgumentException('Unsupported transformation from '.get_class($dto).' to '.get_class($entityClassOrInstance)),
            };
        }
    }

    private function ingredientToDto(Ingredient $ingredient): IngredientDto
    {
        $dto = new IngredientDto();
        $dto->name = $ingredient->getName();
        $dto->quantity = $ingredient->getQuantity();

        return $dto;
    }

    private function dtoToIngredient(IngredientDto $dto): Ingredient
    {
        $ingredient = new Ingredient();
        $ingredient->setName($dto->name);
        $ingredient->setQuantity($dto->quantity);

        return $ingredient;
    }

    private function updateIngredientFromDto(IngredientDto $dto, Ingredient $ingredient): Ingredient
    {
        $ingredient->setName($dto->name);
        $ingredient->setQuantity($dto->quantity);

        return $ingredient;
    }
}
