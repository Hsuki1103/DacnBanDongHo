    <?php
    session_start();
    include '../include/connectdb.php';

    // Kiểm tra nếu người dùng chưa đăng nhập, chuyển hướng đến trang đăng nhập
    if (!isset($_SESSION['user'])) {
        header("Location: dangnhap-dangky.html");
        exit;
    }

    // Lấy thông tin người dùng từ session
    $user_id = $_SESSION['user']['id'];
    $user_name = $_SESSION['user']['name'];
    $user_email = $_SESSION['user']['email'];
    $user_phone = $_SESSION['user']['sdt'];
    $user_address = $_SESSION['user']['diachi'];
    $user_avatar = $_SESSION['user']['avatar'];

    // Kiểm tra nếu có thông tin POST từ form chỉnh sửa
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name']);
        $phone = trim($_POST['phone']);
        $address = trim($_POST['address']);
        $avatar = $user_avatar; // Mặc định giữ nguyên avatar cũ

        // Xử lý upload ảnh nếu có file mới
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "../account/avatar/"; // Thư mục lưu ảnh
            $avatar_name = basename($_FILES['avatar']['name']); // Tên file
            $target_file = $target_dir . $avatar_name; // Đường dẫn đầy đủ

            // Kiểm tra và di chuyển file upload
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target_file)) {
                $avatar = $target_file; // Lưu đường dẫn đầy đủ vào biến
            } else {
                echo "<script>alert('Không thể upload ảnh!');</script>";
            }
        }

        // Cập nhật thông tin vào CSDL
        try {
            $conn = connectDB();
            $sql_update = "UPDATE khachhang SET hotenkh = ?, sdt = ?, diachi = ?, avatar = ? WHERE makh = ?";
            $stmt = $conn->prepare($sql_update);
            $stmt->execute([$name, $phone, $address, $avatar, $user_id]);

            // Cập nhật lại thông tin trong session
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['sdt'] = $phone;
            $_SESSION['user']['diachi'] = $address;
            $_SESSION['user']['avatar'] = $avatar;

            // Thông báo thành công
            echo "<script>alert('Cập nhật thông tin thành công!');</script>";
        } catch (PDOException $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Thông tin cá nhân</title>
        <link rel="stylesheet" href="../assets/style/user_profile.css">
    </head>
    <body>
        <div class="container">
            <h1>Thông tin cá nhân</h1>

            <div class="user-info">
                <img src="<?php echo $user_avatar; ?>" alt="Avatar" class="avatar">
                <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($user_name); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user_email); ?></p>
                <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($user_phone); ?></p>
                <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($user_address); ?></p>
                <button id="editButton">Chỉnh sửa thông tin</button>
            </div>

            <!-- Form chỉnh sửa thông tin -->
            <div id="editForm" style="display:none;">
                <h2>Chỉnh sửa thông tin</h2>
                <form action="user_profile.php" method="POST" enctype="multipart/form-data">
                    <label for="name">Họ tên:</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($user_name); ?>" required>

                    <label for="phone">Số điện thoại:</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($user_phone); ?>" required>

                    <label for="address">Địa chỉ:</label>
                    <input type="text" name="address" value="<?php echo htmlspecialchars($user_address); ?>" required>

                    <label for="avatar">Avatar:</label>
                    <input type="file" name="avatar">

                    <button type="submit">Lưu thông tin</button>
                </form>
                <button id="cancelButton">Hủy bỏ</button>
            </div>
        </div>

        <script src="../assets/javascript/use_profile.js"></script>
    </body>
    </html>
