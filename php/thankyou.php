<?php
include '../include/connectdb.php';
$pdo = connectDB();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra nếu không có thông tin đơn hàng, quay lại trang giỏ hàng
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['tenkh'], $_POST['emailkh'], $_POST['sdt'], $_POST['diachi'], $_POST['tongtien'])) {
    echo "<script>alert('Vui lòng điền đầy đủ thông tin!'); window.history.back();</script>";
    exit;
}

// Lấy thông tin đơn hàng từ form
$tenkh = $_POST['tenkh'];
$emailkh = $_POST['emailkh'];
$sdt = $_POST['sdt'];
$diachi = $_POST['diachi'];
$tongtien = $_POST['tongtien'];
$ngaylaphd = date('Y-m-d H:i:s');
$trangthai = 'Đang xử lý';

// Lưu thông tin vào bảng `hoadon` (bao gồm tổng số lượng và tổng tiền hóa đơn)
$sql_hoadon = "INSERT INTO hoadon (tenkh, emailkh, sdt, diachi, ngaylaphd, trangthai, tongsoluong, tongtienhd) 
               VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt_hoadon = $pdo->prepare($sql_hoadon);
$stmt_hoadon->execute([$tenkh, $emailkh, $sdt, $diachi, $ngaylaphd, $trangthai, 0, $tongtien]);

// Lấy mã hóa đơn vừa tạo
$mahd = $pdo->lastInsertId();

// Lưu chi tiết đơn hàng vào bảng `chitiet_hoadon`
$sql_cart_items = "
    SELECT chitiet_giohang.soluong, dongho.madh, dongho.gia, tendh
    FROM chitiet_giohang
    INNER JOIN dongho ON chitiet_giohang.id_dongho = dongho.madh
    WHERE chitiet_giohang.id_giohang IN (
        SELECT id_giohang FROM giohang WHERE id_taikhoan = ? 
    )
";
$stmt_cart = $pdo->prepare($sql_cart_items);
$stmt_cart->execute([$_SESSION['id_taikhoan']]);
$cart_items = json_decode($_POST['cart_items'], true);

// Tính tổng số lượng và tổng tiền cho đơn hàng
$tong_so_luong = 0;
$tong_tien = 0;
foreach ($cart_items as $item) {
    $tong_so_luong += $item['soluong'];  // Tính tổng số lượng
    $tong_tien_sp = $item['soluong'] * $item['gia'];  // Tính tổng tiền cho sản phẩm
    $tong_tien += $tong_tien_sp;  // Cộng dồn tổng tiền
}

$phi_van_chuyen = 32000;  // Phí vận chuyển
$tong_tien += $phi_van_chuyen;  // Cộng phí vận chuyển vào tổng tiền

// Cập nhật tổng số lượng và tổng tiền vào bảng hoadon
$sql_update_hoadon = "UPDATE hoadon SET tongsoluong = ?, tongtienhd = ? WHERE mahd = ?";
$stmt_update_hoadon = $pdo->prepare($sql_update_hoadon);
$stmt_update_hoadon->execute([$tong_so_luong, $tong_tien, $mahd]);

// Lưu chi tiết hóa đơn
foreach ($cart_items as $item) {
    $tongtien_sp = $item['soluong'] * $item['gia'];

    // Lưu vào bảng chi tiết hóa đơn
    $sql_chitiethoadon = "INSERT INTO chitiet_hoadon (mahd, masp, soluongsp, dongia, tongtien) 
                          VALUES (?, ?, ?, ?, ?)";
    $stmt_chitiethoadon = $pdo->prepare($sql_chitiethoadon);
    $stmt_chitiethoadon->execute([$mahd, $item['madh'], $item['soluong'], $item['gia'], $tongtien_sp]);
}

