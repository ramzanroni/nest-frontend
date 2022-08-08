<?php
class OrderdetailsException extends Exception
{
}
class Item
{
    private $_orderlineno;
    private $_orderno;
    private $_stkcode;
    private $_unitprice;
    private $_quantity;
    private $_description;
    private $_stockid;
    private $_img;
    private $_status;
    // private $_userInfo;

    public function __construct($orderlineno, $orderno, $stkcode, $unitprice, $quantity, $description, $stockid, $img, $status)
    {
        $this->_orderlineno = $orderlineno;
        $this->_orderno = $orderno;
        $this->_stkcode = $stkcode;
        $this->_unitprice = $unitprice;
        $this->_quantity = $quantity;
        $this->_description = $description;
        $this->_stockid = $stockid;
        $this->_img = $img;
        $this->_status = $status;
        // $this->_userInfo = $userInfo;
    }

    public function getOrderlineno()
    {
        return $this->_orderlineno;
    }

    public function getOrderno()
    {
        return $this->_orderno;
    }

    public function getStkcode()
    {
        return $this->_stkcode;
    }

    public function getUnitprice()
    {
        return $this->_unitprice;
    }

    public function getQuantity()
    {
        return $this->_quantity;
    }

    public function getDescription()
    {
        return $this->_description;
    }

    public function getStockid()
    {
        return $this->_stockid;
    }

    public function getImg()
    {
        return $this->_img;
    }

    public function getStatus()
    {
        return $this->_status;
    }
    // public function getUserInfo()
    // {
    //     return $this->_userInfo;
    // }

    public function setOrderlineno($orderlineno)
    {
        if ($orderlineno == null || $orderlineno === '' || !is_numeric($orderlineno)) {
            throw new OrderdetailsException("Order line number can be null.");
        }
        $this->_orderlineno = $orderlineno;
        return $this;
    }

    public function setOrderno($orderno)
    {
        if ($orderno == null || $orderno === '' || !is_numeric($orderno)) {
            throw new OrderdetailsException("Order No number can be null.");
        }
        $this->_orderno = $orderno;
        return $this;
    }

    public function setStkcode($stkcode)
    {
        if ($stkcode == null || $stkcode === '' || !is_numeric($stkcode)) {
            throw new OrderdetailsException("STK Code number can be null.");
        }
        $this->_stkcode = $stkcode;
        return $this;
    }

    public function setUnitprice($unitprice)
    {
        if ($unitprice === '' || !is_numeric($unitprice)) {
            throw new OrderdetailsException("Unit price can not be a string");
        }
        $this->_unitprice = $unitprice;
        return $this;
    }

    public function setQuantity($quantity)
    {
        if ($quantity === '' || !is_numeric($quantity)) {
            throw new OrderdetailsException("Item Quantity can not be a string");
        }
        $this->_quantity = $quantity;
        return $this;
    }

    public function setDescription($description)
    {
        $this->_description = $description;
        return $this;
    }

    public function setStockid($stockid)
    {
        if ($stockid === '' || !is_numeric($stockid)) {
            throw new OrderdetailsException("stock Id can not be a string");
        }
        $this->_stockid = $stockid;
        return $this;
    }

    public function setImg($img)
    {
        $this->_img = $img;
        return $this;
    }

    public function setStatus($status)
    {
        if ($status === '' || !is_numeric($status)) {
            throw new OrderdetailsException("Status can not be a string");
        }
        $this->_status = $status;
        return $this;
    }
    // public function setUserInfo($userInfo)
    // {
    //     return $this->_userInfo = $userInfo;
    // }

    public function returnOrderDetailsArray()
    {
        $details = array();
        $details['orderlineno'] = $this->getOrderlineno();
        $details['orderno'] = $this->getOrderno();
        $details['stkcode'] = $this->getStkcode();
        $details['unitprice'] = $this->getUnitprice();
        $details['quantity'] = $this->getQuantity();
        $details['description'] = $this->getDescription();
        $details['stockid'] = $this->getStockid();
        $details['img'] = $this->getImg();
        $details['status'] = $this->getStatus();
        // $details['userInfo'] = $this->getUserInfo();
        return $details;
    }
}

class OrderDetails
{
    private $_orderinfo;
    private $_userinfo;
    public function __construct($orderinfo, $userinfo)
    {
        $this->_orderinfo = $orderinfo;
        $this->_userinfo = $userinfo;
    }
    public function getUserInfo()
    {
        return $this->_userinfo;
    }
    public function getorderInfo()
    {
        return $this->_orderinfo;
    }
    public function setUserInfo($userInfo)
    {
        return $this->_userInfo = $userInfo;
    }
    public function setOrderInfo($orderInfo)
    {
        return $this->_userinfo = $orderInfo;
    }
    public function returnDetailsArray()
    {
        $info = array();
        $info['item'] = $this->getorderInfo();
        $info['info'] = $this->getUserInfo();
        return $info;
    }
}
