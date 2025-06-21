<?php
class CategoryModel
{
private $conn;
private $table_name = "category";
public function __construct($db)
{
$this->conn = $db;
}

public function addCategory($name, $description) {
    $query = "INSERT INTO category (name, description) VALUES (?, ?)";
    $stmt = $this->conn->prepare($query);
    return $stmt->execute([$name, $description]);
}

public function getCategoryById($id) {
    $query = "SELECT * FROM category WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_OBJ);
}

public function updateCategory($id, $name, $description) {
    $query = "UPDATE category SET name = ?, description = ? WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    return $stmt->execute([$name, $description, $id]);
}

public function deleteCategory($id) {
    $query = "DELETE FROM category WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    return $stmt->execute([$id]);
}

public function getCategories()
{
$query = "SELECT id, name, description FROM " . $this->table_name;
$stmt = $this->conn->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_OBJ);
return $result;
}

public function getCategoryCount()
{
    $query = "SELECT COUNT(*) FROM " . $this->table_name;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn();
}

public function hasProducts($id) {
    $query = "SELECT COUNT(*) FROM product WHERE category_id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$id]);
    return $stmt->fetchColumn() > 0;
}

public function searchCategories($searchTerm, $productCountFilter = 0) {
    $query = "SELECT c.id, c.name, c.description, 
              (SELECT COUNT(*) FROM product WHERE category_id = c.id) as product_count 
              FROM " . $this->table_name . " c
              WHERE 1=1";
    
    $params = [];
    
    // Add search term condition if provided - search by name with accent insensitivity
    if (!empty($searchTerm)) {
        $query .= " AND c.name COLLATE utf8mb4_general_ci LIKE :search";
        $params[':search'] = "%" . $searchTerm . "%";
    }
    
    // Add product count filter if provided
    if ($productCountFilter > 0) {
        $query .= " HAVING product_count >= :product_count";
        $params[':product_count'] = $productCountFilter;
    }
    
    $stmt = $this->conn->prepare($query);
    
    // Bind all parameters
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}
}
?>