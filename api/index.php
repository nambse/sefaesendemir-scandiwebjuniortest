<?php

spl_autoload_register(function ($class) {
    require __DIR__ . "/src/$class.php";
});

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, DELETE, OPTIONS");
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods');


$parts = explode("/", $_SERVER["REQUEST_URI"]);
$data = json_decode(file_get_contents("php://input"));

//This part should be updated with respect to URL Structure
if ($parts[1] != "api") {
    http_response_code(404);
    exit;
}

$method = $_SERVER["REQUEST_METHOD"];

//To prevent CORS error
if($method == "OPTIONS")
{
    http_response_code(200);
    exit;
}
else{
//This part should be updated with respect to URL Structure
$operation = $parts[2];

$database = new Database("localhost", "scandiwebdb", "root", "" );
$db = $database->connect();
$product = new Product($db);
$controller = new ProductController($product);
$controller->processRequest($operation, $data);
}