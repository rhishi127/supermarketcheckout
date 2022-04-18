<?php

namespace Domain\Repository\Product\Factory;

use Domain\Query\Product\ProductQueryMapper;
use Domain\Repository\Product\ProductRepository;

class ProductRepositoryFactory
{
    public function __invoke()
    {
        return new ProductRepository(
            new ProductQueryMapper()
        );
    }
}
