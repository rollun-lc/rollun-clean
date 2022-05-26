<?php

namespace test\example\Infrastructure\Controllers;

use Xiag\Rql\Parser\Query;
use PHPUnit\Framework\TestCase;

class CreateOrderControllerTest extends TestCase
{
    protected $container;

    protected function setUp(): void
    {
        global $container;
        $this->container = $container;
    }

    public function testCreateOrder()
    {
        $controller = $this->container->get('CreateOrderCallback');
        $response = $controller([
            'marketplace_name' => 'ebay',
            'customer_id' => '123',
            'items' => [
                [
                    'price' => 22.33,
                    'quantity' => 2,
                    'rid' => '3BZYU'
                ]
            ]
        ]);

        $response = json_decode($response, true);
        $responseOrder = $response['data'];
        $this->assertNotEmpty($responseOrder);

        $dataStore = $this->container->get('OrderMemoryDataStore');
        $createdOrder = $dataStore->query(new Query())[0] ?? null;
        $this->assertNotEmpty($createdOrder);
    }
}