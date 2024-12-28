<?php
session_start();
// Kiểm tra đăng nhập
if (!isset($_SESSION['admin'])) {
    header("Location: loginad.php");
    exit();
}

// Kết nối cơ sở dữ liệu
require_once 'db.php';
$conn = getDbConnection();

$message = ""; // Biến để lưu thông báo

// Xử lý cập nhật trạng thái hóa đơn
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_invoice'])) {
    $mahd = $_POST['mahd'];
    $trangthai = $_POST['trangthai'];
    $sql = "UPDATE hoadon SET trangthai='$trangthai' WHERE mahd=$mahd";
    if ($conn->query($sql)) {
        $message = "Cập nhật trạng thái hóa đơn thành công!";
    } else {
        $message = "Cập nhật thất bại. Vui lòng thử lại.";
    }
}

// Xử lý xóa hóa đơn
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_invoice'])) {
    $mahd = $_POST['mahd'];
    $sql = "DELETE FROM hoadon WHERE mahd=$mahd";
    if ($conn->query($sql)) {
        $message = "Xóa hóa đơn thành công!";
    } else {
        $message = "Xóa hóa đơn thất bại. Vui lòng thử lại.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý hóa đơn</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin.css">
    <script>
        // Hiển thị thông báo từ PHP
        function showMessage(message) {
            if (message) {
                alert(message);
            }
        }
    </script>
</head>
<body onload="showMessage('<?php echo $message; ?>')">
    <!-- Thanh menu -->
    <div class="menu">
        <a href="quanlydongho.php">Quản lý đồng hồ</a>
        <a href="quanlyhoadon.php">Quản lý hóa đơn</a>
        <a href="quanlytaikhoan.php">Quản lý tài khoản</a>
        <a href="logoutad.php">Đăng xuất</a>
    </div>

    <h1>Quản lý hóa đơn</h1>

    <table>
        <thead>
            <tr>
                <th>Mã hóa đơn</th>
                <th>Tên khách hàng</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Địa chỉ</th>
                <th>Tổng số lượng</th>
                <th>Tổng tiền</th>
                <th>Ngày lập</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM hoadon");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['mahd']}</td>
                    <td>{$row['tenkh']}</td>
                    <td>{$row['emailkh']}</td>
                    <td>{$row['sdt']}</td>
                    <td>{$row['diachi']}</td>
                    <td>{$row['tongsoluong']}</td>
                    <td>{$row['tongtienhd']}</td>
                    <td>{$row['ngaylaphd']}</td>
                    <td>{$row['trangthai']}</td>
                    <td>
                        <!-- Cập nhật trạng thái -->
                        <form method='post' style='display:inline;'>
                            <input type='hidden' name='mahd' value='{$row['mahd']}'>
                            <select name='trangthai'>
                                <option value='Đang xử lý' " . ($row['trangthai'] == 'Đang xử lý' ? 'selected' : '') . ">Đang xử lý</option>
                                <option value='Đã giao' " . ($row['trangthai'] == 'Đã giao' ? 'selected' : '') . ">Đã giao</option>
                                <option value='Hủy' " . ($row['trangthai'] == 'Hủy' ? 'selected' : '') . ">Hủy</option>
                            </select>
                            <button type='submit' name='update_invoice'>Cập nhật</button>
                        </form>
                        <!-- Xóa hóa đơn -->
                        <form method='post' style='display:inline;'>
                            <input type='hidden' name='mahd' value='{$row['mahd']}'>
                            <button type='submit' name='delete_invoice' onclick='return confirm(\"Bạn có chắc chắn muốn xóa hóa đơn này?\");'>Xóa</button>
                        </form>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php $conn->close(); ?>
