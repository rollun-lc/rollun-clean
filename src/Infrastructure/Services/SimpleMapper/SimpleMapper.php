<?php

namespace Clean\Common\Infrastructure\Services\SimpleMapper;

use Clean\Common\Application\Interfaces\EntityMapperInterface;
use Clean\Common\Domain\Interfaces\ArrayableInterface;
use Clean\Common\Utils\Extensions\Collection;

class SimpleMapper implements EntityMapperInterface
{
    /**
     * @param object $entity
     * @param object|string $dto
     * @return mixed|object|string
     * @throws \ReflectionException
     * @todo
     */
    public function fromEntityToDto(object $entity, $dto)
    {
        $reflectionClass = new \ReflectionClass($dto);
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $name = $reflectionProperty->getName();
            $value = $this->extractValueFromData($entity, $name, function ($value) use ($dto, $name) {
                $type = $this->getDtoDocCommentType($dto, $name);
                if ($this->isPropertyTypeCollection($type)) {
                    if ($value instanceof \Traversable) {
                        ['itemClass' => $item] = $this->getCollectionClasses($type);
                        $items = [];
                        foreach ($value as $key => $element) {
                            $item = $this->fromEntityToDto($element, $item);
                            $items[$key] = $this->fromDtoToArray($item);
                        }
                        return $items;
                    }
                    // TODO Delete
                    return $this->fromDtoToArray($value);
                }

                return $this->fromEntityToDto($value, $type);
            });

            // Duplicated
            if ($value instanceof \DateTimeInterface) {
                $value = $value->format('c');
            } elseif (is_object($value) && !$value instanceof \Traversable) {
                $value = $this->fromDtoToArray($value);
            }

            if (is_array($value) || $value instanceof \Traversable) {
                $items = [];
                foreach ($value as $key => $item) {
                    if (is_object($item)) {
                        $items[$key] = $this->fromDtoToArray($item);
                    } else {
                        $items[$key] = $item;
                    }
                }
                $value = $items;
            }

            if (is_object($value)) {
                $value = $this->fromDtoToArray($value);
            }
            $params[$name] = $value;
        }

