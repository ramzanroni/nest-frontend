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
            "cache-control: no-cache",
            "Authorization:" . APIKEY
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
    $limit = trim($_POST['limitValueData']);
    $sortby = trim($_POST['sortByData']);
    $catID = trim($_POST['catID']);
    $pageNumber = trim($_POST['pageNumber']);
    $start = $pageNumber * $limit - $limit;

    $curl = curl_init();
    if ($catID == '') {
        $pageurl = "product.php?limit=" . $limit . "&start=" . $start . "&sort_by=" . $sortby;
    } else {
        $pageurl = "product.php?category_id=" . $catID . "&limit=" . $limit . "&start=" . $start . "&sort_by=" . $sortby;
    }

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . $pageurl,
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
    $curl = curl_init();

    if ($catID == '') {
        $totalpageURL = "product.php?limit=All&start=1&sort_by=" . $sortby;
    } else {
        $totalpageURL = "product.php?category_id=" . $catID . "&limit=All&start=1&sort_by=" . $sortby;
    }
    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . $totalpageURL,
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

// blog pagination 
if ($_POST['check'] == "paginationBlog") {
    $categoryId = $_POST['categoryId'];

    $limit = 2;
    $pageNumber = $_POST['pageNumber'];
    $start = $pageNumber * $limit - $limit;

    if ($categoryId != 0) {
        $subUrlTotal = "admin/blog/blog.php?category_id=" . $categoryId;
        $subUrl = "admin/blog/blog.php?category_id=" . $categoryId . "&start=" . $start . "&limit=2";
    } else {
        $subUrlTotal = "admin/blog/blog.php";
        $subUrl = "admin/blog/blog.php?start=" . $start . "&limit=2";
    }

    // total blogs 
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . $subUrlTotal,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Authorization:" . APIKEY
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $result = json_decode($response);
        $totalBlog = $result->data->rows_returned;
    }


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . $subUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Authorization: " . APIKEY,
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
        $blogs = $result->data->blog;
    }
