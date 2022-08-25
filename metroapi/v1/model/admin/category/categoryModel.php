<?php

class CategoryException extends Exception
{
}
class Categories
{
    private $groupid;
    private $groupname;
    private $parent;
    private $image;
    private $web;

    public function __construct($groupid, $groupname, $parent, $image, $web)
    {
        $this->setGroupid($groupid);
        $this->setGroupname($groupname);
        $this->setParent($parent);
        $this->setImage($image);
        $this->setWeb($web);
    }


    function setGroupid($groupid)
    {
        $this->groupid = $groupid;
    }
    function getGroupid()
    {
        return $this->groupid;
    }
    function setGroupname($groupname)
    {
        if ($groupname == '' || $groupname == null) {
            throw new CategoryException("Category name cannot be null.");
        }
        $this->groupname = $groupname;
    }
    function getGroupname()
    {
        return $this->groupname;
    }
    function setParent($parent)
    {
        $this->parent = $parent;
    }
    function getParent()
    {
        return $this->parent;
    }
    function setImage($image)
    {
        $this->image = $image;
    }
    function getImage()
    {
        return $this->image;
    }
    function setWeb($web)
    {

        $this->web = $web;
    }
    function getWeb()
    {
        return $this->web;
    }
    public function returnCategoryArray()
    {
        $category = array();
        $category['groupid'] = $this->getGroupid();
        $category['groupname'] = $this->getGroupname();
        $category['parent'] = $this->getParent();
        $category['image'] = $this->getImage();
        $category['web'] = $this->getWeb();
        return $category;
    }
}
