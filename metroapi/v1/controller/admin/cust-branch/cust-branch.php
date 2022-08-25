<?php


include_once('../../db.php');
include_once('../../../model/admin/cust-branch/cust-branchModel.php');
include_once('../../../model/response.php');

$allHeaders = getallheaders();
$apiSecurity = $allHeaders['Authorization'];
if ($apiKey != $apiSecurity) {
    $response = new Response();
    $response->setHttpStatusCode(401);
    $response->setSuccess(false);
    $response->addMessage("API Security Key Doesn't exist.");
    $response->send();
    exit;
}
try {
    $writeDB = DB::connectWriteDB();
    $readDB = DB::connectReadDB();
} catch (PDOException $ex) {
    error_log("Connection error - " . $ex, 0);
    $response = new Response();
    $response->setHttpStatusCode(500);
    $response->setSuccess(false);
    $response->addMessage("Database Connection Error");
    $response->send();
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        if ($_SERVER['CONTENT_TYPE'] !== 'application/json') {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("Content type header is not set to JSON");
            $response->send();
            exit();
        }
        $rawPostData = file_get_contents('php://input');
        if (!$jsonData = json_decode($rawPostData)) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("Request body is not valid JSON");
            $response->send();
            exit();
        }
        $custBranch = new CustBranh(null, $jsonData->debtorno, $jsonData->brname, $jsonData->braddress1, $jsonData->braddress2, $jsonData->braddress3, $jsonData->braddress4, $jsonData->braddress5, $jsonData->braddress6, $jsonData->lat, $jsonData->lng, $jsonData->estdeliverydays, $jsonData->area, $jsonData->salesman, $jsonData->fwddate, $jsonData->phoneno, $jsonData->faxno, $jsonData->contactname, $jsonData->email, $jsonData->defaultlocation, $jsonData->taxgroupid, $jsonData->defaultshipvia, $jsonData->deliverblind, $jsonData->disabletrans, $jsonData->brpostaddr1, $jsonData->brpostaddr2, $jsonData->brpostaddr3, $jsonData->brpostaddr4, $jsonData->brpostaddr5, $jsonData->brpostaddr6, $jsonData->specialinstructions, $jsonData->custbranchcode, $jsonData->branchdistance, $jsonData->travelrate, $jsonData->businessunit, $jsonData->emi, $jsonData->esd, $jsonData->branchsince, $jsonData->branchstatus, $jsonData->tag, $jsonData->op_bal, $jsonData->aggrigate_cr, $jsonData->discount_amt);
        $branchcode = $custBranch->getBranchcode();
        $debtorno = $custBranch->getDebtorno();
        $brname = $custBranch->getBrname();
        $braddress1 = $custBranch->getBraddress1();
        $braddress2 = $custBranch->getBraddress2();
        $braddress3 = $custBranch->getBraddress3();
        $braddress4 = $custBranch->getBraddress4();
        $braddress5 = $custBranch->getBraddress5();
        $braddress6 = $custBranch->getBraddress6();
        $lat = $custBranch->getLat();
        $lng = $custBranch->getLng();
        $estdeliverydays = $custBranch->getEstdeliverydays();
        $area = $custBranch->getArea();
        $salesman = $custBranch->getSalesman();
        $fwddate = $custBranch->getFwddate();
        $phoneno = $custBranch->getPhoneno();
        $faxno = $custBranch->getFaxno();
        $contactname = $custBranch->getContactname();
        $email = $custBranch->getEmail();
        $defaultlocation = $custBranch->getDefaultlocation();
        $taxgroupid = $custBranch->getTaxgroupid();
        $defaultshipvia = $custBranch->getDefaultshipvia();
        $deliverblind = $custBranch->getDeliverblind();
        $disabletrans = $custBranch->getDisabletrans();
        $brpostaddr1 = $custBranch->getBrpostaddr1();
        $brpostaddr2 = $custBranch->getBrpostaddr2();
        $brpostaddr3 = $custBranch->getBrpostaddr3();
        $brpostaddr4 = $custBranch->getBrpostaddr4();
        $brpostaddr5 = $custBranch->getBrpostaddr5();
        $brpostaddr6 = $custBranch->getBrpostaddr6();
        $specialinstructions = $custBranch->getSpecialinstructions();
        $custbranchcode = $custBranch->getCustbranchcode();
        $branchdistance = $custBranch->getBranchdistance();
        $travelrate = $custBranch->getTravelrate();
        $businessunit = $custBranch->getBusinessunit();
        $emi = $custBranch->getEmi();
        $esd = $custBranch->getEsd();
        $branchsince = $custBranch->getBranchsince();
        $branchstatus = $custBranch->getBranchstatus();
        $tag = $custBranch->getTag();
        $op_bal = $custBranch->getOp_bal();
        $aggrigate_cr = $custBranch->getAggrigate_cr();
        $discount_amt = $custBranch->getDiscount_amt();
        // insert custbranch 
        $query = "INSERT INTO `custbranch`(`debtorno`, `brname`, `braddress1`, `braddress2`, `braddress3`, `braddress4`, `braddress5`, `braddress6`, `lat`, `lng`, `estdeliverydays`, `area`, `salesman`, `fwddate`, `phoneno`, `faxno`, `contactname`, `email`, `defaultlocation`, `taxgroupid`, `defaultshipvia`, `deliverblind`, `disabletrans`, `brpostaddr1`, `brpostaddr2`, `brpostaddr3`, `brpostaddr4`, `brpostaddr5`, `brpostaddr6`, `specialinstructions`, `custbranchcode`, `branchdistance`, `travelrate`, `businessunit`, `emi`, `esd`, `branchsince`, `branchstatus`, `tag`, `op_bal`, `aggrigate_cr`, `discount_amt`) VALUES (:debtorno,:brname,:braddress1,:braddress2,:braddress3,:braddress4,:braddress5,:braddress6,:lat,:lng,:estdeliverydays,:area,:salesman,:fwddate,:phoneno,:faxno,:contactname,:email,:defaultlocation,:taxgroupid,:defaultshipvia,:deliverblind,:disabletrans,:brpostaddr1,:brpostaddr2,:brpostaddr3,:brpostaddr4,:brpostaddr5,:brpostaddr6,:specialinstructions,:custbranchcode,:branchdistance,:travelrate,:businessunit,:emi,:esd,:branchsince,:branchstatus,:tag,:op_bal,:aggrigate_cr,:discount_amt)";
        $insertCustbranch = $writeDB->prepare($query);
        $insertCustbranch->bindParam(':debtorno', $debtorno, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':brname', $brname, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':braddress1', $braddress1, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':braddress2', $braddress2, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':braddress3', $braddress3, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':braddress4', $braddress4, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':braddress5', $braddress5, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':braddress6', $braddress6, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':lat', $lat, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':lng', $lng, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':estdeliverydays', $estdeliverydays, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':area', $area, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':salesman', $salesman, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':fwddate', $fwddate, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':phoneno', $phoneno, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':faxno', $faxno, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':contactname', $contactname, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':email', $email, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':defaultlocation', $defaultlocation, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':taxgroupid', $taxgroupid, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':defaultshipvia', $defaultshipvia, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':deliverblind', $deliverblind, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':disabletrans', $disabletrans, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':brpostaddr1', $brpostaddr1, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':brpostaddr2', $brpostaddr2, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':brpostaddr3', $brpostaddr3, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':brpostaddr4', $brpostaddr4, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':brpostaddr5', $brpostaddr5, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':brpostaddr6', $brpostaddr6, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':specialinstructions', $specialinstructions, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':custbranchcode', $custbranchcode, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':branchdistance', $branchdistance, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':travelrate', $travelrate, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':businessunit', $businessunit, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':emi', $emi, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':esd', $esd, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':branchsince', $branchsince, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':branchstatus', $branchstatus, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':tag', $tag, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':op_bal', $op_bal, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':aggrigate_cr', $aggrigate_cr, PDO::PARAM_STR);
        $insertCustbranch->bindParam(':discount_amt', $discount_amt, PDO::PARAM_STR);
        $insertCustbranch->execute();
        $rowCount = $insertCustbranch->rowCount();
        if ($rowCount === 1) {
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->addMessage("Insert Success.");
            $response->send();
            exit;
        }
    } catch (CustBranchException $ex) {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        $response->addMessage($ex->getMessage());
        $response->send();
        exit();
    } catch (PDOException $ex) {
        error_log("Database query error." . $ex, 0);
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage($ex->getMessage());
        $response->send();
    }
} elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (array_key_exists('branchcode', $_GET)) {
        $branchcode = $_GET['branchcode'];
        if ($branchcode == '' || !is_numeric($branchcode)) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage('branchcode number be numeric and not null.');
            $response->send();
            exit();
        }
        $result = $readDB->prepare('SELECT * FROM custbranch WHERE branchcode=:branchcode');
        $result->bindParam(':branchcode', $branchcode, PDO::PARAM_STR);
        $result->execute();
    } elseif (array_key_exists('debtorno', $_GET)) {
        $debtorno = $_GET['debtorno'];
        if ($debtorno == '' || !is_numeric($debtorno)) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage('debtorno number be numeric and not null.');
            $response->send();
            exit();
        }
        $result = $readDB->prepare('SELECT * FROM custbranch WHERE debtorno=:debtorno');
        $result->bindParam(':debtorno', $debtorno, PDO::PARAM_STR);
        $result->execute();
    } elseif (array_key_exists('brname', $_GET)) {
        $brname = $_GET['brname'];
        if ($brname == '') {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage('brname can not be null.');
            $response->send();
            exit();
        }
        $subQry = "SELECT * FROM custbranch WHERE ";
        $textsearchQury = '';

        $searchKeywordList = explode(' ', $name);

        foreach ($searchKeywordList as $searchKey) {
            $textsearchQury .= "brname LIKE '%" . $searchKey . "%' OR ";
        }
        $textsearchQury = $subQry . rtrim($textsearchQury, 'OR ');
        $result = $readDB->prepare($textsearchQury);
        $result->execute();
    } elseif (array_key_exists('phoneno', $_GET)) {
        $phoneno = $_GET['phoneno'];
        if ($phoneno == '' || !is_numeric($phoneno)) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage('phoneno number be numeric and not null.');
            $response->send();
            exit();
        }
        $result = $readDB->prepare('SELECT * FROM custbranch WHERE phoneno=:phoneno');
        $result->bindParam(':phoneno', $phoneno, PDO::PARAM_STR);
        $result->execute();
    } else {
        $result = $readDB->prepare('SELECT * FROM custbranch');
        $result->execute();
    }
    $rowCount = $result->rowCount();
    if ($rowCount == 0) {
        $response = new Response();
        $response->setHttpStatusCode(404);
        $response->setSuccess(false);
        $response->addMessage('No data found');
        $response->send();
        exit;
    }
    $custBranchArr = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $custBranchData = new CustBranh($row['branchcode'], $row['debtorno'], $row['brname'], $row['braddress1'], $row['braddress2'], $row['braddress3'], $row['braddress4'], $row['braddress5'], $row['braddress6'], $row['lat'], $row['lng'], $row['estdeliverydays'], $row['area'], $row['salesman'], $row['fwddate'], $row['phoneno'], $row['faxno'], $row['contactname'], $row['email'], $row['defaultlocation'], $row['taxgroupid'], $row['defaultshipvia'], $row['deliverblind'], $row['disabletrans'], $row['brpostaddr1'], $row['brpostaddr2'], $row['brpostaddr3'], $row['brpostaddr4'], $row['brpostaddr5'], $row['brpostaddr6'], $row['specialinstructions'], $row['custbranchcode'], $row['branchdistance'], $row['travelrate'], $row['businessunit'], $row['emi'], $row['esd'], $row['branchsince'], $row['branchstatus'], $row['tag'], $row['op_bal'], $row['aggrigate_cr'], $row['discount_amt']);
        $custBranchArr[] = $custBranchData->returnCustbranchArray();
    }
    $returnArray = array();
    $returnArray['rows_returned'] = $rowCount;
    $returnArray['custbranch'] = $custBranchArr;
    $response = new Response();
    $response->setHttpStatusCode(200);
    $response->toCache(true);
    $response->setSuccess(true);
    $response->setData($returnArray);
    $response->send();
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] == "PUT") {
    try {
        if ($_SERVER['CONTENT_TYPE'] !== 'application/json') {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("Content type header is not set to JSON");
            $response->send();
            exit();
        }
        $rawPostData = file_get_contents('php://input');
        if (!$jsonData = json_decode($rawPostData)) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("Request body is not valid JSON");
            $response->send();
            exit();
        }

        $custBranch = new CustBranh($jsonData->branchcode, $jsonData->debtorno, $jsonData->brname, $jsonData->braddress1, $jsonData->braddress2, $jsonData->braddress3, $jsonData->braddress4, $jsonData->braddress5, $jsonData->braddress6, $jsonData->lat, $jsonData->lng, $jsonData->estdeliverydays, $jsonData->area, $jsonData->salesman, $jsonData->fwddate, $jsonData->phoneno, $jsonData->faxno, $jsonData->contactname, $jsonData->email, $jsonData->defaultlocation, $jsonData->taxgroupid, $jsonData->defaultshipvia, $jsonData->deliverblind, $jsonData->disabletrans, $jsonData->brpostaddr1, $jsonData->brpostaddr2, $jsonData->brpostaddr3, $jsonData->brpostaddr4, $jsonData->brpostaddr5, $jsonData->brpostaddr6, $jsonData->specialinstructions, $jsonData->custbranchcode, $jsonData->branchdistance, $jsonData->travelrate, $jsonData->businessunit, $jsonData->emi, $jsonData->esd, $jsonData->branchsince, $jsonData->branchstatus, $jsonData->tag, $jsonData->op_bal, $jsonData->aggrigate_cr, $jsonData->discount_amt);
        $branchcode = $custBranch->getBranchcode();
        $debtorno = $custBranch->getDebtorno();
        $brname = $custBranch->getBrname();
        $braddress1 = $custBranch->getBraddress1();
        $braddress2 = $custBranch->getBraddress2();
        $braddress3 = $custBranch->getBraddress3();
        $braddress4 = $custBranch->getBraddress4();
        $braddress5 = $custBranch->getBraddress5();
        $braddress6 = $custBranch->getBraddress6();
        $lat = $custBranch->getLat();
        $lng = $custBranch->getLng();
        $estdeliverydays = $custBranch->getEstdeliverydays();
        $area = $custBranch->getArea();
        $salesman = $custBranch->getSalesman();
        $fwddate = $custBranch->getFwddate();
        $phoneno = $custBranch->getPhoneno();
        $faxno = $custBranch->getFaxno();
        $contactname = $custBranch->getContactname();
        $email = $custBranch->getEmail();
        $defaultlocation = $custBranch->getDefaultlocation();
        $taxgroupid = $custBranch->getTaxgroupid();
        $defaultshipvia = $custBranch->getDefaultshipvia();
        $deliverblind = $custBranch->getDeliverblind();
        $disabletrans = $custBranch->getDisabletrans();
        $brpostaddr1 = $custBranch->getBrpostaddr1();
        $brpostaddr2 = $custBranch->getBrpostaddr2();
        $brpostaddr3 = $custBranch->getBrpostaddr3();
        $brpostaddr4 = $custBranch->getBrpostaddr4();
        $brpostaddr5 = $custBranch->getBrpostaddr5();
        $brpostaddr6 = $custBranch->getBrpostaddr6();
        $specialinstructions = $custBranch->getSpecialinstructions();
        $custbranchcode = $custBranch->getCustbranchcode();
        $branchdistance = $custBranch->getBranchdistance();
        $travelrate = $custBranch->getTravelrate();
        $businessunit = $custBranch->getBusinessunit();
        $emi = $custBranch->getEmi();
        $esd = $custBranch->getEsd();
        $branchsince = $custBranch->getBranchsince();
        $branchstatus = $custBranch->getBranchstatus();
        $tag = $custBranch->getTag();
        $op_bal = $custBranch->getOp_bal();
        $aggrigate_cr = $custBranch->getAggrigate_cr();
        $discount_amt = $custBranch->getDiscount_amt();
        // check custbranch 
        $checkCustbranch = $readDB->prepare('SELECT * FROM `custbranch` WHERE `branchcode`=:branchcode');
        $checkCustbranch->bindParam(':branchcode', $branchcode, PDO::PARAM_STR);
        $checkCustbranch->execute();
        $rowCount = $checkCustbranch->rowCount();
        if ($rowCount == 0) {
            $response = new Response();
            $response->setHttpStatusCode(404);
            $response->setSuccess(false);
            $response->addMessage("Data not found.");
            $response->send();
            exit;
        }

        // update castbranch

        $updateCustbranch = $writeDB->prepare('UPDATE `custbranch` SET `debtorno`=:debtorno,`brname`=:brname,`braddress1`=:braddress1,`braddress2`=:braddress2,`braddress3`=:braddress3,`braddress4`=:braddress4,`braddress5`=:braddress5,`braddress6`=:braddress6,`lat`=:lat,`lng`=:lng,`estdeliverydays`=:estdeliverydays,`area`=:area,`salesman`=:salesman,`fwddate`=:fwddate,`phoneno`=:phoneno,`faxno`=:faxno,`contactname`=:contactname,`email`=:email,`defaultlocation`=:defaultlocation,`taxgroupid`=:taxgroupid,`defaultshipvia`=:defaultshipvia,`deliverblind`=:deliverblind,`disabletrans`=:disabletrans,`brpostaddr1`=:brpostaddr1,`brpostaddr2`=:brpostaddr2,`brpostaddr3`=:brpostaddr3,`brpostaddr4`=:brpostaddr4,`brpostaddr5`=:brpostaddr5,`brpostaddr6`=:brpostaddr6,`specialinstructions`=:specialinstructions,`custbranchcode`=:custbranchcode,`branchdistance`=:branchdistance,`travelrate`=:travelrate,`businessunit`=:businessunit,`emi`=:emi,`esd`=:esd,`branchsince`=:branchsince,`branchstatus`=:branchstatus,`tag`=:tag,`op_bal`=:op_bal,`aggrigate_cr`=:aggrigate_cr,`discount_amt`=:discount_amt WHERE `branchcode`=:branchcode');
        $updateCustbranch->bindParam(':branchcode', $branchcode, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':debtorno', $debtorno, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':brname', $brname, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':braddress1', $braddress1, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':braddress2', $braddress2, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':braddress3', $braddress3, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':braddress4', $braddress4, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':braddress5', $braddress5, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':braddress6', $braddress6, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':lat', $lat, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':lng', $lng, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':estdeliverydays', $estdeliverydays, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':area', $area, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':salesman', $salesman, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':fwddate', $fwddate, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':phoneno', $phoneno, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':faxno', $faxno, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':contactname', $contactname, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':email', $email, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':defaultlocation', $defaultlocation, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':taxgroupid', $taxgroupid, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':defaultshipvia', $defaultshipvia, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':deliverblind', $deliverblind, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':disabletrans', $disabletrans, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':brpostaddr1', $brpostaddr1, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':brpostaddr2', $brpostaddr2, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':brpostaddr3', $brpostaddr3, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':brpostaddr4', $brpostaddr4, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':brpostaddr5', $brpostaddr5, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':brpostaddr6', $brpostaddr6, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':specialinstructions', $specialinstructions, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':custbranchcode', $custbranchcode, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':branchdistance', $branchdistance, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':travelrate', $travelrate, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':businessunit', $businessunit, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':emi', $emi, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':esd', $esd, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':branchsince', $branchsince, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':branchstatus', $branchstatus, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':tag', $tag, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':op_bal', $op_bal, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':aggrigate_cr', $aggrigate_cr, PDO::PARAM_STR);
        $updateCustbranch->bindParam(':discount_amt', $discount_amt, PDO::PARAM_STR);
        $updateCustbranch->execute();
        $rowCount = $updateCustbranch->rowCount(0);
        if ($rowCount == 1) {
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->addMessage('Update success');
            $response->send();
            exit;
        } else {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("Nothing to be change");
            $response->send();
            exit();
        }
    } catch (CustBranchException $ex) {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        $response->addMessage($ex->getMessage());
        $response->send();
        exit();
    } catch (PDOException $ex) {
        error_log("Database query error." . $ex, 0);
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage($ex->getMessage());
        $response->send();
    }
} else {
    $response = new Response();
    $response->setHttpStatusCode(404);
    $response->setSuccess(false);
    $response->addMessage("Endpoint not found");
    $response->send();
    exit;
}
