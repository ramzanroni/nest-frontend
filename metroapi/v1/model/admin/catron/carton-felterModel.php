<?php
class CartonFilterException extends Exception
{
}
class CartonFilter
{
    private $cartonId;
    private $customerName;
    private $orderNo;
    private $orderStatus;
    public function __construct($cartonId, $customerName, $orderNo, $orderStatus)
    {
        $this->setCartonId($cartonId);
        $this->setCustomerName($customerName);
        $this->setOrderNo($orderNo);
        $this->setOrderStatus($orderStatus);
    }

    function setCartonId($cartonId)
    {
        $this->cartonId = $cartonId;
    }
    function getCartonId()
    {
        return $this->cartonId;
    }
    function setCustomerName($customerName)
    {
        $this->customerName = $customerName;
    }
    function getCustomerName()
    {
        return $this->customerName;
    }
    function setOrderNo($orderNo)
    {
        $this->orderNo = $orderNo;
    }
    function getOrderNo()
    {
        return $this->orderNo;
    }
    function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;
    }
    function getOrderStatus()
    {
        return $this->orderStatus;
    }
    public function returnCartonFilterArray()
    {
        $carton = array();
        $carton['cartonId'] = $this->getCartonId();
        $carton['customerName'] = $this->getCustomerName();
        $carton['orderNo'] = $this->getOrderNo();
        $carton['orderStatus'] = $this->getOrderStatus();
        return $carton;
    }
}
