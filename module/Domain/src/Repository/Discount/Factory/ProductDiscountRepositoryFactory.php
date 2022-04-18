<?php

namespace Domain\Repository\Discount\Factory;

use Domain\Query\Discount\ProductDiscountQueryMapper;
use Domain\Repository\Discount\ProductDiscountRepository;

class ProductDiscountRepositoryFactory
{
    public function __invoke()
    {
        return new ProductDiscountRepository(
            new ProductDiscountQueryMapper()
        );
    }
}
