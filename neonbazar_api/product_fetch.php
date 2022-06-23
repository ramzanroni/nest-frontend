<?php
include 'connection.php';

$API_Key = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE2MTg4OTU1MjIsImp0aSI6IlRQSTVmdFFUeU5MR1ZLenFOZlVhYThyRURpdEJkRmpIS0ErUGVFMTFjMTg9IiwiaXNzIjoicHVsc2VzZXJ2aWNlc2JkLmNvbSIsImRhdGEiOnsidXNlcklkIjoiMjg4MTUiLCJ1c2VyTGV2ZWwiOjJ9fQ.wQ5AQR-fIGRZgt3CN9-W6v4PkvTIvNVP8HzCOiHHeKwcd8NT1R1Dxz_XpJH9jOa7CsDzCYBklEPRtQus11NiEQ";

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$allHeaders = getallheaders();
	if ($allHeaders['Content-Type'] == 'application/json') {
		if (!empty($allHeaders['Authorization'])) {
			if ($API_Key == $allHeaders['Authorization']) {
				$ip_server = $_SERVER['SERVER_ADDR'] . "/" . "neonbazar_api/";
				$selectProduct = "SELECT stockmaster.stockid AS 'stockid', stockmaster.description AS 'description', stockmaster.`longdescription` AS 'longdescription', stockmaster.`units` AS 'units', stockmaster.`discountcategory` AS 'discountcategory', stockmaster.`taxcatid` AS 'taxcatid', stockmaster.`webprice` AS 'webprice', stockmaster.`img` AS 'img', stockmaster.status AS 'status', stockgroup.groupname AS 'category', stockgroup.groupid AS 'categoryId' FROM stockmaster INNER JOIN stockgroup ON stockmaster.category_id = stockgroup.groupid;";
				$statement = $conn->prepare($selectProduct);
				$statement->execute();
				$result = $statement->fetchAll();
				foreach ($result as $value) {
					$json_row['stockid'] = $value['stockid'];
					$json_row['description'] = $value['description'];
					$json_row['category_id'] = $value['categoryId'];
					$json_row['category'] = $value['category'];
					$json_row['longdescription'] = $value['longdescription'];
					$json_row['units'] = $value['units'];
					$json_row['discountcategory'] = $value['discountcategory'];
					$json_row['taxcatid'] = $value['taxcatid'];
					$json_row['webprice'] = $value['webprice'];
					$json_row['img'] = $ip_server . $value['img'];
					$json_row['status'] = $value['status'];
					$json[] = $json_row;
				}
				echo json_encode($json);
			} else {
				echo json_encode(
					array('message' => 'Authentication Failed...')
				);
			}
		} else {
			echo json_encode(
				array('message' => 'Authentication Required')
			);
		}
	} else {
		echo json_encode(
			array('message' => 'Content Type Not Allowed')
		);
	}
} else {
	echo json_encode(
		array('message' => 'Request Type Not Allowed')
	);
}