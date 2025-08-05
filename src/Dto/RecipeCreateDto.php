<?php

namespace App\Dto;

use App\Entity\Recipe;
use App\Entity\Topic;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\ObjectMapper\Attribute\Map;

use function PHPSTORM_META\map;

#[Map(target: Recipe::class)]
class RecipeCreateDto
{
    public string $name = '';

    public string $content = '';

    /**
     * @var Collection<int, Topic>
     */
    public Collection $topics;

    public function __construct()
    {
        $this->topics = new ArrayCollection();
    }
}
