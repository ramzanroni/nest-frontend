<?php
include '../inc/function.php';
include '../inc/apiendpoint.php';
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
    $item = json_encode($itemInfo);

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
    $url = APIENDPOINT . "createOrder.php";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array( //header will be here
            "cache-control: no-cache",
            "content-type: application/json",
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

    if ($response->success == true) {
        echo 'success';
    }
}

if ($_POST['check'] == "checkorderditails") {
    $orderNumber = $_POST['orderNumber'];
    // order details 
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . "orderDetails.php?order_id=" . $orderNumber,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "authorization:" . APIKEY,
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
        $orderDetails = $result->data->data;
    }

    // $url = "https://demostarter.erp.place/eback/order_details.php?order_id=7";
    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, $url);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt(
    //     $ch,
    //     CURLOPT_HTTPHEADER,
    //     array( //header will be here
    //         'Content-Type: application/json',
    //         'Authorization: ' . 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.7reARPlCna_cIAo1LQ88CmCT6LThZozlt6k3Mw8leLY',
    //     )
    // );
    // $itemInfo = curl_exec($ch);
    // curl_close($ch);
    // $orderDetails = json_decode($itemInfo);
    // print_r($orderDetails);
    // exit;



    // order details 


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . "carton.php?order_id=" . $orderNumber,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "authorization:" . APIKEY,
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
        $orderCartonDetails = $result->data->carton;
    }




    // $url = "https://demostarter.erp.place/eback/order_carton.php?order_id=" . $orderNumber;
    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, $url);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt(
    //     $ch,
    //     CURLOPT_HTTPHEADER,
    //     array( //header will be here
    //         'Content-Type: application/json',
    //         'Authorization: ' . 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.7reARPlCna_cIAo1LQ88CmCT6LThZozlt6k3Mw8leLY',
    //     )
    // );
    // $itemCartonInfo = curl_exec($ch);
    // curl_close($ch);
    // $orderCartonDetails = json_decode($itemCartonInfo);
?>
    <section class="content-main">
        <div class="content-header">
            <div>
                <h2 class="content-title card-title">Order detail</h2>
                <p>Details for Order ID: <?php echo $orderNumber; ?></p>
            </div>
        </div>
        <div class="card">
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
                                        <small class="badge rounded-pill alert-warning text-warning">Pending</small>
                                    <?php
                                    } elseif ($orderDetails->info->so_status == 1) {
                                    ?>
                                        <small class="badge rounded-pill alert-success text-success">Accept</small>
                                    <?php
                                    } elseif ($orderDetails->info->so_status == 2) {
                                    ?>
                                        <small class="badge rounded-pill alert-secondary text-secondary">Rejected</small>
                                    <?php
                                    } elseif ($orderDetails->info->so_status == 3) {
                                    ?>
                                        <small class="badge rounded-pill alert-danger text-danger">Canceled</small>
                                    <?php
                                    } elseif ($orderDetails->info->so_status == 4) {
                                    ?>
                                        <small class="badge rounded-pill alert-success text-success">Complete</small>
                                    <?php
                                    }
                                    ?>
                                </p>
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
                                        <th width="20%">Product</th>
                                        <th width="13%" class="text-end">Unit Price</th>
                                        <th width="13%" class="text-end">Order Qty</th>
                                        <th width="13%" class="text-end">Delivery Qty</th>
                                        <th width="13%" class="text-end">Shipping Qty</th>
                                        <th width="13%" class="text-end">Total</th>
                                        <th width="15%" class="text-end"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total = 0;
                                    $flag = 1;
                                    foreach ($orderDetails->item as $orderItemValue) {
                                    ?>
                                        <tr>
                                            <td>
                                                <a class="itemside" href="product.php?id=<?php echo $orderItemValue->stockid; ?>">
                                                    <div class="left">
                                                        <img src="//<?php echo $orderItemValue->img; ?>" width="40" height="40" class="img-xs" alt="Item" />
                                                    </div>
                                                    <div class="info"><?php echo $orderItemValue->description; ?></div>
                                                </a>
                                            </td>
                                            <td class="text-end">৳<?php echo $orderItemValue->unitprice; ?></td>
                                            <td class="text-end"><?php echo $orderItemValue->quantity; ?></td>
                                            <td class="text-end"><?php echo $orderItemValue->qtyinvoiced; ?></td>
                                            <td class="text-end"><?php echo $orderItemValue->qtyinshipping; ?></td>
                                            <td class="text-end">৳
                                                <?php echo ($orderItemValue->unitprice * $orderItemValue->quantity); ?>
                                            </td>
                                            <td class="text-end">
                                                <?php if (($orderDetails->info->so_status == 0 || $orderDetails->info->so_status == 1) && $orderItemValue->status == 0) {
                                                ?>
                                                    <button id="cancel_<?php echo $orderNumber . "_" . $orderItemValue->orderlineno; ?>" onclick="deleteOrderItem(<?php
                                                                                                                                                                    echo $orderNumber;
                                                                                                                                                                    echo ',';
                                                                                                                                                                    echo $orderItemValue->orderlineno;
                                                                                                                                                                    ?>)" class="float-end btn btn-small bg-danger p-2 mr-10"><i title="Cancel Item" class="fi-rs-cross"></i></button>
                                                <?php
                                                } ?>

                                            </td>
                                        </tr>
                                    <?php
                                        $total = $total + ($orderItemValue->unitprice * $orderItemValue->quantity);
                                        $flag++;
                                    }
                                    ?>

                                    <tr>
                                        <td colspan="6">
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

                <!-- row carton -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table" id="table_detail">
                                <!-- <thead>
                                    <tr>
                                        <th width="33%">Package No</th>
                                        <th width="33%" class="text-end">Status</th>
                                        <th width="33%" class="text-end">Action</th>
                                    </tr>
                                </thead> -->
                                <tbody>
                                    <?php

                                    foreach ($orderCartonDetails as $orderCartonItemValue) {
                                    ?>
                                        <tr>
                                            <div class="row shadow-none p-3 mb-5 bg-light rounded">
                                                <div class="col-md-12 mb-10">
                                                    <div class="col-md-2 align-middle float-start">
                                                        <h6 class="mb-1">Package ID: #<?= $orderCartonItemValue->id ?></h6>
                                                        <p class="mb-1">Status: <?= $orderCartonItemValue->carton_status ?></p>
                                                    </div>
                                                    <div class="col-md-8 float-start">
                                                        <div class="row pt-10 justify-content-center">


                                                            <div class="order-tracking <?php if (!is_null($orderCartonItemValue->date_packed)) {
                                                                                            echo "completed";
                                                                                        } ?>">
                                                                <span class="is-complete"></span>
                                                                <!-- <p>Packaging</p> -->
                                                                <p>Packed<br><span><?php echo !is_null($orderCartonItemValue->date_packed) ? $orderCartonItemValue->date_packed : '' ?></span></p>
                                                            </div>
                                                            <div class="order-tracking <?php if (!is_null($orderCartonItemValue->date_shiped)) {
                                                                                            echo "completed";
                                                                                        } ?>">
                                                                <span class="is-complete"></span>
                                                                <!-- <p>Shipped</p> -->
                                                                <p>Shipping<br><span><?php echo !is_null($orderCartonItemValue->date_shiped) ? $orderCartonItemValue->date_shiped : '' ?></span></p>
                                                            </div>
                                                            <div class="order-tracking <?php if (!is_null($orderCartonItemValue->date_delivered)) {
                                                                                            echo "completed";
                                                                                        } ?>">
                                                                <span class="is-complete"></span>
                                                                <!-- <p>Delivered</p> -->
                                                                <p>Delivered<br><span><?php echo !is_null($orderCartonItemValue->date_delivered) ? $orderCartonItemValue->date_delivered : '' ?></span></p>
                                                            </div>
                                                            <?php

                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 align-middle float-end">
                                                        <button class="float-end btn btn-small bg-sucess p-2" onclick="showPackageDetails(this,<?php echo $orderNumber; ?>,<?php echo $orderCartonItemValue->id; ?>)"><i title="Show Details" class="fi-rs-angle-down"></i></button>
                                                        <?php if ($orderCartonItemValue->carton_status == 'Packed') {
                                                        ?>
                                                            <button onclick="CancelCartoonDelivery(<?php
                                                                                                    echo $orderNumber;
                                                                                                    echo ',';
                                                                                                    echo $orderItemValue->orderlineno;
                                                                                                    ?>)" class="float-end btn btn-small bg-danger p-2 mr-10"><i title="Cancel Package" class="fi-rs-cross"></i></button>
                                                        <?php
                                                        } ?>
                                                    </div>


                                                </div>
                                            </div>
                                        </tr>
                                    <?php

                                    }
                                    ?>

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

