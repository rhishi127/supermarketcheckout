<?php

namespace Cart\Service;

use Core\Discount\Service\IDiscountService;
use Core\Product\Service\IProductService;

if (!session_id()) {
    session_start();
}

class CartService
{
    protected $cart_contents = [];

    protected $product_service;

    protected $discount_service;

    public function __construct(
        IProductService  $productService,
        IDiscountService $discountService
    ) {
        $this->product_service = $productService;
        $this->discount_service = $discountService;
        $this->cart_contents = !empty($_SESSION['cart']) ? $_SESSION['cart'] : null;
        if ($this->cart_contents === null) {
            $this->cart_contents = [
                'items' => [],
                'cart_total' => 0,
                'total_items' => 0,
                'total_product' => 0,
                'product_keys' => [],
                'product_ids' => []
            ];
        }
    }

    public function contents()
    {
        return $this->cart_contents['items'];
        /*$cart = array_reverse($this->cart_contents);
        unset($cart['total_items']);
        unset($cart['cart_total']);
        unset($cart['total_product']);
        unset($cart['product_keys']);
        unset($cart['product_ids']);*/
    }

    public function getItem($rowId)
    {
        return (in_array($rowId, ['total_items', 'cart_total'], true) or !isset($this->cart_contents[$rowId]))
            ? false
            : $this->cart_contents[$rowId];
    }

    public function getTotalItems()
    {
        return $this->cart_contents['total_items'];
    }

    public function getTotal()
    {
        return $this->cart_contents['cart_total'];
    }

    public function add($itemData)
    {
        $result = $this->product_service->getProductByProductId($itemData['id']);
        if ($result->getIsSuccess() == false) {
            return false;
        }
        $result = $result->getResult();
        $item = $result[0]->toArray();
        $productKey = md5($item['product_id']);
        // get quantity if it's already there and add it on
        $old_qty = isset($this->cart_contents['items'][$productKey]['qty']) ? (int)$this->cart_contents['items'][$productKey]['qty'] : 0;
        // re-create the entry with unique identifier and updated quantity
        $item['product_key'] = $productKey;
        $item['qty'] = $itemData['qty'] + $old_qty;
        $this->cart_contents['items'][$productKey] = $item;
        if ($this->saveCart()) {
            return true;
        } else {
            return false;
        }
    }

    public function update($item = [])
    {
        if (!is_array($item) or count($item) === 0) {
            return false;
        } else {
            if (!isset($item['product_key'], $this->cart_contents['items'][$item['product_key']])) {
                return false;
            } else {
                $productKey = $item['product_key'];
                if (isset($item['qty'])) {
                    $item['qty'] = (float)$item['qty'];
                    // remove the item from the cart, if quantity is zero
                    if ($item['qty'] == 0) {
                        $this->cart_contents['product_keys'] = [];
                        $this->cart_contents['product_ids'] = [];
                        return true;
                    }
                }
                $keys = array_intersect(array_keys($this->cart_contents['items'][$item['product_key']]), array_keys($item));
                if (isset($item['product_price'])) {
                    $item['product_price'] = (float)$item['product_price'];
                }
                foreach (array_diff($keys, ['product_id', 'product_name']) as $key) {
                    $this->cart_contents['items'][$item['product_key']][$key] = $item[$key];
                }
                $this->saveCart();
                return true;
            }
        }
    }

    protected function saveCart()
    {
        $this->cart_contents['total_product'] = $this->cart_contents['total_items'] = $this->cart_contents['cart_total'] = 0;
        $this->cart_contents['product_keys'] = $this->cart_contents['product_ids'] = [];
        foreach ($this->cart_contents['items'] as $key => $val) {
            if (!is_array($val) or !isset($val['product_price'], $val['qty'])) {
                continue;
            }
            if (preg_match('/^[a-f0-9]{32}$/', $key)
                && (!in_array($key, $this->cart_contents['product_keys']))) {
                $this->cart_contents['total_product'] += 1;
                $this->cart_contents['product_keys'][] = $key;
                $this->cart_contents['product_ids'][] = $val['product_id'];
            }

            $this->cart_contents['cart_total'] += ($val['product_price'] * $val['qty']);
            $this->cart_contents['total_items'] += $val['qty'];
            $this->cart_contents['items'][$key]['sub_total'] = (
                $this->cart_contents['items'][$key]['product_price'] * $this->cart_contents['items'][$key]['qty']
            );
        }
           $this->cart_contents = $this->discount_service->decorateWithDiscountValues(
               $this->cart_contents
           );
        if (count($this->cart_contents) <= 2) {
            unset($_SESSION['cart']);
            return false;
        } else {
            $_SESSION['cart'] = $this->cart_contents;
            return true;
        }
    }

    public function remove($productKey)
    {
        if (($key = array_search($productKey, $this->cart_contents['product_keys'])) !== false) {
            unset($this->cart_contents['product_keys'][$key]);
            $productId = $this->cart_contents['items'][$productKey]['product_id'];
            unset($this->cart_contents['product_ids'][$productId]);
        }
        unset($this->cart_contents['items'][$productKey]);
        $this->saveCart();
        return true;
    }

    public function destroy()
    {
        $this->cart_contents = ['cart_total' => 0, 'total_items' => 0];
        unset($_SESSION['cart']);
    }
}
