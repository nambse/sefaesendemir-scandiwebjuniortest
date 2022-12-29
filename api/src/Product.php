<?php
class Product extends ProductAbstract
{
    private $conn;
    private $table = 'products';

    // Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createProduct($data)
    {
        $this->sku = $data->sku;
        $this->name = $data->name;
        $this->type_id = $data->type_id;
        $this->price = $data->price;
        $this->size = $data->size;
        $this->height = $data->height;
        $this->width = $data->width;
        $this->length = $data->length;
        $this->weight = $data->weight;

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
        if ($stmt->execute()) {
            return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    public function getAllProducts()
    {
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

    public function deleteProducts($data)
    {
        $this->id = $data->id;

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

