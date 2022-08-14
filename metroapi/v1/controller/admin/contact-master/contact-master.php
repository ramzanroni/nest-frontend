<?php

use LDAP\Result;

include_once('../../db.php');
include_once('../../../model/admin/contact-master/contact-masterModel.php');
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
        $contact = new ContactMaster(null, $jsonData->code, $jsonData->name, $jsonData->address1, $jsonData->address2, $jsonData->address3, $jsonData->address4, $jsonData->phone1, $jsonData->email, $jsonData->bid, $jsonData->picture, $jsonData->created_by, null, null);

        $code = $contact->getCode();
        $name = $contact->getName();
        $address1 = $contact->getAddress1();
        $address2 = $contact->getAddress2();
        $address3 = $contact->getAddress3();
        $address4 = $contact->getAddress4();
        $phone1 = $contact->getPhone1();
        $email = $contact->getEmail();
        $bid = $contact->getBid();
        $picture = $contact->getPicture();
        $created_by = $contact->getCreated_by();

        // check phone 
        $checkPhone = $readDB->prepare('SELECT * FROM contact_master WHERE phone1=:phone1');
        $checkPhone->bindParam(':phone1', $phone1, PDO::PARAM_STR);
        $checkPhone->execute();
        $rowCount = $checkPhone->rowCount();
        if ($rowCount > 0) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage('This phone number already exist in our database.');
            $response->send();
            exit;
        }
        // add contact

        try {
            $updated_by = 0;
            $insertContact = $writeDB->prepare('INSERT INTO contact_master(code, name, address1, address2, address3, address4, phone1, email, bid, picture, created_by,updated_by) VALUES (:code,:name,:address1,:address2,:address3,:address4,:phone1,:email,:bid,:picture,:created_by,:updated_by)');
            $insertContact->bindParam(':code', $code, PDO::PARAM_STR);
            $insertContact->bindParam(':name', $name, PDO::PARAM_STR);
            $insertContact->bindParam(':address1', $address1, PDO::PARAM_STR);
            $insertContact->bindParam(':address2', $address2, PDO::PARAM_STR);
            $insertContact->bindParam(':address3', $address3, PDO::PARAM_STR);
            $insertContact->bindParam(':address4', $address4, PDO::PARAM_STR);
            $insertContact->bindParam(':phone1', $phone1, PDO::PARAM_STR);
            $insertContact->bindParam(':email', $email, PDO::PARAM_STR);
            $insertContact->bindParam(':bid', $bid, PDO::PARAM_STR);
            $insertContact->bindParam(':picture', $picture, PDO::PARAM_STR);
            $insertContact->bindParam(':created_by', $created_by, PDO::PARAM_STR);
            $insertContact->bindParam(':updated_by', $updated_by, PDO::PARAM_STR);

            $insertContact->execute();
            $rowCount = $insertContact->rowCount();
            if ($rowCount === 1) {
                $response = new Response();
                $response->setHttpStatusCode(200);
                $response->setSuccess(true);
                $response->toCache(true);
                $response->addMessage('Contact information added success.');
                $response->send();
                exit;
            } else {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage('Contact information can not be added. Somthing is wrong please try again');
                $response->send();
                exit;
            }
        } catch (ContactException $ex) {
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
            exit();
        }
    } catch (ContactException $ex) {
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
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === "PUT") {
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
        $currentTime = date('Y-m-d H:i:s');
        $contact = new ContactMaster($jsonData->id, $jsonData->code, $jsonData->name, $jsonData->address1, $jsonData->address2, $jsonData->address3, $jsonData->address4, $jsonData->phone1, $jsonData->email, $jsonData->bid, $jsonData->picture, null, $jsonData->updated_by, $currentTime);

        $id = $contact->getId();
        $code = $contact->getCode();
        $name = $contact->getName();
        $address1 = $contact->getAddress1();
        $address2 = $contact->getAddress2();
        $address3 = $contact->getAddress3();
        $address4 = $contact->getAddress4();
        $phone1 = $contact->getPhone1();
        $email = $contact->getEmail();
        $bid = $contact->getBid();
        $picture = $contact->getPicture();
        $updated_by = $contact->getUpdated_by();
        $updated_at = $contact->getUpdated_at();

        // check data 
        $checkID = $readDB->prepare('SELECT * FROM contact_master WHERE id=:id');
        $checkID->bindParam(':id', $id, PDO::PARAM_STR);
        $checkID->execute();
        $rowCount = $checkID->rowCount();
        if ($rowCount != 1) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("Could not find this id in our database.");
            $response->send();
            exit();
        }
        // checkPhone number 
        $checkNumber = $readDB->prepare('SELECT * FROM contact_master WHERE id !=:id AND phone1=:phone1');
        $checkNumber->bindParam(':id', $id, PDO::PARAM_STR);
        $checkNumber->bindParam(':phone1', $phone1, PDO::PARAM_STR);
        $checkNumber->execute();
        $rowCount = $checkNumber->rowCount();
        if ($rowCount > 0) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage('This number already use by someone.');
            $response->send();
            exit();
        }
        // update information 
        $updateContact = $writeDB->prepare('UPDATE contact_master SET code=:code,name=:name,address1=:address1,address2=:address2,address3=:address3,address4=:address4,phone1=:phone1,email=:email,bid=:bid,picture=:picture,updated_by=:updated_by,updated_at=:updated_at WHERE id=:id');
        $updateContact->bindParam(':code', $code, PDO::PARAM_STR);
        $updateContact->bindParam(':name', $name, PDO::PARAM_STR);
        $updateContact->bindParam(':address1', $address1, PDO::PARAM_STR);
        $updateContact->bindParam(':address2', $address2, PDO::PARAM_STR);
        $updateContact->bindParam(':address3', $address3, PDO::PARAM_STR);
        $updateContact->bindParam(':address4', $address4, PDO::PARAM_STR);
        $updateContact->bindParam(':phone1', $phone1, PDO::PARAM_STR);
        $updateContact->bindParam(':email', $email, PDO::PARAM_STR);
        $updateContact->bindParam(':bid', $bid, PDO::PARAM_STR);
        $updateContact->bindParam(':picture', $picture, PDO::PARAM_STR);
        $updateContact->bindParam(':updated_by', $updated_by, PDO::PARAM_STR);
        $updateContact->bindParam(':updated_at', $updated_at, PDO::PARAM_STR);
        $updateContact->bindParam(':id', $id, PDO::PARAM_STR);
        $updateContact->execute();
        $rowCount = $updateContact->rowCount();
        if ($rowCount == 1) {
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->addMessage("Contact information update success");
            $response->send();
            exit();
        }
    } catch (ContactException $ex) {
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
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (array_key_exists('id', $_GET)) {
        $id = $_GET['id'];
        if ($id == '' || !is_numeric($id)) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage('Contact id number be numeric and not null.');
            $response->send();
            exit();
        }
        $selectQuery = $readDB->prepare('SELECT * FROM contact_master WHERE id=:id');
        $selectQuery->bindParam(':id', $id, PDO::PARAM_STR);
        $selectQuery->execute();
    } elseif (array_key_exists('phone', $_GET)) {
        $phone = $_GET['phone'];
        if ($phone == '' || !is_numeric($phone)) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage('Contact phone number be numeric and not null.');
            $response->send();
            exit();
        }
        $selectQuery = $readDB->prepare('SELECT * FROM contact_master WHERE phone1=:phone');
        $selectQuery->bindParam(':phone', $phone, PDO::PARAM_STR);
        $selectQuery->execute();
    } elseif (array_key_exists('name', $_GET)) {
        $name = $_GET['name'];
        if ($name === '') {
            $response = new Response();
            $response->setHttpStatusCode(403);
            $response->setSuccess(false);
            $response->addMessage('Name missing its not be null. ');
            $response->send();
            exit();
        }
        $subQry = "SELECT * FROM contact_master WHERE ";
        $textsearchQury = '';

        $searchKeywordList = explode(' ', $name);

        foreach ($searchKeywordList as $searchKey) {
            $textsearchQury .= "name LIKE '%" . $searchKey . "%' OR ";
        }
        $textsearchQury = $subQry . rtrim($textsearchQury, 'OR ');
        $selectQuery = $readDB->prepare($textsearchQury);
        $selectQuery->execute();
    } else {

        $selectQuery = $readDB->prepare('SELECT * FROM contact_master');
        $selectQuery->execute();
    }
    $rowCount = $selectQuery->rowCount();
    if ($rowCount == 0) {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        $response->addMessage('No Data found.');
        $response->send();
        exit();
    }
    $contactArray = array();
    while ($row = $selectQuery->fetch(PDO::FETCH_ASSOC)) {
        $contact = new ContactMaster($row['id'], $row['code'], $row['name'], $row['address1'], $row['address2'], $row['address3'], $row['address4'], $row['phone1'], $row['email'], $row['bid'], $row['picture'], $row['created_by'], $row['updated_by'], $row['updated_at']);
        $contactArray[] = $contact->returnContactArray();
    }
    $returnArray = array();
    $returnArray['rows_returned'] = $rowCount;
    $returnArray['contact'] = $contactArray;
    $response = new Response();
    $response->setHttpStatusCode(200);
    $response->toCache(true);
    $response->setSuccess(true);
    $response->setData($returnArray);
    $response->send();
    exit;
} else {
    $response = new Response();
    $response->setHttpStatusCode(404);
    $response->setSuccess(false);
    $response->addMessage("Endpoint not found");
    $response->send();
    exit;
}
