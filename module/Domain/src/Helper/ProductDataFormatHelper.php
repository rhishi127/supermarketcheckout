<?php

namespace Domain\Helper;

use Domain\Entity\Discount\ProductDiscountEntity;

class ProductDataFormatHelper
{
    protected $temp_array = [];

    public function formatData($data)
    {
        if (is_array($data) && count($data) > 0) {
            foreach ($data as $product) {
                $productId = $product['product_id'];
                $discountArray = $this->getDiscountArray($product);
                if (! array_key_exists('product'. $productId, $this->temp_array)) {
                    $product['product_id'] = $productId;
                    $this->temp_array['product'. $productId] = $product;
                    $this->temp_array['product'. $productId]['discount'][] = $discountArray;
                } else {
                    $this->temp_array['product'. $productId]['discount'][] = $discountArray;
                }
                unset($data[$productId]);
            }
        }
        return $this->temp_array;
    }

    private function getDiscountArray(&$product)
    {
        $productDiscountEntity = new ProductDiscountEntity();
        $discountsArray = [];
        $properTies = array_keys($productDiscountEntity->getAllProperties());
        foreach ($product as $key => $value) {
            if (in_array($key, $properTies)) {
                $discountsArray[$key] = $value;
                unset($product[$key]);
            }
        }
        return $discountsArray;
    }
}
