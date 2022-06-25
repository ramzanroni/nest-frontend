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
                $phoneNumber = $data->phoneNumber;
                $fullName = $data->fullName;
                $emailAddress = $data->emailAddress;
                $userAddress = $data->userAddress;
                $updateUserInfo = "UPDATE `users` SET `realname`='$fullName',`email`='$emailAddress',`address1`='$userAddress' WHERE `phone`='$phoneNumber'";
                $updateUserInfoStatement = $conn->prepare($updateUserInfo);
                $updateUserInfoStatement->execute();
                if ($updateUserInfoStatement) {
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
