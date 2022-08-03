<?php
require_once('orderModel.php');

try {
    $order = new Order(123132, "20/2/2022", 1);
    header('Content-type: application/json;charset=UTF-8');
    echo json_encode($category->returnOrderArray());
} catch (OrderException $ex) {
    echo "Error: " . $ex;
}
