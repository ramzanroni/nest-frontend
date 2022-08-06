<?php
include_once('../db.php');
include_once('../../model/admin/addProductModel.php');
include_once('../../model/response.php');

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
        $product = new AddProduct($jsonData->stockid, $jsonData->code, $jsonData->categoryid, $jsonData->description, $jsonData->longdescription, $jsonData->units, $jsonData->categoryid, $jsonData->webprice, $jsonData->img);

        if (count($jsonData->multipleImg) > 4) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("Multiple image not more then 4");
            $response->send();
            exit();
        }
        $stockid = $product->getStockid();
        $code = $product->getCode();
        $categoryid = $product->getCategoryid();
        $description = $product->getDescription();
        $longdescription = $product->getLongdescription();
        $units = $product->getUnits();
        $groupid = $product->getGroupid();
        $webprice = $product->getWebprice();
        $img = $product->getImg();
        $size = 0;
        $color = 0;
        $style = 0;
        $depreciation_act = 0;
        $portable = 0;
        $length = 0;
        $length_unit = 0;
        $giid = 0;
        $hs_code = '';
        $tarrif_value = 0;
        $wip_wtd_rate = 0;
        $pz = 0;
        $default_expiry_date = 0;
        $pzCons = 0;
        $mz = 0;
        $mzCons = 0;
        $min_cm = 0;
        $cm_base_price = 0;
        $min_inc = 0;
        $inc_base_price = 0;
        $cm_addi_price = 0;
        $inc_addi_price = 0;
        $per_carton_qty = 0;
        $cost_ = 0;
        $est_cost = 0;
        $parent_id = 0;
        $dia = '';
        $temp_bom_set = 0;
        try {
            $productInsert = $writeDB->prepare('INSERT INTO stockmaster(stockid, code, categoryid, description, longdescription, units, size,color, style,depreciation_act,portable,length,length_unit,giid,hs_code,tarrif_value,wip_wtd_rate,pz,default_expiry_date,pzCons,mz,mzCons,min_cm,cm_base_price,min_inc,inc_base_price,cm_addi_price,inc_addi_price,per_carton_qty,cost_, groupid, webprice, img,est_cost,parent_id,dia,temp_bom_set) VALUES (:stockid, :code, :categoryid, :description, :longdescription, :units,:size,:color,:style,:depreciation_act,:portable,:length,:length_unit,:giid, :hs_code,:tarrif_value, :wip_wtd_rate,:pz,:default_expiry_date,:pzCons,:mz,:mzCons,:min_cm,:cm_base_price,:min_inc,:inc_base_price,:cm_addi_price,:inc_addi_price,:per_carton_qty,:cost_, :groupid, :webprice, :img,:est_cost,:parent_id,:dia,:temp_bom_set)');

            $productInsert->bindParam(':stockid', $stockid, PDO::PARAM_STR);

            $productInsert->bindParam(':code', $code, PDO::PARAM_STR);

            $productInsert->bindParam(':categoryid', $categoryid, PDO::PARAM_STR);
            $productInsert->bindParam(':description', $description, PDO::PARAM_STR);
            $productInsert->bindParam(':longdescription', $longdescription, PDO::PARAM_STR);
            $productInsert->bindParam(':units', $units, PDO::PARAM_STR);
            $productInsert->bindParam(':size', $size, PDO::PARAM_STR);
            $productInsert->bindParam(':color', $size, PDO::PARAM_STR);
            $productInsert->bindParam(':style', $size, PDO::PARAM_STR);
            $productInsert->bindParam(':depreciation_act', $depreciation_act, PDO::PARAM_STR);
            $productInsert->bindParam(':portable', $portable, PDO::PARAM_STR);
            $productInsert->bindParam(':length', $length, PDO::PARAM_STR);
            $productInsert->bindParam(':length_unit', $length_unit, PDO::PARAM_STR);
            $productInsert->bindParam(':giid', $giid, PDO::PARAM_STR);
            $productInsert->bindParam(':hs_code', $hs_code, PDO::PARAM_STR);
            $productInsert->bindParam(':tarrif_value', $tarrif_value, PDO::PARAM_STR);
            $productInsert->bindParam(':wip_wtd_rate', $wip_wtd_rate, PDO::PARAM_STR);
            $productInsert->bindParam(':pz', $pz, PDO::PARAM_STR);
            $productInsert->bindParam(':default_expiry_date', $default_expiry_date, PDO::PARAM_STR);
            $productInsert->bindParam(':pzCons', $pzCons, PDO::PARAM_STR);
            $productInsert->bindParam(':mz', $mz, PDO::PARAM_STR);
            $productInsert->bindParam(':mzCons', $mzCons, PDO::PARAM_STR);
            $productInsert->bindParam(':min_cm', $min_cm, PDO::PARAM_STR);
            $productInsert->bindParam(':cm_base_price', $cm_base_price, PDO::PARAM_STR);
            $productInsert->bindParam(':min_inc', $min_inc, PDO::PARAM_STR);
            $productInsert->bindParam(':inc_base_price', $inc_base_price, PDO::PARAM_STR);
            $productInsert->bindParam(':cm_addi_price', $cm_addi_price, PDO::PARAM_STR);
            $productInsert->bindParam(':inc_addi_price', $inc_addi_price, PDO::PARAM_STR);
            $productInsert->bindParam(':per_carton_qty', $per_carton_qty, PDO::PARAM_STR);
            $productInsert->bindParam(':cost_', $cost_, PDO::PARAM_STR);
            $productInsert->bindParam(':groupid', $groupid, PDO::PARAM_STR);
            $productInsert->bindParam(':webprice', $webprice, PDO::PARAM_STR);
            $productInsert->bindParam(':img', $img, PDO::PARAM_STR);
            $productInsert->bindParam(':est_cost', $est_cost, PDO::PARAM_STR);
            $productInsert->bindParam(':parent_id', $parent_id, PDO::PARAM_STR);
            $productInsert->bindParam(':dia', $dia, PDO::PARAM_STR);
            $productInsert->bindParam(':temp_bom_set', $temp_bom_set, PDO::PARAM_STR);
            $productInsert->execute();

            // multiple image upload
            $multipleImage = $jsonData->multipleImg;
            foreach ($multipleImage as $image) {
                $imagename = $image->image;
                $addMultiImage = $writeDB->prepare('INSERT INTO item_ref_file(stockid, doc_name) VALUES (:stockid,:doc_name)');
                $addMultiImage->bindParam(':stockid', $stockid, PDO::PARAM_STR);
                $addMultiImage->bindParam(':doc_name', $imagename, PDO::PARAM_STR);
                $addMultiImage->execute();
            }
            $rowCount = $productInsert->rowCount();
            if ($rowCount === 1) {
                $response = new Response();
                $response->setHttpStatusCode(200);
                $response->setSuccess(true);
                $response->toCache(true);
                $response->addMessage("Product Add Success.");
                $response->send();
                exit();
            } else {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Product can't be added");
                $response->send();
                exit();
            }
        } catch (PDOException $ex) {
            error_log("Database query error." . $ex, 0);
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
            exit();
        } catch (ProductException $ex) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
            exit();
        }
    } catch (ProductException $ex) {
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
} else {
    $response = new Response();
    $response->setHttpStatusCode(405);
    $response->setSuccess(false);
    $response->addMessage("Request method not allowed.");
    $response->send();
    exit();
}
