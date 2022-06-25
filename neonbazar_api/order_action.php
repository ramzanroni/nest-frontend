<?php
include 'connection.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $allHeaders = getallheaders();
    if ($allHeaders['Content-Type'] == 'application/json') {
        if (!empty($allHeaders['Authorization'])) {
            $apitoken = $allHeaders['Authorization'];
            $findAPI = "SELECT * FROM api WHERE api_key='$apitoken'";
            $findAPIStatement = $conn->prepare($findAPI);
            $findAPIStatement->execute();
            $findData = $findAPIStatement->rowCount();
            if ($findData == 1) {
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
                try {
                    // First of all, let's begin a transaction
                    $conn->beginTransaction();

                    // order insert
                    $findUserID = "SELECT * FROM users WHERE user_token='$token'";
                    $findUserIDStatement = $conn->prepare($findUserID);
                    $findUserIDStatement->execute();
                    $userData = $findUserIDStatement->fetch();
                    $userId = $userData['id'];
                    $delivery_status = 0;
                    $orderDate = date("Y-m-d");
                    // insert order

                    $orderInsertSql = $conn->prepare("INSERT INTO salesorders(orderno, debtorno, comments,orddate,deladd1,contactphone,delivery_status,issue_date) VALUES (:orderno,:debtorno,:comments,:orddate,:deladd1,:contactphone,:delivery_status,:issue_date)");
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
                        $orderDetailsSQL = $conn->prepare("INSERT INTO salesorderdetails(orderno,stkcode,unitprice, quantity, discount_amount) VALUES (:orderno,:stkcode,:unitprice,:quantity,:discount_amount)");
                        $orderDetailsSQL->bindParam(':orderno', $orderNumber, PDO::PARAM_STR);
                        $orderDetailsSQL->bindParam(':stkcode', $itemValue->productID, PDO::PARAM_STR);
                        $orderDetailsSQL->bindParam(':unitprice', $itemValue->unitPrice, PDO::PARAM_STR);
                        $orderDetailsSQL->bindParam(':quantity', $itemValue->productQuantity, PDO::PARAM_STR);
                        $orderDetailsSQL->bindParam(':discount_amount', $discount_amount, PDO::PARAM_STR);
                        $orderDetailsSQL->execute();
                    }
                    $conn->commit();
                    echo json_encode(
                        array('message' => 'success')
                    );
                } catch (\Throwable $e) {
                    // An exception has been thrown
                    // We must rollback the transaction
                    $conn->rollback();
                    throw json_encode(
                        array('message' => $e)
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
