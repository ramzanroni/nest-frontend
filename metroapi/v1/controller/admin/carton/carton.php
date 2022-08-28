<?php

use LDAP\Result;

include_once('../../db.php');
include_once('../../../model/admin/catron/cartonModel.php');
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
    if (array_key_exists('order_id', $_GET)) {
        try {
            $order_id = $_GET['order_id'];
            if ($order_id === '' || !is_numeric($order_id)) {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Order ID cannot be blank or string");
                $response->send();
                exit();
            }
            $query = $readDB->prepare('SELECT l.id,
            (
            SELECT
                m.description
            FROM
                carton_status_details s,
                carton_list c,
                carton_status_list m
            WHERE
                s.cid = c.id AND c.id = l.id AND s.sid = m.id AND m.id =(
                SELECT
                    MAX(s.sid)
                FROM
                    carton_status_details s,
                    carton_list c
                WHERE
                    s.cid = c.id AND c.id = l.id
            )
        ) AS carton_status,
        (
            SELECT
                s.create_at
            FROM
                carton_status_details s,
                carton_list c
            WHERE
                s.cid = c.id AND s.sid = 1 AND c.id = l.id
        ) AS date_packed,
        (
            SELECT
                s.create_at
            FROM
                carton_status_details s,
                carton_list c
            WHERE
                s.cid = c.id AND s.sid = 2 AND c.id = l.id
        ) AS date_shiped,
        (
            SELECT
                s.create_at
            FROM
                carton_status_details s,
                carton_list c
            WHERE
                s.cid = c.id AND s.sid = 3 AND c.id = l.id
        ) AS date_delivered
        FROM
            carton_list l
        WHERE
            l.so =:orderID');
            $query->bindParam(':orderID', $order_id, PDO::PARAM_STR);
            $query->execute();
            $rowCount = $query->rowCount();
            $cartonArray = array();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(200);
                $response->addMessage('Order not found');
                $response->setSuccess(false);
                $response->send();
                exit;
            }
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $packetId = $row['id'];
                $catron_status = $row['carton_status'];
                // $carton = new Carton($row['id'], $row['carton_status'], $row['date_packed'], $row['date_shiped'], $row['date_delivered']);
                $cartonDetails = $readDB->prepare('SELECT carton_list_details.qty as qty, salesorderdetails.unitprice as price FROM carton_list_details INNER JOIN salesorderdetails ON carton_list_details.stockid = salesorderdetails.stkcode WHERE carton_list_details.cid=:packetId AND salesorderdetails.orderno=:orderno');
                $cartonDetails->bindParam(':packetId', $packetId, PDO::PARAM_STR);
                $cartonDetails->bindParam(':orderno', $order_id, PDO::PARAM_STR);
                $cartonDetails->execute();
                $rowCount = $cartonDetails->rowCount();
                $totalQty = 0;
                $totalPrice = 0;
                if ($rowCount != 0) {
                    while ($rowCarton = $cartonDetails->fetch(PDO::FETCH_ASSOC)) {
                        $totalQty = $totalQty + $rowCarton['qty'];
                        $totalPrice = $totalPrice + $rowCarton['price'];
                    }
                }
                $catron = new CartonPackage($packetId, $catron_status, $totalQty, $totalPrice);
                $cartonArray = $catron->returnCartonArray();
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
        } catch (PDOException $ex) {
            error_log("Database query error - " . $ex, 1);
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("Failed to get task");
            $response->send();
            exit();
        } catch (CartonPackException $ex) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
            exit;
        }
    } elseif (array_key_exists('orderId', $_GET) && array_key_exists('carton_id', $_GET)) {
        $orderId = $_GET['orderId'];
        $cartonId = $_GET['carton_id'];
        if ($orderId == '' || $cartonId == '') {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("Order ID or Carton ID cannot be blank");
            $response->send();
            exit();
        }
        try {
            $ip_server = $_SERVER['SERVER_ADDR'] . "/" . "metroapi/v1/";
            $query = $readDB->prepare('SELECT
            stockmaster.stockid,
            stockmaster.img,
            stockmaster.description,
            stockmaster.webprice,
            carton_list_details.qty
        FROM
            carton_list,
            carton_list_details,
            stockmaster
        WHERE
            carton_list.id = carton_list_details.cid AND
            carton_list_details.stockid = stockmaster.stockid AND
            carton_list.so = :order_id AND carton_list.id = :carton_id');
            $query->bindParam(':order_id', $orderId, PDO::PARAM_STR);
            $query->bindParam(':carton_id', $cartonId, PDO::PARAM_STR);
            $query->execute();
            $rowCount = $query->rowCount();
            $cartonListArray = array();

            if ($rowCount !== 0) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $cartonList = new CartonItem($row['qty'], $ip_server . $row['img'], $row['stockid'], $row['description'], $row['webprice']);
                    $cartonListArray[] = $cartonList->returnCartonItemArray();
                }
                $returnArray = array();
                $returnArray['row_returned'] = $rowCount;
                $returnArray['cartonItem'] = $cartonListArray;
                $response = new Response();
                $response->setHttpStatusCode(200);
                $response->setSuccess(true);
                $response->toCache(true);
                $response->setData($returnArray);
                $response->send();
                // exit;
            } else {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("Data not found");
                $response->send();
                exit;
            }
        } catch (PDOException $ex) {
            error_log("Database query error - " . $ex, 1);
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("Failed to get task");
            $response->send();
            exit();
        } catch (CartonPackException $ex) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
            exit;
        }
    } elseif (array_key_exists('pack_id', $_GET) && array_key_exists('orderId', $_GET)) {
        $pack_id = $_GET['pack_id'];
        $order_id = $_GET['orderId'];
        if ($pack_id === '' || !is_numeric($pack_id)) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("Pack ID cannot be blank or string");
            $response->send();
            exit();
        }
        try {
            $query = $readDB->prepare('SELECT MAX(carton_status_details.sid) as max_id 
            FROM carton_status_details
            INNER JOIN carton_list
            ON carton_status_details.cid = carton_list.id WHERE carton_list.so=:orderID AND carton_list.id=:pack_id');

            $query->bindParam(':orderID', $order_id, PDO::PARAM_STR);
            $query->bindParam(':pack_id', $pack_id, PDO::PARAM_STR);
            $query->execute();
            $rowCount = $query->rowCount();
            if ($rowCount === 1) {
                $rowStatus = $query->fetch(PDO::FETCH_ASSOC);
                if ($rowStatus['max_id'] == null) {
                    $response = new Response();
                    $response->setHttpStatusCode(404);
                    $response->setSuccess(false);
                    $response->addMessage('Package not found');
                    $response->send();
                    exit;
                }
                $carton_status = $rowStatus['max_id'];

                if ($carton_status > 2) {
                    $response = new Response();
                    $response->setHttpStatusCode(400);
                    $response->setSuccess(false);
                    $response->addMessage('This pack already Deliverd.');
                    $response->send();
                    exit;
                }
                // check carton  Shipping
                $checkCarton = $readDB->prepare('SELECT * FROM `carton_list` WHERE `id`=:pack_id');
                $checkCarton->bindParam(':pack_id', $pack_id, PDO::PARAM_STR);
                $checkCarton->execute();
                $rowCount = $checkCarton->rowCount();
                if ($rowCount === 0) {
                    $$response = new Response();
                    $response->setHttpStatusCode(404);
                    $response->setSuccess(false);
                    $response->addMessage('Pack not found');
                    $response->send();
                    exit;
                }
                // delete from cartonlist 
                $deleteCarton = $writeDB->prepare('DELETE FROM `carton_list` WHERE id=:pack_id');
                $deleteCarton->bindParam(':pack_id', $pack_id, PDO::PARAM_STR);
                $deleteCarton->execute();
                $rowCountCartonlist = $deleteCarton->rowCount();
                if ($rowCountCartonlist === 1) {
                    // delete carton details 
                    $deleteCartonDetails = $writeDB->prepare('DELETE FROM `carton_list_details` WHERE `cid`=:pack_id');
                    $deleteCartonDetails->bindParam(':pack_id', $pack_id, PDO::PARAM_STR);
                    $deleteCartonDetails->execute();
                    $rowCountCartonDatails = $deleteCartonDetails->rowCount();
                    if ($rowCountCartonDatails === 1) {
                        // delete carton status details 
                        $deleteCartonStatusDetails = $writeDB->prepare('DELETE FROM `carton_status_details` WHERE `cid`=:pack_id');
                        $deleteCartonStatusDetails->bindParam(':pack_id', $pack_id, PDO::PARAM_STR);
                        $deleteCartonStatusDetails->execute();
                        $rowCountCartonStatusDetails = $deleteCartonStatusDetails->rowCount();
                        if ($rowCountCartonStatusDetails == 1) {
                            $response = new Response();
                            $response->setHttpStatusCode(200);
                            $response->setSuccess(true);
                            $response->addMessage('Unpacked success.');
                            $response->send();
                            exit;
                        }
                    }
                }
            } else {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage('Order not found');
                $response->send();
                exit;
            }
        } catch (PDOException $ex) {
            error_log("Database query error - " . $ex, 1);
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
            exit();
        } catch (CartonPackException $ex) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
            exit;
        }
    }
} else {
    $response = new Response();
    $response->setHttpStatusCode(405);
    $response->setSuccess(false);
    $response->addMessage("Request method not allowed");
    $response->send();
    exit();
}
