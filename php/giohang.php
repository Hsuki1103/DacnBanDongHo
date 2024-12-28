<?php
// Kết nối cơ sở dữ liệu và khởi động session
include '../include/connectdb.php';
$pdo = connectDB();

// Kiểm tra trạng thái session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['id_taikhoan'])) {
    echo "Vui lòng đăng nhập để xem giỏ hàng!";
    exit;
}

$id_taikhoan = $_SESSION['id_taikhoan'];

// Lấy dữ liệu giỏ hàng từ cơ sở dữ liệu
$sql_cart = "
    SELECT 
        chitiet_giohang.soluong, 
        dongho.madh, 
        dongho.tendh, 
        dongho.hinhanh, 
        dongho.gia, 
        dongho.mahang
    FROM chitiet_giohang
    INNER JOIN dongho ON chitiet_giohang.id_dongho = dongho.madh
    WHERE chitiet_giohang.id_giohang IN (
        SELECT id_giohang FROM giohang WHERE id_taikhoan = ?
    )
";
$stmt_cart = $pdo->prepare($sql_cart);
$stmt_cart->execute([$id_taikhoan]);
$cart_items = $stmt_cart->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Giỏ hàng</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    	<!-- Bootstrap Icon -->
        <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
</head>
<body>
<?php include '../include/header.php' ?>

<main class="page">
    <section class="shopping-cart dark">
        <div class="container">
            <div class="block-heading">
                <h2>Giỏ hàng</h2>
            </div>
            <div class="content">
                <div class="row">
                    <!-- Danh sách sản phẩm -->
                    <div class="col-md-12 col-lg-8">
                        <div class="items">
                            <?php if (!empty($cart_items)): ?>
                                <?php foreach ($cart_items as $item): ?>
    <?php 
    $imagePath = "../assets/img/dongho/" . $item['hinhanh'];
    $tong_gia = $item['soluong'] * $item['gia'];
    ?>
    <div class="product">
        <div class="row">
            <!-- Checkbox -->
            <div class="col-md-1 text-center">
                <input type="checkbox" class="form-check-input checkbox-large" name="chondh" id="chondh_<?php echo $item['madh']; ?>">
            </div>
            <!-- Hình ảnh sách -->
            <div class="col-md-2">
                <img class="img-fluid mx-auto d-block image w-75" src="<?php echo htmlspecialchars($imagePath); ?>">
            </div>
            <!-- Tên sách -->
            <div class="col-md-4">
                <div class="info">
                    <h6><?php echo htmlspecialchars($item['tendh']); ?></h6>
                    <span>Giá: <?php echo number_format($item['gia'], 0, ',', '.'); ?> VND</span>
                </div>
            </div>
            <!-- Điều chỉnh số lượng -->
            <div class="col-md-2 text-center">
                <div class="quantity-controls">   
                  <input type="number" id="quantity_<?php echo $item['madh']; ?>" class="quantity-input" value="<?php echo $item['soluong']; ?>" min="1">
                </div>
            </div>
            <!-- Tổng giá -->
            <div class="col-md-2 text-center">
                <span id="price_<?php echo $item['madh']; ?>" data-price="<?php echo $item['gia']; ?>">
                    <?php echo number_format($tong_gia, 0, ',', '.'); ?> VND
                </span>
            </div>
            <!-- Xóa sản phẩm -->
            <div class="col-md-1 text-center">
                <form method="POST" action="xoagiohang.php">
                    <input type="hidden" name="id_dongho" value="<?php echo $item['madh']; ?>">
                    <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>                         
                            <?php else: ?>
                                <p>Giỏ hàng trống</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- Tóm tắt đơn hàng -->
                    <div class="col-md-12 col-lg-4">
                        <div class="summary">
                            <h3>Thông tin hóa đơn</h3><br>
                            <?php
// Tính tổng số lượng và tổng tiền
$tong_tien = 0;
$tong_so_luong = 0;
foreach ($cart_items as $item) {
    $tong_so_luong += $item['soluong'];  // Tính tổng số lượng
    $tong_tien_sp = $item['soluong'] * $item['gia'];  // Tính tổng tiền cho sản phẩm
    $tong_tien += $tong_tien_sp;  // Cộng dồn tổng tiền
}

// Phí vận chuyển
$phi_van_chuyen = 32000;  // Phí vận chuyển
$tong_tien += $phi_van_chuyen;  // Cộng phí vận chuyển vào tổng tiền

// Hiển thị tổng tiền
?>
<div class="summary-item"><span class="text">Phí phụ: </span><span class="price1">0 VND</span></div>
<div class="summary-item"><span class="text">Vận chuyển: </span><span class="price2">32,000 VND</span></div>
<div class="summary-item"><span class="text">Tổng tiền: </span><span class="price">
    <?php echo number_format($tong_tien, 0, ',', '.'); ?> VND
</span></div><br>
                            <form action="./thankyou.php" method="post">
                                <div class="mb-3">
                                    <label for="tenkh" class="form-label">Họ Tên:</label>
                                    <input type="text" class="form-control" id="tenkh" name="tenkh" required>
                                </div>
                                <div class="mb-3">
                                    <label for="emailkh" class="form-label">Email:</label>
                                    <input type="email" class="form-control" id="emailkh" name="emailkh" required>
                                </div>
                                <div class="mb-3">
                                    <label for="sdt" class="form-label">Số Điện Thoại:</label>
                                    <input type="text" class="form-control" id="sdt" name="sdt" required>
                                </div>
                                <div class="mb-3">
                                    <label for="diachi" class="form-label">Địa Chỉ:</label>
                                    <input type="text" class="form-control" id="diachi" name="diachi" required>
                                </div>
                                <input type="hidden" name="cart_items" value='<?php echo json_encode($cart_items); ?>'>
                                <input type="hidden" name="tongtien" value="<?php echo $tong_tien + 32000; ?>">

                                <button type="submit" class="btn btn-primary w-100">Đặt Hàng</button>
                            </form>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </section>
</main>

<script src="../assets/javascript/giohang.js"></script>
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
