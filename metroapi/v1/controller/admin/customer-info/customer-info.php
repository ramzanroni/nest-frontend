<?php


include_once('../../db.php');
include_once('../../../model/admin/customer-info/customer-infoModel.php');
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
        $debtorInfo = new DebtorMaster($jsonData->debtorno, $jsonData->cm_id, $jsonData->name, $jsonData->address1, $jsonData->address2, $jsonData->address3, $jsonData->address4, $jsonData->address5, $jsonData->address6, $jsonData->login_media, $jsonData->login_id, $jsonData->currcode, $jsonData->salestype, $jsonData->$clientsince, $jsonData->holdreason, $jsonData->paymentterms, $jsonData->discount, $jsonData->pymtdiscount, $jsonData->lastpaid, $jsonData->lastpaiddate, $jsonData->creditlimit, $jsonData->invaddrbranch, $jsonData->discountcode, $jsonData->ediinvoices, $jsonData->ediorders, $jsonData->edireference, $jsonData->editransport, $jsonData->ediaddress, $jsonData->ediserveruser, $jsonData->ediserverpwd, $jsonData->taxref, $jsonData->customerpoline, $jsonData->typeid, $jsonData->customer_note, $jsonData->custcatid1, $jsonData->op_bal, $jsonData->phone1, $jsonData->phone2, $jsonData->email, $jsonData->created_by, $jsonData->created_at, $jsonData->updated_at, $jsonData->updated_by, $jsonData->status, $jsonData->bin_no, $jsonData->nid_no, $jsonData->user_token);
        $debtorno = $debtorInfo->getDebtorno();
        $cm_id = $debtorInfo->getCm_id();
        $name = $debtorInfo->getName();
        $address1 = $debtorInfo->getAddress1();
        $address2 = $debtorInfo->getAddress2();
        $address3 = $debtorInfo->getAddress3();
        $address4 = $debtorInfo->getAddress4();
        $address5 = $debtorInfo->getAddress5();
        $address6 = $debtorInfo->getAddress6();
        $login_media = $debtorInfo->getLogin_media();
        $login_id = $debtorInfo->getLogin_id();
        $currcode = $debtorInfo->getCurrcode();
        $salestype = $debtorInfo->getSalestype();
        $clientsince = $debtorInfo->getClientsince();
        $holdreason = $debtorInfo->getHoldreason();
        $paymentterms = $debtorInfo->getPaymentterms();
        $discount = $debtorInfo->getDiscount();
        $pymtdiscount = $debtorInfo->getPymtdiscount();
        $lastpaid = $debtorInfo->getLastpaid();
        $lastpaiddate = $debtorInfo->getLastpaiddate();
        $creditlimit = $debtorInfo->getCreditlimit();
        $invaddrbranch = $debtorInfo->getInvaddrbranch();
        $discountcode = $debtorInfo->getDiscountcode();
        $ediinvoices = $debtorInfo->getEdiinvoices();
        $ediorders = $debtorInfo->getEdiorders();
        $edireference = $debtorInfo->getEdireference();
        $editransport = $debtorInfo->getEditransport();
        $ediaddress = $debtorInfo->getEdiaddress();
        $ediserveruser = $debtorInfo->getEdiserveruser();
        $ediserverpwd = $debtorInfo->getEdiserverpwd();
        $taxref = $debtorInfo->getTaxref();
        $customerpoline = $debtorInfo->getCustomerpoline();
        $typeid = $debtorInfo->getTypeid();
        $customer_note = $debtorInfo->getCustomer_note();
        $custcatid1 = $debtorInfo->getCustcatid1();
        $op_bal = $debtorInfo->getOp_bal();
        $phone1 = $debtorInfo->getPhone1();
        $phone2 = $debtorInfo->getPhone2();
        $email = $debtorInfo->getEmail();
        $created_by = $debtorInfo->getCreated_by();
        $created_at = $debtorInfo->getCreated_at();
        $updated_at = $debtorInfo->getUpdated_at();
        $updated_by = $debtorInfo->getUpdated_by();
        $status = $debtorInfo->getStatus();
        $bin_no = $debtorInfo->getBin_no();
        $nid_no = $debtorInfo->getNid_no();
        $user_token = $debtorInfo->getUser_token();
        // check users 
        $checkUser = $readDB->prepare('SELECT * FROM debtorsmaster WHERE debtorno=:debtorno');
        $checkUser->bindParam(':debtorno', $debtorno, PDO::PARAM_STR);
        $checkUser->execute();
        $rowCount = $checkUser->rowCount();
        if ($rowCount > 0) {
            $response = new Response();
            $response->setHttpStatusCode(404);
            $response->setSuccess(false);
            $response->addMessage("This id Already exist in our database");
            $response->send();
            exit;
        }
        $query = "INSERT INTO debtorsmaster(debtorno, cm_id, name, address1,address2,address3,address4,address5,address6, login_id, currcode, salestype, clientsince, holdreason, paymentterms, discount, pymtdiscount, lastpaid, lastpaiddate, creditlimit, invaddrbranch, discountcode, ediinvoices, ediorders, edireference, editransport, customerpoline, typeid, custcatid1, op_bal, phone1, phone2, email, created_by, created_at, updated_at, updated_by, status, bin_no, nid_no, user_token)VALUES(:debtorno, :cm_id, :name, :address1, :address2, :address3, :address4, :address5, :address6, :login_id, :currcode, :salestype, :clientsince, :holdreason, :paymentterms, :discount, :pymtdiscount, :lastpaid, :lastpaiddate, :creditlimit, :invaddrbranch, :discountcode, :ediinvoices, :ediorders, :edireference, :editransport, :customerpoline, :typeid, :custcatid1, :op_bal, :phone1, :phone2, :email, :created_by, :created_at, :updated_at, :updated_by, :status, :bin_no, :nid_no, :user_token
        )";

        $insertDebtorStmt = $writeDB->prepare($query);
        $insertDebtorStmt->bindParam(':debtorno', $debtorno, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':cm_id', $cm_id, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':name', $name, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':address1', $address1, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':address2', $address2, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':address3', $address3, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':address4', $address4, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':address5', $address5, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':address6', $address6, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':login_id', $login_id, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':currcode', $currcode, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':salestype', $salestype, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':clientsince', $clientsince, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':holdreason', $holdreason, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':paymentterms', $paymentterms, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':discount', $discount, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':pymtdiscount', $pymtdiscount, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':lastpaid', $lastpaid, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':lastpaiddate', $lastpaiddate, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':creditlimit', $creditlimit, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':invaddrbranch', $invaddrbranch, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':discountcode', $discountcode, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':ediinvoices', $ediinvoices, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':ediorders', $ediorders, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':edireference', $edireference, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':editransport', $editransport, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':customerpoline', $customerpoline, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':typeid', $typeid, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':custcatid1', $custcatid1, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':op_bal', $op_bal, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':phone1', $phone1, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':phone2', $phone2, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':email', $email, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':created_by', $created_by, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':created_at', $created_at, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':updated_at', $updated_at, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':updated_by', $updated_by, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':status', $status, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':bin_no', $bin_no, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':nid_no', $nid_no, PDO::PARAM_STR);
        $insertDebtorStmt->bindParam(':user_token', $user_token, PDO::PARAM_STR);
        $insertDebtorStmt->execute();
        $rowCount = $insertDebtorStmt->rowCount();
        if ($rowCount === 1) {
            $brname = "main";
            $lat = "0.000000";
            $lng = "0.000000";
            $estdeliverydays = "0";
            $AREA = "2780";
            $salesman = '1';
            $fwddate = "0";
            $defaultlocation = "1010";
            $taxgroupid = "1";
            $defaultshipvia = "1";
            $deliverblind = "1";
            $disabletrans = "0";
            $specialinstructions = "";
            $branchdistance = "0.00";
            $travelrate = "0.00";
            $businessunit = "1";
            $emi = "0";
            $esd = "0000-00-00";
            $branchstatus = "1";
            $tag = "1";
            $op_bal = "0";
            $aggrigate_cr = "1";
            $discount_amt = "0";
            $query = "INSERT INTO custbranch(debtorno, brname, braddress1,braddress2,braddress3,braddress4,braddress5,braddress6, lat, lng, estdeliverydays, AREA, salesman, fwddate, phoneno, defaultlocation, taxgroupid, defaultshipvia, deliverblind, disabletrans, specialinstructions, branchdistance, travelrate, businessunit, emi, esd, branchsince, branchstatus, tag, op_bal, aggrigate_cr, discount_amt)VALUES(:debtorno,:brname,:braddress1,:braddress2,:braddress3,:braddress4,:braddress5,:braddress6,:lat,:lng,:estdeliverydays,:AREA,:salesman,:fwddate,:phoneno,:defaultlocation,:taxgroupid,:defaultshipvia,:deliverblind,:disabletrans,:specialinstructions,:branchdistance,:travelrate,:businessunit,:emi,:esd,now(),:branchstatus,:tag,:op_bal,:aggrigate_cr,:discount_amt)";
            $insertBranchStmt = $writeDB->prepare($query);
            $insertBranchStmt->bindParam(':debtorno', $debtorno, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':brname', $brname, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':braddress1', $address1, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':braddress2', $address2, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':braddress3', $address3, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':braddress4', $address4, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':braddress5', $address5, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':braddress6', $address6, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':lat', $lat, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':lng', $lng, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':estdeliverydays', $estdeliverydays, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':AREA', $AREA, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':salesman', $salesman, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':fwddate', $fwddate, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':phoneno', $phone1, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':defaultlocation', $defaultlocation, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':taxgroupid', $taxgroupid, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':defaultshipvia', $defaultshipvia, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':deliverblind', $deliverblind, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':disabletrans', $disabletrans, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':specialinstructions', $specialinstructions, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':branchdistance', $branchdistance, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':travelrate', $travelrate, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':businessunit', $businessunit, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':emi', $emi, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':esd', $esd, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':branchstatus', $branchstatus, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':tag', $tag, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':op_bal', $op_bal, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':aggrigate_cr', $aggrigate_cr, PDO::PARAM_STR);
            $insertBranchStmt->bindParam(':discount_amt', $discount_amt, PDO::PARAM_STR);
            $insertBranchStmt->execute();
            $rowCount = $insertBranchStmt->rowCount();
            if ($rowCount === 1) {
                $response = new Response();
                $response->setHttpStatusCode(200);
                $response->setSuccess(true);
                $response->toCache(true);
                $response->addMessage("Customer Information Added Success.");
                $response->send();
                exit();
            }
        }
    } catch (CustomerException $ex) {
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
    if (array_key_exists('debtorno', $_GET)) {
        $debtorno = $_GET['debtorno'];
        if ($debtorno == '' || !is_numeric($debtorno)) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage('debtorno number be numeric and not null.');
            $response->send();
            exit();
        }
        $result = $readDB->prepare('SELECT * FROM debtorsmaster WHERE debtorno=:debtorno');
        $result->bindParam(':debtorno', $debtorno, PDO::PARAM_STR);
        $result->execute();
    } elseif (array_key_exists('phone', $_GET)) {
        $phone = $_GET['phone'];
        if ($phone == '' || !is_numeric($phone)) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage('Phone number be numeric and not null.');
            $response->send();
            exit();
        }
        $result = $readDB->prepare('SELECT * FROM debtorsmaster WHERE phone1=:phone');
        $result->bindParam(':phone', $phone, PDO::PARAM_STR);
        $result->execute();
    } elseif (array_key_exists('name', $_GET)) {
        $name = $_GET['name'];
        if ($name == '') {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage('Name can not be null.');
            $response->send();
            exit();
        }
        $subQry = "SELECT * FROM debtorsmaster WHERE ";
        $textsearchQury = '';

        $searchKeywordList = explode(' ', $name);

        foreach ($searchKeywordList as $searchKey) {
            $textsearchQury .= "name LIKE '%" . $searchKey . "%' OR ";
        }
        $textsearchQury = $subQry . rtrim($textsearchQury, 'OR ');
        $result = $readDB->prepare($textsearchQury);
        $result->execute();
    } else {
        $result = $readDB->prepare('SELECT * FROM debtorsmaster');
        $result->execute();
    }
    $rowCount = $result->rowCount();
    if ($rowCount == 0) {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        $response->addMessage('No Data found.');
        $response->send();
        exit();
    } else {
        $customerinfoArr = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $customerInfo = new DebtorMaster($row['debtorno'], $row['cm_id'], $row['name'], $row['address1'], $row['address2'], $row['address3'], $row['address3'], $row['address4'], $row['address5'], $row['address6'], $row['login_id'], $row['currcode'], $row['salestype'], $row['$clientsince'], $row['holdreason'], $row['paymentterms'], $row['discount'], $row['pymtdiscount'], $row['lastpaid'], $row['lastpaiddate'], $row['creditlimit'], $row['invaddrbranch'], $row['discountcode'], $row['ediinvoices'], $row['ediorders'], $row['edireference'], $row['editransport'], $row['ediaddress'], $row['ediserveruser'], $row['ediserverpwd'], $row['taxref'], $row['customerpoline'], $row['typeid'], $row['customer_note'], $row['custcatid1'], $row['op_bal'], $row['phone1'], $row['phone2'], $row['email'], $row['created_by'], $row['created_at'], $row['updated_at'], $row['updated_by'], $row['status'], $row['bin_no'], $row['nid_no'], $row['user_token']);
            $customerinfoArr[] = $customerInfo->returnCustomerInfoArray();
        }
        $returnArray = array();
        $returnArray['rows_returned'] = $rowCount;
        $returnArray['customer-info'] = $customerinfoArr;
        $response = new Response();
        $response->setHttpStatusCode(200);
        $response->toCache(true);
        $response->setSuccess(true);
        $response->setData($returnArray);
        $response->send();
        exit;
    }
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
        $debtorInfo = new DebtorMaster($jsonData->debtorno, $jsonData->cm_id, $jsonData->name, $jsonData->address1, $jsonData->address2, $jsonData->address3, $jsonData->address4, $jsonData->address5, $jsonData->address6, $jsonData->login_media, $jsonData->login_id, $jsonData->currcode, $jsonData->salestype, $jsonData->$clientsince, $jsonData->holdreason, $jsonData->paymentterms, $jsonData->discount, $jsonData->pymtdiscount, $jsonData->lastpaid, $jsonData->lastpaiddate, $jsonData->creditlimit, $jsonData->invaddrbranch, $jsonData->discountcode, $jsonData->ediinvoices, $jsonData->ediorders, $jsonData->edireference, $jsonData->editransport, $jsonData->ediaddress, $jsonData->ediserveruser, $jsonData->ediserverpwd, $jsonData->taxref, $jsonData->customerpoline, $jsonData->typeid, $jsonData->customer_note, $jsonData->custcatid1, $jsonData->op_bal, $jsonData->phone1, $jsonData->phone2, $jsonData->email, $jsonData->created_by, $jsonData->created_at, $jsonData->updated_at, $jsonData->updated_by, $jsonData->status, $jsonData->bin_no, $jsonData->nid_no, $jsonData->user_token);
        $debtorno = $debtorInfo->getDebtorno();
        $cm_id = $debtorInfo->getCm_id();
        $name = $debtorInfo->getName();
        $address1 = $debtorInfo->getAddress1();
        $address2 = $debtorInfo->getAddress2();
        $address3 = $debtorInfo->getAddress3();
        $address4 = $debtorInfo->getAddress4();
        $address5 = $debtorInfo->getAddress5();
        $address6 = $debtorInfo->getAddress6();
        $login_media = $debtorInfo->getLogin_media();
        $login_id = $debtorInfo->getLogin_id();
        $currcode = $debtorInfo->getCurrcode();
        $salestype = $debtorInfo->getSalestype();
        $clientsince = $debtorInfo->getClientsince();
        $holdreason = $debtorInfo->getHoldreason();
        $paymentterms = $debtorInfo->getPaymentterms();
        $discount = $debtorInfo->getDiscount();
        $pymtdiscount = $debtorInfo->getPymtdiscount();
        $lastpaid = $debtorInfo->getLastpaid();
        $lastpaiddate = $debtorInfo->getLastpaiddate();
        $creditlimit = $debtorInfo->getCreditlimit();
        $invaddrbranch = $debtorInfo->getInvaddrbranch();
        $discountcode = $debtorInfo->getDiscountcode();
        $ediinvoices = $debtorInfo->getEdiinvoices();
        $ediorders = $debtorInfo->getEdiorders();
        $edireference = $debtorInfo->getEdireference();
        $editransport = $debtorInfo->getEditransport();
        $ediaddress = $debtorInfo->getEdiaddress();
        $ediserveruser = $debtorInfo->getEdiserveruser();
        $ediserverpwd = $debtorInfo->getEdiserverpwd();
        $taxref = $debtorInfo->getTaxref();
        $customerpoline = $debtorInfo->getCustomerpoline();
        $typeid = $debtorInfo->getTypeid();
        $customer_note = $debtorInfo->getCustomer_note();
        $custcatid1 = $debtorInfo->getCustcatid1();
        $op_bal = $debtorInfo->getOp_bal();
        $phone1 = $debtorInfo->getPhone1();
        $phone2 = $debtorInfo->getPhone2();
        $email = $debtorInfo->getEmail();
        $created_by = $debtorInfo->getCreated_by();
        $created_at = $debtorInfo->getCreated_at();
        $updated_at = $debtorInfo->getUpdated_at();
        $updated_by = $debtorInfo->getUpdated_by();
        $status = $debtorInfo->getStatus();
        $bin_no = $debtorInfo->getBin_no();
        $nid_no = $debtorInfo->getNid_no();
        $user_token = $debtorInfo->getUser_token();
        // check users 
        $checkUser = $readDB->prepare('SELECT * FROM debtorsmaster WHERE debtorno=:debtorno');
        $checkUser->bindParam(':debtorno', $debtorno, PDO::PARAM_STR);
        $checkUser->execute();
        $rowCount = $checkUser->rowCount();
        if ($rowCount == 0) {
            $response = new Response();
            $response->setHttpStatusCode(404);
            $response->setSuccess(false);
            $response->addMessage("Data not found");
            $response->send();
            exit;
        }
        $query = "UPDATE debtorsmaster SET cm_id=:cm_id,name=:name,address1=:address1,address2=:address2,address3=:address3,address4=:address4,address5=:address5,address6=:address6,login_media=:login_media,login_id=:login_id,currcode=:currcode,salestype=:salestype,clientsince=:clientsince,holdreason=:holdreason,paymentterms=:paymentterms,discount=:discount,pymtdiscount=:pymtdiscount,lastpaid=:lastpaid,lastpaiddate=:lastpaiddate,creditlimit=:creditlimit,invaddrbranch=:invaddrbranch, discountcode=:discountcode,ediinvoices=:ediinvoices,ediorders=:ediorders,edireference=:edireference,editransport=:editransport,ediaddress=:ediaddress,ediserveruser=:ediserveruser,ediserverpwd=:ediserverpwd,taxref=:taxref,customerpoline=:customerpoline ,typeid=:typeid,customer_note=:customer_note,custcatid1=:custcatid1,op_bal=:op_bal,phone1=:phone1,phone2=:phone2,email=:email ,created_by=:created_by,created_at=:created_at,updated_at=:updated_at,updated_by=:updated_by,status=:status,bin_no=:bin_no,nid_no=:nid_no,user_token=:user_token WHERE debtorno=:debtorno";
        $updateDebtorStmt = $writeDB->prepare($query);
        $updateDebtorStmt->bindParam(':debtorno', $debtorno, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':cm_id', $cm_id, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':name', $name, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':address1', $address1, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':address2', $address2, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':address3', $address3, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':address4', $address4, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':address5', $address5, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':address6', $address6, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':address6', $address6, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':login_media', $login_media, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':login_id', $login_id, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':currcode', $currcode, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':salestype', $salestype, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':clientsince', $clientsince, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':holdreason', $holdreason, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':paymentterms', $paymentterms, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':discount', $discount, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':pymtdiscount', $pymtdiscount, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':lastpaid', $lastpaid, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':lastpaiddate', $lastpaiddate, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':creditlimit', $creditlimit, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':invaddrbranch', $invaddrbranch, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':discountcode', $discountcode, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':ediinvoices', $ediinvoices, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':ediorders', $ediorders, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':edireference', $edireference, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':editransport', $editransport, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':ediaddress', $ediaddress, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':ediserveruser', $ediserveruser, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':ediserverpwd', $ediserverpwd, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':taxref', $taxref, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':customerpoline', $customerpoline, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':typeid', $typeid, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':customer_note', $customer_note, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':custcatid1', $custcatid1, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':op_bal', $op_bal, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':phone1', $phone1, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':phone2', $phone2, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':email', $email, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':created_by', $created_by, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':created_at', $created_at, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':updated_at', $updated_at, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':updated_by', $updated_by, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':status', $status, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':bin_no', $bin_no, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':nid_no', $nid_no, PDO::PARAM_STR);
        $updateDebtorStmt->bindParam(':user_token', $user_token, PDO::PARAM_STR);
        $updateDebtorStmt->execute();
        $rowCount = $updateDebtorStmt->rowCount();
        if ($rowCount == 1) {
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->addMessage("Update success.");
            $response->send();
            exit;
        } else {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("Nothing to be updated");
            $response->send();
            exit;
        }
    } catch (CustomerException $ex) {
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
