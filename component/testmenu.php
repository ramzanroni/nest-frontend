<?php
include_once('inc/apiendpoint.php');
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => APIENDPOINT . "category-find.php",
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
    $categoryItems = (array) $category->data->menu->items;
    $mainMenuList = (array)$category->data->menu->parents;
    $mainMenu = $mainMenuList[0];
    // print_r($categoryItems);
    // print_r($categoryItems[1]->groupname);
}
?>

<div class="sidebar-widget widget-category-2 mb-30">
    <h5 class="section-title style-1 mb-30">Category</h5>
    <ul>
        <?php

        foreach ($mainMenu as $categoryValue) {
        ?>

            <li onmouseover="getOtherMenu(<?php echo $categoryValue; ?>)">
                <a href="products.php?category_id=<?php echo $categoryValue; ?>"> <img src="<?php echo $categoryItems[$categoryValue]->image; ?>" alt="" /><?php echo  $categoryItems[$categoryValue]->groupname; ?></a>
            </li>
        <?php
        }

        ?>
    </ul>
    <div id="mainMenuDiv">
        <div class="sidebar-widget widget-category-2 menu-box1" id="submenu" style="display: none;">

        </div>
        <div>

        </div>
        <div class="sidebar-widget widget-category-2 menu-box2" id="submenu2" style="display: none;">

        </div>
    </div>
</div>