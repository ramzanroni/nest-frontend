<?php
class CustBranchException extends Exception
{
}
class CustBranh
{
    private $branchcode;
    private $debtorno;
    private $brname;
    private $braddress1;
    private $braddress2;
    private $braddress3;
    private $braddress4;
    private $braddress5;
    private $braddress6;
    private $lat;
    private $lng;
    private $estdeliverydays;
    private $area;
    private $salesman;
    private $fwddate;
    private $phoneno;
    private $faxno;
    private $contactname;
    private $email;
    private $defaultlocation;
    private $taxgroupid;
    private $defaultshipvia;
    private $deliverblind;
    private $disabletrans;
    private $brpostaddr1;
    private $brpostaddr2;
    private $brpostaddr3;
    private $brpostaddr4;
    private $brpostaddr5;
    private $brpostaddr6;
    private $specialinstructions;
    private $custbranchcode;
    private $branchdistance;
    private $travelrate;
    private $businessunit;
    private $emi;
    private $esd;
    private $branchsince;
    private $branchstatus;
    private $tag;
    private $op_bal;
    private $aggrigate_cr;
    private $discount_amt;
    public function __construct($branchcode, $debtorno, $brname, $braddress1, $braddress2, $braddress3, $braddress4, $braddress5, $braddress6, $lat, $lng, $estdeliverydays, $area, $salesman, $fwddate, $phoneno, $faxno, $contactname, $email, $defaultlocation, $taxgroupid, $defaultshipvia, $deliverblind, $disabletrans, $brpostaddr1, $brpostaddr2, $brpostaddr3, $brpostaddr4, $brpostaddr5, $brpostaddr6, $specialinstructions, $custbranchcode, $branchdistance, $travelrate, $businessunit, $emi, $esd, $branchsince, $branchstatus, $tag, $op_bal, $aggrigate_cr, $discount_amt)
    {
        $this->setBranchcode($branchcode);
        $this->setDebtorno($debtorno);
        $this->setBrname($brname);
        $this->setBraddress1($braddress1);
        $this->setBraddress2($braddress2);
        $this->setBraddress3($braddress3);
        $this->setBraddress4($braddress4);
        $this->setBraddress5($braddress5);
        $this->setBraddress6($braddress6);
        $this->setLat($lat);
        $this->setLng($lng);
        $this->setEstdeliverydays($estdeliverydays);
        $this->setArea($area);
        $this->setSalesman($salesman);
        $this->setFwddate($fwddate);
        $this->setPhoneno($phoneno);
        $this->setFaxno($faxno);
        $this->setContactname($contactname);
        $this->setEmail($email);
        $this->setDefaultlocation($defaultlocation);
        $this->setTaxgroupid($taxgroupid);
        $this->setDefaultshipvia($defaultshipvia);
        $this->setDeliverblind($deliverblind);
        $this->setDisabletrans($disabletrans);
        $this->setBrpostaddr1($brpostaddr1);
        $this->setBrpostaddr2($brpostaddr2);
        $this->setBrpostaddr3($brpostaddr3);
        $this->setBrpostaddr4($brpostaddr4);
        $this->setBrpostaddr5($brpostaddr5);
        $this->setBrpostaddr6($brpostaddr6);
        $this->setSpecialinstructions($specialinstructions);
        $this->setCustbranchcode($custbranchcode);
        $this->setBranchdistance($branchdistance);
        $this->setTravelrate($travelrate);
        $this->setBusinessunit($businessunit);
        $this->setEmi($emi);
        $this->setEsd($esd);
        $this->setBranchsince($branchsince);
        $this->setBranchstatus($branchstatus);
        $this->setTag($tag);
        $this->setOp_bal($op_bal);
        $this->setAggrigate_cr($aggrigate_cr);
        $this->setDiscount_amt($discount_amt);
    }
    function setBranchcode($branchcode)
    {
        $this->branchcode = $branchcode;
    }
    function getBranchcode()
    {
        return $this->branchcode;
    }
    function setDebtorno($debtorno)
    {
        $this->debtorno = $debtorno;
    }
    function getDebtorno()
    {
        return $this->debtorno;
    }
    function setBrname($brname)
    {
        $this->brname = $brname;
    }
    function getBrname()
    {
        return $this->brname;
    }
    function setBraddress1($braddress1)
    {
        $this->braddress1 = $braddress1;
    }
    function getBraddress1()
    {
        return $this->braddress1;
    }
    function setBraddress2($braddress2)
    {
        $this->braddress2 = $braddress2;
    }
    function getBraddress2()
    {
        return $this->braddress2;
    }
    function setBraddress3($braddress3)
    {
        $this->braddress3 = $braddress3;
    }
    function getBraddress3()
    {
        return $this->braddress3;
    }
    function setBraddress4($braddress4)
    {
        $this->braddress4 = $braddress4;
    }
    function getBraddress4()
    {
        return $this->braddress4;
    }
    function setBraddress5($braddress5)
    {
        $this->braddress5 = $braddress5;
    }
    function getBraddress5()
    {
        return $this->braddress5;
    }
    function setBraddress6($braddress6)
    {
        $this->braddress6 = $braddress6;
    }
    function getBraddress6()
    {
        return $this->braddress6;
    }
    function setLat($lat)
    {
        $this->lat = $lat;
    }
    function getLat()
    {
        return $this->lat;
    }
    function setLng($lng)
    {
        $this->lng = $lng;
    }
    function getLng()
    {
        return $this->lng;
    }
    function setEstdeliverydays($estdeliverydays)
    {
        $this->estdeliverydays = $estdeliverydays;
    }
    function getEstdeliverydays()
    {
        return $this->estdeliverydays;
    }
    function setArea($area)
    {
        $this->area = $area;
    }
    function getArea()
    {
        return $this->area;
    }
    function setSalesman($salesman)
    {
        $this->salesman = $salesman;
    }
    function getSalesman()
    {
        return $this->salesman;
    }
    function setFwddate($fwddate)
    {
        $this->fwddate = $fwddate;
    }
    function getFwddate()
    {
        return $this->fwddate;
    }
    function setPhoneno($phoneno)
    {
        $this->phoneno = $phoneno;
    }
    function getPhoneno()
    {
        return $this->phoneno;
    }
    function setFaxno($faxno)
    {
        $this->faxno = $faxno;
    }
    function getFaxno()
    {
        return $this->faxno;
    }
    function setContactname($contactname)
    {
        $this->contactname = $contactname;
    }
    function getContactname()
    {
        return $this->contactname;
    }
    function setEmail($email)
    {
        $this->email = $email;
    }
    function getEmail()
    {
        return $this->email;
    }
    function setDefaultlocation($defaultlocation)
    {
        $this->defaultlocation = $defaultlocation;
    }
    function getDefaultlocation()
    {
        return $this->defaultlocation;
    }
    function setTaxgroupid($taxgroupid)
    {
        $this->taxgroupid = $taxgroupid;
    }
    function getTaxgroupid()
    {
        return $this->taxgroupid;
    }
    function setDefaultshipvia($defaultshipvia)
    {
        $this->defaultshipvia = $defaultshipvia;
    }
    function getDefaultshipvia()
    {
        return $this->defaultshipvia;
    }
    function setDeliverblind($deliverblind)
    {
        $this->deliverblind = $deliverblind;
    }
    function getDeliverblind()
    {
        return $this->deliverblind;
    }
    function setDisabletrans($disabletrans)
    {
        $this->disabletrans = $disabletrans;
    }
    function getDisabletrans()
    {
        return $this->disabletrans;
    }
    function setBrpostaddr1($brpostaddr1)
    {
        $this->brpostaddr1 = $brpostaddr1;
    }
    function getBrpostaddr1()
    {
        return $this->brpostaddr1;
    }
    function setBrpostaddr2($brpostaddr2)
    {
        $this->brpostaddr2 = $brpostaddr2;
    }
    function getBrpostaddr2()
    {
        return $this->brpostaddr2;
    }
    function setBrpostaddr3($brpostaddr3)
    {
        $this->brpostaddr3 = $brpostaddr3;
    }
    function getBrpostaddr3()
    {
        return $this->brpostaddr3;
    }
    function setBrpostaddr4($brpostaddr4)
    {
        $this->brpostaddr4 = $brpostaddr4;
    }
    function getBrpostaddr4()
    {
        return $this->brpostaddr4;
    }
    function setBrpostaddr5($brpostaddr5)
    {
        $this->brpostaddr5 = $brpostaddr5;
    }
    function getBrpostaddr5()
    {
        return $this->brpostaddr5;
    }
    function setBrpostaddr6($brpostaddr6)
    {
        $this->brpostaddr6 = $brpostaddr6;
    }
    function getBrpostaddr6()
    {
        return $this->brpostaddr6;
    }
    function setSpecialinstructions($specialinstructions)
    {
        $this->specialinstructions = $specialinstructions;
    }
    function getSpecialinstructions()
    {
        return $this->specialinstructions;
    }
    function setCustbranchcode($custbranchcode)
    {
        $this->custbranchcode = $custbranchcode;
    }
    function getCustbranchcode()
    {
        return $this->custbranchcode;
    }
    function setBranchdistance($branchdistance)
    {
        $this->branchdistance = $branchdistance;
    }
    function getBranchdistance()
    {
        return $this->branchdistance;
    }
    function setTravelrate($travelrate)
    {
        $this->travelrate = $travelrate;
    }
    function getTravelrate()
    {
        return $this->travelrate;
    }
    function setBusinessunit($businessunit)
    {
        $this->businessunit = $businessunit;
    }
    function getBusinessunit()
    {
        return $this->businessunit;
    }
    function setEmi($emi)
    {
        $this->emi = $emi;
    }
    function getEmi()
    {
        return $this->emi;
    }
    function setEsd($esd)
    {
        $this->esd = $esd;
    }
    function getEsd()
    {
        return $this->esd;
    }
    function setBranchsince($branchsince)
    {
        $this->branchsince = $branchsince;
    }
    function getBranchsince()
    {
        return $this->branchsince;
    }
    function setBranchstatus($branchstatus)
    {
        $this->branchstatus = $branchstatus;
    }
    function getBranchstatus()
    {
        return $this->branchstatus;
    }
    function setTag($tag)
    {
        $this->tag = $tag;
    }
    function getTag()
    {
        return $this->tag;
    }
    function setOp_bal($op_bal)
    {
        $this->op_bal = $op_bal;
    }
    function getOp_bal()
    {
        return $this->op_bal;
    }
    function setAggrigate_cr($aggrigate_cr)
    {
        $this->aggrigate_cr = $aggrigate_cr;
    }
    function getAggrigate_cr()
    {
        return $this->aggrigate_cr;
    }
    function setDiscount_amt($discount_amt)
    {
        $this->discount_amt = $discount_amt;
    }
    function getDiscount_amt()
    {
        return $this->discount_amt;
    }


