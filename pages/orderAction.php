<?php
include '../inc/function.php';
if ($_POST['check'] == "placeOrder") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $area = $_POST['area'];
    $phone = $_POST['phone'];
    $town = $_POST['town'];
    $additionalPhone = $_POST['additionalPhone'];
    $additionalInfo = $_POST['additionalInfo'];
    $token = $_POST['token'];
    $paymentMethod = $_POST['paymentMethod'];
    $itemInfoArr = array();
    $cartCookiesProduct = json_decode($_COOKIE['shopping_cart']);
    foreach ($cartCookiesProduct as $cartValue) {
        $itemInfoArr[] = (object)array('productID' => $cartValue->productID, 'unitPrice' => $cartValue->productprice, 'productQuantity' => $cartValue->productQuantity);
    }
    $itemInfo = $itemInfoArr;


    $post = array(  //data array from user side
        "name" => $name,
        "address" => $address,
        "area" => $area,
        "phone" => $phone,
        "town" => $town,
        "additionalPhone" => $additionalPhone,
        "additionalInfo" => $additionalInfo,
        "token" => $token,
        "paymentMethod" => $paymentMethod,
        "itemInfo" => $itemInfo

    );

    $data = json_encode($post); // json encoded
    $url = "http://192.168.0.116/neonbazar_api/order_action.php";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array( //header will be here
            'Content-Type: application/json',
            'Authorization: ' . APIKEY,
        )
    );
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch); //output will be here
    curl_close($ch);
    $response = json_decode($server_output);
    if (isset($_COOKIE['shopping_cart'])) {
        setcookie('shopping_cart', "", time() - 3600, "/");
    }
    echo $response->message;
    // print_r($response);
}

if ($_POST['check'] == "checkorderditails") {
    $orderNumber = $_POST['orderNumber'];
    // order details 

    $url = "http://192.168.0.116/neonbazar_api/order_details.php?order_id=" . $orderNumber;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array( //header will be here
            'Content-Type: application/json',
            'Authorization: ' . APIKEY,
        )
    );
    $itemInfo = curl_exec($ch);
    curl_close($ch);
    $orderDetails = json_decode($itemInfo);
?>
    <section class="content-main">
        <div class="content-header">
            <div>
                <h2 class="content-title card-title">Order detail</h2>
                <p>Details for Order ID: <?php echo $orderNumber; ?></p>
            </div>
        </div>
        <div class="card">
            <header class="card-header">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6 mb-lg-0 mb-15">
                        <small class="text-muted">Order ID: <?php echo $orderNumber; ?></small>
                    </div>
                </div>
            </header>
            <!-- card-header end// -->
            <div class="card-body">
                <div class="row mb-50 mt-20 order-info-wrap">
                    <div class="col-md-6">
                        <article class="icontext align-items-start">
                            <span class="icon icon-sm rounded-circle bg-primary-light">
                                <i class="text-primary material-icons fi-rs-user"></i>
                            </span>
                            <div class="text">
                                <h6 class="mb-1">Customer</h6>
                                <p class="mb-1">
                                    <?php echo $orderDetails->info->realname; ?> <br />
                                    <?php echo $orderDetails->info->email; ?> <br />
                                    <?php echo $orderDetails->info->phone; ?>
                                </p>
                                <!-- <a href="#">View profile</a> -->
                            </div>
                        </article>
                    </div>
                    <!-- col// -->
                    <div class="col-md-6">
                        <article class="icontext align-items-start">
                            <span class="icon icon-sm rounded-circle bg-primary-light">
                                <i class="text-primary material-icons fi-rs-marker"></i>
                            </span>
                            <div class="text">
                                <h6 class="mb-1">Delivery info</h6>
                                <p class="mb-1">
                                    Shipping: <?php echo $orderDetails->info->address; ?> <br />
                                    Pay method: Cash on delivery <br />
                                    Status:
                                    <?php
                                    if ($orderDetails->info->so_status == 0) {
                                    ?>
                                        <small class="badge rounded-pill alert-danger text-danger"> Panding</small>
                                    <?php
                                    } elseif ($orderDetails->info->so_status == 1) {
                                    ?>
                                        <small class="badge rounded-pill alert-warning text-warning"> Accept</small>
                                    <?php
                                    } else {
                                    ?>
                                        <small class="badge rounded-pill alert-success text-success"> Complete</small>
                                    <?php
                                    }
                                    ?>
                                </p>
                                <!-- <a href="#">Download info</a> -->
                            </div>
                        </article>
                    </div>
                </div>
                <!-- row // -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table" id="table_detail">
                                <thead>
                                    <tr>
                                        <th width="40%">Product</th>
                                        <th width="20%" class="text-end">Unit Price</th>
                                        <th width="20%" class="text-end">Quantity</th>
                                        <th width="20%" class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total = 0;
                                    $flag = 1;
                                    foreach ($orderDetails->item as $orderItemValue) {
                                    ?>
                                        <tr onclick="showHideRow('hidden_row<?php echo $flag; ?>', <?php echo $flag; ?>,<?php echo $orderItemValue->orderlineno; ?>,<?php echo $orderNumber; ?>)">
                                            <td>
                                                <a class="itemside" href="http://localhost/nest-frontend/shop-product-right.php?product_id=<?php echo $orderItemValue->stockid; ?>">
                                                    <div class="left">
                                                        <img src="//<?php echo $orderItemValue->img; ?>" width="40" height="40" class="img-xs" alt="Item" />
                                                    </div>
                                                    <div class="info"><?php echo $orderItemValue->description; ?></div>
                                                </a>
                                            </td>
                                            <td class="text-end">৳<?php echo $orderItemValue->unitprice; ?></td>
                                            <td class="text-end"><?php echo $orderItemValue->quantity; ?></td>
                                            <td class="text-end">৳
                                                <?php echo ($orderItemValue->unitprice * $orderItemValue->quantity); ?></td>
                                        </tr>
                                        <tr id="hidden_row<?php echo $flag; ?>" class="hidden_row">
                                            <td colspan=4 id="itemDetails<?php echo $flag; ?>">
                                            </td>
                                        </tr>
                                    <?php
                                        $total = $total + ($orderItemValue->unitprice * $orderItemValue->quantity);
                                        $flag++;
                                    }
                                    ?>

                                    <tr>
                                        <td colspan="4">
                                            <article class="float-end">
                                                <dl class="dlist">
                                                    <dt>Subtotal:</dt>
                                                    <dd>৳<?php echo $total; ?></dd>
                                                </dl>
                                                <dl class="dlist">
                                                    <dt>Shipping cost:</dt>
                                                    <dd>৳ 0.00</dd>
                                                </dl>
                                                <dl class="dlist">
                                                    <dt>Grand total:</dt>
                                                    <dd><b class="h5">৳<?php echo $total; ?></b></dd>
                                                </dl>
                                                <!-- <dl class="dlist">
                                                    <dt class="text-muted">Status:</dt>
                                                    <dd>
                                                        <span class="badge rounded-pill alert-success text-success">Payment done</span>
                                                    </dd>
                                                </dl> -->
                                            </article>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- card-body end// -->
        </div>
        <!-- card end// -->
    </section>
