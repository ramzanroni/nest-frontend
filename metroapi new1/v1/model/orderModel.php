<?php
class OrderException extends Exception
{
}
class Order
{
    private $_orderno;
    private $_orddate;
    private $_so_status;

    public function __construct($orderno, $orddate, $so_status)
    {
        $this->setOrderno($orderno);
        $this->setOrddate($orddate);
        $this->setSo_status($so_status);
    }



    public function getOrderno()
    {
        return $this->_orderno;
    }
    public function getOrddate()
    {
        return $this->_orddate;
    }
    public function getSoStatus()
    {
        return $this->_so_status;
    }


    public function getOrderlineno()
    {
        return $this->_orderlineno;
    }
    public function getStkcode()
    {
        return $this->_stkcode;
    }
    public function get()
    {
        return $this->_so_status;
    }
    public function getUnitprice()
    {
        return $this->_unitprice;
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



    public function setOrderno($orderno)
    {
        if (($orderno != null) && (!is_numeric($orderno) || $this->_orderno !== null)) {
            throw new OrderException("Order ID Error");
        }
        $this->_orderno = $orderno;
    }

    public function setOrddate($orddate)
    {
        // if ($orddate == null || $orddate === '') {
        //     throw new OrderException("Order Date Error");
        // }
        $this->_orddate = $orddate;
    }
    public function setSo_status($so_status)
    {
        // if ($so_status === null) {
        //     throw new OrderException("so_status error");
        // }
        $this->_so_status = $so_status;
    }


    public function returnOrderArray()
    {
        $order = array();
        $order['orderno'] = $this->getOrderno();
        $order['orddate'] = $this->getOrddate();
        $order['so_status'] = $this->getSoStatus();
        return $order;
    }
}

class CreateOrder
{

    private $_name;
    private $_address;
    private $_area;
    private $_phone;
    private $_town;
    private $_additionalPhone;
    private $_additionalInfo;
    private $_token;
    private $_paymentMethod;

    public function __construct($name, $address, $area, $phone, $town, $additionalPhone, $additionalInfo, $token, $paymentMethod)
    {
        $this->setName($name);
        $this->setAddress($address);
        $this->setArea($area);
        $this->setPhone($phone);
        $this->setTown($town);
        $this->setAdditionalPhone($additionalPhone);
        $this->setAdditionalInfo($additionalInfo);
        $this->setToken($token);
        $this->setPaymentMethod($paymentMethod);
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getAddress()
    {
        return $this->_address;
    }

    public function getArea()
    {
        return $this->_area;
    }

    public function getPhone()
    {
        return $this->_phone;
    }

    public function getTown()
    {
        return $this->_town;
    }

    public function getAdditionalPhone()
    {
        return $this->_additionalPhone;
    }

    public function getAdditionalInfo()
    {
        return $this->_additionalInfo;
    }

    public function getToken()
    {
        return $this->_token;
    }

    public function getPaymentMethod()
    {
        return $this->_paymentMethod;
    }

    // public function getItemInfo(): array
    // {
    //     return $this->itemInfo;
    // }

    public function setName($name)
    {
        if (!is_string($name) || $name === '' || $name === null) {
            throw new OrderException("Name can not be null.");
        }
        $this->_name = $name;
    }

    public function setAddress($address)
    {
        if (!is_string($address) || $address === '' || $address === null) {
            throw new OrderException("Address can not be null.");
        }
        $this->_address = $address;
    }

    public function setArea($area)
    {
        if (!is_string($area) || $area === '' || $area === null) {
            throw new OrderException("Area can not be null.");
        }
        $this->_area = $area;
    }

    public function setPhone($phone)
    {
        if (!is_numeric($phone) || $phone === '' || $phone === null) {
            throw new OrderException("Phone number can not be null or text data .");
        }
        $this->_phone = $phone;
    }

    public function setTown($town)
    {
        if (!is_string($town) || $town === '' || $town === null) {
            throw new OrderException("Town can not be null or numeric or String value");
        }
        $this->_town = $town;
    }

    public function setAdditionalPhone($additionalPhone)
    {
        $this->_additionalPhone = $additionalPhone;
    }

    public function setAdditionalInfo($additionalInfo)
    {
        $this->_additionalInfo = $additionalInfo;
    }

    public function setToken($token)
    {
        if ($token === '' || $token === null) {
            throw new OrderException("Token can not be null.");
        }
        $this->_token = $token;
    }

    public function setPaymentMethod($paymentMethod)
    {

        if ($paymentMethod == '') {

            throw new OrderException("Payment method can not be null.");
        }
        $this->_paymentMethod = $paymentMethod;
    }
}
class OrderItem
{
    private $_productID;
    private $_unitPrice;
    private $_productQuantity;

    public function __construct($productId, $unitPrice, $productQuantity)
    {
        $this->setProductId($productId);
        $this->setUnitPrice($unitPrice);
        $this->setProductQuantity($productQuantity);
    }
    public function getProductId()
    {
        return $this->_productID;
    }

    public function getUnitPrice()
    {
        return $this->_unitPrice;
    }

    public function getProductQuantity()
    {
        return $this->_productQuantity;
    }


    public function setProductId($productId)
    {
        if (!is_numeric($productId) || $productId == '') {
            throw new OrderException("Product ID can not be null or String value");
        }
        $this->_productID = $productId;
    }

    public function setUnitPrice($unitPrice)
    {
        if (!is_numeric($unitPrice) || $unitPrice == '') {
            throw new OrderException("Unit Price ID can not be null or String value");
        }
        $this->_unitPrice = $unitPrice;
    }

    public function setProductQuantity($productQuantity)
    {
        if (!is_numeric($productQuantity) || $productQuantity == '') {
            throw new OrderException("Product Quantity Price ID can not be null or String value");
        }
        $this->_productQuantity = $productQuantity;
    }
}
