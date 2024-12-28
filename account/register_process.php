<?php

include '../include/connectdb.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);

    // Kiểm tra các trường trống
    if (empty($email) || empty($password) || empty($confirm_password) || empty($name) || empty($address) || empty($phone)) {
        echo "<script type='text/javascript'>alert('Vui lòng nhập đầy đủ thông tin!'); window.location.href = 'dangnhap-dangky.html';</script>";
        exit;
    }

    // Kiểm tra xác nhận mật khẩu
    if ($password !== $confirm_password) {
        echo "<script type='text/javascript'>alert('Xác nhận mật khẩu không khớp'); window.location.href = 'dangnhap-dangky.html';</script>";
        exit; // Dừng thực thi nếu mật khẩu không khớp
    }

    // Hash mật khẩu
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    $conn = connectDB();

    // Kiểm tra email đã tồn tại
    $sql_check = "SELECT * FROM khachhang WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->execute([$email]);

    if ($stmt_check->rowCount() > 0) {
        echo "<script type='text/javascript'>alert('Email đã được sử dụng'); window.location.href = 'dangnhap-dangky.html';</script>";
        exit; // Dừng thực thi nếu email đã tồn tại
    }

    // Chèn thông tin khách hàng
    $avatar_default = '../account/avatar/default_avatar.jpg';
    $sql_insert = "INSERT INTO khachhang (email, password, hotenkh, sdt, diachi, avatar) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);

    if ($stmt_insert->execute([$email, $password_hashed, $name, $phone, $address, $avatar_default])) {
        echo "<script type='text/javascript'>alert('Đăng ký thành công'); window.location.href = 'dangnhap-dangky.html';</script>";
        exit;
    } else {
        echo "<script type='text/javascript'>alert('Lỗi: Không thể đăng ký tài khoản!'); window.location.href = 'dangnhap-dangky.html';</script>";
        exit;
    }
}


?>