if ($_POST['check'] == "CancelCartoonDelivery") {
    $orderNumber = $_POST['orderNumber'];
    $orderlineno = $_POST['orderlineno'];

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . "carton.php?orderNumber=" . $orderNumber . "&orderlineno=" . $orderlineno,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "authorization:" . APIKEY,
            "cache-control: no-cache"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response;
    }
}

if ($_POST['check'] == "checkorderditailsOld") {
    $orderNumber = $_POST['orderNumber'];
    // order details 

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . "orderDetails.php?order_id=" . $orderNumber,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "authorization:" . APIKEY,
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
        $orderDetails = $result->data->data;
    }




    // $url = "https://demostarter.erp.place/eback/order_details.php?order_id=" . $orderNumber;
    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, $url);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt(
    //     $ch,
    //     CURLOPT_HTTPHEADER,
    //     array( //header will be here
    //         'Content-Type: application/json',
    //         'Authorization: ' . APIKEY,
    //     )
    // );
    // $itemInfo = curl_exec($ch);
    // curl_close($ch);
    // $orderDetails = json_decode($itemInfo);
?>
    <section class="content-main">
        <div class="content-header">
            <div>
                <h2 class="content-title card-title">Order detail</h2>
                <p>Details for Order ID: <?php echo $orderNumber; ?></p>
            </div>
        </div>
        <div class="card">
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
                                        <small class="badge rounded-pill alert-warning text-warning">Pending</small>
                                    <?php
                                    } elseif ($orderDetails->info->so_status == 1) {
                                    ?>
                                        <small class="badge rounded-pill alert-success text-success">Accept</small>
                                    <?php
                                    } elseif ($orderDetails->info->so_status == 2) {
                                    ?>
                                        <small class="badge rounded-pill alert-secondary text-secondary">Rejected</small>
                                    <?php
                                    } elseif ($orderDetails->info->so_status == 3) {
                                    ?>
                                        <small class="badge rounded-pill alert-danger text-danger">Canceled</small>
                                    <?php
                                    } elseif ($orderDetails->info->so_status == 4) {
                                    ?>
                                        <small class="badge rounded-pill alert-success text-success">Complete</small>
                                    <?php
                                    }
                                    ?>
                                </p>
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
                                        <th width="20%">Product</th>
                                        <th width="13%" class="text-end">Unit Price</th>
                                        <th width="13%" class="text-end">Order Qty</th>
                                        <th width="13%" class="text-end">Delivery Qty</th>
                                        <th width="13%" class="text-end">Shipping Qty</th>
                                        <th width="13%" class="text-end">Total</th>
                                        <th width="15%" class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total = 0;
                                    $flag = 1;
                                    foreach ($orderDetails->item as $orderItemValue) {
                                    ?>
                                        <tr>
                                            <td>
                                                <a class="itemside" href="product.php?id=<?php echo $orderItemValue->stockid; ?>">
                                                    <div class="left">
                                                        <img src="<?php echo $orderItemValue->img; ?>" width="40" height="40" class="img-xs" alt="Item" />
                                                    </div>
                                                    <div class="info"><?php echo $orderItemValue->description; ?></div>
                                                </a>
                                            </td>
                                            <td class="text-end">৳<?php echo $orderItemValue->unitprice; ?></td>
                                            <td class="text-end"><?php echo $orderItemValue->quantity; ?></td>
                                            <td class="text-end"><?php echo $orderItemValue->qtyinvoiced; ?></td>
                                            <td class="text-end"><?php echo $orderItemValue->qtyinshipping; ?></td>
                                            <td class="text-end">৳
                                                <?php echo ($orderItemValue->unitprice * $orderItemValue->quantity); ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if (($orderDetails->info->so_status == 0 || $orderDetails->info->so_status == 1) && $orderItemValue->status == 0) {
                                                ?>
                                                    <button onclick="deleteOrderItem(<?php
                                                                                        echo $orderNumber;
                                                                                        echo ',';
                                                                                        echo $orderItemValue->orderlineno;
                                                                                        ?>)" class="float-start btn btn-small bg-danger">Cancel</button>
                                                <?php
                                                } ?>
                                                <button class="float-end btn btn-small bg-sucess p-2" onclick="showHideRow('hidden_row<?php echo $flag; ?>', <?php echo $flag; ?>,<?php echo $orderItemValue->stockid; ?>,<?php echo $orderNumber; ?>)"><i title="Show Details" class="fi-rs-angle-down"></i></button>

                                            </td>
                                        </tr>
                                        <tr id="hidden_row<?php echo $flag; ?>" class="hidden_row">
                                            <td colspan=7 id="itemDetails<?php echo $flag; ?>">
                                            </td>
                                        </tr>
                                    <?php
                                        $total = $total + ($orderItemValue->unitprice * $orderItemValue->quantity);
                                        $flag++;
                                    }
                                    ?>

                                    <tr>
                                        <td colspan="6">
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
if ($_POST['check'] == "cartonItemDetails") {
    $cartonNumber = $_POST['cartonNumber'];
    $orderNumber = $_POST['orderNumber'];

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . "carton.php?orderId=" . $orderNumber . "&carton_id=" . $cartonNumber,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "authorization:" . APIKEY,
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
        $itemDetails = $result->data->cartonItem;
    }






    // $url = "https://demostarter.erp.place/eback/carton_item.php?order_id=" . $orderNumber . "&carton_id=" . $cartonNumber;

    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, $url);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt(
    //     $ch,
    //     CURLOPT_HTTPHEADER,
    //     array( //header will be here
    //         'Content-Type: application/json',
    //         'Authorization: ' . 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.7reARPlCna_cIAo1LQ88CmCT6LThZozlt6k3Mw8leLY',
    //     )
    // );
    // $itemInfo = curl_exec($ch);
    // curl_close($ch);
    // $itemDetails = json_decode($itemInfo);
    // print_r($itemDetail);

?>
    <div class="row cartonDetails">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table" id="table_detail">
                    <thead>
                        <tr>
                            <th width="20%">Product</th>
                            <th width="13%" class="text-end">Unit Price</th>
                            <th width="13%" class="text-end">Shipping Qty</th>
                            <th width="13%" class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        $flag = 1;
                        foreach ($itemDetails as $orderItemValue) {
                        ?>
                            <tr>
                                <td>
                                    <a class="itemside" href="product.php?id=<?php echo $orderItemValue->stockid; ?>">
                                        <div class="left">
                                            <img src="//<?php echo $orderItemValue->img; ?>" width="40" height="40" class="img-xs" alt="Item" />
                                        </div>
                                        <div class="info"><?php echo $orderItemValue->description; ?></div>
                                    </a>
                                </td>

                                <td class="text-end"><?php echo $orderItemValue->unitprice; ?></td>
                                <td class="text-end"><?php echo $orderItemValue->qty; ?></td>
                                <td class="text-end">৳
                                    <?php echo ($orderItemValue->unitprice * $orderItemValue->qty); ?>
                                </td>

                            </tr>
                        <?php
                            $total = $total + ($orderItemValue->unitprice * $orderItemValue->qty);
                            $flag++;
                        }
                        ?>

                        <tr>
                            <td colspan="6">
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
                                </article>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <?php
}
if ($_POST['check'] == "itemDetails") {
    $orderProductID = $_POST['orderProductID'];
    $orderNumber = $_POST['orderNumber'];

    //$url = "https://demostarter.erp.place/eback/order_item.php?order_id=" . $orderNumber . "&item_id=" . $orderProductID;

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
    foreach ($itemDetails as $itemDetail) {
    ?>
        <div class="row shadow-none p-3 mb-5 bg-light rounded">
            <div class="col-md-12">
                <div class="col-md-2 align-middle float-start">
                    <h6 class="mb-1">Package ID: #<?= $itemDetail->id ?></h6>
                    <p class="mb-1">Qty: <?= $itemDetail->qty ?></p>
                </div>
                <div class="col-md-8 float-start">
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
                            <div class="order-tracking <?php if (!is_null($itemDetail->date_packed)) {
                                                            echo "completed";
                                                        } ?>">
                                <span class="is-complete"></span>
                                <!-- <p>Packaging</p> -->
                                <p>Packed<br><span><?php echo !is_null($itemDetail->date_packed) ? $itemDetail->date_packed : '' ?></span></p>
                            </div>
                            <div class="order-tracking <?php if (!is_null($itemDetail->date_shiped)) {
                                                            echo "completed";
                                                        } ?>">
                                <span class="is-complete"></span>
                                <!-- <p>Shipped</p> -->
                                <p>Shipping<br><span><?php echo !is_null($itemDetail->date_shiped) ? $itemDetail->date_shiped : '' ?></span></p>
                            </div>
                            <div class="order-tracking <?php if (!is_null($itemDetail->date_delivered)) {
                                                            echo "completed";
                                                        } ?>">
                                <span class="is-complete"></span>
                                <!-- <p>Delivered</p> -->
                                <p>Delivered<br><span><?php echo !is_null($itemDetail->date_delivered) ? $itemDetail->date_delivered : '' ?></span></p>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>


            </div>
        </div>
    <?php
    }


    ?>


