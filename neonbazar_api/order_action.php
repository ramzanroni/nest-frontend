<?php
include 'connection.php';

$API_Key = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE2MTg4OTU1MjIsImp0aSI6IlRQSTVmdFFUeU5MR1ZLenFOZlVhYThyRURpdEJkRmpIS0ErUGVFMTFjMTg9IiwiaXNzIjoicHVsc2VzZXJ2aWNlc2JkLmNvbSIsImRhdGEiOnsidXNlcklkIjoiMjg4MTUiLCJ1c2VyTGV2ZWwiOjJ9fQ.wQ5AQR-fIGRZgt3CN9-W6v4PkvTIvNVP8HzCOiHHeKwcd8NT1R1Dxz_XpJH9jOa7CsDzCYBklEPRtQus11NiEQ";

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $allHeaders = getallheaders();
    if ($allHeaders['Content-Type'] == 'application/json') {
        if (!empty($allHeaders['Authorization'])) {
            if ($API_Key == $allHeaders['Authorization']) {
                $name = $data->name;
                $address = $data->address;
                $area = $data->area;
                $phone = $data->phone;
                $town = $data->town;
                $additionalPhone = $data->additionalPhone;
                $additionalInfo = $data->additionalInfo;
                $token = $data->token;
                $paymentMethod = $data->paymentMethod;
                $itemInfo = $data->itemInfo;

                $orderNumber = random_int(100000, 999999);
                // order insert
                $findUserID = "SELECT * FROM `users` WHERE `user_token`='$token'";
                $findUserIDStatement = $conn->prepare($findUserID);
                $findUserIDStatement->execute();
                $userData = $findUserIDStatement->fetch();
                $userId = $userData['id'];
                $delivery_status = 0;
                $orderDate = date("Y-m-d");
                // insert order

                $orderInsertSql = $conn->prepare("INSERT INTO `salesorders`(`orderno`, `debtorno`, `comments`,`orddate`,`deladd1`,`contactphone`,`delivery_status`,`issue_date`) VALUES (:orderno,:debtorno,:comments,:orddate,:deladd1,:contactphone,:delivery_status,:issue_date)");
                $orderInsertSql->bindParam(':orderno', $orderNumber, PDO::PARAM_STR);
                $orderInsertSql->bindParam(':debtorno', $userId, PDO::PARAM_STR);
                $orderInsertSql->bindParam(':comments', $additionalInfo, PDO::PARAM_STR);
                $orderInsertSql->bindParam(':orddate', $orderDate, PDO::PARAM_STR);
                $orderInsertSql->bindParam(':deladd1', $address, PDO::PARAM_STR);
                $orderInsertSql->bindParam(':contactphone', $additionalPhone, PDO::PARAM_STR);
                $orderInsertSql->bindParam(':delivery_status', $delivery_status, PDO::PARAM_STR);
                $orderInsertSql->bindParam(':issue_date', $orderDate, PDO::PARAM_STR);
                $orderInsertSql->execute();

                // inser order details
                // echo json_encode($itemInfo);
                // exit();
                foreach ($itemInfo as $itemValue) {
                    $stkcode = 0;
                    $discount_amount = 0;
                    $orderDetailsSQL = $conn->prepare("INSERT INTO `salesorderdetails`(`orderno`,`stkcode`,`unitprice`, `quantity`, `discount_amount`) VALUES (:orderno,:stkcode,:unitprice,:quantity,:discount_amount)");
                    $orderDetailsSQL->bindParam(':orderno', $orderNumber, PDO::PARAM_STR);
                    $orderDetailsSQL->bindParam(':stkcode', $itemValue->productID, PDO::PARAM_STR);
                    $orderDetailsSQL->bindParam(':unitprice', $itemValue->unitPrice, PDO::PARAM_STR);
                    $orderDetailsSQL->bindParam(':quantity', $itemValue->productQuantity, PDO::PARAM_STR);
                    $orderDetailsSQL->bindParam(':discount_amount', $discount_amount, PDO::PARAM_STR);
                    $orderDetailsSQL->execute();
                }
                echo json_encode(
                    array('message' => 'success')
                );
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