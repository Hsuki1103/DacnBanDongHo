<?php
include '../include/connectdb.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Lấy dữ liệu từ phía client
        $data = json_decode(file_get_contents("php://input"), true);
        $madh = $data['madh'] ?? null;
        $soluong = $data['soluong'] ?? 1;
        
        // Kiểm tra dữ liệu đầu vào
        if (!$madh || $soluong <= 0) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ!']);
            exit;
        }

        // Kiểm tra người dùng đã đăng nhập chưa
        if (!isset($_SESSION['id_taikhoan'])) {
            echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng!']);
            exit;
        }

        $id_taikhoan = $_SESSION['id_taikhoan'];
        $conn = connectDB();

        // Kiểm tra xem người dùng đã có giỏ hàng chưa
        $sql_check_cart = "SELECT id_giohang FROM giohang WHERE id_taikhoan = ?";
        $stmt = $conn->prepare($sql_check_cart);
        $stmt->execute([$id_taikhoan]);
        $giohang = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$giohang) {
            // Tạo giỏ hàng nếu chưa có
            $sql_create_cart = "INSERT INTO giohang (id_taikhoan) VALUES (?)";
            $stmt = $conn->prepare($sql_create_cart);
            $stmt->execute([$id_taikhoan]);
            $id_giohang = $conn->lastInsertId();
        } else {
            $id_giohang = $giohang['id_giohang'];
        }

        // Kiểm tra và thêm sản phẩm vào giỏ hàng
        $sql_check_item = "SELECT id_chitiet, soluong FROM chitiet_giohang WHERE id_giohang = ? AND id_dongho = ?";
        $stmt = $conn->prepare($sql_check_item);
        $stmt->execute([$id_giohang, $madh]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            // Cập nhật số lượng sản phẩm
            $new_quantity = $item['soluong'] + $soluong;
            $sql_update_item = "UPDATE chitiet_giohang SET soluong = ? WHERE id_chitiet = ?";
            $stmt = $conn->prepare($sql_update_item);
            $stmt->execute([$new_quantity, $item['id_chitiet']]);
        } else {
            // Thêm sản phẩm mới
            $sql_add_item = "INSERT INTO chitiet_giohang (id_giohang, id_dongho, soluong) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql_add_item);
            $stmt->execute([$id_giohang, $madh, $soluong]);
        }

        // Lấy tổng số lượng sản phẩm trong giỏ hàng
        $sql_count_cart = "SELECT SUM(soluong) AS total_items FROM chitiet_giohang WHERE id_giohang = ?";
        $stmt = $conn->prepare($sql_count_cart);
        $stmt->execute([$id_giohang]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_items = $cart['total_items'] ?? 0;

        echo json_encode(['success' => true, 'message' => 'Thêm vào giỏ hàng thành công!', 'total_items' => $total_items]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ!']);
}
?>
