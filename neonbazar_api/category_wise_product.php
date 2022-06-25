<?php
include 'connection.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $allHeaders = getallheaders();
    if ($allHeaders['Content-Type'] == 'application/json') {
        if (!empty($allHeaders['Authorization'])) {
            $apitoken = $allHeaders['Authorization'];
            $findAPI = "SELECT * FROM api WHERE api_key='$apitoken'";
            $findAPIStatement = $conn->prepare($findAPI);
            $findAPIStatement->execute();
            $findData = $findAPIStatement->rowCount();

            if ($findData == 1) {
                $categoryID = $_GET['category_id'];
                $limit = $_GET['limit'];
                $sort_by = $_GET['sort_by'];
                if ($sort_by == "PriceLowtoHigh") {
                    $condition = "ASC";
                }
                if ($sort_by == "PriceHightoLow") {
                    $condition = "DESC";
                }
                if ($_GET['start'] == '') {
                    $start = 0;
                } else {
                    $start = $_GET['start'];
                }
                if ($categoryID != '') {
                    $ip_server = $_SERVER['SERVER_ADDR'] . "/" . "neonbazar_api/";
                    if ($limit == "All") {
                        $selectProduct = "SELECT stockmaster.stockid AS 'stockid',stockmaster.category_id AS 'category_id', stockmaster.description AS 'description', stockmaster.`longdescription` AS 'longdescription', stockmaster.`units` AS 'units', stockmaster.`discountcategory` AS 'discountcategory', stockmaster.`taxcatid` AS 'taxcatid', stockmaster.`webprice` AS 'webprice', stockmaster.`img` AS 'img', stockmaster.status AS 'status', stockgroup.groupname AS 'category', stockgroup.groupid AS 'categoryId' FROM stockmaster INNER JOIN stockgroup ON stockmaster.category_id = stockgroup.groupid WHERE stockmaster.category_id='$categoryID' ORDER BY stockmaster.webprice $condition";
                        // $selectProduct="SELECT * FROM `stockmaster` WHERE `category_id`='$categoryID' ORDER BY `webprice` $condition";
                    } else {
                        $selectProduct = "SELECT stockmaster.stockid AS 'stockid',stockmaster.category_id AS 'category_id', stockmaster.description AS 'description', stockmaster.`longdescription` AS 'longdescription', stockmaster.`units` AS 'units', stockmaster.`discountcategory` AS 'discountcategory', stockmaster.`taxcatid` AS 'taxcatid', stockmaster.`webprice` AS 'webprice', stockmaster.`img` AS 'img', stockmaster.status AS 'status', stockgroup.groupname AS 'category' FROM stockmaster INNER JOIN stockgroup ON stockmaster.category_id = stockgroup.groupid WHERE stockmaster.category_id='$categoryID' ORDER BY stockmaster.webprice $condition LIMIT $start, $limit";
                    }

                    $statement = $conn->prepare($selectProduct);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    foreach ($result as $value) {
                        $json_row['stockid'] = $value['stockid'];
                        $json_row['description'] = $value['description'];
                        $json_row['category_id'] = $value['category_id'];
                        $json_row['category'] = $value['category'];
                        $json_row['longdescription'] = $value['longdescription'];
                        $json_row['units'] = $value['units'];
                        $json_row['discountcategory'] = $value['discountcategory'];
                        $json_row['taxcatid'] = $value['taxcatid'];
                        $json_row['webprice'] = $value['webprice'];
                        $json_row['img'] = $ip_server . $value['img'];
                        $json_row['status'] = $value['status'];
                        $json[] = $json_row;
                    }
                    echo json_encode($json);
                } else {
                    echo json_encode(
                        array('message' => 'Product ID Missing')
                    );
                }
            } else {
                echo json_encode(
                    array('message' => 'Authentication Failed...')
                );
            }
        } else {
            echo json_encode(
                array('message' => 'Authentication Required')
            );
        }
    } else {
        echo json_encode(
            array('message' => 'Content Type Not Allowed')
        );
    }
} else {
    echo json_encode(
        array('message' => 'Request Type Not Allowed')
    );
}
