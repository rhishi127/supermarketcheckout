<?php

namespace Domain\Query\Product;

use Custom\Utility\Hydrate\GenericEntityHydrator;
use Domain\Common\DefaultMapper;
use Domain\Entity\Product\ProductEntity;
use Domain\Entity\ResultEntity;
use Domain\Helper\ProductDataFormatHelper;

class ProductQueryMapper extends DefaultMapper
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllProducts()
    {
        $resultEntity = new ResultEntity();
        try {
            $resultEntity->setIsSuccess(false);
            $sql = "select  ap.product_id,
                            ap.product_name, 
                            ap.product_price,
                            ap.product_sku, 
                            pd.product_discount_id,
                            pd.max_qty,
                            pd.min_qty,
                            pd.discount_amt,
                            dc.discount_category_id,
                            dc.discount_type_name,
                            dc.discount_type_alias
                    from    all_products ap
                    LEFT JOIN product_discount pd On ap.product_id = pd.product_id 
                    LEFT JOIN discount_category dc ON dc.discount_category_id = pd.discount_type_id  
                    ORDER by ap.product_id";
            $rowPrototype = new ProductEntity();
            $stmt = $this->conn->prepare($sql);
            if ($stmt->execute()) {
                $resultEntity->setIsSuccess(true);
                $stmt->setFetchMode($this->conn::FETCH_ASSOC);
                $results = $stmt->fetchAll();
                $hydrator = new GenericEntityHydrator();
                $resultItem = [];
                $productDataFormatHelper = new ProductDataFormatHelper();
                $results = $productDataFormatHelper->formatData($results);
                foreach ($results as $result) {
                    $resultItem[] = $hydrator->hydrate($result, $rowPrototype);
                }
                $resultEntity->setResult($resultItem);
            }
        } catch (\Exception $ex) {
            $resultEntity->setIsSuccess(false);
            return $resultEntity;
        }
        return $resultEntity;
    }

    public function getProductByProductId(int $productId)
    {
        $resultEntity = new ResultEntity();
        try {
            $resultEntity->setIsSuccess(false);
            $sql = "select  ap.product_id,
                            ap.product_name, 
                            ap.product_price,
                            ap.product_sku 
                    from    all_products ap  
                    WHERE   ap.product_id = :id
                    ";
            $stmt =$this->conn->prepare($sql);
            $stmt->bindParam(":id", $productId);
            $stmt->execute();
            $rowPrototype = new ProductEntity();
            if ($stmt->execute()) {
                $resultEntity->setIsSuccess(true);
                $stmt->setFetchMode($this->conn::FETCH_ASSOC);
                $results = $stmt->fetch();
                $hydrator = new GenericEntityHydrator();
                $resultItem[] = $hydrator->hydrate($results, $rowPrototype);
                $resultEntity->setResult($resultItem);
            }
        } catch (\Exception $ex) {
            $resultEntity->setIsSuccess(false);
            return $resultEntity;
        }
        return $resultEntity;
    }
}
