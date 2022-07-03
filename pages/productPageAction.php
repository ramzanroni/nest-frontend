<?php
include '../inc/function.php';
if ($_POST['check'] == "sortingProductList") {
    $limit = $_POST['limitValueData'];
    $sortby = $_POST['sortByData'];
    $catID = $_POST['catID'];
    $pageNumber = 1;
    setcookie('product_limit', $limit, time() + (86400 * 30), "/");
    setcookie('sort_by', $sortby, time() + (86400 * 30), "/");
    $url = 'http://192.168.0.116/neonbazar_api/category_wise_product.php?category_id=' . $catID . '&limit=' . $limit . '&sort_by=' . $sortby; //url will be here
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
    $categoryData = json_decode($categoryInfo);
    $totalProduct = count($categoryData);
    $cartCookiesProduct = json_decode($_COOKIE['shopping_cart']);
    //total item of this category
    $url = 'http://192.168.0.116/neonbazar_api/total_number_of_item_category_wise.php?category_id=' . $catID;
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
    $categoryItemInfo = curl_exec($ch);
    curl_close($ch);
    $categoryDataCount = json_decode($categoryItemInfo);
    $totalCategoryItem = $categoryDataCount[0]->totalItem;
?>
    <div class="row product-grid">
        <?php
        foreach ($categoryData as $productData) {
        ?>
            <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                <?php
                $cartCookiesProduct = json_decode($_COOKIE['shopping_cart']);
                $cartItem = count($cartCookiesProduct);
                ?>
                <div class="product-cart-wrap mb-30">
                    <div class="product-img-action-wrap">
                        <div class="product-img product-img-zoom">
                            <a href="shop-product-right.php?product_id=<?php echo $productData->stockid; ?>">
                                <img class="default-img" src="//<?php echo $productData->img; ?>" alt="" />
                            </a>
                        </div>
                    </div>
                    <div class="product-content-wrap">
                        <div class="product-category">
                            <a href="shop-grid-right.php?category_id<?php echo $productData->category; ?>"><?php echo $productData->category; ?></a>
                        </div>
                        <h2><a href="shop-product-right.php?product_id=<?php echo $productData->stockid; ?>"><?php echo $productData->description; ?></a>
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
                        </div>
                    </div>
                </div>
            </div>

        <?php
        }
        ?>
        <div class="pagination-area mt-20 mb-20">
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-start">

                    <?php
                    $currentPage = 1;
                    $nextPage = $currentPage + 1;
                    $previousPage = $currentPage - 1;
                    $perpageItem = $limit;
                    $numberOfpage = ceil($totalCategoryItem / $perpageItem);
                    if ($numberOfpage <= 5) {
                        if ($pageNumber > 1) {
                    ?>
                            <li class="page-item">
                                <a class="page-link" onclick="pagination(<?php if ($pageNumber > 1) {

                                                                                echo $pageNumber - 1;
                                                                            } else {
                                                                                echo 1;
                                                                            }  ?>)"><i class="fi-rs-arrow-small-left"></i></a>
                            </li>
                        <?php
                        }
                        for ($i = 1; $i <= $numberOfpage; $i++) { ?>
                            <li id="pagination_<?php echo $i; ?>" class="page-item <?php if ($i == $pageNumber) {
                                                                                        echo 'active';
                                                                                    } ?>">
                                <a class="page-link" onclick="pagination(<?php echo $i; ?>)"><?php echo $i; ?></a>
                            </li>
                        <?php
                        }
                        if ($pageNumber != $numberOfpage) {
                        ?>
                            <li class="page-item">
                                <a class="page-link" onclick="pagination(<?php if ($numberOfpage != $pageNumber) {
                                                                                echo $pageNumber + 1;
                                                                            } else {
                                                                                echo $pageNumber;
                                                                            } ?>)"><i class="fi-rs-arrow-small-right"></i></a>
                            </li>
                        <?php }
                    } else {
                        if ($pageNumber > 1) {

                        ?>
                            <li class="page-item">
                                <a class="page-link" onclick="pagination(<?php if ($pageNumber > 1) {
                                                                                echo $pageNumber - 1;
                                                                            } else {
                                                                                echo 1;
                                                                            }  ?>)"><i class="fi-rs-arrow-small-left"></i></a>
                            </li>
                        <?php
                        }
                        if (!($numberOfpage >= $pageNumber + 2)) {
                        ?>
                            <li id="pagination_<?php echo $pageNumber - 4; ?>" class="page-item">
                                <a class="page-link" onclick="pagination(<?php echo $pageNumber - 4; ?>)"><?php echo $pageNumber - 4; ?></a>
                            </li>
                        <?php } ?>
                        <?php
                        if (!($numberOfpage >= $pageNumber + 1)) {
                        ?>
                            <li id="pagination_<?php echo $pageNumber - 3; ?>" class="page-item">
                                <a class="page-link" onclick="pagination(<?php echo $pageNumber - 3; ?>)"><?php echo $pageNumber - 3; ?></a>
                            </li>
                        <?php
                        }
                        ?>
                        <?php
                        if ($pageNumber - 2 > 0) {
                        ?>
                            <li id="pagination_<?php echo $pageNumber - 2; ?>" class="page-item <?php if ($pageNumber - 2 == $pageNumber) {
                                                                                                    echo 'active';
                                                                                                } ?>">
                                <a class="page-link" onclick="pagination(<?php echo $pageNumber - 2; ?>)"><?php echo $pageNumber - 2; ?></a>
                            </li>
                        <?php }
                        if ($pageNumber - 1 > 0) {
                        ?>
                            <li id="pagination_<?php echo $pageNumber - 1; ?>" class="page-item <?php if ($pageNumber - 1 == $pageNumber) {
                                                                                                    echo 'active';
                                                                                                } ?>">
                                <a class="page-link" onclick="pagination(<?php echo  $pageNumber - 1; ?>)"><?php echo  $pageNumber - 1; ?></a>
                            </li>
                        <?php } ?>
                        <li id="pagination_<?php echo  $pageNumber; ?>" class="page-item <?php if ($pageNumber == $pageNumber) {
                                                                                                echo 'active';
                                                                                            } ?>">
                            <a class="page-link" onclick="pagination(<?php echo $pageNumber; ?>)"><?php echo $pageNumber; ?></a>
                        </li>
                        <?php
                        if ($numberOfpage >= $pageNumber + 1) {
                        ?>
                            <li id="pagination_<?php echo $pageNumber + 1; ?>" class="page-item <?php if ($pageNumber + 1 == $pageNumber) {
                                                                                                    echo 'active';
                                                                                                } ?>">
                                <a class="page-link" onclick="pagination(<?php echo $pageNumber + 1; ?>)"><?php echo $pageNumber + 1; ?></a>
                            </li>
                        <?php
                        }
                        if ($numberOfpage >= $pageNumber + 2) {
                        ?>
                            <li id="pagination_<?php echo $pageNumber + 2; ?>" class="page-item <?php if ($pageNumber + 2 == $pageNumber) {
                                                                                                    echo 'active';
                                                                                                } ?>">
                                <a class="page-link" onclick="pagination(<?php echo $pageNumber + 2; ?>)"><?php echo $pageNumber + 2; ?></a>
                            </li>
                        <?php } ?>
                        <?php
                        if ($pageNumber <= 2) {
                        ?>
                            <li id="pagination_<?php echo $pageNumber + 3; ?>" class="page-item">
                                <a class="page-link" onclick="pagination(<?php echo $pageNumber + 3; ?>)"><?php echo $pageNumber + 3; ?></a>
                            </li>
                        <?php } ?>
                        <?php
                        if ($pageNumber <= 1) {
                        ?>
                            <li id="pagination_<?php echo $pageNumber + 4; ?>" class="page-item">
                                <a class="page-link" onclick="pagination(<?php echo $pageNumber + 4; ?>)"><?php echo $pageNumber + 4; ?></a>
                            </li>
                        <?php }
                        if ($pageNumber != $numberOfpage) { ?>

                            <li class="page-item">
                                <a class="page-link" onclick="pagination(<?php if ($numberOfpage != $pageNumber) {
                                                                                echo $pageNumber + 1;
                                                                            } else {
                                                                                echo $pageNumber;
                                                                            } ?>)"><i class="fi-rs-arrow-small-right"></i></a>
                            </li>
                    <?php
                        }
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </div>
<?php
}

