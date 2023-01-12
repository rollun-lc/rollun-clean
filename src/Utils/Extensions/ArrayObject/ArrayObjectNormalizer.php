<?php

namespace Clean\Common\Utils\Extensions\ArrayObject;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ArrayObjectNormalizer implements NormalizerInterface
{
    protected const FORMAT_ARRAY = 'array';

    /**
     * @param ArrayObject $object
     * @param string|null $format
     * @param array $context
     * @return array|\ArrayObject|bool|float|int|string|void|null
     */
    public function normalize(mixed $object, string $format = null, array $context = [])
    {
        $format = $format ?? self::FORMAT_ARRAY;

        $array = $object->toArray();

        switch ($format) {
            case self::FORMAT_ARRAY:
                return $array;
                break;
        }
    }

    public function supportsNormalization(mixed $data, string $format = null)
    {
        return $data instanceof ArrayObject;
    }
}