<?php
}

if ($_POST['check'] == "cancelFullOrder") {
    $orderId = $_POST['orderId'];

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . "order.php?order_id=" . $orderId,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "authorization:" . APIKEY,
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
        if ($result->success === true) {
            echo 'success';
        }
    }


    // $url = "https://demostarter.erp.place/eback/cancel_order.php?order_id=" . $orderId;
    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, $url);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt(
    //     $ch,
    //     CURLOPT_HTTPHEADER,
    //     array( //header will be here
    //         'Content-Type: application/json',
    //         'Authorization: ' . APIKEY,
    //     )
    // );
    // $updateInfo = curl_exec($ch);
    // curl_close($ch);
    // $updateStatus = json_decode($updateInfo);
    // echo $updateStatus->message;
}

if ($_POST['check'] == "deleteOrderItem") {
    $itemId = $_POST['lineNo'];
    $orderNumber = $_POST['orderNo'];
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . "order.php?item_id=" . $itemId . "&orderID=" . $orderNumber,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "authorization:" . APIKEY,
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
        if ($result->success === true) {
            echo 'success';
        }
    }







    // $url = "https://demostarter.erp.place/eback/delete_order_item.php?order_id=" . $orderNumber . "&item_id=" . $itemId;
    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, $url);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt(
    //     $ch,
    //     CURLOPT_HTTPHEADER,
    //     array( //header will be here
    //         'Content-Type: application/json',
    //         'Authorization: ' . APIKEY,
    //     )
    // );
    // $updateInfo = curl_exec($ch);
    // curl_close($ch);
    // $updateStatus = json_decode($updateInfo);
    // echo $updateStatus->message;
}
?>