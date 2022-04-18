<?php

namespace Core\Product\Service;

interface IProductService
{
    public function getAllProducts();

    public function getProductByProductId(int $productId);
}
