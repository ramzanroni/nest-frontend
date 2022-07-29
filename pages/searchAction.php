<?php
include '../inc/function.php';
include '../inc/apiendpoint.php';
if ($_POST['check'] == "searchItem") {
    $categoryId = $_POST['categoryName'];
    $productSearchItem = $_POST['productSearchItem'];
    if ($categoryId == "") {
        $url = 'product.php?product_name=' . urlencode($productSearchItem);
    } else {
        $url = 'product.php?product_name=' . urlencode($productSearchItem) . '&category=' . $categoryId; //url will be here
    }


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
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
        $searchData = $result->data->products;
        $totalProduct = $result->data->rows_returned;
    }





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
    // $categoryInfo = curl_exec($ch);
    // curl_close($ch);
    // $searchData = json_decode($categoryInfo);
    // $totalProduct = count($searchData);
    // print_r($searchData);
?>
    <?php
    $i = 1;
    if ($totalProduct > 0) {
        foreach ($searchData as $itemValue) {
            if ($i % 2 != 0) {
                echo '<div class="row">';
            }
    ?>

            <div class="col-6 p-2 serchItemBox">
                <div class="col-3 p-2 float-start">
                    <img src="//<?php echo $itemValue->img; ?>" alt="" width="80px" height="80px">
                </div>
                <div class="col-7 float-end">
                    <a href="product.php?product_id=<?php echo $itemValue->stockid; ?>" class="h6"><?php echo $itemValue->description; ?></a><br>
                    <span class="bg-brand pt-1 pb-1 pr-10 pl-10 text-white border-radius-10"><?php echo $itemValue->webprice; ?></span>
                </div>
            </div>


            <!-- <div class="col-7 p-2 float-start">Hello</div> -->
        <?php
            if ($i % 2 == 0) {
                echo '</div>';
            }
            $i++;
        }

        if ($totalProduct % 2 != 0) {
            echo '</div>';
        }

        ?>
        <div class="row">
            <div class="col-12 text-center p-3">
                <a onclick="viewAllItem('<?php echo $categoryId; ?>','<?php echo  $productSearchItem; ?>')">Show All</a>
            </div>
        </div>
    <?php
    } else {
        echo "Nothing found.";
    }
    ?>
<?php
}

