<?php
include_once('inc/apiendpoint.php');
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
        "cache-control: no-cache",
        "postman-token: 2b84e1e1-21f0-dd0b-32d9-9cf7ed95dd96"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $category = json_decode($response);
    $categoryData = $category->data->category;
}
?>

<div class="sidebar-widget widget-category-2 mb-30">
    <h5 class="section-title style-1 mb-30">Category</h5>
    <ul>
        <?php

        foreach ($categoryData as $categoryValue) {
        ?>
            <li>
                <a href="shop-grid-right.php?category_id=<?php echo $categoryValue->categoryID; ?>"> <img src="//<?php echo $categoryValue->categoryImg; ?>" alt="" /><?php echo $categoryValue->categoryName; ?></a><span class="count"><?php echo $categoryValue->item; ?></span>
            </li>
        <?php
        }
        ?>
    </ul>
</div>