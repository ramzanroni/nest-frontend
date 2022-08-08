<?php
include_once('db.php');
include_once('../model/orderModel.php');
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
if (array_key_exists('token', $_GET)) {
    $token = trim($_GET['token']);
    if ($token === '') {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        $response->addMessage("Token ID cannot be blank");
        $response->send();
        exit();
    }
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        try {
            $query = $readDB->prepare('SELECT salesorders.orderno AS orderno, salesorders.orddate AS orddate, salesorders.so_status AS so_status FROM salesorders INNER JOIN users ON users.id = salesorders.debtorno WHERE users.user_token=:token ORDER BY salesorders.orddate DESC');
            $query->bindParam(':token', $token, PDO::PARAM_STR);
            $query->execute();
            $rowCount = $query->rowCount();
            $orderArray = array();
            if ($rowCount !== 0) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

                    $order = new Order($row['orderno'], $row['orddate'], $row['so_status']);
                    $orderArray[] = $order->returnOrderArray();
                }
                $returnArray = array();
                $returnArray['row_returned'] = $rowCount;
                $returnArray['orders'] = $orderArray;
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
        } catch (OrderException $ex) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("Faild to get tasks.");
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
} elseif (array_key_exists('order_id', $_GET)) {
    // cancel order
    $order_id = trim($_GET['order_id']);
    if ($order_id === '') {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        $response->addMessage("Order ID cannot be blank");
        $response->send();
        exit();
    }
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        try {
            $query = $writeDB->prepare('UPDATE salesorders SET so_status=3 WHERE orderno=:orderno');
            $query->bindParam(':orderno', $order_id, PDO::PARAM_INT);
            $query->execute();
            $rowCount = $query->rowCount();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage('Order not found');
                $response->send();
                exit();
            } else {
                $response = new Response();
                $response->setHttpStatusCode(200);
                $response->setSuccess(true);
                $response->addMessage("Cancel order Success.");
                $response->send();
                exit();
            }
        } catch (PDOException $th) {
            error_log("Database query error." . $ex, 0);
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
        }
    } else {
        $response = new Response();
        $response->setHttpStatusCode(405);
        $response->setSuccess(false);
        $response->addMessage("Request method not allowed");
        $response->send();
        exit();
    }
} elseif (array_key_exists('item_id', $_GET) && array_key_exists('orderID', $_GET)) {
    $item_id = trim($_GET['item_id']);
    $orderID = trim($_GET['orderID']);
    if ($item_id === '' || $orderID === '' || !is_numeric($orderID) || !is_numeric($item_id)) {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        $response->addMessage("Item and order ID cannot be blank and text data Id must be numeric");
        $response->send();
        exit();
    }
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        try {
            $query = $writeDB->prepare('UPDATE salesorderdetails SET completed=3 WHERE orderlineno=:item_id AND orderno=:orderno');
            $query->bindParam(':item_id', $item_id, PDO::PARAM_INT);
            $query->bindParam(':orderno', $orderID, PDO::PARAM_INT);
            $query->execute();
            $rowCount = $query->rowCount();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage('Item not found');
                $response->send();
                exit();
            } else {
                $response = new Response();
                $response->setHttpStatusCode(200);
                $response->setSuccess(true);
                $response->addMessage("Cancel Item Success.");
                $response->send();
                exit();
            }
        } catch (PDOException $th) {
            error_log("Database query error." . $ex, 0);
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
        }
    } else {
        $response = new Response();
        $response->setHttpStatusCode(405);
        $response->setSuccess(false);
        $response->addMessage("Request method not allowed");
        $response->send();
        exit();
    }
}
