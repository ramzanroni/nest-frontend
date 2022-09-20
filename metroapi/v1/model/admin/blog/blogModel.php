<?php
class BlogException extends Exception
{
}
class Blog
{
    private $id;
    private $category_id;
    private $category_name;
    private $title;
    private $image;
    private $body;
    private $create_by;
    private $created_at;
    private $update_at;
    private $status;

    public function __construct($id, $category_id, $category_name, $title, $image, $body, $create_by, $created_at, $update_at, $status)
    {
        $this->setId($id);
        $this->setCategory_id($category_id);
        $this->setCategory_name($category_name);
        $this->setTitle($title);
        $this->setImage($image);
        $this->setBody($body);
        $this->setCreate_by($create_by);
        $this->setCreated_at($created_at);
        $this->setUpdate_at($update_at);
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
    function setCategory_id($category_id)
    {
        // if ($category_id == '') {
        //     throw (new BlogException('Blog category can not be null'));
        // }
        $this->category_id = $category_id;
    }
    function getCategory_id()
    {
        return $this->category_id;
    }
    function setCategory_name($category_name)
    {
        $this->category_name = $category_name;
    }
    function getCategory_name()
    {
        return $this->category_name;
    }
    function setTitle($title)
    {
        // if ($title == '') {
        //     throw (new BlogException('Title can not be null'));
        // }
        $this->title = $title;
    }
    function getTitle()
    {
        return $this->title;
    }
    function setImage($image)
    {
        // if ($image == '') {
        //     throw (new BlogException('Image can not be null'));
        // }
        $this->image = $image;
    }
    function getImage()
    {
        return $this->image;
    }
    function setBody($body)
    {
        // if ($body == '') {
        //     throw (new BlogException('Body can not be null'));
        // }
        $this->body = $body;
    }
    function getBody()
    {
        return $this->body;
    }
    function setCreate_by($create_by)
    {
        $this->create_by = $create_by;
    }
    function getCreate_by()
    {
        return $this->create_by;
    }
    function setCreated_at($created_at)
    {
        $this->created_at = $created_at;
    }
    function getCreated_at()
    {
        return $this->created_at;
    }
    function setUpdate_at($update_at)
    {
        $this->update_at = $update_at;
    }
    function getUpdate_at()
    {
        return $this->update_at;
    }
    function setStatus($status)
    {
        $this->status = $status;
    }
    function getStatus()
    {
        return $this->status;
    }

    public function returnBlogArray()
    {
        $blog = array();
        $blog['id'] = $this->getId();
        $blog['category_id'] = $this->getCategory_id();
        $blog['category_name'] = $this->getCategory_name();
        $blog['title'] = $this->getTitle();
        // $blog['body'] = $this->getBody();
        $blog['image'] = $this->getImage();
        $blog['create_by'] = $this->getCreate_by();
        $blog['create_at'] = $this->getCreated_at();
        $blog['update_at'] = $this->getUpdate_at();
        $blog['status'] = $this->getStatus();
        return $blog;
    }
}
