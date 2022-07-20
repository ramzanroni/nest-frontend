<?php
class CartonException extends Exception
{
}
class Carton
{
    private $_id;
    private $_carton_status;
    private $_date_packed;
    private $_date_shiped;
    private $_date_delivered;

    public function __construct($id, $carton_status, $date_packed, $date_shiped, $date_delivered)
    {
        $this->setId($id);
        $this->setCartonStatus($carton_status);
        $this->setDatePacked($date_packed);
        $this->setDateShiped($date_shiped);
        $this->setDateDelivered($date_delivered);
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getCartonStatus()
    {
        return $this->_carton_status;
    }

    public function getDatePacked()
    {
        return $this->_date_packed;
    }

    public function getDateShiped()
    {
        return $this->_date_shiped;
    }

    public function getDateDelivered()
    {
        return $this->_date_delivered;
    }



    public function setId($id)
    {
        $this->_id = $id;
    }

    public function setCartonStatus($carton_status)
    {
        $this->_carton_status = $carton_status;
    }

    public function setDatePacked($date_packed)
    {
        $this->_date_packed = $date_packed;
    }

    public function setDateShiped($date_shiped)
    {
        $this->_date_shiped = $date_shiped;
    }

    public function setDateDelivered($date_delivered)
    {
        $this->_date_delivered = $date_delivered;
    }

    public function returnCartonArray()
    {
        $carton = array();
        $carton['id'] = $this->getID();
        $carton['carton_status'] = $this->getCartonStatus();
        $carton['date_packed'] = $this->getDatePacked();
        $carton['date_shiped'] = $this->getDatePacked();
        $carton['date_delivered'] = $this->getDateDelivered();
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
