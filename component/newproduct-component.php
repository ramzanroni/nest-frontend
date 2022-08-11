<?php
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => APIENDPOINT . "product.php",
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
    $productData = json_decode($response);
    $productsArr = $productData->data->products;
    // print_r($productsArr);
    $newProduct = array_slice($productsArr, 0, 4);
}
?>

<div class="sidebar-widget product-sidebar mb-30 p-30 bg-grey border-radius-10">
    <h5 class="section-title style-1 mb-30">New products</h5>
    <?php
    foreach ($newProduct as $newProductValue) {
    ?>
        <div class="single-post clearfix">
            <div class="image">
                <img src="<?php if ($newProductValue->img != '') {
                                echo $newProductValue->img;
                            } else {
                                echo 'assets/imgs/product.png';
                            } ?>" alt="#" />
            </div>
            <div class="content pt-10">
                <h6><a href="product.php?product_id=<?php echo $newProductValue->stockid; ?>"><?php echo $newProductValue->description; ?></a></h6>
                <p class="price mb-0 mt-5">৳<?php echo $newProductValue->webprice; ?></p>
                <!-- <div class="product-rate">
                    <div class="product-rating" style="width: 90%"></div>
                </div> -->
            </div>
        </div>
    <?php
    }
    ?>
</div>