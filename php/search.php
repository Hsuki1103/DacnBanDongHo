<?php
require_once 'connectdb.php';

$keyword = isset($_GET['search']) ? $_GET['search'] : '';

// Khởi tạo kết nối
connectDB(); // Đảm bảo biến $pdo được khởi tạo

// Hàm thực thi câu lệnh SELECT
function selectSQL($sql, $params = []) {
    global $pdo;
    if (!$pdo) {
        die("Lỗi: Chưa kết nối đến cơ sở dữ liệu.");
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Nếu có từ khóa tìm kiếm, thực hiện truy vấn
if ($keyword !== '') {
    $sqlSearchProducts = "
        SELECT dh.madh, dh.tendh, dh.hinhanh, dh.gia, dh.mahang, hdh.tenhang 
        FROM dongho dh 
        JOIN hangdh hdh 
        ON dh.mahang = hdh.mahang 
        WHERE dh.tendh LIKE :keyword 
        ORDER BY dh.ngaythem DESC
    ";
    $stmt = $pdo->prepare($sqlSearchProducts);
    $stmt->execute(['keyword' => "%$keyword%"]);
    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $searchResults = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm kiếm sản phẩm</title>

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

    <!-- Bootstrap Icon -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />

</head>
<body class="tg-home tg-homeone">

    <?php include '../include/header.php'; ?>

    <section class="tg-sectionspace tg-haslayout">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="tg-sectionhead">
                        <h2>Kết quả tìm kiếm cho "<?php echo htmlspecialchars($keyword); ?>"</h2>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <?php if (count($searchResults) > 0): ?>
                            <?php foreach ($searchResults as $product): ?>
                                <div class="col-xs-6 col-sm-6 col-md-4 col-lg-3">
                                    <div class="tg-postbook2">
                                        <figure class="tg-featureimg2">
                                            <div class="tg-bookimg2">
                                                <div class="tg-frontcover2">
                                                    <a href="./chitietdh.php?id=<?php echo htmlspecialchars($product['madh']); ?>">
                                                        <img src="../assets/img/dongho/<?php echo htmlspecialchars($product['hinhanh']); ?>" alt="<?php echo htmlspecialchars($product['tendh']); ?>">
                                                    </a>
                                                </div>
                                            </div>
                                        </figure>
                                        <div class="tg-postbookcontent">
                                            <div class="tg-booktitle">
                                                <h3><a href="./chitietdh.php?id=<?php echo htmlspecialchars($product['madh']); ?>"><?php echo htmlspecialchars($product['tendh']); ?></a></h3>
                                            </div>
                                            <span class="tg-bookprice">
                                                <ins><?php echo number_format($product['gia'], 0, ',', '.'); ?> VND</ins>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Không tìm thấy sản phẩm nào phù hợp với từ khóa "<?php echo htmlspecialchars($keyword); ?>"</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>
</html>
