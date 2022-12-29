<?php

abstract class ProductAbstract
{
// Database connection
    protected $db;

// Product properties
    protected $id;
    protected $sku;
    protected $name;
    protected $price;
    protected $type_name;
    protected $type_id;
    protected $size;
    protected $height;
    protected $width;
    protected $length;
    protected $weight;


// Abstract method to create a product
    abstract public function createProduct($data);
    abstract public function getAllProducts();
    abstract public function deleteProducts($data);

}