<?php
include '../include/connectdb.php';
session_start();

if (!isset($_SESSION['id_taikhoan'])) {
    echo json_encode(['success' => false, 'message' => 'Người dùng chưa đăng nhập!']);
    exit;
}

$id_taikhoan = $_SESSION['id_taikhoan'];

try {
    $conn = connectDB();

    // Sửa lại JOIN và SELECT phù hợp với cấu trúc bảng
    $sql_cart_items = "
        SELECT dh.tendh, dh.gia, dh.hinhanh, ct.soluong, dh.mahang
        FROM chitiet_giohang ct
        INNER JOIN dongho dh ON ct.id_dongho = dh.madh
        WHERE ct.id_giohang = (
            SELECT id_giohang FROM giohang WHERE id_taikhoan = ?
        )
    ";
    $stmt = $conn->prepare($sql_cart_items);
    $stmt->execute([$id_taikhoan]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Nếu không có sản phẩm trong giỏ hàng
    if (!$cart_items) {
        echo json_encode(['success' => true, 'cart_html' => '<p>Giỏ hàng trống</p>', 'total_items' => 0]);
        exit;
    }

    // Tạo HTML hiển thị giỏ hàng
    $cart_html = '';
    $total_items = 0;

    foreach ($cart_items as $item) {
        $imagePath = "../assets/img/dongho/" . $item['hinhanh'];
        $cart_html .= "
            <div class='tg-minicarproduct'>
                <figure><img src='" . htmlspecialchars($imagePath) . "' alt='" . htmlspecialchars($item['tendh']) . "'></figure>
                <div class='tg-minicarproductdata'>
                    <h5>" . htmlspecialchars($item['tendh']) . "</h5>
                    <h6>" . number_format($item['gia'], 0, ',', '.') . " VNĐ</h6>
                    <h6>Số lượng: " . $item['soluong'] . "</h6>
                </div>
            </div>
        ";
        $total_items += $item['soluong'];
    }

    echo json_encode(['success' => true, 'cart_html' => $cart_html, 'total_items' => $total_items]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()]);
}
?>
