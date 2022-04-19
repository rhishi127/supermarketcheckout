<?php

namespace DiscountTest\Service;

use Discount\Data\Enum\DiscountTypeEnum;
use Domain\Entity\ResultEntity;
use Discount\Service\DiscountService;
use PHPUnit\Framework\TestCase;
use \Mockery as Mockery;

class DiscountServiceTest extends TestCase
{
    /**
     * @var DiscountService
     */
    private $discount_service;

    private $discount_repository;

    public function setUp()
    {
        parent::setUp();
        $this->discount_repository = Mockery::mock('Domain\Repository\Discount\ProductDiscountRepository');
    }

    /**
     * @dataProvider getCartDataset
     */
    public function testDecorateWithDiscountValues(array $cartArray, $offers, $expectedAmount, $productKey, $productId)
    {
        $productArray = [$productId];
        $this->discount_repository->shouldReceive('getProductQtyDiscountByProductIdAndDiscountType')
            ->once()
            ->with(1, DiscountTypeEnum::QUANTITY_DISCOUNT)
            ->andReturn($offers)
            ->getMock();

        $this->discount_repository->shouldReceive('getProductDiscountOffersByProductIdArray')
            ->once()
            ->with($productArray)
            ->andReturn($offers)
            ->getMock();
        $this->discount_service = new DiscountService($this->discount_repository);
        $result = $this->discount_service->decorateWithDiscountValues($cartArray);
        $this->assertEquals($expectedAmount, $result['items'][$productKey]['sub_total']);
    }

    public function getCartDataset(): array
    {
        $cart = [
            'items' => [
                    'c4ca4238a0b923820dcc509a6f75849b' => [
                    'product_id' => 1,
                    'product_name' => 'A',
                    'product_price' => 50,
                    'product_sku' => 50,
                    'discount_offers' => NULL,
                    'product_key' => 'c4ca4238a0b923820dcc509a6f75849b',
                    'qty' => 3,
                    'sub_total' => 50
                ]
            ],
            'cart_total' => 50,
            'total_items' => 1,
            'total_product' => 1,
            'product_keys' => ['c4ca4238a0b923820dcc509a6f75849b'],
            'product_ids' => [1]
        ];
        $offerResult = new ResultEntity();
        $offerResult->setIsSuccess(true);
        $discountOffers  = [
            [
                'product_id' => 1,
                'product_discount_id' => 1,
                'max_qty' => 3,
                'min_qty' => 3,
                'discount_amt' => 130,
            ],
            [
                'product_id' => 1,
                'product_discount_id' => 6,
                'max_qty' => 8,
                'min_qty' => 8,
                'discount_amt' => 300,
            ]
        ];
        $expectedAmount = 130;
        $productKey = md5(1);
        $offerResult->setResult($discountOffers);
        $productId = 1;
        $data[] = [$cart, $offerResult, $expectedAmount, $productKey, $productId];

        $cart = [
            'items' => [
                'c4ca4238a0b923820dcc509a6f75849b' => [
                    'product_id' => 1,
                    'product_name' => 'A',
                    'product_price' => 50,
                    'product_sku' => 50,
                    'discount_offers' => NULL,
                    'product_key' => 'c4ca4238a0b923820dcc509a6f75849b',
                    'qty' => 11,
                    'sub_total' => 50
                ]
            ],
            'cart_total' => 50,
            'total_items' => 1,
            'total_product' => 1,
            'product_keys' => ['c4ca4238a0b923820dcc509a6f75849b'],
            'product_ids' => [1]
        ];
        $expectedAmount = 430;
        $data[] = [$cart, $offerResult, $expectedAmount, $productKey, $productId];
        return $data;
    }
}
