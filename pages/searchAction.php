<?php
include '../inc/function.php';
include '../inc/apiendpoint.php';
if ($_POST['check'] == "searchItem") {
    $categoryId = trim($_POST['categoryName']);
    $productSearchItem = trim($_POST['productSearchItem']);
    if ($categoryId == "") {
        $url = 'product.php?product_name=' . urlencode($productSearchItem);
    } else {
        $url = 'product.php?product_name=' . urlencode($productSearchItem) . '&category=' . $categoryId; //url will be here
    }


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . $url,
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
        $result = json_decode($response);
        $searchData = $result->data->products;
        $totalProduct = $result->data->rows_returned;
    }


?>
    <?php
    $i = 1;
    if ($totalProduct > 0) {
        foreach ($searchData as $itemValue) {
            if ($i % 2 != 0) {
                echo '<div class="row">';
            }
    ?>

            <div class="col-6 p-2 serchItemBox">
                <div class="col-3 p-2 float-start">
                    <img src="//<?php echo $itemValue->img; ?>" alt="" width="80px" height="80px">
                </div>
                <div class="col-7 float-end">
                    <a href="product.php?product_id=<?php echo $itemValue->stockid; ?>" class="h6"><?php echo $itemValue->description; ?></a><br>
                    <span class="bg-brand pt-1 pb-1 pr-10 pl-10 text-white border-radius-10"><?php echo $itemValue->webprice; ?></span>
                </div>
            </div>


            <!-- <div class="col-7 p-2 float-start">Hello</div> -->
        <?php
            if ($i % 2 == 0) {
                echo '</div>';
            }
            $i++;
        }

        if ($totalProduct % 2 != 0) {
            echo '</div>';
        }

        ?>
        <div class="row">
            <div class="col-12 text-center p-3">
                <a onclick="viewAllItem('<?php echo $categoryId; ?>','<?php echo  $productSearchItem; ?>')">Show All</a>
            </div>
        </div>
    <?php
    } else {
        echo "Nothing found.";
    }
    ?>
    <?php
}



if ($_POST['check'] == "searchItemMobile") {

    $categoryId = '';
    $productSearchItem = trim($_POST['searchString']);
    if ($categoryId == "") {
        $url = 'product.php?product_name=' . urlencode($productSearchItem);
    } else {
        $url = 'product.php?product_name=' . urlencode($productSearchItem) . '&category=' . $categoryId; //url will be here
    }


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . $url,
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
        $result = json_decode($response);
        $searchData = $result->data->products;
        $totalProduct = $result->data->rows_returned;
    }



    // $searchString = $_POST['searchString'];



    if ($totalProduct > 0) {
    ?>
        <div class="col-12">
            <?php
            $productSliceArr = array_slice($searchData, 0, 10, true);
            foreach ($productSliceArr as $searchItemValue) {
            ?>
                <div class="row border-bottom p-2">
                    <div class="col-3 p-2 float-start">
                        <img src="//<?php echo $searchItemValue->img; ?>" alt="" width="40px" height="40px">
                    </div>
                    <div class="col-7 float-end pt-10">
                        <a href="product.php?product_id=<?php echo $searchItemValue->stockid; ?>" class="h6"><?php echo $searchItemValue->description; ?></a><br>
                        <span class="bg-brand mt-10 pt-1 pb-1 pr-10 pl-10 text-white border-radius-10">৳<?php echo $searchItemValue->webprice; ?></span>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="col-12 text-center p-3 border-bottom">
            <a onclick="viewAllItemMobile('<?php echo  $productSearchItem; ?>')">Show All</a>
        </div>
<?php
    } else {
        echo "No product found..!";
    }
}
?>