if ($_POST['check'] == "paginationProduct") {
    $limit = $_POST['limitValueData'];
    $sortby = $_POST['sortByData'];
    $catID = $_POST['catID'];
    $pageNumber = $_POST['pageNumber'];
    $start = $pageNumber * $limit - $limit;

    $url = 'http://192.168.0.116/neonbazar_api/category_wise_product.php?category_id=' . $catID . '&limit=' . $limit . '&sort_by=' . $sortby . '&start=' . $start; //url will be here
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
    $categoryData = json_decode($categoryInfo);
    $totalProduct = count($categoryData);
    $cartCookiesProduct = json_decode($_COOKIE['shopping_cart']);
    //total item of this category
    $url = 'http://192.168.0.116/neonbazar_api/total_number_of_item_category_wise.php?category_id=' . $catID;
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
    $categoryItemInfo = curl_exec($ch);
    curl_close($ch);
    $categoryDataCount = json_decode($categoryItemInfo);
    $totalCategoryItem = $categoryDataCount[0]->totalItem; ?>

    <div class="row product-grid" id="myTabContent">
        <?php
        foreach ($categoryData as $productData) {
        ?>
            <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                <?php
                $cartCookiesProduct = json_decode($_COOKIE['shopping_cart']);
                $cartItem = count($cartCookiesProduct);
                ?>
                <div class="product-cart-wrap mb-30">
                    <div class="product-img-action-wrap">
                        <div class="product-img product-img-zoom">
                            <a href="shop-product-right.php?product_id=<?php echo $productData->stockid; ?>">
                                <img class="default-img" src="//<?php echo $productData->img; ?>" alt="" />
                            </a>
                        </div>
                    </div>
                    <div class="product-content-wrap">
                        <div class="product-category">
                            <a href="shop-grid-right.php?category_id<?php echo $productData->category; ?>"><?php echo $productData->category; ?></a>
                        </div>
                        <h2><a href="shop-product-right.php?product_id=<?php echo $productData->stockid; ?>"><?php echo $productData->description; ?></a>
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
                        </div>
                    </div>
                </div>
            </div>

        <?php
        }
        ?>
        <div class="pagination-area mt-20 mb-20">
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-start">
                    <?php
                    $currentPage = 1;
                    $nextPage = $currentPage + 1;
                    $previousPage = $currentPage - 1;
                    $perpageItem = $limit;
                    $numberOfpage = ceil($totalCategoryItem / $perpageItem);
                    if ($numberOfpage <= 5) {
                        if ($pageNumber > 1) {
                    ?>
                            <li class="page-item">
                                <a class="page-link" onclick="pagination(<?php
                                                                            if ($pageNumber > 1) {
                                                                                echo $pageNumber - 1;
                                                                            } else {
                                                                                echo 1;
                                                                            }
                                                                            ?>)"><i class="fi-rs-arrow-small-left"></i></a>
                            </li>
                        <?php
                        }
                        for ($i = 1; $i <= $numberOfpage; $i++) { ?>
                            <li id="pagination_<?php echo $i; ?>" class="page-item <?php if ($i == $pageNumber) {
                                                                                        echo 'active';
                                                                                    } ?>">
                                <a class="page-link" onclick="pagination(<?php echo $i; ?>)"><?php echo $i; ?></a>
                            </li>
                        <?php
                        }
                        if ($pageNumber != $numberOfpage) {
                        ?>
                            <li class="page-item">
                                <a class="page-link" onclick="pagination(<?php if ($numberOfpage != $pageNumber) {
                                                                                echo $pageNumber + 1;
                                                                            } else {
                                                                                echo $pageNumber;
                                                                            } ?>)"><i class="fi-rs-arrow-small-right"></i></a>
                            </li>
                        <?php }
                    } else {
                        if ($pageNumber > 1) {

                        ?>
                            <li class="page-item">
                                <a class="page-link" onclick="pagination(<?php if ($pageNumber > 1) {
                                                                                echo $pageNumber - 1;
                                                                            } else {
                                                                                echo 1;
                                                                            }  ?>)"><i class="fi-rs-arrow-small-left"></i></a>
                            </li>
                        <?php
                        }
                        if (!($numberOfpage >= $pageNumber + 2)) {
                        ?>
                            <li id="pagination_<?php echo $pageNumber - 4; ?>" class="page-item">
                                <a class="page-link" onclick="pagination(<?php echo $pageNumber - 4; ?>)"><?php echo $pageNumber - 4; ?></a>
                            </li>
                        <?php } ?>
                        <?php
                        if (!($numberOfpage >= $pageNumber + 1)) {
                        ?>
                            <li id="pagination_<?php echo $pageNumber - 3; ?>" class="page-item">
                                <a class="page-link" onclick="pagination(<?php echo $pageNumber - 3; ?>)"><?php echo $pageNumber - 3; ?></a>
                            </li>
                        <?php
                        }
                        ?>
                        <?php
                        if ($pageNumber - 2 > 0) {
                        ?>
                            <li id="pagination_<?php echo $pageNumber - 2; ?>" class="page-item <?php if ($pageNumber - 2 == $pageNumber) {
                                                                                                    echo 'active';
                                                                                                } ?>">
                                <a class="page-link" onclick="pagination(<?php echo $pageNumber - 2; ?>)"><?php echo $pageNumber - 2; ?></a>
                            </li>
                        <?php }
                        if ($pageNumber - 1 > 0) {
                        ?>
                            <li id="pagination_<?php echo $pageNumber - 1; ?>" class="page-item <?php if ($pageNumber - 1 == $pageNumber) {
                                                                                                    echo 'active';
                                                                                                } ?>">
                                <a class="page-link" onclick="pagination(<?php echo  $pageNumber - 1; ?>)"><?php echo  $pageNumber - 1; ?></a>
                            </li>
                        <?php } ?>
                        <li id="pagination_<?php echo  $pageNumber; ?>" class="page-item <?php if ($pageNumber == $pageNumber) {
                                                                                                echo 'active';
                                                                                            } ?>">
                            <a class="page-link" onclick="pagination(<?php echo $pageNumber; ?>)"><?php echo $pageNumber; ?></a>
                        </li>
                        <?php
                        if ($numberOfpage >= $pageNumber + 1) {
                        ?>
                            <li id="pagination_<?php echo $pageNumber + 1; ?>" class="page-item <?php if ($pageNumber + 1 == $pageNumber) {
                                                                                                    echo 'active';
                                                                                                } ?>">
                                <a class="page-link" onclick="pagination(<?php echo $pageNumber + 1; ?>)"><?php echo $pageNumber + 1; ?></a>
                            </li>
                        <?php
                        }
                        if ($numberOfpage >= $pageNumber + 2) {
                        ?>
                            <li id="pagination_<?php echo $pageNumber + 2; ?>" class="page-item <?php if ($pageNumber + 2 == $pageNumber) {
                                                                                                    echo 'active';
                                                                                                } ?>">
                                <a class="page-link" onclick="pagination(<?php echo $pageNumber + 2; ?>)"><?php echo $pageNumber + 2; ?></a>
                            </li>
                        <?php } ?>
                        <?php
                        if ($pageNumber <= 2) {
                        ?>
                            <li id="pagination_<?php echo $pageNumber + 3; ?>" class="page-item">
                                <a class="page-link" onclick="pagination(<?php echo $pageNumber + 3; ?>)"><?php echo $pageNumber + 3; ?></a>
                            </li>
                        <?php } ?>
                        <?php
                        if ($pageNumber <= 1) {
                        ?>
                            <li id="pagination_<?php echo $pageNumber + 4; ?>" class="page-item">
                                <a class="page-link" onclick="pagination(<?php echo $pageNumber + 4; ?>)"><?php echo $pageNumber + 4; ?></a>
                            </li>
                        <?php }
                        if ($pageNumber != $numberOfpage) { ?>

                            <li class="page-item">
                                <a class="page-link" onclick="pagination(<?php if ($numberOfpage != $pageNumber) {
                                                                                echo $pageNumber + 1;
                                                                            } else {
                                                                                echo $pageNumber;
                                                                            } ?>)"><i class="fi-rs-arrow-small-right"></i></a>
                            </li>
                    <?php
                        }
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </div>
    <?php
}

