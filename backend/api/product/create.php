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

$product->sku = $data->sku;
$product->name = $data->name;
$product->type_id = $data->type_id;
$product->price = $data->price;
$product->size = $data->size;
$product->height = $data->height;
$product->width = $data->width;
$product->length = $data->length;
$product->weight = $data->weight;



// Create product
if($product->create()) {
    http_response_code(200);
    echo json_encode(
        array('message' => 'Product Created')
    );
} else {
    http_response_code(503);
    echo json_encode(
        array('message' => 'Product Not Created')
    );
}