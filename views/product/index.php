<?php

use Product\Service\Factory\ProductServiceFactory;
use Cart\Service\Factory\CartServiceFactory;

$productServiceFactory = new ProductServiceFactory();
$productService = new $productServiceFactory();
$resultEntity = $productService()->getAllProducts();
$cartServiceFactory = new CartServiceFactory();
$cartService = new $cartServiceFactory();
$GLOBALS['product_service'] = $cartService();
?>

<div class='container mt-5 pb-5'>

    <h2 class='text-muted mb-4 text-center'>Products</h2>
    <div class="cart-view">
        <a href="view-cart.php" title="View Cart"><i class="icart"></i> (<?= ($cartService()->getTotalItems() > 0) ?
                $cartService()->getTotalItems() .' Items':0; ?>)</a>
    </div>

    <?php
    if ((!$resultEntity->getIsSuccess()) || empty($resultEntity->getResult())) {
        ?>
        <div class='row'>
            <div class="col-lg-12">
                <h4 class='text-muted  text-center'>No Products Available</h4>
            </div>
        </div>
        <?php
    }
    if (($resultEntity->getIsSuccess()) && (!empty($resultEntity->getResult()))) {
    ?>
    <div class='row'>
        <?php
        foreach ($resultEntity->getResult() as $product):
            ?>
            <div class='col-md-3 mt-2'>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= $product->getProductName();?></h5>
                        <p class="card-text">
                            Price &#163; <?=$product->getProductPrice();?>
                        </p>
                        <p class="card-text">
                            <?=$product->getDiscountOfferString();?>
                        </p>
                        <a href="views/cart/cart-action.php?action=add&id=<?= $product->getProductId();?>" class="btn btn-primary">Add to Cart</a>
                    </div>
                </div>
            </div>
        <?php endforeach;
        }
        ?>
    </div>


</div>
</body>
    <script type="text/javascript">
        $( document ).ready(function() {
            $(".addCart").click(function (){
                var arr = this.id.split('-');
                var productId = arr[1]
                var qty = $('#qty-'+productId).val()
                $.ajax({
                    type:"POST",
                    url:"views/cart/add-to-cart.php",
                    data: {product_id:productId, qty:qty},
                    success:function(data){
                        console.log(data);
                    }
                });

            })
        });

    </script>
