<?php
include 'apidata/dataFetch.php';
include 'inc/header.php';

// single product api data 
if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
    $limit = 2;
    $sortby = "PriceLowtoHigh";
    if ($_COOKIE['product_limit']) {
        $limit = $_COOKIE['product_limit'];
    }
    if ($_COOKIE['sort_by']) {
        $sortby = $_COOKIE['sort_by'];
    }
    $url = 'http://192.168.0.116/neonbazar_api/category_wise_product.php?category_id=' . $category_id . '&limit=' . $limit . '&sort_by=' . $sortby; //url will be here
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
    $categoryInfo = curl_exec($ch);
    curl_close($ch);
    $categoryData = json_decode($categoryInfo);
    $totalProduct = count($categoryData);


    //total item of this category
    $url = 'http://192.168.0.116/neonbazar_api/total_number_of_item_category_wise.php?category_id=' . $category_id;
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
    $categoryItemInfo = curl_exec($ch);
    curl_close($ch);
    $categoryDataCount = json_decode($categoryItemInfo);
    $totalCategoryItem = $categoryDataCount[0]->totalItem;
?>
<main class="main">
    <div class="page-header mt-30 mb-50">
        <div class="container">
            <div class="archive-header">
                <div class="row align-items-center">
                    <div class="col-xl-3">
                        <h1 class="mb-15">Snack</h1>
                        <div class="breadcrumb">
                            <a href="index.php" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                            <span></span> Shop <span></span> Snack
                        </div>
                    </div>
                    <div class="col-xl-9 text-end d-none d-xl-block">
                        <ul class="tags-list">
                            <li class="hover-up">
                                <a href="blog-category-grid.html"><i class="fi-rs-cross mr-10"></i>Cabbage</a>
                            </li>
                            <li class="hover-up active">
                                <a href="blog-category-grid.html"><i class="fi-rs-cross mr-10"></i>Broccoli</a>
                            </li>
                            <li class="hover-up">
                                <a href="blog-category-grid.html"><i class="fi-rs-cross mr-10"></i>Artichoke</a>
                            </li>
                            <li class="hover-up">
                                <a href="blog-category-grid.html"><i class="fi-rs-cross mr-10"></i>Celery</a>
                            </li>
                            <li class="hover-up mr-0">
                                <a href="blog-category-grid.html"><i class="fi-rs-cross mr-10"></i>Spinach</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mb-30" id="myTabContent">
        <div class="row">
            <div class="col-lg-4-5">
                <div class="shop-product-fillter">
                    <div class="totall-product">
                        <p>We found <strong class="text-brand"><?php echo $totalCategoryItem; ?></strong> items for you!
                        </p>
                    </div>
                    <div class="sort-by-product-area">
                        <div class="sort-by-cover mr-10">
                            <div class="sort-by-product-wrap">
                                <div class="sort-by">
                                    <span><i class="fi-rs-apps"></i>Show:</span>
                                </div>
                                <div class="sort-by-dropdown-wrap">
                                    <span> <?php echo $limit; ?> <i class="fi-rs-angle-small-down"></i></span>
                                </div>
                            </div>
                            <input type="hidden" id="getLimit" value="<?php echo $limit; ?>">
                            <div class="sort-by-dropdown">
                                <ul id="limitValue">
                                    <li data-id="2"><a>2</a></li>
                                    <li data-id="3"><a>3</a></li>
                                    <li data-id="4"><a>4</a></li>
                                    <li data-id="5"><a>5</a></li>
                                    <li data-id="All"><a>All</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="sort-by-cover">
                            <div class="sort-by-product-wrap">
                                <div class="sort-by">
                                    <span><i class="fi-rs-apps-sort"></i>Sort by:</span>
                                </div>
                                <div class="sort-by-dropdown-wrap">
                                    <span> <?php echo $sortby; ?> <i class="fi-rs-angle-small-down"></i></span>
                                </div>
                            </div>
                            <input type="hidden" id="catId" value="<?php echo $category_id; ?>">
                            <input type="hidden" id="getSortByValue" value="<?php echo $sortby; ?>">
                            <div class="sort-by-dropdown">
                                <ul id="sortByValue">
                                    <li data-id="PriceLowtoHigh"><a>PriceLowtoHigh</a></li>
                                    <li data-id="PriceHightoLow"><a>PriceHightoLow</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="productItem">
                    <div class="row product-grid">
                        <?php
                            $cartCookiesProduct = json_decode($_COOKIE['shopping_cart']);
                            foreach ($categoryData as $productData) {
                            ?>
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                            <div class="product-cart-wrap mb-30">
                                <div class="product-img-action-wrap">
                                    <div class="product-img product-img-zoom">
                                        <a
                                            href="shop-product-right.php?product_id=<?php echo $productData->stockid; ?>">
                                            <img class="default-img" src="//<?php echo $productData->img; ?>" alt="" />
                                            <!-- <img class="hover-img" src="assets/imgs/shop/product-1-2.jpg" alt="" /> -->
                                        </a>
                                    </div>
                                    <!-- <div class="product-badges product-badges-position product-badges-mrg">
                                        <span class="hot"><?php echo $productData->units; ?></span>
                                    </div> -->
                                </div>
                                <div class="product-content-wrap">
                                    <div class="product-category">
                                        <a href="shop-grid-right.html"><?php echo $productData->category; ?></a>
                                    </div>
                                    <h2><a
                                            href="shop-product-right.php?product_id=<?php echo $productData->stockid; ?>"><?php echo $productData->description; ?></a>
                                    </h2>
                                    <div class="product-rate-cover product-badges">
                                        <span
                                            class="font-small ml-5 text-muted hot"><?php echo $productData->units; ?></span>
                                    </div>
                                    <!-- <div class="product-rate-cover">
                                        <div class="product-rate d-inline-block">
                                            <div class="product-rating" style="width: 90%"></div>
                                        </div>
                                        <span class="font-small ml-5 text-muted"> (4.0)</span>
                                    </div> -->
                                    <div class="product-card-bottom">
                                        <div class="product-price">
                                            <span>৳<?php echo $productData->webprice; ?></span>
                                            <!-- <span class="old-price"><?php echo $item['price']; ?></span> -->
                                        </div>
                                        <?php
                                                $cartProductID = '';
                                                $numberOfItem = '';
                                                foreach ($cartCookiesProduct as $cartKey => $itemValue) {
                                                    if ($itemValue->productID == $productData->stockid) {
                                                        $cartProductID = $itemValue->productID;
                                                        $numberOfItem = $itemValue->productQuantity;
                                                    }
                                                }
                                                if ($cartProductID == '') {
                                                ?>
                                        <div id="item_<?= $productData->stockid ?>">
                                            <div class="add-cart">
                                                <a class="add"
                                                    onclick="firstAddtoCart(<?php echo $productData->stockid; ?>,'<?php echo $productData->description; ?>',<?php echo $productData->webprice; ?>,1,'<?php echo $productData->img; ?>' )"><i
                                                        class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                            </div>
                                        </div>
                                        <?php
                                                } else {
                                                ?>
                                        <div class="col-12" id="item_<?= $productData->stockid ?>">
                                            <input type="hidden" id="getItem_<?php echo $productData->stockid; ?>"
                                                value="<?php echo $numberOfItem; ?>">
                                            <div class="col-10 float-end after-cart">
                                                <div class="col-2 float-end increment"
                                                    onclick="CartItemChange('increment', <?php echo $productData->stockid; ?>)">
                                                    <a><i class="fi-rs-plus"></i></a>
                                                </div>
                                                <div class="col-4 float-end middle">
                                                    <a><i class="fi-rs-shopping-cart"></i>
                                                        <span
                                                            id="cartCount_<?php echo $productData->stockid; ?>"><?php echo $numberOfItem; ?></span>
                                                    </a>
                                                </div>
                                                <div class="col-2 float-end add decrement"
                                                    onclick="CartItemChange('decrement', <?php echo $productData->stockid; ?>)">
                                                    <a><i class="fi-rs-minus"></i></a>
                                                </div>
                                            </div>
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
                    </div>
                    <!--product grid-->
                    <div class="pagination-area mt-20 mb-20">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-start">

                                <?php
                                    $currentPage = 1;
                                    $nextPage = $currentPage + 1;
                                    $previousPage = $currentPage - 1;
                                    $perpageItem = $limit;
                                    $numberOfpage = ceil($totalCategoryItem / $perpageItem);
                                    ?>
                                <li class="page-item">
                                    <a class="page-link" onclick="pagination(1)"><i
                                            class="fi-rs-arrow-small-left"></i></a>
                                </li>
                                <?php
                                    for ($i = 1; $i <= $numberOfpage; $i++) {

                                    ?>
                                <li id="pagination_<?php echo $i; ?>" class="page-item <?php if ($i == 1) {
                                                                                                    echo 'active';
                                                                                                } ?>">
                                    <a class="page-link" onclick="pagination(<?php echo $i; ?>)"><?php echo $i; ?></a>
                                </li>
                                <?php
                                    }
                                    ?>
                                <li class="page-item">
                                    <a class="page-link" onclick="pagination(2)"><i
                                            class="fi-rs-arrow-small-right"></i></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <section class="section-padding pb-5">
                    <div class="section-title">
                        <h3 class="">Deals Of The Day</h3>
                        <a class="show-all" href="shop-grid-right.html">
                            All Deals
                            <i class="fi-rs-angle-right"></i>
                        </a>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="product-cart-wrap style-2">
                                <div class="product-img-action-wrap">
                                    <div class="product-img">
                                        <a href="shop-product-right.html">
                                            <img src="assets/imgs/banner/banner-5.png" alt="" />
                                        </a>
                                    </div>
                                </div>
                                <div class="product-content-wrap">
                                    <div class="deals-countdown-wrap">
                                        <div class="deals-countdown" data-countdown="2025/03/25 00:00:00"></div>
                                    </div>
                                    <div class="deals-content">
                                        <h2><a href="shop-product-right.html">Seeds of Change Organic Quinoa,
                                                Brown</a>
                                        </h2>
                                        <div class="product-rate-cover">
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" style="width: 90%"></div>
                                            </div>
                                            <span class="font-small ml-5 text-muted"> (4.0)</span>
                                        </div>
                                        <div>
                                            <span class="font-small text-muted">By <a
                                                    href="vendor-details-1.html">NestFood</a></span>
                                        </div>
                                        <div class="product-card-bottom">
                                            <div class="product-price">
                                                <span>$32.85</span>
                                                <span class="old-price">$33.8</span>
                                            </div>
                                            <div class="add-cart">
                                                <a class="add" href="shop-cart.html"><i
                                                        class="fi-rs-shopping-cart mr-5"></i>Add </a>
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
                                        <a href="shop-product-right.html">
                                            <img src="assets/imgs/banner/banner-6.png" alt="" />
                                        </a>
                                    </div>
                                </div>
                                <div class="product-content-wrap">
                                    <div class="deals-countdown-wrap">
                                        <div class="deals-countdown" data-countdown="2026/04/25 00:00:00"></div>
                                    </div>
                                    <div class="deals-content">
                                        <h2><a href="shop-product-right.html">Perdue Simply Smart Organics
                                                Gluten</a>
                                        </h2>
                                        <div class="product-rate-cover">
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" style="width: 90%"></div>
                                            </div>
                                            <span class="font-small ml-5 text-muted"> (4.0)</span>
                                        </div>
                                        <div>
                                            <span class="font-small text-muted">By <a href="vendor-details-1.html">Old
                                                    El Paso</a></span>
                                        </div>
                                        <div class="product-card-bottom">
                                            <div class="product-price">
                                                <span>$24.85</span>
                                                <span class="old-price">$26.8</span>
                                            </div>
                                            <div class="add-cart">
                                                <a class="add" href="shop-cart.html"><i
                                                        class="fi-rs-shopping-cart mr-5"></i>Add </a>
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
                                        <a href="shop-product-right.html">
                                            <img src="assets/imgs/banner/banner-7.png" alt="" />
                                        </a>
                                    </div>
                                </div>
                                <div class="product-content-wrap">
                                    <div class="deals-countdown-wrap">
                                        <div class="deals-countdown" data-countdown="2027/03/25 00:00:00"></div>
                                    </div>
                                    <div class="deals-content">
                                        <h2><a href="shop-product-right.html">Signature Wood-Fired Mushroom</a></h2>
                                        <div class="product-rate-cover">
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" style="width: 80%"></div>
                                            </div>
                                            <span class="font-small ml-5 text-muted"> (3.0)</span>
                                        </div>
                                        <div>
                                            <span class="font-small text-muted">By <a
                                                    href="vendor-details-1.html">Progresso</a></span>
                                        </div>
                                        <div class="product-card-bottom">
                                            <div class="product-price">
                                                <span>$12.85</span>
                                                <span class="old-price">$13.8</span>
                                            </div>
                                            <div class="add-cart">
                                                <a class="add" href="shop-cart.html"><i
                                                        class="fi-rs-shopping-cart mr-5"></i>Add </a>
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
                                        <a href="shop-product-right.html">
                                            <img src="assets/imgs/banner/banner-8.png" alt="" />
                                        </a>
                                    </div>
                                </div>
                                <div class="product-content-wrap">
                                    <div class="deals-countdown-wrap">
                                        <div class="deals-countdown" data-countdown="2025/02/25 00:00:00"></div>
                                    </div>
                                    <div class="deals-content">
                                        <h2><a href="shop-product-right.html">Simply Lemonade with Raspberry
                                                Juice</a>
                                        </h2>
                                        <div class="product-rate-cover">
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" style="width: 80%"></div>
                                            </div>
                                            <span class="font-small ml-5 text-muted"> (3.0)</span>
                                        </div>
                                        <div>
                                            <span class="font-small text-muted">By <a
                                                    href="vendor-details-1.html">Yoplait</a></span>
                                        </div>
                                        <div class="product-card-bottom">
                                            <div class="product-price">
                                                <span>$15.85</span>
                                                <span class="old-price">$16.8</span>
                                            </div>
                                            <div class="add-cart">
                                                <a class="add" href="shop-cart.html"><i
                                                        class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col-lg-1-5 primary-sidebar sticky-sidebar">
                <div class="sidebar-widget widget-category-2 mb-30">
                    <h5 class="section-title style-1 mb-30">Category</h5>
                    <ul>
                        <?php

                            foreach ($categoryItemData as $categoryValue) {
                            ?>
                        <li>
                            <a href="shop-grid-right.php?category_id=<?php echo $categoryValue->categoryID; ?>">
                                <img src="//<?php echo $categoryValue->categoryImg; ?>"
                                    alt="" /><?php echo $categoryValue->categoryName; ?></a><span
                                class="count"><?php echo $categoryValue->item; ?></span>
                        </li>
                        <?php
                            }
                            ?>
                        <!-- <li>
                            <a href="shop-grid-right.html"> <img src="assets/imgs/theme/icons/category-1.svg"
                                    alt="" />Milks & Dairies</a><span class="count">30</span>
                        </li>
                        <li>
                            <a href="shop-grid-right.html"> <img src="assets/imgs/theme/icons/category-2.svg"
                                    alt="" />Clothing</a><span class="count">35</span>
                        </li>
                        <li>
                            <a href="shop-grid-right.html"> <img src="assets/imgs/theme/icons/category-3.svg"
                                    alt="" />Pet Foods </a><span class="count">42</span>
                        </li>
                        <li>
                            <a href="shop-grid-right.html"> <img src="assets/imgs/theme/icons/category-4.svg"
                                    alt="" />Baking material</a><span class="count">68</span>
                        </li>
                        <li>
                            <a href="shop-grid-right.html"> <img src="assets/imgs/theme/icons/category-5.svg"
                                    alt="" />Fresh Fruit</a><span class="count">87</span>
                        </li> -->
                    </ul>
                </div>
                <!-- Fillter By Price -->
                <div class="sidebar-widget price_range range mb-30">
                    <h5 class="section-title style-1 mb-30">Fill by price</h5>
                    <div class="price-filter">
                        <div class="price-filter-inner">
                            <div id="slider-range" class="mb-20"></div>
                            <div class="d-flex justify-content-between">
                                <div class="caption">From: <strong id="slider-range-value1" class="text-brand"></strong>
                                </div>
                                <div class="caption">To: <strong id="slider-range-value2" class="text-brand"></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="list-group">
                        <div class="list-group-item mb-10 mt-10">
                            <label class="fw-900">Color</label>
                            <div class="custome-checkbox">
                                <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox1"
                                    value="" />
                                <label class="form-check-label" for="exampleCheckbox1"><span>Red (56)</span></label>
                                <br />
                                <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox2"
                                    value="" />
                                <label class="form-check-label" for="exampleCheckbox2"><span>Green
                                        (78)</span></label>
                                <br />
                                <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox3"
                                    value="" />
                                <label class="form-check-label" for="exampleCheckbox3"><span>Blue
                                        (54)</span></label>
                            </div>
                            <label class="fw-900 mt-15">Item Condition</label>
                            <div class="custome-checkbox">
                                <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox11"
                                    value="" />
                                <label class="form-check-label" for="exampleCheckbox11"><span>New
                                        (1506)</span></label>
                                <br />
                                <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox21"
                                    value="" />
                                <label class="form-check-label" for="exampleCheckbox21"><span>Refurbished
                                        (27)</span></label>
                                <br />
                                <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox31"
                                    value="" />
                                <label class="form-check-label" for="exampleCheckbox31"><span>Used
                                        (45)</span></label>
                            </div>
                        </div>
                    </div>
                    <a href="shop-grid-right.html" class="btn btn-sm btn-default"><i class="fi-rs-filter mr-5"></i>
                        Fillter</a>
                </div>
                <!-- Product sidebar Widget -->
                <div class="sidebar-widget product-sidebar mb-30 p-30 bg-grey border-radius-10">
                    <h5 class="section-title style-1 mb-30">New products</h5>
                    <div class="single-post clearfix">
                        <div class="image">
                            <img src="assets/imgs/shop/thumbnail-3.jpg" alt="#" />
                        </div>
                        <div class="content pt-10">
                            <h5><a href="shop-product-detail.html">Chen Cardigan</a></h5>
                            <p class="price mb-0 mt-5">$99.50</p>
                            <div class="product-rate">
                                <div class="product-rating" style="width: 90%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="single-post clearfix">
                        <div class="image">
                            <img src="assets/imgs/shop/thumbnail-4.jpg" alt="#" />
                        </div>
                        <div class="content pt-10">
                            <h6><a href="shop-product-detail.html">Chen Sweater</a></h6>
                            <p class="price mb-0 mt-5">$89.50</p>
                            <div class="product-rate">
                                <div class="product-rating" style="width: 80%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="single-post clearfix">
                        <div class="image">
                            <img src="assets/imgs/shop/thumbnail-5.jpg" alt="#" />
                        </div>
                        <div class="content pt-10">
                            <h6><a href="shop-product-detail.html">Colorful Jacket</a></h6>
                            <p class="price mb-0 mt-5">$25</p>
                            <div class="product-rate">
                                <div class="product-rating" style="width: 60%"></div>
                            </div>
                        </div>
                    </div>
                </div>
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
</main>

<?php
}
if (isset($_GET['category']) && isset($_GET['product_name'])) {

    $categoryId = $_GET['category'];
    $itemString = $_GET['product_name'];
    $url = 'http://192.168.0.116/neonbazar_api/search_product.php?product_name=' . urlencode($itemString) . '&category=' . $categoryId; //url will be here
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
    $categoryInfo = curl_exec($ch);
    curl_close($ch);
    $searchData = json_decode($categoryInfo);
    $cartCookiesProduct = json_decode($_COOKIE['shopping_cart']);
    // print_r($searchData);
?>
<main class="main">
    <div class="container mb-30 mt-10" id="myTabContent">
        <div class="row">
            <div class="col-lg-4-5">
                <div id="productItem">
                    <div class="row product-grid">
                        <?php
                            $cartCookiesProduct = json_decode($_COOKIE['shopping_cart']);
                            foreach ($searchData as $productData) {
                            ?>
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                            <div class="product-cart-wrap mb-30">
                                <div class="product-img-action-wrap">
                                    <div class="product-img product-img-zoom">
                                        <a
                                            href="shop-product-right.php?product_id=<?php echo $productData->stockid; ?>">
                                            <img class="default-img" src="//<?php echo $productData->img; ?>" alt="" />
                                            <!-- <img class="hover-img" src="assets/imgs/shop/product-1-2.jpg" alt="" /> -->
                                        </a>
                                    </div>
                                    <!-- <div class="product-badges product-badges-position product-badges-mrg">
                                        <span class="hot"><?php echo $productData->units; ?></span>
                                    </div> -->
                                </div>
                                <div class="product-content-wrap">
                                    <div class="product-category">
                                        <a href="shop-grid-right.html"><?php echo $productData->category; ?></a>
                                    </div>
                                    <h2><a
                                            href="shop-product-right.php?product_id=<?php echo $productData->stockid; ?>"><?php echo $productData->description; ?></a>
                                    </h2>
                                    <div class="product-rate-cover product-badges">
                                        <span
                                            class="font-small ml-5 text-muted hot"><?php echo $productData->units; ?></span>
                                    </div>
                                    <!-- <div class="product-rate-cover">
                                        <div class="product-rate d-inline-block">
                                            <div class="product-rating" style="width: 90%"></div>
                                        </div>
                                        <span class="font-small ml-5 text-muted"> (4.0)</span>
                                    </div> -->
                                    <div class="product-card-bottom">
                                        <div class="product-price">
                                            <span>৳<?php echo $productData->webprice; ?></span>
                                            <!-- <span class="old-price"><?php echo $item['price']; ?></span> -->
                                        </div>
                                        <?php
                                                $cartProductID = '';
                                                $numberOfItem = '';
                                                foreach ($cartCookiesProduct as $cartKey => $itemValue) {
                                                    if ($itemValue->productID == $productData->stockid) {
                                                        $cartProductID = $itemValue->productID;
                                                        $numberOfItem = $itemValue->productQuantity;
                                                    }
                                                }
                                                if ($cartProductID == '') {
                                                ?>
                                        <div id="item_<?= $productData->stockid ?>">
                                            <div class="add-cart">
                                                <a class="add"
                                                    onclick="firstAddtoCart(<?php echo $productData->stockid; ?>,'<?php echo $productData->description; ?>',<?php echo $productData->webprice; ?>,1,'<?php echo $productData->img; ?>' )"><i
                                                        class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                            </div>
                                        </div>
                                        <?php
                                                } else {
                                                ?>
                                        <div class="col-12" id="item_<?= $productData->stockid ?>">
                                            <input type="hidden" id="getItem_<?php echo $productData->stockid; ?>"
                                                value="<?php echo $numberOfItem; ?>">
                                            <div class="col-10 float-end after-cart">
                                                <div class="col-2 float-end increment"
                                                    onclick="CartItemChange('increment', <?php echo $productData->stockid; ?>)">
                                                    <a><i class="fi-rs-plus"></i></a>
                                                </div>
                                                <div class="col-4 float-end middle">
                                                    <a><i class="fi-rs-shopping-cart"></i>
                                                        <span
                                                            id="cartCount_<?php echo $productData->stockid; ?>"><?php echo $numberOfItem; ?></span>
                                                    </a>
                                                </div>
                                                <div class="col-2 float-end add decrement"
                                                    onclick="CartItemChange('decrement', <?php echo $productData->stockid; ?>)">
                                                    <a><i class="fi-rs-minus"></i></a>
                                                </div>
                                            </div>
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
                    </div>
                </div>
            </div>
            <div class="col-lg-1-5 primary-sidebar sticky-sidebar">
                <div class="sidebar-widget widget-category-2 mb-30">
                    <h5 class="section-title style-1 mb-30">Category</h5>
                    <ul>
                        <?php

                            foreach ($categoryItemData as $categoryValue) {
                            ?>
                        <li>
                            <a href="shop-grid-right.php?category_id=<?php echo $categoryValue->categoryID; ?>">
                                <img src="//<?php echo $categoryValue->categoryImg; ?>"
                                    alt="" /><?php echo $categoryValue->categoryName; ?></a><span
                                class="count"><?php echo $categoryValue->item; ?></span>
                        </li>
                        <?php
                            }
                            ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
}
include 'inc/footer.php';

?>