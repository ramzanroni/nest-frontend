<?php
include_once('db.php');
include_once('../model/cartonModel.php');
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
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (array_key_exists('order_id', $_GET)) {
        try {
            $order_id = trim($_GET['order_id']);
            if ($order_id === '') {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Order ID cannot be blank");
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
            if ($rowCount !== 0) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

                    $carton = new Carton($row['id'], $row['carton_status'], $row['date_packed'], $row['date_shiped'], $row['date_delivered']);
                    $cartonArray[] = $carton->returnCartonArray();
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
        } catch (CartonException $ex) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
            exit;
        }
    } elseif (array_key_exists('orderId', $_GET) && array_key_exists('carton_id', $_GET)) {
        $orderId = trim($_GET['orderId']);
        $cartonId = trim($_GET['carton_id']);
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
                exit;
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
        } catch (CartonException $ex) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
            exit;
        }
    } elseif (array_key_exists('orderNumber', $_GET) && array_key_exists('orderlineno', $_GET)) {
        $orderNumber = trim($_GET['orderNumber']);
        $orderlineno = trim($_GET['orderlineno']);
        if ($orderNumber == '' || !is_numeric($orderNumber) || $orderlineno == '' || !is_numeric($orderlineno)) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("Order ID or Carton ID cannot be blank or string");
            $response->send();
            exit();
        }
        try {
            // sql code will be here
            echo "Add SQL query. Page Carton under the metroapi folder";
        } catch (PDOException $ex) {
            error_log("Database query error - " . $ex, 1);
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("Failed to get task");
            $response->send();
            exit();
        } catch (CartonException $ex) {
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
