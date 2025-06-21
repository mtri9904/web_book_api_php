<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');
require_once('app/helpers/SessionHelper.php');

class CategoryController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /project1/account/login');
            exit;
        }
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    public function list()
    {
        $categories = $this->categoryModel->getCategories();
        include 'app/views/category/list.php';
    }

    public function add()
    {
        $errors = [];
        include 'app/views/category/add.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $errors = [];

            if (empty($name)) {
                $errors[] = "Tên danh mục không được để trống.";
            }

            if (empty($errors)) {
                $result = $this->categoryModel->addCategory($name, $description);
                if ($result) {
                    $_SESSION['toast_message'] = "Thêm danh mục thành công!";
                    $_SESSION['toast_type'] = "success";
                } else {
                    $_SESSION['toast_message'] = "Không thể thêm danh mục!";
                    $_SESSION['toast_type'] = "error";
                }
                header('Location: /project1/category/list');
                exit;
            } else {
                include 'app/views/category/add.php';
            }
        }
    }

    public function edit($id)
    {
        $category = $this->categoryModel->getCategoryById($id);
        $errors = [];
        if ($category) {
            include 'app/views/category/edit.php';
        } else {
            echo "Không tìm thấy danh mục.";
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $errors = [];

            if (empty($name)) {
                $errors[] = "Tên danh mục không được để trống.";
            }

            if (empty($errors)) {
                $result = $this->categoryModel->updateCategory($id, $name, $description);
                if ($result) {
                    $_SESSION['toast_message'] = "Cập nhật danh mục thành công!";
                    $_SESSION['toast_type'] = "success";
                } else {
                    $_SESSION['toast_message'] = "Không thể cập nhật danh mục!";
                    $_SESSION['toast_type'] = "error";
                }
                header('Location: /project1/category/list');
                exit;
            } else {
                $category = (object)[
                    'id' => $id,
                    'name' => $name,
                    'description' => $description
                ];
                include 'app/views/category/edit.php';
            }
        }
    }

    public function show($id)
    {
        $category = $this->categoryModel->getCategoryById($id);
        if ($category) {
            include 'app/views/category/show.php';
        } else {
            $_SESSION['toast_message'] = "Không tìm thấy danh mục!";
            $_SESSION['toast_type'] = "error";
            header('Location: /project1/category/list');
            exit;
        }
    }

    public function delete($id)
    {
        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            $_SESSION['toast_message'] = "Không tìm thấy danh mục!";
            $_SESSION['toast_type'] = "error";
            header('Location: /project1/category/list');
            exit;
        }
        
        try {
            // Kiểm tra xem bảng product có tồn tại không
            $checkTable = $this->db->query("SHOW TABLES LIKE 'product'");
            if ($checkTable->rowCount() > 0) {
                // Kiểm tra xem có sản phẩm nào thuộc danh mục này không
                $query = "SELECT COUNT(*) FROM product WHERE category_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$id]);
                $count = $stmt->fetchColumn();
                
                if ($count > 0) {
                    $_SESSION['toast_message'] = "Không thể xóa danh mục đang chứa sản phẩm!";
                    $_SESSION['toast_type'] = "error";
                    header('Location: /project1/category/list');
                    exit;
                }
            }
        } catch (PDOException $e) {
            // Bỏ qua lỗi nếu bảng không tồn tại
        }
        
        $result = $this->categoryModel->deleteCategory($id);
        if ($result) {
            $_SESSION['toast_message'] = "Xóa danh mục thành công!";
            $_SESSION['toast_type'] = "success";
        } else {
            $_SESSION['toast_message'] = "Không thể xóa danh mục!";
            $_SESSION['toast_type'] = "error";
        }
        header('Location: /project1/category/list');
        exit;
    }
}
?>