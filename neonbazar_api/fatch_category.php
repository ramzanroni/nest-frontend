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
				$selectData = "SELECT * FROM `stockgroup` WHERE `web`!=0";
				$statement = $conn->prepare($selectData);
				$statement->execute();
				$result = $statement->fetchAll();
				$categoryArr = array();
				foreach ($result as $value) {
					$json_row['categoryID'] = $value['groupid'];
					$json_row['categoryName'] = $value['groupname'];
					$json_row['categoryImg'] = $ip_server . $value['image'];
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

// $selectData="SELECT * FROM `stockgroup`";
// $statement=$conn->prepare($selectData);
// $statement->execute();
// $result=$statement->fetchAll();
// $categoryArr=array();
// foreach ($result as $value) {
//     $json_row['categoryName'] = $value['groupname'];
// 	$json[] = $json_row;
// }
// print_r($json);