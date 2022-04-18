<?php

namespace Domain\Entity\Product;

use Custom\Domain\Entity\DefaultEntity;
use Discount\Data\Enum\DiscountTypeEnum;
use Domain\Entity\Discount\ProductDiscountEntity;

class ProductEntity extends DefaultEntity
{
    /**
     * @var int|null
     */
    protected $product_id;

    /**
     * @var string|null
     */
    protected $product_name;

    /**
     * @var float|null
     */
    protected $product_price;

    /**
     * @var int|null
     */
    protected $product_sku;

    /**
     * @var array|null
     */
    protected $discount_offers;

    /**
     * @return int|null
     */

    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    /**
     * @param int|null $productId
     */
    public function setProductId(?int $productId): void
    {
        $this->product_id = $productId;
    }

    /**
     * @return string|null
     */
    public function getProductName(): ?string
    {
        return $this->product_name;
    }

    /**
     * @param string|null $productName
     */
    public function setProductName(?string $productName): void
    {
        $this->product_name = $productName;
    }

    /**
     * @return float|null
     */
    public function getProductPrice(): ?float
    {
        return $this->product_price;
    }

    /**
     * @param float|null $productPrice
     */
    public function setProductPrice(?float $productPrice): void
    {
        $this->product_price = $productPrice;
    }

    /**
     * @return int|null
     */
    public function getProductSku(): ?int
    {
        return $this->product_sku;
    }

    /**
     * @param int|null $productSku
     */
    public function setProductSku(?int $productSku): void
    {
        $this->product_sku = $productSku;
    }

    /**
     * @return array|null
     */
    public function getDiscountOffers(): ?array
    {
        return $this->discount_offers;
    }

    /**
     * @param array|null $discountOffers
     */
    public function setDiscountOffers(?array $discountOffers): void
    {
        $this->discount_offers = $discountOffers;
    }

    public function exchangeArray($data)
    {
        $this->product_id = isset($data['product_id']) ? $data['product_id'] : null;
        $this->product_name = isset($data['product_name']) ? $data['product_name'] : null;
        $this->product_price = isset($data['product_price']) ? $data['product_price'] : null;
        $this->product_sku = isset($data['product_sku']) ? $data['product_sku'] : null;
        $discountArray = [];
        if (!empty($data['discount'])) {
            foreach ($data['discount'] as $discountOffer) {
                $productDiscountEntity = new ProductDiscountEntity();
                $productDiscountEntity->exchangeArray($discountOffer);
                $discountArray[] = $productDiscountEntity;
            }
            $this->discount_offers = $discountArray;
        }
    }

    public function getDiscountOfferString()
    {
        $discountOffers = $this->discount_offers;
        foreach ($discountOffers as $discountOffer) {
            if (!empty($discountOffer->getProductDiscountId())) {
                if ($discountOffer->getDiscountTypeAlias() == DiscountTypeEnum::QUANTITY_DISCOUNT) {
                    $actualAmount = $this->product_price * $discountOffer->getMinQty();
                    return 'Offer Buy '
                        . $discountOffer->getMinQty() . ' <del> &#163; ' . $actualAmount
                        . '</del> for &#163; ' . $discountOffer->getDiscountAmt();
                }
            }
        }
        return '';
    }
}
