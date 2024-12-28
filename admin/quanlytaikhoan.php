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

// Thông báo kết quả hành động
$message = "";
$type = ""; // Loại thông báo: 'success', 'error', hoặc 'info'

// Xử lý cập nhật khách hàng
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_customer'])) {
    $makh = intval($_POST['makh']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $hotenkh = trim($_POST['hotenkh']);
    $sdt = trim($_POST['sdt']);
    $diachi = trim($_POST['diachi']);
    $password = !empty($_POST['password']) ? password_hash(trim($_POST['password']), PASSWORD_DEFAULT) : null;

    if ($email && $hotenkh && $sdt && $diachi) {
        if ($password) {
            $stmt = $conn->prepare("UPDATE khachhang SET email = ?, hotenkh = ?, sdt = ?, diachi = ?, password = ? WHERE makh = ?");
            $stmt->bind_param("sssssi", $email, $hotenkh, $sdt, $diachi, $password, $makh);
        } else {
            $stmt = $conn->prepare("UPDATE khachhang SET email = ?, hotenkh = ?, sdt = ?, diachi = ? WHERE makh = ?");
            $stmt->bind_param("ssssi", $email, $hotenkh, $sdt, $diachi, $makh);
        }
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $message = "Cập nhật thông tin khách hàng thành công.";
            $type = "success";
        } else {
            $message = "Không có thay đổi nào được thực hiện.";
            $type = "info";
        }
    } else {
        $message = "Dữ liệu không hợp lệ.";
        $type = "error";
    }
    $stmt->close();
}

// Xử lý xóa khách hàng
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_customer'])) {
    $makh = intval($_POST['makh']);
    $stmt = $conn->prepare("DELETE FROM khachhang WHERE makh = ?");
    $stmt->bind_param("i", $makh);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $message = "Xóa khách hàng thành công.";
        $type = "success";
    } else {
        $message = "Lỗi khi xóa khách hàng.";
        $type = "error";
    }
    $stmt->close();
}

// Xử lý thêm khách hàng
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_customer'])) {
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $hotenkh = trim($_POST['hotenkh']);
    $sdt = trim($_POST['sdt']);
    $diachi = trim($_POST['diachi']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    if ($email && $hotenkh && $sdt && $diachi) {
        $stmt = $conn->prepare("INSERT INTO khachhang (email, hotenkh, sdt, diachi, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $email, $hotenkh, $sdt, $diachi, $password);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $message = "Thêm khách hàng mới thành công.";
            $type = "success";
        } else {
            $message = "Có lỗi xảy ra khi thêm khách hàng.";
            $type = "error";
        }
    } else {
        $message = "Dữ liệu không hợp lệ.";
        $type = "error";
    }
    $stmt->close();
}

// Lấy danh sách khách hàng
$stmt = $conn->prepare("SELECT * FROM khachhang");
$stmt->execute();
$result = $stmt->get_result();
$customers = [];
while ($row = $result->fetch_assoc()) {
    $customers[] = $row;
}
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý khách hàng</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin.css">
    <script>
        // Hiển thị thông báo từ PHP
        function showMessage(message, type) {
            if (message) {
                alert(message);
                // Hiển thị thêm thông báo nếu cần thông báo trực tiếp trên giao diện
            }
        }
    </script>
</head>
<body onload="showMessage('<?php echo $message; ?>', '<?php echo $type; ?>')">
    <!-- Thanh menu -->
    <div class="menu">
        <a href="quanlydongho.php">Quản lý đồng hồ</a>
        <a href="quanlyhoadon.php">Quản lý hóa đơn</a>
        <a href="quanlytaikhoan.php">Quản lý tài khoản</a>
        <a href="logoutad.php">Đăng xuất</a>
    </div>

    <h1>Quản lý tài khoản</h1>

    <table>
        <thead>
            <tr>
                <th>Mã KH</th>
                <th>Email</th>
                <th>Họ tên</th>
                <th>Số điện thoại</th>
                <th>Địa chỉ</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['makh']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['hotenkh']) ?></td>
                    <td><?= htmlspecialchars($row['sdt']) ?></td>
                    <td><?= htmlspecialchars($row['diachi']) ?></td>
                    <td>
                        <button type="button" onclick="editCustomer(<?= $row['makh'] ?>, '<?= htmlspecialchars($row['email']) ?>', '<?= htmlspecialchars($row['hotenkh']) ?>', '<?= htmlspecialchars($row['sdt']) ?>', '<?= htmlspecialchars($row['diachi']) ?>')">Sửa</button>
                        <form method="post" style="display:inline-block;">
                            <input type="hidden" name="makh" value="<?= $row['makh'] ?>">
                            <button type="submit" name="delete_customer" onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này không?');">Xóa</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Form cập nhật khách hàng -->
    <form id="editForm" method="post" style="display:none; margin-top: 20px;">
        <h2>Chỉnh sửa thông tin tài khoản</h2>
        <input type="hidden" name="makh" id="editMakh">
        <input type="text" name="email" id="editEmail" placeholder="Email">
        <input type="text" name="hotenkh" id="editHotenkh" placeholder="Họ tên">
        <input type="text" name="sdt" id="editSdt" placeholder="Số điện thoại">
        <input type="text" name="diachi" id="editDiachi" placeholder="Địa chỉ">
        <input type="password" name="password" id="editPassword" placeholder="Mật khẩu mới (nếu muốn thay đổi)">
        <button type="submit" name="update_customer">Cập nhật</button>
        <button type="button" onclick="document.getElementById('editForm').style.display='none'">Hủy</button>
    </form>

    <!-- Form thêm khách hàng -->
    <form id="addForm" method="post" style="margin-top: 20px;">
        <h2>Thêm khách hàng mới</h2>
        <input type="text" name="email" placeholder="Email" required>
        <input type="text" name="hotenkh" placeholder="Họ tên" required>
        <input type="text" name="sdt" placeholder="Số điện thoại" required>
        <input type="text" name="diachi" placeholder="Địa chỉ" required>
        <input type="password" name="password" placeholder="Mật khẩu">
        <button type="submit" name="add_customer">Thêm khách hàng</button>
        <button type="button" onclick="document.getElementById('addForm').style.display='none'">Hủy</button>
    </form>

    <!-- Script chỉnh sửa thông tin khách hàng -->
    <script>
        function editCustomer(makh, email, hotenkh, sdt, diachi) {
            document.getElementById('editForm').style.display = 'block';
            document.getElementById('editMakh').value = makh;
            document.getElementById('editEmail').value = email;
            document.getElementById('editHotenkh').value = hotenkh;
            document.getElementById('editSdt').value = sdt;
            document.getElementById('editDiachi').value = diachi;
        }
    </script>
</body>
</html>
