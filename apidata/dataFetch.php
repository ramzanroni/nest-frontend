<?php
include_once('./inc/apiendpoint.php');


// product data 


$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => APIENDPOINT . "product.php",
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
    echo '<script type="text/javascript">',
    'curlErrorFunction();',
    '</script>';
    // ;
    //     echo "cURL Error #:" . $err;
} else {
    $productData = json_decode($response);
    $productsArr = $productData->data->products;
    // print_r($productsArr);
}


// item with category
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => APIENDPOINT . "category.php",
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
    $category = json_decode($response);
    $categoryItemData = $category->data->category;
}
