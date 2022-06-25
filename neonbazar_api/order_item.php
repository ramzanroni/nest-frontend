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
                $item_id = $_GET['item_id'];
                if ($item_id != '') {
                    $ip_server = $_SERVER['SERVER_ADDR'] . "/" . "neonbazar_api/";
                    $selectItem = "SELECT * FROM `salesorderdetails` WHERE `orderlineno`='$item_id'";
                    $itemStatement = $conn->prepare($selectItem);
                    $itemStatement->execute();
                    $itemResult = $itemStatement->fetchAll();
                    foreach ($itemResult as $value) {
                        $json_row['orderno'] = $value['orderno'];
                        $json_row['stkcode'] = $value['stkcode'];
                        $json_row['unitprice'] = $value['unitprice'];
                        $json_row['quantity'] = $value['quantity'];
                        $json_row['units'] = $value['units'];
                        $json_row['tax_rate'] = $value['tax_rate'];
                        $json_row['discountpercent'] = $value['discountpercent'];
                        $json_row['completed'] = $value['completed'];
                        $json_row['discount_amount'] = $value['discount_amount'];
                        $json_row['discount_flag'] = $value['discount_flag'];
                        $json[] = $json_row;
                    }
                    echo json_encode($json);
                } else {
                    echo json_encode(
                        array('message' => 'Item ID Missing')
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
