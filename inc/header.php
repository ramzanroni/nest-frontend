<?php
include 'function.php';
include_once('apiendpoint.php');
if (session_id() == '') {
    session_start();
}
list($categoryFirstHalf, $categorySecondHalf) = array_chunk($categoryItemData, ceil(count($categoryItemData) / 2));

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => APIENDPOINT . "category-find.php",
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
    $category = json_decode($response);
    $categoryItems = (array) $category->data->menu->items;
    $mainMenuList = (array)$category->data->menu->parents;
    $mainMenu = $mainMenuList[0];
    // print_r($categoryItems);
    // print_r($categoryItems[1]->groupname);
}
?>
<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8" />
    <title>NEO Bazaar</title>
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:title" content="" />
    <meta property="og:type" content="" />
    <meta property="og:url" content="" />
    <meta property="og:image" content="" />
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/imgs/theme/favicon.svg" />
    <!-- Template CSS -->
    <link rel="stylesheet" href="assets/css/plugins/slider-range.css" />

    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="assets/css/external.css" />
    <?php echo $pageLavelCss; ?>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script> -->
    <script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
    <script src="./js/index.js"></script>
    <style></style>
</head>

<body>
    <?php
    if (!isset($_COOKIE['first'])) {
        setcookie('first', true, time() + (86400 * 30), "/");
    ?>
        <!-- Modal -->
        <div class="modal fade custom-modal" id="onloadModal" tabindex="-1" aria-labelledby="onloadModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-body">
                        <div class="deal" style="background-image: url('assets/imgs/banner/popup-1.png')">
                            <div class="deal-top">
                                <h6 class="mb-10 text-brand-2">Deal of the Day</h6>
                            </div>
                            <div class="deal-content detail-info">
                                <h4 class="product-title"><a href="shop-product-right.html" class="text-heading">Organic fruit for your family's health</a></h4>
                                <div class="clearfix product-price-cover">
                                    <div class="product-price primary-color float-left">
                                        <span class="current-price text-brand">$38</span>
                                        <span>
                                            <span class="save-price font-md color3 ml-15">26% Off</span>
                                            <span class="old-price font-md ml-15">$52</span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
    <div class="modal fade custom-modal" id="userlogin" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body">
                    <div class="row" id="modalDiv">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <header class="header-area header-style-1 header-height-2">
        <div class="alertSuccess hide">
            <span class="fi-rs-exclamation-circle"></span>
            <span class="msg" id="errorMessageSuccess"></span>
        </div>
        <div class="mobile-promotion">
            <span>Grand opening, <strong>up to 15%</strong> off all items. Only <strong>3 days</strong> left</span>
        </div>
        <div class="header-top header-top-ptb-1 d-none d-lg-block">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-3 col-lg-4">
                        <div class="header-info">
                            <ul>
                                <?php
                                if (getPhone() != '') {
                                ?>
                                    <li><a href="page-account.php">My Account</a></li>
                                    <li><a href="page-account.php">Order Tracking</a></li>
                                    <li><a href="page-about.php">About Us</a></li>

                                <?php
                                } else {
                                ?>
                                    <li><a onclick="loginUserFororder()" data-bs-toggle="modal" data-bs-target="#userlogin">My Account</a></li>
                                    <li> <a onclick="loginUserFororder()" data-bs-toggle="modal" data-bs-target="#userlogin">Order Tracking</a></li>
                                    <li><a href="page-about.php">About Us</a></li>
                                <?php
                                }
                                ?>


                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-4">
                        <div class="text-center">
                            <div id="news-flash" class="d-inline-block">
                                <ul>
                                    <li>100% Secure delivery without contacting the courier</li>
                                    <li>Supper Value Deals - Save more with coupons</li>
                                    <li>Trendy 25silver jewelry, save up 35% off today</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4">
                        <div class="header-info header-info-right">
                            <ul>
                                <li>Need help? Call Us: + 1800 900</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="web-sticky" class="header-middle header-middle-ptb-1 d-none d-lg-block sticky-bar">

            <div class="container">
                <div class="header-wrap header-space-between position-relative">
                    <div class="logo logo-width-1">
                        <a href="index.php"><img src="assets/imgs/theme/logo.png" alt="logo" /></a>
                    </div>
                    <div class="header-right">
                        <div class="search-style-2">
                            <form method="POST" id="allProduct" onsubmit="event.preventDefault(); viewAllProduct();">
                                <select class="select-active" id="category_name">
                                    <option value="">All Categories</option>

                                    <?php
                                    foreach ($categoryItemData as $categoryValue) {
                                    ?>
                                        <option value="<?php echo $categoryValue->categoryID; ?>">
                                            <?php echo $categoryValue->categoryName; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <input type="text" id="searchItemData" onkeyup="searchItem(this.value)" placeholder="Search for items..." /><span class="cross-btn" onclick="clearSearch()">x</span>
                            </form>
                        </div>
                        <div class="search-box col-12" id="searchBox">
                        </div>
                        <div class="header-action-right">
                            <div class="header-action-2">
                                <div class="mobile-social-icon">
                                    <a style="background: #365493;" href="https://www.facebook.com/neobazaar20"><img src="assets/imgs/theme/icons/icon-facebook-white.svg" alt=""></a>
                                    <a style="background: #CB2027;" href="https://www.youtube.com/channel/UCCppmqczruogeIoCALgpWIQ"><img src="assets/imgs/theme/icons/icon-youtube-white.svg" alt=""></a>
                                </div>
                                <div class="header-action-icon-2" onmouseover="cartPopUp()">
                                    <a class="mini-cart-icon" href="shop-cart.php">
                                        <img alt="Nest" src="assets/imgs/theme/icons/icon-cart.svg" />
                                        <span class="pro-count blue" id="cartCount">0</span>
                                    </a>
                                    <a href="shop-cart.php"><span class="lable">Cart</span></a>
                                    <div class="cart-dropdown-wrap cart-dropdown-hm2" id="cartItem" onmouseleave="removeCssClass()">

                                    </div>
                                </div>
                                <div class="header-action-icon-2">
                                    <?php
                                    if (getPhone() != '') {
                                    ?>
                                        <a href="page-account.php">
                                            <img class="svgInject" alt="Nest" src="assets/imgs/theme/icons/icon-user.svg" />
                                        </a>
                                        <a href="page-account.php"><span class="lable ml-0">Account</span></a>
                                    <?php
                                    } else {
                                    ?>
                                        <a aria-label="Quick view" onclick="loginUserFororder()" data-bs-toggle="modal" data-bs-target="#userlogin"><img class="svgInject" alt="Nest" src="assets/imgs/theme/icons/icon-user.svg" /></i><span class="lable ml-0">Account</span></a>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if (getPhone() != '') {
                                    ?>
                                        <div class="cart-dropdown-wrap cart-dropdown-hm2 account-dropdown">
                                            <ul>
                                                <li><a href="page-account.php"><i class="fi fi-rs-user mr-10"></i>My
                                                        Account</a></li>
                                                <!-- <li><a href="page-account.php"><i class="fi fi-rs-location-alt mr-10"></i>Order Tracking</a></li>><i class="fi fi-rs-settings-sliders mr-10"></i>Setting</a></li>  -->
                                                <li><a href="logout.php"><i class="fi fi-rs-sign-out mr-10"></i>Sign
                                                        out</a></li>
                                            </ul>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="mobile-sticky" class="header-bottom header-bottom-bg-color ">
            <div class="container">
                <div class="header-wrap header-space-between">
                    <div class="logo logo-width-1 d-block d-lg-none">
                        <a href="index.php"><img src="assets/imgs/theme/logo.png" alt="logo" /></a>
                    </div>
                    <div class="header-nav d-none d-lg-flex">
                        <div class="main-categori-wrap d-none d-lg-block">
                            <a class="categories-button-active" href="#">
                                <span class="fi-rs-apps"></span> <span class="et">Browse</span> All Categories
                                <i class="fi-rs-angle-down"></i>
                            </a>
                            <div class="categories-dropdown-wrap categories-dropdown-active-large font-heading">
                                <div class="d-flex categori-dropdown-inner">
                                    <ul>
                                        <?php
                                        foreach ($categoryFirstHalf as $categoryFirstHalfvalue) {
                                        ?>
                                            <li>
                                                <a href="products.php?category_id=<?php echo $categoryFirstHalfvalue->categoryID; ?>">
                                                    <img src="<?php echo $categoryFirstHalfvalue->categoryImg; ?>" alt="" /><?php echo $categoryFirstHalfvalue->categoryName; ?></a>
                                            </li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                    <ul class="end">
                                        <?php
                                        foreach ($categorySecondHalf as $categorySecondHalfvalue) {
                                        ?>
                                            <li>
                                                <a href="products.php?category_id=<?php echo $categorySecondHalfvalue->categoryID;  ?>">
                                                    <img src="<?php echo $categorySecondHalfvalue->categoryImg; ?>" alt="" /><?php echo $categorySecondHalfvalue->categoryName; ?></a>
                                            </li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </div>

                            </div>
                        </div>
                        <div class="main-menu main-menu-padding-1 main-menu-lh-2 d-none d-lg-block font-heading">
                            <nav>
                                <ul>

                                    <li>
                                        <a href="about.php">About</a>
                                    </li>

                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="hotline d-none d-lg-flex">
                        <img src="assets/imgs/theme/icons/icon-headphone.svg" alt="hotline" />
                        <p><a href="tel:01859893939">0185-9893939</a><span>24/7 Support Center</span></p>
                    </div>
                    <div class="header-action-icon-2 d-block d-lg-none">
                        <div class="burger-icon burger-icon-white">
                            <span class="burger-icon-top"></span>
                            <span class="burger-icon-mid"></span>
                            <span class="burger-icon-bottom"></span>
                        </div>
                    </div>

                    <div class="header-action-right d-block d-lg-none">

                        <div class="header-action-2">
                            <!-- <div style="width: 100%;" class="header-action-icon-2 burger-icon">
                                <img style="cursor: pointer;" alt="Nest" src="assets/imgs/theme/icons/icon-search-new.png" />
                            </div> -->
                            <a class="mini-cart-icon burger-icon" style="margin-right: 5px;" href="shop-cart.php">
                                <img alt="Nest" src="assets/imgs/theme/icons/icon-search-new.png" />
                            </a>
                            <div class="header-action-icon-2" onmouseover="cartPopUp()">
                                <a class="mini-cart-icon" href="shop-cart.php">
                                    <img alt="Nest" src="assets/imgs/theme/icons/icon-cart.svg" />
                                    <span class="pro-count blue" id="cartCountMobile">0</span>
                                </a>
                                <a href="shop-cart.php"><span class="lable">Cart</span></a>
                                <div class="cart-dropdown-wrap cart-dropdown-hm2 cartbox" id="cartItemMobile" onmouseleave="removeCssClass()">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- alert -->

    <div class="mobile-header-active mobile-header-wrapper-style">
        <div class="mobile-header-wrapper-inner">
            <div class="mobile-header-top">
                <div class="mobile-header-logo">
                    <a href="index.php"><img src="assets/imgs/theme/logo.png" alt="logo" /></a>
                </div>
                <div class="mobile-menu-close close-style-wrap close-style-position-inherit">
                    <button class="close-style search-close">
                        <i class="icon-top"></i>
                        <i class="icon-bottom"></i>
                    </button>
                </div>
            </div>
            <div class="mobile-header-content-area">
                <div class="mobile-search search-style-3 mobile-header-border">
                    <form action="#">
                        <input type="text" onkeyup="searchProductMobile(this.value)" placeholder="Search for items…" />
                    </form>
                </div>
                <div class="mobile-menu-wrap mobile-header-border search-box-mobile" id="searchResultBox">
                </div>
                <div class="mobile-menu-wrap mobile-header-border">
                    <!-- mobile menu start -->
                    <nav>
                        <ul class="mobile-menu font-heading">
                            <li class="menu-item-has-children">
                                <a>Category</a>
                                <ul class="dropdown">
                                    <?php

                                    foreach ($mainMenu as $categoryValueMobile) {
                                        $secondSubMenu = $mainMenuList[$categoryValueMobile];
                                        if (count($secondSubMenu) > 0) {
                                    ?>
                                            <li class="menu-item-has-children">
                                                <a href="products.php?category_id=<?php echo $categoryValueMobile;  ?>"><?php echo $categoryItems[$categoryValueMobile]->groupname; ?></a>
                                                <ul class="dropdown">
                                                    <?php
                                                    foreach ($secondSubMenu as $secondSubID) {
                                                        $thirdSubMenu = $mainMenuList[$secondSubID];
                                                        if (count($thirdSubMenu) > 0) {
                                                    ?>
                                                            <li class="menu-item-has-children">
                                                                <a href="products.php?category_id=<?php echo $secondSubID;  ?>"><?php echo $categoryItems[$secondSubID]->groupname; ?></a>
                                                                <ul class="dropdown">
                                                                    <?php
                                                                    foreach ($thirdSubMenu as $thirdSubMenuID) {
                                                                    ?>
                                                                        <li><a href="products.php?category_id=<?php echo $thirdSubMenuID;  ?>"><?php echo $categoryItems[$thirdSubMenuID]->groupname; ?></a></li>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </ul>
                                                            </li>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <li><a href="products.php?category_id=<?php echo $secondSubID;  ?>"><?php echo $categoryItems[$secondSubID]->groupname; ?></a>
                                                            </li>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </ul>
                                            </li>
                                        <?php
                                        } else {
                                        ?>
                                            <li><a href="products.php?category_id=<?php echo $categoryValueMobile;  ?>"><?php echo $categoryItems[$categoryValueMobile]->groupname; ?></a>
                                            </li>
                                        <?php
                                        }

                                        ?>

                                    <?php
                                    }
                                    ?>
                                </ul>
                            </li>

                        </ul>
                    </nav>
                    <!-- mobile menu end -->
                </div>
                <div class="mobile-header-info-wrap">

                    <?php
                    if (getPhone() == "") {
                    ?>
                        <div class="single-mobile-header-info">
                            <a aria-label="Quick view" onclick="loginUserFororder()" data-bs-toggle="modal" data-bs-target="#userlogin"><i class="fi-rs-user"></i><span class="lable ml-0">Account</span></a>
                        </div>

                    <?php
                    } else {
                    ?>
                        <div class="single-mobile-header-info">
                            <a href="page-account.php"><i class="fi-rs-user"></i>Account</a>
                        </div>
                        <div class="single-mobile-header-info">
                            <a href="logout.php"><i class="fi-rs-sign-out"></i></i>Sign Out </a>
                        </div>
                    <?php
                    }
                    ?>

                </div>
                <div class="single-mobile-header-info">
                    <a href="#"><i class="fi-rs-headphones"></i>(+01) - 2345 - 6789 </a>
                </div>
            </div>
            <div class="mobile-social-icon mb-50">
                <h6 class="mb-15">Follow Us</h6>
                <a href="#"><img src="assets/imgs/theme/icons/icon-facebook-white.svg" alt="" /></a>
                <a href="#"><img src="assets/imgs/theme/icons/icon-twitter-white.svg" alt="" /></a>
                <a href="#"><img src="assets/imgs/theme/icons/icon-instagram-white.svg" alt="" /></a>
                <a href="#"><img src="assets/imgs/theme/icons/icon-pinterest-white.svg" alt="" /></a>
                <a href="#"><img src="assets/imgs/theme/icons/icon-youtube-white.svg" alt="" /></a>
            </div>
            <div class="site-copyright">Copyright 2022 © Nest. All rights reserved. Powered by AliThemes.</div>
        </div>
    </div>
    </div>