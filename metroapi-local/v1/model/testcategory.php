<?php
require_once('categoryModel.php');

try {
    $category = new Category(1, "category name", "image gere", "item");
    header('Content-type: application/json;charset=UTF-8');
    echo json_encode($category->returnCategoryArray());
} catch (TaskException $ex) {
    echo "Error: " . $ex;
}