if ($_POST['check'] == "categoryWiseProduct") {
    $catID = $_POST['categoryId'];
    if ($catID == 0) {
        $url = "http://192.168.0.116/neonbazar_api/product_fetch.php";
    } else {
        $url = 'http://192.168.0.116/neonbazar_api/category_wise_product.php?category_id=' . $catID . '&limit=All'; //url will be here
    }
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
    $categoryData = json_decode($categoryInfo);
    $totalProduct = count($categoryData);
    $cartCookiesProduct = json_decode($_COOKIE['shopping_cart']);
    // print_r($categoryData);
    // exit();
    foreach ($categoryData as $productData) {
    ?>
        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
            <?php
            $cartCookiesProduct = json_decode($_COOKIE['shopping_cart']);
            $cartItem = count($cartCookiesProduct);
            ?>
            <div class="product-cart-wrap mb-30">
                <div class="product-img-action-wrap">
                    <div class="product-img product-img-zoom">
                        <a href="shop-product-right.php?product_id=<?php echo $productData->stockid; ?>">
                            <img class="default-img" src="//<?php echo $productData->img; ?>" alt="" />
                        </a>
                    </div>
                </div>
                <div class="product-content-wrap">
                    <div class="product-category">
                        <a href="shop-grid-right.php?category_id<?php echo $productData->category; ?>"><?php echo $productData->category; ?></a>
                    </div>
                    <h2><a href="shop-product-right.php?product_id=<?php echo $productData->stockid; ?>"><?php echo $productData->description; ?></a>
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

                    </div>
                </div>
            </div>
        </div>

<?php
    }
}
?>