<?php
session_start();
include '../include/connectdb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password']);

    if (!$email || empty($password)) {
        echo "<script type='text/javascript'>alert('Vui lòng nhập email hợp lệ và mật khẩu!'); window.location.href = 'dangnhap-dangky.html';</script>";
        exit;
    }

    try {
        $sql = "SELECT * FROM khachhang WHERE email = ?";
        $conn = connectDB();
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Lưu thông tin người dùng vào session
            $_SESSION['id_taikhoan'] = $user['makh']; // Mã khách hàng (id)
            $_SESSION['email'] = $user['email'];
            $_SESSION['user'] = [
                'id' => $user['makh'], // Mã khách hàng
                'name' => $user['hotenkh'], // Họ tên khách hàng
                'email' => $user['email'],
                'sdt' => $user['sdt'], // Số điện thoại
                'diachi' => $user['diachi'], // Địa chỉ
                'avatar' => $user['avatar'] ?? '../account/avatar/default_avatar.jpg',
            ];
        
            // Chuyển hướng đến trang chính
            header("Location: ../index.php");
            exit;
        } else {
            echo "<script type='text/javascript'>alert('Thông tin đăng nhập không chính xác'); window.location.href = 'dangnhap-dangky.html';</script>";
            exit;
        }
    } catch (PDOException $e) {
        error_log("Lỗi truy vấn: " . $e->getMessage());
        echo "<script type='text/javascript'>alert('Có lỗi xảy ra, vui lòng thử lại sau!'); window.location.href = 'dangnhap-dangky.html';</script>";
        exit;
    }
}
