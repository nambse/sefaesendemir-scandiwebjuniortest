<?php
class Product {
    // DB stuff
    private $conn;
    private $table = 'products';

    // Product Properties
    public $id;
    public $sku;
    public $name;
    public $price;
    public $type_name;
    public $type_id;
    public $size;
    public $height;
    public $width;
    public $length;
    public $weight;

    // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get Products
    public function read() {
        // Create query
        $query = 'SELECT t.name as type_name, p.id, p.sku, p.name, p.price, p.type_id, p.size, p.height, p.width, p.length, p.weight
                                FROM ' . $this->table . ' p
                                LEFT JOIN
                                  types t ON p.type_id = t.id
                                ORDER BY
                                  p.id DESC';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Get Single Product
    public function read_single() {
        // Create query
        $query = 'SELECT t.name as type_name, p.id, p.sku, p.name, p.price, p.type_id, p.size, p.height, p.width, p.length, p.weight
                                FROM ' . $this->table . ' p
                                LEFT JOIN
                                  types t ON p.category_id = t.id
                                    WHERE
                                      p.id = ?
                                    LIMIT 0,1';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind ID
        $stmt->bindParam(1, $this->id);

        // Execute query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        $this->sku = $row['sku'];
        $this->name = $row['name'];
        $this->price = $row['price'];
        $this->type_id = $row['type_id'];
        $this->type_name = $row['type_name'];
        $this->size = $row['size'];
        $this->height = $row['height'];
        $this->width = $row['width'];
        $this->length = $row['length'];
        $this->weight = $row['weight'];
    }

    // Create Product
    public function create() {
        // Create query
        $query = 'INSERT INTO ' . $this->table . ' SET sku = :sku, name = :name, price = :price, type_id = :type_id, size = :size, height = :height, width = :width, length = :length, weight = :weight';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->sku = htmlspecialchars(strip_tags($this->sku));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->type_id = htmlspecialchars(strip_tags($this->type_id));
        $this->size = htmlspecialchars(strip_tags($this->size));
        $this->height = htmlspecialchars(strip_tags($this->height));
        $this->width = htmlspecialchars(strip_tags($this->width));
        $this->length = htmlspecialchars(strip_tags($this->length));
        $this->weight = htmlspecialchars(strip_tags($this->weight));

        // Bind data
        $stmt->bindParam(':sku', $this->sku, PDO::PARAM_STR);
        $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindParam(':price', $this->price, PDO::PARAM_STR);
        $stmt->bindParam(':type_id', $this->type_id, PDO::PARAM_INT);
        $stmt->bindParam(':size', $this->size, PDO::PARAM_STR);
        $stmt->bindParam(':height', $this->height, PDO::PARAM_INT);
        $stmt->bindParam(':width', $this->width, PDO::PARAM_INT);
        $stmt->bindParam(':length', $this->length, PDO::PARAM_INT);
        $stmt->bindParam(':weight', $this->weight, PDO::PARAM_INT);


        // Execute query
        if($stmt->execute()) {
            return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    // Delete Products
    public function delete() {
        // Create query
        $query = 'DELETE FROM ' . $this->table . ' WHERE id IN (';
        $query .= implode(',', array_fill(0, count($this->id), '?'));
        $query .= ')';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        // Bind data
        $stmt->execute($this->id);
        if ($stmt->rowCount() > 0) {
            return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->errorInfo()[2]);

        return false;
    }
}