// In hóa đơn
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cảm ơn đã đặt hàng</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Cảm ơn bạn đã đặt hàng!</h2>
        <h4 class="mt-4">Thông tin hóa đơn:</h4>
        <table class="table">
            <tr>
                <th>Họ và tên:</th>
                <td><?php echo htmlspecialchars($tenkh); ?></td>
            </tr>
            <tr>
                <th>Email:</th>
                <td><?php echo htmlspecialchars($emailkh); ?></td>
            </tr>
            <tr>
                <th>Số điện thoại:</th>
                <td><?php echo htmlspecialchars($sdt); ?></td>
            </tr>
            <tr>
                <th>Địa chỉ:</th>
                <td><?php echo htmlspecialchars($diachi); ?></td>
            </tr>
            <tr>
                <th>Tổng số lượng:</th>
                <td><?php echo $tong_so_luong; ?> sản phẩm</td>
            </tr>
            <tr>
                <th>Phí vận chuyển (tiêu chuẩn):</th>
                <td>32.000 VND</td>
            </tr>
            <tr>
                <th>Tổng tiền:</th>
                <td><?php echo number_format($tong_tien, 0, ',', '.'); ?> VND</td>
            </tr>
        </table>

        <h4 class="mt-4">Chi tiết sản phẩm:</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Tổng tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><?php echo isset($item['tendh']) ? htmlspecialchars($item['tendh']) : 'Không có tên đồng hồ'; ?></td>
                        <td><?php echo isset($item['soluong']) ? $item['soluong'] : 0; ?></td>
                        <td><?php echo isset($item['gia']) ? number_format($item['gia'], 0, ',', '.') : '0'; ?> VND</td>
                        <td><?php echo isset($item['soluong']) && isset($item['gia']) ? number_format($item['soluong'] * $item['gia'], 0, ',', '.') : '0'; ?> VND</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Đường dẫn đến autoload của Composer
// Tạo nội dung email
$email_subject = "Hóa đơn mua hàng từ cửa hàng đồng hồ";
$email_body = "
    <h3>Xin chào $tenkh,</h3>
    <p>Cảm ơn bạn đã đặt hàng tại cửa hàng chúng tôi. Dưới đây là thông tin hóa đơn:</p>
    <table border='1' style='border-collapse:collapse;'>
        <tr><th>Họ và tên:</th><td>$tenkh</td></tr>
        <tr><th>Email:</th><td>$emailkh</td></tr>
        <tr><th>Số điện thoại:</th><td>$sdt</td></tr>
        <tr><th>Địa chỉ:</th><td>$diachi</td></tr>
        <tr><th>Tổng số lượng:</th><td>$tong_so_luong sản phẩm</td></tr>
        <tr><th>Phí vận chuyển:</th><td>32.000 VND</td></tr>
        <tr><th>Tổng tiền:</th><td>" . number_format($tong_tien, 0, ',', '.') . " VND</td></tr>
    </table>
    <h4>Chi tiết sản phẩm:</h4>
    <table border='1' style='border-collapse:collapse;'>
        <tr>
            <th>Tên sản phẩm</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
            <th>Tổng tiền</th>
        </tr>";
foreach ($cart_items as $item) {
    $email_body .= "
        <tr>
            <td>" . htmlspecialchars($item['tendh']) . "</td>
            <td>" . $item['soluong'] . "</td>
            <td>" . number_format($item['gia'], 0, ',', '.') . " VND</td>
            <td>" . number_format($item['soluong'] * $item['gia'], 0, ',', '.') . " VND</td>
        </tr>";
}
$email_body .= "
    </table>
    <p>Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi.</p>
    <p>Trân trọng,<br>Cửa hàng đồng hồ</p>";

// Gửi email
$mail = new PHPMailer(true);

try {
    // Cấu hình máy chủ gửi mail
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Thay bằng SMTP của bạn
    $mail->SMTPAuth = true;
    $mail->Username = 'hsukihsuki001@gmail.com'; // Email của bạn
    $mail->Password = 'ypuw mbif vpql jyso'; // Mật khẩu email
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Cài đặt người gửi và người nhận
    $mail->CharSet = 'UTF-8';
    $mail->setFrom('hsukihsuki001@gmail.com', 'Cửa hàng đồng hồ');
    $mail->addAddress($emailkh, $tenkh); // Người nhận

    // Nội dung email
    $mail->isHTML(true);
    $mail->Subject = $email_subject;
    $mail->Body    = $email_body;

    $mail->send();
    echo "<script>alert('Đặt hàng thành công và hóa đơn đã được gửi qua email!');</script>";
} catch (Exception $e) {
    echo "<script>alert('Đặt hàng thành công nhưng không thể gửi email: {$mail->ErrorInfo}');</script>";
}
?>

        <div class="text-center">
            <a href="../index.php" class="btn btn-primary">Quay lại trang chủ</a>
        </div>
    </div>
</body>
</html>

<?php
// Xóa giỏ hàng sau khi đặt
$sql_delete_cart = "DELETE FROM chitiet_giohang WHERE id_giohang IN (SELECT id_giohang FROM giohang WHERE id_taikhoan = ?)";
$stmt_delete_cart = $pdo->prepare($sql_delete_cart);
$stmt_delete_cart->execute([$_SESSION['id_taikhoan']]);
?>
