<?php
session_start();
include '../include/connectdb.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['id_taikhoan'])) {
    echo "Bạn phải đăng nhập để xóa giỏ hàng!";
    exit;
}

$id_taikhoan = $_SESSION['id_taikhoan']; // Lấy ID tài khoản người dùng từ session

// Lấy kết nối cơ sở dữ liệu
$conn = connectDB(); // Đảm bảo kết nối được khởi tạo từ hàm connectDB

// Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
try {
    $conn->beginTransaction();

    // Xóa các chi tiết giỏ hàng từ bảng chitiet_giohang
    $sql_clear_details = "DELETE FROM chitiet_giohang WHERE id_giohang IN (SELECT id_giohang FROM giohang WHERE id_taikhoan = ?)";
    $stmt_clear_details = $conn->prepare($sql_clear_details);
    $stmt_clear_details->execute([$id_taikhoan]);

    // Xóa giỏ hàng của người dùng từ bảng giohang
    $sql_clear_cart = "DELETE FROM giohang WHERE id_taikhoan = ?";
    $stmt_clear_cart = $conn->prepare($sql_clear_cart);
    $stmt_clear_cart->execute([$id_taikhoan]);

    // Cam kết các thay đổi trong cơ sở dữ liệu
    $conn->commit();

    header("Location: ../index.php");
    exit;
} catch (Exception $e) {
    // Nếu có lỗi, rollback transaction
    $conn->rollBack();
    echo "Đã có lỗi xảy ra khi xóa giỏ hàng: " . $e->getMessage();
}
?>