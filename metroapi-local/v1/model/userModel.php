<?php
class UserException extends Exception
{
}
class User
{
    private $_id;
    private $_userid;
    private $_fullName;
    private $_email;
    private $_phone;
    private $_address;
    private $_token;
    private $_activeNow;
    private $_customerid;

    public function __construct($id, $userid, $fullName, $email, $phone, $address, $token, $activeNow, $customerid)
    {
        $this->setId($id);
        $this->setUserid($userid);
        $this->setFullName($fullName);
        $this->setEmail($email);
        $this->setPhone($phone);
        $this->setAddress($address);
        $this->setToken($token);
        $this->setActiveNow($activeNow);
        $this->setCustomerid($customerid);
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getUserid()
    {
        return $this->_userid;
    }

    public function getFullName()
    {
        return $this->_fullName;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function getPhone()
    {
        return $this->_phone;
    }

    public function getAddress()
    {
        return $this->_address;
    }

    public function getToken()
    {
        return $this->_token;
    }

    public function getActiveNow()
    {
        return $this->_activeNow;
    }

    public function getCustomerid()
    {
        return $this->_customerid;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function setUserid($userid)
    {
        $this->_userid = $userid;
    }

    public function setFullName($fullName)
    {
        $this->_fullName = $fullName;
    }

    public function setEmail($email)
    {
        $this->_email = $email;
    }

    public function setPhone($phone)
    {
        if ($phone == '' || $phone == null) {
            throw new UserException("Phone number can not be null or string.");
        }
        $this->_phone = $phone;
    }

    public function setAddress($address)
    {
        $this->_address = $address;
    }

    public function setToken($token)
    {
        $this->_token = $token;
    }

    public function setActiveNow($activeNow)
    {
        $this->_activeNow = $activeNow;
    }

    public function setCustomerid($customerid)
    {
        $this->_customerid = $customerid;
    }
    public function returnUsersArray()
    {
        $user = array();
        $user['id'] = $this->getID();
        $user['userid'] = $this->getUserid();
        $user['fullName'] = $this->getFullName();
        $user['email'] = $this->getEmail();
        $user['phone'] = $this->getPhone();
        $user['address'] = $this->getAddress();
        $user['token'] = $this->getToken();
        $user['active_now'] = $this->getActiveNow();
        $user['customerid'] = $this->getCustomerid();
        return $user;
    }
}
