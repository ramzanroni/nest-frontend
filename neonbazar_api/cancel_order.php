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
                $order_id = $_GET['order_id'];
                if ($order_id != '') {
                    $selectOrder = "SELECT * FROM `salesorders` WHERE `orderno`='$order_id'";
                    $selectOrderStatement = $conn->prepare($selectOrder);
                    $selectOrderStatement->execute();
                    $number_of_rows = $selectOrderStatement->fetchColumn();
                    if ($number_of_rows != 0) {


                        $updateOrderSQL = "UPDATE `salesorders` SET `so_status`='3' WHERE `orderno`='$order_id'";
                        $updateStatement = $conn->prepare($updateOrderSQL);
                        $updateStatement->execute();
                        if ($updateStatement) {
                            echo json_encode(
                                array('message' => 'success')
                            );
                        } else {
                            echo json_encode(
                                array('message' => 'Something is wrong')
                            );
                        }
                    } else {
                        echo json_encode(
                            array('message' => 'Order ID is not correct.')
                        );
                    }
                } else {
                    echo json_encode(
                        array('message' => 'Order ID Missing')
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
