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
                    $selectOrder = "SELECT * FROM `salesorderdetails` WHERE `orderlineno`='$item_id'";
                    $selectOrderStatement = $conn->prepare($selectOrder);
                    $selectOrderStatement->execute();
                    $number_of_rows = $selectOrderStatement->fetchColumn();
                    if ($number_of_rows != 0) {
                        $updateOrderSQL = "UPDATE `salesorderdetails` SET `completed`='3' WHERE `orderlineno`='$item_id'";

                        // if($deliverQty+$packQty < $orderQty)
                        // {
                        //     true;
                        // }



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
                            array('message' => 'Item ID is not correct.')
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