?>
    <div class="loop-grid">
        <div class="row">
            <?php
            foreach ($blogs as $blog) {
            ?>
                <article class="col-xl-3 col-lg-4 col-md-6 text-center hover-up mb-30 animated">
                    <div class="post-thumb">
                        <a href="blog.php?id=<?php echo $blog->id; ?>">
                            <img class="border-radius-15" src="<?php echo '../urban/' . $blog->image; ?>" alt="" />
                        </a>
                        <div class="entry-meta">
                            <a class="entry-meta meta-2" href="#"><i class="fi-rs-heart"></i></a>
                        </div>
                    </div>
                    <div class="entry-content-2">
                        <h6 class="mb-10 font-sm"><a class="entry-meta text-muted" href="blogs.php?category_id=<?php echo $blog->category_id; ?>"><?php echo $blog->category_name; ?></a></h6>
                        <h4 class="post-title mb-15">
                            <a href="blog.php?id=<?php echo $blog->id; ?>"><?php echo $blog->title; ?></a>
                        </h4>
                        <div class="entry-meta font-xs color-grey mt-10 pb-10">
                            <div>
                                <span class="post-on mr-10"><?php echo date('d F Y', $blog->create_at); ?></span>
                                <span class="hit-count has-dot mr-10">126k Views</span>
                                <span class="hit-count has-dot">4 mins read</span>
                            </div>
                        </div>
                    </div>
                </article>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="pagination-area mt-15 mb-sm-5 mb-lg-0">
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-start">
                <?php
                $currentPage = 1;
                $nextPage = $currentPage + 1;
                $previousPage = $currentPage - 1;
                $perpageItem = $limit;
                $numberOfpage = ceil($totalBlog / $perpageItem);
                if ($numberOfpage <= 5) {
                    if ($pageNumber > 1) {
                ?>
                        <li class="page-item">
                            <a class="page-link" onclick="blogPagination(<?php
                                                                            if ($pageNumber > 1) {
                                                                                echo $pageNumber - 1;
                                                                            } else {
                                                                                echo 1;
                                                                            }
                                                                            ?>,<?php echo $categoryId; ?>)"><i class="fi-rs-arrow-small-left"></i></a>
                        </li>
                    <?php
                    }
                    for ($i = 1; $i <= $numberOfpage; $i++) { ?>
                        <li id="blogPagination_<?php echo $i; ?>" class="page-item <?php if ($i == $pageNumber) {
                                                                                        echo 'active';
                                                                                    } ?>">
                            <a class="page-link" onclick="blogPagination(<?php echo $i; ?>,<?php echo $categoryId; ?>)"><?php echo $i; ?></a>
                        </li>
                    <?php
                    }
                    if ($pageNumber != $numberOfpage) {
                    ?>
                        <li class="page-item">
                            <a class="page-link" onclick="blogPagination(<?php if ($numberOfpage != $pageNumber) {
                                                                                echo $pageNumber + 1;
                                                                            } else {
                                                                                echo $pageNumber;
                                                                            } ?>,<?php echo $categoryId; ?>)"><i class="fi-rs-arrow-small-right"></i></a>
                        </li>
                    <?php }
                } else {
                    if ($pageNumber > 1) {

                    ?>
                        <li class="page-item">
                            <a class="page-link" onclick="blogPagination(<?php if ($pageNumber > 1) {
                                                                                echo $pageNumber - 1;
                                                                            } else {
                                                                                echo 1;
                                                                            }  ?>,<?php echo $categoryId; ?>)"><i class="fi-rs-arrow-small-left"></i></a>
                        </li>
                    <?php
                    }
                    if (!($numberOfpage >= $pageNumber + 2)) {
                    ?>
                        <li id="blogPagination_<?php echo $pageNumber - 4; ?>" class="page-item">
                            <a class="page-link" onclick="blogPagination(<?php echo $pageNumber - 4; ?>,<?php echo $categoryId; ?>)"><?php echo $pageNumber - 4; ?></a>
                        </li>
                    <?php } ?>
                    <?php
                    if (!($numberOfpage >= $pageNumber + 1)) {
                    ?>
                        <li id="blogPagination_<?php echo $pageNumber - 3; ?>" class="page-item">
                            <a class="page-link" onclick="blogPagination(<?php echo $pageNumber - 3; ?>,<?php echo $categoryId; ?>)"><?php echo $pageNumber - 3; ?></a>
                        </li>
                    <?php
                    }
                    ?>
                    <?php
                    if ($pageNumber - 2 > 0) {
                    ?>
                        <li id="blogPagination_<?php echo $pageNumber - 2; ?>" class="page-item <?php if ($pageNumber - 2 == $pageNumber) {
                                                                                                    echo 'active';
                                                                                                } ?>">
                            <a class="page-link" onclick="blogPagination(<?php echo $pageNumber - 2; ?>,<?php echo $categoryId; ?>)"><?php echo $pageNumber - 2; ?></a>
                        </li>
                    <?php }
                    if ($pageNumber - 1 > 0) {
                    ?>
                        <li id="blogPagination_<?php echo $pageNumber - 1; ?>" class="page-item <?php if ($pageNumber - 1 == $pageNumber) {
                                                                                                    echo 'active';
                                                                                                } ?>">
                            <a class="page-link" onclick="blogPagination(<?php echo  $pageNumber - 1; ?>,<?php echo $categoryId; ?>)"><?php echo  $pageNumber - 1; ?></a>
                        </li>
                    <?php } ?>
                    <li id="blogPagination_<?php echo  $pageNumber; ?>" class="page-item <?php if ($pageNumber == $pageNumber) {
                                                                                                echo 'active';
                                                                                            } ?>">
                        <a class="page-link" onclick="blogPagination(<?php echo $pageNumber; ?>,<?php echo $categoryId; ?>)"><?php echo $pageNumber; ?></a>
                    </li>
                    <?php
                    if ($numberOfpage >= $pageNumber + 1) {
                    ?>
                        <li id="blogPagination_<?php echo $pageNumber + 1; ?>" class="page-item <?php if ($pageNumber + 1 == $pageNumber) {
                                                                                                    echo 'active';
                                                                                                } ?>">
                            <a class="page-link" onclick="blogPagination(<?php echo $pageNumber + 1; ?>,<?php echo $categoryId; ?>)"><?php echo $pageNumber + 1; ?></a>
                        </li>
                    <?php
                    }
                    if ($numberOfpage >= $pageNumber + 2) {
                    ?>
                        <li id="blogPagination_<?php echo $pageNumber + 2; ?>" class="page-item <?php if ($pageNumber + 2 == $pageNumber) {
                                                                                                    echo 'active';
                                                                                                } ?>">
                            <a class="page-link" onclick="blogPagination(<?php echo $pageNumber + 2; ?>,<?php echo $categoryId; ?>)"><?php echo $pageNumber + 2; ?></a>
                        </li>
                    <?php } ?>
                    <?php
                    if ($pageNumber <= 2) {
                    ?>
                        <li id="blogPagination_<?php echo $pageNumber + 3; ?>" class="page-item">
                            <a class="page-link" onclick="blogPagination(<?php echo $pageNumber + 3; ?>,<?php echo $categoryId; ?>)"><?php echo $pageNumber + 3; ?></a>
                        </li>
                    <?php } ?>
                    <?php
                    if ($pageNumber <= 1) {
                    ?>
                        <li id="blogPagination_<?php echo $pageNumber + 4; ?>" class="page-item">
                            <a class="page-link" onclick="blogPagination(<?php echo $pageNumber + 4; ?>,<?php echo $categoryId; ?>)"><?php echo $pageNumber + 4; ?></a>
                        </li>
                    <?php }
                    if ($pageNumber != $numberOfpage) { ?>

                        <li class="page-item">
                            <a class="page-link" onclick="blogPagination(<?php if ($numberOfpage != $pageNumber) {
                                                                                echo $pageNumber + 1;
                                                                            } else {
                                                                                echo $pageNumber;
                                                                            } ?>,<?php echo $categoryId; ?>)"><i class="fi-rs-arrow-small-right"></i></a>
                        </li>
                <?php
                    }
                }
                ?>
            </ul>
        </nav>
    </div>
