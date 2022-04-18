<?php

namespace Discount\Service\Factory;

use Discount\Service\DiscountService;
use Domain\Repository\Discount\Factory\ProductDiscountRepositoryFactory;

class DiscountServiceFactory
{
    public function __invoke(): DiscountService
    {
        $productDiscountRepositoryFactory = new ProductDiscountRepositoryFactory();
        $productDiscountRepository = new $productDiscountRepositoryFactory();
        return new DiscountService($productDiscountRepository());
    }
}
