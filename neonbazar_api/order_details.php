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
                if ($_GET['order_id'] != '') {
                    $ip_server = $_SERVER['SERVER_ADDR'] . "/" . "neonbazar_api/";
                    $order_id = $_GET['order_id'];

                    $orderAddress = "SELECT salesorders.deladd1 AS deladd1, salesorders.so_status AS so_status, users.realname AS realname, users.email AS email, users.phone AS phone FROM salesorders
                    INNER JOIN users
                    ON salesorders.debtorno = users.id WHERE salesorders.orderno='$order_id'";
                    $addressStatement = $conn->prepare($orderAddress);
                    $addressStatement->execute();
                    $addressInfo = $addressStatement->fetchAll();
                    $orderdetails = "SELECT  salesorderdetails.orderlineno AS orderlineno, salesorderdetails.orderno AS orderno, salesorderdetails.`stkcode` AS stkcode, salesorderdetails.unitprice AS unitprice, salesorderdetails.quantity AS quantity,  salesorderdetails.completed AS completed, stockmaster.description AS 'description',stockmaster.stockid AS 'stockid', stockmaster.img AS img FROM salesorderdetails INNER JOIN stockmaster ON stockmaster.stockid= salesorderdetails.stkcode WHERE salesorderdetails.orderno='$order_id'";
                    $detailsStatement = $conn->prepare($orderdetails);
                    $detailsStatement->execute();
                    $detailsResult = $detailsStatement->fetchAll();
                    foreach ($detailsResult as $orderValue) {
                        $json_row['orderlineno'] = $orderValue['orderlineno'];
                        $json_row['orderno'] = $orderValue['orderno'];
                        $json_row['stkcode'] = $orderValue['stkcode'];
                        $json_row['unitprice'] = $orderValue['unitprice'];
                        $json_row['quantity'] = $orderValue['quantity'];
                        $json_row['description'] = $orderValue['description'];
                        $json_row['stockid'] = $orderValue['stockid'];
                        $json_row['img'] = $ip_server . $orderValue['img'];
                        $json_row['status'] = $orderValue['completed'];
                        $json[] = $json_row;
                    }
                    $user_row['address'] = $addressInfo[0]['deladd1'];
                    $user_row['so_status'] = $addressInfo[0]['so_status'];
                    $user_row['realname'] = $addressInfo[0]['realname'];
                    $user_row['email'] = $addressInfo[0]['email'];
                    $user_row['phone'] = $addressInfo[0]['phone'];
                    $data["item"] = $json;
                    $data["info"] = $user_row;
                    echo json_encode($data);
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
