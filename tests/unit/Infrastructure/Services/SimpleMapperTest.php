<?php

namespace unit\Infrastructure\Services;

use Clean\Common\Infrastructure\Services\SimpleMapper\SimpleMapper;
use Clean\Common\Utils\Extensions\Collection;
use Clean\Common\Utils\Extensions\DateTime;
use PHPUnit\Framework\TestCase;
use unit\Infrastructure\Services\Classes\Entity;
use unit\Infrastructure\Services\Classes\EntityDto;
use unit\Infrastructure\Services\Classes\EntityInner;
use unit\Infrastructure\Services\Classes\EntityInnerDto;
use unit\Infrastructure\Services\Classes\EntityItem;
use unit\Infrastructure\Services\Classes\EntityItemDto;

class SimpleMapperTest extends TestCase
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
        $innerInnerDto = new EntityInnerDto(1);
        $innerInnerDto->title = 'Inner title 1';
        $innerDto->inner = $innerInnerDto;
        $entityDto = new EntityDto(1);
        $entityDto->items = $items;
        $entityDto->inner = $innerDto;
        $entityDto->date = new DateTime('2021-11-09T00:13:27+00:00');

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
                'inner' => [
                    'id' => 1,
                    'title' => 'Inner title 1',
                    'inner' => null,
                ]
            ],
            'items' => $items,
            'date' => '2021-11-09T00:13:27+00:00',
        ];
    }

    protected function createTestEntity()
    {
        $inner = new EntityInner(1);
        $inner->setTitle('Title 1');
        $innerInner = new EntityInner(1);
        $innerInner->setTitle('Inner title 1');
        $inner->setInner($innerInner);
        $entity = new Entity(1);
        $entity->setInner($inner);
        $items = new Collection();
        for ($i = 1; $i < 3; $i++) {
            $item = new EntityItem($i);
            $item->setName('Item ' . $i);
            $items[] = $item;
        }
        $entity->setItems($items);
        $entity->setDate(new DateTime('2021-11-09T00:13:27+00:00'));

        return $entity;
    }

    public function testEntityToDto()
    {
        $entity = $this->createTestEntity();

        $mapper = new SimpleMapper();

        $dto = $mapper->fromEntityToDto($entity, EntityDto::class);

        $this->assertEquals(1, $dto->id);
        $this->assertCount(2, $dto->items);
        $this->assertInstanceOf(EntityInnerDto::class, $dto->inner);
        $this->assertInstanceOf(EntityItemDto::class, $dto->items[0]);
    }

    public function testFromDtoToEntity()
    {
        $dto = $this->createTestEntityDto();

        $mapper = new SimpleMapper();

        $entity = $mapper->fromDtoToEntity($dto, Entity::class);

        $this->assertEquals(1, $entity->getId());
        $this->assertCount(2, $entity->getItems());
        $this->assertInstanceOf(EntityInner::class, $entity->getInner());
        $this->assertInstanceOf(EntityItem::class, $entity->getItems()[0]);
    }

    public function testTransferFromDtoToArray()
    {
        $entityDto = $this->createTestEntityDto();
        $mapper = new SimpleMapper();

        $data = $mapper->fromDtoToArray($entityDto);

        $this->assertEquals($this->createTestArrayData(), $data);
    }

    public function testTransferFromArrayToDto()
    {
        $data = $this->createTestArrayData();
        $mapper = new SimpleMapper();

        $dto = $mapper->fromArrayToDto($data, EntityDto::class);

        $this->assertInstanceOf(EntityDto::class, $dto);
        $this->assertInstanceOf(Collection::class, $dto->items);
        $this->assertCount(2, $dto->items);
        $this->assertInstanceOf(EntityInnerDto::class, $dto->inner);
        $this->assertEquals($data['date'], $dto->date->format('c'));
    }

    public function testTransferWithNoDtoClass()
    {
        $data = $this->createTestArrayData();
        $data['date'] = new DateTime('2021-01-01 00:00:00');
        $mapper = new SimpleMapper();

        $dto = $mapper->fromArrayToDto($data, EntityDto::class);

        $this->assertInstanceOf(EntityDto::class, $dto);
        $this->assertInstanceOf(Collection::class, $dto->items);
        $this->assertCount(2, $dto->items);
        $this->assertInstanceOf(EntityInnerDto::class, $dto->inner);
    }

   /* public function testFromEntityToArray()
    {
        $entity = $this->createTestEntity();

        $mapper = new SimpleMapper();

        $result = $mapper->fromEntityToArray($entity);
    }*/
}