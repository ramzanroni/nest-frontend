<?php
if($_POST['check']=="sortingProductList")
{
    $limit=$_POST['limitValueData'];
    $sortby=$_POST['sortByData'];
    $catID=$_POST['catID'];
    setcookie('product_limit', $limit, time() + (86400 * 30), "/");
    setcookie('sort_by', $sortby, time() + (86400 * 30), "/");
    $url = 'http://192.168.0.116/neonbazar_api/category_wise_product.php?category_id='.$catID.'&limit='.$limit.'&sort_by='.$sortby; //url will be here
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array( //header will be here
        'Content-Type: application/json',
        'Authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE2MTg4OTU1MjIsImp0aSI6IlRQSTVmdFFUeU5MR1ZLenFOZlVhYThyRURpdEJkRmpIS0ErUGVFMTFjMTg9IiwiaXNzIjoicHVsc2VzZXJ2aWNlc2JkLmNvbSIsImRhdGEiOnsidXNlcklkIjoiMjg4MTUiLCJ1c2VyTGV2ZWwiOjJ9fQ.wQ5AQR-fIGRZgt3CN9-W6v4PkvTIvNVP8HzCOiHHeKwcd8NT1R1Dxz_XpJH9jOa7CsDzCYBklEPRtQus11NiEQ',
    )
    );
    $categoryInfo = curl_exec($ch);
    curl_close($ch);
    $categoryData= json_decode($categoryInfo);
    $totalProduct= count($categoryData);
    $cartCookiesProduct=json_decode($_COOKIE['shopping_cart']);
    //total item of this category
    $url = 'http://192.168.0.116/neonbazar_api/total_number_of_item_category_wise.php?category_id='.$catID;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array( //header will be here
        'Content-Type: application/json',
        'Authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE2MTg4OTU1MjIsImp0aSI6IlRQSTVmdFFUeU5MR1ZLenFOZlVhYThyRURpdEJkRmpIS0ErUGVFMTFjMTg9IiwiaXNzIjoicHVsc2VzZXJ2aWNlc2JkLmNvbSIsImRhdGEiOnsidXNlcklkIjoiMjg4MTUiLCJ1c2VyTGV2ZWwiOjJ9fQ.wQ5AQR-fIGRZgt3CN9-W6v4PkvTIvNVP8HzCOiHHeKwcd8NT1R1Dxz_XpJH9jOa7CsDzCYBklEPRtQus11NiEQ',
    )
    );
    $categoryItemInfo = curl_exec($ch);
    curl_close($ch);
    $categoryDataCount= json_decode($categoryItemInfo);
    $totalCategoryItem=$categoryDataCount[0]->totalItem;

    foreach ($categoryData as $productData) 
    {
     ?>
<div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
    <div class="product-cart-wrap mb-30">
        <div class="product-img-action-wrap">
            <div class="product-img product-img-zoom">
                <a href="shop-product-right.php?product_id=<?php echo $productData->stockid; ?>">
                    <img class="default-img" src="//<?php echo $productData->img; ?>" alt="" />
                    <!-- <img class="hover-img" src="assets/imgs/shop/product-1-2.jpg" alt="" /> -->
                </a>
            </div>
            <div class="product-badges product-badges-position product-badges-mrg">
                <span class="hot"><?php echo $productData->units; ?></span>
            </div>
        </div>
        <div class="product-content-wrap">
            <div class="product-category">
                <a href="shop-grid-right.html"><?php echo $productData->category; ?></a>
            </div>
            <h2><a
                    href="shop-product-right.php?product_id=<?php echo $productData->stockid; ?>"><?php echo $productData->description; ?></a>
            </h2>
            <div class="product-rate-cover">
                <div class="product-rate d-inline-block">
                    <div class="product-rating" style="width: 90%"></div>
                </div>
                <span class="font-small ml-5 text-muted"> (4.0)</span>
            </div>
            <div class="product-card-bottom">
                <div class="product-price">
                    <span>৳<?php echo $productData->webprice; ?></span>
                    <!-- <span class="old-price"><?php echo $item['price']; ?></span> -->
                </div>
                <?php 
                    $cartProductID=''; 
                    $numberOfItem='';
                    foreach ($cartCookiesProduct as $cartKey => $itemValue) 
                        {
                            if($itemValue->productID==$productData->stockid)
                            {
                                $cartProductID=$itemValue->productID;
                                $numberOfItem=$itemValue->productQuantity;
                            }
                        }
                        if($cartProductID=='')
                        {
                        ?>
                <div class="add-cart">
                    <a class="add"
                        onclick="addtoCart(<?php echo $productData->stockid; ?>,'<?php echo $productData->description; ?>',<?php echo $productData->webprice; ?>,1,'<?php echo $productData->img; ?>' )"><i
                            class="fi-rs-shopping-cart mr-5"></i>Add </a>
                </div>
                <?php
                        }
                        else
                        {
                        ?>
                <input type="hidden" id="getItem_<?php echo $productData->stockid; ?>"
                    value="<?php echo $numberOfItem; ?>">
                <div class="col-8 float-end after-cart">
                    <div class="col-3 float-end increment"
                        onclick="CartItemChange('increment', <?php echo $productData->stockid; ?>)">
                        <a><i class="fi-rs-plus"></i></a>
                    </div>
                    <div class="col-6 float-end middle">
                        <a><i class="fi-rs-shopping-cart"></i>
                            <span
                                id="cartCount_<?php echo $productData->stockid; ?>"><?php echo $numberOfItem; ?></span>
                        </a>
                    </div>
                    <div class="col-3 float-end add decrement"
                        onclick="CartItemChange('decrement', <?php echo $productData->stockid; ?>)">
                        <a><i class="fi-rs-minus"></i></a>
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
                $currentPage=1;
                $nextPage=$currentPage+1;
                $previousPage=$currentPage-1;
                $perpageItem=$limit;
                $numberOfpage=ceil($totalCategoryItem/$perpageItem);
                ?>
            <li class="page-item">
                <a class="page-link" onclick="pagination(1)"><i class="fi-rs-arrow-small-left"></i></a>
            </li>
            <?php
                for ($i=1; $i <= $numberOfpage; $i++) { ?>
            <li id="pagination_<?php echo $i; ?>" class="page-item <?php if($i==1){echo 'active';} ?>">
                <a class="page-link" onclick="pagination(<?php echo $i; ?>)"><?php echo $i; ?></a>
            </li>
            <?php
                 }
               ?>
            <li class="page-item">
                <a class="page-link" onclick="pagination(2)"><i class="fi-rs-arrow-small-right"></i></a>
            </li>
        </ul>
    </nav>
</div>
<?php
}
?>