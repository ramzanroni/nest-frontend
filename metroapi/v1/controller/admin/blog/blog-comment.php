<?php
include_once('../../db.php');
include_once('../../../model/admin/blog/blogCommentModel.php');
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
        if (!isset($jsonData->reply)) {
            if (isset($jsonData->phone)) {
                $phone = $jsonData->phone;
                $findId = $readDB->prepare("SELECT * FROM `contact_master` WHERE `phone1`=:phone");
                $findId->bindParam(':phone', $phone, PDO::PARAM_STR);
                $findId->execute();
                $rowCount = $findId->rowCount();
                if ($rowCount != 0) {
                    $userIdFetch = $findId->fetch(PDO::FETCH_ASSOC);
                    $userId = $userIdFetch['id'];
                } else {
                    $response = new Response();
                    $response->setHttpStatusCode(404);
                    $response->setSuccess(false);
                    $response->addMessage("User phone number is not valid");
                    $response->send();
                    exit();
                }
            } elseif (isset($jsonData->user_id)) {
                $userId = $jsonData->user_id;
            }
            $commentData = new BlogComment('', $jsonData->blog_id, '', $jsonData->comment, $jsonData->ratings, $userId, '', 1, '', '', '');
            $blog_id = $commentData->getBlog_id();
            $comment = $commentData->getComment();
            $ratings = $commentData->getRatings();
            $user_id = $commentData->getUser_id();
            $status = $commentData->getStatus();

            // check block commend 
            $maxBlogCommentId = 1;
            $checkBlogComment = $readDB->prepare("SELECT MAX(`blog_comment_id`) AS max_blog_comment_id FROM `blogs_comments` WHERE `blog_id`=:blog_id
        ");
            $checkBlogComment->bindParam(':blog_id', $blog_id, PDO::PARAM_STR);
            $checkBlogComment->execute();
            $rowCount = $checkBlogComment->rowCount();
            if ($rowCount != 0) {
                $maxBlogCommentFetch = $checkBlogComment->fetch(PDO::FETCH_ASSOC);
                $maxBlogCommentId = $maxBlogCommentFetch['max_blog_comment_id'] + 1;
            }
            $parent = 0;
            // comment insert 
            $insertSql = $writeDB->prepare("INSERT INTO blogs_comments(blog_id,blog_comment_id, comment, ratings, user_id, status,parent) VALUES (:blog_id,:blog_comment_id, :comment,:ratings,:user_id,:status,:parent)");
            $insertSql->bindParam(':blog_id', $blog_id, PDO::PARAM_STR);
            $insertSql->bindParam(':blog_comment_id', $maxBlogCommentId, PDO::PARAM_STR);
            $insertSql->bindParam(':comment', $comment, PDO::PARAM_STR);
            $insertSql->bindParam(':ratings', $ratings, PDO::PARAM_STR);
            $insertSql->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $insertSql->bindParam(':status', $status, PDO::PARAM_STR);
            $insertSql->bindParam(':parent', $parent, PDO::PARAM_STR);
            $insertSql->execute();
            $rowCount = $insertSql->rowCount();
            if ($rowCount === 1) {
                $response = new Response();
                $response->setHttpStatusCode(200);
                $response->setSuccess(true);
                $response->toCache(true);
                $response->addMessage('Comment insert success.');
                $response->send();
                exit;
            }
        }
        if (isset($jsonData->reply) && $jsonData->reply != '' && $jsonData->id != '') {

            $comment = new BlogComment($jsonData->id, '', '', $jsonData->reply, '', $jsonData->user_id, '', '', '', '', '');
            $id = $comment->getId();
            $reply = $comment->getComment();
            $user_id = $comment->getUser_id();

            // getDataComment
            $findComment = $readDB->prepare('SELECT * FROM `blogs_comments` WHERE id=:id');
            $findComment->bindParam(':id', $id, PDO::PARAM_STR);
            $findComment->execute();
            $rowCount = $findComment->rowCount();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage('Comment not found..');
                $response->send();
                exit;
            } else {
                $commentRow = $findComment->fetch(PDO::FETCH_ASSOC);
                $blog_id = $commentRow['blog_id'];
                $blog_comment_id = $commentRow['blog_comment_id'];
                $ratings = $commentRow['ratings'];
                $status = $commentRow['status'];
                // check comment or reply 
                $checkParent = 0;
                $chekReply = $readDB->prepare('SELECT * FROM `blogs_comments` WHERE id=:id AND parent !=0');
                $chekReply->bindParam(':id', $id, PDO::PARAM_STR);
                $chekReply->execute();
                $rowCount = $chekReply->rowCount();
                if ($rowCount > 0) {
                    $response = new Response();
                    $response->setHttpStatusCode(404);
                    $response->setSuccess(false);
                    $response->addMessage("Comment id does not exist.");
                    $response->send();
                    exit();
                }
                // check reply 
                $checkReply = $readDB->prepare('SELECT * FROM `blogs_comments` WHERE blog_id=:blog_id AND blog_comment_id=:blog_comment_id AND parent=:parent');
                $checkReply->bindParam(':blog_id', $blog_id, PDO::PARAM_STR);
                $checkReply->bindParam(':blog_comment_id', $blog_comment_id, PDO::PARAM_STR);
                $checkReply->bindParam(':parent', $id, PDO::PARAM_STR);
                $checkReply->execute();
                $rowCount = $checkReply->rowCount();
                if ($rowCount > 0) {
                    $response = new Response();
                    $response->setHttpStatusCode(400);
                    $response->setSuccess(false);
                    $response->addMessage("Already have a comment.");
                    $response->send();
                    exit();
                }
                // insert reply 
                $addReply = $writeDB->prepare('INSERT INTO `blogs_comments`(`blog_id`, `blog_comment_id`, `comment`, `ratings`, `user_id`, `status`, `parent`) VALUES (:blog_id, :blog_comment_id, :comment, :ratings, :user_id, :status, :parent)');
                $addReply->bindParam(':blog_id', $blog_id, PDO::PARAM_STR);
                $addReply->bindParam(':blog_comment_id', $blog_comment_id, PDO::PARAM_STR);
                $addReply->bindParam(':comment', $reply, PDO::PARAM_STR);
                $addReply->bindParam(':ratings', $ratings, PDO::PARAM_STR);
                $addReply->bindParam(':user_id', $user_id, PDO::PARAM_STR);
                $addReply->bindParam(':status', $status, PDO::PARAM_STR);
                $addReply->bindParam(':parent', $id, PDO::PARAM_STR);
                $addReply->execute();
                $rowCount = $addReply->rowCount();
                if ($rowCount != 0) {
                    $response = new Response();
                    $response->setHttpStatusCode(200);
                    $response->setSuccess(true);
                    $response->toCache(true);
                    $response->addMessage("Reply added success.");
                    $response->send();
                    exit();
                }
            }
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
    if (array_key_exists('blog_id', $_GET) && array_key_exists('page', $_GET)) {
        $blog_id = $_GET['blog_id'];
        $page = $_GET['page'];
        $limit = 2;
        $start = ($page - 1) * $limit + 1;
        $end = ($page * $limit);
        $mainQuery = $readDB->prepare("SELECT blogs_comments.id AS id, blogs_comments.blog_id AS blog_id, blog.title AS title, blogs_comments.comment AS comment, blogs_comments.ratings AS ratings, blogs_comments.user_id AS user_id, blogs_comments.status AS status, blogs_comments.create_at AS create_at, blogs_comments.blog_comment_id AS blog_comment_id, blogs_comments.parent AS parent, contact_master.name AS name FROM `blogs_comments` INNER JOIN contact_master ON contact_master.id=blogs_comments.user_id INNER JOIN blog ON blog.id=blogs_comments.blog_id WHERE blogs_comments.blog_id=:blog_id AND blogs_comments.blog_comment_id>= :start AND blogs_comments.blog_comment_id<=:end ORDER BY blogs_comments.create_at ASC");
        $mainQuery->bindParam(':blog_id', $blog_id, PDO::PARAM_STR);
        $mainQuery->bindParam(':start', $start, PDO::PARAM_STR);
        $mainQuery->bindParam(':end', $end, PDO::PARAM_STR);
        $mainQuery->execute();
        $rowCount = $mainQuery->rowCount();
        if ($rowCount > 0) {
            $comment = array();
            while ($rowComment = $mainQuery->fetch(PDO::FETCH_ASSOC)) {
                $comments = new BlogComment($rowComment['id'], $rowComment['blog_id'], $rowComment['title'], $rowComment['comment'], $rowComment['ratings'], $rowComment['user_id'], $rowComment['name'], $rowComment['status'], $rowComment['create_at'], $rowComment['blog_comment_id'], $rowComment['parent']);
                $comment[] = $comments->returnBlogCommentArray();
            }
            $returnArray = array();
            $returnArray['rows_returned'] = $rowCount;
            $returnArray['comment'] = $comment;
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->toCache(true);
            $response->setSuccess(true);
            $response->setData($returnArray);
            $response->send();
            exit;
        } else {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("No Data Found");
            $response->send();
            exit();
        }
    } elseif (array_key_exists('blog_id', $_GET) && !array_key_exists('page', $_GET)) {
        $parent = 0;
        $blog_id = $_GET['blog_id'];
        $mainQuery = $readDB->prepare("SELECT blogs_comments.id AS id, blogs_comments.blog_id AS blog_id, blog.title AS title, blogs_comments.comment AS comment, blogs_comments.ratings AS ratings, blogs_comments.user_id AS user_id, blogs_comments.status AS status, blogs_comments.create_at AS create_at, blogs_comments.blog_comment_id AS blog_comment_id, blogs_comments.parent AS parent, contact_master.name AS name FROM `blogs_comments` INNER JOIN contact_master ON contact_master.id=blogs_comments.user_id INNER JOIN blog ON blog.id=blogs_comments.blog_id WHERE blogs_comments.parent=:parent AND blogs_comments.blog_id=:blog_id");
        $mainQuery->bindParam(':parent', $parent, PDO::PARAM_STR);
        $mainQuery->bindParam(':blog_id', $blog_id, PDO::PARAM_STR);
        $mainQuery->execute();
        $rowCount = $mainQuery->rowCount();
        if ($rowCount > 0) {
            $comment = array();
            while ($rowComment = $mainQuery->fetch(PDO::FETCH_ASSOC)) {
                $comments = new BlogComment($rowComment['id'], $rowComment['blog_id'], $rowComment['title'], $rowComment['comment'], $rowComment['ratings'], $rowComment['user_id'], $rowComment['name'], $rowComment['status'], $rowComment['create_at'], $rowComment['blog_comment_id'], $rowComment['parent']);
                $comment[] = $comments->returnBlogCommentArray();
            }
            $returnArray = array();
            $returnArray['rows_returned'] = $rowCount;
            $returnArray['comment'] = $comment;
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->toCache(true);
            $response->setSuccess(true);
            $response->setData($returnArray);
            $response->send();
            exit;
        } else {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("No Data Found");
            $response->send();
            exit();
        }
    } elseif (array_key_exists('parent', $_GET)) {
        $parent = $_GET['parent'];
        $mainQuery = $readDB->prepare("SELECT blogs_comments.id AS id, blogs_comments.blog_id AS blog_id, blog.title AS title, blogs_comments.comment AS comment, blogs_comments.ratings AS ratings, blogs_comments.user_id AS user_id, blogs_comments.status AS status, blogs_comments.create_at AS create_at, blogs_comments.blog_comment_id AS blog_comment_id, blogs_comments.parent AS parent, contact_master.name AS name FROM `blogs_comments` INNER JOIN contact_master ON contact_master.id=blogs_comments.user_id INNER JOIN blog ON blog.id=blogs_comments.blog_id WHERE blogs_comments.parent=:parent");
        $mainQuery->bindParam(':parent', $parent, PDO::PARAM_STR);
        $mainQuery->execute();
        $rowCount = $mainQuery->rowCount();
        if ($rowCount > 0) {
            $comment = array();
            while ($rowComment = $mainQuery->fetch(PDO::FETCH_ASSOC)) {
                $comments = new BlogComment($rowComment['id'], $rowComment['blog_id'], $rowComment['title'], $rowComment['comment'], $rowComment['ratings'], $rowComment['user_id'], $rowComment['name'], $rowComment['status'], $rowComment['create_at'], $rowComment['blog_comment_id'], $rowComment['parent']);
                $comment[] = $comments->returnBlogCommentArray();
            }
            $returnArray = array();
            $returnArray['rows_returned'] = $rowCount;
            $returnArray['comment'] = $comment;
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
            $response->toCache(false);
            $response->setSuccess(false);
            $response->addMessage("No data found");
            $response->send();
            exit;
        }
    } else {
        $parent = 0;
        $mainQuery = $readDB->prepare("SELECT blogs_comments.id AS id, blogs_comments.blog_id AS blog_id, blog.title AS title, blogs_comments.comment AS comment, blogs_comments.ratings AS ratings, blogs_comments.user_id AS user_id, blogs_comments.status AS status, blogs_comments.create_at AS create_at, blogs_comments.blog_comment_id AS blog_comment_id, blogs_comments.parent AS parent, contact_master.name AS name FROM `blogs_comments` INNER JOIN contact_master ON contact_master.id=blogs_comments.user_id INNER JOIN blog ON blog.id=blogs_comments.blog_id WHERE blogs_comments.parent=0 ORDER BY blogs_comments.create_at ASC");
        $mainQuery->bindParam(':parent', $parent, PDO::PARAM_STR);
        $mainQuery->execute();
        $rowCount = $mainQuery->rowCount();
        if ($rowCount > 0) {
            $comment = array();
            while ($rowComment = $mainQuery->fetch(PDO::FETCH_ASSOC)) {
                $comments = new BlogComment($rowComment['id'], $rowComment['blog_id'], $rowComment['title'], $rowComment['comment'], $rowComment['ratings'], $rowComment['user_id'], $rowComment['name'], $rowComment['status'], $rowComment['create_at'], $rowComment['blog_comment_id'], $rowComment['parent']);
                $comment[] = $comments->returnBlogCommentArray();
            }
            $returnArray = array();
            $returnArray['rows_returned'] = $rowCount;
            $returnArray['comment'] = $comment;
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->toCache(true);
            $response->setSuccess(true);
            $response->setData($returnArray);
            $response->send();
            exit;
        }
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
        if ($jsonData->type === 'reply') {
            $id = $jsonData->id;
            $reply = $jsonData->reply;
            $user_id = $jsonData->user_id;
            // check reply exist
            $checkReply = $readDB->prepare('SELECT * FROM `blogs_comments` WHERE `parent`=:parent');
            $checkReply->bindParam(':parent', $id, PDO::PARAM_STR);
            $checkReply->execute();
            $rowCount = $checkReply->rowCount();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("Reply not found");
                $response->send();
                exit();
            } else {
                $updateComment = $writeDB->prepare('UPDATE `blogs_comments` SET `comment`=:comment, `user_id`=:user_id WHERE `parent`=:parent');
                $updateComment->bindParam(':comment', $reply, PDO::PARAM_STR);
                $updateComment->bindParam(':user_id', $user_id, PDO::PARAM_STR);
                $updateComment->bindParam(':parent', $id, PDO::PARAM_STR);
                $updateComment->execute();
                $rowCount = $updateComment->rowCount();
                if ($rowCount == 1) {
                    $response = new Response();
                    $response->setHttpStatusCode(200);
                    $response->setSuccess(true);
                    $response->addMessage("Reply edit success");
                    $response->send();
                    exit();
                }
            }
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
} else {
    $response = new Response();
    $response->setHttpStatusCode(404);
    $response->setSuccess(false);
    $response->addMessage("Endpoint not found");
    $response->send();
    exit;
}
