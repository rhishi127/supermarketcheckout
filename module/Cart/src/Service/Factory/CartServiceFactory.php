<?php

namespace Cart\Service\Factory;

use Cart\Service\CartService;
use Discount\Service\Factory\DiscountServiceFactory;
use Product\Service\Factory\ProductServiceFactory;

class CartServiceFactory
{
    public function __invoke(): CartService
    {
        $productServiceFactory = new ProductServiceFactory();
        $productService = new $productServiceFactory();
        $discountServiceFactory = new DiscountServiceFactory();
        $discountService = new $discountServiceFactory();
        return new CartService($productService(), $discountService());
    }
}
