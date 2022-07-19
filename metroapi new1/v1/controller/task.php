<?php

use LDAP\Result;

include_once('db.php');
include_once('../model/task.php');
include_once('../model/response.php');
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
    exit();
}

if (array_key_exists("taskid", $_GET)) {
    $taskid = $_GET['taskid'];
    if ($taskid == '' || !is_numeric($taskid)) {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        $response->addMessage("Task ID cannot be blank its must be numeric");
        $response->send();
        exit();
    }
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        try {
            $query = $readDB->prepare('SELECT id, title, description, deadline, complete FROM tbltasks WHERE id=:taskid');
            $query->bindParam(':taskid', $taskid, PDO::PARAM_INT);
            $query->execute();
            $rowCount = $query->rowCount();
            $taskArray = array();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("Task ID not found");
                $response->send();
                exit();
            }
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $task = new Task($row['id'], $row['title'], $row['description'], $row['deadline'], $row['complete']);
                $taskArray[] = $task->returnTasksArray();
            }
            $returnData = array();
            $returnData['rows_returned'] = $rowCount;
            $returnData['task'] = $taskArray;
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->setData($returnData);
            $response->send();
            exit;
        } catch (TaskException $ex) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
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
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        try {
            $query = $writeDB->prepare('DELETE FROM `tbltasks` WHERE id=:taskid');
            $query->bindParam(':taskid', $taskid, PDO::PARAM_INT);
            $query->execute();
            $rowCount = $query->rowCount();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage('Task not found');
                $response->send();
                exit();
            }
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->addMessage("Task delete Success.");
            $response->send();
            exit();
        } catch (PDOException $ex) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("Failed to delete.");
            $response->send();
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
        # code...
    } else {
        $response = new Response();
        $response->setHttpStatusCode(405);
        $response->setSuccess(false);
        $response->addMessage("Request method not allowed");
        $response->send();
        exit();
    }
} elseif (array_key_exists('completed', $_GET)) {
    $completed = $_GET['completed'];
    if ($completed !== "Y" && $completed !== "N") {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        $response->addMessage("Completed filter must be Y or N");
        $response->send();
        exit();
    }
    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        try {
            $query = $readDB->prepare('SELECT id, title, description, deadline, complete FROM tbltasks WHERE complete=:complete');
            $query->bindParam(':complete', $completed, PDO::PARAM_INT);
            $query->execute();
            $rowCount = $query->rowCount();
            $taskArray = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $task = new Task($row['id'], $row['title'], $row['description'], $row['deadline'], $row['complete']);
                $taskArray[] = $task->returnTasksArray();
            }
            $returnData = array();
            $returnData['rows_returned'] = $rowCount;
            $returnData['data'] = $taskArray;
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->setData($returnData);
            $response->send();
            exit();
        } catch (TaskException $ex) {
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
} elseif (empty($_GET)) {
    if ($_SERVER['REQUEST_METHOD'] === "GET") {
        try {
            $query = $readDB->prepare('SELECT id, title, description, deadline, complete FROM tbltasks');
            $query->execute();
            $rowCount = $query->rowCount();
            $taskArray = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $task = new Task($row['id'], $row['title'], $row['description'], $row['deadline'], $row['complete']);
                $taskArray[] = $task->returnTasksArray();
            }
            $returnData = array();
            $returnData['rows_retured'] = $rowCount;
            $returnData['tasks'] = $taskArray;
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->setData($returnData);
            $response->send();
            exit();
        } catch (TaskException $ex) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
            exit();
        } catch (PDOException $ex) {
            error_log("Database query error - " . $ex, 0);
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("Failed to get tasks.");
            $response->send();
            exit();
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === "POST") {
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
            if (!isset($jsonData->title) || !isset($jsonData->complete)) {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                (!isset($jsonData->title) ? $response->addMessage("Title field must be mandatory field.") : false);
                (!isset($jsonData->complete) ? $response->addMessage("Complete field must be mandatory field.") : false);
                $response->send();
                exit();
            }
            $newTask = new Task(null, $jsonData->title, (isset($jsonData->description) ? $jsonData->description : null), (isset($jsonData->deadline) ? $jsonData->deadline : null), $jsonData->complete);
            $title = $newTask->getTitle();
            $description = $newTask->getDescription();
            $deadline = $newTask->getDeadline();
            $complete = $newTask->getCompleted();
            $query = $writeDB->prepare('INSERT INTO tbltasks(title, description, deadline, complete) VALUES (:title, :description, :deadline,:complete)');
            $query->bindParam(':title', $title, PDO::PARAM_STR);
            $query->bindParam(':description', $description, PDO::PARAM_STR);
            $query->bindParam(':deadline', $deadline, PDO::PARAM_STR);
            $query->bindParam(':complete', $complete, PDO::PARAM_STR);
            $query->execute();
            $rowCount = $query->rowCount();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Failed to create Task");
                $response->send();
                exit();
            }
        } catch (TaskException $ex) {
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
} else {
    $response = new Response();
    $response->setHttpStatusCode(404);
    $response->setSuccess(false);
    $response->addMessage('Endpoint not found');
    $response->send();
    exit();
}
