<?php
class LoginException extends Exception
{
}

class UserLogin
{
    private $userid;
    private $password;
    public function __construct($userid, $password)
    {
        $this->setUserid($userid);
        $this->setPassword($password);
    }

    function setUserid($userid)
    {
        if ($userid == null) {
            throw new LogicException("User ID cannot be null.");
        }
        $this->userid = $userid;
    }
    function getUserid()
    {
        return $this->userid;
    }
    function setPassword($password)
    {
        if ($password == null) {
            throw new LogicException("User password cannot be null.");
        }
        $this->password = $password;
    }
    function getPassword()
    {
        return $this->password;
    }
}

class UserInfo
{
    private $id;
    private $userid;
    private $realname;
    private $phone;
    public function __construct($id, $userid, $realname, $phone)
    {
        $this->setId($id);
        $this->setUserid($userid);
        $this->setRealname($realname);
        $this->setPhone($phone);
    }
    function setId($id)
    {
        $this->id = $id;
    }
    function getId()
    {
        return $this->id;
    }
    function setUserid($userid)
    {
        $this->userid = $userid;
    }
    function getUserid()
    {
        return $this->userid;
    }
    function setRealname($realname)
    {
        $this->realname = $realname;
    }
    function getRealname()
    {
        return $this->realname;
    }
    function setPhone($phone)
    {
        $this->phone = $phone;
    }
    function getPhone()
    {
        return $this->phone;
    }
    public function returnLoggedInfoArray()
    {
        $userInfo = array();
        $userInfo['id'] = $this->getId();
        $userInfo['userid'] = $this->getUserid();
        $userInfo['realname'] = $this->getRealname();
        $userInfo['phone'] = $this->getPhone();
        return $userInfo;
    }
}
