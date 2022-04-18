<?php

namespace Core\Domain\Repository\Product;

interface IProductRepository
{
    public function getAllProducts();

    public function getProductByProductId(int $productId);
}
