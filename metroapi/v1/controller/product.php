<?php
include_once('db.php');
include_once('../model/productModel.php');
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

    $dataArr = array();
    function display_children($parent, $level)
    {
        $readDB = DB::connectReadDB();
        $result = $readDB->prepare('SELECT groupid FROM stockgroup ' . 'WHERE parent="' . $parent . '"');
        $result->execute();
        $count = "";
        global  $dataArr;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $dataArr[] =  str_repeat(' ', $level) . $row['groupid'];
            if ($count != "")
                $count .= (1 + display_children($row['groupid'], $level + 1));
            else
                $count = ", " . (1 + display_children($row['groupid'], $level + 1));
        }
        return json_encode($dataArr);
    }


    $mainQuery = 'SELECT stockmaster.stockid AS stockid, stockmaster.description AS description, stockmaster.longdescription AS longdescription, stockmaster.units AS units, stockmaster.discountcategory AS discountcategory, stockmaster.taxcatid AS taxcatid, stockmaster.webprice AS webprice, stockmaster.img AS img, stockgroup.groupname AS category, stockgroup.groupid AS categoryId FROM stockmaster INNER JOIN stockgroup ON stockmaster.groupid = stockgroup.groupid AND stockmaster.webprice !=0';

    if (array_key_exists('product_id', $_GET)) {
        $product_id = $_GET['product_id'];
        $query = $readDB->prepare($mainQuery . ' where stockmaster.stockid= :stockid');
        $query->bindParam(':stockid', $product_id, PDO::PARAM_INT);
    } elseif (array_key_exists('category_id', $_GET) && array_key_exists('limit', $_GET) && array_key_exists('start', $_GET)) {
        $categoryId = $_GET['category_id'];
        $dataArr[] = $categoryId;
        $idarray = json_decode(display_children($categoryId, 0));


        $limit = $_GET['limit'];
        $sort_by = $_GET['sort_by'];
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

        //groupid IN (' . implode(",", $idarray) . ')'
        if ($limit == "All") {
            $query = $readDB->prepare($mainQuery . ' WHERE stockmaster.groupid IN (' . implode(",", $idarray) . ') ORDER BY stockmaster.webprice ' . $condition . '');
        } else {
            $query = $readDB->prepare($mainQuery . ' WHERE stockmaster.groupid IN (' . implode(",", $idarray) . ') ORDER BY stockmaster.webprice ' . $condition . ' LIMIT ' . $start . ',' . $limit . '');
        }
    } elseif (array_key_exists('product_name', $_GET)) {
        $product_name = $_GET['product_name'];
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
            $dataArr[] = $category;
            $idarray = json_decode(display_children($category, 0));
            $query = $readDB->prepare($mainQuery . ' WHERE ' . $textsearchQury . ' AND stockmaster.groupid IN (' . implode(",", $idarray) . ')');
        } else {
            $query = $readDB->prepare($mainQuery . ' WHERE ' . $textsearchQury);
        }
    } else {
        $start = 0;
        if (isset($_GET['start'])) {
            $start = $_GET['start'];
        }
        $limit = 5;
        if (isset($_GET['limit'])) {
            if ($_GET == "All") {
                $limit = "All";
            } else {
                $limit = $_GET['limit'];
            }
        }
        $condition = "ASC";


        if ($limit == "All") {
            $querySQL = $mainQuery . ' ORDER BY stockmaster.webprice ' . $condition . '';
        } else {
            $querySQL = $mainQuery . ' ORDER BY stockmaster.webprice ' . $condition . ' LIMIT ' . $start . ',' . $limit . '';
        }
        $query = $readDB->prepare($querySQL);
    }
    try {
        $query->execute();
        // echo $query->debugDumpParams();
        // exit;

        $productArray = array();
        $rowCount = $query->rowCount();
        // $ip_server = 'https://neo.fuljor.com/erp/companies/neo_bazar/part_pics/';
        $ip_server = 'http://' . $_SERVER['SERVER_ADDR'] . "/" . "metroapi/v1/images/";
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
                $img =  $ip_server . "000.jpg";
            } else {
                $img = $ip_server . $row['stockid'] . '.jpg';
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
            $response = new Response();
            $response->setHttpStatusCode(403);
            $response->setSuccess(false);
            $response->addMessage("No data found");
            $response->send();
            exit;
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
