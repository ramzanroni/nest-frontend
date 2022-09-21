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
    $limit = 2;
    $pageNumber = $_POST['pageNumber'];
    $start = $pageNumber * $limit - $limit;


    // total blogs 
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . "admin/blog/blog.php",
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
        CURLOPT_URL => APIENDPOINT . "admin/blog/blog.php?start=" . $start . "&limit=2",
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
                                                                            ?>)"><i class="fi-rs-arrow-small-left"></i></a>
                        </li>
                    <?php
                    }
                    for ($i = 1; $i <= $numberOfpage; $i++) { ?>
                        <li id="blogPagination_<?php echo $i; ?>" class="page-item <?php if ($i == $pageNumber) {
                                                                                        echo 'active';
                                                                                    } ?>">
                            <a class="page-link" onclick="blogPagination(<?php echo $i; ?>)"><?php echo $i; ?></a>
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
                                                                            } ?>)"><i class="fi-rs-arrow-small-right"></i></a>
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
                                                                            }  ?>)"><i class="fi-rs-arrow-small-left"></i></a>
                        </li>
                    <?php
                    }
                    if (!($numberOfpage >= $pageNumber + 2)) {
                    ?>
                        <li id="blogPagination_<?php echo $pageNumber - 4; ?>" class="page-item">
                            <a class="page-link" onclick="blogPagination(<?php echo $pageNumber - 4; ?>)"><?php echo $pageNumber - 4; ?></a>
                        </li>
                    <?php } ?>
                    <?php
                    if (!($numberOfpage >= $pageNumber + 1)) {
                    ?>
                        <li id="blogPagination_<?php echo $pageNumber - 3; ?>" class="page-item">
                            <a class="page-link" onclick="blogPagination(<?php echo $pageNumber - 3; ?>)"><?php echo $pageNumber - 3; ?></a>
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
                            <a class="page-link" onclick="blogPagination(<?php echo $pageNumber - 2; ?>)"><?php echo $pageNumber - 2; ?></a>
                        </li>
                    <?php }
                    if ($pageNumber - 1 > 0) {
                    ?>
                        <li id="blogPagination_<?php echo $pageNumber - 1; ?>" class="page-item <?php if ($pageNumber - 1 == $pageNumber) {
                                                                                                    echo 'active';
                                                                                                } ?>">
                            <a class="page-link" onclick="blogPagination(<?php echo  $pageNumber - 1; ?>)"><?php echo  $pageNumber - 1; ?></a>
                        </li>
                    <?php } ?>
                    <li id="blogPagination_<?php echo  $pageNumber; ?>" class="page-item <?php if ($pageNumber == $pageNumber) {
                                                                                                echo 'active';
                                                                                            } ?>">
                        <a class="page-link" onclick="blogPagination(<?php echo $pageNumber; ?>)"><?php echo $pageNumber; ?></a>
                    </li>
                    <?php
                    if ($numberOfpage >= $pageNumber + 1) {
                    ?>
                        <li id="blogPagination_<?php echo $pageNumber + 1; ?>" class="page-item <?php if ($pageNumber + 1 == $pageNumber) {
                                                                                                    echo 'active';
                                                                                                } ?>">
                            <a class="page-link" onclick="blogPagination(<?php echo $pageNumber + 1; ?>)"><?php echo $pageNumber + 1; ?></a>
                        </li>
                    <?php
                    }
                    if ($numberOfpage >= $pageNumber + 2) {
                    ?>
                        <li id="blogPagination_<?php echo $pageNumber + 2; ?>" class="page-item <?php if ($pageNumber + 2 == $pageNumber) {
                                                                                                    echo 'active';
                                                                                                } ?>">
                            <a class="page-link" onclick="blogPagination(<?php echo $pageNumber + 2; ?>)"><?php echo $pageNumber + 2; ?></a>
                        </li>
                    <?php } ?>
                    <?php
                    if ($pageNumber <= 2) {
                    ?>
                        <li id="blogPagination_<?php echo $pageNumber + 3; ?>" class="page-item">
                            <a class="page-link" onclick="blogPagination(<?php echo $pageNumber + 3; ?>)"><?php echo $pageNumber + 3; ?></a>
                        </li>
                    <?php } ?>
                    <?php
                    if ($pageNumber <= 1) {
                    ?>
                        <li id="blogPagination_<?php echo $pageNumber + 4; ?>" class="page-item">
                            <a class="page-link" onclick="blogPagination(<?php echo $pageNumber + 4; ?>)"><?php echo $pageNumber + 4; ?></a>
                        </li>
                    <?php }
                    if ($pageNumber != $numberOfpage) { ?>

                        <li class="page-item">
                            <a class="page-link" onclick="blogPagination(<?php if ($numberOfpage != $pageNumber) {
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