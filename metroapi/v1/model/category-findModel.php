<?php
class CategoryException extends Exception
{
}

class FindCategory
{
    private $groupid;
    private $groupname;
    private $parent;
    private $image;
    private $web;

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
}
