﻿<?php
session_start();
include 'apidata/dataFetch.php';
include 'inc/header.php';
include 'config.php';
if (isset($_GET['id']) && $_GET['id'] != '') {
    $id = $_GET['id'];
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . "admin/blog/blog.php?id=" . $id,
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
        $blog = $result->data->blog;
    }



?>
    <!--End header-->
    <main class="main">
        <div class="page-content mb-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9 m-auto">
                        <div class="single-page pt-50 pr-30">
                            <div class="single-header style-2">
                                <div class="row">
                                    <div class="col-xl-10 col-lg-12 m-auto">
                                        <h6 class="mb-10"><a href="./blogs.php?category_id=<?php echo $blog[0]->category_id; ?>"><?php echo $blog[0]->category_name; ?></a></h6>
                                        <h2 class="mb-10"><?php echo $blog[0]->title; ?></h2>
                                        <div class="single-header-meta">
                                            <div class="entry-meta meta-1 font-xs mt-15 mb-15">
                                                <a class="author-avatar" href="#">
                                                    <img class="img-circle" src="assets/imgs/blog/author-1.png" alt="">
                                                </a>
                                                <span class="post-by">By <a href="#">NeoBazar</a></span>
                                                <span class="post-on has-dot">2 hours ago</span>
                                                <span class="time-reading has-dot">8 mins read</span>
                                            </div>
                                            <div class="social-icons single-share">
                                                <ul class="text-grey-5 d-inline-block">
                                                    <li class="mr-5"><a href="#"><img src="assets/imgs/theme/icons/icon-bookmark.svg" alt=""></a></li>
                                                    <li><a href="#"><img src="assets/imgs/theme/icons/icon-heart-2.svg" alt=""></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <figure class="single-thumbnail">
                                <img src="<?php echo '../urban/' . $blog[0]->image; ?>" alt="">
                            </figure>
                            <div class="single-content">
                                <div class="row">
                                    <div class="col-xl-10 col-lg-12 m-auto">
                                        <?php echo $blog[0]->body; ?>
                                        <div class="entry-bottom mt-50 mb-30">
                                            <!-- <div class="tags w-50 w-sm-100">
                                            <a href="blog-category-big.html" rel="tag" class="hover-up btn btn-sm btn-rounded mr-10">deer</a>
                                            <a href="blog-category-big.html" rel="tag" class="hover-up btn btn-sm btn-rounded mr-10">nature</a>
                                            <a href="blog-category-big.html" rel="tag" class="hover-up btn btn-sm btn-rounded mr-10">conserve</a>
                                        </div> -->
                                            <div class="social-icons single-share">
                                                <ul class="text-grey-5 d-inline-block">
                                                    <li><strong class="mr-10">Share this:</strong></li>
                                                    <li class="social-facebook"><a href="#"><img src="assets/imgs/theme/icons/icon-facebook.svg" alt=""></a></li>
                                                    <li class="social-twitter"> <a href="#"><img src="assets/imgs/theme/icons/icon-twitter.svg" alt=""></a></li>
                                                    <li class="social-instagram"><a href="#"><img src="assets/imgs/theme/icons/icon-instagram.svg" alt=""></a></li>
                                                    <li class="social-linkedin"><a href="#"><img src="assets/imgs/theme/icons/icon-pinterest.svg" alt=""></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <?php if (getPhone() == '') {
                                        ?>
                                            <h5 class=" text-center p-10">You must be logged in to post a comment. <a aria-label="Quick view" onclick="loginUserFororder()" class="" data-bs-toggle="modal" data-bs-target="#userlogin">login</a></h5>
                                        <?php
                                        } else {

                                        ?>

                                            <!--Comment form-->
                                            <div class="comment-form" id="commentItem">
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
                                                            </div>
                                                            <div class="form-group">
                                                                <button type="button" class="button button-contactForm" onclick="addComment(<?php echo $id; ?>,'<?php echo getPhone(); ?>')">Post Comment</button>
                                                            </div>
                                                        </form>
                                                        <div class="comments-area">
                                                            <h3 class="mb-30">Comments</h3>
                                                            <div class="comment-list m-auto">
                                                                <?php
                                                                // total comment
                                                                $curl = curl_init();

                                                                curl_setopt_array($curl, array(
                                                                    CURLOPT_URL => APIENDPOINT . "admin/blog/blog-comment.php?blog_id=" . $id,
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

                                                                $curl = curl_init();

                                                                curl_setopt_array($curl, array(
                                                                    CURLOPT_URL => APIENDPOINT . "admin/blog/blog-comment.php?blog_id=" . $id . "&page=1",
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
                                                            $perpageItem = 2;
                                                            $numberOfpage = ceil($totalComment / $perpageItem);
                                                            if ($numberOfpage <= 5 && $numberOfpage > 1) {
                                                            ?>
                                                                <?php

                                                                for ($i = 1; $i <= $numberOfpage; $i++) {

                                                                ?>
                                                                    <li id="commentPagination_<?php echo $i; ?>" class="page-item <?php if ($i == 1) {
                                                                                                                                        echo 'active';
                                                                                                                                    } ?>">
                                                                        <a class="page-link" onclick="commentPagination(<?php echo $i; ?>, <?php echo $id; ?>)"><?php echo $i; ?></a>
                                                                    </li>
                                                                <?php
                                                                }

                                                                ?>
                                                                <li class="page-item">
                                                                    <a class="page-link" onclick="commentPagination(2, <?php echo $id; ?>)"><i class="fi-rs-arrow-small-right"></i></a>
                                                                </li>
                                                            <?php
                                                            } elseif ($numberOfpage > 5) {
                                                            ?>
                                                                <?php

                                                                for ($i = 1; $i <= 5; $i++) {

                                                                ?>
                                                                    <li id="commentPagination_<?php echo $i; ?>" class="page-item <?php if ($i == 1) {
                                                                                                                                        echo 'active';
                                                                                                                                    } ?>">
                                                                        <a class="page-link" onclick="commentPagination(<?php echo $i; ?>, <?php echo $id; ?>)"><?php echo $i; ?></a>
                                                                    </li>
                                                                <?php
                                                                }

                                                                ?>
                                                                <li class="page-item">
                                                                    <a class="page-link" onclick="commentPagination(2, <?php echo $id; ?>)"><i class="fi-rs-arrow-small-right"></i></a>
                                                                </li>
                                                            <?php
                                                            }

                                                            ?>
                                                        </ul>
                                                    </nav>
                                                </div>
                                            </div>
                                        <?php }
                                        ?>
                                    </div>
                                </div>
                            </div>
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