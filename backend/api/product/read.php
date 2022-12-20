<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Product.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate product object
$product = new Product($db);

// Product query
$result = $product->read();
// Get row count
$num = $result->rowCount();

// Check if any products
if($num > 0) {
    // Products array
    $products_arr = array();
    // $products_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $product_item = array(
            'id' => $id,
            'sku' => $sku,
            'name' => $name,
            'price' => $price,
            'type_name' => $type_name,
            'type_id' => $type_id,
            'size' => $size,
            'height' => $height,
            'width' => $width,
            'length' => $length,
            'weight' => $weight
        );

        // Push to array
        array_push($products_arr, $product_item);
    }

    // Turn to JSON & output
    http_response_code(200);
    echo json_encode($products_arr);

} else {
    // No Products
    http_response_code(503);
    echo json_encode(
        array('message' => 'No Products Found')
    );
}