<?php

namespace Clean\Common\Utils\Extensions\ArrayObject;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ArrayObjectNormalizer implements NormalizerInterface, DenormalizerInterface
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

    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        // TODO check unique true
        $object = new ArrayObject(true);
        foreach ($data as $value) {
            $object->addItem(new ArrayObjectItem($value));
        }

        return $object;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null)
    {
        return is_array($data) && is_a($type, ArrayObject::class, true);
    }
}