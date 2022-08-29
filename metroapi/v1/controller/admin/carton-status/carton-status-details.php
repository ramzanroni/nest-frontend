<?php

use LDAP\Result;

include_once('../../db.php');
include_once('../../../model/admin/carton-status/carton-statusModel.php');
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
        if (isset($jsonData->startDate)) {
            $startDate = $jsonData->startDate;
            if (!isset($jsonData->endDate)) {
                $endDate = $startDate;
            } else {
                $endDate = $jsonData->endDate;
            }
            if (isset($jsonData->start)) {
                $start = $jsonData->start;
            } else {
                $start = 0;
            }
            if (isset($jsonData->end)) {
                $end = $jsonData->end;
            } else {
                $end = 10;
            }
            if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $startDate) && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $endDate)) {
                $startDate = $startDate . " 00:00:00";
                $enddate = $endDate . " 23:59:59";
                $searchSql = $readDB->prepare('SELECT * FROM `carton_status_details` WHERE create_at >= "' . $startDate . '" AND create_at<="' . $enddate . '" ORDER BY `id` DESC LIMIT ' . $start . ',' . $end . '');
                $searchSql->execute();
                $rowCount = $searchSql->rowCount();
                $cartonDetailsArr = array();
                while ($row = $searchSql->fetch(PDO::FETCH_ASSOC)) {
                    $cartonFetch = new CartonStatus($row['id'], $row['cid'], $row['sid'], $row['uid'], $row['note'], $row['create_at']);
                    $cartonDetailsArr[] = $cartonFetch->returnCartonStatusArray();
                }
                $returnArray = array();
                $returnArray['rows_returned'] = $rowCount;
                $returnArray['cartonDetails'] = $cartonDetailsArr;
                $response = new Response();
                $response->setHttpStatusCode(200);
                $response->toCache(true);
                $response->setSuccess(true);
                $response->setData($returnArray);
                $response->send();
                exit;
            } else {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("StartDate is not valid formate");
                $response->send();
                exit();
            }
        } else {
            $cartonData = new CartonStatus(null, $jsonData->cid, $jsonData->sid, $jsonData->uid, $jsonData->note, null);
            $cid = $cartonData->getCid();
            $sid = $cartonData->getSid();
            $uid = $cartonData->getUid();
            $note = $cartonData->getNote();
            if ($note == '') {
                $statusName = $readDB->prepare('SELECT * FROM `carton_status_list` WHERE `id`=:sid');
                $statusName->bindParam(':sid', $sid, PDO::PARAM_STR);
                $statusName->execute();
                $rowCount = $statusName->rowCount();
                if ($rowCount == 1) {
                    $statusStr = $statusName->fetch(PDO::FETCH_ASSOC);
                    $note = $statusStr['description'] . " by " . $uid;
                }
            }
            // add carton status details 
            $addCartonStatusDetails = $writeDB->prepare('INSERT INTO `carton_status_details`(`cid`, `sid`, `uid`, `note`) VALUES (:cid,:sid,:uid,:note)');
            $addCartonStatusDetails->bindParam(':cid', $cid, PDO::PARAM_STR);
            $addCartonStatusDetails->bindParam(':sid', $sid, PDO::PARAM_STR);
            $addCartonStatusDetails->bindParam(':uid', $uid, PDO::PARAM_STR);
            $addCartonStatusDetails->bindParam(':note', $note, PDO::PARAM_STR);
            $addCartonStatusDetails->execute();
            $rowCount = $addCartonStatusDetails->rowCount();
            if ($rowCount === 1) {
                $response = new Response();
                $response->setHttpStatusCode(200);
                $response->addMessage('Carton status add success.');
                $response->setSuccess(true);
                $response->toCache(true);
                $response->send();
                exit;
            }
        }
    } catch (CartonStatusException $ex) {
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
} elseif ($_SERVER['REQUEST_METHOD'] === "GET") {
    if (!isset($_GET['start'])) {
        $start = 0;
    } else {
        $start = $_GET['start'];
    }
    if (!isset($_GET['end'])) {
        $end = 5;
    } else {
        $end = $_GET['end'];
    }
    if (array_key_exists('id', $_GET)) {
        $id = $_GET['id'];
        $findCartonStatusDetails = $readDB->prepare('SELECT * FROM `carton_status_details` WHERE `id`=:id  ORDER BY `id` DESC LIMIT ' . $start . ',' . $end);
        $findCartonStatusDetails->bindParam(':id', $id, PDO::PARAM_STR);
    } elseif (array_key_exists('cid', $_GET)) {
        $cid = $_GET['cid'];
        $findCartonStatusDetails = $readDB->prepare('SELECT * FROM `carton_status_details` WHERE `cid`=:cid  ORDER BY `id` DESC LIMIT ' . $start . ',' . $end);
        $findCartonStatusDetails->bindParam(':cid', $cid, PDO::PARAM_STR);
    } elseif (array_key_exists('sid', $_GET)) {
        $sid = $_GET['sid'];
        $findCartonStatusDetails = $readDB->prepare('SELECT * FROM `carton_status_details` WHERE `sid`=:sid  ORDER BY `id` DESC LIMIT ' . $start . ',' . $end);
        $findCartonStatusDetails->bindParam(':sid', $sid, PDO::PARAM_STR);
    } elseif (array_key_exists('uid', $_GET)) {
        $uid = $_GET['uid'];
        $findCartonStatusDetails = $readDB->prepare('SELECT * FROM `carton_status_details` WHERE `uid`=:uid  ORDER BY `id` DESC LIMIT ' . $start . ',' . $end);
        $findCartonStatusDetails->bindParam(':uid', $uid, PDO::PARAM_STR);
    } else {
        $findCartonStatusDetails = $readDB->prepare('SELECT * FROM `carton_status_details` ORDER BY `id` DESC LIMIT ' . $start . ',' . $end);
    }
    $findCartonStatusDetails->execute();
    $rowCount = $findCartonStatusDetails->rowCount();
    if ($rowCount === 0) {
        $response = new Response();
        $response->setHttpStatusCode(404);
        $response->setSuccess(false);
        $response->addMessage("Data not found");
        $response->send();
        exit;
    }
    $cartonDetailsArr = array();
    while ($row = $findCartonStatusDetails->fetch(PDO::FETCH_ASSOC)) {
        $cartonFetch = new CartonStatus($row['id'], $row['cid'], $row['sid'], $row['uid'], $row['note'], $row['create_at']);
        $cartonDetailsArr[] = $cartonFetch->returnCartonStatusArray();
    }
    $returnArray = array();
    $returnArray['rows_returned'] = $rowCount;
    $returnArray['cartonDetails'] = $cartonDetailsArr;
    $response = new Response();
    $response->setHttpStatusCode(200);
    $response->toCache(true);
    $response->setSuccess(true);
    $response->setData($returnArray);
    $response->send();
    exit;
} else {
    $response = new Response();
    $response->setHttpStatusCode(404);
    $response->setSuccess(false);
    $response->addMessage("Endpoint not found");
    $response->send();
    exit;
}
