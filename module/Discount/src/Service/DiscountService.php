<?php

namespace Discount\Service;

use Core\Discount\Service\IDiscountService;
use Core\Domain\Repository\Discount\IProductDiscountRepository;
use Discount\Data\Enum\DiscountTypeEnum;

class DiscountService implements IDiscountService
{
    /**
     * @var IProductDiscountRepository
     */
    protected $product_discount_repository;

    public function __construct(IProductDiscountRepository $productDiscountRepository)
    {
        $this->product_discount_repository = $productDiscountRepository;
    }

    public function decorateWithDiscountValues(array $cartArray)
    {
        $total = 0;
        $productCount = count($cartArray['product_keys']);
        if ($productCount == 1) {
            $productId = $cartArray['product_ids'][0];
            $productKey = $cartArray['product_keys'][0];
            $productArray = $cartArray['items'][$productKey];
            $discountAmount = $cartArray['items'][$productKey]['sub_total'];
            $discountOffersEntity = $this->getProductQtyDiscountByProductId(
                $productId
            );
            $cartArray['items'][$productKey]['sub_total'] = $discountAmount;
            if ($discountOffersEntity->getIsSuccess() && (! empty($discountOffersEntity->getResult()))) {
                $discountOffers = $discountOffersEntity->getResult();
                $discountAmount = $this->calculateSingleQtyDiscount(
                    $productArray['qty'],
                    $discountOffers,
                    $productArray['product_price']
                );
                $cartArray['items'][$productKey]['sub_total'] = $discountAmount;
            }
        } else {
            $cartItemsArray = $this->calculateQtyAndConditionDiscount($cartArray['items']);
            $cartArray['items'] = $cartItemsArray;
        }
        foreach ($cartArray['items'] as $item) {
            $total += $item['sub_total'];
        }
        $cartArray['cart_total'] = $total;
        return $cartArray;
    }

    private function calculateConditionalDiscount($productsArray, $offer)
    {
        $productKey = md5($offer['product_id']);
        $discountAmount = $productsArray[$productKey]['qty'] * $productsArray[$productKey]['product_price'];
        $dependantProductKey = md5($offer['dependant_product_id']);
        if (array_key_exists($dependantProductKey, $productsArray)) {
            $discountAmount = $offer['discount_amt'] * $productsArray[$productKey]['qty'];
        }
        return $discountAmount;
    }

    private function calculateQtyAndConditionDiscount($cartItemArray)
    {
        foreach ($cartItemArray as $cartItem) {
            $productId = $cartItem['product_id'];
            $discountOffersEntity = $this->getProductDiscountOffersByProductIdArray([$productId]);
            if ($discountOffersEntity->getIsSuccess() && (!empty($discountOffersEntity->getResult()))) {
                $offers = $discountOffersEntity->getResult();
                foreach ($offers as $key => $offer) {
                    $productKey = md5($offer['product_id']);
                    $discountAmount = $cartItemArray[$productKey]['qty'] * $cartItemArray[$productKey]['product_price'];
                    if ($offer['discount_type_alias'] == DiscountTypeEnum::CONDITION_DISCOUNT) {
                        $discountAmount = $this->calculateConditionalDiscount($cartItemArray, $offer);
                        unset($offers[$key]);
                        $offers = array_values($offers);
                    } else {
                        $discountAmount = $this->calculateSingleQtyDiscount(
                            $cartItemArray[$productKey]['qty'],
                            $offers,
                            $cartItemArray[$productKey]['product_price']
                        );
                    }
                    $cartItemArray[$productKey]['sub_total'] = $discountAmount;
                }
            }
        }

        return $cartItemArray;
    }

    private function calculateSingleQtyDiscount(int $quantity, array $offers, $productPrice)
    {
        $quantitiesArray = array_column($offers, 'min_qty');
        $discountAmount = 0;
        if (is_numeric($key = array_search($quantity, $quantitiesArray))) {
            return $offers[$key]['discount_amt'];
        } else {
            foreach ($offers as $offer) {
                if ($quantity < $offer['min_qty']) {
                    return $quantity * $productPrice;
                }
                if ($quantity > $offer['min_qty']) {
                    $closetQuantity = $this->findClosest($quantitiesArray, count($quantitiesArray), $quantity);
                    $offerKey = array_search($closetQuantity, $quantitiesArray);
                    $discountOffer = $offers[$offerKey];
                    $discountOfferQuantity = $discountOffer['min_qty'];
                    $discountOfferAmount = $discountOffer['discount_amt'];
                    $remainingQty = $quantity - $discountOfferQuantity;
                    $discountAmount = $discountOfferAmount + $this->calculateSingleQtyDiscount(
                        $remainingQty,
                        $offers,
                        $productPrice
                    );
                    return $discountAmount;
                }
            }
        }
        return $discountAmount;
    }

    private function getProductQtyDiscountByProductId($productId)
    {
        return $this->product_discount_repository->
        getProductQtyDiscountByProductIdAndDiscountType(
            $productId,
            DiscountTypeEnum::QUANTITY_DISCOUNT
        );
    }

    private function getProductDiscountOffersByProductIdArray($productIdArray)
    {
        return $this->product_discount_repository
            ->getProductDiscountOffersByProductIdArray($productIdArray);
    }


    private function findClosest($arr, $n, $target)
    {
        if ($target <= $arr[0]) {
            return $arr[0];
        }
        if ($target >= $arr[$n - 1]) {
            return $arr[$n - 1];
        }
        $i = 0;
        $j = $n;
        $mid = 0;
        while ($i < $j) {
            $mid = ($i + $j) / 2;
            if ($arr[$mid] == $target) {
                return $arr[$mid];
            }
            if ($target < $arr[$mid]) {
                if ($mid > 0 && $target > $arr[$mid - 1]) {
                    return $this->getClosest($arr[$mid - 1], $arr[$mid], $target);
                }
                $j = $mid;
            } else {
                if ($mid < $n - 1 &&
                    $target < $arr[$mid + 1]) {
                    return $this->getClosest($arr[$mid], $arr[$mid + 1], $target);
                }
                $i = $mid + 1;
            }
        }
        return $arr[$mid];
    }

    function getClosest($val1, $val2, $target)
    {
        if ($val1 <= $target && $val2 > $target) {
            return $val1;
        } else {
            return $val2;
        }
    }
}
