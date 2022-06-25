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
                $phoneNumber = $_GET['phoneNumber'];
                $findProfile = "SELECT * FROM `users` WHERE `phone`='$phoneNumber'";
                $findProfileStatement = $conn->prepare($findProfile);
                $findProfileStatement->execute();
                $findProfileData = $findProfileStatement->fetch();
                echo json_encode(
                    array(
                        'id' => $findProfileData['id'],
                        'userid' => $findProfileData['userid'],
                        'fullName' => $findProfileData['realname'],
                        'email' => $findProfileData['email'],
                        'phone' => $findProfileData['phone'],
                        'address' => $findProfileData['address1'],
                        'token' => $findProfileData['user_token'],
                        'active_now' => $findProfileData['active_now'],
                        'customerid' => $findProfileData['customerid']
                    )
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
