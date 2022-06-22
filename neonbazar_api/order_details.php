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
                if ($_GET['order_id'] != '') {
                    $ip_server = $_SERVER['SERVER_ADDR'] . "/" . "neonbazar_api/";
                    $order_id = $_GET['order_id'];
                    $orderdetails = "SELECT salesorderdetails.orderno AS orderno, salesorderdetails.`stkcode` AS stkcode, salesorderdetails.unitprice AS unitprice, salesorderdetails.quantity AS quantity, stockmaster.description AS 'description',stockmaster.stockid AS 'stockid', stockmaster.img AS img FROM salesorderdetails INNER JOIN stockmaster ON stockmaster.stockid= salesorderdetails.stkcode WHERE salesorderdetails.orderno='876354'";
                    $detailsStatement = $conn->prepare($orderdetails);
                    $detailsStatement->execute();
                    $detailsResult = $detailsStatement->fetchAll();
                    foreach ($detailsResult as $orderValue) {
                        $json_row['orderno'] = $orderValue['orderno'];
                        $json_row['stkcode'] = $orderValue['stkcode'];
                        $json_row['unitprice'] = $orderValue['unitprice'];
                        $json_row['quantity'] = $orderValue['quantity'];
                        $json_row['description'] = $orderValue['description'];
                        $json_row['stockid'] = $orderValue['stockid'];
                        $json_row['img'] = $ip_server . $orderValue['img'];
                        $json[] = $json_row;
                    }
                    echo json_encode($json);
                } else {
                    echo json_encode(
                        array('message' => 'Order ID failed in URL..')
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