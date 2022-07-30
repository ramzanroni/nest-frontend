<?php
include_once('db.php');
include_once('../model/response.php');
$allHeaders = getallheaders();
$apiSecurity = $allHeaders['authorization'];
if ($apiKey != $apiSecurity) {
    $response = new Response();
    $response->setHttpStatusCode(401);
    $response->setSuccess(false);
    $response->addMessage("API Security Key Doesn't exist.");
    $response->send();
    exit;
}
try {
    $writeDB = DB::connectWriteDB();
    $readDB = DB::connectReadDB();
} catch (PDOException $ex) {
    error_log("Connection error - " . $ex, 0);
    $response = new Response();
    $response->setHttpStatusCode(500);
    $response->setSuccess(false);
    $response->addMessage("Database Connection Error");
    $response->send();
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        date_default_timezone_set("Asia/Dhaka");
        if ($_SERVER['CONTENT_TYPE'] !== 'application/json') {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("Content type header is not set to JSON");
            $response->send();
            exit();
        }
        $rawPostData = file_get_contents('php://input');
        if (!$jsonData = json_decode($rawPostData)) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("Request body is not valid JSON");
            $response->send();
            exit();
        }
        if (isset($jsonData->phoneNumber)) {
            $phoneNumber = $jsonData->phoneNumber;
            if (!is_numeric($phoneNumber) || strlen($phoneNumber) != 11) {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Phone number can be only numeric and must 11 digit.");
                $response->send();
                exit;
            }
            date_default_timezone_set("Asia/Dhaka");
            $findRecord = $readDB->prepare('SELECT * FROM temp_otp WHERE phone=:phone');
            $findRecord->BindParam(':phone', $phoneNumber, PDO::PARAM_STR);
            $findRecord->execute();
            $number_of_rows =  $findRecord->rowCount();
            if ($number_of_rows == 0) {
                $otpNumber = rand(100000, 999999);
                $flag = 'changephone';
                $counter = 1;
                $date = strtotime(date('Y-m-d H:i:s'));
                $expDate = date('Y-m-d H:i:s', strtotime('+2 minutes', $date));
                $addPhone = $writeDB->prepare('INSERT INTO temp_otp(phone, otp, expair_at, counter, flag) VALUES (:phone,:otp,:expair,:counter,:flag)');
                $addPhone->bindParam(':phone', $phoneNumber, PDO::PARAM_STR);
                $addPhone->bindParam(':otp', $otpNumber, PDO::PARAM_STR);
                $addPhone->bindParam(':expair', $expDate, PDO::PARAM_STR);
                $addPhone->bindParam(':counter', $counter, PDO::PARAM_STR);
                $addPhone->bindParam(':flag', $flag, PDO::PARAM_STR);
                $addPhone->execute();
                $rowCount = $addPhone->rowCount();
                if ($rowCount == 0) {
                    $response = new Response();
                    $response->setHttpStatusCode(500);
                    $response->setSuccess(false);
                    $response->addMessage("Failed to send message");
                    $response->send();
                    exit();
                } else {
                    $response = new Response();
                    $response->setHttpStatusCode(200);
                    $response->setSuccess(true);
                    $response->toCache(true);
                    $response->addMessage('OTP Send success.');
                    $response->send();
                    exit;
                }
            } else {
                $findData = $findRecord->fetch();
                $id = $findData['id'];
                if ($findData['counter'] < 3) {
                    $counterNew = $findData['counter'] + 1;
                    $date = strtotime(date('Y-m-d H:i:s'));
                    $otpNumber = rand(100000, 999999);
                    $expDate = date('Y-m-d H:i:s', strtotime('+2 minutes', $date));

                    $updateCounter = $writeDB->prepare('UPDATE `temp_otp` SET `expair_at`=:expDate, `otp`=:otpNumber,`counter`=:counterNew WHERE `id`=:id');
                    $updateCounter->BindParam(':expDate', $expDate, PDO::PARAM_STR);
                    $updateCounter->BindParam(':otpNumber', $otpNumber, PDO::PARAM_STR);
                    $updateCounter->BindParam(':counterNew', $counterNew, PDO::PARAM_STR);
                    $updateCounter->BindParam(':id', $id, PDO::PARAM_STR);
                    $updateCounter->execute();
                    $rowCount = $updateCounter->rowCount();
                    if ($rowCount == 0) {
                        $response = new Response();
                        $response->setHttpStatusCode(500);
                        $response->setSuccess(false);
                        $response->addMessage("Failed to send message");
                        $response->send();
                        exit();
                    } else {
                        $response = new Response();
                        $response->setHttpStatusCode(200);
                        $response->setSuccess(true);
                        $response->toCache(true);
                        $response->addMessage('OTP Send success.');
                        $response->send();
                        exit;
                    }
                } elseif ($findData['counter'] == 3) {
                    $currnetTime = strtotime(date('Y-m-d H:i:s'));
                    $expireTime = strtotime($findData['expair_at']);
                    $diffrence = round(abs($currnetTime - $expireTime) / 60);
                    if ($diffrence > 180) {
                        $date = strtotime(date('Y-m-d H:i:s'));
                        $otpNumber = rand(100000, 999999);
                        $expDate = date('Y-m-d H:i:s', strtotime('+60 minutes', $date));
                        $counter = 1;
                        $updateCounter = $writeDB->prepare('UPDATE `temp_otp` SET `expair_at`=:expDate, `otp`=:otpNumber,`counter`=:counter WHERE `id`=:id');
                        $updateCounter->BindParam(':expDate', $expDate, PDO::PARAM_STR);
                        $updateCounter->BindParam(':otpNumber', $otpNumber, PDO::PARAM_STR);
                        $updateCounter->BindParam(':counter', $counter, PDO::PARAM_STR);
                        $updateCounter->BindParam(':id', $id, PDO::PARAM_STR);
                        $updateCounter->execute();
                        $rowCount = $updateCounter->rowCount();
                        if ($rowCount == 0) {
                            $response = new Response();
                            $response->setHttpStatusCode(500);
                            $response->setSuccess(false);
                            $response->addMessage("Failed to send message");
                            $response->send();
                            exit();
                        } else {
                            $response = new Response();
                            $response->setHttpStatusCode(200);
                            $response->setSuccess(true);
                            $response->toCache(true);
                            $response->addMessage('OTP Send success.');
                            $response->send();
                            exit;
                        }
                    } else {
                        $response = new Response();
                        $response->setHttpStatusCode(500);
                        $response->setSuccess(false);
                        $response->addMessage("You cross your limit. Please Wait 3 Hour.");
                        $response->send();
                        exit();
                    }
                }
            }
        } elseif (isset($jsonData->optNumber)) {
            $otp = $jsonData->optNumber;
            $phonenew = $jsonData->phoneNew;
            $phoneold = $jsonData->phoneOld;
            if (!is_numeric($otp) || $otp == '' || !is_numeric($phonenew) || $phonenew == '' || !is_numeric($phoneold) || $phoneold == '') {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("All field are required and can not be null.");
                $response->send();
                exit;
            }
            $flag = 'changephone';
            $checkValidity = $readDB->prepare('SELECT * FROM `temp_otp` WHERE `phone`=:phonenew AND `otp`=:otp AND `flag`=:flag');
            $checkValidity->BindParam(':phonenew', $phonenew, PDO::PARAM_STR);
            $checkValidity->BindParam(':otp', $otp, PDO::PARAM_STR);
            $checkValidity->BindParam(':flag', $flag, PDO::PARAM_STR);
            $checkValidity->execute();
            $countData = $checkValidity->rowCount();

            if ($countData > 0) {

                $currnetTime = date('Y-m-d H:i:s');
                $checkExpair = $readDB->prepare('SELECT * FROM `temp_otp` WHERE `phone`=:phonenew AND `otp`=:otp AND `expair_at`>= :currnetTime AND `flag`=:flag');
                $checkExpair->BindParam(':phonenew', $phonenew, PDO::PARAM_STR);
                $checkExpair->BindParam(':otp', $otp, PDO::PARAM_STR);
                $checkExpair->BindParam(':currnetTime', $currnetTime, PDO::PARAM_STR);
                $checkExpair->BindParam(':flag', $flag, PDO::PARAM_STR);
                $checkExpair->execute();
                $countExpari = $checkExpair->rowCount();
                if ($countExpari > 0) {
                    // delete and insert
                    $deleteOtp = $writeDB->prepare('DELETE FROM `temp_otp` WHERE `phone`=:phonenew AND `flag`=:flag');
                    $deleteOtp->BindParam(':phonenew', $phonenew, PDO::PARAM_STR);
                    $deleteOtp->BindParam(':flag', $flag, PDO::PARAM_STR);
                    $deleteOtp->execute();

                    // updatePhone
                    $phoneUpdateSQL = $writeDB->prepare('UPDATE `users` SET `phone`=:phonenew WHERE `phone`=:phoneold');
                    $phoneUpdateSQL->BindParam(':phonenew', $phonenew, PDO::PARAM_STR);
                    $phoneUpdateSQL->BindParam(':phoneold', $phoneold, PDO::PARAM_STR);
                    $phoneUpdateSQL->execute();

                    $response = new Response();
                    $response->setHttpStatusCode(200);
                    $response->setSuccess(true);
                    $response->toCache(true);
                    $response->addMessage('Phone Change Success.');
                    $response->send();
                    exit;
                } else {
                    $response = new Response();
                    $response->setHttpStatusCode(500);
                    $response->setSuccess(false);
                    $response->addMessage("You cross your limit. Please Wait 3 Hour.");
                    $response->send();
                    exit();
                }
            } else {
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Wrong OTP.");
                $response->send();
                exit();
            }
        }
    } catch (SmsException $ex) {
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage($ex->getMessage());
        $response->send();
        exit;
    } catch (PDOException $ex) {
        error_log("Database query error - " . $ex, 1);
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage($ex->getMessage());
        $response->send();
        exit();
    }
}
