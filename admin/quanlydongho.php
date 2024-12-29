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

// Lấy thông tin đồng hồ cần sửa
$editWatch = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];

    $sql = "SELECT madh, tendh, mota, gia, mahang, hinhanh, soseri FROM dongho WHERE madh = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $editWatch = $result->fetch_assoc();
    }
    $stmt->close();
}

// Xử lý sửa đồng hồ
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_watch'])) {
    $madh = $_POST['madh'];
    $tendh = $_POST['tendh'];
    $mota = $_POST['mota'];
    $gia = $_POST['gia'];
    $mahang = $_POST['mahang'];
    $soseri = $_POST['soseri'];

    $hinhanh = $editWatch['hinhanh'];
    if ($_FILES['hinhanh']['name']) {
        $new_hinhanh = basename($_FILES['hinhanh']['name']);
        $target_dir = "../assets/img/dongho/";
        $target_file = $target_dir . $new_hinhanh;

        // Nếu file mới khác file hiện tại, xử lý upload
        if ($new_hinhanh != $editWatch['hinhanh']) {
            if (!file_exists($target_file)) {
                move_uploaded_file($_FILES['hinhanh']['tmp_name'], $target_file);
            }
            $hinhanh = $new_hinhanh;
        }
    }

    // Kiểm tra mã đồng hồ đã tồn tại chưa
    $sql = "SELECT madh FROM dongho WHERE madh = ? AND madh != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $madh, $editWatch['madh']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "<script>alert('Mã đồng hồ đã tồn tại! Vui lòng chọn mã khác.'); window.location.href='quanlydongho.php';</script>";
        exit();
    }
    $stmt->close();

    $sql = "UPDATE dongho SET tendh = ?, mota = ?, gia = ?, mahang = ?, hinhanh = ?, soseri = ? WHERE madh = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssissss", $tendh, $mota, $gia, $mahang, $hinhanh, $soseri, $madh);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Sửa đồng hồ thành công!'); window.location.href='quanlydongho.php';</script>";
    exit();
}

// Xử lý thêm đồng hồ
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_watch'])) {
    $madh = $_POST['madh'];
    $tendh = $_POST['tendh'];
    $mota = $_POST['mota'];
    $gia = $_POST['gia'];
    $mahang = $_POST['mahang'];
    $soseri = $_POST['soseri'];
    $hinhanh = basename($_FILES['hinhanh']['name']);
    $target_dir = "../assets/img/dongho/";
    $target_file = $target_dir . $hinhanh;

    // Kiểm tra mã đồng hồ đã tồn tại chưa
    $sql = "SELECT madh FROM dongho WHERE madh = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $madh);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "<script>alert('Mã đồng hồ đã tồn tại! Vui lòng chọn mã khác.'); window.location.href='quanlydongho.php';</script>";
        exit();
    }
    $stmt->close();

    move_uploaded_file($_FILES['hinhanh']['tmp_name'], $target_file);

    $sql = "INSERT INTO dongho (madh, tendh, mota, gia, mahang, hinhanh, soseri) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssisss", $madh, $tendh, $mota, $gia, $mahang, $hinhanh, $soseri);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Thêm đồng hồ thành công!'); window.location.href='quanlydongho.php';</script>";
    exit();
}

// Xử lý xóa đồng hồ
if (isset($_POST['delete_watch'])) {
    $madh = $_POST['delete_id'];

    $sql = "DELETE FROM dongho WHERE madh = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $madh);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Xóa đồng hồ thành công!'); window.location.href='quanlydongho.php';</script>";
    exit();
}

// Lấy danh sách đồng hồ
$sql = "SELECT madh, tendh, mota, gia, mahang, hinhanh, soseri FROM dongho";
$result = $conn->query($sql);
$watches = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đồng hồ</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="menu">
        <a href="quanlydongho.php">Quản lý đồng hồ</a>
        <a href="quanlyhoadon.php">Quản lý hóa đơn</a>
        <a href="quanlytaikhoan.php">Quản lý tài khoản</a>
        <a href="logoutad.php">Đăng xuất</a>
    </div>
