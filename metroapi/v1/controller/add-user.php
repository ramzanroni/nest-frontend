<?php
include 'db-conn.php';


// $userName=$_POST['name'];
// $userPhone=$_POST['phone'];
$userName = "test";
$userPhone = '01516151212881';
// check number 
if ($userPhone == '') {
    echo "user phone cannot be null.";
    exit;
}
// chekc user 
$checkUser = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(*) as user FROM `debtorsmaster` WHERE phone1='" . $userPhone . "'"));
if ($checkUser['user'] == 1) {
    echo 'user already exist';
    exit;
}
// insert debtorsmaster
$userId = rand(1000, 9999);
$address = '';
$currcode = "BDT";
$salestype = "DP";
$holdreason = "1";
$paymentterms = "30";
$discount = "0";
$pymtdiscount = "0";
$lastpaid = "0";
$lastpaiddate = "0000-00-00 00:00:00";
$creditlimit = "10000";
$invaddrbranch = "0";
$discountcode = '';
$ediinvoices = "0";
$ediorders = "0";
$edireference = '';
$editransport = "email";
$customerpoline = "0";
$typeid = "1";
$custcatid1 = "0";
$op_bal = "0";
$created_at = "2022-06-30 17:09:09";
$updated_at = "0000-00-00 00:00:00";
$created_by = '0';
$updated_by = "0";
$status = "1";
$login_id = '1';
$phone2 = '';
$email = '';
$dateTime = date('Y-m-d H:i:s');
$bin_no = '';
$nid_no = '';
$user_token = base64_encode($userPhone . $dateTime);
$query = mysqli_query($conn, "INSERT INTO debtorsmaster(debtorno, cm_id, name, address1,login_id, currcode, salestype, clientsince, holdreason, paymentterms, discount, pymtdiscount, lastpaid, lastpaiddate, creditlimit, invaddrbranch, discountcode, ediinvoices, ediorders, edireference, editransport, customerpoline, typeid, custcatid1, op_bal, phone1,phone2,email, created_by, created_at, updated_at, updated_by, status,bin_no, nid_no, user_token)VALUES('$userId', '$userId', '$userName', '$address', '$login_id', '$currcode', '$salestype',  now(), '$holdreason', '$paymentterms', '$discount', '$pymtdiscount', '$lastpaid', '$lastpaiddate', '$creditlimit', '$invaddrbranch', '$discountcode', '$ediinvoices', '$ediorders', '$edireference', '$editransport', '$customerpoline', '$typeid', '$custcatid1', '$op_bal', '$userPhone','$phone2','$email', '$created_by', '$created_at', '$updated_at', '$updated_by', '$status','$bin_no', '$nid_no', '$user_token')");


// insert in contact master 
$bid = '';
$picture = '';
$queryContactMaster = mysqli_query($conn, "INSERT INTO contact_master( id, code, name, address1,address2, address3, address4, phone1,email,bid, picture, created_by, created_at, updated_by)VALUES( '$userId', '$userId', '$userName', '$address', '$address', '$address', '$address', '$userPhone','$email', '$bid', '$picture', '$created_by', CURRENT_TIMESTAMP(),'$updated_by')");


$type = "ecm";
$status = 1;
$queryUserType = mysqli_query($conn, "INSERT INTO user_type( user_id, type, status, create_at, create_by)VALUES( '$userId', '$type', '$status', now(), '$created_by')");


// cusrbrach insert 
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
$specialinstructions = '';
$queryCustbranch = mysqli_query($conn, "INSERT INTO custbranch( debtorno, brname, braddress1, lat, lng, estdeliverydays, AREA, salesman, fwddate, phoneno, defaultlocation, taxgroupid, defaultshipvia, deliverblind, disabletrans,specialinstructions, branchdistance, travelrate, businessunit, emi, esd, branchsince, branchstatus, tag, op_bal, aggrigate_cr, discount_amt )VALUES('$userId', '$brname', '$address', '$lat', '$lng', '$estdeliverydays', '$AREA', '$salesman', '$fwddate', '$userPhone', '$defaultlocation', '$taxgroupid', '$defaultshipvia', '$deliverblind', '$disabletrans', '$specialinstructions', '$branchdistance', '$travelrate', '$businessunit', '$emi', '$esd', now(), '$branchstatus', '$tag', '$op_bal', '$aggrigate_cr', '$discount_amt')");

if ($query == 1 && $queryContactMaster == 1 && $queryCustbranch == 1 && $queryUserType == 1) {
    echo 'true';
} else {
    echo "something is wrong";
}
