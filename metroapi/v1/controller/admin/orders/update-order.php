<?php


include_once('../../db.php');
include_once('../../../model/admin/orders/update-orderStatusModel.php');
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
        $orderInfo = new OrderStatus($jsonData->orderno, $jsonData->so_status);
        $orderno = $orderInfo->getOrderno();
        $so_status = $orderInfo->getSo_status();
        // find order 
        $findOrder = $readDB->prepare('SELECT * FROM `salesorders` WHERE `orderno`=:orderno');
        $findOrder->bindParam(':orderno', $orderno, PDO::PARAM_STR);
        $findOrder->execute();
        $rowCount = $findOrder->rowCount();
        if ($rowCount === 0) {
            $response = new Response();
            $response->setHttpStatusCode(404);
            $response->setSuccess(false);
            $response->addMessage("Order not found");
            $response->send();
            exit();
        }
        // update order status 
        $updateOrder = $writeDB->prepare('UPDATE `salesorders` SET `so_status`=:so_status WHERE `orderno`=:orderno');
        $updateOrder->bindParam(':so_status', $so_status, PDO::PARAM_STR);
        $updateOrder->bindParam(':orderno', $orderno, PDO::PARAM_STR);
        $updateOrder->execute();
        $rowCount = $updateOrder->rowCount();
        if ($rowCount === 1) {
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->addMessage("Order status update success.");
            $response->send();
            exit();
        } else {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage('Nothing to be updated');
            $response->send();
            exit();
        }
    } catch (StatusException $ex) {
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
