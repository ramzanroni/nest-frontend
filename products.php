<?php
include 'apidata/dataFetch.php';
include 'inc/header.php';
include 'inc/apiendpoint.php';
// category product api data 
if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
    $limit = 5;
    $sortby = "PriceLowtoHigh";
    if ($_COOKIE['product_limit']) {
        $limit = $_COOKIE['product_limit'];
    }
    if ($_COOKIE['sort_by']) {
        $sortby = $_COOKIE['sort_by'];
    }
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT .  "product.php?category_id=" . $category_id . "&limit=" . $limit . "&start=0&sort_by=" . $sortby,
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
                                        <li data-id="5"><a>5</a></li>
                                        <li data-id="10"><a>10</a></li>
                                        <li data-id="20"><a>20</a></li>
                                        <li data-id="30"><a>30</a></li>
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
                                include 'component/product-component.php';
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
if ($_GET == null) {
    $limit = 5;
    $sortby = "PriceLowtoHigh";
    if ($_COOKIE['product_limit']) {
        $limit = $_COOKIE['product_limit'];
    }
    if ($_COOKIE['sort_by']) {
        $sortby = $_COOKIE['sort_by'];
    }
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT .  "product.php",
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
        // $totalProduct = $cateoryProduct->data->rows_returned;
        $categoryName = $cateoryProduct->data->products[0]->category;
    }

    // total product 
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT .  "product.php?limit=All",
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
                                        <li data-id="5"><a>5</a></li>
                                        <li data-id="10"><a>10</a></li>
                                        <li data-id="20"><a>20</a></li>
                                        <li data-id="30"><a>30</a></li>
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
                                include 'component/product-component.php';
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
include 'inc/footer.php';

?>
<script>
    $(document).ready(function() {
        var searchItem = '<?php echo $_GET['product_name']; ?>';
        $("#searchItemData").val(searchItem);
    });
</script>