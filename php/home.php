<?php
require_once 'connectdb.php';

// Khởi tạo kết nối
connectDB(); // Đảm bảo biến $pdo được khởi tạo

// Hàm thực thi câu lệnh SELECT
function selectSQL($sql) {
    global $pdo;
    if (!$pdo) {
        die("Lỗi: Chưa kết nối đến cơ sở dữ liệu.");
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Câu truy vấn lấy sản phẩm mới
$sqldonghomoi = "SELECT dh.madh, dh.tendh, dh.hinhanh, dh.gia, dh.ngaythem, dh.mahang, hdh.tenhang 
FROM dongho dh 
JOIN hangdh hdh 
ON dh.mahang = hdh.mahang 
ORDER BY dh.ngaythem DESC 
LIMIT 8";

// Câu truy vấn lấy ngẫu nhiên 5 sản phẩm
$sqlRandomProducts = "SELECT dh.madh, dh.tendh, dh.hinhanh, dh.gia, dh.mahang, hdh.tenhang 
FROM dongho dh 
JOIN hangdh hdh 
ON dh.mahang = hdh.mahang 
ORDER BY RAND() 
LIMIT 5";

// Thực hiện truy vấn
$randomProducts = selectSQL($sqlRandomProducts);

// Thực hiện truy vấn
$newProducts = selectSQL($sqldonghomoi);

// Ánh xạ danh mục trong database sang thư mục

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đồng Hồ</title>

	<meta name="description" content="">

	<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="../assets/css/normalize.css">
	<link rel="stylesheet" href="../assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="../assets/css/icomoon.css">
	<link rel="stylesheet" href="../assets/css/jquery-ui.css">
	<link rel="stylesheet" href="../assets/css/owl.carousel.css">
	<link rel="stylesheet" href="../assets/css/transitions.css">
	<link rel="stylesheet" href="../assets/css/main.css">
	<link rel="stylesheet" href="../assets/css/color.css">
    <link rel="stylesheet" href="../assets/style/lamdep.css">
	<link rel="stylesheet" href="../assets/css/responsive.css">
	<link rel="stylesheet" href="../assets/style/footer.css">
    <link rel="stylesheet" href="../assets/css/owl.carousel.min.css">
<link rel="stylesheet" href="../assets/css/owl.theme.default.min.css">
<script src="../assets/js/owl.carousel.min.js"></script>


	<!-- Bootstrap Icon -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />

</head>
<body class="tg-home tg-homeone">

	<?php include '../include/header.php' ?>
    
    <section class="tg-sectionspace tg-advertisement-top">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="tg-advertisement-content">
                    <div id="advertisement-slider" class="owl-carousel owl-theme">
                        <div class="item">
                            <img src="../assets/img/qc1.jpg" alt="Quảng cáo 1">
                        </div>
                        <div class="item">
                            <img src="../assets/img/qc2.jpg" alt="Quảng cáo 2">
                        </div>
                        <div class="item">
                            <img src="../assets/img/qc3.jpg" alt="Quảng cáo 3">
                        </div>
                        <div class="item">
                            <img src="../assets/img/qc4.jpg" alt="Quảng cáo 4">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


	<section class="tg-sectionspace tg-haslayout">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "style="margin-top: -130px;">
                <div class="tg-sectionhead">
                    <h2 >Sản phẩm bán chạy</h2>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div id="tg-bestsellingbooksslider" class="tg-bestsellingbooksslider tg-bestsellingbooks owl-carousel">
                <?php foreach ($randomProducts as $product): ?>
    <div class="item">
        <div class="tg-postbook">
            <figure class="tg-featureimg">
                <div class="tg-bookimg">
                    <a href="./chitietdh.php?id=<?php echo htmlspecialchars($product['madh']); ?>">
                        <div class="tg-frontcover">
                            <img src="../assets/img/dongho/<?php echo htmlspecialchars($product['hinhanh']); ?>" alt="<?php echo htmlspecialchars($product['tendh']); ?>">
                        </div>
                        <div class="tg-backcover">
                            <img src="../assets/img/dongho/<?php echo htmlspecialchars($product['hinhanh']); ?>" alt="<?php echo htmlspecialchars($product['tendh']); ?>">
                        </div>
                    </a>
                </div>
            </figure>
            <div class="tg-postbookcontent">
                <div class="tg-booktitle">
                    <h3><a href="./chitietdh.php?id=<?php echo htmlspecialchars($product['madh']); ?>"><?php echo htmlspecialchars($product['tendh']); ?></a></h3>
                </div>
                <span class="tg-bookwriter">Hãng: <a href="#"><?php echo htmlspecialchars($product['tenhang']); ?></a></span>
                <span class="tg-bookprice">
                    <ins><?php echo number_format($product['gia'], 0, ',', '.'); ?> VND</ins>
                </span>
            </div>
        </div>
    </div>
<?php endforeach; ?>


                </div>
            </div>
        </div>
    </div>
</section>

	<div class="container">
		<div class="tg-sectionhead">
			<h2>Đồng hồ mới</h2>
		</div>
		<div class="row">
			<?php foreach ($newProducts as $product): 
				$imagePath = "../assets/img/dongho/" . $product['hinhanh'];
				$detailLink = "./chitietdh.php?id=" . $product['madh'];
			?>  
				<div class="col-xs-6 col-sm-6 col-md-4 col-lg-3">
					<div class="tg-postbook2">
						<figure class="tg-featureimg2">
							<div class="tg-bookimg2">
                            <div class="tg-frontcover2">
    <a href="<?php echo htmlspecialchars($detailLink); ?>">
        <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($product['tendh']); ?>">
    </a>
</div>
<div class="tg-backcover2">
    <a href="<?php echo htmlspecialchars($detailLink); ?>">
        <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($product['tendh']); ?>">
    </a>
</div>
							</div>
						</figure>
						<div class="tg-postbookcontent">
							<div class="tg-booktitle2">
								<h3><a href="<?php echo htmlspecialchars($detailLink); ?>"><?php echo htmlspecialchars($product['tendh']); ?></a></h3>
							</div>
							<div class="tg-bookwriter2">
								<h3>Tên hãng: <a href="#"><?php echo htmlspecialchars($product['tenhang']); ?></a></h3>
							</div>
							<span class="tg-bookprice2">
								<ins><?php echo number_format($product['gia'], 0, ',', '.') . ' VNĐ'; ?></ins>
							</span>
							<a class="tg-btn2 tg-btnstyletwo2" href="javascript:void(0);" data-madh="<?php echo htmlspecialchars($product['madh']); ?>">
								<i class="bi bi-basket2"></i>
								<em>Thêm vào giỏ hàng</em>
							</a>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>	
    <a class="tg-btn" href="../php/sanpham.php" >View All</a>

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

	<script src="../assets/javascript/themvaogiohang.js"></script>

    <!-- Script cho quảng cáo -->
    <script>
    $(document).ready(function(){
    $('#advertisement-slider').owlCarousel({
        loop: true,         // Tự động lặp lại
        autoplay: true,     // Tự động chạy
        autoplayTimeout: 3000, // Thời gian dừng mỗi slide (3 giây)
        dots: true,         // Hiển thị điểm dừng
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    });
    });
    </script>

</body>

<?php include '../include/footer.php'; ?>
</html>
