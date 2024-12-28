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

// Xác định trang hiện tại
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 12; // Số lượng sản phẩm mỗi trang
$offset = ($page - 1) * $limit; // Vị trí bắt đầu từ

// Câu truy vấn với phân trang
$sqlPaginatedProducts = "SELECT dh.madh, dh.tendh, dh.hinhanh, dh.gia, dh.mahang, hdh.tenhang 
FROM dongho dh 
JOIN hangdh hdh 
ON dh.mahang = hdh.mahang 
LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sqlPaginatedProducts);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$paginatedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy tổng số sản phẩm
$totalProducts = selectSQL("SELECT COUNT(*) as total FROM dongho");
$totalPages = ceil($totalProducts[0]['total'] / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tất Cả Sản Phẩm</title>

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

    <section class="tg-sectionspace tg-haslayout">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="tg-sectionhead">
                        <h2>Tất Cả Sản Phẩm</h2>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <?php foreach ($paginatedProducts as $product): 
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

                    <!-- Phân trang -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $page === $i ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>

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

    <?php include '../include/footer.php'; ?>
</body>
</html>