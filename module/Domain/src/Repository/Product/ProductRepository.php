<?php

namespace Domain\Repository\Product;

use Core\Domain\Repository\Product\IProductRepository;
use Domain\Query\Product\ProductQueryMapper;

class ProductRepository implements IProductRepository
{
    /**
     * @var ProductQueryMapper
     */
    protected $product_query_mapper;

    public function __construct(
        ProductQueryMapper $productQueryMapper
    ) {
        $this->product_query_mapper = $productQueryMapper;
    }

    public function getAllProducts()
    {
        return $this->product_query_mapper->getAllProducts();
    }

    public function getProductByProductId(int $productId)
    {
        return $this->product_query_mapper->getProductByProductId($productId);
    }
}
