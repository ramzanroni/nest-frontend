<?php


include_once('../../db.php');
include_once('../../../model/admin/user-login/user-loginModel.php');
include_once('../../../model/response.php');

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
if ($_SERVER['REQUEST_METHOD'] === "POST") {
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
        $userinfo = new UserLogin($jsonData->userid, $jsonData->password);
        $userid = $userinfo->getUserid();
        $password = md5($userinfo->getPassword());
        // check user user & password 
        $checkUser = $readDB->prepare('SELECT `userid`,`password` FROM `www_users` WHERE `userid`=:userid AND `password`=:password');
        $checkUser->bindParam(':userid', $userid, PDO::PARAM_STR);
        $checkUser->bindParam(':password', $password, PDO::PARAM_STR);
        $checkUser->execute();
        $rowCount = $checkUser->rowCount();
        if ($rowCount == 1) {
            $findLoggedData = $readDB->prepare('SELECT `id`,`userid`,`realname`,`phone` FROM `users` WHERE `userid`=:userid');
            $findLoggedData->bindParam(':userid', $userid, PDO::PARAM_STR);
            $findLoggedData->execute();
            $rowCount = $findLoggedData->rowCount();
            if ($rowCount === 1) {
                $loggedData = array();
                while ($row = $findLoggedData->fetch(PDO::FETCH_ASSOC)) {
                    $info = new UserInfo($row['id'], $row['userid'], $row['realname'], $row['phone']);
                    $loggedData[] = $info->returnLoggedInfoArray();
                }
                $returnArray = array();
                $returnArray['rows_returned'] = $rowCount;
                $returnArray['userInfo'] = $loggedData;
                $response = new Response();
                $response->setHttpStatusCode(200);
                $response->toCache(true);
                $response->setSuccess(true);
                $response->setData($returnArray);
                $response->send();
                exit;
            }
        } else {
            $response = new Response();
            $response->setHttpStatusCode(404);
            $response->setSuccess(false);
            $response->addMessage('Wrong username or password.');
            $response->send();
            exit;
        }
    } catch (LogicException $ex) {
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
    $response->setHttpStatusCode(404);
    $response->setSuccess(false);
    $response->addMessage("Endpoint not found");
    $response->send();
    exit;
}
