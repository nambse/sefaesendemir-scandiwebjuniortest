<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Product.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate product object
$product = new Product($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Set product ID(s)
$product->id = $data->id;

// Delete product
if ($product->delete()) {
    http_response_code(200);
    echo json_encode(array("message" => "Product(s) were deleted."));
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Unable to delete product(s)."));
}