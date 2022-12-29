<?php

class ProductController{
    private $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function processRequest($method, $data){
        if($data)
        {
            $this->processResourceRequest($method, $data);
        }
        else
        {
            //To prevent CORS error
            if($method == "OPTIONS")
            {
                http_response_code(200);
                exit;
            }
            else
            {
                $this->getAllProducts($method);
            }
        }
    }

    private function processResourceRequest($method, $data){
        switch ($method) {
            case "POST":
                if($this->product->createProduct($data))
                {
                    http_response_code(200);
                    echo json_encode(
                        array('message' => 'Product Created')
                    );
                }
                else {
                    http_response_code(503);
                    echo json_encode(
                        array('message' => 'Product Not Created')
                    );
                }
            break;

            case "DELETE":
                if($this->product->deleteProducts($data)){
                    http_response_code(200);
                    echo json_encode(array("message" => "Product(s) were deleted."));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "Unable to delete product(s)."));
                }
            break;

            default:
                http_response_code(405);
                header("Allow: POST, DELETE");
        }
    }

    private function getAllProducts($method)
    {
        if ($method == "POST" || $method === "GET") {
            // Product query
            $result = $this->product->getAllProducts();

            // Get row count
            $num = $result->rowCount();

            // Check if any products
            if ($num > 0) {
                // Products array
                $products_arr = array();
                // $products_arr['data'] = array();

                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
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

        }
        else{
            http_response_code(405);
            header("Allow: GET, POST");
        }
    }
}