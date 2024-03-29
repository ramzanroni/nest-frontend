﻿<?php
include 'apidata/dataFetch.php';
include 'inc/header.php';
include 'inc/apiendpoint.php';
// single product api data 
$product_id = $_GET['product_id'];
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => APIENDPOINT . "product.php?product_id=" . $product_id,
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
    $productInfo = json_decode($response);
    $productData = $productInfo->data->products;
}

$numberCheck = count($productData);

//Related product

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => APIENDPOINT . "product.php?category_id=" . $productData[0]->category_id . "&limit=5&start=0",
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
    $relatedproductInfo = json_decode($response);
    $relatedProductData = $relatedproductInfo->data->products;
}

if ($numberCheck > 0) {
?>
    <main class="main">
        <div class="page-header breadcrumb-wrap">
            <div class="container">
                <div class="breadcrumb">
                    <a href="index.php" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                    <span></span> <a href="products.php?category_id=<?php echo $productData[0]->category_id; ?>"><?php echo $productData[0]->category; ?></a><span></span><?php echo $productData[0]->description; ?>
                </div>
            </div>
        </div>
        <div class="container mb-30">
            <div class="row">
                <div class="col-xl-11 col-lg-12 m-auto">
                    <div class="row">
                        <div class="col-xl-9">
                            <div class="product-detail accordion-detail">
                                <div class="row mb-50 mt-30">
                                    <div class="col-md-6 col-sm-12 col-xs-12 mb-md-0 mb-sm-5">
                                        <div class="detail-gallery">
                                            <span class="zoom-icon"><i class="fi-rs-search"></i></span>
                                            <!-- MAIN SLIDES -->
                                            <div class="product-image-slider">
                                                <figure class="border-radius-10">
                                                    <img src="<?php if ($productData[0]->img == '') {
                                                                    echo './assets/imgs/product.png';
                                                                } else {
                                                                    echo $productData[0]->img;
                                                                } ?>" alt="product image" />
                                                </figure>
                                                <?php
                                                foreach ($productData[0]->multipleImg as $multiImgValue) {
                                                ?>
                                                    <figure class="border-radius-10">
                                                        <img src="<?php echo $multiImgValue; ?>" alt="product image" />
                                                    </figure>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                            <!-- THUMBNAILS -->
                                            <div class="slider-nav-thumbnails">
                                                <div><img src="<?php if ($productData[0]->img == '') {
                                                                    echo './assets/imgs/product.png';
                                                                } else {
                                                                    echo $productData[0]->img;
                                                                } ?>" alt="product image" />
                                                </div>
                                                <?php
                                                foreach ($productData[0]->multipleImg as $multiImgValue) {
                                                ?>
                                                    <div><img src="<?php echo $multiImgValue; ?>" alt="product image" /></div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <!-- End Gallery -->
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="detail-info pr-30 pl-30">
                                            <h2 class="title-detail"><?php echo $productData[0]->description; ?></h2>
                                            <div class="clearfix product-price-cover">
                                                <div class="product-price primary-color float-left">
                                                    <span class="current-price text-brand">৳<?php echo $productData[0]->webprice; ?></span>

                                                </div>
                                            </div>
                                            <div class="short-desc mb-30">
                                                <p class="font-lg"><?php echo $productData[0]->longdescription; ?></p>
                                            </div>
                                            <div class="attr-detail attr-size mb-30">
                                                <strong class="mr-10">Size / Weight: </strong>
                                                <ul class="list-filter size-filter font-small">
                                                    <li class="active"><a href="#"><?php echo $productData[0]->units; ?></a>
                                                    </li>

                                                </ul>
                                            </div>
                                            <div class="detail-extralink mb-50">
                                                <div class="detail-qty border radius">

                                                    <a onclick="cartDecrementSingle()" class="qty-down"><i class="fi-rs-angle-small-down"></i></a>
                                                    <input type="text" name="quantity" id="itemQuantitySingle" class="qty-val" value="1" min="1">
                                                    <a onclick="cartInchrementSingle()" class="qty-up"><i class="fi-rs-angle-small-up"></i></a>
                                                </div>
                                                <div class="product-extra-link2">
                                                    <button onclick="addtoCartSingle(<?php echo $productData[0]->stockid; ?>,'<?php echo $productData[0]->description; ?>',<?php echo $productData[0]->webprice; ?>,'<?php echo $productData[0]->img; ?>' )" type="submit" class="button button-add-to-cart"><i class="fi-rs-shopping-cart"></i>Add to cart</button>

                                                </div>
                                            </div>
                                            <div class="font-xs">
                                                <ul class="mr-50 float-start">
                                                    <li class="mb-5">Type: <span class="text-brand">Organic</span></li>
                                                    <li class="mb-5">MFG:<span class="text-brand"> Jun 4.2022</span></li>
                                                    <li>LIFE: <span class="text-brand">70 days</span></li>
                                                </ul>
                                                <ul class="float-start">
                                                    <li class="mb-5">SKU: <a href="#">FWM15VKT</a></li>
                                                    <li class="mb-5">Tags: <a href="#" rel="tag">Snack</a>, <a href="#" rel="tag">Organic</a>, <a href="#" rel="tag">Brown</a></li>
                                                    <li>Stock:<span class="in-stock text-brand ml-5">8 Items In Stock</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!-- Detail Info -->
                                    </div>
                                </div>
                                <div class="product-info">
                                    <div class="tab-style3">
                                        <ul class="nav nav-tabs text-uppercase">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="Description-tab" data-bs-toggle="tab" href="#Description">Description</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="Additional-info-tab" data-bs-toggle="tab" href="#Additional-info">Additional info</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="Vendor-info-tab" data-bs-toggle="tab" href="#Vendor-info">Vendor</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="Reviews-tab" data-bs-toggle="tab" href="#Reviews">Reviews (3)</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content shop_info_tab entry-main-content">
                                            <div class="tab-pane fade show active" id="Description">
                                                <div class="">
                                                    <p><?php echo $productData[0]->longdescription; ?></p>
                                                    <h4 class="mt-30">Packaging & Delivery</h4>
                                                    <hr class="wp-block-separator is-style-wide" />
                                                    <p>Less lion goodness that euphemistically robin expeditiously bluebird
                                                        smugly scratched far while thus cackled sheepishly rigid after due
                                                        one assenting regarding censorious while occasional or this more
                                                        crane went more as this less much amid overhung anathematic because
                                                        much held one exuberantly sheep goodness so where rat wry well
                                                        concomitantly.</p>
                                                    <p>Scallop or far crud plain remarkably far by thus far iguana lewd
                                                        precociously and and less rattlesnake contrary caustic wow this near
                                                        alas and next and pled the yikes articulate about as less cackled
                                                        dalmatian in much less well jeering for the thanks blindly
                                                        sentimental whimpered less across objectively fanciful grimaced
                                                        wildly some wow and rose jeepers outgrew lugubrious luridly
                                                        irrationally attractively dachshund.</p>
                                                    <h4 class="mt-30">Suggested Use</h4>
                                                    <ul class="product-more-infor mt-30">
                                                        <li>Refrigeration not necessary.</li>
                                                        <li>Stir before serving</li>
                                                    </ul>
                                                    <h4 class="mt-30">Other Ingredients</h4>
                                                    <ul class="product-more-infor mt-30">
                                                        <li>Organic raw pecans, organic raw cashews.</li>
                                                        <li>This butter was produced using a LTG (Low Temperature Grinding)
                                                            process</li>
                                                        <li>Made in machinery that processes tree nuts but does not process
                                                            peanuts, gluten, dairy or soy</li>
                                                    </ul>
                                                    <h4 class="mt-30">Warnings</h4>
                                                    <ul class="product-more-infor mt-30">
                                                        <li>Oil separation occurs naturally. May contain pieces of shell.
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="Additional-info">
                                                <table class="font-md">
                                                    <tbody>
                                                        <tr class="stand-up">
                                                            <th>Stand Up</th>
                                                            <td>
                                                                <p>35″L x 24″W x 37-45″H(front to back wheel)</p>
                                                            </td>
                                                        </tr>
                                                        <tr class="folded-wo-wheels">
                                                            <th>Folded (w/o wheels)</th>
                                                            <td>
                                                                <p>32.5″L x 18.5″W x 16.5″H</p>
                                                            </td>
                                                        </tr>
                                                        <tr class="folded-w-wheels">
                                                            <th>Folded (w/ wheels)</th>
                                                            <td>
                                                                <p>32.5″L x 24″W x 18.5″H</p>
                                                            </td>
                                                        </tr>
                                                        <tr class="door-pass-through">
                                                            <th>Door Pass Through</th>
                                                            <td>
                                                                <p>24</p>
                                                            </td>
                                                        </tr>
                                                        <tr class="frame">
                                                            <th>Frame</th>
                                                            <td>
                                                                <p>Aluminum</p>
                                                            </td>
                                                        </tr>
                                                        <tr class="weight-wo-wheels">
                                                            <th>Weight (w/o wheels)</th>
                                                            <td>
                                                                <p>20 LBS</p>
                                                            </td>
                                                        </tr>
                                                        <tr class="weight-capacity">
                                                            <th>Weight Capacity</th>
                                                            <td>
                                                                <p>60 LBS</p>
                                                            </td>
                                                        </tr>
                                                        <tr class="width">
                                                            <th>Width</th>
                                                            <td>
                                                                <p>24″</p>
                                                            </td>
                                                        </tr>
                                                        <tr class="handle-height-ground-to-handle">
                                                            <th>Handle height (ground to handle)</th>
                                                            <td>
                                                                <p>37-45″</p>
                                                            </td>
                                                        </tr>
                                                        <tr class="wheels">
                                                            <th>Wheels</th>
                                                            <td>
                                                                <p>12″ air / wide track slick tread</p>
                                                            </td>
                                                        </tr>
                                                        <tr class="seat-back-height">
                                                            <th>Seat back height</th>
                                                            <td>
                                                                <p>21.5″</p>
                                                            </td>
                                                        </tr>
                                                        <tr class="head-room-inside-canopy">
                                                            <th>Head room (inside canopy)</th>
                                                            <td>
                                                                <p>25″</p>
                                                            </td>
                                                        </tr>
                                                        <tr class="pa_color">
                                                            <th>Color</th>
                                                            <td>
                                                                <p>Black, Blue, Red, White</p>
                                                            </td>
                                                        </tr>
                                                        <tr class="pa_size">
                                                            <th>Size</th>
                                                            <td>
                                                                <p>M, S</p>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane fade" id="Vendor-info">
                                                <div class="vendor-logo d-flex mb-30">
                                                    <img src="assets/imgs/vendor/vendor-18.svg" alt="" />
                                                    <div class="vendor-name ml-15">
                                                        <h6>
                                                            <a href="vendor-details-2.php">Noodles Co.</a>
                                                        </h6>
                                                        <div class="product-rate-cover text-end">
                                                            <div class="product-rate d-inline-block">
                                                                <div class="product-rating" style="width: 90%"></div>
                                                            </div>
                                                            <span class="font-small ml-5 text-muted"> (32 reviews)</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul class="contact-infor mb-50">
                                                    <li><img src="assets/imgs/theme/icons/icon-location.svg" alt="" /><strong>Address: </strong> <span>5171 W Campbell Ave
                                                            undefined Kent, Utah 53127 United States</span></li>
                                                    <li><img src="assets/imgs/theme/icons/icon-contact.svg" alt="" /><strong>Contact Seller:</strong><span>(+91) -
                                                            540-025-553</span></li>
                                                </ul>
                                                <div class="d-flex mb-55">
                                                    <div class="mr-30">
                                                        <p class="text-brand font-xs">Rating</p>
                                                        <h4 class="mb-0">92%</h4>
                                                    </div>
                                                    <div class="mr-30">
                                                        <p class="text-brand font-xs">Ship on time</p>
                                                        <h4 class="mb-0">100%</h4>
                                                    </div>
                                                    <div>
                                                        <p class="text-brand font-xs">Chat response</p>
                                                        <h4 class="mb-0">89%</h4>
                                                    </div>
                                                </div>
                                                <p>
                                                    Noodles & Company is an American fast-casual restaurant that offers
                                                    international and American noodle dishes and pasta in addition to soups
                                                    and salads. Noodles & Company was founded in 1995 by Aaron Kennedy and
                                                    is headquartered in Broomfield, Colorado. The company went public in
                                                    2013 and recorded a $457 million revenue in 2017.In late 2018, there
                                                    were 460 Noodles & Company locations across 29 states and Washington,
                                                    D.C.
                                                </p>
                                            </div>
                                            <div class="tab-pane fade" id="Reviews">
                                                <!--Comments-->
                                                <div class="comments-area">
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <h4 class="mb-30">Customer questions & answers</h4>
                                                            <div class="comment-list">
                                                                <div class="single-comment justify-content-between d-flex mb-30">
                                                                    <div class="user justify-content-between d-flex">
                                                                        <div class="thumb text-center">
                                                                            <img src="assets/imgs/blog/author-2.png" alt="" />
                                                                            <a href="#" class="font-heading text-brand">Sienna</a>
                                                                        </div>
                                                                        <div class="desc">
                                                                            <div class="d-flex justify-content-between mb-10">
                                                                                <div class="d-flex align-items-center">
                                                                                    <span class="font-xs text-muted">December
                                                                                        4, 2022 at 3:12 pm </span>
                                                                                </div>
                                                                                <div class="product-rate d-inline-block">
                                                                                    <div class="product-rating" style="width: 100%"></div>
                                                                                </div>
                                                                            </div>
                                                                            <p class="mb-10">Lorem ipsum dolor sit amet,
                                                                                consectetur adipisicing elit. Delectus,
                                                                                suscipit exercitationem accusantium
                                                                                obcaecati quos voluptate nesciunt facilis
                                                                                itaque modi commodi dignissimos sequi
                                                                                repudiandae minus ab deleniti totam officia
                                                                                id incidunt? <a href="#" class="reply">Reply</a>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="single-comment justify-content-between d-flex mb-30 ml-30">
                                                                    <div class="user justify-content-between d-flex">
                                                                        <div class="thumb text-center">
                                                                            <img src="assets/imgs/blog/author-3.png" alt="" />
                                                                            <a href="#" class="font-heading text-brand">Brenna</a>
                                                                        </div>
                                                                        <div class="desc">
                                                                            <div class="d-flex justify-content-between mb-10">
                                                                                <div class="d-flex align-items-center">
                                                                                    <span class="font-xs text-muted">December
                                                                                        4, 2022 at 3:12 pm </span>
                                                                                </div>
                                                                                <div class="product-rate d-inline-block">
                                                                                    <div class="product-rating" style="width: 80%"></div>
                                                                                </div>
                                                                            </div>
                                                                            <p class="mb-10">Lorem ipsum dolor sit amet,
                                                                                consectetur adipisicing elit. Delectus,
                                                                                suscipit exercitationem accusantium
                                                                                obcaecati quos voluptate nesciunt facilis
                                                                                itaque modi commodi dignissimos sequi
                                                                                repudiandae minus ab deleniti totam officia
                                                                                id incidunt? <a href="#" class="reply">Reply</a>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="single-comment justify-content-between d-flex">
                                                                    <div class="user justify-content-between d-flex">
                                                                        <div class="thumb text-center">
                                                                            <img src="assets/imgs/blog/author-4.png" alt="" />
                                                                            <a href="#" class="font-heading text-brand">Gemma</a>
                                                                        </div>
                                                                        <div class="desc">
                                                                            <div class="d-flex justify-content-between mb-10">
                                                                                <div class="d-flex align-items-center">
                                                                                    <span class="font-xs text-muted">December
                                                                                        4, 2022 at 3:12 pm </span>
                                                                                </div>
                                                                                <div class="product-rate d-inline-block">
                                                                                    <div class="product-rating" style="width: 80%"></div>
                                                                                </div>
                                                                            </div>
                                                                            <p class="mb-10">Lorem ipsum dolor sit amet,
                                                                                consectetur adipisicing elit. Delectus,
                                                                                suscipit exercitationem accusantium
                                                                                obcaecati quos voluptate nesciunt facilis
                                                                                itaque modi commodi dignissimos sequi
                                                                                repudiandae minus ab deleniti totam officia
                                                                                id incidunt? <a href="#" class="reply">Reply</a>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <h4 class="mb-30">Customer reviews</h4>
                                                            <div class="d-flex mb-30">
                                                                <div class="product-rate d-inline-block mr-15">
                                                                    <div class="product-rating" style="width: 90%"></div>
                                                                </div>
                                                                <h6>4.8 out of 5</h6>
                                                            </div>
                                                            <div class="progress">
                                                                <span>5 star</span>
                                                                <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">50%
                                                                </div>
                                                            </div>
                                                            <div class="progress">
                                                                <span>4 star</span>
                                                                <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%
                                                                </div>
                                                            </div>
                                                            <div class="progress">
                                                                <span>3 star</span>
                                                                <div class="progress-bar" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100">45%
                                                                </div>
                                                            </div>
                                                            <div class="progress">
                                                                <span>2 star</span>
                                                                <div class="progress-bar" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">65%
                                                                </div>
                                                            </div>
                                                            <div class="progress mb-30">
                                                                <span>1 star</span>
                                                                <div class="progress-bar" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">85%
                                                                </div>
                                                            </div>
                                                            <a href="#" class="font-xs text-muted">How are ratings
                                                                calculated?</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--comment form-->
                                                <div class="comment-form">
                                                    <h4 class="mb-15">Add a review</h4>
                                                    <div class="product-rate d-inline-block mb-30"></div>
                                                    <div class="row">
                                                        <div class="col-lg-8 col-md-12">
                                                            <form class="form-contact comment_form" action="#" id="commentForm">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <textarea class="form-control w-100" name="comment" id="comment" cols="30" rows="9" placeholder="Write Comment"></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <input class="form-control" name="name" id="name" type="text" placeholder="Name" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <input class="form-control" name="email" id="email" type="email" placeholder="Email" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <input class="form-control" name="website" id="website" type="text" placeholder="Website" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <button type="submit" class="button button-contactForm">Submit
                                                                        Review</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-60">
                                    <div class="col-12">
                                        <h2 class="section-title style-1 mb-30">Related products</h2>
                                    </div>
                                    <div class="col-12">
                                        <div class="row related-products">
                                            <?php

                                            foreach ($relatedProductData as $productData) {

                                                include 'component/product-component.php';
                                            }
                                            ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 primary-sidebar sticky-sidebar mt-30">
                            <?php
                            include_once('component/category-component.php');
                            ?>
                            <!-- Fillter By Price -->
                            <?php
                            include_once('component/filter-component.php');
                            ?>
                            <!-- Product sidebar Widget -->
                            <?php
                            include_once('component/newproduct-component.php');
                            ?>
                            <div class="banner-img wow fadeIn mb-lg-0 animated d-lg-block d-none">
                                <img src="assets/imgs/banner/banner-11.png" alt="" />
                                <div class="banner-text">
                                    <span>Oganic</span>
                                    <h4>
                                        Save 17% <br />
                                        on <span class="text-brand">Oganic</span><br />
                                        Juice
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php
} else {
?>
    <main class="main page-404">
        <div class="page-content pt-150 pb-150">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-10 col-md-12 m-auto text-center">
                        <p class="mb-20"><img src="assets/imgs/page/page-404.png" alt="" class="hover-up" /></p>
                        <h1 class="display-2 mb-30">Page Not Found</h1>
                        <p class="font-lg text-grey-700 mb-30">
                            The link you clicked may be broken or the page may have been removed.<br />
                            visit the <a href="index.php"> <span> Homepage</span></a> or <a href="page-contact.php"><span>Contact us</span></a> about the problem
                        </p>
                        <a class="btn btn-default submit-auto-width font-xs hover-up mt-30" href="index.php"><i class="fi-rs-home mr-5"></i> Back To Home Page</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php
}
include 'inc/footer.php';

?>