<?php
include '../include/connectdb.php';
$pdo = connectDB();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_taikhoan'])) {
    header('Location:../account/dangnhap-dangky.html');
    exit();
}

$sql_hoadon = "
    SELECT h.*, SUM(ch.soluongsp) as tongsoluong, SUM(ch.tongtien) + 32000 as tongtienhd 
    FROM hoadon h
    LEFT JOIN chitiet_hoadon ch ON h.mahd = ch.mahd
    INNER JOIN giohang g ON h.mahd = ch.mahd
    WHERE g.id_taikhoan = ?
    GROUP BY h.mahd
    ORDER BY h.ngaylaphd DESC
";
$stmt_hoadon = $pdo->prepare($sql_hoadon);
$stmt_hoadon->execute([$_SESSION['id_taikhoan']]);
$hoadon_list = $stmt_hoadon->fetchAll(PDO::FETCH_ASSOC);

foreach ($hoadon_list as &$hoadon) {
    $hoadon['tongsoluong'] = intval($hoadon['tongsoluong']);
    $hoadon['tongtienhd'] = intval($hoadon['tongtienhd']);
}
unset($hoadon);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['huydon'])) {
    $mahd_huy = $_POST['mahd'];
    $sql_huy = "UPDATE hoadon SET trangthai = 'Hủy' WHERE mahd = ?";
    $stmt_huy = $pdo->prepare($sql_huy);
    $stmt_huy->execute([$mahd_huy]);

    $stmt_hoadon->execute([$_SESSION['id_taikhoan']]);
    $hoadon_list = $stmt_hoadon->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng của tôi</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/giohang.css">
    <link rel="stylesheet" href="../assets/style/lamdep.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
    <link rel="stylesheet" href="../assets/style/footer.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/normalize.css">
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/icomoon.css">
    <link rel="stylesheet" href="../assets/css/jquery-ui.css">
    <link rel="stylesheet" href="../assets/css/owl.carousel.css">
    <link rel="stylesheet" href="../assets/css/transitions.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/color.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<?php include '../include/header.php' ?>

<div class="container mt-5">
    <h2 class="text-center">Đơn hàng của tôi</h2>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>Mã Hóa Đơn</th>
                <th>Tên Khách Hàng</th>
                <th>Email</th>
                <th>Số Điện Thoại</th>
                <th>Địa Chỉ</th>
                <th>Tổng Sản Phẩm</th>
                <th>Tổng Tiền</th>
                <th>Ngày Lập</th>
                <th>Trạng Thái</th>
                <th>Hủy Đơn</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($hoadon_list as $hoadon): ?>
                <tr>
                    <td><?php echo htmlspecialchars($hoadon['mahd']); ?></td>
                    <td><?php echo htmlspecialchars($hoadon['tenkh']); ?></td>
                    <td><?php echo htmlspecialchars($hoadon['emailkh']); ?></td>
                    <td><?php echo htmlspecialchars($hoadon['sdt']); ?></td>
                    <td><?php echo htmlspecialchars($hoadon['diachi']); ?></td>
                    <td><?php echo htmlspecialchars($hoadon['tongsoluong']); ?> sản phẩm</td>
                    <td><?php echo number_format($hoadon['tongtienhd'], 0, ',', '.'); ?> VND</td>
                    <td><?php echo date('d-m-Y H:i:s', strtotime($hoadon['ngaylaphd'])); ?></td>
                    <td><?php echo htmlspecialchars($hoadon['trangthai']); ?></td>
                    <td>
                        <?php if ($hoadon['trangthai'] === 'Đang xử lý'): ?>
                            <form method="post">
                                <input type="hidden" name="mahd" value="<?php echo $hoadon['mahd']; ?>">
                                <button type="submit" name="huydon" class="btn btn-danger btn-sm">Hủy</button>
                            </form>
                        <?php else: ?>
                            <span>Không thể hủy</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="../assets/js/jquery-library.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/owl.carousel.min.js"></script>
<script src="../assets/js/jquery.vide.min.js"></script>
<script src="../assets/js/countdown.js"></script>
<script src="../assets/js/jquery-ui.js"></script>
<script src="../assets/js/parallax.js"></script>
<script src="../assets/js/countTo.js"></script>
<script src="../assets/js/appear.js"></script>
<script src="../assets/js/main.js"></script>

</body>
<?php include '../include/footer.php'; ?>
</html>
