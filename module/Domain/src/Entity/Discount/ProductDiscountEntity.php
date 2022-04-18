<?php

namespace Domain\Entity\Discount;

use Custom\Domain\Entity\DefaultEntity;
use ReflectionObject;
use ReflectionProperty;

class ProductDiscountEntity extends DefaultEntity
{
    /**
     * @var int|null
     */
    protected $product_discount_id;

    /**
     * @var int|null
     */
    protected $product_id;

    /**
     * @var int|null
     */
    protected $max_qty;

    /**
     * @var int|null
     */
    protected $min_qty;

    /**
     * @var int|null
     */
    protected $is_condition;

    /**
     * @var float|null
     */
    protected $discount_amt;

    /**
     * @var string|null
     */
    protected $discount_type_name;

    /**
     * @var string|null
     */
    protected $discount_type_alias;

    /**
     * @var array
     */
    protected $discount_condition;

    public function getProductDiscountId(): ?int
    {
        return $this->product_discount_id;
    }

    /**
     * @param int|null $product_discount_id
     */
    public function setProductDiscountId(?int $product_discount_id): void
    {
        $this->product_discount_id = $product_discount_id;
    }

    /**
     * @return int|null
     */
    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    /**
     * @param int|null $product_id
     */
    public function setProductId(?int $product_id): void
    {
        $this->product_id = $product_id;
    }

    /**
     * @return int|null
     */
    public function getMaxQty(): ?int
    {
        return $this->max_qty;
    }

    /**
     * @param int|null $max_qty
     */
    public function setMaxQty(?int $max_qty): void
    {
        $this->max_qty = $max_qty;
    }

    /**
     * @return int|null
     */
    public function getMinQty(): ?int
    {
        return $this->min_qty;
    }

    /**
     * @param int|null $min_qty
     */
    public function setMinQty(?int $min_qty): void
    {
        $this->min_qty = $min_qty;
    }

    /**
     * @return int|null
     */
    public function getIsCondition(): ?int
    {
        return $this->is_condition;
    }

    /**
     * @param int|null $is_condition
     */
    public function setIsCondition(?int $is_condition): void
    {
        $this->is_condition = $is_condition;
    }

    /**
     * @return float|null
     */
    public function getDiscountAmt(): ?float
    {
        return $this->discount_amt;
    }

    /**
     * @param float|null $discount_amt
     */
    public function setDiscountAmt(?float $discount_amt): void
    {
        $this->discount_amt = $discount_amt;
    }

    /**
     * @return string|null
     */
    public function getDiscountTypeName(): ?string
    {
        return $this->discount_type_name;
    }

    /**
     * @param string|null $discount_type_name
     */
    public function setDiscountTypeName(?string $discount_type_name): void
    {
        $this->discount_type_name = $discount_type_name;
    }

    /**
     * @return string|null
     */
    public function getDiscountTypeAlias(): ?string
    {
        return $this->discount_type_alias;
    }

    /**
     * @param string|null $discount_type_alias
     */
    public function setDiscountTypeAlias(?string $discount_type_alias): void
    {
        $this->discount_type_alias = $discount_type_alias;
    }

    /**
     * @return array
     */
    public function getDiscountCondition(): array
    {
        return $this->discount_condition;
    }

    /**
     * @param array $discount_condition
     */
    public function setDiscountCondition(array $discount_condition): void
    {
        $this->discount_condition = $discount_condition;
    }

    public function getAllProperties()
    {
        $reflectionObject = new ReflectionObject($this);
        $protectedPropertyArray = $reflectionObject->
        getProperties(ReflectionProperty::IS_PROTECTED);
        $data = [];
        foreach ($protectedPropertyArray as $reflectionPropertyItem) {
            $propertyName = $reflectionPropertyItem->getName();
            $data[$propertyName] = $this->$propertyName;
        }
        return $data;
    }
}
