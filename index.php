﻿<?php
$pageLavelCss = '';
$pageLavelCss = ' 
';
include 'apidata/dataFetch.php';
include 'inc/header.php';
include 'config.php';
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    // get profile info
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $email =  $google_account_info->email;
    $name =  $google_account_info->name;
    $emailID = $google_account_info->id;
    echo "<script type='text/javascript'>$(document).ready(function () {checkEmail('$email', '$name', '$emailID');});</script>";

    // now you can use this profile info to create account in your website and make user logged in.
}
?>
<main class="main">
    <div class="container mb-30" id="myTabContent">
        <div class="row flex-row-reverse">
            <div class="col-lg-4-5">
                <section class="home-slider position-relative mb-30">
                    <div class="home-slide-cover mt-30">
                        <div class="hero-slider-1 style-4 dot-style-1 dot-style-1-position-1">
                            <div class="single-hero-slider single-animation-wrap" style="background-image: url(assets/imgs/slider/slider-1.png)">
                                <div class="slider-content">
                                    <h1 class="display-2 mb-40">
                                        Don’t miss amazing<br />
                                        grocery deals
                                    </h1>
                                    <p class="mb-65">Sign up for the daily newsletter</p>
                                    <form class="form-subcriber d-flex">
                                        <input type="email" placeholder="Your emaill address" />
                                        <button class="btn" type="submit">Subscribe</button>
                                    </form>
                                </div>
                            </div>
                            <div class="single-hero-slider single-animation-wrap" style="background-image: url(assets/imgs/slider/slider-2.png)">
                                <div class="slider-content">
                                    <h1 class="display-2 mb-40">
                                        Fresh Vegetables<br />
                                        Big discount
                                    </h1>
                                    <p class="mb-65">Save up to 50% off on your first order</p>
                                    <form class="form-subcriber d-flex">
                                        <input type="email" placeholder="Your emaill address" />
                                        <button class="btn" type="submit">Subscribe</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="slider-arrow hero-slider-1-arrow"></div>
                    </div>
                </section>
                <!--End hero-->
                <section class="product-tabs section-padding position-relative">
                    <div class="section-title style-2">
                        <h3>Popular Products</h3>
                        <style>

                        </style>
                        <nav id="menu-container" class="arrow">
                            <div id="btn-nav-previous"><span class="slider-btn slider-prev slick-arrow"><i class="fi-rs-arrow-small-left"></i></span></div>
                            <div id="btn-nav-next"><span class="slider-btn slider-next slick-arrow"><i class="fi-rs-arrow-small-right"></i></span></div>
                            <div class="menu-inner-box">
                                <div class="menu">
                                    <!-- <a class="menu-item" href="#">Test</a> -->
                                    <a class="menu-item active-menu" id="nav_0" onclick="categoryProduct(0)">All</a>
                                    <?php
                                    foreach ($categoryItemData as $categoryValueitem) {
                                        if ($categoryValueitem->parent == 0) {
                                    ?>
                                            <a class="menu-item" id="nav_<?php echo $categoryValueitem->categoryID; ?>" onclick="categoryProduct(<?php echo $categoryValueitem->categoryID; ?>)"> <?php echo $categoryValueitem->categoryName; ?></a>
                                    <?php
                                        }
                                    }
                                    ?>

                                </div>
                            </div>
                        </nav>

                        <!-- <ul class="nav nav-tabs links" id="myTab" role="tablist">
                            <div id="btn-nav-previous">&lt;</div>
                            <div id="btn-nav-next">&gt;</div>
                            <div class="menu-inner-box">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="nav-tab-one" data-bs-toggle="tab" data-bs-target="#tab-one" onclick="categoryProduct(0)" type="button" role="tab" aria-controls="tab-one" aria-selected="true">All</button>
                            </li>

                            <?php
                            foreach ($categoryItemData as $categoryValueitem) {
                                if ($categoryValueitem->parent == 0) {
                            ?>

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="nav-tab-two" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab-two" aria-selected="false" onclick="categoryProduct(<?php echo $categoryValueitem->categoryID; ?>)">
                                            <?php echo $categoryValueitem->categoryName; ?>
                                        </button>
                                    </li>
                            <?php
                                }
                            }
                            ?>
                        </ul> -->
                        <script>
                            $('#btn-nav-previous').click(function() {
                                $(".menu-inner-box").animate({
                                    scrollLeft: "-=100px"
                                });
                            });

                            $('#btn-nav-next').click(function() {
                                $(".menu-inner-box").animate({
                                    scrollLeft: "+=100px"
                                });
                            });
                        </script>
                    </div>
                    <!--End nav-tabs-->
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="tab-one" role="tabpanel" aria-labelledby="tab-one">
                            <div class="row product-grid-4" id="productItemField">

                                <?php
                                foreach ($productsArr as $productData) {

                                    include 'component/product-component.php';
                                }
                                ?>
                            </div>
                            <!--End product-grid-4-->
                        </div>

                    </div>
                    <!--End tab-content-->
                </section>
                <!--Products Tabs-->
                <!-- <section class="section-padding pb-5">
                    <div class="section-title">
                        <h3 class="">Deals Of The Day</h3>
                        <a class="show-all" href="products.php">
                            All Deals
                            <i class="fi-rs-angle-right"></i>
                        </a>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="product-cart-wrap style-2">
                                <div class="product-img-action-wrap">
                                    <div class="product-img">
                                        <a href="product.php">
                                            <img src="assets/imgs/banner/banner-5.png" alt="" />
                                        </a>
                                    </div>
                                </div>
                                <div class="product-content-wrap">
                                    <div class="deals-countdown-wrap">
                                        <div class="deals-countdown" data-countdown="2025/03/25 00:00:00"></div>
                                    </div>
                                    <div class="deals-content">
                                        <h2><a href="product.php">Seeds of Change Organic Quinoa, Brown</a>
                                        </h2>
                                        <div class="product-rate-cover">
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" style="width: 90%"></div>
                                            </div>
                                            <span class="font-small ml-5 text-muted"> (4.0)</span>
                                        </div>
                                        <div>
                                            <span class="font-small text-muted">By <a href="vendor-details-1.php">NestFood</a></span>
                                        </div>
                                        <div class="product-card-bottom">
                                            <div class="product-price">
                                                <span>$32.85</span>
                                                <span class="old-price">$33.8</span>
                                            </div>
                                            <div class="add-cart">
                                                <a class="add" href="shop-cart.php"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="product-cart-wrap style-2">
                                <div class="product-img-action-wrap">
                                    <div class="product-img">
                                        <a href="product.php">
                                            <img src="assets/imgs/banner/banner-6.png" alt="" />
                                        </a>
                                    </div>
                                </div>
                                <div class="product-content-wrap">
                                    <div class="deals-countdown-wrap">
                                        <div class="deals-countdown" data-countdown="2026/04/25 00:00:00"></div>
                                    </div>
                                    <div class="deals-content">
                                        <h2><a href="product.php">Perdue Simply Smart Organics Gluten</a>
                                        </h2>
                                        <div class="product-rate-cover">
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" style="width: 90%"></div>
                                            </div>
                                            <span class="font-small ml-5 text-muted"> (4.0)</span>
                                        </div>
                                        <div>
                                            <span class="font-small text-muted">By <a href="vendor-details-1.php">Old
                                                    El Paso</a></span>
                                        </div>
                                        <div class="product-card-bottom">
                                            <div class="product-price">
                                                <span>$24.85</span>
                                                <span class="old-price">$26.8</span>
                                            </div>
                                            <div class="add-cart">
                                                <a class="add" href="shop-cart.php"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 d-none d-lg-block">
                            <div class="product-cart-wrap style-2">
                                <div class="product-img-action-wrap">
                                    <div class="product-img">
                                        <a href="product.php">
                                            <img src="assets/imgs/banner/banner-7.png" alt="" />
                                        </a>
                                    </div>
                                </div>
                                <div class="product-content-wrap">
                                    <div class="deals-countdown-wrap">
                                        <div class="deals-countdown" data-countdown="2027/03/25 00:00:00"></div>
                                    </div>
                                    <div class="deals-content">
                                        <h2><a href="product.php">Signature Wood-Fired Mushroom</a></h2>
                                        <div class="product-rate-cover">
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" style="width: 80%"></div>
                                            </div>
                                            <span class="font-small ml-5 text-muted"> (3.0)</span>
                                        </div>
                                        <div>
                                            <span class="font-small text-muted">By <a href="vendor-details-1.php">Progresso</a></span>
                                        </div>
                                        <div class="product-card-bottom">
                                            <div class="product-price">
                                                <span>$12.85</span>
                                                <span class="old-price">$13.8</span>
                                            </div>
                                            <div class="add-cart">
                                                <a class="add" href="shop-cart.php"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 d-none d-xl-block">
                            <div class="product-cart-wrap style-2">
                                <div class="product-img-action-wrap">
                                    <div class="product-img">
                                        <a href="product.php">
                                            <img src="assets/imgs/banner/banner-8.png" alt="" />
                                        </a>
                                    </div>
                                </div>
                                <div class="product-content-wrap">
                                    <div class="deals-countdown-wrap">
                                        <div class="deals-countdown" data-countdown="2025/02/25 00:00:00"></div>
                                    </div>
                                    <div class="deals-content">
                                        <h2><a href="product.php">Simply Lemonade with Raspberry Juice</a>
                                        </h2>
                                        <div class="product-rate-cover">
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" style="width: 80%"></div>
                                            </div>
                                            <span class="font-small ml-5 text-muted"> (3.0)</span>
                                        </div>
                                        <div>
                                            <span class="font-small text-muted">By <a href="vendor-details-1.php">Yoplait</a></span>
                                        </div>
                                        <div class="product-card-bottom">
                                            <div class="product-price">
                                                <span>$15.85</span>
                                                <span class="old-price">$16.8</span>
                                            </div>
                                            <div class="add-cart">
                                                <a class="add" href="shop-cart.php"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section> -->
                <!--End Deals-->
                <section class="banners">
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="banner-img">
                                <img src="assets/imgs/banner/banner-1.png" alt="" />
                                <div class="banner-text">
                                    <h4>
                                        Everyday Fresh & <br />Clean with Our<br />
                                        Products
                                    </h4>
                                    <a href="products.php" class="btn btn-xs">Shop Now <i class="fi-rs-arrow-small-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="banner-img">
                                <img src="assets/imgs/banner/banner-2.png" alt="" />
                                <div class="banner-text">
                                    <h4>
                                        Make your Breakfast<br />
                                        Healthy and Easy
                                    </h4>
                                    <a href="products.php" class="btn btn-xs">Shop Now <i class="fi-rs-arrow-small-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 d-md-none d-lg-flex">
                            <div class="banner-img mb-sm-0">
                                <img src="assets/imgs/banner/banner-3.png" alt="" />
                                <div class="banner-text">
                                    <h4>The best Organic <br />Products Online</h4>
                                    <a href="products.php" class="btn btn-xs">Shop Now <i class="fi-rs-arrow-small-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--End banners-->
            </div>
            <div class="col-lg-1-5 primary-sidebar sticky-sidebar pt-30">
                <?php include_once('component/category-component.php'); ?>
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
    <section class="popular-categories section-padding">
        <div class="container">
            <div class="section-title">
                <div class="title">
                    <h3>Shop by Categories</h3>
                </div>
                <div class="slider-arrow slider-arrow-2 flex-right carausel-8-columns-arrow" id="carausel-8-columns-arrows"></div>
            </div>
            <div class="carausel-8-columns-cover position-relative">
                <div class="carausel-8-columns" id="carausel-8-columns">
                    <?php
                    foreach ($categoryItemData as $categoryItemDataValue) {
                    ?>
                        <div class="card-1">
                            <figure class="img-hover-scale overflow-hidden">
                                <a href="products.php?category_id=
                                <?php echo $categoryItemDataValue->categoryID; ?>"><img src="<?php if ($categoryItemDataValue->categoryImg != '') {
                                                                                                    echo '//' . $categoryItemDataValue->categoryImg;
                                                                                                } else {
                                                                                                    echo './assets/imgs/product.png';;
                                                                                                }
                                                                                                ?>" alt="" /></a>
                            </figure>
                            <h6>
                                <a href="products.php?category_id=<?php echo $categoryItemDataValue->categoryID; ?>"><?php echo $categoryItemDataValue->categoryName; ?></a>
                            </h6>
                        </div>

                    <?php
                    }

                    ?>

                </div>
            </div>
        </div>
    </section>
    <!--End category slider-->
    <?php include 'component/our-products.php'; ?>
    <!--End 4 columns-->
</main>
<?php include 'inc/footer.php'; ?>