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
                $productName = "%" . $_GET['product_name'] . "%";
                $category = $_GET['category'];
                if ($productName != '') {
                    $ip_server = $_SERVER['SERVER_ADDR'] . "/" . "neonbazar_api/";
                    if ($category == "") {
                        $selectProduct = "SELECT * FROM `stockmaster` WHERE `description` LIKE '$productName'";
                    } else {
                        $selectProduct = "SELECT * FROM `stockmaster` WHERE `description` LIKE '$productName' AND `category_id`='$category'";
                    }
                    $statement = $conn->prepare($selectProduct);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    foreach ($result as $value) {
                        $multipleImg = "SELECT * FROM `item_ref` WHERE stockid='" . $value['stockid'] . "'";
                        $statementImg = $conn->prepare($multipleImg);
                        $statementImg->execute();
                        $resultImg = $statementImg->fetchAll();
                        $imgArr = array();
                        foreach ($resultImg as $imgValue) {
                            array_push($imgArr, $ip_server . $imgValue['doc_name']);
                        }
                        $json_row['multipleImg'] = $imgArr;
                        $json_row['stockid'] = $value['stockid'];
                        $json_row['description'] = $value['description'];
                        $json_row['category_id'] = $value['category_id'];
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
                        array('message' => 'Product name missing')
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