<?php
}
if ($_POST['check'] == "itemDetails") {
    $orderProductID = $_POST['orderProductID'];
    $orderNumber = $_POST['orderNumber'];

    $url = "http://192.168.0.116/neonbazar_api/order_item.php?item_id=" . $orderProductID . "&order_id=" . $orderNumber;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array( //header will be here
            'Content-Type: application/json',
            'Authorization: ' . APIKEY,
        )
    );
    $itemInfo = curl_exec($ch);
    curl_close($ch);
    $itemDetails = json_decode($itemInfo);

?>
    <!-- <div class="row">
    <div class="col-12 col-md-10 hh-grayBox pt45 pb20"> -->
    <div class="row pt-10 justify-content-center">
        <?php
        if ($itemDetails[0]->completed == 3) {
        ?>
            <div class="order-tracking justify-content-center">
                <p class="badge rounded-pill alert-danger text-danger">Canceled</p>

            </div>
        <?php
        } else {

        ?>
            <div class="order-tracking <?php if ($itemDetails[0]->completed >= 0) {
                                            echo "completed";
                                        } ?>">
                <span class="is-complete"></span>
                <p>Packaging</p>
                <!-- <p>Ordered<br><span>Mon, June 24</span></p> -->
            </div>
            <div class="order-tracking <?php if ($itemDetails[0]->completed >= 1) {
                                            echo "completed";
                                        } ?>">
                <span class="is-complete"></span>
                <p>Shipped</p>
                <!-- <p>Shipped<br><span>Tue, June 25</span></p> -->
            </div>
            <div class="order-tracking <?php if ($itemDetails[0]->completed >= 2) {
                                            echo "completed";
                                        } ?>">
                <span class="is-complete"></span>
                <p>Delivered</p>
                <!-- <p>Delivered<br><span>Fri, June 28</span></p> -->
            </div>
            <!-- <div class="order-tracking">
            <span class="is-complete"></span>
            <p>Delivered<br><span>Fri, June 28</span></p>
        </div> -->

        <?php
        }
        ?>
    </div>
    <!-- <div class="row"> -->
    <?php

    if ($itemDetails[0]->completed != 3) {
    ?>
        <button id="<?php echo $orderProductID; ?>" class="btn bg-danger btn-small float-end mt-10 mb-10 mr-10" onclick="deleteOrderItem(<?php echo $orderProductID; ?>)">Cancel Item</button>
    <?php
    }
    ?>

    <!-- </div> -->
    <!-- </div>
</div> -->
<?php
}

if ($_POST['check'] == "cancelFullOrder") {
    $orderId = $_POST['orderId'];
    $url = "http://192.168.0.116/neonbazar_api/cancel_order.php?order_id=" . $orderId;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array( //header will be here
            'Content-Type: application/json',
            'Authorization: ' . APIKEY,
        )
    );
    $updateInfo = curl_exec($ch);
    curl_close($ch);
    $updateStatus = json_decode($updateInfo);
    echo $updateStatus->message;
}

if ($_POST['check'] == "deleteOrderItem") {
    $itemId = $_POST['itemId'];
    $url = "http://192.168.0.116/neonbazar_api/delete_order_item.php?item_id=" . $itemId;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array( //header will be here
            'Content-Type: application/json',
            'Authorization: ' . APIKEY,
        )
    );
    $updateInfo = curl_exec($ch);
    curl_close($ch);
    $updateStatus = json_decode($updateInfo);
    echo $updateStatus->message;
}
?>