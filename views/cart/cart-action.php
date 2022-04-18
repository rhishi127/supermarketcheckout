<?php
require_once __DIR__."/../../bootstrap.php";

use Cart\Service\Factory\CartServiceFactory;

$productServiceFactory = new CartServiceFactory();
$productService = new $productServiceFactory();
$GLOBALS['product_service'] = $productService();

$action = $_REQUEST['action'];
    switch ($action) {
        case "add":
            addToCart();
            break;
        case "update":
            updateToCart();
            break;
        case "remove":
            removeFromCart();
            break;
    }

    function addToCart()
    {
        $productKey = $_REQUEST['id'];
        $itemData =  [
            'id' => $productKey,
            'qty' => 1
        ];
        $insertItem = $GLOBALS['product_service']->add($itemData);
        $redirectURL = $insertItem?'view-cart.php':'index.php';
        header("Location: $redirectURL");
        exit();
    }

    function updateToCart()
    {
        $itemData = array(
            'product_key' => $_REQUEST['product_key'],
            'qty' => $_REQUEST['qty']
        );
        $updateItem = $GLOBALS['product_service']->update($itemData);
        echo $updateItem?'ok':'err';die;
    }

    function removeFromCart()
    {
        $deleteItem = $GLOBALS['product_service']->remove($_REQUEST['product_key']);
        // Redirect to cart page
        $redirectURL = 'view-cart.php';
        header("Location: $redirectURL");
        exit();
    }
