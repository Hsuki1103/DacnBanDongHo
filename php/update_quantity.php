<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kết nối cơ sở dữ liệu và khởi động session
include '../include/connectdb.php';
$pdo = connectDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra nếu có dữ liệu productId và quantity
    if (isset($_POST['productId']) && isset($_POST['quantity'])) {
        $productId = $_POST['productId'];
        $quantity = $_POST['quantity'];
        $id_taikhoan = $_SESSION['id_taikhoan']; // Lấy id_taikhoan từ session

        // Cập nhật số lượng sản phẩm trong giỏ hàng
        $sql = "
            UPDATE chitiet_giohang
            SET soluong = ?
            WHERE id_dongho = ? AND id_giohang IN (
                SELECT id_giohang FROM giohang WHERE id_taikhoan = ?
            )
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$quantity, $productId, $id_taikhoan]);

        // Kiểm tra xem có thành công không
        if ($stmt->rowCount() > 0) {
            echo "Cập nhật số lượng thành công!";
        } else {
            echo "Lỗi khi cập nhật số lượng!";
        }
    } else {
        echo "Dữ liệu không hợp lệ!";
    }
}
?>
