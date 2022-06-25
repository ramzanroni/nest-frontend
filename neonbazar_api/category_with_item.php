<?php
include 'connection.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$allHeaders = getallheaders();
	if ($allHeaders['Content-Type'] == 'application/json') {
		if (!empty($allHeaders['Authorization'])) {
			$apitoken = $allHeaders['Authorization'];
			$findAPI = "SELECT * FROM api WHERE api_key='$apitoken'";
			$findAPIStatement = $conn->prepare($findAPI);
			$findAPIStatement->execute();
			$findData = $findAPIStatement->rowCount();

			if ($findData == 1) {
				$ip_server = $_SERVER['SERVER_ADDR'] . "/" . "neonbazar_api/";
				$selectData = "SELECT * FROM `stockgroup` WHERE `web`!=0";
				$statement = $conn->prepare($selectData);
				$statement->execute();
				$result = $statement->fetchAll();
				$categoryArr = array();
				foreach ($result as $value) {
					$json_row['categoryName'] = $value['groupname'];
					$json_row['categoryID'] = $value['groupid'];
					$json_row['categoryImg'] = $ip_server . $value['image'];
					$selectData1 = "SELECT count(*) as 'total' FROM `stockmaster` WHERE category_id='{$value['groupid']}'";
					$statement1 = $conn->prepare($selectData1);
					$statement1->execute();
					$result1 = $statement1->fetchAll();
					$json_row['item'] = $result1[0]['total'];
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
