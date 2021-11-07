<?php

namespace Clean\Common\Infrastructure\Services\SimpleMapper;

use Clean\Common\Application\Interfaces\DtoMapperInterface;
use Clean\Common\Application\Interfaces\EntityMapperInterface;
use PHPUnit\Framework\Constraint\TraversableContains;

/**
 * @todo
 */
class SimpleReflectionMapper implements DtoMapperInterface, EntityMapperInterface
{
    protected const BUILTIN_TYPES = [
        'int' => 'integer',
        'float' => 'double',
        'string' => 'string',
        'bool' => 'boolean',
        'null' => 'null',
    ];

    protected const DIRECTION_TO_DTO = 'fromEntityToDto';

    protected const DIRECTION_TO_ENTITY = 'fromDtoToEntity';

    public function map(object $instance, array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($instance, $key)) {
                $instance->{$key} = $value;
            }
        }

        return $instance;
    }

    protected function isBuiltin($type)
    {
        return in_array($type, self::BUILTIN_TYPES) || array_key_exists($type, self::BUILTIN_TYPES);
    }

    protected function isCollection($type)
    {
        return $this->isTraversable($type) || $this->isCommentedArray($type);
    }

    protected function isTraversable($type)
    {
        return is_a($type, \Traversable::class, true);
    }

    protected function isCommentedArray($type)
    {
        return strpos($type, '[]');
    }

    protected function getCommentedArrayType($type)
    {
        return str_replace('[]', '', $type);
    }

    /**
     * @param string $name
     * @param $from
     * @return array|object
     */
    protected function getParamFromValue(string $name, $from)
    {
        if (is_array($from)) {
            return $from[$name] ?? null;
        }

        $getter = 'get' . $name;
        if (method_exists($from, $getter)) {
            return $from->{$getter}();
        }

        if (property_exists($from, $name)) {
            $value = $from->{$name};
        }

        return $value;
    }

    protected function createInstanceFromAnother($object, string $className, string $direction)
    {
        $reflectionClass = new \ReflectionClass($className);
        $arguments = [];
        if ($constructor = $reflectionClass->getConstructor()) {
            foreach ($constructor->getParameters() as $reflectionParameter) {
                $name = $reflectionParameter->getName();
                $value = $this->getParamFromValue($name, $object);

                $type = $reflectionParameter->getType() ? $reflectionParameter->getType()->getName() : null;
                $value = $this->setType($value, $type, $direction);

                $arguments[$name] = $value;
            }
        }
        return $reflectionClass->newInstanceArgs($arguments);
    }

    protected function setType($value, $type, $direction)
    {
        if ($value) {
            if ((is_scalar($value) || is_null($value)) && $this->isBuiltin($type)) {
                settype($value, $type);
            } elseif ($this->isCollection($type) && (is_array($value) || $value instanceof \Traversable)) {
                /*$className = str_replace('[]', '', $type);
                $items = [];
                foreach ($value as $item) {
                    $items[] = $createCollectionItem($item, $className);
                }*/
                $collection = [];
                $type = $this->getCollectionItemDocCommentType($item, $type);
                foreach ($value as $item) {
                    $collection[] = $this->fromDtoToEntity($item, $type);
                }
                $value = $collection;
                $value = $items;
            } elseif (class_exists($type)) {
                // TODO
                $directionMethod = is_object($value) ? [$this, $direction] : [$this, 'fromArrayToDto'];
                $value = $directionMethod($value, $type);
            }
        }

        return $value;
    }

    /**
     * @param object $entity
     * @param object|string $dto
     * @return mixed
     */
    public function fromEntityToDto(object $entity, $dto, $test = null)
    {
        if (is_string($dto)) {
            $reflectionClass = new \ReflectionClass($dto);
            $dto = $this->createInstanceFromAnother($entity, $dto, self::DIRECTION_TO_DTO);
        }

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $name = $reflectionProperty->getName();

            $value = $this->getParamFromValue($name, $entity);

            $type = $this->getPropertyDocCommentType($dto, $name);

            if (!isset($value) && !$this->isBuiltin($type) && $type === gettype($entity)) {
                $value = $entity;
            } elseif ($this->isCollection($type)) {
                // TODO Only array not Collection
                $collection = [];
                $type = $this->getCollectionItemDocCommentType($dto, $name);
                foreach ($value as $item) {
                    $collection[] = $this->fromEntityToDto($item, $type);
                }
                $value = $collection;
            } else {
                $value = $this->setType($value, $type, self::DIRECTION_TO_DTO);
            }

            $dto->{$name} = $value;
        }

        return $dto;
    }

    /**
     * @param object $dto
     * @param object|string $entity
     * @return mixed
     */
    public function fromDtoToEntity(object $dto, $entity)
    {

        if (is_string($entity)) {
            $entity = $this->createInstanceFromAnother($dto, $entity, self::DIRECTION_TO_ENTITY);
        }

        $reflectionClass = new \ReflectionClass($dto);
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $name = $reflectionProperty->getName();

            $value = $this->getParamFromValue($name, $dto);

            $setter = 'set' . $name;
            $reflectionMethod = new \ReflectionMethod($entity, $setter);
            $reflectionParameter = $reflectionMethod->getParameters()[0];
            $type = $reflectionParameter->getType() ? $reflectionParameter->getType()->getName() : null;

            if ($this->isCollection($type)) {
                $collection = new $type();
                $type = $this->getCollectionItemDocCommentType($entity, $name);
                foreach ($value as $item) {
                    $collection[] = $this->fromDtoToEntity($item, $type);
                }
                $value = $collection;
            } else {
                $value = $this->setType($value, $type, self::DIRECTION_TO_ENTITY);
            }

            $reflectionMethod->invoke($entity, $value);
        }

        return $entity;
    }

    protected function getCollectionItemDocCommentType($entity, $name)
    {
        if ($type = $this->getPropertyDocCommentType($entity, $name) and $this->isCommentedArray($type)) {
            return $this->getCommentedArrayType($type);
        }

        if ($type = $this->getMethodDocCommentType($entity, $name) and $this->isCommentedArray($type)) {
            return $this->getCommentedArrayType($type);
        }

        return null;
    }

    protected function getMethodDocCommentType($entity, $name)
    {
        $reflectionClass = new \ReflectionClass($entity);
        $setter = 'set' . $name;
        if ($reflectionClass->hasMethod($setter)) {
            $reflector = $reflectionClass->getMethod($setter);
            if ($type = $this->getDocCommentFromReflector($reflector)) {
                return $type;
            }
        }

        return null;
    }

    /**
     * @param $entity
     * @param $name
     * @return mixed|null
     */
    protected function getPropertyDocCommentType($entity, $name)
    {
        $reflectionClass = new \ReflectionClass($entity);
        if ($reflectionClass->hasProperty($name)) {
            $reflector = $reflectionClass->getProperty($name);
            if ($type = $this->getDocCommentFromReflector($reflector)) {
                return $type;
            }
        }

        return null;
    }

    protected function getDocCommentFromReflector(\Reflector $reflector)
    {
        if ($docComment = $reflector->getDocComment()) {
            if (preg_match_all('/@(\w+)\s+(.*)\r?\n/m', $docComment, $matches)){
                $result = array_combine($matches[1], $matches[2]);
                if (isset($result['var'])) {
                    return $result['var'];
                }
            }
        }

        return null;
    }

    /**
     * @param array $data
     * @param object|string $dto
     * @return mixed
     * @todo implementation add test
     */
    public function fromArrayToDto(array $data, $dto)
    {
        if (is_string($dto)) {
            $reflectionClass = new \ReflectionClass($dto);
            $dto = $this->createInstanceFromAnother($data, $dto, self::DIRECTION_TO_DTO);
        }

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $name = $reflectionProperty->getName();

            $value = $this->getParamFromValue($name, $data);

            $type = $this->getPropertyDocCommentType($dto, $name);

            if (!isset($value) && !$this->isBuiltin($type) && $type === gettype($data)) {
                $value = $data;
            } elseif ($this->isCollection($type)) {
                // TODO Only array not Collection
                $collection = [];
                $type = $this->getCollectionItemDocCommentType($dto, $name);
                foreach ($value as $item) {
                    $collection[] = $this->fromArrayToDto($item, $type);
                }
                $value = $collection;
            } else {
                $value = $this->setType($value, $type, self::DIRECTION_TO_DTO);
            }

            $dto->{$name} = $value;
        }

        return $dto;
    }

    public function fromDtoToArray($dto)
    {
        $result = [];

        $reflectionClass = new \ReflectionClass($dto);
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $name = $reflectionProperty->getName();

            $value = $this->getParamFromValue($name, $dto);

            if (is_array($value)) {
                $collection = [];
                foreach ($value as $item) {
                    $collection[] = $this->fromDtoToArray($item);
                }
                $value = $collection;
            } elseif(is_object($value)) {
                $value = $this->fromDtoToArray($value);
            }

            $result[$name] = $value;
        }

        return $result;
    }

}