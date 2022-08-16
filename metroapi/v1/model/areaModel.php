<?php
class AreaException extends Exception
{
}
class Area
{
    private $id;
    private $deliveryArea;
    private $deliveryCharge;

    public function __construct($id, $deliveryArea, $deliveryCharge)
    {
        $this->setId($id);
        $this->setDeliveryArea($deliveryArea);
        $this->setDeliveryCharge($deliveryCharge);
    }
    function setId($id)
    {
        $this->id = $id;
    }
    function getId()
    {
        return $this->id;
    }
    function setDeliveryArea($deliveryArea)
    {
        $this->deliveryArea = $deliveryArea;
    }
    function getDeliveryArea()
    {
        return $this->deliveryArea;
    }
    function setDeliveryCharge($deliveryCharge)
    {
        $this->deliveryCharge = $deliveryCharge;
    }
    function getDeliveryCharge()
    {
        return $this->deliveryCharge;
    }

    public function returnAreaArray()
    {
        $area = array();
        $area['id'] = $this->getID();
        $area['deliveryArea'] = $this->getDeliveryArea();
        $area['deliveryCharge'] = $this->getDeliveryCharge();
        return $area;
    }
}
