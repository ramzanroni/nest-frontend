<?php

include_once('db.php');
include_once('../model/smsModel.php');
include_once('../model/response.php');
$allHeaders = getallheaders();
$apiSecurity = $allHeaders['Authorization'];
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


function sendOtp($mobile, $otp)
{
    // metrotel sms api
    $api_key = 'C20011586187c1f6d99aa5.18006622';
    $senderid = '8809612441960';

    $url = "https://portal.metrotel.com.bd/smsapi";
    $data = [
        "api_key" => $api_key,
        "type" => "text",
        "contacts" => $mobile,
        "senderid" => $senderid,
        "msg" => "NEO Bazaar OTP: " . $otp,
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
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
        // send otp for verification
        if (!isset($jsonData->otp) && isset($jsonData->phone)) {

            $smsInfo = new SMS($jsonData->phone, null);
            $phoneNumber = $smsInfo->getPhone();
            // check user 
            $chekUserAccount = $readDB->prepare('SELECT * FROM debtorsmaster WHERE phone1=:phone');
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
                        // $otpNumber = rand(100000, 999999);
                        $otpNumber = 1234;

                        $counter = 1;
                        $flag = '';
                        $date = strtotime(date('Y-m-d H:i:s'));
                        $expDate = date('Y-m-d H:i:s', strtotime('+2 minutes', $date));
                        $rowCount = sendSms($phoneNumber, $otpNumber, $expDate, $counter, $flag);
                        // sendOtp($phoneNumber, $otpNumber);
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
                            $otpNumber = 1234;
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
                                $otpNumber = 1234;
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
        } // check otp for verification 
        elseif (isset($jsonData->otp) && isset($jsonData->phone)) {
            $otp = $jsonData->otp;
            $phone = $jsonData->phone;
            $flag = '';
            $smsInfo = new SMS($jsonData->phone, null);
            $phone = $smsInfo->getPhone();

            // exit;
            $checkValidity = $readDB->prepare('SELECT * FROM temp_otp WHERE phone=:phone AND otp=:otp AND flag=:flag');

            $checkValidity->bindParam(':phone', $phone, PDO::PARAM_STR);
            $checkValidity->bindParam(':otp', $otp, PDO::PARAM_STR);
            $checkValidity->bindParam(':flag', $flag, PDO::PARAM_STR);
            $checkValidity->execute();
            // echo $checkValidity->debugDumpParams();
            // exit;
            $countData = $checkValidity->rowCount();
            if ($countData > 0) {

                $currnetTime = date('Y-m-d H:i:s');
                $checkExpair = $readDB->prepare('SELECT * FROM temp_otp WHERE phone=:phone AND otp=:otp AND expair_at >= :currnetTime AND flag=:flag');
                $checkExpair->bindParam(':phone', $phone, PDO::PARAM_STR);
                $checkExpair->bindParam(':otp', $otp, PDO::PARAM_STR);
                $checkExpair->bindParam(':currnetTime', $currnetTime, PDO::PARAM_STR);
                $checkExpair->bindParam(':flag', $flag, PDO::PARAM_STR);
                $checkExpair->execute();
                // echo $checkExpair->debugDumpParams();
                $countExpari = $checkExpair->rowCount();
                if ($countExpari > 0) {
                    // delete and insert
                    $deleteOtp = $writeDB->prepare('DELETE FROM temp_otp WHERE phone=:phone');
                    $deleteOtp->bindParam(':phone', $phone, PDO::PARAM_STR);
                    $deleteOtp->execute();
                    // echo $deleteOtp->debugDumpParams();

                    $rowCount = $deleteOtp->rowCount();
                    if ($rowCount === 1) {
                        // select user
                        $selectUserData = $readDB->prepare('SELECT * FROM debtorsmaster WHERE phone1=:phone');
                        $selectUserData->bindParam(':phone', $phone, PDO::PARAM_STR);
                        $selectUserData->execute();
                        // echo $selectUserData->debugDumpParams();

                        $rowCount = $selectUserData->rowCount();
                        $smsArray = array();
                        while ($row = $selectUserData->fetch(PDO::FETCH_ASSOC)) {
                            $sms = new SMS($row['phone1'], $row['user_token']);
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
        } // new user phone check 
        elseif (isset($jsonData->newPhone)) {
            $phone = $jsonData->newPhone;
            $otpNumber = rand(100000, 999999);
            $otpNumber = 1234;
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
                        $otpNumber = 1234;
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
                            $otpNumber = 1234;
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
        } // new user registration 
        elseif (isset($jsonData->login_id) && !isset($jsonData->newUserPhone)) {
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
                $selectUser = $readDB->prepare('SELECT * FROM debtorsmaster WHERE login_id=:login_id');
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
                $response->addMessage($ex->getMessage());
                $response->send();
                exit();
            }
        } else {
            $phone = trim($jsonData->newUserPhone);
            $address = trim($jsonData->address);
            $name = trim($jsonData->name);
            $otp = trim($jsonData->newOtp);
            if ($jsonData->email == '') {
                $email = '';
            } else {
                $email = trim($jsonData->email);
            }

            $login_media = trim($jsonData->login_media);
            $login_id = trim($jsonData->login_id);
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
                    $chekUserAccount = $readDB->prepare('SELECT * FROM debtorsmaster WHERE phone1=:phone');
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

                        $token = base64_encode($phone . $dateTime);
                        $smsInfo = new SMS($jsonData->phone, $token);
                        $userId = rand(1000, 9999); // 450 auto contact no code
                        // exit;

                        // $userId = 123;
                        $created_by = '0';
                        $updated_by = '0';
                        $address2 = '';
                        $address3 = '';
                        $address4 = '';
                        $bid = '';
                        $picture = '';
                        // insert in contact master 
                        try {
                            $query = "INSERT INTO contact_master(
                                id,
                                code,
                                name,
                                address1,
                                address2,
                                address3,
                                address4,
                                phone1,
                                email,
                                bid,
                                picture,
                                created_by,
                                created_at,
                                updated_by
                            )
                            VALUES(
                                :id,
                                :userId,
                                :name,
                                :address,
                                :address2,
                                :address3,
                                :address4,
                                :phone,
                                :email,
                                :bid,
                                :picture,
                                :created_by,
                                CURRENT_TIMESTAMP(),
                                :updated_by);";

                            $insertContactStmt = $writeDB->prepare($query);
                            $insertContactStmt->bindParam(':id', $userId, PDO::PARAM_STR);
                            $insertContactStmt->bindParam(':userId', $userId, PDO::PARAM_STR);
                            $insertContactStmt->bindParam(':name', $name, PDO::PARAM_STR);
                            $insertContactStmt->bindParam(':address', $address, PDO::PARAM_STR);
                            $insertContactStmt->bindParam(':address2', $address2, PDO::PARAM_STR);
                            $insertContactStmt->bindParam(':address3', $address3, PDO::PARAM_STR);
                            $insertContactStmt->bindParam(':address4', $address4, PDO::PARAM_STR);
                            $insertContactStmt->bindParam(':phone', $phone, PDO::PARAM_STR);
                            $insertContactStmt->bindParam(':email', $email, PDO::PARAM_STR);
                            $insertContactStmt->bindParam(':bid', $bid, PDO::PARAM_STR);
                            $insertContactStmt->bindParam(':picture', $picture, PDO::PARAM_STR);
                            $insertContactStmt->bindParam(':created_by', $created_by, PDO::PARAM_STR);
                            $insertContactStmt->bindParam(':updated_by', $updated_by, PDO::PARAM_STR);
                            $insertContactStmt->execute();
                        } catch (PDOException $ex) {
                            error_log("Database query error - " . $ex, 1);
                            $response = new Response();
                            $response->setHttpStatusCode(500);
                            $response->setSuccess(false);
                            $response->addMessage($ex->getMessage());
                            $response->send();
                            exit();
                        }

                        // echo $insertContactStmt->debugDumpParams();
                        // echo ' insertContactStmt';


                        //insert into user type
                        try {
                            $type = "ecm";
                            $status = 1;
                            $query = "INSERT INTO user_type(
                                                                        user_id,
                                                                        type,
                                                                        status,
                                                                        create_at,
                                                                        create_by
                                                                    )
                                                                    VALUES(
                                                                        :usuerId,
                                                                        :type,
                                                                        :status,
                                                                        now(),
                                                                        :create_by
                                                                        )";

                            $insertUserTypeStmt = $writeDB->prepare($query);
                            $insertUserTypeStmt->bindParam(':usuerId', $userId, PDO::PARAM_STR);
                            $insertUserTypeStmt->bindParam(':type', $type, PDO::PARAM_STR);
                            $insertUserTypeStmt->bindParam(':status', $status, PDO::PARAM_STR);
                            $insertUserTypeStmt->bindParam(':create_by', $created_by, PDO::PARAM_STR);
                            $insertUserTypeStmt->execute();
                        } catch (PDOException $ex) {
                            error_log("Database query error - " . $ex, 1);
                            $response = new Response();
                            $response->setHttpStatusCode(500);
                            $response->setSuccess(false);
                            $response->addMessage($ex->getMessage());
                            $response->send();
                            exit();
                        }


                        // echo $insertUserTypeStmt->debugDumpParams();
                        // echo ' insertUserTypeStmt';

                        //insert debtormaster
                        try {
                            $login_id = '';
                            $currcode = "BDT";
                            $salestype = "DP";
                            $holdreason = "1";
                            $paymentterms = "30";
                            $discount = "0";
                            $pymtdiscount = "0";
                            $lastpaid = "0";
                            $lastpaiddate = null;
                            $creditlimit = "10000";
                            $invaddrbranch = "0";
                            $discountcode = '';
                            $ediinvoices = "0";
                            $ediorders = "0";
                            $edireference = '';
                            $editransport = "email";
                            $customerpoline = "0";
                            $typeid = "1";
                            $custcatid1 = "0";
                            $op_bal = "0";
                            $phone2 = '';
                            $created_at = "2022-06-30 17:09:09";
                            $updated_at = "0000-00-00 00:00:00";
                            $updated_by = "0";
                            $status = "1";
                            $bin_no = '';
                            $nid_no = '';
                            $query = "INSERT INTO debtorsmaster(
                                                                            debtorno,
                                                                            cm_id,
                                                                            name,
                                                                            address1,
                                                                            login_id,
                                                                            currcode,
                                                                            salestype,
                                                                            clientsince,
                                                                            holdreason,
                                                                            paymentterms,
                                                                            discount,
                                                                            pymtdiscount,
                                                                            lastpaid,
                                                                            lastpaiddate,
                                                                            creditlimit,
                                                                            invaddrbranch,
                                                                            discountcode,
                                                                            ediinvoices,
                                                                            ediorders,
                                                                            edireference,
                                                                            editransport,
                                                                            customerpoline,
                                                                            typeid,
                                                                            custcatid1,
                                                                            op_bal,
                                                                            phone1,
                                                                            phone2,
                                                                            email,
                                                                            created_by,
                                                                            created_at,
                                                                            updated_at,
                                                                            updated_by,
                                                                            status,
                                                                            bin_no,
                                                                            nid_no,
                                                                            user_token
                                                                        )
                                                                        VALUES(
                                                                            :debtorno,
                                                                            :cm_id,
                                                                            :name,
                                                                            :address,
                                                                            :login_id,
                                                                            :currcode,
                                                                            :salestype,
                                                                            now(),
                                                                            :holdreason,
                                                                            :paymentterms,
                                                                            :discount,
                                                                            :pymtdiscount,
                                                                            :lastpaid,
                                                                            :lastpaiddate,
                                                                            :creditlimit,
                                                                            :invaddrbranch,
                                                                            :discountcode,
                                                                            :ediinvoices,
                                                                            :ediorders,
                                                                            :edireference,
                                                                            :editransport,
                                                                            :customerpoline,
                                                                            :typeid,
                                                                            :custcatid1,
                                                                            :op_bal,
                                                                            :phone1,
                                                                            :phone2,
                                                                            :email,
                                                                            :created_by,
                                                                            :created_at,
                                                                            :updated_at,
                                                                            :updated_by,
                                                                            :status,
                                                                            :bin_no,
                                                                            :nid_no,
                                                                            :user_token
                                                                        )";

                            $insertDebtorStmt = $writeDB->prepare($query);
                            $insertDebtorStmt->bindParam(':debtorno', $userId, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':cm_id', $userId, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':name', $name, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':address', $address, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':login_id', $login_id, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':currcode', $currcode, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':salestype', $salestype, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':holdreason', $holdreason, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':paymentterms', $paymentterms, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':discount', $discount, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':pymtdiscount', $pymtdiscount, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':lastpaid', $lastpaid, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':lastpaiddate', $lastpaiddate, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':creditlimit', $creditlimit, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':invaddrbranch', $invaddrbranch, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':discountcode', $discountcode, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':ediinvoices', $ediinvoices, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':ediorders', $ediorders, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':edireference', $edireference, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':editransport', $editransport, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':customerpoline', $customerpoline, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':typeid', $typeid, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':custcatid1', $custcatid1, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':op_bal', $op_bal, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':phone1', $phone, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':phone2', $phone2, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':email', $email, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':created_by', $created_by, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':created_at', $created_at, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':updated_at', $updated_at, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':updated_by', $updated_by, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':status', $status, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':bin_no', $bin_no, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':nid_no', $nid_no, PDO::PARAM_STR);
                            $insertDebtorStmt->bindParam(':user_token', $token, PDO::PARAM_STR);
                            $insertDebtorStmt->execute();
                        } catch (PDOException $ex) {
                            error_log("Database query error - " . $ex, 1);
                            $response = new Response();
                            $response->setHttpStatusCode(500);
                            $response->setSuccess(false);
                            $response->addMessage($ex->getMessage());
                            $response->send();
                            exit();
                        }

                        // echo $insertDebtorStmt->debugDumpParams();
                        // echo ' insertDebtorStmt';

                        try {
                            //insert custbranch
                            // $branchcode = "1";
                            $brname = "main";
                            $lat = "0.000000";
                            $lng = "0.000000";
                            $estdeliverydays = "0";
                            $AREA = "2780";
                            $salesman = '1';
                            $fwddate = "0";
                            $defaultlocation = "1010";
                            $taxgroupid = "1";
                            $defaultshipvia = "1";
                            $deliverblind = "1";
                            $disabletrans = "0";
                            $branchdistance = "0.00";
                            $travelrate = "0.00";
                            $businessunit = "1";
                            $emi = "0";
                            $esd = "0000-00-00";
                            $branchstatus = "1";
                            $tag = "1";
                            $op_bal = "0";
                            $aggrigate_cr = "1";
                            $discount_amt = "0";
                            $specialinstructions = '';
                            $query = "INSERT INTO custbranch(
                                                                   
                                                                    debtorno,
                                                                    brname,
                                                                    braddress1,
                                                                    lat,
                                                                    lng,
                                                                    estdeliverydays,
                                                                    AREA,
                                                                    salesman,
                                                                    fwddate,
                                                                    phoneno,
                                                                    defaultlocation,
                                                                    taxgroupid,
                                                                    defaultshipvia,
                                                                    deliverblind,
                                                                    disabletrans,
                                                                    specialinstructions,
                                                                    branchdistance,
                                                                    travelrate,
                                                                    businessunit,
                                                                    emi,
                                                                    esd,
                                                                    branchsince,
                                                                    branchstatus,
                                                                    tag,
                                                                    op_bal,
                                                                    aggrigate_cr,
                                                                    discount_amt
                                                                    
                                                                )
                                                                VALUES(
                                                                    
                                                                    :debtorno,
                                                                    :brname,
                                                                    :braddress1,
                                                                    :lat,
                                                                    :lng,
                                                                    :estdeliverydays,
                                                                    :AREA,
                                                                    :salesman,
                                                                    :fwddate,
                                                                    :phoneno,
                                                                    :defaultlocation,
                                                                    :taxgroupid,
                                                                    :defaultshipvia,
                                                                    :deliverblind,
                                                                    :disabletrans,
                                                                    :specialinstructions,
                                                                    :branchdistance,
                                                                    :travelrate,
                                                                    :businessunit,
                                                                    :emi,
                                                                    :esd,
                                                                    now(),
                                                                    :branchstatus,
                                                                    :tag,
                                                                    :op_bal,
                                                                    :aggrigate_cr,
                                                                    :discount_amt
                                                                )";

                            $insertBranchStmt = $writeDB->prepare($query);
                            $insertBranchStmt->bindParam(':debtorno', $userId, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':brname', $brname, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':braddress1', $address, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':lat', $lat, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':lng', $lng, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':estdeliverydays', $estdeliverydays, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':AREA', $AREA, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':salesman', $salesman, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':fwddate', $fwddate, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':phoneno', $phone, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':defaultlocation', $defaultlocation, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':taxgroupid', $taxgroupid, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':defaultshipvia', $defaultshipvia, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':deliverblind', $deliverblind, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':disabletrans', $disabletrans, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':specialinstructions', $specialinstructions, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':branchdistance', $branchdistance, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':travelrate', $travelrate, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':businessunit', $businessunit, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':emi', $emi, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':esd', $esd, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':branchstatus', $branchstatus, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':tag', $tag, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':op_bal', $op_bal, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':aggrigate_cr', $aggrigate_cr, PDO::PARAM_STR);
                            $insertBranchStmt->bindParam(':discount_amt', $discount_amt, PDO::PARAM_STR);
                            $insertBranchStmt->execute();


                            $rowCount = $insertBranchStmt->rowCount();
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
                        } catch (PDOException $ex) {
                            error_log("Database query error - " . $ex, 1);
                            $response = new Response();
                            $response->setHttpStatusCode(500);
                            $response->setSuccess(false);
                            $response->addMessage($ex->getMessage());
                            $response->send();
                            exit();
                        }


                        // echo $insertBranchStmt->debugDumpParams();
                        // echo ' insertBranchStmt';

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
        $response->addMessage($ex->getMessage());
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
