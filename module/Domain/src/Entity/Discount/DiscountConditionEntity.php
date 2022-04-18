<?php

namespace Domain\Entity\Discount;

use Custom\Domain\Entity\DefaultEntity;

class DiscountConditionEntity extends DefaultEntity
{
    protected $discount_condition_id;

    protected $product_discount_id;

    protected $dependant_product_id;

    protected $dependant_min_qty;

    /**
     * @return mixed
     */
    public function getDiscountConditionId()
    {
        return $this->discount_condition_id;
    }

    /**
     * @param mixed $discount_condition_id
     */
    public function setDiscountConditionId($discount_condition_id): void
    {
        $this->discount_condition_id = $discount_condition_id;
    }

    /**
     * @return mixed
     */
    public function getProductDiscountId()
    {
        return $this->product_discount_id;
    }

    /**
     * @param mixed $product_discount_id
     */
    public function setProductDiscountId($product_discount_id): void
    {
        $this->product_discount_id = $product_discount_id;
    }

    /**
     * @return mixed
     */
    public function getDependantProductId()
    {
        return $this->dependant_product_id;
    }

    /**
     * @param mixed $dependant_product_id
     */
    public function setDependantProductId($dependant_product_id): void
    {
        $this->dependant_product_id = $dependant_product_id;
    }

    /**
     * @return mixed
     */
    public function getDependantMinQty()
    {
        return $this->dependant_min_qty;
    }

    /**
     * @param mixed $dependant_min_qty
     */
    public function setDependantMinQty($dependant_min_qty): void
    {
        $this->dependant_min_qty = $dependant_min_qty;
    }
}
