<?php

namespace ProdductTest\Service;

use Domain\Entity\Product\ProductEntity;
use Domain\Entity\ResultEntity;
use PHPUnit\Framework\TestCase;
use Product\Service\ProductService;
use \Mockery as Mockery;

class ProductServiceTest extends TestCase
{
    /**
     * @var ProductService
     */
    private $product_service;

    private $product_repository;

    public function setUp()
    {
        parent::setUp();
        $this->product_repository = Mockery::mock('Domain\Repository\Product\ProductRepository');
    }

    /**
     * @dataProvider getAllProductsDataset
     */
    public function testGetAllProducts($mockResponse): void
    {
        $this->product_repository
            ->shouldReceive('getAllProducts')
            ->once()
            ->andReturn($mockResponse)
            ->getMock();
        $this->product_service = new ProductService($this->product_repository);
        $actualResult = $this->product_service->getAllProducts();
        $this->assertInstanceOf(ResultEntity::class, $actualResult);
        if ($actualResult->getIsSuccess()) {
            $this->assertNotEmpty($actualResult->getResult());
        } else {
            $this->assertEmpty($actualResult->getResult());
        }
    }
    
    public function getAllProductsDataset(): array
    {
        $resultEntity = new ResultEntity();
        $resultEntity->setIsSuccess(true);
        $productEntity1 = new ProductEntity();
        $productEntity1->setProductId(1);
        $productEntity1->setProductName('A');
        $productEntity1->setProductPrice(2.5);
        $products = [
            [
                $productEntity1
            ],
        ];
        $resultEntity->setResult($products);

        $resultEntity1 = new ResultEntity();
        $resultEntity1->setIsSuccess(false);
        $data = [
            [$resultEntity],
            [$resultEntity1]
        ];
        return $data;
    }

    /**
     * @dataProvider getProductDataset
     */
    public function testGetProduct($mockResponse, $id): void
    {
        $this->product_repository
            ->shouldReceive('getProductByProductId')
            ->once()
            ->with($id)
            ->andReturn($mockResponse)
            ->getMock();
        $this->product_service = new PrgetProductQtyDiscountByProductIdoductService($this->product_repository);
        $actualResult = $this->product_service->getProductByProductId($id);
        $this->assertInstanceOf(ResultEntity::class, $actualResult);
        if ($actualResult->getIsSuccess()) {
            $this->assertNotEmpty($actualResult->getResult());
            $products = $actualResult->getResult();
            $product = ($products[0][0]);

            $this->assertEquals($id, $product->getProductId());
        } else {
            $this->assertEmpty($actualResult->getResult());
        }
    }

    public function getProductDataset(): array
    {
        $resultEntity = new ResultEntity();
        $resultEntity->setIsSuccess(true);
        $productEntity1 = new ProductEntity();
        $productEntity1->setProductId(1);
        $productEntity1->setProductName('A');
        $productEntity1->setProductPrice(2.5);
        $products = [
            [
                $productEntity1
            ],
        ];
        $resultEntity->setResult($products);

        $resultEntity1 = new ResultEntity();
        $resultEntity1->setIsSuccess(false);
        $data = [
            [$resultEntity, 1],
            [$resultEntity1, 5]
        ];
        return $data;
    }
}
