<?php
include_once('db.php');
include_once('../model/areaModel.php');
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
if ($_SERVER['REQUEST_METHOD'] === "GET") {

    if (array_key_exists('id', $_GET)) {
        $id = $_GET['id'];
        $query = "SELECT * FROM area WHERE id='$id'";
    } else {
        $query = 'SELECT * FROM area';
    }
    try {
        $areaData = $readDB->prepare($query);
        $areaData->execute();
        $rowCount = $areaData->rowCount();
        $areaArray = array();
        while ($row = $areaData->fetch(PDO::FETCH_ASSOC)) {
            $area = new Area($row['id'], $row['area_name'], $row['delivery_charge']);
            $areaArray[] = $area->returnAreaArray();
        }
        $returnArray = array();
        $returnArray['rows_returned'] = $rowCount;
        $returnArray['area'] = $areaArray;
        $response = new Response();
        $response->setHttpStatusCode(200);
        $response->toCache(true);
        $response->setSuccess(true);
        $response->setData($returnArray);
        $response->send();
        exit;
    } catch (AreaException $ex) {
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
    $response->addMessage("Request method not allowed.");
    $response->send();
    exit();
}
