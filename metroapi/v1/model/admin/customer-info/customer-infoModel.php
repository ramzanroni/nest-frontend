<?php

class CustomerException extends Exception
{
}
class DebtorMaster
{
    private $debtorno;
    private $cm_id;
    private $name;
    private $address1;
    private $address2;
    private $address3;
    private $address4;
    private $address5;
    private $address6;
    private $login_media;
    private $login_id;
    private $currcode;
    private $salestype;
    private $clientsince;
    private $holdreason;
    private $paymentterms;
    private $discount;
    private $pymtdiscount;
    private $lastpaid;
    private $lastpaiddate;
    private $creditlimit;
    private $invaddrbranch;
    private $discountcode;
    private $ediinvoices;
    private $ediorders;
    private $edireference;
    private $editransport;
    private $ediaddress;
    private $ediserveruser;
    private $ediserverpwd;
    private $taxref;
    private $customerpoline;
    private $typeid;
    private $customer_note;
    private $custcatid1;
    private $op_bal;
    private $phone1;
    private $phone2;
    private $email;
    private $created_by;
    private $created_at;
    private $updated_at;
    private $updated_by;
    private $status;
    private $bin_no;
    private $nid_no;
    private $user_token;

    public function __construct($debtorno, $cm_id, $name, $address1, $address2, $address3, $address4, $address5, $address6, $login_media, $login_id, $currcode, $salestype, $clientsince, $holdreason, $paymentterms, $discount, $pymtdiscount, $lastpaid, $lastpaiddate, $creditlimit, $invaddrbranch, $discountcode, $ediinvoices, $ediorders, $edireference, $editransport, $ediaddress, $ediserveruser, $ediserverpwd, $taxref, $customerpoline, $typeid, $customer_note, $custcatid1, $op_bal, $phone1, $phone2, $email, $created_by, $created_at, $updated_at, $updated_by, $status, $bin_no, $nid_no, $user_token)
    {
        $this->setDebtorno($debtorno);
        $this->setCm_id($cm_id);
        $this->setName($name);
        $this->setAddress1($address1);
        $this->setAddress2($address2);
        $this->setAddress3($address3);
        $this->setAddress4($address4);
        $this->setAddress5($address5);
        $this->setAddress6($address6);
        $this->setLogin_media($login_media);
        $this->setLogin_id($login_id);
        $this->setCurrcode($currcode);
        $this->setSalestype($salestype);
        $this->setClientsince($clientsince);
        $this->setHoldreason($holdreason);
        $this->setPaymentterms($paymentterms);
        $this->setDiscount($discount);
        $this->setPymtdiscount($pymtdiscount);
        $this->setLastpaid($lastpaid);
        $this->setLastpaiddate($lastpaiddate);
        $this->setCreditlimit($creditlimit);
        $this->setInvaddrbranch($invaddrbranch);
        $this->setDiscountcode($discountcode);
        $this->setEdiinvoices($ediinvoices);
        $this->setEdiorders($ediorders);
        $this->setEdireference($edireference);
        $this->setEditransport($editransport);
        $this->setEdiaddress($ediaddress);
        $this->setEdiserveruser($ediserveruser);
        $this->setEdiserverpwd($ediserverpwd);
        $this->setTaxref($taxref);
        $this->setCustomerpoline($customerpoline);
        $this->setTypeid($typeid);
        $this->setCustomer_note($customer_note);
        $this->setCustcatid1($custcatid1);
        $this->setOp_bal($op_bal);
        $this->setPhone1($phone1);
        $this->setPhone2($phone2);
        $this->setEmail($email);
        $this->setCreated_by($created_by);
        $this->setCreated_at($created_at);
        $this->setUpdated_at($updated_at);
        $this->setUpdated_by($updated_by);
        $this->setStatus($status);
        $this->setBin_no($bin_no);
        $this->setNid_no($nid_no);
        $this->setUser_token($user_token);
    }

