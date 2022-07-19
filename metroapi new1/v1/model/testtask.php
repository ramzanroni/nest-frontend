<?php
require_once('task.php');

try {
    $task = new Task(1, "Title here", "Description here", "01/12/2022 12:00", "N");
    header('Content-type: application/json;charset=UTF-8');
    echo json_encode($task->returnTasksArray());
} catch (TaskException $ex) {
    echo "Error: " . $ex;
}
