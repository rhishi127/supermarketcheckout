<?php

namespace Domain\Query\Discount;

use Domain\Common\DefaultMapper;
use Domain\Entity\ResultEntity;

class ProductDiscountQueryMapper extends DefaultMapper
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getProductQtyDiscountByProductIdAndDiscountType(int $productId, string $discountType)
    {
        $resultEntity = new ResultEntity();
        try {
            $resultEntity->setIsSuccess(false);
            $sql = "SELECT  pd.product_id,
                            pd.product_discount_id,
                            pd.max_qty,
                            pd.min_qty,
                            pd.discount_amt
                    FROM    product_discount pd  
                    LEFT JOIN discount_category dc ON dc.discount_category_id = pd.discount_type_id
                    WHERE   pd.product_id = :id 
                        AND dc.discount_type_alias = :discount_type_alias
                    ";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $productId);
            $stmt->bindParam(":discount_type_alias", $discountType);
            if ($stmt->execute()) {
                $resultEntity->setIsSuccess(true);
                $stmt->setFetchMode($this->conn::FETCH_ASSOC);
                $results = $stmt->fetchAll();
                $resultEntity->setResult($results);
            }
        } catch (\Exception $ex) {
            $resultEntity->setIsSuccess(false);
            return $resultEntity;
        }
        return $resultEntity;
    }

    public function getProductDiscountOffersByProductIdArray(array $productIdArray)
    {
        $resultEntity = new ResultEntity();
        $count = count($productIdArray);
        $placeholders = implode(',', array_fill(0, $count, '?'));
        $resultEntity->setIsSuccess(false);
        try {
            $sql = "SELECT    pd.product_id,
                              pd.product_discount_id,
                              pd.max_qty,
                              pd.min_qty,
                              pd.discount_amt,
                              pd.is_condition,
                              dc.dependant_product_id,
                              dc.dependant_max_qty,
                              dc.dependant_min_qty,
                              dc.dependant_discount_amount,
                              dc.discount_condition_id,
                                dct.discount_category_id,
                                dct.discount_type_name,
                                dct.discount_type_alias
                    FROM      product_discount pd
                    LEFT JOIN discount_condition AS dc
                        ON dc.product_discount_id = pd.product_discount_id
                    LEFT JOIN discount_category dct 
                        ON dct.discount_category_id = pd.discount_type_id
                    WHERE     pd.product_id IN ($placeholders)
                    ORDER BY  pd.is_condition DESC, pd.product_id ASC";
            $stmt = $this->conn->prepare($sql);
            if ($stmt->execute($productIdArray)) {
                $resultEntity->setIsSuccess(true);
                $stmt->setFetchMode($this->conn::FETCH_ASSOC);
                $results = $stmt->fetchAll();
                $resultEntity->setResult($results);
            }
        } catch (\Exception $ex) {
            $resultEntity->setIsSuccess(false);
            return $resultEntity;
        }
        return $resultEntity;
    }
}
