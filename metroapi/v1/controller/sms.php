<?php

include_once('db.php');
include_once('../model/smsModel.php');
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
function sendSms($phoneNumber, $otpNumber, $expDate, $counter, $flag)
{
    include_once('db.php');
    include_once('../model/smsModel.php');
    include_once('../model/response.php');
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

    $addPhone = $writeDB->prepare('INSERT INTO temp_otp(phone, otp, expair_at, counter,flag) VALUES (:phoneNumber,:otpNumber,:expDate,:counter,:flag)');
    $addPhone->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
    $addPhone->bindPARAM(':otpNumber', $otpNumber, PDO::PARAM_STR);
    $addPhone->bindPARAM(':expDate', $expDate, PDO::PARAM_STR);
    $addPhone->bindPARAM(':counter', $counter, PDO::PARAM_INT);
    $addPhone->bindPARAM(':flag', $flag, PDO::PARAM_STR);
    $addPhone->execute();
    return $rowCount = $addPhone->rowCount();
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
        if (!isset($jsonData->otp) && isset($jsonData->phone)) {

            $smsInfo = new SMS($jsonData->phone, null);
            $phoneNumber = $smsInfo->getPhone();
            // check user 
            $chekUserAccount = $readDB->prepare('SELECT id FROM users WHERE phone=:phone');
            $chekUserAccount->bindParam(':phone', $phoneNumber, PDO::PARAM_STR);
            $chekUserAccount->execute();
            $rowCount = $chekUserAccount->rowCount();
            if ($rowCount === 1) {
                $findRecord = $readDB->prepare('SELECT * FROM temp_otp WHERE phone=:phoneNumber AND flag=""');
                $findRecord->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
                $findRecord->execute();
                $number_of_rows = $findRecord->rowCount();
                $findData = $findRecord->fetch();
                if ($number_of_rows == 0) {
                    try {
                        $otpNumber = rand(100000, 999999);
                        $counter = 1;
                        $flag = '';
                        $date = strtotime(date('Y-m-d H:i:s'));
                        $expDate = date('Y-m-d H:i:s', strtotime('+2 minutes', $date));
                        $rowCount = sendSms($phoneNumber, $otpNumber, $expDate, $counter, $flag);
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
                    } catch (SmsException $ex) {
                        $response = new Response();
                        $response->setHttpStatusCode(400);
                        $response->setSuccess(false);
                        $response->addMessage($ex->getMessage());
                        $response->send();
                        exit();
                    } catch (PDOException $ex) {
                        error_log("Database query error." . $ex, 0);
                        $response = new Response();
                        $response->setHttpStatusCode(500);
                        $response->setSuccess(false);
                        $response->addMessage($ex->getMessage());
                        $response->send();
                        exit();
                    }
                } else {
                    if ($findData['counter'] < 3) {
                        try {
                            $counterNew = $findData['counter'] + 1;
                            $date = strtotime(date('Y-m-d H:i:s'));
                            $otpNumber = rand(100000, 999999);
                            $expDate = date('Y-m-d H:i:s', strtotime('+2 minutes', $date));
                            $findDataID = $findData['id'];
                            $updateCounter = $writeDB->prepare('UPDATE temp_otp SET expair_at=:expDate, otp=:otpNumber,counter=:counterNew WHERE id=:findData');
                            $updateCounter->bindPARAM(':expDate', $expDate, PDO::PARAM_STR);
                            $updateCounter->bindPARAM(':otpNumber', $otpNumber, PDO::PARAM_STR);
                            $updateCounter->bindPARAM(':counterNew', $counterNew, PDO::PARAM_INT);
                            $updateCounter->bindPARAM(':findData', $findDataID, PDO::PARAM_INT);
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
                        } catch (SmsException $ex) {
                            $response = new Response();
                            $response->setHttpStatusCode(400);
                            $response->setSuccess(false);
                            $response->addMessage($ex->getMessage());
                            $response->send();
                            exit();
                        } catch (PDOException $ex) {
                            error_log("Database query error." . $ex, 0);
                            $response = new Response();
                            $response->setHttpStatusCode(500);
                            $response->setSuccess(false);
                            $response->addMessage($ex->getMessage());
                            $response->send();
                            exit();
                        }
                    } elseif ($findData['counter'] == 3) {
                        $currnetTime = strtotime(date('Y-m-d H:i:s'));
                        $expireTime = strtotime($findData['expair_at']);
                        $diffrence = round(abs($currnetTime - $expireTime) / 60);
                        if ($diffrence > 180) {
                            try {
                                $date = strtotime(date('Y-m-d H:i:s'));
                                $otpNumber = rand(100000, 999999);
                                $expDate = date('Y-m-d H:i:s', strtotime('+60 minutes', $date));
                                $upCounter = 1;
                                $findID = $findData['id'];
                                $updateCounter = $writeDB->prepare('UPDATE temp_otp SET expair_at= :expDate, otp=:otpNumber,counter=:counter WHERE id=:findID');
                                $updateCounter->bindPARAM(':expDate', $expDate, PDO::PARAM_STR);
                                $updateCounter->bindPARAM(':otpNumber', $otpNumber, PDO::PARAM_STR);
                                $updateCounter->bindPARAM(':counter', $upCounter, PDO::PARAM_INT);
                                $updateCounter->bindPARAM(':findID', $findID, PDO::PARAM_INT);
                                $updateCounter->execute;
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
                            } catch (SmsException $ex) {
                                $response = new Response();
                                $response->setHttpStatusCode(400);
                                $response->setSuccess(false);
                                $response->addMessage($ex->getMessage());
                                $response->send();
                                exit();
                            } catch (PDOException $ex) {
                                error_log("Database query error." . $ex, 0);
                                $response = new Response();
                                $response->setHttpStatusCode(500);
                                $response->setSuccess(false);
                                $response->addMessage($ex->getMessage());
                                $response->send();
                                exit();
                            }
                        } else {
                            $response = new Response();
                            $response->setHttpStatusCode(400);
                            $response->setSuccess(false);
                            $response->addMessage("You cross your limit. Please try later");
                            $response->send();
                            exit;
                        }
                    }
                }
            } else {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Unauthorized user.");
                $response->send();
                exit;
            }
        } elseif (isset($jsonData->otp) && isset($jsonData->phone)) {
            $otp = $jsonData->otp;
            $flag = '';
            $smsInfo = new SMS($jsonData->phone, null);
            $phone = $smsInfo->getPhone();
            $checkValidity = $readDB->prepare('SELECT * FROM temp_otp WHERE phone=:phone AND otp=:otp AND flag=:flag');
            $checkValidity->bindParam(':phone', $phone, PDO::PARAM_STR);
            $checkValidity->bindParam(':otp', $otp, PDO::PARAM_STR);
            $checkValidity->bindParam(':flag', $flag, PDO::PARAM_STR);
            $checkValidity->execute();
            $countData = $checkValidity->rowCount();
            if ($countData > 0) {

                $currnetTime = date('Y-m-d H:i:s');
                $checkExpair = $readDB->prepare('SELECT * FROM temp_otp WHERE phone=:phone AND otp=:otp AND expair_at >= :currnetTime AND flag=:flag');
                $checkExpair->bindParam(':phone', $phone, PDO::PARAM_STR);
                $checkExpair->bindParam(':otp', $otp, PDO::PARAM_STR);
                $checkExpair->bindParam(':currnetTime', $currnetTime, PDO::PARAM_STR);
                $checkExpair->bindParam(':flag', $flag, PDO::PARAM_STR);
                $checkExpair->execute();
                $countExpari = $checkExpair->rowCount();
                if ($countExpari > 0) {
                    // delete and insert
                    $deleteOtp = $writeDB->prepare('DELETE FROM temp_otp WHERE phone=:phone');
                    $deleteOtp->bindParam(':phone', $phone, PDO::PARAM_STR);
                    $deleteOtp->execute();
                    $rowCount = $deleteOtp->rowCount();
                    if ($rowCount === 1) {
                        // select user
                        $selectUserData = $readDB->prepare('SELECT * FROM users WHERE phone=:phone');
                        $selectUserData->bindParam(':phone', $phone, PDO::PARAM_STR);
                        $selectUserData->execute();
                        $rowCount = $selectUserData->rowCount();
                        $smsArray = array();
                        while ($row = $selectUserData->fetch(PDO::FETCH_ASSOC)) {
                            $sms = new SMS($row['phone'], $row['user_token']);
                            $smsArray[] = $sms->returnSmsArray();
                        }
                        $returnData = array();
                        $returnData['rows_returned'] = $rowCount;
                        $returnData['userdata'] = $smsArray;
                        $response = new Response();
                        $response->setHttpStatusCode(200);
                        $response->setSuccess(true);
                        $response->toCache(true);
                        $response->setData($returnData);
                        $response->send();
                        exit;
                    }

                    // $checkuser = $readDB->prepare('SELECT * FROM users WHERE phone=:phone');
                    // $checkuser->bindParam(':phone', $phone, PDO::PARAM_STR);
                    // $checkuser->execute();
                    // $userCheckCount = $checkuser->rowCount();

                    // if ($userCheckCount == 0) {
                    //     // insert user 
                    //     $dateTime = date('Y-m-d H:i:s');
                    //     $userid = '';
                    //     $password = '';
                    //     $address1 = '';
                    //     $token = base64_encode($phone . $dateTime);
                    //     $smsInfo = new SMS($jsonData->phone, $token);
                    //     $insertUser = $writeDB->prepare('INSERT INTO users(userid, password,phone, address1,user_token) VALUES (:userid,:password,:phone,:address1,:token)');
                    //     $insertUser->bindParam('userid', $userid, PDO::PARAM_STR);
                    //     $insertUser->bindParam('password', $password, PDO::PARAM_STR);
                    //     $insertUser->bindParam('phone', $phone, PDO::PARAM_STR);
                    //     $insertUser->bindParam('address1', $address1, PDO::PARAM_STR);
                    //     $insertUser->bindParam('token', $token, PDO::PARAM_STR);
                    //     $insertUser->execute();
                    //     $rowCount = $insertUser->rowCount();
                    //     $sms = new SMS($phone, $token);
                    //     $data = $sms->returnSmsArray();
                    //     $returnArray = array();
                    //     $returnArray['rows_returned'] = $rowCount;
                    //     $returnArray['userdata'] = $data;
                    //     $response = new Response();
                    //     $response->setHttpStatusCode(200);
                    //     $response->setSuccess(true);
                    //     $response->toCache(true);
                    //     $response->setData($returnArray);
                    //     $response->send();
                    //     exit;
                    //     // echo json_encode(
                    //     //     array(
                    //     //         'message' => "success",
                    //     //         'userPhone' => $phone,
                    //     //         'userToken' => $token
                    //     //     )
                    //     // );
                    // } else {
                    //     // select user
                    //     $selectUserData = $readDB->prepare('SELECT * FROM users WHERE phone=:phone');
                    //     $selectUserData->bindParam(':phone', $phone, PDO::PARAM_STR);
                    //     $selectUserData->execute();
                    //     $rowCount = $selectUserData->rowCount();
                    //     $smsArray = array();
                    //     while ($row = $selectUserData->fetch(PDO::FETCH_ASSOC)) {
                    //         $sms = new SMS($row['phone'], $row['user_token']);
                    //         $smsArray[] = $sms->returnSmsArray();
                    //     }
                    //     $returnData = array();
                    //     $returnData['rows_returned'] = $rowCount;
                    //     $returnData['userdata'] = $smsArray;
                    //     $response = new Response();
                    //     $response->setHttpStatusCode(200);
                    //     $response->setSuccess(true);
                    //     $response->toCache(true);
                    //     $response->setData($returnData);
                    //     $response->send();
                    //     exit;
                    // }
                } else {
                    $response = new Response();
                    $response->setHttpStatusCode(400);
                    $response->setSuccess(false);
                    $response->addMessage("your OTP time expaired. Please try later.");
                    $response->send();
                    exit;
                }
            } else {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Wrong OTP");
                $response->send();
                exit;
            }
        } elseif (isset($jsonData->newPhone)) {
            $phone = $jsonData->newPhone;
            $otpNumber = rand(100000, 999999);
            $counter = 1;
            $flag = '';
            $date = strtotime(date('Y-m-d H:i:s'));
            $expDate = date('Y-m-d H:i:s', strtotime('+2 minutes', $date));
            $findRecord = $readDB->prepare('SELECT * FROM temp_otp WHERE phone=:phoneNumber AND flag=""');
            $findRecord->bindParam(':phoneNumber', $phone, PDO::PARAM_STR);
            $findRecord->execute();
            $number_of_rows = $findRecord->rowCount();
            $findData = $findRecord->fetch();

            if ($number_of_rows === 0) {
                $rowCount = sendSms($phone, $otpNumber, $expDate, $counter, $flag);
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
                if ($findData['counter'] < 3) {
                    try {
                        $counterNew = $findData['counter'] + 1;
                        $date = strtotime(date('Y-m-d H:i:s'));
                        $otpNumber = rand(100000, 999999);
                        $expDate = date('Y-m-d H:i:s', strtotime('+2 minutes', $date));
                        $findDataID = $findData['id'];
                        $updateCounter = $writeDB->prepare('UPDATE temp_otp SET expair_at=:expDate, otp=:otpNumber,counter=:counterNew WHERE id=:findData');
                        $updateCounter->bindPARAM(':expDate', $expDate, PDO::PARAM_STR);
                        $updateCounter->bindPARAM(':otpNumber', $otpNumber, PDO::PARAM_STR);
                        $updateCounter->bindPARAM(':counterNew', $counterNew, PDO::PARAM_INT);
                        $updateCounter->bindPARAM(':findData', $findDataID, PDO::PARAM_INT);
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
                    } catch (SmsException $ex) {
                        $response = new Response();
                        $response->setHttpStatusCode(400);
                        $response->setSuccess(false);
                        $response->addMessage($ex->getMessage());
                        $response->send();
                        exit();
                    } catch (PDOException $ex) {
                        error_log("Database query error." . $ex, 0);
                        $response = new Response();
                        $response->setHttpStatusCode(500);
                        $response->setSuccess(false);
                        $response->addMessage($ex->getMessage());
                        $response->send();
                        exit();
                    }
                } elseif ($findData['counter'] == 3) {
                    $currnetTime = strtotime(date('Y-m-d H:i:s'));
                    $expireTime = strtotime($findData['expair_at']);
                    $diffrence = round(abs($currnetTime - $expireTime) / 60);
                    if ($diffrence > 180) {
                        try {
                            $date = strtotime(date('Y-m-d H:i:s'));
                            $otpNumber = rand(100000, 999999);
                            $expDate = date('Y-m-d H:i:s', strtotime('+60 minutes', $date));
                            $upCounter = 1;
                            $findID = $findData['id'];
                            $updateCounter = $writeDB->prepare('UPDATE temp_otp SET expair_at= :expDate, otp=:otpNumber,counter=:counter WHERE id=:findID');
                            $updateCounter->bindPARAM(':expDate', $expDate, PDO::PARAM_STR);
                            $updateCounter->bindPARAM(':otpNumber', $otpNumber, PDO::PARAM_STR);
                            $updateCounter->bindPARAM(':counter', $upCounter, PDO::PARAM_INT);
                            $updateCounter->bindPARAM(':findID', $findID, PDO::PARAM_INT);
                            $updateCounter->execute;
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
                        } catch (SmsException $ex) {
                            $response = new Response();
                            $response->setHttpStatusCode(400);
                            $response->setSuccess(false);
                            $response->addMessage($ex->getMessage());
                            $response->send();
                            exit();
                        } catch (PDOException $ex) {
                            error_log("Database query error." . $ex, 0);
                            $response = new Response();
                            $response->setHttpStatusCode(500);
                            $response->setSuccess(false);
                            $response->addMessage($ex->getMessage());
                            $response->send();
                            exit();
                        }
                    } else {
                        $response = new Response();
                        $response->setHttpStatusCode(400);
                        $response->setSuccess(false);
                        $response->addMessage("You cross your limit. Please try later.");
                        $response->send();
                        exit;
                    }
                }
            }
        } elseif (isset($jsonData->login_id) && !isset($jsonData->newUserPhone)) {
            if ($jsonData->login_id == '') {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Login ID can be blank");
                $response->send();
                exit;
            }
            $login_id = $jsonData->login_id;
            try {
                $selectUser = $readDB->prepare('SELECT * FROM users WHERE login_id=:login_id');
                $selectUser->BindParam(':login_id', $login_id, PDO::PARAM_STR);
                $selectUser->execute();
                $rowCount = $selectUser->rowCount();
                if ($rowCount === 1) {
                    $smsArray = array();
                    while ($row = $selectUser->fetch(PDO::FETCH_ASSOC)) {
                        $sms = new SMS($row['phone'], $row['user_token']);
                        $smsArray[] = $sms->returnSmsArray();
                    }
                    $returnData = array();
                    $returnData['rows_returned'] = $rowCount;
                    $returnData['userdata'] = $smsArray;
                    $response = new Response();
                    $response->setHttpStatusCode(200);
                    $response->setSuccess(true);
                    $response->toCache(true);
                    $response->setData($returnData);
                    $response->send();
                    exit;
                } else {
                    $response = new Response();
                    $response->setHttpStatusCode(400);
                    $response->setSuccess(false);
                    $response->addMessage("New user");
                    $response->send();
                    exit;
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
                $response->addMessage("Failed to get task");
                $response->send();
                exit();
            }
        } else {
            $phone = $jsonData->newUserPhone;
            $address = $jsonData->address;
            $name = $jsonData->name;
            $otp = $jsonData->newOtp;
            $email = $jsonData->email;
            $login_media = $jsonData->login_media;
            $login_id = $jsonData->login_id;
            $flag = '';
            $userInfo = new Register($phone, $name, $address, $otp);
            $validPhone = $userInfo->getNewUserPhone();
            $validName = $userInfo->getName();
            $validAddress = $userInfo->getAddress();
            $validOtp = $userInfo->getNewOtp();
            $checkOTP = $readDB->prepare('SELECT id, phone, otp, expair_at, counter, flag FROM temp_otp WHERE phone=:phone AND otp=:otp AND flag=:flag');
            $checkOTP->bindParam(':phone', $validPhone, PDO::PARAM_STR);
            $checkOTP->bindParam(':otp', $validOtp, PDO::PARAM_STR);
            $checkOTP->bindParam(':flag', $flag, PDO::PARAM_STR);
            $checkOTP->execute();
            $rowCount = $checkOTP->rowCount();
            if ($rowCount === 1) {
                $currnetTime = date('Y-m-d H:i:s');
                $checkExpair = $readDB->prepare('SELECT * FROM temp_otp WHERE phone=:phone AND otp=:otp AND expair_at >= :currnetTime AND flag=:flag');
                $checkExpair->bindParam(':phone', $validPhone, PDO::PARAM_STR);
                $checkExpair->bindParam(':otp', $validOtp, PDO::PARAM_STR);
                $checkExpair->bindParam(':currnetTime', $currnetTime, PDO::PARAM_STR);
                $checkExpair->bindParam(':flag', $flag, PDO::PARAM_STR);
                $checkExpair->execute();
                $rowCount = $checkExpair->rowCount();

                if ($rowCount === 1) {
                    // check user 
                    $chekUserAccount = $readDB->prepare('SELECT id FROM users WHERE phone=:phone');
                    $chekUserAccount->bindParam(':phone', $validPhone, PDO::PARAM_STR);
                    $chekUserAccount->execute();
                    $rowCount = $chekUserAccount->rowCount();
                    if ($rowCount === 1) {
                        $deleteOtp = $writeDB->prepare('DELETE FROM temp_otp WHERE phone=:phone');
                        $deleteOtp->bindParam(':phone', $validPhone, PDO::PARAM_STR);
                        $deleteOtp->execute();
                        $response = new Response();
                        $response->setHttpStatusCode(400);
                        $response->setSuccess(false);
                        $response->addMessage("This user already exist. Please try another phone number");
                        $response->send();
                        exit;
                    }
                    // delete 
                    $deleteOtp = $writeDB->prepare('DELETE FROM temp_otp WHERE phone=:phone');
                    $deleteOtp->bindParam(':phone', $validPhone, PDO::PARAM_STR);
                    $deleteOtp->execute();
                    $rowCount = $deleteOtp->rowCount();
                    if ($rowCount === 1) {
                        // insert 
                        $dateTime = date('Y-m-d H:i:s');
                        $userid = '';
                        $password = '';
                        $customerid = '';

                        // $address1 = '';
                        $token = base64_encode($phone . $dateTime);
                        $smsInfo = new SMS($jsonData->phone, $token);
                        $insertUser = $writeDB->prepare('INSERT INTO users(userid, password,realname, customerid,phone, email, address1, login_media, login_id, user_token) VALUES (:userid,:password, :realname,:customerid, :phone, :email, :address1, :login_media, :login_id, :token)');
                        $insertUser->bindParam('userid', $userid, PDO::PARAM_STR);
                        $insertUser->bindParam('password', $password, PDO::PARAM_STR);
                        $insertUser->bindParam('realname', $validName, PDO::PARAM_STR);
                        $insertUser->bindParam('customerid', $customerid, PDO::PARAM_STR);
                        $insertUser->bindParam('phone', $validPhone, PDO::PARAM_STR);
                        $insertUser->bindParam('email', $email, PDO::PARAM_STR);
                        $insertUser->bindParam('address1', $validAddress, PDO::PARAM_STR);
                        $insertUser->bindParam('login_media', $login_media, PDO::PARAM_STR);
                        $insertUser->bindParam('login_id', $login_id, PDO::PARAM_STR);
                        $insertUser->bindParam('token', $token, PDO::PARAM_STR);
                        $insertUser->execute();
                        $rowCount = $insertUser->rowCount();
                        $sms = new SMS($phone, $token);
                        $data = $sms->returnSmsArray();
                        $returnArray = array();
                        $returnArray['rows_returned'] = $rowCount;
                        $returnArray['userdata'] = $data;
                        $response = new Response();
                        $response->setHttpStatusCode(200);
                        $response->setSuccess(true);
                        $response->toCache(true);
                        $response->setData($returnArray);
                        $response->send();
                        exit;
                    }
                } else {
                    $response = new Response();
                    $response->setHttpStatusCode(1);
                    $response->setSuccess(false);
                    $response->addMessage("your OTP time expaired. Please try later.");
                    $response->send();
                    exit;
                }
            } else {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Wrong OTP");
                $response->send();
                exit;
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
        $response->addMessage("Failed to get task");
        $response->send();
        exit();
    }
} else {
    $response = new Response();
    $response->setHttpStatusCode(405);
    $response->setSuccess(false);
    $response->addMessage("Request method not allowed.");
    $response->send();
    exit();
}
