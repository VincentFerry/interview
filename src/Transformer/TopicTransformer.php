<?php

namespace App\Transformer;

use App\Dto\TopicCreateDto;
use App\Dto\TopicEditDto;
use App\Entity\Topic;

class TopicTransformer
{
    public function fromEntity(Topic $topic, string $dtoClass): object
    {
        return match ($dtoClass) {
            TopicEditDto::class => $this->topicToEditDto($topic),
            default => throw new \InvalidArgumentException('Unsupported DTO class: '.$dtoClass),
        };
    }

    public function fromDto(object $dto, string|Topic $entityClassOrInstance): Topic
    {
        if (is_string($entityClassOrInstance)) {
            // Create new entity
            return match (true) {
                $dto instanceof TopicCreateDto && Topic::class === $entityClassOrInstance => $this->createDtoToTopic($dto),
                default => throw new \InvalidArgumentException('Unsupported transformation from '.get_class($dto).' to '.$entityClassOrInstance),
            };
        } else {
            // Update existing entity
            return match (true) {
                $dto instanceof TopicEditDto && $entityClassOrInstance instanceof Topic => $this->editDtoToTopic($dto, $entityClassOrInstance),
                default => throw new \InvalidArgumentException('Unsupported transformation from '.get_class($dto).' to '.get_class($entityClassOrInstance)),
            };
        }
    }

    private function topicToEditDto(Topic $topic): TopicEditDto
    {
        $dto = new TopicEditDto();
        $dto->name = $topic->getName();

        return $dto;
    }

    private function createDtoToTopic(TopicCreateDto $dto): Topic
    {
        $topic = new Topic();
        $topic->setName($dto->name);

        return $topic;
    }

    private function editDtoToTopic(TopicEditDto $dto, Topic $topic): Topic
    {
        $topic->setName($dto->name);

        return $topic;
    }
}
