<?php
include_once('db.php');
include_once('../model/categoryModel.php');
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
if (empty($_GET)) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        try {
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
            $web = 1;
            $query = $readDB->prepare('SELECT groupid, groupname,parent, image FROM stockgroup WHERE web=:web AND parent=0');
            $query->bindParam(':web', $web, PDO::PARAM_INT);
            $query->execute();
            $rowCount = $query->rowCount();
            $categoryArray = array();
            $ip_server = 'http://' . $_SERVER['SERVER_ADDR'] . "/" . "metroapi/v1/";
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $categoryID = $row['groupid'];
                $dataArr[] = $categoryID;
                $idarray = json_decode(display_children($categoryID, 0));
                $countItem = $readDB->prepare('SELECT * FROM stockmaster WHERE groupid IN (' . implode(",", $idarray) . ')');
                $countItem->execute();
                $numberofItem = $countItem->rowCount();
                // $numberofItem = 1;
                if ($row['image'] == '') {
                    $img = '';
                } else {
                    $img = $ip_server . $row['image'];
                }
                $category = new Category($row['groupid'], $row['groupname'], $img, $numberofItem, $row['parent']);
                $categoryArray[] = $category->returnCategoryArray();
                $dataArr = array();
            }
            $returnArray = array();
            $returnArray['rows_returned'] = $rowCount;
            $returnArray['category'] = $categoryArray;
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->toCache(true);
            $response->setSuccess(true);
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
        } catch (CategoryException $ex) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
            exit;
        }
    } else {
        $response = new Response();
        $response->setHttpStatusCode(405);
        $response->setSuccess(false);
        $response->addMessage("Request method not allowed");
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
