<?php
include 'connection.php';

$API_Key = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE2MTg4OTU1MjIsImp0aSI6IlRQSTVmdFFUeU5MR1ZLenFOZlVhYThyRURpdEJkRmpIS0ErUGVFMTFjMTg9IiwiaXNzIjoicHVsc2VzZXJ2aWNlc2JkLmNvbSIsImRhdGEiOnsidXNlcklkIjoiMjg4MTUiLCJ1c2VyTGV2ZWwiOjJ9fQ.wQ5AQR-fIGRZgt3CN9-W6v4PkvTIvNVP8HzCOiHHeKwcd8NT1R1Dxz_XpJH9jOa7CsDzCYBklEPRtQus11NiEQ";

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $allHeaders = getallheaders();
    if ($allHeaders['Content-Type'] == 'application/json') {
        if (!empty($allHeaders['Authorization'])) {
            if ($API_Key == $allHeaders['Authorization']) {
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
