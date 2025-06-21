<?php
class ProductModel
{
private $conn;
private $table_name = "product";
public function __construct($db)
{
$this->conn = $db;
}
public function getProducts()
{
    $query = "SELECT p.id, p.name, p.description, p.price, p.image, p.quantity, 
              p.average_rating, p.review_count, c.name as category_name
              FROM " . $this->table_name . " p
              LEFT JOIN category c ON p.category_id = c.id";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $result;
}
public function getProductById($id)
{
    $query = "SELECT p.*, c.name as category_name 
              FROM " . $this->table_name . " p
              LEFT JOIN category c ON p.category_id = c.id
              WHERE p.id = :id";
    
    // Debug query
    error_log("SQL Query: " . $query);
    error_log("Product ID: " . $id);
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_OBJ);
    
    // Debug result
    error_log("Query result: " . ($result ? json_encode($result) : "No result"));
    
    return $result;
}

public function getProductsByCategory($category_id)
{
    $query = "SELECT p.id, p.name, p.description, p.price, p.image, p.quantity, 
              p.average_rating, p.review_count, c.name as category_name
              FROM " . $this->table_name . " p
              LEFT JOIN category c ON p.category_id = c.id
              WHERE p.category_id = :category_id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $result;
}

public function addProduct($name, $description, $price, $category_id, $image, $quantity)
{
    $errors = [];
    if (empty($name)) {
        $errors['name'] = 'Tên sản phẩm không được để trống';
    }
    if (empty($description)) {
        $errors['description'] = 'Mô tả không được để trống';
    }
    if (!is_numeric($price) || $price < 0) {
        $errors['price'] = 'Giá sản phẩm không hợp lệ';
    }
    if (!is_numeric($quantity) || $quantity < 0) {
        $errors['quantity'] = 'Số lượng không hợp lệ';
    }
    if (count($errors) > 0) {
        return $errors;
    }
    $query = "INSERT INTO " . $this->table_name . " (name, description, price, category_id, image, quantity) VALUES (:name, :description, :price, :category_id, :image, :quantity)";
    $stmt = $this->conn->prepare($query);
    $name = htmlspecialchars(strip_tags($name));
    $description = htmlspecialchars(strip_tags($description));
    $price = htmlspecialchars(strip_tags($price));
    $category_id = htmlspecialchars(strip_tags($category_id));
    $image = htmlspecialchars(strip_tags($image));
    $quantity = (int)$quantity;
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':image', $image);
    $stmt->bindParam(':quantity', $quantity);
    if ($stmt->execute()) {
        return true;
    }
    return false;
}
public function updateProduct($id, $name, $description, $price, $category_id, $image, $quantity)
{
    $query = "UPDATE " . $this->table_name . " SET name=:name, description=:description, price=:price, category_id=:category_id, image=:image, quantity=:quantity WHERE id=:id";
    $stmt = $this->conn->prepare($query);
    $name = htmlspecialchars(strip_tags($name));
    $description = htmlspecialchars(strip_tags($description));
    $price = htmlspecialchars(strip_tags($price));
    $category_id = htmlspecialchars(strip_tags($category_id));
    $image = htmlspecialchars(strip_tags($image));
    $quantity = (int)$quantity;
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':image', $image);
    $stmt->bindParam(':quantity', $quantity);
    if ($stmt->execute()) {
        return true;
    }
    return false;
}
public function deleteProduct($id)
{
$query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
$stmt = $this->conn->prepare($query);
$stmt->bindParam(':id', $id);
if ($stmt->execute()) {
return true;
}
return false;
}
public function decreaseQuantity($id, $amount)
{
    $query = "UPDATE " . $this->table_name . " SET quantity = quantity - :amount WHERE id = :id AND quantity >= :amount";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
    return $stmt->execute();
}
public function getProductCount()
{
    $query = "SELECT COUNT(*) FROM " . $this->table_name;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn();
}

public function getLatestProducts($limit = 5)
{
    $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC LIMIT :limit";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

public function searchAdminProducts($searchTerm, $categoryId = 0)
{
    $query = "SELECT p.id, p.name, p.description, p.price, p.image, p.quantity, 
              p.average_rating, p.review_count, c.name as category_name
              FROM " . $this->table_name . " p
              LEFT JOIN category c ON p.category_id = c.id
              WHERE 1=1";
    
    $params = [];
    
    // Add search term condition if provided - search only by name with accent insensitivity
    if (!empty($searchTerm)) {
        // Using COLLATE for accent insensitivity (works in MySQL)
        $query .= " AND p.name COLLATE utf8mb4_general_ci LIKE :search";
        $params[':search'] = "%" . $searchTerm . "%";
    }
    
    // Add category filter if provided
    if ($categoryId > 0) {
        $query .= " AND p.category_id = :category_id";
        $params[':category_id'] = $categoryId;
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