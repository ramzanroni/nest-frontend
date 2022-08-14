<?php
class CategoryException extends Exception
{
}
class Category
{
    private $_categoryID;
    private $_categoryName;
    private $_categoryImg;
    private $_item;
    private $_parent;

    public function __construct($categoryID, $categoryName, $categoryImg, $item, $parent)
    {
        $this->setCategoryID($categoryID);
        $this->setCategoryName($categoryName);
        $this->setCategoryImg($categoryImg);
        $this->setItem($item);
        $this->setParent($parent);
    }


    public function getCategoryID()
    {
        return $this->_categoryID;
    }
    public function getCategoryName()
    {
        return $this->_categoryName;
    }
    public function getCategoryImg()
    {
        return $this->_categoryImg;
    }
    public function getItem()
    {
        return $this->_item;
    }
    public function getParent()
    {
        return $this->_parent;
    }

    public function setCategoryID($categoryID)
    {
        if (($categoryID != null) && (!is_numeric($categoryID) || $categoryID <= 0 || $this->_categoryID !== null)) {
            throw new CategoryException("Category ID Error");
        }
        $this->_categoryID = $categoryID;
    }

    public function setCategoryName($categoryName)
    {
        if (strlen($categoryName) == 0 || strlen($categoryName) > 255) {
            throw new CategoryException("Category Name Error");
        }
        $this->_categoryName = $categoryName;
    }
    public function setCategoryImg($categoryImg)
    {
        // if (($categoryImg !== null) && (strlen($categoryImg) == 0 || strlen($categoryImg) > 16777215)) {
        //     throw new CategoryException("Category Image error");
        // }
        $this->_categoryImg = $categoryImg;
    }
    public function setItem($item)
    {
        // if ($item == null) {
        //     throw new CategoryException("Item Number not null");
        // }
        $this->_item = $item;
    }
    public function setParent($parent)
    {
        $this->_parent = $parent;
    }


    public function returnCategoryArray()
    {
        $category = array();
        $category['categoryID'] = $this->getCategoryID();
        $category['categoryName'] = $this->getCategoryName();
        $category['categoryImg'] = $this->getCategoryImg();
        $category['item'] = $this->getItem();
        $category['parent'] = $this->getParent();
        return $category;
    }
}
