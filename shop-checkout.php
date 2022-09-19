<?php
include 'apidata/dataFetch.php';
include 'inc/header.php';
include 'inc/apiendpoint.php';
// user profile data 


$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL =>  APIENDPOINT . "user.php?phoneNumber=" . getPhone(),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "Authorization:" . APIKEY,
        "cache-control: no-cache"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $result = json_decode($response);
    $profileData = $result->data->user[0];
}


// area 
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => APIENDPOINT . "area.php",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "Authorization: " . APIKEY,
        "cache-control: no-cache"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $result = json_decode($response);
    $areaData = $result->data->area;
}
// foreach ($areaData as  $area) {
//     echo $area->id;
// }
?>
<main class="main">
    <?php
    $cartData = json_decode($_COOKIE['shopping_cart']);
    $totalCartItem = count($cartData);
    if ($totalCartItem == 0) {
    ?>
        <div class="col-md-12 col-sm-6 text-center p-5">
            <img style="width: 25%;" class="img-fluid mx-auto" src="./assets/imgs/empty-cart.png" alt="">
            <h2 class="text-center">No product on your cart for confirm your order.</h2>
            <p class="text-center">Before proceed to output_add_rewrite_var you must add some products to your shopping cart.
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
                    <span></span> Checkout
                </div>
            </div>
        </div>
        <div class="container mb-80 mt-50">
            <div class="row">
                <div class="col-lg-8 mb-40">
                    <h1 class="heading-2 mb-10">Checkout</h1>
                    <div class="d-flex justify-content-between">
                        <h6 class="text-body">There are <span class="text-brand"><?php echo $totalCartItem; ?></span>
                            products in your cart</h6>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-7">
                    <div class="row mb-50">
                    </div>
                    <div class="row">
                        <h4 class="mb-30">Delivery Details</h4>
                        <!-- <form method="post"> -->
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <input type="text" required="" name="name" value="<?php echo $profileData->fullName; ?>" id="name" placeholder="Full Name *">
                            </div>
                            <div class="form-group col-lg-6">
                                <input type="text" name="address" value="<?php echo $profileData->address; ?>" id="address" required="" placeholder="Delivery Address *">
                            </div>
                        </div>
                        <div class="row shipping_calculator">
                            <div class="form-group col-lg-6">
                                <div class="custom_select">
                                    <select class="form-control select-active" onchange="getDeliveryCharge(this.value)" name="area" id="area">
                                        <option value="">Select an option...</option>
                                        <?php
                                        foreach ($areaData as $area) {
                                        ?>
                                            <option value="<?php echo $area->id; ?>"><?php echo $area->deliveryArea; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <input required="" type="text" name="town" id="town" placeholder="City / Town *">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-lg-6">
                                <input required="" type="text" value="<?php echo $profileData->phone; ?>" name="phone" id="phone" placeholder="Phone *">
                            </div>
                            <div class="form-group col-lg-6">
                                <input required="" type="text" name="additionalPhone" id="additionalPhone" placeholder="Additional Phone">
                            </div>
                        </div>
                        <div class="form-group mb-30">
                            <textarea rows="5" id="additionalInfo" placeholder="Additional information"></textarea>
                        </div>
                        <!-- </form> -->
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="border p-40 cart-totals ml-30 mb-50">
                        <div class="d-flex align-items-end justify-content-between mb-30">
                            <h4>Your Order</h4>
                        </div>
                        <div class="divider-2 mb-30"></div>
                        <div class="table-responsive order_table checkout">
                            <table class="table no-border">
                                <tbody>
                                    <?php
                                    $totalSum = 0;
                                    foreach ($cartData as $key => $product) {
                                    ?>
                                        <tr>
                                            <td class="image product-thumbnail"><img src="<?php echo $product->productImage; ?>" alt="#"></td>
                                            <td>
                                                <h6 class="w-160 mb-5"><a href="shop-product-full.php" class="text-heading"><?php echo $product->productName; ?></a></h6>
                                                </span>
                                            </td>
                                            <td>
                                                <h6 class="text-muted pl-20 pr-20">x <?php echo $product->productQuantity; ?>
                                                </h6>
                                            </td>
                                            <td>
                                                <h4 class="text-brand">
                                                    ৳<?php echo $product->productQuantity * $product->productprice; ?></h4>
                                            </td>
                                        </tr>
                                    <?php
                                        $totalSum = $totalSum + $product->productQuantity * $product->productprice;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12">
                            <p class="text-muted pt-2 pb-2 h5" style="text-align: right;">Delivery Charge: ৳ <span class="text-brand" id="deliveryCharge"> 0.00</span></p>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mb-30 float-end">
                            <input type="hidden" id="totalValue" value="<?php echo $totalSum; ?>">
                            <h4 class="text-muted">Subtotal: ৳ <span class="text-brand" id="totalAmount"><?php echo $totalSum; ?></span></h4>
                        </div>
                    </div>
                    <div class="payment ml-30">
                        <h4 class="mb-30">Payment</h4>
                        <div class="payment_option">
                            <div class="custome-radio">
                                <input class="form-check-input" required="" type="radio" name="payment_option" value="Cash on delivery" id="exampleRadios4" checked="">
                                <label class="form-check-label" for="exampleRadios4" data-bs-toggle="collapse" data-target="#checkPayment" aria-controls="checkPayment">Cash on delivery</label>
                            </div>
                        </div>
                        <div class="payment-logo d-flex">
                            <img class="mr-15" src="assets/imgs/theme/icons/payment-paypal.svg" alt="">
                            <img class="mr-15" src="assets/imgs/theme/icons/payment-visa.svg" alt="">
                            <img class="mr-15" src="assets/imgs/theme/icons/payment-master.svg" alt="">
                            <img src="assets/imgs/theme/icons/payment-zapper.svg" alt="">
                        </div>
                        <?php
                        if (getPhone() != '') {
                        ?>
                            <a class="btn btn-fill-out btn-block mt-30" style="<?php if ($totalCartItem == 0) {
                                                                                    echo "pointer-events: none;";
                                                                                } ?>" onclick="placeorder('<?php echo getPhone(); ?>', '<?php echo getToken(); ?>')">Place
                                an Order<i class="fi-rs-sign-out ml-15"></i></a>
                        <?php
                        } else {
                        ?>
                            <a aria-label="Quick view" onclick="loginUserFororder()" class="btn btn-fill-out btn-block mt-30" data-bs-toggle="modal" data-bs-target="#userlogin">Complete Order</i><i class="fi-rs-sign-out ml-15"></i></a>
                        <?php
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</main>

<?php
include 'inc/footer.php';
?>