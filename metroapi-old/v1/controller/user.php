<?php


include_once('db.php');
include_once('../model/userModel.php');
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
    exit();
}
if (array_key_exists('phoneNumber', $_GET)) {
    $phoneNumber = $_GET['phoneNumber'];
    if (!is_numeric($phoneNumber) || $phoneNumber == '') {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        $response->addMessage("Phone number must be numeric and not null.");
        $response->send();
        exit();
    }
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        try {
            $query = $readDB->prepare('SELECT id, userid, password, realname, customerid, phone, email, address1, active_now, user_token FROM users WHERE  phone=:phoneNumber');
            $query->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
            $query->execute();
            $rowCount = $query->rowCount();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("User Data not found");
                $response->send();
                exit();
            }
            $userArray = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $userData = new User($row['id'], $row['userid'], $row['realname'], $row['email'], $row['phone'], $row['address1'], $row['user_token'], $row['active_now'], $row['customerid']);
                $userArray[] = $userData->returnUsersArray();
            }
            $returnArray = array();
            $returnArray['rows_returned'] = $rowCount;
            $returnArray['user'] = $userArray;
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->setData($returnArray);
            $response->send();
            exit;
        } catch (UserException $ex) {
            $response = new Response();
            $response->setHttpStatusCode(500);
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
        $response->setHttpStatusCode(405);
        $response->setSuccess(false);
        $response->addMessage("Request method does not allow");
        $response->send();
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    try {
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
        if (!isset($jsonData->phoneNumber)) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("Phone Number field must be mandatory field.");
            $response->send();
            exit();
        }
        $userInfo = new User(null, null, (isset($jsonData->fullName) ? $jsonData->fullName : null), (isset($jsonData->emailAddress) ? $jsonData->emailAddress : null), $jsonData->phoneNumber, (isset($jsonData->userAddress) ? $jsonData->userAddress : null), null, null, null);
        $userPhone = $userInfo->getPhone();
        $userFullname = $userInfo->getFullName();
        $email = $userInfo->getEmail();
        $address = $userInfo->getAddress();
        $query = $writeDB->prepare('UPDATE users SET realname=:fullname,email=:email,address1=:address WHERE phone=:phone');
        $query->bindParam(':fullname', $userFullname, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':address', $address, PDO::PARAM_STR);
        $query->bindParam(':phone', $userPhone, PDO::PARAM_STR);
        $query->execute();

        $rowCount = $query->rowCount();
        if ($rowCount === 0) {
            $response = new Response();
            $response->setHttpStatusCode(404);
            $response->setSuccess(false);
            $response->addMessage("User Data not found");
            $response->send();
            exit();
        }
        $response = new Response();
        $response->setHttpStatusCode(200);
        $response->setSuccess(true);
        $response->toCache(true);
        $response->addMessage("Update profile data successfully. ");
        $response->send();
        exit();
    } catch (UserException $ex) {
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
}
