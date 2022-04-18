<?php

namespace Core\Domain\Repository\Discount;

interface IProductDiscountRepository
{
    public function getProductQtyDiscountByProductIdAndDiscountType(int $productId, string $discountType);

    public function getProductDiscountOffersByProductIdArray(array $productIdArray);
}
