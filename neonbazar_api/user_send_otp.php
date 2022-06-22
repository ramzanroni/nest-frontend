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
                date_default_timezone_set("Asia/Dhaka");
                if (session_id() == '') {
                    session_start();
                }
                if (array_key_exists("phoneNumber", $data)) {
                    $phoneNumber = $data->phoneNumber;
                    $findRecord = "SELECT * FROM `temp_otp` WHERE `phone`='$phoneNumber'";
                    $statement = $conn->prepare($findRecord);
                    $statement->execute();
                    $number_of_rows = $statement->rowCount();
                    if ($number_of_rows == 0) {
                        $otpNumber = rand(100000, 999999);
                        $date = strtotime(date('Y-m-d H:i:s'));
                        $expDate = date('Y-m-d H:i:s', strtotime('+2 minutes', $date));
                        $addPhone = "INSERT INTO `temp_otp`(`phone`, `otp`, `expair_at`, `counter`) VALUES ('$phoneNumber','$otpNumber','$expDate',1)";
                        $insertStatement = $conn->prepare($addPhone);
                        $insertStatement->execute();

                        // send SMS FUnction
                        echo json_encode(
                            array(
                                'message' => "success"
                            )
                        );
                    } else {
                        $findCounte = $conn->prepare($findRecord);
                        $findCounte->execute();
                        $findData = $findCounte->fetch();
                        if ($findData['counter'] < 3) {
                            $counterNew = $findData['counter'] + 1;
                            $date = strtotime(date('Y-m-d H:i:s'));
                            $otpNumber = rand(100000, 999999);
                            $expDate = date('Y-m-d H:i:s', strtotime('+2 minutes', $date));
                            $updateCounter = "UPDATE `temp_otp` SET `expair_at`='$expDate', `otp`='$otpNumber',`counter`='$counterNew' WHERE `id`='" . $findData['id'] . "'";
                            $updateCounterStatement = $conn->prepare($updateCounter);
                            $updateCounterStatement->execute();

                            // send sms function

                            echo json_encode(
                                array(
                                    'message' => "success"
                                )
                            );
                        } elseif ($findData['counter'] == 3) {
                            $currnetTime = strtotime(date('Y-m-d H:i:s'));
                            $expireTime = strtotime($findData['expair_at']);
                            $diffrence = round(abs($currnetTime - $expireTime) / 60);
                            if ($diffrence > 180) {
                                $date = strtotime(date('Y-m-d H:i:s'));
                                $otpNumber = rand(100000, 999999);
                                $expDate = date('Y-m-d H:i:s', strtotime('+60 minutes', $date));
                                $updateCounter = "UPDATE `temp_otp` SET `expair_at`='$expDate', `otp`='$otpNumber',`counter`=1 WHERE `id`='" . $findData['id'] . "'";
                                $updateCounterStatement = $conn->prepare($updateCounter);
                                $updateCounterStatement->execute();

                                // send sms function

                                echo json_encode(
                                    array(
                                        'message' => "success"
                                    )
                                );
                            } else {
                                echo json_encode(
                                    array(
                                        'message' => "You cross your limit. Please Wait 3 Hour."
                                    )
                                );
                            }
                        }
                    }
                }
                if (array_key_exists("optNumber", $data)) {
                    $otp = $data->optNumber;
                    $phone = $data->phone;
                    $checkValidity = "SELECT * FROM `temp_otp` WHERE `phone`='$phone' AND `otp`='$otp'";

                    $validityStatement = $conn->prepare($checkValidity);
                    $validityStatement->execute();
                    $countData = $validityStatement->rowCount();
                    if ($countData > 0) {

                        $currnetTime = date('Y-m-d H:i:s');
                        $checkExpair = "SELECT * FROM `temp_otp` WHERE `phone`='$phone' AND `otp`='$otp' AND `expair_at`>= '$currnetTime' ";
                        $checkExpairStatement = $conn->prepare($checkExpair);
                        $checkExpairStatement->execute();
                        $countExpari = $checkExpairStatement->rowCount();
                        if ($countExpari > 0) {
                            // delete and insert
                            $deleteOtp = "DELETE FROM `temp_otp` WHERE `phone`='$phone'";
                            $deleteOtpStatement = $conn->prepare($deleteOtp);
                            $deleteOtpStatement->execute();

                            $checkuser = "SELECT * FROM `users` WHERE `phone`='$phone'";
                            $userCheckStatement = $conn->prepare($checkuser);
                            $userCheckStatement->execute();
                            $userCheckCount = $userCheckStatement->rowCount();

                            if ($userCheckCount == 0) {
                                // insert user 
                                $dateTime = date('Y-m-d H:i:s');
                                $token = base64_encode($phone . $dateTime);
                                $insertUser = "INSERT INTO `users`(`userid`, `password`,`phone`, `address1`,`user_token`) VALUES ('','','$phone','','$token')";

                                $inserUserStatement = $conn->prepare($insertUser);
                                $inserUserStatement->execute();
                                echo json_encode(
                                    array(
                                        'message' => "success",
                                        'userPhone' => $phone,
                                        'userToken' => $token
                                    )
                                );
                            } else {
                                // select user
                                $selectUserData = "SELECT * FROM `users` WHERE `phone`='$phone'";
                                $userdataStatement = $conn->prepare($selectUserData);
                                $userdataStatement->execute();
                                $userData = $userdataStatement->fetch();

                                echo json_encode(
                                    array(
                                        'message' => "success",
                                        'userPhone' => $phone,
                                        'userToken' => $userData['user_token']
                                    )
                                );
                            }
                        } else {
                            echo json_encode(
                                array(
                                    'message' => "Your OTP Time is Expaired. Please Send Again."
                                )
                            );
                        }
                    } else {
                        echo json_encode(
                            array(
                                'message' => "Wrong otp."
                            )
                        );
                    }
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