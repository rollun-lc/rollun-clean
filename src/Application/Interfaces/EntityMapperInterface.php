<?php

namespace Clean\Common\Application\Interfaces;

interface EntityMapperInterface
{
    /**
     * @param object $entity
     * @param object|string $dto
     * @return mixed
     */
    public function fromEntityToDto(object $entity, $dto);

    /**
     * @param object $dto
     * @param object|string $entity
     * @return mixed
     */
    public function fromDtoToEntity(object $dto, $entity);

    /**
     * @param array $data
     * @param object|string $dto
     * @return mixed
     */
    public function fromArrayToDto(array $data, $dto);

    /**
     * @param object $dto
     * @return mixed
     */
    public function fromDtoToArray(object $dto);

    /*public function fromEntityToArray(object $entity);

    public function fromArrayToEntity(array $array, $entity);*/
}