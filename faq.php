<?php
include 'apidata/dataFetch.php';
include 'inc/header.php';
include 'inc/apiendpoint.php';
?>
<main class="main pages">
    <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                <a href="index.php" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                <span></span> Pages <span></span> FAQ
            </div>
        </div>
    </div>
    <div class="page-content pt-50">
        <div class="container">
            <div class="row">
                <style>
                    .showBtn {
                        display: block;
                        width: 100%;
                        margin-bottom: 30px;
                        background: #f5f5f5;
                        color: #666666;
                        font-weight: 400;
                        box-shadow: none;
                        padding: 14px 16px;
                        border-radius: 5px;
                        border: 1px solid #cecdcd;
                    }

                    .hideme {
                        display: none;
                    }
                </style>
                <div class="col-xl-10 col-lg-12 m-auto">
                    <section class="row align-items-end mb-50">
                        <div class="col-lg-12">
                            <p class="showBtn h6">What is NEO Bazaar ? <span class="float-end"><i class="fi-rs-angle-down"></i></span></p>
                            <!-- <a class="showBtn">BUTTON</a> -->

                            <div class="hideme">

                                <p class="mb-20">NEO Bazaar is a manufacturing & marketing their own sourcing and produced pure products by their website and Facebook page. “always pure” is our slogan. We never compromise purity for any reason. We are fully committed to reach pure products to the customer.</p>

                                <p class="mb-20">Considering huge adulterated spices in the market, it is very difficult to get unadulterated products even in exchange for money. With this feeling, we have been planning for a long time to source, produce pure products for us. We are not planning to do business only; we want to reach pure product to the customers also. We are more concern about purity equal to value. We decided after a long period of close observation and monitoring to start this business. We have a long business background for other products and supply chain. So, we have a great mission and vision to succeed in our planning.</p>
                            </div>

                            <p class="showBtn h6">Do we have any nearby stores ? <span class="float-end"><i class="fi-rs-angle-down"></i></span></p>

                            <div class="hideme">
                                <p class="mb-20">Our store in Uttara and Gazipur . </p>
                            </div>
                            <p class="showBtn h6">Which areas do we serve in ? <span class="float-end"><i class="fi-rs-angle-down"></i></span></p>

                            <div class="hideme">
                                <p class="mb-20">We are currently serving in Dhaka, Chattogram, Narayanganj, and Gazipur.</p>
                            </div>
                            <p class="showBtn h6">How can I contact you ?<span class="float-end"><i class="fi-rs-angle-down"></i></span></p>

                            <div class="hideme">
                                <p class="mb-20">Greetings from NEO Bazaar!!!<br>
                                    NEO Bazaar is a consumer e-commerce platform. Please place your order to-<br>
                                    1. Website: www.neo-bazaar.com<br>
                                    2. Facebook Page: facebook.com/neobazaar20<br>
                                    3. Linkedin Page : https://www.linkedin.com/company/neo-bazaarbd/<br>
                                    4. WhatsApp: +880 1859-893939<br>
                                    5. Direct Call/SMS: +880 1859-893939<br>
                                    Thanks for staying with us.<br>
                                    *-NEO Bazaar Team</p>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    $('.showBtn').click(function() {
        //$('.hideme').hide();  
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $('.hideme').slideUp();
        } else {
            $('.hideme').slideUp();
            $('.showBtn').removeClass('active');
            $(this).addClass('active');
            $(this).next().filter('.hideme').slideDown();
        }
    });
</script>
<?php
include 'inc/footer.php';
?>