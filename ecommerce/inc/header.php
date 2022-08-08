<?php
include 'function.php';
if (session_id() == '') {
    session_start();
}
list($categoryFirstHalf, $categorySecondHalf) = array_chunk($categoryItemData, ceil(count($categoryItemData) / 2));

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
    <link rel="shortcut icon" type="image/x-icon" href="assets/imgs/theme/favicon.png" />
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
            <!-- <div class="close-btn">
                    <span><i class="fi-rs-cross-small"></i></span>
                </div> -->
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
                                    <li>Trendy 25silver jewelry, save up to 35% off today</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4">
                        <div class="header-info header-info-right">
                            <ul>
                                <li>Need help? Call Us: 0185-989-3939</li>
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
                                <input type="text" id="searchItemData" onkeyup="searchItem(this.value)" placeholder="Search for items..." /><span style="margin-top: 11px;padding-right: 15px;cursor: pointer;" onclick="clearSearch()">x</span>
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
                                    <a href="#"><img src="assets/imgs/theme/icons/icon-facebook-white.svg" alt=""></a>
                                    <a href="#"><img src="assets/imgs/theme/icons/icon-facebook-white.svg" alt=""></a>
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
                                            <img class="svgInject" alt="Nest" src="assets/imgs/theme/icons/icon-user-active.svg" />
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
                                                <li><a href="page-account.php"><i class="fi fi-rs-user mr-10"></i>My Account</a></li>
                                                <li><a href="logout.php"><i class="fi fi-rs-sign-out mr-10"></i>Sign out</a></li>
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
                                <!-- <div class="more_slide_open" style="display: none">
                                    <div class="d-flex categori-dropdown-inner">
                                        <ul>
                                            <li>
                                                <a href="products.php"> <img src="assets/imgs/theme/icons/icon-1.svg" alt="" />Milks and Dairies</a>
                                            </li>
                                            <li>
                                                <a href="products.php"> <img src="assets/imgs/theme/icons/icon-2.svg" alt="" />Clothing & beauty</a>
                                            </li>
                                        </ul>
                                        <ul class="end">
                                            <li>
                                                <a href="products.php"> <img src="assets/imgs/theme/icons/icon-3.svg" alt="" />Wines & Drinks</a>
                                            </li>
                                            <li>
                                                <a href="products.php"> <img src="assets/imgs/theme/icons/icon-4.svg" alt="" />Fresh Seafood</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="more_categories"><span class="icon"></span> <span class="heading-sm-1">Show more...</span></div> -->
                            </div>
                        </div>
                        <div class="main-menu main-menu-padding-1 main-menu-lh-2 d-none d-lg-block font-heading">
                            <nav>
                                <ul>
                                    <!-- <li class="hot-deals"><img src="assets/imgs/theme/icons/icon-hot.svg" -->
                                    <!-- alt="hot deals" /><a href="products.php">Deals</a></li> -->
                                    <!-- <li>
                                        <a class="active" href="index.php">Home <i class="fi-rs-angle-down"></i></a>
                                        <ul class="sub-menu">
                                            <li><a href="index.php">Home 1</a></li>
                                            <li><a href="index-2.php">Home 2</a></li>
                                            <li><a href="index-3.php">Home 3</a></li>
                                            <li><a href="index-4.php">Home 4</a></li>
                                            <li><a href="index-5.php">Home 5</a></li>
                                            <li><a href="index-6.php">Home 6</a></li>
                                        </ul>
                                    </li> -->
                                    <li>
                                        <a href="page-about.php">About</a>
                                    </li>
                                    <!-- <li>
                                        <a href="products.php">Shop <i class="fi-rs-angle-down"></i></a>
                                        <ul class="sub-menu">
                                            <li><a href="products.php">Shop Grid – Right Sidebar</a></li>
                                            <li><a href="shop-grid-left.php">Shop Grid – Left Sidebar</a></li>
                                            <li><a href="shop-list-right.php">Shop List – Right Sidebar</a></li>
                                            <li><a href="shop-list-left.php">Shop List – Left Sidebar</a></li>
                                            <li><a href="shop-fullwidth.php">Shop - Wide</a></li>
                                            <li>
                                                <a href="#">Single Product <i class="fi-rs-angle-right"></i></a>
                                                <ul class="level-menu">
                                                    <li><a href="product.php">Product – Right Sidebar</a>
                                                    </li>
                                                    <li><a href="shop-product-left.php">Product – Left Sidebar</a></li>
                                                    <li><a href="shop-product-full.php">Product – No sidebar</a></li>
                                                    <li><a href="shop-product-vendor.php">Product – Vendor Infor</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li><a href="shop-filter.php">Shop – Filter</a></li>
                                            <li><a href="shop-wishlist.php">Shop – Wishlist</a></li>
                                            <li><a href="shop-cart.php">Shop – Cart</a></li>
                                            <li><a href="shop-checkout.php">Shop – Checkout</a></li>
                                            <li><a href="shop-compare.php">Shop – Compare</a></li>
                                            <li>
                                                <a href="#">Shop Invoice<i class="fi-rs-angle-right"></i></a>
                                                <ul class="level-menu">
                                                    <li><a href="shop-invoice-1.php">Shop Invoice 1</a></li>
                                                    <li><a href="shop-invoice-2.php">Shop Invoice 2</a></li>
                                                    <li><a href="shop-invoice-3.php">Shop Invoice 3</a></li>
                                                    <li><a href="shop-invoice-4.php">Shop Invoice 4</a></li>
                                                    <li><a href="shop-invoice-5.php">Shop Invoice 5</a></li>
                                                    <li><a href="shop-invoice-6.php">Shop Invoice 6</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li> -->
                                    <!-- <li>
                                        <a href="#">Vendors <i class="fi-rs-angle-down"></i></a>
                                        <ul class="sub-menu">
                                            <li><a href="vendors-grid.php">Vendors Grid</a></li>
                                            <li><a href="vendors-list.php">Vendors List</a></li>
                                            <li><a href="vendor-details-1.php">Vendor Details 01</a></li>
                                            <li><a href="vendor-details-2.php">Vendor Details 02</a></li>
                                            <li><a href="vendor-dashboard.php">Vendor Dashboard</a></li>
                                            <li><a href="vendor-guide.php">Vendor Guide</a></li>
                                        </ul>
                                    </li> -->
                                    <!-- <li class="position-static">
                                        <a href="#">Mega menu <i class="fi-rs-angle-down"></i></a>
                                        <ul class="mega-menu">
                                            <li class="sub-mega-menu sub-mega-menu-width-22">
                                                <a class="menu-title" href="#">Fruit & Vegetables</a>
                                                <ul>
                                                    <li><a href="product.php">Meat & Poultry</a></li>
                                                    <li><a href="product.php">Fresh Vegetables</a></li>
                                                    <li><a href="product.php">Herbs & Seasonings</a></li>
                                                    <li><a href="product.php">Cuts & Sprouts</a></li>
                                                    <li><a href="product.php">Exotic Fruits & Veggies</a>
                                                    </li>
                                                    <li><a href="product.php">Packaged Produce</a></li>
                                                </ul>
                                            </li>
                                            <li class="sub-mega-menu sub-mega-menu-width-22">
                                                <a class="menu-title" href="#">Breakfast & Dairy</a>
                                                <ul>
                                                    <li><a href="product.php">Milk & Flavoured Milk</a></li>
                                                    <li><a href="product.php">Butter and Margarine</a></li>
                                                    <li><a href="product.php">Eggs Substitutes</a></li>
                                                    <li><a href="product.php">Marmalades</a></li>
                                                    <li><a href="product.php">Sour Cream</a></li>
                                                    <li><a href="product.php">Cheese</a></li>
                                                </ul>
                                            </li>
                                            <li class="sub-mega-menu sub-mega-menu-width-22">
                                                <a class="menu-title" href="#">Meat & Seafood</a>
                                                <ul>
                                                    <li><a href="product.php">Breakfast Sausage</a></li>
                                                    <li><a href="product.php">Dinner Sausage</a></li>
                                                    <li><a href="product.php">Chicken</a></li>
                                                    <li><a href="product.php">Sliced Deli Meat</a></li>
                                                    <li><a href="product.php">Wild Caught Fillets</a></li>
                                                    <li><a href="product.php">Crab and Shellfish</a></li>
                                                </ul>
                                            </li>
                                            <li class="sub-mega-menu sub-mega-menu-width-34">
                                                <div class="menu-banner-wrap">
                                                    <a href="product.php"><img src="assets/imgs/banner/banner-menu.png" alt="Nest" /></a>
                                                    <div class="menu-banner-content">
                                                        <h4>Hot deals</h4>
                                                        <h3>
                                                            Don't miss<br />
                                                            Trending
                                                        </h3>
                                                        <div class="menu-banner-price">
                                                            <span class="new-price text-success">Save to 50%</span>
                                                        </div>
                                                        <div class="menu-banner-btn">
                                                            <a href="product.php">Shop now</a>
                                                        </div>
                                                    </div>
                                                    <div class="menu-banner-discount">
                                                        <h3>
                                                            <span>25%</span>
                                                            off
                                                        </h3>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </li> -->
                                    <!-- <li>
                                        <a href="blog-category-grid.php">Blog <i class="fi-rs-angle-down"></i></a>
                                        <ul class="sub-menu">
                                            <li><a href="blog-category-grid.php">Blog Category Grid</a></li>
                                            <li><a href="blog-category-list.php">Blog Category List</a></li>
                                            <li><a href="blog-category-big.php">Blog Category Big</a></li>
                                            <li><a href="blog-category-fullwidth.php">Blog Category Wide</a></li>
                                            <li>
                                                <a href="#">Single Post <i class="fi-rs-angle-right"></i></a>
                                                <ul class="level-menu level-menu-modify">
                                                    <li><a href="blog-post-left.php">Left Sidebar</a></li>
                                                    <li><a href="blog-post-right.php">Right Sidebar</a></li>
                                                    <li><a href="blog-post-fullwidth.php">No Sidebar</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#">Pages <i class="fi-rs-angle-down"></i></a>
                                        <ul class="sub-menu">
                                            <li><a href="page-about.php">About Us</a></li>
                                            <li><a href="page-contact.php">Contact</a></li>
                                            <li><a href="page-account.php">My Account</a></li>
                                            <li><a href="page-login.php">Login</a></li>
                                            <li><a href="page-register.php">Register</a></li>
                                            <li><a href="page-forgot-password.php">Forgot password</a></li>
                                            <li><a href="page-reset-password.php">Reset password</a></li>
                                            <li><a href="page-purchase-guide.php">Purchase Guide</a></li>
                                            <li><a href="page-privacy-policy.php">Privacy Policy</a></li>
                                            <li><a href="page-terms.php">Terms of Service</a></li>
                                            <li><a href="page-404.php">404 Page</a></li>
                                        </ul>
                                    </li> -->
                                    <!-- <li>
                                        <a href="page-contact.php">Contact</a>
                                    </li> -->
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="hotline d-none d-lg-flex">
                        <img src="assets/imgs/theme/icons/icon-headphone.svg" alt="hotline" />
                        <p>0185-989-3939<span>24/7 Custome support Center</span></p>
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
                            <!-- <div class="header-action-icon-2">
                                <a href="shop-wishlist.php">
                                    <img alt="Nest" src="assets/imgs/theme/icons/icon-heart.svg" />
                                    <span class="pro-count white">4</span>
                                </a>
                            </div> -->
                            <div class="header-action-icon-2" onmouseover="cartPopUp()">
                                <a href="#"><img src="assets/imgs/theme/icons/icon-facebook-white.svg" alt=""></a>
                                <a href="#"><img src="assets/imgs/theme/icons/icon-facebook-white.svg" alt=""></a>
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
                    <a href="index.php"><img src="assets/imgs/theme/logo.svg" alt="logo" /></a>
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
                        <!-- <button type="submit"><i class="fi-rs-search"></i></button> -->
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

                                    foreach ($categoryItemData as $categoryValueMobile) {
                                    ?>
                                        <li><a href="products.php?category_id=<?php echo $categoryValueMobile->categoryID;  ?>"><?php echo $categoryValueMobile->categoryName; ?></a>
                                        </li>

                                    <?php
                                    }
                                    ?>
                                </ul>
                            </li>

                            <!-- <li class="menu-item-has-children">
                                <a href="#">Vendors</a>
                                <ul class="dropdown">
                                    <li><a href="vendors-grid.php">Vendors Grid</a></li>
                                    <li><a href="vendors-list.php">Vendors List</a></li>
                                    <li><a href="vendor-details-1.php">Vendor Details 01</a></li>
                                    <li><a href="vendor-details-2.php">Vendor Details 02</a></li>
                                    <li><a href="vendor-dashboard.php">Vendor Dashboard</a></li>
                                    <li><a href="vendor-guide.php">Vendor Guide</a></li>
                                </ul>
                            </li> -->
                            <!-- <li class="menu-item-has-children">
                                <a href="#">Mega menu</a>
                                <ul class="dropdown">
                                    <li class="menu-item-has-children">
                                        <a href="#">Women's Fashion</a>
                                        <ul class="dropdown">
                                            <li><a href="product.php">Dresses</a></li>
                                            <li><a href="product.php">Blouses & Shirts</a></li>
                                            <li><a href="product.php">Hoodies & Sweatshirts</a></li>
                                            <li><a href="product.php">Women's Sets</a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item-has-children">
                                        <a href="#">Men's Fashion</a>
                                        <ul class="dropdown">
                                            <li><a href="product.php">Jackets</a></li>
                                            <li><a href="product.php">Casual Faux Leather</a></li>
                                            <li><a href="product.php">Genuine Leather</a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item-has-children">
                                        <a href="#">Technology</a>
                                        <ul class="dropdown">
                                            <li><a href="product.php">Gaming Laptops</a></li>
                                            <li><a href="product.php">Ultraslim Laptops</a></li>
                                            <li><a href="product.php">Tablets</a></li>
                                            <li><a href="product.php">Laptop Accessories</a></li>
                                            <li><a href="product.php">Tablet Accessories</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li> -->
                            <!-- <li class="menu-item-has-children">
                                <a href="blog-category-fullwidth.php">Blog</a>
                                <ul class="dropdown">
                                    <li><a href="blog-category-grid.php">Blog Category Grid</a></li>
                                    <li><a href="blog-category-list.php">Blog Category List</a></li>
                                    <li><a href="blog-category-big.php">Blog Category Big</a></li>
                                    <li><a href="blog-category-fullwidth.php">Blog Category Wide</a></li>
                                    <li class="menu-item-has-children">
                                        <a href="#">Single Product Layout</a>
                                        <ul class="dropdown">
                                            <li><a href="blog-post-left.php">Left Sidebar</a></li>
                                            <li><a href="blog-post-right.php">Right Sidebar</a></li>
                                            <li><a href="blog-post-fullwidth.php">No Sidebar</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li class="menu-item-has-children">
                                <a href="#">Pages</a>
                                <ul class="dropdown">
                                    <li><a href="page-about.php">About Us</a></li>
                                    <li><a href="page-contact.php">Contact</a></li>
                                    <li><a href="page-account.php">My Account</a></li>
                                    <li><a href="page-login.php">Login</a></li>
                                    <li><a href="page-register.php">Register</a></li>
                                    <li><a href="page-forgot-password.php">Forgot password</a></li>
                                    <li><a href="page-reset-password.php">Reset password</a></li>
                                    <li><a href="page-purchase-guide.php">Purchase Guide</a></li>
                                    <li><a href="page-privacy-policy.php">Privacy Policy</a></li>
                                    <li><a href="page-terms.php">Terms of Service</a></li>
                                    <li><a href="page-404.php">404 Page</a></li>
                                </ul>
                            </li>
                            <li class="menu-item-has-children">
                                <a href="#">Language</a>
                                <ul class="dropdown">
                                    <li><a href="#">English</a></li>
                                    <li><a href="#">French</a></li>
                                    <li><a href="#">German</a></li>
                                    <li><a href="#">Spanish</a></li>
                                </ul>
                            </li> -->
                        </ul>
                    </nav>
                    <!-- mobile menu end -->
                </div>
                <div class="mobile-header-info-wrap">
                    <!-- <div class="single-mobile-header-info">
                        <a href="page-contact.php"><i class="fi-rs-marker"></i> Our location </a>
                    </div> -->

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
                    <a href="#"><i class="fi-rs-headphones"></i>(+880) 18598-93939 </a>
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
    <!--End header-->
    <!-- fixed cart -->
    <!-- <div class="wrapper">
        <div class="inner-fixed">
            <div class="item" id="item">
                <i class="fi-rs-shopping-cart mr-5 color-danger"></i><br>
                <span id="numberofCartItem"></span> Items
            </div>
            <div class="total">
                ৳ <span id="total_Taka"></span>
            </div>
        </div>
    </div> -->