        return $this->fromArrayToDto($params, $dto);
    }

    public function fromDtoToEntity(object $dto, $entity)
    {
        $array = $this->fromDtoToArray($dto);
        if (is_string($entity)) {
            $entity = $this->createObject($entity, $array);
        }
        return $this->fromArrayToDto($array, $entity);
    }

    public function fromDtoToArray($dto)
    {
        $data = [];

        $reflectionClass = new \ReflectionClass($dto);
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $name = $reflectionProperty->getName();
            $value = $this->extractValueFromData($dto, $name, function ($value) {
                return $this->fromDtoToArray($value);
            });

            // Duplicated
            if ($value instanceof \DateTimeInterface) {
                $value = $value->format('c');
            } elseif (is_object($value) && !$value instanceof \Traversable) {
                $value = $this->fromDtoToArray($value);
            }

            if (is_array($value) || $value instanceof \Traversable) {
                $items = [];
                foreach ($value as $key => $item) {
                    if (is_object($item)) {
                        $items[$key] = $this->fromDtoToArray($item);
                    } else {
                        $items[$key] = $item;
                    }
                }
                $value = $items;
            }

            $data[$name] = $value;
        }

        return $data;
    }

    public function fromArrayToDto(array $data, $dto)
    {
        if (is_string($dto)) {
            $dto = $this->createObject($dto, $data);
        }

        foreach ($data as $name => $value) {
            $this->setValueToObject($dto, $name, $value);
        }

        return $dto;
    }

    /*public function fromEntityToArray(object $entity)
    {
        if ($entity instanceof ArrayableInterface) {
            return $entity->toArray();
        }

        $reflectionClass = new \ReflectionClass($entity);
        $results = [];
        foreach ($reflectionClass->getProperties() as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $name = $property->getName();

            $getter = 'get' . $name;

            if ($property->isPublic() || method_exists($entity, $getter)) {
                $value = $this->extractValueFromObject($entity, $name);

                if (is_object($value)) {
                    $value = $this->fromEntityToArray($value);
                }
            }



            $results[$name] = $value;
        }

        return $results;
    }*/

    /*public function fromArrayToEntity(array $array, $entity)
    {
        foreach ($array as $key => $value) {
            $this->setValueToObject($entity, $key, $value);
        }

        return $entity;
    }*/

    public function createObject($class, $params = [])
    {
        $reflectionClass = new \ReflectionClass($class);
        $arguments = [];
        if ($constructor = $reflectionClass->getConstructor()) {
            foreach ($constructor->getParameters() as $reflectionParameter) {
                $name = $reflectionParameter->getName();
                if (!isset($params[$name])) {
                    if ($reflectionParameter->isOptional()) {
                        continue;
                    }
                    throw new \Exception(
                        sprintf(
                            'Can not create object %s. Undefined parameter %s',
                            $reflectionClass->getName(),
                            $reflectionParameter->getName()
                        )
                    );
                }
                $arguments[$name] = $params[$name];
            }
        }
        return $reflectionClass->newInstanceArgs($arguments);
    }

    protected function setValueToObject($object, $name, $value)
    {
        $reflectionClass = new \ReflectionClass($object);
        $setter = 'set' . $name;
        if ($reflectionClass->hasMethod($setter)) {
            $this->setValueBySetter($object, $name, $value);
        } elseif ($reflectionClass->hasProperty($name)) {
            $this->setValueByProperty($object, $name, $value);
        }
    }

    protected function setValueByProperty($object, $property, $value)
    {
        $reflectionProperty = new \ReflectionProperty($object, $property);
        if ($type = $this->getDocCommentFromReflector($reflectionProperty)) {
            if ($this->isPropertyTypeBuiltin($type)) {
                settype($value, $type);
            } elseif ($this->isPropertyTypeCollection($type)) {
                ['collectionClass' => $collection, 'itemClass' => $item] = $this->getCollectionClasses($type);
                $value = $this->createCollection($collection, $item, $value);
            } elseif ($this->isPropertyTypeDate($type)) {
                $value = $this->createDateTime($type, $value);
            } elseif (is_array($value)) {
                $value = $this->fromArrayToDto($value, $type);
            }
        }
        $object->{$property} = $value;
    }

    protected function setValueBySetter($object, $name, $value)
    {
        $reflectionMethod = new \ReflectionMethod($object, 'set' . $name);
        $reflectionParameter = $reflectionMethod->getParameters()[0];
        if ($type = $reflectionParameter->getType()) {
            if ($type->isBuiltin()) {
                settype($value, $type->getName());
            } elseif (is_a($type->getName(), Collection::class, true)) {
                $collectionType = $this->getDtoMethodDocCommentType($object, $name);
                ['collectionClass' => $collection, 'itemClass' => $item] = $this->getCollectionClasses($collectionType);
                $value = $this->createCollection($collection, $item, $value);
            } elseif ($this->isPropertyTypeDate($type)) {
                $value = $this->createDateTime($type->getName(), $value);
            } elseif (is_array($value)) {
                $value = $this->fromArrayToDto($value, $type->getName());
            }

            if (is_null($value) && !$type->allowsNull()) {
                return;
            }
        }
        $reflectionMethod->invoke($object, $value);
    }

    protected function createDateTime($type, $value)
    {
        if ($value && !$value instanceof \DateTimeInterface) {
            $date = $this->createObject($type, $value);
            if ($date && is_numeric($value)) {
                $date->setTimestamp($value);
            } elseif ($date && is_string($value)) {
                $date->setTimestamp(strtotime($value));
            }

            return $date;
        }

        return $value;
    }

    protected function createCollection($collectionClass, $itemClass, $data)
    {
        $collection = $this->createObject($collectionClass ?? Collection::class);
        if ($data) {
            foreach ($data as $key => $value) {
                $item = $this->fromArrayToDto($value, $itemClass);
                $collection[$key] = $item;
            }
        }
        return $collection;
    }

    protected function getCollectionClasses($type)
    {
        $parts = explode('|', $type);

        if (count($parts) === 2) {
            $classes['collectionClass'] = trim($parts[0]);
            $classes['itemClass'] = trim(str_replace('[]', '', $parts[1]));
        } else {
            $classes['collectionClass'] = Collection::class;
            $classes['itemClass'] = str_replace('[]', '', $parts[0]);
        }

        return $classes;
    }

    protected function isPropertyTypeDate($type)
    {
        if ($type instanceof \ReflectionNamedType) {
            return is_a($type->getName(), \DateTimeInterface::class, true);
        }
        return is_a($type, \DateTimeInterface::class, true);
    }

    protected function isPropertyTypeCollection($type)
    {
        $parts = explode('|', $type);
        if (count($parts) === 2 && is_a($parts[0], Collection::class, true)) {
            return true;
        }

        return strpos($type, '[]');
    }

    protected function isPropertyTypeBuiltin($type)
    {
        return in_array($type, ['int', 'string', 'bool', 'float']);
    }

    protected function getDtoDocCommentType($dto, $name)
    {
        if ($type = $this->getDtoPropertyDocCommentType($dto, $name)) {
            return $type;
        }

        if ($type = $this->getDtoMethodDocCommentType($dto, $name)) {
            return $type;
        }

        return null;
    }

    protected function getDtoMethodDocCommentType($dto, $name)
    {
        $reflectionClass = new \ReflectionClass($dto);
        $setter = 'set' . $name;
        if ($reflectionClass->hasMethod($setter)) {
            $reflector = $reflectionClass->getMethod($setter);
            if ($type = $this->getDocCommentFromReflector($reflector)) {
                return $type;
            }
        }

        return null;
    }

    protected function getDtoPropertyDocCommentType($dto, $name)
    {
        $reflectionClass = new \ReflectionClass($dto);
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
            if (preg_match_all('/@(\w+)\s+(.*?)(\$.+)?\r?\n/m', $docComment, $matches)){
                $result = array_combine($matches[1], $matches[2]);
                if (isset($result['var'])) {
                    return $result['var'];
                }
                if (isset($result['param'])) {
                    return trim($result['param']);
                }
            }
        }

        return null;
    }

    protected function extractValueFromData($data, $name, callable $extractor)
    {
        $value = null;

        if (is_array($data) || $data instanceof \ArrayAccess) {
            $value = $data[$name];
        } elseif (is_object($data)) {
            $value = $this->extractValueFromObject($data, $name);
        }

        return $value;
    }

    protected function extractValueFromObject($object, $name)
    {
        $value = null;

        $reflectionClass = new \ReflectionClass($object);
        $getter = 'get' . $name;
        if ($reflectionClass->hasMethod($getter)) {
            $value = $reflectionClass->getMethod($getter)->invoke($object);
        } elseif ($reflectionClass->hasProperty($name) && $reflectionClass->getProperty($name)->isPublic()) {
            $value = $reflectionClass->getProperty($name)->getValue($object);
        }

        return $value;
    }
}