    public function returnCustbranchArray()
    {
        $custbranch = array();
        $custbranch['branchcode'] = $this->getBranchcode();
        $custbranch['debtorno'] = $this->getDebtorno();
        $custbranch['brname'] = $this->getBrname();
        $custbranch['braddress1'] = $this->getBraddress1();
        $custbranch['braddress2'] = $this->getBraddress2();
        $custbranch['braddress3'] = $this->getBraddress3();
        $custbranch['braddress4'] = $this->getBraddress4();
        $custbranch['braddress5'] = $this->getBraddress5();
        $custbranch['braddress6'] = $this->getBraddress6();
        $custbranch['lat'] = $this->getLat();
        $custbranch['lng'] = $this->getLng();
        $custbranch['estdeliverydays'] = $this->getEstdeliverydays();
        $custbranch['area'] = $this->getArea();
        $custbranch['salesman'] = $this->getSalesman();
        $custbranch['fwddate'] = $this->getFwddate();
        $custbranch['phoneno'] = $this->getPhoneno();
        $custbranch['faxno'] = $this->getFaxno();
        $custbranch['contactname'] = $this->getContactname();
        $custbranch['email'] = $this->getEmail();
        $custbranch['defaultlocation'] = $this->getDefaultlocation();
        $custbranch['taxgroupid'] = $this->getTaxgroupid();
        $custbranch['defaultshipvia'] = $this->getDefaultshipvia();
        $custbranch['deliverblind'] = $this->getDeliverblind();
        $custbranch['disabletrans'] = $this->getDisabletrans();
        $custbranch['brpostaddr1'] = $this->getBrpostaddr1();
        $custbranch['brpostaddr2'] = $this->getBrpostaddr2();
        $custbranch['brpostaddr3'] = $this->getBrpostaddr3();
        $custbranch['brpostaddr4'] = $this->getBrpostaddr4();
        $custbranch['brpostaddr5'] = $this->getBrpostaddr5();
        $custbranch['brpostaddr6'] = $this->getBrpostaddr6();
        $custbranch['specialinstructions'] = $this->getSpecialinstructions();
        $custbranch['custbranchcode'] = $this->getCustbranchcode();
        $custbranch['branchdistance'] = $this->getBranchdistance();
        $custbranch['travelrate'] = $this->getTravelrate();
        $custbranch['businessunit'] = $this->getBusinessunit();
        $custbranch['emi'] = $this->getEmi();
        $custbranch['esd'] = $this->getEsd();
        $custbranch['branchsince'] = $this->getBranchsince();
        $custbranch['branchstatus'] = $this->getBranchstatus();
        $custbranch['tag'] = $this->getTag();
        $custbranch['op_bal'] = $this->getOp_bal();
        $custbranch['aggrigate_cr'] = $this->getAggrigate_cr();
        $custbranch['discount_amt'] = $this->getDiscount_amt();
        return $custbranch;
    }
}
