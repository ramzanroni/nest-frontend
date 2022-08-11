<?php

$catID = 1;
$url = "product.php?category_id=" . $catID . "&limit=All&start=1"; //url will be here



$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => APIENDPOINT .  $url,
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
    $cateoryProduct = json_decode($response);
    $categoryData = $cateoryProduct->data->products;
}
$outProduct = array_chunk($categoryData, 3, true);
?>

<section class="section-padding mb-30">
    <div class="container">
        <div class="row">
            <h4 class="section-title style-1 mb-30 animated animated">Our Products</h4>
            <div class="col-xl-3 col-lg-4 col-md-6 mb-sm-5 mb-md-0">

                <div class="product-list-small animated animated">
                    <?php
                    foreach ($outProduct[0] as $productValue) {
                    ?>
                        <article class="row align-items-center hover-up">
                            <figure class="col-md-4 mb-0">
                                <a href="product.php?product_id=<?php echo $productValue->stockid; ?>"><img src="<?php if ($productValue->img != '') {
                                                                                                                        echo  $productValue->img;
                                                                                                                    } else {
                                                                                                                        echo 'assets/imgs/product.png';
                                                                                                                    } ?>" alt="" /></a>
                            </figure>
                            <div class="col-md-8 mb-0">
                                <h6>
                                    <a href="product.php?product_id=<?php echo $productValue->stockid; ?>"><?php echo $productValue->description; ?></a>
                                </h6>
                                <!-- <div class="product-rate-cover">
                                    <div class="product-rate d-inline-block">
                                        <div class="product-rating" style="width: 90%"></div>
                                    </div>
                                    <span class="font-small ml-5 text-muted"> (4.0)</span>
                                </div> -->
                                <div class="product-price">
                                    <span>৳<?php echo $productValue->webprice; ?></span>
                                    <!-- <span class="old-price">৳33.8</span> -->
                                </div>
                            </div>
                        </article>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 mb-md-0">
                <div class="product-list-small animated animated">
                    <?php
                    foreach ($outProduct[1] as $productValue) {
                    ?>
                        <article class="row align-items-center hover-up">
                            <figure class="col-md-4 mb-0">
                                <a href="product.php?product_id=<?php echo $productValue->stockid; ?>"><img src="<?php if ($productValue->img != '') {
                                                                                                                        echo  $productValue->img;
                                                                                                                    } else {
                                                                                                                        echo 'assets/imgs/product.png';
                                                                                                                    } ?>" alt="" /></a>
                            </figure>
                            <div class="col-md-8 mb-0">
                                <h6>
                                    <a href="product.php?product_id=<?php echo $productValue->stockid; ?>"><?php echo $productValue->description; ?></a>
                                </h6>
                                <!-- <div class="product-rate-cover">
                                    <div class="product-rate d-inline-block">
                                        <div class="product-rating" style="width: 90%"></div>
                                    </div>
                                    <span class="font-small ml-5 text-muted"> (4.0)</span>
                                </div> -->
                                <div class="product-price">
                                    <span>৳<?php echo $productValue->webprice; ?></span>
                                    <!-- <span class="old-price">৳33.8</span> -->
                                </div>
                            </div>
                        </article>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 mb-sm-5 mb-md-0 d-none d-lg-block">
                <div class="product-list-small animated animated">
                    <?php
                    foreach ($outProduct[2] as $productValue) {
                    ?>
                        <article class="row align-items-center hover-up">
                            <figure class="col-md-4 mb-0">
                                <a href="product.php?product_id=<?php echo $productValue->stockid; ?>"><img src="<?php if ($productValue->img != '') {
                                                                                                                        echo $productValue->img;
                                                                                                                    } else {
                                                                                                                        echo 'assets/imgs/product.png';
                                                                                                                    } ?>" alt="" /></a>
                            </figure>
                            <div class="col-md-8 mb-0">
                                <h6>
                                    <a href="product.php?product_id=<?php echo $productValue->stockid; ?>"><?php echo $productValue->description; ?></a>
                                </h6>
                                <!-- <div class="product-rate-cover">
                                    <div class="product-rate d-inline-block">
                                        <div class="product-rating" style="width: 90%"></div>
                                    </div>
                                    <span class="font-small ml-5 text-muted"> (4.0)</span>
                                </div> -->
                                <div class="product-price">
                                    <span>৳<?php echo $productValue->webprice; ?></span>
                                    <!-- <span class="old-price">৳33.8</span> -->
                                </div>
                            </div>
                        </article>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 mb-sm-5 mb-md-0 d-none d-xl-block">
                <div class="product-list-small animated animated">
                    <?php
                    foreach ($outProduct[3] as $productValue) {
                    ?>
                        <article class="row align-items-center hover-up">
                            <figure class="col-md-4 mb-0">
                                <a href="product.php?product_id=<?php echo $productValue->stockid; ?>"><img src="<?php
                                                                                                                    if ($productValue->img != '') {
                                                                                                                        echo $productValue->img;
                                                                                                                    } else {
                                                                                                                        echo 'assets/imgs/product.png';
                                                                                                                    } ?>" alt="" /></a>
                            </figure>
                            <div class="col-md-8 mb-0">
                                <h6>
                                    <a href="product.php?product_id=<?php echo $productValue->stockid; ?>"><?php echo $productValue->description; ?></a>
                                </h6>
                                <!-- <div class="product-rate-cover">
                                    <div class="product-rate d-inline-block">
                                        <div class="product-rating" style="width: 90%"></div>
                                    </div>
                                    <span class="font-small ml-5 text-muted"> (4.0)</span>
                                </div> -->
                                <div class="product-price">
                                    <span>৳<?php echo $productValue->webprice; ?></span>
                                    <!-- <span class="old-price">৳33.8</span> -->
                                </div>
                            </div>
                        </article>
                    <?php
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
</section>