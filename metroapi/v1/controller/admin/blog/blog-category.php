<?php
include_once('../../db.php');
include_once('../../../model/admin/blog/blogCategoryModel.php');
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
        $category = new BlogCategory(null, $jsonData->category_name, null);
        $category_name = $category->getCategory_name();
        // insert category data 
        $addCategory = $writeDB->prepare('INSERT INTO `blog_category`(`category_name`) VALUES (:category_name)');
        $addCategory->bindParam(':category_name', $category_name, PDO::PARAM_STR);
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
    } catch (BlogCategoryException $ex) {
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
        $category = new BlogCategory($jsonData->id, $jsonData->category_name, $jsonData->status);
        $id = $category->getId();
        $category_name = $category->getCategory_name();
        $status = $category->getStatus();

        //check category
        $checkCategory = $readDB->prepare('SELECT * FROM `blog_category` WHERE `id`=:id');
        $checkCategory->bindParam(':id', $id, PDO::PARAM_STR);
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
        $mainQurey = 'UPDATE blog_category SET ';
        if ($category_name != '') {
            $mainQurey = $mainQurey . 'category_name=:category_name, ';
        }
        if ($status != '') {
            $mainQurey = $mainQurey . 'status=:status ,';
        }
        $mainQurey = substr($mainQurey, 0, -2) . ' WHERE id=:id';

        $updateCategory = $writeDB->prepare($mainQurey);
        if ($category_name != '') {
            $updateCategory->bindParam(':category_name', $category_name, PDO::PARAM_STR);
        }
        if ($status != '') {
            $updateCategory->bindParam(':status', $status, PDO::PARAM_STR);
        }
        $updateCategory->bindParam(':id', $id, PDO::PARAM_STR);
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
    } catch (BlogCategoryException $ex) {
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

        $checkCategory = $readDB->prepare('SELECT * FROM `blog_category
        ` WHERE `id`=:id');
        $checkCategory->bindParam(':id', $categoryId, PDO::PARAM_STR);
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
        $deleteCategory = $writeDB->prepare('DELETE FROM `blog_category` WHERE `id`=:id');
        $deleteCategory->bindParam(':id', $categoryId, PDO::PARAM_STR);
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
    if (array_key_exists('id', $_GET)) {
        $id = $_GET['id'];
        $categorys = $readDB->prepare('SELECT * FROM `blog_category` WHERE id=:id');
        $categorys->bindParam(':id', $id, PDO::PARAM_STR);
    } elseif (array_key_exists('name', $_GET)) {
        $name = $_GET['name'];
        if ($name === '') {
            $response = new Response();
            $response->setHttpStatusCode(403);
            $response->setSuccess(false);
            $response->addMessage('Category Name missing its not be null. ');
            $response->send();
            exit();
        }
        $subQry = "SELECT * FROM `blog_category` WHERE ";
        $textsearchQury = '';

        $searchKeywordList = explode(' ', $name);

        foreach ($searchKeywordList as $searchKey) {
            $textsearchQury .= "category_name LIKE '%" . $searchKey . "%' OR ";
        }
        $textsearchQury = $subQry . rtrim($textsearchQury, 'OR ');
        $categorys = $readDB->prepare($textsearchQury);
    } else {
        $categorys = $readDB->prepare('SELECT * FROM `blog_category`');
    }
    $categorys->execute();
    $rowCount = $categorys->rowCount();
    $categoryArray = array();
    while ($row = $categorys->fetch(PDO::FETCH_ASSOC)) {
        $cat = new BlogCategory($row['id'], $row['category_name'], $row['status']);
        $categoryArray[] = $cat->returnBlogCategoryArray();
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
