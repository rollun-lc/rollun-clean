<?php

namespace Clean\Common\Infrastructure\Mappers;

use Clean\Common\Application\Interfaces\MapperInterface;
use Symfony\Component\Serializer\Serializer;

class SimpleSymfonyMapper implements MapperInterface
{
    protected $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function map(mixed $data, string $type = null): mixed
    {
        if (is_array($data)) {
            return $this->mapFromArray($data, $type);
        }

        $array = $this->mapToArray($data);

        if ($type === self::TYPE_ARRAY) {
            return $array;
        }

        return $this->mapFromArray($array, $type);
    }

    public function mapToArray(object $data): array
    {
        return $this->serializer->normalize($data);
    }

    public function mapFromArray(array $data, string $type): object
    {
        return $this->serializer->denormalize($data, $type);
    }
}