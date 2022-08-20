<?php
include_once('../../db.php');
include_once('../../../model/admin/category/categoryModel.php');
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
        $category = new Categories(null, $jsonData->groupname, $jsonData->parent, $jsonData->image, $jsonData->web);
        $groupname = $category->getGroupname();
        $parent = $category->getParent();
        $image = $category->getImage();
        $web = $category->getWeb();
        // insert category data 
        $addCategory = $writeDB->prepare('INSERT INTO stockgroup(groupname, parent, image, web) VALUES (:groupname,:parent,:image,:web)');
        $addCategory->bindParam(':groupname', $groupname, PDO::PARAM_STR);
        $addCategory->bindParam(':parent', $parent, PDO::PARAM_STR);
        $addCategory->bindParam(':image', $image, PDO::PARAM_STR);
        $addCategory->bindParam(':web', $web, PDO::PARAM_STR);
        $addCategory->execute();
        $rowCount = $addCategory->rowCount();
        if ($rowCount === 1) {
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->addMessage('Category added success.');
            $response->send();
            exit;
        }
    } catch (CategoryException $ex) {
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
} elseif ($_SERVER['REQUEST_METHOD'] === "PUT") {
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
        $category = new Categories($jsonData->groupid, $jsonData->groupname, $jsonData->parent, $jsonData->image, $jsonData->web);
        $groupid = $category->getGroupid();
        $groupname = $category->getGroupname();
        $parent = $category->getParent();
        $image = $category->getImage();
        $web = $category->getWeb();

        //check category
        $checkCategory = $readDB->prepare('SELECT * FROM `stockgroup` WHERE `groupid`=:groupid');
        $checkCategory->bindParam(':groupid', $groupid, PDO::PARAM_STR);
        $checkCategory->execute();
        $rowCount = $checkCategory->rowCount();
        if ($rowCount === 0) {
            $response = new Response();
            $response->setHttpStatusCode(404);
            $response->setSuccess(false);
            $response->addMessage("Category not found");
            $response->send();
            exit();
        }

        // update category data 
        $updateCategory = $writeDB->prepare('UPDATE stockgroup SET groupname=:groupname,parent=:parent,image=:image,web=:web WHERE groupid=:groupid');
        $updateCategory->bindParam(':groupname', $groupname, PDO::PARAM_STR);
        $updateCategory->bindParam(':parent', $parent, PDO::PARAM_STR);
        $updateCategory->bindParam(':image', $image, PDO::PARAM_STR);
        $updateCategory->bindParam(':web', $web, PDO::PARAM_STR);
        $updateCategory->bindParam(':groupid', $groupid, PDO::PARAM_STR);
        $updateCategory->execute();
        $rowCount = $updateCategory->rowCount();
        if ($rowCount === 1) {
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->addMessage('Category Update success.');
            $response->send();
            exit;
        } else {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage('Nothing to be change.');
            $response->send();
            exit();
        }
    } catch (CategoryException $ex) {
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
} elseif ($_SERVER['REQUEST_METHOD'] === "DELETE") {
    $categoryId = $_GET['id'];
    if ($categoryId != null && is_numeric($categoryId)) {
        // check category 

        $checkCategory = $readDB->prepare('SELECT * FROM `stockgroup` WHERE `groupid`=:groupid');
        $checkCategory->bindParam(':groupid', $categoryId, PDO::PARAM_STR);
        $checkCategory->execute();
        $rowCount = $checkCategory->rowCount();
        if ($rowCount === 0) {
            $response = new Response();
            $response->setHttpStatusCode(404);
            $response->setSuccess(false);
            $response->addMessage("Category not found");
            $response->send();
            exit();
        }

        // delete category 
        $deleteCategory = $writeDB->prepare('DELETE FROM `stockgroup` WHERE `groupid`=:groupid');
        $deleteCategory->bindParam(':groupid', $categoryId, PDO::PARAM_STR);
        $deleteCategory->execute();
        $rowCount = $deleteCategory->rowCount();
        if ($rowCount === 1) {
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->addMessage('Category Deleted success.');
            $response->send();
            exit;
        }
    } else {
        $response = new Response();
        $response->setHttpStatusCode(404);
        $response->setSuccess(false);
        $response->addMessage("Category is not valid. Its can not be null or empty");
        $response->send();
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === "GET") {
    $categorys = $readDB->prepare('SELECT * FROM `stockgroup`');
    $categorys->execute();
    $rowCount = $categorys->rowCount();
    $categoryArray = array();
    while ($row = $categorys->fetch(PDO::FETCH_ASSOC)) {
        // echo $row['groupid'];
        // echo $row['groupname'];
        // echo $row['parent'];
        // echo $row['image'];
        // echo $row['web'];
        $cat = new Categories($row['groupid'], $row['groupname'], $row['parent'], $row['image'],  $row['web']);
        // $categoryData = new Categories($row['groupid'], $row['groupname'], $row['parent'], $row['image'], $row['web']);
        $categoryArray[] = $cat->returnCategoryArray();
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
} else {
    $response = new Response();
    $response->setHttpStatusCode(404);
    $response->setSuccess(false);
    $response->addMessage("Endpoint not found");
    $response->send();
    exit;
}