<div class="container mt-5">
    <h1>Quản lý đồng hồ</h1>

    <!-- Form thêm hoặc sửa đồng hồ -->
    <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
    <label for="madh" class="form-label">Mã đồng hồ</label>
    <input type="text" name="madh" id="madh" class="form-control" value="<?php echo $editWatch['madh'] ?? ''; ?>" readonly>
    </div>
        <div class="mb-3">
            <label for="tendh" class="form-label">Tên đồng hồ</label>
            <input type="text" name="tendh" id="tendh" class="form-control" value="<?php echo $editWatch['tendh'] ?? ''; ?>" required>
        </div>
        <div class="mb-3">
            <label for="mota" class="form-label">Mô tả</label>
            <textarea name="mota" id="mota" class="form-control" rows="3" required><?php echo $editWatch['mota'] ?? ''; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="gia" class="form-label">Giá</label>
            <input type="number" name="gia" id="gia" class="form-control" value="<?php echo $editWatch['gia'] ?? ''; ?>" required>
        </div>
        <div class="mb-3">
            <label for="mahang" class="form-label">Mã hãng</label>
            <select name="mahang" id="mahang" class="form-select" required>
                <option value="" disabled selected>Chọn mã hãng</option>
                <option value="mh001" <?php echo (isset($editWatch) && $editWatch['mahang'] == 'mh001') ? 'selected' : ''; ?>>mh001</option>
                <option value="mh002" <?php echo (isset($editWatch) && $editWatch['mahang'] == 'mh002') ? 'selected' : ''; ?>>mh002</option>
                <option value="mh003" <?php echo (isset($editWatch) && $editWatch['mahang'] == 'mh003') ? 'selected' : ''; ?>>mh003</option>
                <option value="mh004" <?php echo (isset($editWatch) && $editWatch['mahang'] == 'mh004') ? 'selected' : ''; ?>>mh004</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="soseri" class="form-label">Số Seri</label>
            <input type="text" name="soseri" id="soseri" class="form-control" value="<?php echo $editWatch['soseri'] ?? ''; ?>" required>
        </div>
        <div class="mb-3">
            <label for="hinhanh" class="form-label">Hình ảnh</label>
            <input type="file" name="hinhanh" id="hinhanh" class="form-control">
            <?php if (isset($editWatch['hinhanh'])): ?>
                <img src="../assets/img/dongho/<?php echo $editWatch['hinhanh']; ?>" alt="Hình ảnh hiện tại" width="100">
            <?php endif; ?>
        </div>
        <button type="submit" name="<?php echo isset($editWatch) ? 'edit_watch' : 'add_watch'; ?>" class="btn btn-primary">
            <?php echo isset($editWatch) ? 'Sửa đồng hồ' : 'Thêm đồng hồ'; ?>
        </button>
        <a href="quanlydongho.php" class="btn btn-secondary">Quay lại</a>
    </form>

    <hr>
    <h3>Danh sách đồng hồ</h3>
    <table class="table">
        <thead>
        <tr>
            <th>Mã đồng hồ</th>
            <th>Tên đồng hồ</th>
            <th>Mô tả</th>
            <th>Giá</th>
            <th>Mã hãng</th>
            <th>Số Seri</th>
            <th>Hình ảnh</th>
            <th>Thao tác</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($watches as $watch): ?>
            <tr>
                <td><?php echo $watch['madh']; ?></td>
                <td><?php echo $watch['tendh']; ?></td>
                <td><?php echo $watch['mota']; ?></td>
                <td><?php echo number_format($watch['gia'], 0, ',', '.'); ?></td>
                <td><?php echo $watch['mahang']; ?></td>
                <td><?php echo $watch['soseri']; ?></td>
                <td><img src="../assets/img/dongho/<?php echo $watch['hinhanh']; ?>" alt="<?php echo $watch['tendh']; ?>" width="50"></td>
                <td>
                    <a href="?edit_id=<?php echo $watch['madh']; ?>" class="btn btn-warning btn-sm">Sửa</a>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="delete_id" value="<?php echo $watch['madh']; ?>">
                        <button type="submit" name="delete_watch" class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
