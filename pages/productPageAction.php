<?php
include '../inc/function.php';
include '../inc/apiendpoint.php';
if ($_POST['check'] == "sortingProductList") {
    $limit = $_POST['limitValueData'];
    $sortby = $_POST['sortByData'];
    $catID = $_POST['catID'];
    $pageNumber = 1;


    setcookie('product_limit', $limit, time() + (86400 * 30), "/");
    setcookie('sort_by', $sortby, time() + (86400 * 30), "/");


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT .  "product.php?category_id=" . $catID . "&limit=" . $limit . "&start=1&sort_by=" . $sortby,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "authorization:" . APIKEY,
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
        $totalCategoryItem = $cateoryProduct->data->rows_returned;
        $categoryName = $cateoryProduct->data->products[0]->category;
    }


    $cartCookiesProduct = json_decode($_COOKIE['shopping_cart']);




    //total item of this category
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT .  "product.php?category_id=" . $catID . "&limit=All&start=1&sort_by=" . $sortby,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "authorization:" . APIKEY,
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
        $totalCategoryItem = $cateoryProduct->data->rows_returned;
    }
?>
    <div class="row product-grid">
        <?php
        foreach ($categoryData as $productData) {
            include '../component/product-component.php';
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

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT .  "product.php?category_id=" . $catID . "&limit=" . $limit . "&start=" . $start . "&sort_by=" . $sortby,
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
        $totalCategoryItem = $cateoryProduct->data->rows_returned;
        $categoryName = $cateoryProduct->data->products[0]->category;
    }


    //total item of this category
    //total item of this category
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT .  "product.php?category_id=" . $catID . "&limit=All&start=1&sort_by=" . $sortby,
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
        $totalCategoryItem = $cateoryProduct->data->rows_returned;
    }
?>

    <div class="row product-grid" id="myTabContent">
        <?php
        foreach ($categoryData as $productData) {
            include '../component/product-component.php';
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
        $url = "product.php";
    } else {
        $url = "product.php?category_id=" . $catID . "&limit=All&start=1"; //url will be here
    }


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
            "authorization:" . APIKEY,
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










    $cartCookiesProduct = json_decode($_COOKIE['shopping_cart']);

    foreach ($categoryData as $productData) {
        include '../component/product-component.php';
    }
}
?>