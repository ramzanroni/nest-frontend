<?php
include_once('../../db.php');
include_once('../../../model/admin/blog/blogModel.php');
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
        $blog = new Blog('', $jsonData->category_id, '', $jsonData->title, $jsonData->image, $jsonData->body, $jsonData->create_by, '', '', '');

        $category_id = $blog->getCategory_id();
        $title = $blog->getTitle();
        $image = $blog->getImage();
        $body = $blog->getBody();
        $create_by = $blog->getCreate_by();
        //insert query
        $blogInsert = $writeDB->prepare('INSERT INTO blog(category_id, title, image, body, create_by) VALUES (:category_id,:title,:image,:body,:create_by)');
        $blogInsert->bindParam(':category_id', $category_id, PDO::PARAM_STR);
        $blogInsert->bindParam(':title', $title, PDO::PARAM_STR);
        $blogInsert->bindParam(':image', $image, PDO::PARAM_STR);
        $blogInsert->bindParam(':body', $body, PDO::PARAM_STR);
        $blogInsert->bindParam(':create_by', $create_by, PDO::PARAM_STR);
        $blogInsert->execute();
        $rowCount = $blogInsert->rowCount();
        if ($rowCount === 1) {
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->addMessage('Blog insert success.');
            $response->send();
            exit;
        }
    } catch (BlogException $ex) {
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
} elseif ($_SERVER['REQUEST_METHOD'] === "GET") {
    $start = 0;
    if (isset($_GET['start']) && $_GET['start'] != '') {
        $start = $_GET['start'];
    }
    $limit = 0;
    if (isset($_GET['limit']) && $_GET['limit'] != '') {
        $limit = $_GET['limit'];
    }
    if ($start != '' && $limit != '') {
        $limitQuery = ' LIMIT ' . $start . ',' . $limit;
    } else {
        $limitQuery = '';
    }
    $mainQuery = "SELECT blog.id AS id, blog.category_id AS category_id, blog.title AS title, blog.image AS image, blog.body AS body, blog.create_by AS create_by, blog.created_at AS created_at, blog.update_at AS update_at, blog.status AS status, blog_category.category_name AS category_name FROM `blog` INNER JOIN blog_category ON blog_category.id=blog.category_id";
    if (array_key_exists('id', $_GET)) {
        $id = $_GET['id'];

        $subQuery = $readDB->prepare($mainQuery . " WHERE  blog.id=:id" . $limitQuery);
        $subQuery->bindParam(':id', $id, PDO::PARAM_STR);
    } elseif (array_key_exists('category_id', $_GET)) {
        $category_id = $_GET['category_id'];
        $subQuery = $readDB->prepare($mainQuery . " WHERE  blog.category_id=:category_id" . $limitQuery);
        $subQuery->bindParam(':category_id', $category_id, PDO::PARAM_STR);
    } elseif (array_key_exists('name', $_GET)) {
        $name = $_GET['name'];
        if ($name === '') {
            $response = new Response();
            $response->setHttpStatusCode(403);
            $response->setSuccess(false);
            $response->addMessage('Blog Name missing its not be null. ');
            $response->send();
            exit();
        }
        $subQry = "SELECT blog.id AS id, blog.category_id AS category_id, blog.title AS title, blog.image AS image, blog.body AS body, blog.create_by AS create_by, blog.created_at AS created_at, blog.update_at AS update_at, blog.status AS status, blog_category.category_name AS category_name FROM `blog` INNER JOIN blog_category ON blog_category.id=blog.category_id WHERE ";
        $textsearchQury = '';

        $searchKeywordList = explode(' ', $name);

        foreach ($searchKeywordList as $searchKey) {
            $textsearchQury .= " blog.title LIKE '%" . $searchKey . "%' OR ";
        }
        $subQueryTrim = $subQry . rtrim($textsearchQury, 'OR ');
        $subQuery = $readDB->prepare($subQueryTrim . $limitQuery);
    } else {
        $subQuery = $readDB->prepare($mainQuery . $limitQuery);
    }
    $subQuery->execute();
    $rowCount = $subQuery->rowCount();
    if ($rowCount > 0) {
        // $ip_server = 'http://' . $_SERVER['SERVER_ADDR'] . "/" . "metroapi/v1/images";
        $blogArray = array();
        while ($rowBlog = $subQuery->fetch(PDO::FETCH_ASSOC)) {
            $img = $rowBlog['image'];
            // $img = $ip_server . $rowBlog['image'];
            $blog = new Blog($rowBlog['id'], $rowBlog['category_id'], $rowBlog['category_name'], $rowBlog['title'], $img, $rowBlog['body'], $rowBlog['create_by'], $rowBlog['created_at'], $rowBlog['update_at'], $rowBlog['status']);
            $blogArray[] = $blog->returnBlogArray();
        }
        $returnArray = array();
        $returnArray['rows_returned'] = $rowCount;
        $returnArray['blog'] = $blogArray;
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
        $response->addMessage("No datafound");
        $response->send();
        exit;
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
        if ($jsonData->id != '') {
            $data = date('Y-m-d H:i:s');
            $blog = new Blog($jsonData->id, $jsonData->category_id, '', $jsonData->title, $jsonData->image, $jsonData->body, $jsonData->create_by, '', $data, $jsonData->status);

            $id = $blog->getId();
            $category_id = $blog->getCategory_id();
            $title = $blog->getTitle();
            $image = $blog->getImage();
            $body = $blog->getBody();
            $create_by = $blog->getCreate_by();
            $update_at = $blog->getUpdate_at();
            $status = $blog->getStatus();

            // check blog 
            $checkBlog = $readDB->prepare('SELECT * FROM blog WHERE id=:id');
            $checkBlog->bindParam(':id', $id, PDO::PARAM_STR);
            $checkBlog->execute();
            $rowCount = $checkBlog->rowCount();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("Blog not found");
                $response->send();
                exit();
            }

            // update blog 
            $mainUpQuery = "UPDATE blog SET ";
            if ($category_id != '') {
                $mainUpQuery = $mainUpQuery . 'category_id=:category_id, ';
            }
            if ($title != '') {
                $mainUpQuery = $mainUpQuery . 'title=:title, ';
            }
            if ($image != '') {
                $mainUpQuery = $mainUpQuery . 'image=:image, ';
            }
            if ($body != '') {
                $mainUpQuery = $mainUpQuery . 'body=:body, ';
            }
            if ($status != '') {
                $mainUpQuery = $mainUpQuery . 'status=:status, ';
            }
            $mainUpQuery = substr($mainUpQuery, 0, -2) . ', update_at=:update_at WHERE id=:id';
            $updateBlog = $writeDB->prepare($mainUpQuery);
            $updateBlog->bindParam(':id', $id, PDO::PARAM_STR);
            if ($category_id != '') {
                $updateBlog->bindParam(':category_id', $category_id, PDO::PARAM_STR);
            }
            if ($title != '') {
                $updateBlog->bindParam(':title', $title, PDO::PARAM_STR);
            }
            if ($image != '') {
                $updateBlog->bindParam(':image', $image, PDO::PARAM_STR);
            }
            if ($body != '') {
                $updateBlog->bindParam(':body', $body, PDO::PARAM_STR);
            }
            if ($status != '') {
                $updateBlog->bindParam(':status', $status, PDO::PARAM_STR);
            }
            $updateBlog->bindParam(':update_at', $update_at, PDO::PARAM_STR);


            $updateBlog->execute();
            $rowCount = $updateBlog->rowCount();
            if ($rowCount === 1) {
                $response = new Response();
                $response->setHttpStatusCode(200);
                $response->setSuccess(true);
                $response->toCache(true);
                $response->addMessage('Blog Update success.');
                $response->send();
                exit;
            }
        } else {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("Blog ID Missing");
            $response->send();
            exit();
        }
    } catch (BlogException $ex) {
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
    $response->setHttpStatusCode(404);
    $response->setSuccess(false);
    $response->addMessage("Endpoint not found");
    $response->send();
    exit;
}
