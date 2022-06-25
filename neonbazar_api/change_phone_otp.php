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
                date_default_timezone_set("Asia/Dhaka");
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
                        $addPhone = "INSERT INTO `temp_otp`(`phone`, `otp`, `expair_at`, `counter`, `flag`) VALUES ('$phoneNumber','$otpNumber','$expDate',1,'chnagephone')";
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
                    $phonenew = $data->phoneNew;
                    $phoneold = $data->phoneOld;

                    $checkValidity = "SELECT * FROM `temp_otp` WHERE `phone`='$phonenew' AND `otp`='$otp' AND `flag`='chnagephone'";

                    $validityStatement = $conn->prepare($checkValidity);
                    $validityStatement->execute();
                    $countData = $validityStatement->rowCount();
                    if ($countData > 0) {

                        $currnetTime = date('Y-m-d H:i:s');
                        $checkExpair = "SELECT * FROM `temp_otp` WHERE `phone`='$phonenew' AND `otp`='$otp' AND `expair_at`>= '$currnetTime' AND `flag`='chnagephone'";
                        $checkExpairStatement = $conn->prepare($checkExpair);
                        $checkExpairStatement->execute();
                        $countExpari = $checkExpairStatement->rowCount();
                        if ($countExpari > 0) {
                            // delete and insert
                            $deleteOtp = "DELETE FROM `temp_otp` WHERE `phone`='$phonenew' AND `flag`='chnagephone'";
                            $deleteOtpStatement = $conn->prepare($deleteOtp);
                            $deleteOtpStatement->execute();

                            // updatePhone
                            $phoneUpdateSQL = "UPDATE `users` SET `phone`='$phonenew' WHERE `phone`='$phoneold'";
                            $updateStatement = $conn->prepare($phoneUpdateSQL);
                            $updateStatement->execute();
                            echo json_encode(
                                array(
                                    'message' => "success"
                                )
                            );
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
