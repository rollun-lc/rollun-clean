<?php

namespace example\Orders\Application\Services;

use example\Orders\Application\Interfaces\MarketplaceRegistryInterface;
use example\Orders\Domain\Entities\Marketplace;

class MarketplaceRegistry implements MarketplaceRegistryInterface
{
    public function getByName(string $name): Marketplace
    {
        // TODO For testing
        return new Marketplace($name, 'amazon');
    }
}