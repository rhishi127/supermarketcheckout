<?php
// Include the configuration file
require_once __DIR__."/../../bootstrap.php";

use Cart\Service\Factory\CartServiceFactory;

$cartServiceFactory = new CartServiceFactory();
$cartService = new $cartServiceFactory();
$GLOBALS['product_service'] = $cartService();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>View Cart - PHP Shopping Cart</title>
<meta charset="utf-8">

<!-- Bootstrap core CSS -->
<link href="../../assets/css/bootstrap.min.css" rel="stylesheet">

<!-- Custom style -->
<link href="../../assets/css/style.css" rel="stylesheet">

<!-- jQuery library -->
<script src="../../assets/js/jquery.min.js"></script>

<script>
function updateCartItem(obj,product_key){
    $.get("cart-action.php",  {
            action:"update",
            product_key:product_key,
            qty:obj.value
        }, function(data) {
            if (data == 'ok') {
                location.reload();
            } else {
                alert('Cart update failed, please try again.');
            }
    });
}
</script>
</head>
<body>
<div class="container">
    <h1>SHOPPING CART</h1>
    <div class="cart-view">
        <a href="view-cart.php" title="View Cart"><i class="icart"></i> (<?= ($cartService()->getTotalItems() > 0) ?
                $cartService()->getTotalItems() .' Items':0; ?>)</a>
    </div>
	<div class="row">
		<div class="cart">
			<div class="col-12">
				<div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<tr>
                               <th>Product</th>
								<th >Quantity</th>
								<th>Sub Total</th>
								<th> Action</th>
							</tr>
						</thead>
						<tbody>
                        <?php

                        $cartItems = $cartService()->contents();
                        if($cartService()->getTotalItems() > 0){
                            // Get cart items from session
                            foreach($cartItems as $item) {
                        ?>
							<tr>
                                <td><?php echo $item["product_name"]; ?></td>
								<td>
                                    <input class="form-control" type="number"
                                           value="<?php echo $item["qty"]; ?>"
                                           onchange="updateCartItem(this, '<?= $item["product_key"]; ?>')"/></td>
								<td><?php echo $item["sub_total"] ?></td>
                                <td><button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to remove cart item?')
                                            ?window.location.href='cart-action.php?action=remove&product_key=<?php echo $item["product_key"]; ?>':false;" title="Remove Item"><i class="itrash"></i> </button> </td>
							</tr>
						<?php }
                        }else{ ?>
							<tr><td colspan="6"><p>Your cart is empty.....</p></td>
						<?php } ?>
						<?php if($cartService()->getTotalItems() > 0){ ?>
							<tr>
								<td colspan="3" class="text-center border-right"><strong>Cart Total</strong></td>
                                <td colspan="2" class="text-center"><strong><?= $cartService()->getTotal();?></strong></td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col mb-2">
				<div class="row text-center">
					<div class="col-sm-12  col-md-6">
						<a href="../../index.php" class="btn btn-block btn-secondary btn-lg"><i class="ialeft"></i>Continue Shopping</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
