<?php include 'inc/header.php'; ?>

<main class="main pages">
    <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                <a href="index.php" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                <span></span> My Account
            </div>
        </div>
    </div>
    <div class="page-content pt-150 pb-150">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-10 col-md-12 m-auto">
                    <div class="row">
                        <div class="col-lg-6 pr-30 d-none d-lg-block">
                            <img class="border-radius-15" src="assets/imgs/page/login-1.png" alt="" />
                        </div>
                        <div class="col-lg-6 col-md-8">
                            <div class="login_wrap widget-taber-content background-white">
                                <div class="padding_eight_all bg-white" id="loginDiv">
                                    <div class="heading_s1">
                                        <h1 class="mb-5">Login</h1>
                                        <!-- <p class="mb-30">Don't have an account? <a href="page-register.php">Create
                                                here</a></p> -->
                                    </div>
                                    <!-- <form method="post"> -->
                                    <div class="form-group">
                                        <input type="text" required="" id="phoneNumber" name="phoneNumber" placeholder="Enter your phone number*" />
                                        <small class="text-danger" id="errorNumMessage"></small>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-heading btn-block hover-up" name="login" onclick="userLogin()">Send</button>
                                    </div>
                                    <!-- </form> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include 'inc/footer.php'; ?>
<script>

</script>