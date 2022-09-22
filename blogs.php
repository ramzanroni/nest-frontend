<?php
include 'apidata/dataFetch.php';
include 'inc/header.php';
include 'config.php';
$subUrl = '';
$categoryId = 0;
$subUrlTotal = '';
if (isset($_GET['category_id']) && $_GET['category_id'] != '') {
    $categoryId = $_GET['category_id'];
    $subUrlTotal = "admin/blog/blog.php?category_id=" . $categoryId;
    $subUrl = "admin/blog/blog.php?category_id=" . $categoryId . "&start=0&limit=2";
} else {
    $subUrlTotal = "admin/blog/blog.php";
    $subUrl = "admin/blog/blog.php?start=0&limit=2";
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

// blogs 
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
<main class="main">
    <div class="page-header mt-30 mb-75">
        <div class="container">
            <div class="archive-header">
                <div class="row align-items-center">
                    <div class="col-xl-3">
                        <h1 class="mb-15">Blogs</h1>
                        <div class="breadcrumb">
                            <a href="index.php" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                            <span></span> Blogs
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-content mb-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-12" id="blogItem">
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
                                $perpageItem = 2;
                                $numberOfpage = ceil($totalBlog / $perpageItem);
                                if ($numberOfpage <= 5 && $numberOfpage > 1) {
                                ?>
                                    <?php

                                    for ($i = 1; $i <= $numberOfpage; $i++) {

                                    ?>
                                        <li id="blogPagination_<?php echo $i; ?>" class="page-item <?php if ($i == 1) {
                                                                                                        echo 'active';
                                                                                                    } ?>">
                                            <a class="page-link" onclick="blogPagination(<?php echo $i; ?>, <?php echo $categoryId; ?>)"><?php echo $i; ?></a>
                                        </li>
                                    <?php
                                    }

                                    ?>
                                    <li class="page-item">
                                        <a class="page-link" onclick="blogPagination(2, <?php echo $categoryId; ?>)"><i class="fi-rs-arrow-small-right"></i></a>
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
                                            <a class="page-link" onclick="blogPagination(<?php echo $i; ?>, <?php echo $categoryId; ?>)"><?php echo $i; ?></a>
                                        </li>
                                    <?php
                                    }

                                    ?>
                                    <li class="page-item">
                                        <a class="page-link" onclick="blogPagination(2, <?php echo $categoryId; ?>)"><i class="fi-rs-arrow-small-right"></i></a>
                                    </li>
                                <?php
                                }

                                ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'inc/footer.php'; ?>