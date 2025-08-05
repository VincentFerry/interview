<?php

namespace App\Dto;

use App\Entity\Topic;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class RecipeCreateDto
{
    public string $name = '';

    public string $content = '';

    /**
     * @var Collection<int, Topic>
     */
    public Collection $topics;

    /**
     * @var Collection<int, IngredientDto>
     */
    public Collection $ingredients;

    public function __construct()
    {
        $this->topics = new ArrayCollection();
        $this->ingredients = new ArrayCollection();
    }
}
