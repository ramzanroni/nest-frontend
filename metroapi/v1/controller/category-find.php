<?php
include_once('db.php');
// include_once('../model/categoryModel.php');
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
    $query = $readDB->prepare("select a.groupid,a.groupname,a.parent,
    (CASE WHEN a.parent<>0 THEN (select groupname from stockgroup where groupid=a.parent) ELSE '' END) as parentName
    from stockgroup a where 1=1 order by a.groupid");
    $query->execute();
    $rowCount = $query->rowCount();
    $menu = array();
    if ($rowCount > 0) {
        $menu = array(
            'items' => array(),
            'parents' => array()
        );
        while ($items = $query->fetch(PDO::FETCH_ASSOC)) {
            $itemGroupID = $items["groupid"];
            $sql = $readDB->prepare("SELECT * FROM stockgroup where groupid=:groupid");
            $sql->bindParam(':groupid', $itemGroupID, PDO::PARAM_STR);
            $sql->execute();
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $menu['items'][$itemGroupID] = $row;
            $menu['parents'][$items['parent']][] = $itemGroupID;
        }
        $returnArray = array();
        $returnArray['rows_returned'] = $rowCount;
        $returnArray['menu'] = $menu;
        $response = new Response();
        $response->setHttpStatusCode(200);
        $response->toCache(true);
        $response->setSuccess(true);
        $response->setData($returnArray);
        $response->send();
        exit;
    }
} else {
    $response = new Response();
    $response->setHttpStatusCode(404);
    $response->setSuccess(false);
    $response->addMessage("Endpoint not found");
    $response->send();
    exit;
}
