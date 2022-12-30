<?php

class ProductController{
    private $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function processRequest($operation, $data){
        if($operation == "create" || $operation == "delete")
        {
            $this->processResourceRequest($operation, $data);
        }
        else if($operation == "read")
        {
            $this->processReadRequest();
        }
        else
        {
            http_response_code(404);
            exit;
        }
    }

    private function processResourceRequest($operation, $data){
        if ($operation == "create") {
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
        }

         if($operation == "delete"){
                if($this->product->deleteProducts($data)){
                    http_response_code(200);
                    echo json_encode(array("message" => "Product(s) were deleted."));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "Unable to delete product(s)."));
                }
        }
         else {
             http_response_code(404);
             exit;
         }
    }

    private function processReadRequest()
    {
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

            }
            else{
                // No Products
                http_response_code(503);
                echo json_encode(
                    array('message' => 'No Products Found')
                );
            }
    }
}