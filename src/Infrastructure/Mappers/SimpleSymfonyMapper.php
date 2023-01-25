<?php

namespace Clean\Common\Infrastructure\Mappers;

use Clean\Common\Application\Interfaces\MapperInterface;
use Clean\Common\Utils\Helpers\Arr;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;

class SimpleSymfonyMapper implements MapperInterface
{
    protected $serializer;

    protected $withMagic;

    public function __construct(Serializer $serializer, ?bool $withMagic = true)
    {
        $this->serializer = $serializer;
        $this->withMagic = (bool) $withMagic;
    }

    public function map(mixed $data, string|object $type = null): mixed
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

    public function mapFromArray(array $data, string|object $type): object
    {
        $context = [];
        if (!$this->withMagic) {
            $context[AbstractNormalizer::IGNORED_ATTRIBUTES] = $this->getIgnoredAttributes($data, $type);
        }
        if (is_object($type)) {
            $array = $this->mapToArray($type);
            // TODO
            $data = $this->mergeArrays($array, $data);
            $type = get_class($type);
        }

        return $this->serializer->denormalize($data, $type, null, $context);
    }

    protected function getIgnoredAttributes(array $data, string $type)
    {
        $reflectionClass = new \ReflectionClass($type);
        $properties = array_map(function (\ReflectionProperty $property) {
            return $property->getName();
        }, $reflectionClass->getProperties());

        return array_diff(array_keys($data), $properties);
    }

    /**
     * @todo Temp decision
     * Проблема в тому, що індексовані масиви, котрі є простим полем, а не вложеним об'єктом,
     * при рекурсивному об'єданні не заміняються, а додаються.
     * Наприклад, якщо оригінальне значення ['a' => [1, 2]], а друге значення ['a' => [3]],
     * то в результаті отримуємо ['a' => [1, 2, 3], а цього не потрібно робити при мапінгу -
     * значення повинне замінитись на ['a' => [3].
     * Потрібно або знайти інше рішення, або оптимізувати даний метод
     *
     * @param array $array1
     * @param array $array2
     * @return mixed
     */
    protected function mergeArrays(array $array1, array $array2)
    {
        $result = Arr::mergeRecursive($array1, $array2);

        foreach ($result as $key => $value) {
            if (is_array($value) && isset($array2[$key])) {
                if (!Arr::isAssociativeArray($value)) {
                    $result[$key] = $array2[$key];
                } else {
                    $result[$key] = $this->mergeArrays($value, $array2[$key]);
                }
            }
        }

        return $result;
    }
}