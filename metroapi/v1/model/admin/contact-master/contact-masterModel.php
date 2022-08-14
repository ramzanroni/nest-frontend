<?php
class ContactException extends Exception
{
}
class ContactMaster
{
    public function __construct($id, $code, $name, $address1, $address2, $address3, $address4, $phone1, $email, $bid, $picture, $created_by, $updated_by, $updated_at)
    {
        $this->setId($id);
        $this->setCode($code);
        $this->setName($name);
        $this->setAddress1($address1);
        $this->setAddress2($address2);
        $this->setAddress3($address3);
        $this->setAddress4($address4);
        $this->setPhone1($phone1);
        $this->setEmail($email);
        $this->setBid($bid);
        $this->setPicture($picture);
        $this->setCreated_by($created_by);
        $this->setUpdated_by($updated_by);
        $this->setUpdated_at($updated_at);
    }
    private $id;
    private $code;
    private $name;
    private $address1;
    private $address2;
    private $address3;
    private $address4;
    private $phone1;
    private $email;
    private $bid;
    private $picture;
    private $created_by;
    private $updated_by;
    private $updated_at;

    function setId($id)
    {
        $this->id = $id;
    }
    function getId()
    {
        return $this->id;
    }
    function setCode($code)
    {
        $this->code = $code;
    }
    function getCode()
    {
        return $this->code;
    }
    function setName($name)
    {
        $this->name = $name;
    }
    function getName()
    {
        return $this->name;
    }
    function setAddress1($address1)
    {
        $this->address1 = $address1;
    }
    function getAddress1()
    {
        return $this->address1;
    }
    function setAddress2($address2)
    {
        $this->address2 = $address2;
    }
    function getAddress2()
    {
        return $this->address2;
    }
    function setAddress3($address3)
    {
        $this->address3 = $address3;
    }
    function getAddress3()
    {
        return $this->address3;
    }
    function setAddress4($address4)
    {
        $this->address4 = $address4;
    }
    function getAddress4()
    {
        return $this->address4;
    }
    function setPhone1($phone1)
    {
        if ($phone1 == '' || $phone1 == null) {
            throw new ContactException("Phone number can not be null");
        }
        $this->phone1 = $phone1;
    }
    function getPhone1()
    {
        return $this->phone1;
    }
    function setEmail($email)
    {
        $this->email = $email;
    }
    function getEmail()
    {
        return $this->email;
    }
    function setBid($bid)
    {
        $this->bid = $bid;
    }
    function getBid()
    {
        return $this->bid;
    }
    function setPicture($picture)
    {
        $this->picture = $picture;
    }
    function getPicture()
    {
        return $this->picture;
    }
    function setCreated_by($created_by)
    {
        $this->created_by = $created_by;
    }
    function getCreated_by()
    {
        return $this->created_by;
    }
    function setUpdated_by($updated_by)
    {
        $this->updated_by = $updated_by;
    }
    function getUpdated_by()
    {
        return $this->updated_by;
    }
    function setUpdated_at($updated_at)
    {
        $this->updated_at = $updated_at;
    }
    function getUpdated_at()
    {
        return $this->updated_at;
    }

    public function returnContactArray()
    {
        $contact = array();
        $contact['id'] = $this->getId();
        $contact['code'] = $this->getCode();
        $contact['name'] = $this->getName();
        $contact['address1'] = $this->getAddress1();
        $contact['address2'] = $this->getAddress2();
        $contact['address3'] = $this->getAddress3();
        $contact['address4'] = $this->getAddress4();
        $contact['phone1'] = $this->getPhone1();
        $contact['email'] = $this->getEmail();
        $contact['bid'] = $this->getBid();
        $contact['picture'] = $this->getPicture();
        $contact['created_by'] = $this->getCreated_by();
        $contact['updated_by'] = $this->getUpdated_by();
        $contact['updated_at'] = $this->getUpdated_at();
        return $contact;
    }
}
