<?php
class SmsException extends Exception
{
}
class SMS
{
    private $_phone;
    private $_token;

    public function __construct($phone, $token)
    {
        $this->setPhone($phone);
        $this->setToken($token);
    }
    public function getPhone()
    {
        return $this->_phone;
    }
    public function getToken()
    {
        return $this->_token;
    }
    public function setPhone($phone)
    {
        if (($phone != null) && (!is_numeric($phone) || $this->_phone !== null)) {
            throw new SmsException("Phone number error");
        }
        $this->_phone = $phone;
    }
    public function setToken($token)
    {
        $this->_token = $token;
    }


    public function returnSmsArray()
    {
        $sms = array();
        // $sms['otp'] = $this->getOtp();
        $sms['userPhone'] = $this->getPhone();
        $sms['userToken'] = $this->getToken();
        return $sms;
    }
}

class Register
{
    public $_newUserPhone;
    public $_name;
    public $_address;
    public $_newOtp;

    public function __construct($newUserPhone, $name, $address, $newOtp)
    {
        $this->setNewUserPhone($newUserPhone);
        $this->setName($name);
        $this->setAddress($address);
        $this->setNewOtp($newOtp);
    }

    public function getNewUserPhone()
    {
        return $this->_newUserPhone;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getAddress()
    {
        return $this->_address;
    }

    public function getNewOtp()
    {
        return $this->_newOtp;
    }

    public function setNewUserPhone($newUserPhone)
    {
        if (($newUserPhone != null) && (!is_numeric($newUserPhone) || $this->_newUserPhone !== null)) {
            throw new SmsException("Phone number error");
        }
        $this->_newUserPhone = $newUserPhone;
    }

    public function setName($name)
    {
        if ($name == '') {
            throw new SmsException("provide user name");
        }
        $this->_name = $name;
    }

    public function setAddress($address)
    {
        if ($address == '') {
            throw new SmsException("provide user address");
        }
        $this->_address = $address;
    }

    public function setNewOtp($newOtp)
    {
        if (!is_numeric($newOtp) || $newOtp == '') {
            throw new SmsException("OTP code can not be null or string.");
        }
        $this->_newOtp = $newOtp;
    }
}
