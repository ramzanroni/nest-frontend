<?php
class BasicAccountException extends Exception
{
}
class BasicAccountAdd
{
    private $accountcode;
    private $accountname;
    public function __construct($accountcode, $accountname)
    {
        $this->setAccountcode($accountcode);
        $this->setAccountname($accountname);
    }

    function setAccountcode($accountcode)
    {
        $this->accountcode = $accountcode;
    }
    function getAccountcode()
    {
        return $this->accountcode;
    }
    function setAccountname($accountname)
    {
        $this->accountname = $accountname;
    }
    function getAccountname()
    {
        return $this->accountname;
    }
    public function returnBasicAccountArray()
    {
        $basicAccount = array();
        $basicAccount['id'] = $this->getAccountcode();
        $basicAccount['accountname'] = $this->getAccountname();
        return $basicAccount;
    }
}
