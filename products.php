<?php
include 'apidata/dataFetch.php';
include 'inc/header.php';
include 'inc/apiendpoint.php';

// category product api data 
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
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT .  "product.php?category_id=" . $category_id . "&limit=" . $limit . "&start=1&sort_by=" . $sortby,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "postman-token: 048401f8-a5b1-86ce-ba6f-ffd0d94ce8b3"
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
        // $totalProduct = $cateoryProduct->data->rows_returned;
        $categoryName = $cateoryProduct->data->products[0]->category;
    }

    // total product 
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT .  "product.php?category_id=" . $category_id . "&limit=All&start=1&sort_by=" . $sortby,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "postman-token: 048401f8-a5b1-86ce-ba6f-ffd0d94ce8b3"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $cateoryProduct = json_decode($response);
        $totalProduct = $cateoryProduct->data->rows_returned;
    }

?>
    <main class="main">
        <div class="page-header mt-30 mb-50">
            <div class="container">
                <div class="archive-header">
                    <div class="row align-items-center">
                        <div class="col-xl-3">
                            <h1 class="mb-15"> <?php echo $categoryName; ?></h1>
                            <div class="breadcrumb">
                                <a href="index.php" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                                <span></span> Shop<span></span> <?php echo $categoryName; ?>
                            </div>
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
                            <p>We found <strong class="text-brand"><?php echo $totalProduct; ?></strong> items for you!
                            </p>
                        </div>
                        <div class="sort-by-product-area">
                            <div class="sort-by-cover mr-10">
                                <div class="sort-by-product-wrap">
                                    <div class="sort-by">
                                        <span><i class="fi-rs-apps"></i>Show:</span>
                                    </div>
                                    <div class="sort-by-dropdown-wrap">
                                        <span><span id="pageLimit"> <?php echo $limit; ?> </span><i class="fi-rs-angle-small-down"></i></span>
                                    </div>
                                </div>
                                <input type="hidden" id="getLimit" value="<?php echo $limit; ?>">
                                <div class="sort-by-dropdown">
                                    <ul id="limitValue">
                                        <li data-id="2"><a>2</a></li>
                                        <li data-id="3"><a>3</a></li>
                                        <li data-id="4"><a>4</a></li>
                                        <li data-id="5"><a>5</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="sort-by-cover">
                                <div class="sort-by-product-wrap">
                                    <div class="sort-by">
                                        <span><i class="fi-rs-apps-sort"></i>Sort by:</span>
                                    </div>
                                    <div class="sort-by-dropdown-wrap">
                                        <span> <span id="sortBy"><?php echo $sortby; ?> </span><i class="fi-rs-angle-small-down"></i></span>
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
                                                <a href="product.php?product_id=<?php echo $productData->stockid; ?>">
                                                    <img class="default-img" src="//<?php echo $productData->img; ?>" alt="" />
                                                    <img class="hover-img" src="//<?php echo $productData->img; ?>" alt="" />
                                                </a>
                                            </div>
                                        </div>
                                        <div class="product-content-wrap">
                                            <div class="product-category">
                                                <a href="product.php?product_id=<?php echo $productData->category_id; ?>"><?php echo $productData->category; ?></a>
                                            </div>
                                            <h2><a href="product.php?product_id=<?php echo $productData->stockid; ?>"><?php echo $productData->description; ?></a>
                                            </h2>
                                            <div class="product-rate-cover product-badges">
                                                <span class="font-small ml-5 text-muted hot"><?php echo $productData->units; ?></span>
                                            </div>
                                            <div class="product-card-bottom">
                                                <div class="product-price">
                                                    <span>৳<?php echo $productData->webprice; ?></span>
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
                                                            <a class="add" onclick="firstAddtoCart(<?php echo $productData->stockid; ?>,'<?php echo $productData->description; ?>',<?php echo $productData->webprice; ?>,1,'<?php echo $productData->img; ?>' )"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                                        </div>
                                                    </div>
                                                <?php
                                                } else {
                                                ?>
                                                    <div class="col-12" id="item_<?= $productData->stockid ?>">
                                                        <input type="hidden" id="getItem_<?php echo $productData->stockid; ?>" value="<?php echo $numberOfItem; ?>">
                                                        <div class="col-10 float-end after-cart">
                                                            <div class="col-2 float-end increment" onclick="CartItemChange('increment', <?php echo $productData->stockid; ?>)">
                                                                <a><i class="fi-rs-plus"></i></a>
                                                            </div>
                                                            <div class="col-4 float-end middle">
                                                                <a><i class="fi-rs-shopping-cart"></i>
                                                                    <span id="cartCount_<?php echo $productData->stockid; ?>"><?php echo $numberOfItem; ?></span>
                                                                </a>
                                                            </div>
                                                            <div class="col-2 float-end add decrement" onclick="CartItemChange('decrement', <?php echo $productData->stockid; ?>)">
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
                                    $numberOfpage = ceil($totalProduct / $perpageItem);
                                    if ($numberOfpage <= 5 && $numberOfpage > 1) {
                                    ?>
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
                                            <a class="page-link" onclick="pagination(2)"><i class="fi-rs-arrow-small-right"></i></a>
                                        </li>
                                    <?php
                                    } elseif ($numberOfpage > 5) {
                                    ?>
                                        <?php

                                        for ($i = 1; $i <= 5; $i++) {

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
                                            <a class="page-link" onclick="pagination(2)"><i class="fi-rs-arrow-small-right"></i></a>
                                        </li>
                                    <?php
                                    }

                                    ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <section class="section-padding pb-5">
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
                                            <a href="product.html">
                                                <img src="assets/imgs/banner/banner-5.png" alt="" />
                                            </a>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="deals-countdown-wrap">
                                            <div class="deals-countdown" data-countdown="2025/03/25 00:00:00"></div>
                                        </div>
                                        <div class="deals-content">
                                            <h2><a href="product.html">Seeds of Change Organic Quinoa,
                                                    Brown</a>
                                            </h2>
                                            <div class="product-rate-cover">
                                                <div class="product-rate d-inline-block">
                                                    <div class="product-rating" style="width: 90%"></div>
                                                </div>
                                                <span class="font-small ml-5 text-muted"> (4.0)</span>
                                            </div>
                                            <div>
                                                <span class="font-small text-muted">By <a href="vendor-details-1.html">NestFood</a></span>
                                            </div>
                                            <div class="product-card-bottom">
                                                <div class="product-price">
                                                    <span>$32.85</span>
                                                    <span class="old-price">$33.8</span>
                                                </div>
                                                <div class="add-cart">
                                                    <a class="add" href="shop-cart.html"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
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
                                            <a href="product.html">
                                                <img src="assets/imgs/banner/banner-6.png" alt="" />
                                            </a>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="deals-countdown-wrap">
                                            <div class="deals-countdown" data-countdown="2026/04/25 00:00:00"></div>
                                        </div>
                                        <div class="deals-content">
                                            <h2><a href="product.html">Perdue Simply Smart Organics
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
                                                    <a class="add" href="shop-cart.html"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
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
                                            <a href="product.html">
                                                <img src="assets/imgs/banner/banner-7.png" alt="" />
                                            </a>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="deals-countdown-wrap">
                                            <div class="deals-countdown" data-countdown="2027/03/25 00:00:00"></div>
                                        </div>
                                        <div class="deals-content">
                                            <h2><a href="product.html">Signature Wood-Fired Mushroom</a></h2>
                                            <div class="product-rate-cover">
                                                <div class="product-rate d-inline-block">
                                                    <div class="product-rating" style="width: 80%"></div>
                                                </div>
                                                <span class="font-small ml-5 text-muted"> (3.0)</span>
                                            </div>
                                            <div>
                                                <span class="font-small text-muted">By <a href="vendor-details-1.html">Progresso</a></span>
                                            </div>
                                            <div class="product-card-bottom">
                                                <div class="product-price">
                                                    <span>$12.85</span>
                                                    <span class="old-price">$13.8</span>
                                                </div>
                                                <div class="add-cart">
                                                    <a class="add" href="shop-cart.html"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
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
                                            <a href="product.html">
                                                <img src="assets/imgs/banner/banner-8.png" alt="" />
                                            </a>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="deals-countdown-wrap">
                                            <div class="deals-countdown" data-countdown="2025/02/25 00:00:00"></div>
                                        </div>
                                        <div class="deals-content">
                                            <h2><a href="product.html">Simply Lemonade with Raspberry
                                                    Juice</a>
                                            </h2>
                                            <div class="product-rate-cover">
                                                <div class="product-rate d-inline-block">
                                                    <div class="product-rating" style="width: 80%"></div>
                                                </div>
                                                <span class="font-small ml-5 text-muted"> (3.0)</span>
                                            </div>
                                            <div>
                                                <span class="font-small text-muted">By <a href="vendor-details-1.html">Yoplait</a></span>
                                            </div>
                                            <div class="product-card-bottom">
                                                <div class="product-price">
                                                    <span>$15.85</span>
                                                    <span class="old-price">$16.8</span>
                                                </div>
                                                <div class="add-cart">
                                                    <a class="add" href="shop-cart.html"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
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
                    <?php include_once('component/category-component.php');
                    include_once('component/newproduct-component.php');
                    ?>
                    <!-- Product sidebar Widget -->

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
if (isset($_GET['product_name'])) {

    $categoryId = $_GET['category'];
    $itemString = $_GET['product_name'];

    if ($categoryId == "") {
        $url = 'product.php?product_name=' . urlencode($itemString);
    } else {
        $url = 'product.php?product_name=' . urlencode($itemString) . '&category=' . $categoryId; //url will be here
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
            "cache-control: no-cache",
            "postman-token: 4acdffb1-1f44-c966-4053-240a068adcd1"
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
                                include 'component/product-component.php';
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
                                    <a href="products.php?category_id=<?php echo $categoryValue->categoryID; ?>">
                                        <img src="//<?php echo $categoryValue->categoryImg; ?>" alt="" /><?php echo $categoryValue->categoryName; ?></a><span class="count"><?php echo $categoryValue->item; ?></span>
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