<?php


}
if ($_POST['check'] == "paginationComment") {
    $limit = 2;
    $pageNumber = $_POST['pageNumber'];
    $commentId = $_POST['commentId'];

    // total comment
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . "admin/blog/blog-comment.php?blog_id=" . $commentId,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Authorization: " . APIKEY,
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
        $totalComment = $result->data->rows_returned;
    }
?>
    <h3 class="mb-15 text-center mb-30">Leave a Comment</h3>
    <div class="row">
        <div class="col-lg-9 col-md-12  m-auto">
            <form class="form-contact comment_form mb-50" action="#" id="commentForm">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <textarea class="form-control w-100" name="comment" id="comment" cols="30" rows="9" placeholder="Write Comment"></textarea>
                        </div>
                    </div>
                    <!-- <div class="col-sm-6">
                        <div class="form-group">
                            <input class="form-control" name="name" id="name" type="text" placeholder="Name">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input class="form-control" name="email" id="email" type="email" placeholder="Email">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <input class="form-control" name="website" id="website" type="text" placeholder="Website">
                        </div>
                    </div>
                </div> -->
                    <div class="form-group">
                        <button type="button" class="button button-contactForm" onclick="addComment(<?php echo $commentId; ?>,'<?php echo getPhone(); ?>')">Post Comment</button>
                    </div>
            </form>
            <div class="comments-area">
                <h3 class="mb-30">Comments</h3>
                <div class="comment-list m-auto">
                    <?php

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => APIENDPOINT . "admin/blog/blog-comment.php?blog_id=" . $commentId . "&page=" . $pageNumber,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_HTTPHEADER => array(
                            "Authorization: " . APIKEY,
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
                        $comments = $result->data->comment;
                    }
                    foreach ($comments as $comment) {
                        if ($comment->parent == 0) {
                    ?>
                            <div class=" single-comment justify-content-between d-flex mb-30">
                                <div class="user justify-content-between d-flex">
                                    <div class="thumb text-center">
                                        <img src="assets/imgs/blog/author-2.png" alt="">
                                        <a href="#" class="font-heading text-brand"><?php echo $comment->user_name; ?></a>
                                    </div>
                                    <div class="desc">
                                        <div class="d-flex justify-content-between mb-10">
                                            <div class="d-flex align-items-center">
                                                <span class="font-xs text-muted"><?php echo date("F d, Y g:i A", strtotime($comment->create_at)) ?></span>
                                            </div>
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" style="width:80%">
                                                </div>
                                            </div>
                                        </div>
                                        <p class="mb-10"><?php echo $comment->comment; ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        if ($comment->parent != 0) {
                        ?>
                            <div class="single-comment justify-content-between d-flex mb-30 ml-30">
                                <div class="user justify-content-between d-flex">
                                    <div class="thumb text-center">
                                        <img src="assets/imgs/blog/author-3.png" alt="">
                                        <a href="#" class="font-heading text-brand"><?php echo $comment->user_name; ?></a>
                                    </div>
                                    <div class="desc">
                                        <div class="d-flex justify-content-between mb-10">
                                            <div class="d-flex align-items-center">
                                                <span class="font-xs text-muted"><?php echo date("F d, Y g:i A", strtotime($comment->create_at)) ?></span>
                                            </div>
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" style="width:80%">
                                                </div>
                                            </div>
                                        </div>
                                        <p class="mb-10"><?php echo $comment->comment; ?></p>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="pagination-area mt-15 mb-sm-5 mb-lg-0">
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-start listStyle">
                <?php
                $currentPage = 1;
                $nextPage = $currentPage + 1;
                $previousPage = $currentPage - 1;
                $perpageItem = $limit;
                $numberOfpage = ceil($totalComment / $perpageItem);
                if ($numberOfpage <= 5) {
                    if ($pageNumber > 1) {
                ?>
                        <li class="page-item">
                            <a class="page-link" onclick="commentPagination(<?php
                                                                            if ($pageNumber > 1) {
                                                                                echo $pageNumber - 1;
                                                                            } else {
                                                                                echo 1;
                                                                            }
                                                                            ?>,<?php echo $commentId; ?>)"><i class="fi-rs-arrow-small-left"></i></a>
                        </li>
                    <?php
                    }
                    for ($i = 1; $i <= $numberOfpage; $i++) { ?>
                        <li id="commentPagination_<?php echo $i; ?>" class="page-item <?php if ($i == $pageNumber) {
                                                                                            echo 'active';
                                                                                        } ?>">
                            <a class="page-link" onclick="commentPagination(<?php echo $i; ?>,<?php echo $commentId; ?>)"><?php echo $i; ?></a>
                        </li>
                    <?php
                    }
                    if ($pageNumber != $numberOfpage) {
                    ?>
                        <li class="page-item">
                            <a class="page-link" onclick="commentPagination(<?php if ($numberOfpage != $pageNumber) {
                                                                                echo $pageNumber + 1;
                                                                            } else {
                                                                                echo $pageNumber;
                                                                            } ?>,<?php echo $commentId; ?>)"><i class="fi-rs-arrow-small-right"></i></a>
                        </li>
                    <?php }
                } else {
                    if ($pageNumber > 1) {

                    ?>
                        <li class="page-item">
                            <a class="page-link" onclick="commentPagination(<?php if ($pageNumber > 1) {
                                                                                echo $pageNumber - 1;
                                                                            } else {
                                                                                echo 1;
                                                                            }  ?>,<?php echo $commentId; ?>)"><i class="fi-rs-arrow-small-left"></i></a>
                        </li>
                    <?php
                    }
                    if (!($numberOfpage >= $pageNumber + 2)) {
                    ?>
                        <li id="commentPagination_<?php echo $pageNumber - 4; ?>" class="page-item">
                            <a class="page-link" onclick="commentPagination(<?php echo $pageNumber - 4; ?>,<?php echo $commentId; ?>)"><?php echo $pageNumber - 4; ?></a>
                        </li>
                    <?php } ?>
                    <?php
                    if (!($numberOfpage >= $pageNumber + 1)) {
                    ?>
                        <li id="commentPagination_<?php echo $pageNumber - 3; ?>" class="page-item">
                            <a class="page-link" onclick="commentPagination(<?php echo $pageNumber - 3; ?>,<?php echo $commentId; ?>)"><?php echo $pageNumber - 3; ?></a>
                        </li>
                    <?php
                    }
                    ?>
                    <?php
                    if ($pageNumber - 2 > 0) {
                    ?>
                        <li id="commentPagination_<?php echo $pageNumber - 2; ?>" class="page-item <?php if ($pageNumber - 2 == $pageNumber) {
                                                                                                        echo 'active';
                                                                                                    } ?>">
                            <a class="page-link" onclick="commentPagination(<?php echo $pageNumber - 2; ?>,<?php echo $commentId; ?>)"><?php echo $pageNumber - 2; ?></a>
                        </li>
                    <?php }
                    if ($pageNumber - 1 > 0) {
                    ?>
                        <li id="commentPagination_<?php echo $pageNumber - 1; ?>" class="page-item <?php if ($pageNumber - 1 == $pageNumber) {
                                                                                                        echo 'active';
                                                                                                    } ?>">
                            <a class="page-link" onclick="commentPagination(<?php echo  $pageNumber - 1; ?>,<?php echo $commentId; ?>)"><?php echo  $pageNumber - 1; ?></a>
                        </li>
                    <?php } ?>
                    <li id="commentPagination_<?php echo  $pageNumber; ?>" class="page-item <?php if ($pageNumber == $pageNumber) {
                                                                                                echo 'active';
                                                                                            } ?>">
                        <a class="page-link" onclick="commentPagination(<?php echo $pageNumber; ?>,<?php echo $commentId; ?>)"><?php echo $pageNumber; ?></a>
                    </li>
                    <?php
                    if ($numberOfpage >= $pageNumber + 1) {
                    ?>
                        <li id="commentPagination_<?php echo $pageNumber + 1; ?>" class="page-item <?php if ($pageNumber + 1 == $pageNumber) {
                                                                                                        echo 'active';
                                                                                                    } ?>">
                            <a class="page-link" onclick="commentPagination(<?php echo $pageNumber + 1; ?>,<?php echo $commentId; ?>)"><?php echo $pageNumber + 1; ?></a>
                        </li>
                    <?php
                    }
                    if ($numberOfpage >= $pageNumber + 2) {
                    ?>
                        <li id="commentPagination_<?php echo $pageNumber + 2; ?>" class="page-item <?php if ($pageNumber + 2 == $pageNumber) {
                                                                                                        echo 'active';
                                                                                                    } ?>">
                            <a class="page-link" onclick="commentPagination(<?php echo $pageNumber + 2; ?>,<?php echo $commentId; ?>)"><?php echo $pageNumber + 2; ?></a>
                        </li>
                    <?php } ?>
                    <?php
                    if ($pageNumber <= 2) {
                    ?>
                        <li id="commentPagination_<?php echo $pageNumber + 3; ?>" class="page-item">
                            <a class="page-link" onclick="commentPagination(<?php echo $pageNumber + 3; ?>,<?php echo $commentId; ?>)"><?php echo $pageNumber + 3; ?></a>
                        </li>
                    <?php } ?>
                    <?php
                    if ($pageNumber <= 1) {
                    ?>
                        <li id="commentPagination_<?php echo $pageNumber + 4; ?>" class="page-item">
                            <a class="page-link" onclick="commentPagination(<?php echo $pageNumber + 4; ?>,<?php echo $commentId; ?>)"><?php echo $pageNumber + 4; ?></a>
                        </li>
                    <?php }
                    if ($pageNumber != $numberOfpage) { ?>

                        <li class="page-item">
                            <a class="page-link" onclick="commentPagination(<?php if ($numberOfpage != $pageNumber) {
                                                                                echo $pageNumber + 1;
                                                                            } else {
                                                                                echo $pageNumber;
                                                                            } ?>,<?php echo $commentId; ?>)"><i class="fi-rs-arrow-small-right"></i></a>
                        </li>
                <?php
                    }
                }
                ?>
            </ul>
        </nav>
    </div>
<?php
}

if ($_POST['check'] == "categoryWiseProduct") {


    $catID = trim($_POST['categoryId']);
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










    $cartCookiesProduct = json_decode($_COOKIE['shopping_cart']);

    foreach ($categoryData as $productData) {
        include '../component/product-component.php';
    }
}
?>