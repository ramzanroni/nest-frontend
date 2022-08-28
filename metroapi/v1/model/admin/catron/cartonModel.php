<?php
class CartonPackException extends Exception
{
}
class CartonPackage
{
    private $id;
    private $carton_status;
    private $total_qty;
    private $total_price;
    public function __construct($id, $carton_status, $total_qty, $total_price)
    {
        $this->setId($id);
        $this->setCarton_status($carton_status);
        $this->setTotal_qty($total_qty);
        $this->setTotal_price($total_price);
    }

    function setId($id)
    {
        $this->id = $id;
    }
    function getId()
    {
        return $this->id;
    }
    function setCarton_status($carton_status)
    {
        $this->carton_status = $carton_status;
    }
    function getCarton_status()
    {
        return $this->carton_status;
    }
    function setTotal_qty($total_qty)
    {
        $this->total_qty = $total_qty;
    }
    function getTotal_qty()
    {
        return $this->total_qty;
    }
    function setTotal_price($total_price)
    {
        $this->total_price = $total_price;
    }
    function getTotal_price()
    {
        return $this->total_price;
    }
    public function returnCartonArray()
    {
        $carton = array();
        $carton['id'] = $this->getID();
        $carton['carton_status'] = $this->getCarton_status();
        $carton['total_qty'] = $this->getTotal_qty();
        $carton['total_price'] = $this->getTotal_price();
        return $carton;
    }
}
class CartonItem
{
    private $_qty;
    private $_img;
    private $_stockid;
    private $_description;
    private $_webPrice;

    public function __construct($qty, $img, $stockid, $description, $webPrice)
    {
        $this->setQty($qty);
        $this->setImg($img);
        $this->setStockid($stockid);
        $this->setDescription($description);
        $this->setWebPrice($webPrice);
    }
    public function getQty()
    {
        return $this->_qty;
    }

    public function getImg()
    {
        return $this->_img;
    }

    public function getStockid()
    {
        return $this->_stockid;
    }
    public function getDescription()
    {
        return $this->_description;
    }
    public function getWebPrice()
    {
        return $this->_webPrice;
    }

    public function setQty($qty)
    {
        $this->_qty = $qty;
    }

    public function setImg($img)
    {
        $this->_img = $img;
    }

    public function setStockid($stockid)
    {
        $this->_stockid = $stockid;
    }

    public function setDescription($description)
    {
        $this->_description = $description;
    }
    public function setWebPrice($webPrice)
    {
        $this->_webPrice = $webPrice;
    }

    public function returnCartonItemArray()
    {
        $cartonItem = array();
        $cartonItem['qty'] = $this->getQty();
        $cartonItem['img'] = $this->getImg();
        $cartonItem['stockid'] = $this->getStockid();
        $cartonItem['description'] = $this->getDescription();
        $cartonItem['unitprice'] = $this->getWebPrice();
        return $cartonItem;
    }
}
