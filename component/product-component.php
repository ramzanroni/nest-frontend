<div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
    <?php
    $cartCookiesProduct = json_decode($_COOKIE['shopping_cart']);
    $cartItem = count($cartCookiesProduct);
    ?>
    <div class="product-cart-wrap mb-30">
        <div class="product-img-action-wrap text-center">
            <div class="product-img product-img-zoom text-center">
                <a href="product.php?product_id=<?php echo $productData->stockid; ?>">
                    <img class="default-img" src="<?php if ($productData->img != '') {
                                                        echo "//" . $productData->img;
                                                    } else {
                                                        echo 'assets/imgs/product.png';
                                                    } ?>" alt="" />
                </a>
            </div>
        </div>
        <div class="product-content-wrap">
            <div class="product-category">
                <a href="products.php?category_id=<?php echo $productData->category_id; ?>"><?php echo $productData->category; ?></a>
            </div>
            <h2><a href="product.php?product_id=<?php echo $productData->stockid; ?>"><?php echo $productData->description; ?></a>
            </h2>
            <div class="product-price">
                <span>৳<?php echo $productData->webprice; ?></span>
                <span class="float-end"><span class="text-dark" style="color: #adadad !important; font-size: 12px;">UoM </span><?php echo $productData->units; ?></span>
            </div>
            <div class="product-card-bottom">

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
                    <div id="item_<?= $productData->stockid ?>" class="divsize">
                        <div class="add-cart">
                            <a class="add divsize" onclick="firstAddtoCart(<?php echo $productData->stockid; ?>,'<?php echo $productData->description; ?>',<?php echo $productData->webprice; ?>,1,'<?php echo $productData->img; ?>')"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
                        </div>
                    </div>
                <?php
                } else {
                ?>
                    <div id="item_<?= $productData->stockid ?>" class="d-flex divsize">
                        <input type="hidden" id="getItem_<?php echo $productData->stockid; ?>" value="<?php echo $numberOfItem; ?>">
                        <div class="add-cart">
                            <a class="add" style="border-radius: 5px 0px 0px 5px;" onclick="CartItemChange('decrement', <?php echo $productData->stockid; ?>,'<?php echo $productData->description; ?>',<?php echo $productData->webprice; ?>,'<?php echo $productData->img;  ?>')"><i class="fi-rs-minus"></i> </a>
                        </div>
                        <div class="add-cart midCart">
                            <a class="add middlediv" style="border-radius: 0 !important;"><i class="fi-rs-shopping-cart mr-5"></i><span id="cartCount_<?php echo $productData->stockid; ?>"><?php echo $numberOfItem; ?></span> </a>
                        </div>
                        <div class="add-cart">
                            <a class="add" style="border-radius: 0px 5px 5px 0px;" onclick="CartItemChange('increment', <?php echo $productData->stockid; ?>,'<?php echo $productData->description; ?>',<?php echo $productData->webprice; ?>,'<?php echo $productData->img;  ?>')"><i class="fi-rs-plus"></i></a>
                        </div>
                        <!-- <div class="col-10 float-end after-cart">
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
                        </div> -->
                    </div>

                <?php
                }
                ?>
                <!-- </span> -->

            </div>
        </div>
    </div>
</div>