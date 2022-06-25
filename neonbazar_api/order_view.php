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
                if ($_GET['token'] != '') {
                    $token = $_GET['token'];
                    $orderInfo = "SELECT salesorders.orderno AS 'orderno', salesorders.orddate AS 'orddate', salesorders.so_status AS 'so_status' FROM salesorders INNER JOIN users ON users.id = salesorders.debtorno WHERE users.user_token='$token' ORDER BY salesorders.orddate DESC";
                    $orderStatement = $conn->prepare($orderInfo);
                    $orderStatement->execute();
                    $orderResult = $orderStatement->fetchAll();
                    foreach ($orderResult as $orderValue) {
                        $json_row['orderno'] = $orderValue['orderno'];
                        $json_row['orddate'] = $orderValue['orddate'];
                        $json_row['so_status'] = $orderValue['so_status'];
                        $json[] = $json_row;
                    }
                    echo json_encode($json);
                } else {
                    echo json_encode(
                        array('message' => 'Token failed in URL..')
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
