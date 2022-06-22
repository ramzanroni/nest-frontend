<?php
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
            'Authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE2MTg4OTU1MjIsImp0aSI6IlRQSTVmdFFUeU5MR1ZLenFOZlVhYThyRURpdEJkRmpIS0ErUGVFMTFjMTg9IiwiaXNzIjoicHVsc2VzZXJ2aWNlc2JkLmNvbSIsImRhdGEiOnsidXNlcklkIjoiMjg4MTUiLCJ1c2VyTGV2ZWwiOjJ9fQ.wQ5AQR-fIGRZgt3CN9-W6v4PkvTIvNVP8HzCOiHHeKwcd8NT1R1Dxz_XpJH9jOa7CsDzCYBklEPRtQus11NiEQ',
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
            'Authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE2MTg4OTU1MjIsImp0aSI6IlRQSTVmdFFUeU5MR1ZLenFOZlVhYThyRURpdEJkRmpIS0ErUGVFMTFjMTg9IiwiaXNzIjoicHVsc2VzZXJ2aWNlc2JkLmNvbSIsImRhdGEiOnsidXNlcklkIjoiMjg4MTUiLCJ1c2VyTGV2ZWwiOjJ9fQ.wQ5AQR-fIGRZgt3CN9-W6v4PkvTIvNVP8HzCOiHHeKwcd8NT1R1Dxz_XpJH9jOa7CsDzCYBklEPRtQus11NiEQ',
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
                                John Alexander <br />
                                alex@example.com <br />
                                +998 99 22123456
                            </p>
                            <a href="#">View profile</a>
                        </div>
                    </article>
                </div>
                <!-- col// -->
                <div class="col-md-6">
                    <article class="icontext align-items-start">
                        <span class="icon icon-sm rounded-circle bg-primary-light">
                            <i class="text-primary material-icons fi-rs-truck"></i>
                        </span>
                        <div class="text">
                            <h6 class="mb-1">Order info</h6>
                            <p class="mb-1">
                                Shipping: Fargo express <br />
                                Pay method: card <br />
                                Status: new
                            </p>
                            <a href="#">Download info</a>
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
                                    <th width="20%">Unit Price</th>
                                    <th width="20%">Quantity</th>
                                    <th width="20%" class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $total = 0;
                                    $flag = 1;
                                    foreach ($orderDetails as $orderItemValue) {
                                    ?>
                                <tr onclick="showHideRow('hidden_row<?php echo $flag; ?>', <?php echo $flag; ?>)">
                                    <td>
                                        <a class="itemside"
                                            href="http://localhost/nest-frontend/shop-product-right.php?product_id=<?php echo $orderItemValue->stockid; ?>">
                                            <div class="left">
                                                <img src="//<?php echo $orderItemValue->img; ?>" width="40" height="40"
                                                    class="img-xs" alt="Item" />
                                            </div>
                                            <div class="info"><?php echo $orderItemValue->description; ?></div>
                                        </a>
                                    </td>
                                    <td>৳<?php echo $orderItemValue->unitprice; ?></td>
                                    <td><?php echo $orderItemValue->quantity; ?></td>
                                    <td class="text-end">৳
                                        <?php echo ($orderItemValue->unitprice * $orderItemValue->quantity); ?></td>
                                </tr>
                                <tr id="hidden_row<?php echo $flag; ?>" class="hidden_row">
                                    <td colspan=4 id="itemDetails<?php echo $flag; ?>">
                                        Person-1 is 24 years old and
                                        he is a computer programmer
                                        he earns 60000 per month
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