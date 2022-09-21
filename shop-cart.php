<?php
include 'apidata/dataFetch.php';
include 'inc/header.php';

?>
<main class="main" id="mainCartDivPage">
    <?php
    $cartData = json_decode($_COOKIE['shopping_cart']);
    $totalCartItem = count($cartData);

    if ($totalCartItem == 0) {
    ?>
        <div class="col-md-12 col-sm-6 text-center p-5">
            <img style="width: 25%;" class="img-fluid mx-auto" src="./assets/imgs/empty-cart.png" alt="">
            <h2 class="text-center">Your cart is currently empty.</h2>
            <p class="text-center">Before proceed to checkout you must add some products to your shopping cart.
                You will find a lot of interesting products on our "Shop Now" page.</p>
            <a href="index.php" class="btn btn-sm btn-default">Shop Now</a>
        </div>
    <?php
    } else {
    ?>
        <div class="page-header breadcrumb-wrap">
            <div class="container">
                <div class="breadcrumb">
                    <a href="index.php" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                    <span></span> Shop
                    <span></span> Cart
                </div>
            </div>
        </div>
        <div class="container mb-80 mt-50">
            <div class="row">
                <div class="col-lg-8 mb-40">
                    <h1 class="heading-2 mb-10">Your Cart</h1>
                    <div class="d-flex justify-content-between">
                        <h6 class="text-body">There are <span class="text-brand"><?php echo $totalCartItem; ?></span>
                            products in your cart</h6>
                        <h6 class="text-body"><a href="#" onclick="clearCart()" class="text-muted"><i class="fi-rs-trash mr-5"></i>Clear Cart</a>
                        </h6>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="shopping-summery">
                        <table class="table table-wishlist">
                            <thead>
                                <tr class="main-heading">
                                    <th scope="col" class="start pl-30" colspan="2">Product</th>
                                    <th scope="col">Unit Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Subtotal</th>
                                    <th scope="col" class="end">Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sum = 0;
                                foreach ($cartData as $key => $cartproduct) {
                                ?>
                                    <tr class="pt-30">
                                        <td class="image product-thumbnail pt-30 start pl-30"><img src="<?php echo $cartproduct->productImage; ?>" alt="#"></td>
                                        <td class="product-des product-name">
                                            <h6 class="mb-5"><a class="product-name mb-10 text-heading" href="product.php"><?php echo $cartproduct->productName; ?></a>
                                            </h6>
                                        </td>
                                        <td class="price" data-title="Price">
                                            <h4 class="text-body">৳<?php echo $cartproduct->productprice; ?> </h4>
                                        </td>
                                        <td class="text-center detail-info" data-title="Stock">
                                            <div class="detail-extralink mr-15">
                                                <div class="detail-qty border radius">
                                                    <a onclick="decrement(<?php echo $key; ?>); changeQuantity(<?php echo $key; ?>, <?php echo $cartproduct->productprice; ?>)" class="qty-down"><i class="fi-rs-angle-small-down"></i></a>
                                                    <input type="text" name="quantity" id="quantityChnage_<?php echo $key; ?>" onkeyup="changeQuantity(<?php echo $key; ?>, <?php echo $cartproduct->productprice; ?>)" class="qty-val" value="<?php echo $cartproduct->productQuantity; ?>" min="1">
                                                    <a onclick="inchrement(<?php echo $key; ?>);changeQuantity(<?php echo $key; ?>, <?php echo $cartproduct->productprice; ?>)" class="qty-up"><i class="fi-rs-angle-small-up"></i></a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="price" data-title="Total Price">
                                            <h4 class="text-brand" id="totalProductPrice_<?php echo $key; ?>">
                                                ৳<?php echo $cartproduct->productQuantity * $cartproduct->productprice; ?> </h4>
                                        </td>
                                        <td class="action text-center" data-title="Remove"><a onclick="removeItem(<?php echo $key; ?>)" class="text-body"><i class="fi-rs-trash"></i></a></td>
                                    </tr>
                                <?php
                                    $sum = $sum + $cartproduct->productQuantity * $cartproduct->productprice;
                                }

                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="divider-2 mb-30"></div>
                    <div class="cart-action d-flex justify-content-between">
                        <a class="btn  mr-10 mb-sm-15" href="index.php"><i class="fi-rs-arrow-left mr-10"></i>Continue
                            Shopping</a>
                        <a onclick="updateCart(<?php echo $totalCartItem; ?>)" class="btn  mr-10 mb-sm-15"><i class="fi-rs-refresh mr-10"></i>Update Cart</a>
                    </div>

                </div>
                <div class="col-lg-4">
                    <div class="border p-md-4 cart-totals ml-30">
                        <div class="table-responsive">
                            <table class="table no-border">
                                <tbody>
                                    <tr>
                                        <td class="cart_total_label">
                                            <h6 class="text-muted">Subtotal</h6>
                                        </td>
                                        <td class="cart_total_amount">
                                            <h4 class="text-brand text-end">৳<?php echo $sum; ?></h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="col" colspan="2">
                                            <div class="divider-2 mt-10 mb-10"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cart_total_label">
                                            <h6 class="text-muted">Shipping</h6>
                                        </td>
                                        <td class="cart_total_amount">
                                            <h5 class="text-heading text-end">Free</h4< /td>
                                    </tr>
                                    <tr>
                                        <td class="cart_total_label">
                                            <h6 class="text-muted">Estimate for</h6>
                                        </td>
                                        <td class="cart_total_amount">
                                            <h5 class="text-heading text-end">United Kingdom</h4< /td>
                                    </tr>
                                    <tr>
                                        <td scope="col" colspan="2">
                                            <div class="divider-2 mt-10 mb-10"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cart_total_label">
                                            <h6 class="text-muted">Total</h6>
                                        </td>
                                        <td class="cart_total_amount">
                                            <h4 class="text-brand text-end"><?php echo $sum; ?></h4>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <a href="shop-checkout.php" class="btn mb-20 w-100">Proceed To CheckOut<i class="fi-rs-sign-out ml-15"></i></a>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</main>
<?php include 'inc/footer.php'; ?>