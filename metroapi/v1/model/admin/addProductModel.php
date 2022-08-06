<?php
class ProductException extends Exception
{
}
class AddProduct
{

    public function __construct($stockid, $code, $categoryid, $description, $longdescription, $units, $groupid, $webprice, $img)
    {
        $this->setStockid($stockid);
        $this->setCode($code);
        $this->setCategoryid($categoryid);
        $this->setDescription($description);
        $this->setLongdescription($longdescription);
        $this->setUnits($units);
        $this->setGroupid($groupid);
        $this->setWebprice($webprice);
        $this->setImg($img);
    }


    private $stockid;
    private $code;
    private $categoryid;
    private $description;
    private $longdescription;
    private $units;
    private $groupid;
    private $webprice;
    private $img;

    function setStockid($stockid)
    {
        $this->stockid = $stockid;
    }
    function getStockid()
    {
        return $this->stockid;
    }
    function setCode($code)
    {
        $this->code = $code;
    }
    function getCode()
    {
        return $this->code;
    }
    function setCategoryid($categoryid)
    {
        $this->categoryid = $categoryid;
    }
    function getCategoryid()
    {
        return $this->categoryid;
    }
    function setDescription($description)
    {
        $this->description = $description;
    }
    function getDescription()
    {
        return $this->description;
    }
    function setLongdescription($longdescription)
    {
        $this->longdescription = $longdescription;
    }
    function getLongdescription()
    {
        return $this->longdescription;
    }
    function setUnits($units)
    {
        $this->units = $units;
    }
    function getUnits()
    {
        return $this->units;
    }
    function setGroupid($groupid)
    {
        $this->groupid = $groupid;
    }
    function getGroupid()
    {
        return $this->groupid;
    }
    function setWebprice($webprice)
    {
        $this->webprice = $webprice;
    }
    function getWebprice()
    {
        return $this->webprice;
    }
    function setImg($img)
    {
        $this->img = $img;
    }
    function getImg()
    {
        return $this->img;
    }
}
