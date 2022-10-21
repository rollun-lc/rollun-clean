<?php

namespace Clean\Common\Infrastructure\Mappers;

use Clean\Common\Application\Interfaces\DataStoreMapperInterface;
use Clean\Common\Application\Interfaces\MapperInterface;

abstract class DataStoreMapperAbstract implements DataStoreMapperInterface
{
    protected $mapper;

    public function __construct(MapperInterface $mapper)
    {
        $this->mapper = $mapper;
    }

    abstract protected function getModelClass(): string;

    /*protected function denormalize($data, $class, $format = null, $context = [])
    {
        $context = array_merge($context, [
            ObjectNormalizer::CIRCULAR_REFERENCE_LIMIT => 2,
            ObjectNormalizer::CALLBACKS => [
                'created_at' => function($value) {
                    return new DateTime($value);
                },
                'updated_at' => function($value) {
                    return new DateTime($value);
                }
            ]
        ]);
        return $this->serializer->denormalize($data, $class, $format, $context);
    }*/

    /**
     * @param object|array $data
     * @return mixed
     */
    public function fromStore(array $data): mixed
    {
        return $this->mapper->map($data, $this->getModelClass());
    }

    /**
     * @param mixed $data
     * @return object|array
     */
    public function toStore(mixed $data): array
    {
        return  $this->mapper->map($data, MapperInterface::TYPE_ARRAY);
    }
}