<?php

namespace Product\Service\Factory;

use Domain\Repository\Product\Factory\ProductRepositoryFactory;
use Product\Service\ProductService;

class ProductServiceFactory
{
    public function __invoke(): ProductService
    {
        $productRepositoryFactory = new ProductRepositoryFactory();
        $productRepository = new $productRepositoryFactory();
        return new ProductService($productRepository());
    }
}