    function setDebtorno($debtorno)
    {
        $this->debtorno = $debtorno;
    }
    function getDebtorno()
    {
        return $this->debtorno;
    }
    function setCm_id($cm_id)
    {
        $this->cm_id = $cm_id;
    }
    function getCm_id()
    {
        return $this->cm_id;
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
    function setAddress5($address5)
    {
        $this->address5 = $address5;
    }
    function getAddress5()
    {
        return $this->address5;
    }
    function setAddress6($address6)
    {
        $this->address6 = $address6;
    }
    function getAddress6()
    {
        return $this->address6;
    }
    function setLogin_media($login_media)
    {
        $this->login_media = $login_media;
    }
    function getLogin_media()
    {
        return $this->login_media;
    }
    function setLogin_id($login_id)
    {
        $this->login_id = $login_id;
    }
    function getLogin_id()
    {
        return $this->login_id;
    }
    function setCurrcode($currcode)
    {
        $this->currcode = $currcode;
    }
    function getCurrcode()
    {
        return $this->currcode;
    }
    function setSalestype($salestype)
    {
        $this->salestype = $salestype;
    }
    function getSalestype()
    {
        return $this->salestype;
    }
    function setClientsince($clientsince)
    {
        $this->clientsince = $clientsince;
    }
    function getClientsince()
    {
        return $this->clientsince;
    }
    function setHoldreason($holdreason)
    {
        $this->holdreason = $holdreason;
    }
    function getHoldreason()
    {
        return $this->holdreason;
    }
    function setPaymentterms($paymentterms)
    {
        $this->paymentterms = $paymentterms;
    }
    function getPaymentterms()
    {
        return $this->paymentterms;
    }
    function setDiscount($discount)
    {
        $this->discount = $discount;
    }
    function getDiscount()
    {
        return $this->discount;
    }
    function setPymtdiscount($pymtdiscount)
    {
        $this->pymtdiscount = $pymtdiscount;
    }
    function getPymtdiscount()
    {
        return $this->pymtdiscount;
    }
    function setLastpaid($lastpaid)
    {
        $this->lastpaid = $lastpaid;
    }
    function getLastpaid()
    {
        return $this->lastpaid;
    }
    function setLastpaiddate($lastpaiddate)
    {
        $this->lastpaiddate = $lastpaiddate;
    }
    function getLastpaiddate()
    {
        return $this->lastpaiddate;
    }
    function setCreditlimit($creditlimit)
    {
        $this->creditlimit = $creditlimit;
    }
    function getCreditlimit()
    {
        return $this->creditlimit;
    }
    function setInvaddrbranch($invaddrbranch)
    {
        $this->invaddrbranch = $invaddrbranch;
    }
    function getInvaddrbranch()
    {
        return $this->invaddrbranch;
    }
    function setDiscountcode($discountcode)
    {
        $this->discountcode = $discountcode;
    }
    function getDiscountcode()
    {
        return $this->discountcode;
    }
    function setEdiinvoices($ediinvoices)
    {
        $this->ediinvoices = $ediinvoices;
    }
    function getEdiinvoices()
    {
        return $this->ediinvoices;
    }
    function setEdiorders($ediorders)
    {
        $this->ediorders = $ediorders;
    }
    function getEdiorders()
    {
        return $this->ediorders;
    }
    function setEdireference($edireference)
    {
        $this->edireference = $edireference;
    }
    function getEdireference()
    {
        return $this->edireference;
    }
    function setEditransport($editransport)
    {
        $this->editransport = $editransport;
    }
    function getEditransport()
    {
        return $this->editransport;
    }
    function setEdiaddress($ediaddress)
    {
        $this->ediaddress = $ediaddress;
    }
    function getEdiaddress()
    {
        return $this->ediaddress;
    }
    function setEdiserveruser($ediserveruser)
    {
        $this->ediserveruser = $ediserveruser;
    }
    function getEdiserveruser()
    {
        return $this->ediserveruser;
    }
    function setEdiserverpwd($ediserverpwd)
    {
        $this->ediserverpwd = $ediserverpwd;
    }
    function getEdiserverpwd()
    {
        return $this->ediserverpwd;
    }
    function setTaxref($taxref)
    {
        $this->taxref = $taxref;
    }
    function getTaxref()
    {
        return $this->taxref;
    }
    function setCustomerpoline($customerpoline)
    {
        $this->customerpoline = $customerpoline;
    }
    function getCustomerpoline()
    {
        return $this->customerpoline;
    }
    function setTypeid($typeid)
    {
        $this->typeid = $typeid;
    }
    function getTypeid()
    {
        return $this->typeid;
    }
    function setCustomer_note($customer_note)
    {
        $this->customer_note = $customer_note;
    }
    function getCustomer_note()
    {
        return $this->customer_note;
    }
    function setCustcatid1($custcatid1)
    {
        $this->custcatid1 = $custcatid1;
    }
    function getCustcatid1()
    {
        return $this->custcatid1;
    }
    function setOp_bal($op_bal)
    {
        $this->op_bal = $op_bal;
    }
    function getOp_bal()
    {
        return $this->op_bal;
    }
    function setPhone1($phone1)
    {
        $this->phone1 = $phone1;
    }
    function getPhone1()
    {
        return $this->phone1;
    }
    function setPhone2($phone2)
    {
        $this->phone2 = $phone2;
    }
    function getPhone2()
    {
        return $this->phone2;
    }
    function setEmail($email)
    {
        $this->email = $email;
    }
    function getEmail()
    {
        return $this->email;
    }
    function setCreated_by($created_by)
    {
        $this->created_by = $created_by;
    }
    function getCreated_by()
    {
        return $this->created_by;
    }
    function setCreated_at($created_at)
    {
        $this->created_at = $created_at;
    }
    function getCreated_at()
    {
        return $this->created_at;
    }
    function setUpdated_at($updated_at)
    {
        $this->updated_at = $updated_at;
    }
    function getUpdated_at()
    {
        return $this->updated_at;
    }
    function setUpdated_by($updated_by)
    {
        $this->updated_by = $updated_by;
    }
    function getUpdated_by()
    {
        return $this->updated_by;
    }
    function setStatus($status)
    {
        $this->status = $status;
    }
    function getStatus()
    {
        return $this->status;
    }
    function setBin_no($bin_no)
    {
        $this->bin_no = $bin_no;
    }
    function getBin_no()
    {
        return $this->bin_no;
    }
    function setNid_no($nid_no)
    {
        $this->nid_no = $nid_no;
    }
    function getNid_no()
    {
        return $this->nid_no;
    }
    function setUser_token($user_token)
    {
        $this->user_token = $user_token;
    }
    function getUser_token()
    {
        return $this->user_token;
    }
    public function returnCustomerInfoArray()
    {
        $customerInfo = array();
        $customerInfo['debtorno'] = $this->getDebtorno();
        $customerInfo['cm_id'] = $this->getCm_id();
        $customerInfo['name'] = $this->getName();
        $customerInfo['address1'] = $this->getAddress1();
        $customerInfo['address2'] = $this->getAddress2();
        $customerInfo['address3'] = $this->getAddress3();
        $customerInfo['address4'] = $this->getAddress4();
        $customerInfo['address5'] = $this->getAddress5();
        $customerInfo['address6'] = $this->getAddress6();
        $customerInfo['login_media'] = $this->getLogin_media();
        $customerInfo['login_id'] = $this->getLogin_id();
        $customerInfo['currcode'] = $this->getCurrcode();
        $customerInfo['salestype'] = $this->getSalestype();
        $customerInfo['clientsince'] = $this->getClientsince();
        $customerInfo['holdreason'] = $this->getHoldreason();
        $customerInfo['paymentterms'] = $this->getPaymentterms();
        $customerInfo['discount'] = $this->getDiscount();
        $customerInfo['pymtdiscount'] = $this->getPymtdiscount();
        $customerInfo['lastpaid'] = $this->getLastpaid();
        $customerInfo['lastpaiddate'] = $this->getLastpaid();
        $customerInfo['creditlimit'] = $this->getCreditlimit();
        $customerInfo['invaddrbranch'] = $this->getInvaddrbranch();
        $customerInfo['discountcode'] = $this->getDiscountcode();
        $customerInfo['ediinvoices'] = $this->getEdiinvoices();
        $customerInfo['ediorders'] = $this->getEdiorders();
        $customerInfo['edireference'] = $this->getEdireference();
        $customerInfo['editransport'] = $this->getEditransport();
        $customerInfo['ediaddress'] = $this->getEdiaddress();
        $customerInfo['ediserveruser'] = $this->getEdiserveruser();
        $customerInfo['ediserverpwd'] = $this->getEdiserverpwd();
        $customerInfo['taxref'] = $this->getTaxref();
        $customerInfo['customerpoline'] = $this->getCustomerpoline();
        $customerInfo['typeid'] = $this->getTypeid();
        $customerInfo['customer_note'] = $this->getCustomer_note();
        $customerInfo['custcatid1'] = $this->getCustcatid1();
        $customerInfo['op_bal'] = $this->getOp_bal();
        $customerInfo['phone1'] = $this->getPhone1();
        $customerInfo['phone2'] = $this->getPhone2();
        $customerInfo['email'] = $this->getEmail();
        $customerInfo['created_by'] = $this->getCreated_by();
        $customerInfo['created_at'] = $this->getCreated_at();
        $customerInfo['updated_at'] = $this->getUpdated_at();
        $customerInfo['updated_by'] = $this->getUpdated_by();
        $customerInfo['status'] = $this->getStatus();
        $customerInfo['bin_no'] = $this->getBin_no();
        $customerInfo['nid_no'] = $this->getNid_no();
        $customerInfo['user_token'] = $this->getUser_token();
        return $customerInfo;
    }
}
