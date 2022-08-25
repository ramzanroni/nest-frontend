<?php
class StatusException extends Exception
{
}
class OrderStatus
{
    private $orderno;
    private $so_status;
    public function __construct($orderno, $so_status)
    {
        $this->setOrderno($orderno);
        $this->setSo_status($so_status);
    }

    function setOrderno($orderno)
    {
        if ($orderno == '' || !is_numeric($orderno)) {
            throw new StatusException("Order number can not be null or string");
        }
        $this->orderno = $orderno;
    }
    function getOrderno()
    {
        return $this->orderno;
    }
    function setSo_status($so_status)
    {
        if ($so_status == '' || !is_numeric($so_status) || $so_status > 3) {
            throw new StatusException("Order status can not be null or string or more then 3 ");
        }
        $this->so_status = $so_status;
    }
    function getSo_status()
    {
        return $this->so_status;
    }
}