if ($_POST['check'] == "viewAllItem") {
    $categoryId = $_POST['categoryId'];
    $itemString = $_POST['itemString'];
    $url = 'http://192.168.0.116/neonbazar_api/search_product.php?product_name=' . urlencode($itemString) . '&category=' . $categoryId; //url will be here
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
    $categoryInfo = curl_exec($ch);
    curl_close($ch);
    $searchData = json_decode($categoryInfo);
    $cartCookiesProduct = json_decode($_COOKIE['shopping_cart']);
?>
    <div class="row product-grid mt-10" id="myTabContent">
        <?php
        foreach ($searchData as $productData) {
        ?>
            <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                <?php
                $cartCookiesProduct = json_decode($_COOKIE['shopping_cart']);
                $cartItem = count($cartCookiesProduct);
                ?>
                <div class="product-cart-wrap mb-30">
                    <div class="product-img-action-wrap">
                        <div class="product-img product-img-zoom">
                            <a href="product.php?product_id=<?php echo $productData->stockid; ?>">
                                <img class="default-img" src="//<?php echo $productData->img; ?>" alt="" />
                            </a>
                        </div>
                    </div>
                    <div class="product-content-wrap">
                        <div class="product-category">
                            <a href="products.php?category_id<?php echo $productData->category; ?>"><?php echo $productData->category; ?></a>
                        </div>
                        <h2><a href="product.php?product_id=<?php echo $productData->stockid; ?>"><?php echo $productData->description; ?></a>

                            <!-- <div class="product-badges">
                                                    <small><span
                                                        class="hot"><?php echo $productData->units; ?></span></small>
                                                </div> -->
                        </h2>
                        <div class="product-rate-cover product-badges">
                            <span class="font-small ml-5 text-muted hot"><?php echo $productData->units; ?></span>
                        </div>
                        <!-- <div class="product-rate-cover">
                                                <div class="product-rate d-inline-block">
                                                    <div class="product-rating" style="width: 90%"></div>
                                                </div>
                                                <span class="font-small ml-5 text-muted"> (4.0)</span>
                                            </div> -->
                        <!-- <div>
                                                    <span class="font-small text-muted">By <a href="vendor-details-1.php">NestFood</a></span>
                                                </div> -->
                        <div class="product-card-bottom">
                            <div class="product-price">
                                <span>৳<?php echo $productData->webprice; ?></span>
                                <!-- <span class="old-price"><?php echo $item['price']; ?></span> -->
                            </div>
                            <!-- <span id="carBtnId_<?php echo $productData->stockid; ?>"> -->
                            <?php
                            $cartProductID = '';
                            $numberOfItem = '';
                            $catIndex = '';
                            foreach ($cartCookiesProduct as $cartKey => $itemValue) {
                                if ($itemValue->productID == $productData->stockid) {
                                    $cartProductID = $itemValue->productID;
                                    $numberOfItem = $itemValue->productQuantity;
                                    $catIndex = $cartKey;
                                }
                            }
                            if ($cartProductID == '') {
                            ?>
                                <div id="item_<?= $productData->stockid ?>">
                                    <div class="add-cart">
                                        <a class="add" onclick="firstAddtoCart(<?php echo $productData->stockid; ?>,'<?php echo $productData->description; ?>',<?php echo $productData->webprice; ?>,1,'<?php echo $productData->img; ?>')"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                    </div>
                                </div>
                            <?php
                            } else {
                            ?>
                                <div class="col-12" id="item_<?= $productData->stockid ?>">
                                    <input type="hidden" id="getItem_<?php echo $productData->stockid; ?>" value="<?php echo $numberOfItem; ?>">
                                    <div class="col-10 float-end after-cart">
                                        <div class="col-2 float-end increment" onclick="CartItemChange('increment', <?php echo $productData->stockid; ?>,'<?php echo $productData->description; ?>',<?php echo $productData->webprice; ?>,'<?php echo $productData->img;  ?>')">
                                            <a><i class="fi-rs-plus"></i></a>
                                        </div>
                                        <div class="col-4 float-end middle">
                                            <a><i class="fi-rs-shopping-cart"></i>
                                                <span id="cartCount_<?php echo $productData->stockid; ?>"><?php echo $numberOfItem; ?></span>
                                            </a>
                                        </div>
                                        <div class="col-2 float-end add decrement" onclick="CartItemChange('decrement', <?php echo $productData->stockid; ?>,'<?php echo $productData->description; ?>',<?php echo $productData->webprice; ?>,'<?php echo $productData->img;  ?>')">
                                            <a><i class="fi-rs-minus"></i></a>
                                        </div>
                                    </div>
                                </div>

                            <?php
                            }
                            ?>
                            <!-- </span> -->

                        </div>
                    </div>
                </div>
            </div>

        <?php
        }
        ?>
    </div>
    <?php
}

if ($_POST['check'] == "searchItemMobile") {

    $categoryId = '';
    $productSearchItem = $_POST['searchString'];
    if ($categoryId == "") {
        $url = 'product.php?product_name=' . urlencode($productSearchItem);
    } else {
        $url = 'product.php?product_name=' . urlencode($productSearchItem) . '&category=' . $categoryId; //url will be here
    }


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
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
        $searchData = $result->data->products;
        $totalProduct = $result->data->rows_returned;
    }



    // $searchString = $_POST['searchString'];





    // $url = 'http://192.168.0.116/neonbazar_api/search_product.php?product_name=' . urlencode($searchString) . '&category='; //url will be here
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
    // $categoryInfo = curl_exec($ch);
    // curl_close($ch);
    // $searchData = json_decode($categoryInfo);
    // $totalProduct = count($searchData);
    if ($totalProduct > 0) {
    ?>
        <div class="col-12">
            <?php
            $productSliceArr = array_slice($searchData, 0, 10, true);
            foreach ($productSliceArr as $searchItemValue) {
            ?>
                <div class="row border-bottom p-2">
                    <div class="col-3 p-2 float-start">
                        <img src="//<?php echo $searchItemValue->img; ?>" alt="" width="40px" height="40px">
                    </div>
                    <div class="col-7 float-end pt-10">
                        <a href="product.php?product_id=<?php echo $searchItemValue->stockid; ?>" class="h6"><?php echo $searchItemValue->description; ?></a><br>
                        <span class="bg-brand mt-10 pt-1 pb-1 pr-10 pl-10 text-white border-radius-10">৳<?php echo $searchItemValue->webprice; ?></span>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="col-12 text-center p-3 border-bottom">
            <a onclick="viewAllItemMobile('<?php echo  $productSearchItem; ?>')">Show All</a>
        </div>
<?php
    } else {
        echo "No product found..!";
    }
}
?>