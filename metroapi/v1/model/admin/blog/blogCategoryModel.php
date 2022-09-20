<?php
class BlogCategoryException extends Exception
{
}
class BlogCategory
{
    private $id;
    private $category_name;
    private $status;

    public function __construct($id, $category_name, $status)
    {
        $this->setId($id);
        $this->setCategory_name($category_name);
        $this->setStatus($status);
    }

    function setId($id)
    {
        $this->id = $id;
    }
    function getId()
    {
        return $this->id;
    }
    function setCategory_name($category_name)
    {
        $this->category_name = $category_name;
    }
    function getCategory_name()
    {
        return $this->category_name;
    }
    function setStatus($status)
    {
        $this->status = $status;
    }
    function getStatus()
    {
        return $this->status;
    }
    public function returnBlogCategoryArray()
    {
        $blogCat = array();
        $blogCat['id'] = $this->getId();
        $blogCat['category_name'] = $this->getCategory_name();
        $blogCat['status'] = $this->getStatus();
        return $blogCat;
    }
}
