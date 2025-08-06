<?php

namespace App\Transformer;

use App\Dto\TopicDto;
use App\Entity\Topic;

class TopicTransformer
{
    public function fromEntity(Topic $topic, string $dtoClass): object
    {
        return match ($dtoClass) {
            TopicDto::class => $this->topicToDto($topic),
            default => throw new \InvalidArgumentException('Unsupported DTO class: '.$dtoClass),
        };
    }

    public function fromDto(object $dto, string|Topic $entityClassOrInstance): Topic
    {
        if (is_string($entityClassOrInstance)) {
            // Create new entity
            return match (true) {
                $dto instanceof TopicDto && Topic::class === $entityClassOrInstance => $this->dtoToTopic($dto),
                default => throw new \InvalidArgumentException('Unsupported transformation from '.get_class($dto).' to '.$entityClassOrInstance),
            };
        } else {
            // Update existing entity
            return match (true) {
                $dto instanceof TopicDto && $entityClassOrInstance instanceof Topic => $this->dtoToTopic($dto, $entityClassOrInstance),
                default => throw new \InvalidArgumentException('Unsupported transformation from '.get_class($dto).' to '.get_class($entityClassOrInstance)),
            };
        }
    }

    private function topicToDto(Topic $topic): TopicDto
    {
        $dto = new TopicDto();
        $dto->name = $topic->getName();

        return $dto;
    }

    private function dtoToTopic(TopicDto $dto, ?Topic $topic = null): Topic
    {
        if (null === $topic) {
            $topic = new Topic();
        }

        $topic->setName($dto->name);

        return $topic;
    }
}
