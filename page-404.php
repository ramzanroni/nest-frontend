<?php
include 'apidata/dataFetch.php';
include 'inc/header.php';
include 'config.php';
?>
<main class="main page-404">
    <div class="page-content pt-150 pb-150">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-10 col-md-12 m-auto text-center">
                    <p class="mb-20"><img src="assets/imgs/page/page-404.png" alt="" class="hover-up" /></p>
                    <h1 class="display-2 mb-30">Page Not Found</h1>
                    <p class="font-lg text-grey-700 mb-30">
                        The link you clicked may be broken or the page may have been removed.<br />
                        visit the <a href="index.php"> <span> Homepage</span></a> or <a href="page-contact.php"><span>Contact us</span></a> about the problem
                    </p>
                    <a class="btn btn-default submit-auto-width font-xs hover-up mt-30" href="index.php"><i class="fi-rs-home mr-5"></i> Back To Home Page</a>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include 'inc/footer.php'; ?>