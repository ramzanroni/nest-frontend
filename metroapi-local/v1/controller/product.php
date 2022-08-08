<?php
include_once('db.php');
include_once('../model/productModel.php');
include_once('../model/response.php');
header('Access-Control-Allow-Origin: *');
header('Authorization');

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
    $mainQuery = 'SELECT stockmaster.stockid AS stockid, stockmaster.description AS description, stockmaster.longdescription AS longdescription, stockmaster.units AS units, stockmaster.discountcategory AS discountcategory, stockmaster.taxcatid AS taxcatid, stockmaster.webprice AS webprice, stockmaster.img AS img, stockgroup.groupname AS category, stockgroup.groupid AS categoryId FROM stockmaster INNER JOIN stockgroup ON stockmaster.groupid = stockgroup.groupid';

    if (array_key_exists('product_id', $_GET)) {
        $product_id = trim($_GET['product_id']);
        $query = $readDB->prepare($mainQuery . ' where stockmaster.stockid= :stockid');
        $query->bindParam(':stockid', $product_id, PDO::PARAM_INT);
    } elseif (array_key_exists('category_id', $_GET) && array_key_exists('limit', $_GET) && array_key_exists('start', $_GET)) {
        $categoryId = trim($_GET['category_id']);
        $limit = trim($_GET['limit']);
        $sort_by = trim($_GET['sort_by']);
        if ($sort_by == "PriceLowtoHigh") {
            $condition = "ASC";
        }
        if ($sort_by == "PriceHightoLow") {
            $condition = "DESC";
        }
        if ($_GET['start'] == '') {
            $start = 0;
        } else {
            $start = $_GET['start'];
        }
        if ($limit == "All") {
            $query = $readDB->prepare($mainQuery . ' WHERE stockmaster.groupid=:category_id ORDER BY stockmaster.webprice ' . $condition . '');
            $query->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        } else {
            $query = $readDB->prepare($mainQuery . ' WHERE stockmaster.groupid=:category_id ORDER BY stockmaster.webprice ' . $condition . ' LIMIT ' . $start . ',' . $limit . '');
            $query->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        }
    } elseif (array_key_exists('product_name', $_GET)) {
        $product_name = trim($_GET['product_name']);
        if ($product_name === '') {
            $response = new Response();
            $response->setHttpStatusCode(403);
            $response->setSuccess(false);
            $response->addMessage('Product Name missing its not be null. ');
            $response->send();
            exit();
        }
        $textsearchQury = '';

        $searchKeywordList = explode(' ', $product_name);

        foreach ($searchKeywordList as $searchKey) {
            $textsearchQury = $textsearchQury . " stockmaster.description LIKE '%" . $searchKey . "%' OR ";
        }
        $textsearchQury = rtrim($textsearchQury, 'OR ');

        if (array_key_exists('category', $_GET)) {
            $category = $_GET['category'];
            $query = $readDB->prepare($mainQuery . ' WHERE ' . $textsearchQury . ' AND stockmaster.groupid=:category_id');
            $query->bindParam(':category_id', $category, PDO::PARAM_INT);
        } else {
            $query = $readDB->prepare($mainQuery . ' WHERE ' . $textsearchQury);
        }
    } elseif (empty($_GET)) {
        $querySQL = $mainQuery;
        $query = $readDB->prepare($querySQL);
    } else {
        $response = new Response();
        $response->setHttpStatusCode(404);
        $response->setSuccess(false);
        $response->addMessage('Endpoint not found');
        $response->send();
        exit();
    }
    try {

        $query->execute();

        $productArray = array();
        $rowCount = $query->rowCount();
        if ($rowCount === 0) {
            $response = new Response();
            $response->setHttpStatusCode(403);
            $response->setSuccess(false);
            $response->addMessage("No data found");
            $response->send();
            exit;
        }
        $ip_server = $_SERVER['SERVER_ADDR'] . "/" . "metroapi/v1/";
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $stockID = $row['stockid'];
            $multipleImg = $readDB->prepare("SELECT * FROM `item_ref_file` WHERE stockid=:stockid");
            $multipleImg->bindParam(':stockid', $stockID, PDO::PARAM_STR);
            $multipleImg->execute();
            $imgArr = array();
            while ($rowImg = $multipleImg->fetch(PDO::FETCH_ASSOC)) {
                // array_push($imgArr, $ip_server . $rowImg['doc_name']);
                $imgArr[] = $ip_server . $rowImg['doc_name'];
            }
            if ($row['img'] == '') {
                $img = '';
            } else {
                $img = $ip_server . $row['img'];
            }
            $product = new Product($row['stockid'], $row['description'], $row['categoryId'], $row['category'], $row['longdescription'], $row['units'], $row['discountcategory'], $row['taxcatid'], $row['webprice'], $img, $imgArr);
            $productArray[] = $product->returnProducrArray();
        }
        $returnArray = array();
        $returnArray['rows_returned'] = $rowCount;
        $returnArray['products'] = $productArray;
        if ($rowCount != 0) {
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->setData($returnArray);
            $response->send();
            exit;
        } else {
        }
    } catch (ProductException $ex) {
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
