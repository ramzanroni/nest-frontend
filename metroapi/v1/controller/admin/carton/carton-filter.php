<?php

include_once('../../db.php');
include_once('../../../model/admin/catron/carton-felterModel.php');
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
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['start'])) {
        $start = 0;
    } else {
        $start = $_GET['start'];
    }
    if (!isset($_GET['end'])) {
        $end = 2;
    } else {
        $end = $_GET['end'];
    }
    if (array_key_exists('order_id', $_GET)) {
        $order_id = $_GET['order_id'];
        $selectSQL = $readDB->prepare('SELECT carton_list.id AS cartonId, carton_list.so AS orderNo, debtorsmaster.name AS name, MAX(carton_status_details.sid) as status FROM carton_list INNER JOIN carton_status_details ON carton_status_details.cid = carton_list.id INNER JOIN salesorders ON salesorders.orderno = carton_list.so INNER JOIN debtorsmaster ON debtorsmaster.debtorno = salesorders.debtorno WHERE carton_list.so=:order_id GROUP BY carton_status_details.cid LIMIT ' . $start . ',' . $end);
        $selectSQL->bindParam(':order_id', $order_id, PDO::PARAM_STR);
        $selectSQL->execute();
    } elseif (array_key_exists('carton_id', $_GET)) {
        $carton_id = $_GET['carton_id'];
        $selectSQL = $readDB->prepare('SELECT carton_list.id AS cartonId, carton_list.so AS orderNo, debtorsmaster.name AS name, MAX(carton_status_details.sid) as status FROM carton_list INNER JOIN carton_status_details ON carton_status_details.cid = carton_list.id INNER JOIN salesorders ON salesorders.orderno = carton_list.so INNER JOIN debtorsmaster ON debtorsmaster.debtorno = salesorders.debtorno WHERE carton_list.id=:carton_id GROUP BY carton_status_details.cid LIMIT ' . $start . ',' . $end);
        $selectSQL->bindParam(':carton_id', $carton_id, PDO::PARAM_STR);
        $selectSQL->execute();
    } elseif (array_key_exists('user_id', $_GET) && array_key_exists('status_id', $_GET)) {
        $user_id = $_GET['user_id'];
        $status_id = $_GET['status_id'];
        $selectSQL = $readDB->prepare('SELECT carton_list.id AS cartonId, carton_list.so AS orderNo, debtorsmaster.name AS name, MAX(carton_status_details.sid) as status FROM carton_list INNER JOIN carton_status_details ON carton_status_details.cid = carton_list.id INNER JOIN salesorders ON salesorders.orderno = carton_list.so INNER JOIN debtorsmaster ON debtorsmaster.debtorno = salesorders.debtorno WHERE salesorders.debtorno=:user_id AND carton_status_details.sid=:status_id GROUP BY carton_status_details.cid LIMIT ' . $start . ',' . $end);
        $selectSQL->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $selectSQL->bindParam(':status_id', $status_id, PDO::PARAM_STR);
        $selectSQL->execute();
    } elseif (array_key_exists('name', $_GET)) {
        $name = $_GET['name'];
        if ($name === '') {
            $response = new Response();
            $response->setHttpStatusCode(403);
            $response->setSuccess(false);
            $response->addMessage('Name missing its not be null. ');
            $response->send();
            exit();
        }
        $subQry = "SELECT carton_list.id AS cartonId, carton_list.so AS orderNo, debtorsmaster.name AS name, MAX(carton_status_details.sid) as status FROM carton_list INNER JOIN carton_status_details ON carton_status_details.cid = carton_list.id INNER JOIN salesorders ON salesorders.orderno = carton_list.so INNER JOIN debtorsmaster ON debtorsmaster.debtorno = salesorders.debtorno WHERE ";
        $textsearchQury = '';

        $searchKeywordList = explode(' ', $name);

        foreach ($searchKeywordList as $searchKey) {
            $textsearchQury .= "debtorsmaster.name LIKE '%" . $searchKey . "%' OR ";
        }
        $textsearchQury = $subQry . rtrim($textsearchQury, 'OR ') . 'GROUP BY carton_status_details.cid LIMIT ' . $start . ',' . $end;
        $selectSQL = $readDB->prepare($textsearchQury);
        $selectSQL->execute();
    } else {
        $selectSQL = $readDB->prepare('SELECT carton_list.id AS cartonId, carton_list.so AS orderNo, debtorsmaster.name AS name, MAX(carton_status_details.sid) as status FROM carton_list INNER JOIN carton_status_details ON carton_status_details.cid = carton_list.id INNER JOIN salesorders ON salesorders.orderno = carton_list.so INNER JOIN debtorsmaster ON debtorsmaster.debtorno = salesorders.debtorno GROUP BY carton_status_details.cid LIMIT ' . $start . ',' . $end);
        $selectSQL->execute();
    }
    $rowCount = $selectSQL->rowCount();
    if ($rowCount == 0) {
        $response = new Response();
        $response->setHttpStatusCode(404);
        $response->setSuccess(false);
        $response->addMessage('Data not found');
        $response->send();
        exit;
    }
    $cartonArray = array();
    while ($row = $selectSQL->fetch(PDO::FETCH_ASSOC)) {
        $cartonInfo = new CartonFilter($row['cartonId'], $row['name'], $row['orderNo'], $row['status']);
        $cartonArray[] = $cartonInfo->returnCartonFilterArray();
    }
    $returnArray = array();
    $returnArray['row_returned'] = $rowCount;
    $returnArray['carton'] = $cartonArray;
    $response = new Response();
    $response->setHttpStatusCode(200);
    $response->setSuccess(true);
    $response->toCache(true);
    $response->setData($returnArray);
    $response->send();
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
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
                $endDate = $endDate . " 23:59:59";
                $selectSQL = $readDB->prepare('SELECT carton_list.id AS cartonId, carton_list.so AS orderNo, debtorsmaster.name AS name, MAX(carton_status_details.sid) as status FROM carton_list INNER JOIN carton_status_details ON carton_status_details.cid = carton_list.id INNER JOIN salesorders ON salesorders.orderno = carton_list.so INNER JOIN debtorsmaster ON debtorsmaster.debtorno = salesorders.debtorno WHERE carton_list.created_at >=:startDate AND carton_list.created_at<=:endDate GROUP BY carton_status_details.cid LIMIT ' . $start . ',' . $end);
                $selectSQL->bindParam(':startDate', $startDate, PDO::PARAM_STR);
                $selectSQL->bindParam(':endDate', $endDate, PDO::PARAM_STR);
                $selectSQL->execute();
                $rowCount = $selectSQL->rowCount();
                $cartonArray = array();
                while ($row = $selectSQL->fetch(PDO::FETCH_ASSOC)) {
                    $cartonInfo = new CartonFilter($row['cartonId'], $row['name'], $row['orderNo'], $row['status']);
                    $cartonArray[] = $cartonInfo->returnCartonFilterArray();
                }
                $returnArray = array();
                $returnArray['row_returned'] = $rowCount;
                $returnArray['carton'] = $cartonArray;
                $response = new Response();
                $response->setHttpStatusCode(200);
                $response->setSuccess(true);
                $response->toCache(true);
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
        }
    } catch (CartonFilterException $ex) {
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
    $response->setHttpStatusCode(405);
    $response->setSuccess(false);
    $response->addMessage("Request method not allowed");
    $response->send();
    exit();
}
