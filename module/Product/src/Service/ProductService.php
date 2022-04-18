<?php

namespace Product\Service;

use Core\Domain\Repository\Product\IProductRepository;
use Core\Product\Service\IProductService;

class ProductService implements IProductService
{
    /**
     * @var IProductRepository
     */
    protected $product_repository;

    public function __construct(IProductRepository $productRepository)
    {
        $this->product_repository = $productRepository;
    }

    public function getAllProducts()
    {
        return $this->product_repository->getAllProducts();
    }

    public function getProductByProductId(int $productId)
    {
        return $this->product_repository->getProductByProductId($productId);
    }
}
