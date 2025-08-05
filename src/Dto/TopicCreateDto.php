<?php

namespace App\Dto;

use App\Entity\Topic;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(target: Topic::class)]
class TopicCreateDto
{
    public string $name = '';
}
