<?php

namespace Domain\Repository\Discount;

use Core\Domain\Repository\Discount\IProductDiscountRepository;
use Domain\Entity\ResultEntity;
use Domain\Query\Discount\ProductDiscountQueryMapper;

class ProductDiscountRepository implements IProductDiscountRepository
{
    /**
     * @var ProductDiscountQueryMapper
     */
    protected $product_discount_query_mapper;

    public function __construct(
        ProductDiscountQueryMapper $productDiscountQueryMapper
    ) {
        $this->product_discount_query_mapper = $productDiscountQueryMapper;
    }

    /**
     * function used to get product qty discount records
     *
     * @param int $productId
     * @param string $discountType
     * @return \Domain\Entity\ResultEntity
     */
    public function getProductQtyDiscountByProductIdAndDiscountType(int $productId, string $discountType) : ResultEntity
    {
        return $this->product_discount_query_mapper
            ->getProductQtyDiscountByProductIdAndDiscountType($productId, $discountType);
    }

    /**
     * function used to get discount products by array
     *
     * @param array $productIdArray
     * @return \Domain\Entity\ResultEntity
     */
    public function getProductDiscountOffersByProductIdArray(array $productIdArray) : ResultEntity
    {
        return $this->product_discount_query_mapper
            ->getProductDiscountOffersByProductIdArray($productIdArray);
    }
}
