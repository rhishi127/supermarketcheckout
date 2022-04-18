<?php

namespace Core\Discount\Service;

interface IDiscountService
{
    public function decorateWithDiscountValues(array $cartArray);
}
