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