<?php
class TaskException extends Exception
{
}
class Task
{
    private $_id;
    private $_title;
    private $_description;
    private $_deadline;
    private $_completed;

    public function __construct($id, $title, $description, $deadline, $completed)
    {
        $this->setID($id);
        $this->setTitle($title);
        $this->setDescription($description);
        $this->setDeadline($deadline);
        $this->setCompleted($completed);
    }


    public function getID()
    {
        return $this->_id;
    }
    public function getTitle()
    {
        return $this->_title;
    }
    public function getDescription()
    {
        return $this->_description;
    }
    public function getDeadline()
    {
        return $this->_deadline;
    }
    public function getCompleted()
    {
        return $this->_completed;
    }

    public function setID($id)
    {
        if (($id != null) && (!is_numeric($id) || $id <= 0 || $this->_id !== null)) {
            throw new TaskException("Task ID Error");
        }
        $this->_id = $id;
    }

    public function setTitle($title)
    {
        if (strlen($title) == 0 || strlen($title) > 255) {
            throw new TaskException("Task Title Error");
        }
        $this->_title = $title;
    }
    public function setDescription($description)
    {
        if (($description !== null) && (strlen($description) == 0 || strlen($description) > 16777215)) {
            throw new TaskException("Task description error");
        }
        $this->_description = $description;
    }
    public function setDeadline($deadline)
    {
        if ($deadline == null) {
            throw new TaskException("Task deadline date or time");
        }
        $this->_deadline = $deadline;
    }

    public function setCompleted($completed)
    {
        if (strtoupper($completed) !== "Y" && strtoupper($completed) !== "N") {
            throw new TaskException("Task Completed must be Y or N");
        }
        $this->_completed = $completed;
    }

    public function returnTasksArray()
    {
        $task = array();
        $task['id'] = $this->getID();
        $task['title'] = $this->getTitle();
        $task['description'] = $this->getDescription();
        $task['deadline'] = $this->getDeadline();
        $task['completed'] = $this->getCompleted();
        return $task;
    }
    public function returnTasksArray2()
    {
        $task = array();
        $task['id'] = $this->getID();
        $task['title2'] = $this->getTitle();
        $task['description2'] = $this->getDescription();
        $task['deadline2'] = $this->getDeadline();
        $task['completed2'] = $this->getCompleted();
        return $task;
    }
}
