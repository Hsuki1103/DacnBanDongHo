<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$conn = connectDB(); 
// Lấy thông tin giỏ hàng nếu người dùng đã đăng nhập
$cart_count = 0;
$cart_total = 0;  // Biến để lưu tổng giá trị giỏ hàng
$cart_items = []; // Mảng lưu các sản phẩm trong giỏ hàng

if (isset($_SESSION['id_taikhoan'])) {
    $id_taikhoan = $_SESSION['id_taikhoan'];

    // Lấy tổng số lượng sản phẩm trong giỏ hàng
    $sql_cart_count = "
        SELECT SUM(chitiet_giohang.soluong) AS total_items, SUM(chitiet_giohang.soluong * dongho.gia) AS total_price
        FROM giohang
        INNER JOIN chitiet_giohang ON giohang.id_giohang = chitiet_giohang.id_giohang
        INNER JOIN dongho ON chitiet_giohang.id_dongho = dongho.madh
        WHERE giohang.id_taikhoan = ?";
    $stmt_cart_count = $conn->prepare($sql_cart_count);
    $stmt_cart_count->execute([$id_taikhoan]);
    $cart = $stmt_cart_count->fetch(PDO::FETCH_ASSOC);
    $cart_count = $cart['total_items'] ?? 0;
    $cart_total = $cart['total_price'] ?? 0;

    // Lấy các sản phẩm trong giỏ hàng
    $sql_cart_items = "
        SELECT chitiet_giohang.soluong, dongho.madh, dongho.tendh, dongho.hinhanh, dongho.gia, mahang
        FROM chitiet_giohang
        INNER JOIN dongho ON chitiet_giohang.id_dongho = dongho.madh
        WHERE chitiet_giohang.id_giohang IN (SELECT id_giohang FROM giohang WHERE id_taikhoan = ?)";
    $stmt_cart_items = $conn->prepare($sql_cart_items);
    $stmt_cart_items->execute([$id_taikhoan]);
    $cart_items = $stmt_cart_items->fetchAll(PDO::FETCH_ASSOC);


}
?>

<header id="tg-header" class="tg-header tg-haslayout">
    <div class="tg-middlecontainer">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <strong class="tg-logo">
                    <a href="../php/home.php">
                    <a href="../php/home.php">

<h1>Đồng Hồ Shop</h1>
<link rel="stylesheet" href="../assets/style/lamdep.css">

                    </strong>
                    <div class="tg-wishlistandcart">
                        <div class="dropdown tg-themedropdown tg-minicartdropdown">
                            <a href="javascript:void(0);" id="tg-minicart" class="tg-btnthemedropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="tg-themebadge"><?php echo $cart_count; ?></span>
                                <i class="bi bi-cart3"></i>
                                <span>Giỏ hàng</span>
                            </a>    
                            <div class="dropdown-menu tg-themedropdownmenu" aria-labelledby="tg-minicart">
                                <div class="tg-minicartbody">
                                    <?php if (count($cart_items) > 0): ?>
                                        <?php foreach ($cart_items as $item): ?>
                                            <div class="tg-minicarproduct">
                                            <?php
                                            // Ánh xạ danh mục
                                            $imagePath = "../assets/img/dongho/" . $item['hinhanh']; ?>
                                                <figure><img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($item['tendh']); ?>"></figure>
                                                <div class="tg-minicarproductdata">
                                                    <h5><?php echo htmlspecialchars($item['tendh']); ?></h5>
                                                    <h6><?php echo number_format($item['gia'], 0, ',', '.'); ?> VNĐ</h6>
                                                    <h6>Số lượng: <?php echo $item['soluong']; ?></h6>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p>Giỏ hàng trống</p>
                                    <?php endif; ?>
                                </div>
                                <div class="tg-minicartfoot">
                                    <?php if (count($cart_items) > 0): ?>
                                        <a class="tg-btnemptycart" href="../php/xoagiohangall.php">
                                            <i class="bi bi-trash"></i>
                                            <span>Xóa toàn bộ</span>
                                        </a>
                                        <span class="tg-subtotal">
                                            Tổng: <strong><?php echo number_format($cart_total, 0, ',', '.'); ?> VNĐ</strong>
                                        </span>
                                    <?php endif; ?>
                                    <div class="tg-btns">
                                        <a class="tg-btn tg-active" href="./giohang.php">Xem giỏ hàng</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tg-searchbox">
                        <form class="tg-formtheme tg-formsearch" method="GET" action="../php/search.php">
                            <fieldset>
                                <input type="text" name="search" class="typeahead form-control" placeholder="Nhập tên đồng hồ cần tìm...">
                                <button type="submit"><i class="bi bi-search"></i></button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tg-navigationarea">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <nav id="tg-nav" class="tg-nav">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#tg-navigation" aria-expanded="false">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>
                        <div id="tg-navigation" class="collapse navbar-collapse tg-navigation">
                            <ul>
                                <li><a href="../php/home.php">Home</a></li>
                                <li><a href="../php/sanpham.php">Sản phẩm</a></li>    
                                <li><a href="../php/donhang.php">Đơn hàng</a></li>    
                                <li>
                                    <div class="tg-userlogin">
                                        <?php if (isset($_SESSION['user'])): ?>
                                            <figure><a href="../account/user_profile.php"><img src="<?php echo $_SESSION['user']['avatar']; ?>" alt="Avatar"></a></figure>
                                            <span><a class="tenkh" href="../account/user_profile.php"><?php echo htmlspecialchars($_SESSION['user']['name']); ?></a></span>
                                        <?php else: ?>
                                            <figure><a href="../account/dangnhap-dangky.html"><img src="../account/avatar/default_avatar.jpg" alt="Default Avatar"></a></figure>
                                            <span><a class="tenkh" href="../account/dangnhap-dangky.html">Đăng nhập</a></span>
                                        <?php endif; ?>
                                    </div>
                                </li>
                                <li><a href="../account/logout.php">Đăng xuất</a></li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
