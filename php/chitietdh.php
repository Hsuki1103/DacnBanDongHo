<?php
   include '../include/connectdb.php';
   $pdo = connectDB(); // Nhận đối tượng PDO từ hàm connectDB()
   // Lấy ID đồng hồ từ URL
   $id = isset($_GET['id']) ? $_GET['id'] : null;
   if (!$id) {
       echo "Không tìm thấy thông tin đồng hồ!";
       exit;
   }
   
   // Truy vấn thông tin đồng hồ theo ID
   $sql = "SELECT dh.madh, dh.tendh, dh.hinhanh, dh.gia, dh.mahang, dh.mota, hd.tenhang, dh.soseri 
   FROM dongho dh 
   JOIN hangdh hd ON dh.mahang = hd.mahang 
   WHERE dh.madh = ?";

   
   $dpo = selectByID($sql, $id); // Gọi hàm selectByID
   
   // Ánh xạ danh mục trong database sang thư mục
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đồng hồ</title>

    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/normalize.css">
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/icomoon.css">
    <link rel="stylesheet" href="../assets/css/jquery-ui.css">
    <link rel="stylesheet" href="../assets/css/owl.carousel.css">
    <link rel="stylesheet" href="../assets/css/transitions.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/color.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
    <link rel="stylesheet" href="../assets/style/lamdep.css">
    <link rel="stylesheet" href="../assets/style/footer.css">

    <!-- Bootstrap Icon -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
</head>
<body>
    
    <?php include '../include/header.php' ?>

    <div class="container">
        <div class="tg-productdetail">
            <div class="row">
                <div class="col-md-6">
                    <figure class="tg-featureimg">
                        <img src="../assets/img/dongho/<?php echo htmlspecialchars($dpo['hinhanh']); ?>" alt="<?php echo htmlspecialchars($dpo['tendh']); ?>">
                    </figure>
                </div>
                <div class="col-md-6">
                <div class="tg-productcontent">
                        <h3><?php echo htmlspecialchars($dpo['tendh']); ?></h3>
                        <span class="tg-bookwriter text-left">Hãng: <a href="#"><?php echo htmlspecialchars($dpo['tenhang']); ?></a></span>  
                        <p><strong>Số Seri:</strong> <?php echo htmlspecialchars($dpo['soseri']); ?></p>
                        <p><?php echo htmlspecialchars($dpo['mota']); ?></p>
                        <span class="tg-bookprice">
                            <ins><?php echo number_format($dpo['gia'], 0, ',', '.') . ' VNĐ'; ?></ins>
                        </span>
                        <ul class="tg-delevrystock">
                            <li><span>Vận chuyển: Chỉ 32.000 VND trên toàn quốc</span></li>
                            <li><span>Tình trạng: <em>Còn hàng</em></span></li>
                        </ul>
                        <a class="tg-btn2 tg-btnstyletwo2 btnAddToBasketchitietsach" href="javascript:void(0);" data-madh="<?php echo htmlspecialchars($dpo['madh']); ?>">
                            <i class="bi bi-basket2"></i>
                            <em>Thêm vào giỏ hàng</em>
                        </a>
                    </div>
                </div>
            </div>
        </div>
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

    <script src="../assets/javascript/themvaogiohang.js"></script>
</body>
<?php include '../include/footer.php'; ?>

</html>
