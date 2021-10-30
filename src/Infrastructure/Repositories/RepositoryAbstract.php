<?php

namespace Clean\Common\Infrastructure\Repositories;

use Clean\Common\Application\Interfaces\FromArrayInterface;
use Clean\Common\Application\Interfaces\MapperInterface;
use Clean\Common\Utils\Extensions\Collection;
use Clean\Common\Utils\Helpers\Str;

abstract class RepositoryAbstract implements FromArrayInterface
{
    protected $mapper;

    protected function getMapper()
    {
        return $this->mapper;
    }

    abstract protected function getModelClass(): string;

    /**
     * @param $data
     */
    protected function createEntities($items)
    {
        $models = [];
        foreach ($items as $item) {
            $models[] = $this->createEntity($item);
        }

        return new Collection($models);
    }

    protected function createEntity($data)
    {
        // TODO Отрефакторить
        if ($mapper = $this->getMapper()) {
            if ($mapper instanceof MapperInterface) {
                $data = $mapper->map($data);
            } elseif (is_callable($mapper)) {
                $data = call_user_func($mapper, $data);
            }

        }

        $className = $this->getModelClass();
        $model = new $className();
        foreach ($data as $key => $value) {
            $formatted = Str::toCamelCase($key, true);
            $setter = 'set' . $formatted;
            if (method_exists($model, $setter)) {
                $model->{$setter}($value);
            } elseif (property_exists($model, $formatted) && array_key_exists($formatted, get_object_vars($model))) {
                $model->{$formatted} = $value;
            }
        }

        return $model;
    }

    public function createFromArray($data)
    {
        return $this->createEntity($data);
    }
}