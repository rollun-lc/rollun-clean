<?php

namespace example\Orders\Application\Interfaces;

use example\Orders\Domain\Entities\Marketplace;

interface MarketplaceRegistryInterface
{
    public function getByName(string $name): Marketplace;
}