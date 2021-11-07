<?php

namespace unit\Infrastructure\Services;

use Clean\Common\Infrastructure\Services\SimpleMapper\SimpleReflectionMapper;
use Clean\Common\Utils\Extensions\Collection;
use Clean\Common\Utils\Extensions\DateTime;
use PHPUnit\Framework\TestCase;
use unit\Infrastructure\Services\Classes\Entity;
use unit\Infrastructure\Services\Classes\EntityDto;
use unit\Infrastructure\Services\Classes\EntityInner;
use unit\Infrastructure\Services\Classes\EntityInnerDto;
use unit\Infrastructure\Services\Classes\EntityItem;
use unit\Infrastructure\Services\Classes\EntityItemDto;

class ReflectionMapperTest extends TestCase
{
    protected $count = 2;

    protected function createTestEntityDto()
    {
        for ($i = 1; $i <= $this->count; $i++) {
            $item = new EntityItemDto($i);
            $item->name = 'Item ' . $i;
            $items[] = $item;
        }
        $innerDto = new EntityInnerDto(1);
        $innerDto->title = 'Title 1';
        $entityDto = new EntityDto(1);
        $entityDto->items = $items;
        $entityDto->inner = $innerDto;

        return $entityDto;
    }

    protected function createTestArrayData()
    {
        for ($i = 1; $i <= $this->count; $i++) {
            $items[] = [
                'id' => $i,
                'name' => 'Item ' . $i,
            ];
        }
        return [
            'id' => 1,
            'inner' => [
                'id' => 1,
                'title' => 'Title 1',
            ],
            'items' => $items,
            'date' => null,
        ];
    }

    public function testTransferToDto()
    {
        $inner = new EntityInner(1);
        $inner->setTitle('Title 1');
        $entity = new Entity(1);
        $entity->setInner($inner);
        $items = new Collection();
        for ($i = 1; $i < 3; $i++) {
            $item = new EntityItem($i);
            $item->setName('Item ' . $i);
            $items[] = $item;
        }
        $entity->setItems($items);


        $mapper = new SimpleReflectionMapper();

        $dto = $mapper->fromEntityToDto($entity, EntityDto::class);

        $this->assertEquals(1, $dto->id);
        $this->assertCount(2, $dto->items);
        $this->assertInstanceOf(EntityInnerDto::class, $dto->inner);
        $this->assertInstanceOf(EntityItemDto::class, $dto->items[0]);
    }

    public function testTransferFromDto()
    {
        $entityDto = $this->createTestEntityDto();

        $mapper = new SimpleReflectionMapper();

        $entity = $mapper->fromDtoToEntity($entityDto, Entity::class);

        $this->assertEquals(1, $entity->getId());
        $this->assertCount(2, $entity->getItems());
        $this->assertInstanceOf(EntityInner::class, $entity->getInner());
        $this->assertInstanceOf(EntityItem::class, $entity->getItems()[0]);
    }

    public function testTransferFromArrayToDto()
    {
        $data = $this->createTestArrayData();
        $mapper = new SimpleReflectionMapper();

        $dto = $mapper->fromArrayToDto($data, EntityDto::class);

        $this->assertInstanceOf(EntityDto::class, $dto);
        $this->assertIsArray($dto->items);
        $this->assertCount(2, $dto->items);
        $this->assertInstanceOf(EntityInnerDto::class, $dto->inner);
    }

    public function testTransferFromDtoToArray()
    {
        $entityDto = $this->createTestEntityDto();
        $mapper = new SimpleReflectionMapper();

        $data = $mapper->fromDtoToArray($entityDto);

        $this->assertEquals($this->createTestArrayData(), $data);
    }

    public function testTransferWithNoDtoClass()
    {
        $data = $this->createTestArrayData();
        $data['date'] = new DateTime('2021-01-01 00:00:00');
        $mapper = new SimpleReflectionMapper();

        $dto = $mapper->fromArrayToDto($data, EntityDto::class);

        $this->assertInstanceOf(EntityDto::class, $dto);

    }
}