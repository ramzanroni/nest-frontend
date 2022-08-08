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
        $orderNumber = random_int(100000, 999999);
        $newOrder = new CreateOrder(trim($jsonData->name), trim($jsonData->address), trim($jsonData->area), trim($jsonData->phone), trim($jsonData->town), (isset($jsonData->additionalPhone) ? trim($jsonData->additionalPhone) : null), (isset($jsonData->additionalInfo) ? trim($jsonData->additionalInfo) : null), trim($jsonData->token), trim($jsonData->paymentMethod));

        // find user
        $token = $newOrder->getToken();
        $findUserID = $readDB->prepare('SELECT * FROM users WHERE user_token=:token');
        $findUserID->bindParam(':token', $token, PDO::PARAM_STR);
        $findUserID->execute();
        $rowCount = $findUserID->rowCount();
        if ($rowCount === 1) {
            $userIDInfo = $findUserID->fetch(PDO::FETCH_ASSOC);
            $userId = $userIDInfo['id'];
            $delivery_status = 0;
            $orderDate = date("Y-m-d");
            $branchcode = 1;
            $customerref = 'SO:1';
            $tag = 1;
            $ordertype = 'DP';
            $shipvia = 1;
            $deliverto = 'main';
            $deliverblind = 1;
            $fromstkloc = 1010;
            $printedpackingslip = 0;
            $delivery_status = 0;
            $name = $newOrder->getName();
            $address = $newOrder->getAddress();
            $area = $newOrder->getArea();
            $phone = $newOrder->getPhone();
            $town = $newOrder->getTown();
            $additionalPhone = $newOrder->getAdditionalPhone();
            $additionalInfo = $newOrder->getAdditionalInfo();
            $pamentMethod = $newOrder->getPaymentMethod();
            // $finalAddress = $address . " " . $area . " " . $town;

            try {
                $orderInsert = $writeDB->prepare('INSERT INTO salesorders(orderno, debtorno,comments, branchcode, customerref, tag,  orddate, ordertype, shipvia, deladd1, contactphone, deliverto, deliverblind, fromstkloc, printedpackingslip,delivery_status,issue_date) VALUES (:orderno, :debtorno, :comments, :branchcode, :customerref, :tag,:orddate,:ordertype, :shipvia,:deladd1,:contactphone,:deliverto,:deliverblind,:fromstkloc,:printedpackingslip,:delivery_status,:issue_date)');
                $orderInsert->bindParam(':orderno', $orderNumber, PDO::PARAM_STR);
                $orderInsert->bindParam(':debtorno', $userId, PDO::PARAM_STR);
                $orderInsert->bindParam(':comments', $additionalInfo, PDO::PARAM_STR);
                $orderInsert->bindParam(':branchcode', $branchcode, PDO::PARAM_STR);
                $orderInsert->bindParam(':customerref', $customerref, PDO::PARAM_STR);
                $orderInsert->bindParam(':tag', $tag, PDO::PARAM_STR);
                $orderInsert->bindParam(':orddate', $orderDate, PDO::PARAM_STR);
                $orderInsert->bindParam(':ordertype', $ordertype, PDO::PARAM_STR);
                $orderInsert->bindParam(':shipvia', $shipvia, PDO::PARAM_STR);
                $orderInsert->bindParam(':deladd1', $address, PDO::PARAM_STR);
                $orderInsert->bindParam(':contactphone', $additionalPhone, PDO::PARAM_STR);
                $orderInsert->bindParam(':deliverto', $deliverto, PDO::PARAM_STR);
                $orderInsert->bindParam(':deliverblind', $deliverblind, PDO::PARAM_STR);
                $orderInsert->bindParam(':fromstkloc', $fromstkloc, PDO::PARAM_STR);
                $orderInsert->bindParam(':printedpackingslip', $printedpackingslip, PDO::PARAM_STR);
                $orderInsert->bindParam(':delivery_status', $delivery_status, PDO::PARAM_STR);
                $orderInsert->bindParam(':issue_date', $orderDate, PDO::PARAM_STR);
                $orderInsert->execute();
                $rowCount = $orderInsert->rowCount();
                if ($rowCount === 1) {
                    $orderItem = $jsonData->itemInfo;
                    $stkcode = 0;
                    $discount_amount = 0;
                    $qtyinvoiced = 0;
                    $org_so_qty = 0;
                    $orderlineno = 1;
                    foreach ($orderItem as $itemValue) {
                        $itemInfo = new OrderItem($itemValue->productID, $itemValue->unitPrice, $itemValue->productQuantity);

                        $itemId = $itemInfo->getProductId();
                        $itemQuantity = $itemInfo->getProductQuantity();
                        $unitPrice = $itemInfo->getUnitPrice();
                        $addItem = $writeDB->prepare('INSERT INTO salesorderdetails(orderlineno, orderno, stkcode, qtyinvoiced, unitprice, quantity,discount_amount,org_so_qty) VALUES (:orderlineno,:orderno,:stkcode,:qtyinvoiced,:unitprice,:quantity,:discount_amount,:org_so_qty)');
                        $addItem->bindParam('orderlineno', $orderlineno, PDO::PARAM_STR);
                        $addItem->bindParam('orderno', $orderNumber, PDO::PARAM_STR);
                        $addItem->bindParam('stkcode', $itemId, PDO::PARAM_STR);
                        $addItem->bindParam('qtyinvoiced', $qtyinvoiced, PDO::PARAM_STR);
                        $addItem->bindParam('unitprice', $unitPrice, PDO::PARAM_STR);
                        $addItem->bindParam('quantity', $itemQuantity, PDO::PARAM_STR);
                        $addItem->bindParam('discount_amount', $discount_amount, PDO::PARAM_STR);
                        $addItem->bindParam('org_so_qty', $org_so_qty, PDO::PARAM_STR);
                        $addItem->execute();
                        $rowCount = $addItem->rowCount();
                        $orderlineno++;
                    }
                    $response = new Response();
                    $response->setHttpStatusCode(200);
                    $response->setSuccess(true);
                    $response->toCache(true);
                    $response->addMessage("Order Create Success.");
                    $response->send();
                    exit();
                } else {
                    $response = new Response();
                    $response->setHttpStatusCode(400);
                    $response->setSuccess(false);
                    $response->addMessage("Order can not be created");
                    $response->send();
                    exit();
                }
            } catch (OrderException $ex) {
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
            $response->addMessage("User Token is not valid.");
            $response->send();
            exit();
        }
    } catch (OrderException $ex) {
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
