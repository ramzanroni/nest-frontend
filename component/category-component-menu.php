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
<style>
    .menudiv {
        height: 100%;
    }

    .menudiv>ul {
        background-color: white;
        position: relative;
        overflow: visible;
    }

    .menudiv>ul>li:hover {
        background-color: white;
    }

    .menudiv>ul>li>ul {
        display: none;
        position: absolute;
        right: -250px;
        top: -104px;
        width: 249px;
        background-color: white;
        z-index: 99;
        border: 1px solid #ececec;
        border-radius: 15px;
        -webkit-box-shadow: 5px 5px 15px rgb(0 0 0 / 5%);
        box-shadow: 5px 5px 15px rgb(0 0 0 / 5%);
        height: 126%;
    }

    .menudiv>ul>li:hover>ul {
        display: block;
    }

    .menudiv>ul>li>ul>li:hover {
        background-color: white;
    }

    .menudiv>ul>li>ul>li>ul {
        display: none;
        position: absolute;
        right: -251px;
        top: 0;
        width: 250px;
        background-color: white;
        /* padding: 30px; */
        border: 1px solid #ececec;
        border-radius: 15px;
        -webkit-box-shadow: 5px 5px 15px rgb(0 0 0 / 5%);
        box-shadow: 5px 5px 15px rgb(0 0 0 / 5%);
        height: 100%;
    }

    .menudiv>ul>li>ul>li:hover ul {
        display: block;
    }

    .menudiv>ul>li>ul>li>ul>li:hover {
        background-color: white;
    }

    .menudiv>ul>li>ul>li ul li ul li {
        border-bottom: 1px dotted #fff;
        /* padding: 20px; */
    }

    .sidebar-widget-menu {
        position: relative;
        border: 1px solid #ececec;
        border-radius: 15px;
        -webkit-box-shadow: 5px 5px 15px rgb(0 0 0 / 5%);
        box-shadow: 5px 5px 15px rgb(0 0 0 / 5%);
    }

    .section-title-style {
        border-bottom: 1px solid #ececec;
        padding-bottom: 20px;
        padding-top: 20px;
        font-size: 24px;
        padding-left: 50px;
    }
</style>

<div class="sidebar-widget-menu widget-category-2 mb-30">
    <h5 class="section-title-style mb-30">Category</h5>
    <div class="menudiv">
        <ul class="">
            <?php
            foreach ($mainMenu as $categoryValue) {
                $secondSubMenu = $mainMenuList[$categoryValue];
                if (count($secondSubMenu) > 0) {
            ?>
                    <li class="">
                        <a tabindex="-1" href="products.php?category_id=<?php echo $categoryValue; ?>"> <img src="<?php echo $categoryItems[$categoryValue]->image; ?>" alt="" /><?php echo  $categoryItems[$categoryValue]->groupname; ?> </a><span class="count">></span>
                        <ul class="">
                            <?php
                            foreach ($secondSubMenu as $secondSubID) {
                                $thirdSubMenu = $mainMenuList[$secondSubID];
                                if (count($thirdSubMenu) > 0) {
                            ?>
                                    <li class="">
                                        <a href="products.php?category_id=<?php echo $secondSubID; ?>"><?php echo  $categoryItems[$secondSubID]->groupname; ?></a><span class="count">></span>

                                        <ul class="parent">
                                            <?php
                                            foreach ($thirdSubMenu as $thirdSubMenuID) {
                                            ?>
                                                <li><a href="products.php?category_id=<?php echo $thirdSubMenuID; ?>"><?php echo  $categoryItems[$thirdSubMenuID]->groupname; ?> </a></li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                                } else {
                                ?>
                                    <li class=""><a href="products.php?category_id=<?php echo $secondSubID; ?>"><?php echo  $categoryItems[$secondSubID]->groupname; ?></a></li>
                                <?php
                                }
                                ?>

                            <?php
                            }
                            ?>
                        </ul>
                    </li>
                <?php
                } else {
                ?>
                    <li><a href="products.php?category_id=<?php echo $categoryValue; ?>"><?php echo  $categoryItems[$categoryValue]->groupname; ?></a></li>
            <?php
                }
            }
            ?>
        </ul>

    </div>
</div>

<script>
    $('.child').hide(); //Hide children by default

    $('.parent').children().click(function() {
        // event.preventDefault();
        $(this).children('.child').slideToggle('slow');
        $(this).find('span').toggle();
    });
</script>