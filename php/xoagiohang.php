<?php
// Kết nối cơ sở dữ liệu
include '../include/connectdb.php';
$pdo = connectDB();

// Kiểm tra đăng nhập
session_start();
if (!isset($_SESSION['id_taikhoan'])) {
    header('Location: login.php');
    exit;
}

$id_taikhoan = $_SESSION['id_taikhoan'];

// Nhận giá trị ID đồng hồ từ request POST
$id_dongho = $_POST['id_dongho'];

// Câu lệnh xóa sản phẩm khỏi giỏ hàng
$sql = "DELETE FROM chitiet_giohang WHERE id_dongho = ? AND id_giohang IN (
    SELECT id_giohang FROM giohang WHERE id_taikhoan = ?
)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_dongho, $id_taikhoan]);

// Chuyển hướng về trang giỏ hàng sau khi xóa
header('Location: giohang.php');
exit;
?>
