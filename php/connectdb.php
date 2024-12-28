<?php

function connectDB() {
    global $pdo;
    if ($pdo === null) { // Đảm bảo kết nối chỉ được tạo một lần
        $servername = "localhost";
        $database = "donghoshop";
        $username = "root";
        $password = "";

        try {
            $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Kết nối thất bại: " . $e->getMessage());
        }
    }
    return $pdo; // Trả về kết nối nếu cần sử dụng
}

if (!function_exists('selectSQL')) {
    function selectSQL($sql) {
        $conn = connectDB();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $conn = null; // Đóng kết nối
        return $result;
    }
}


function selectByID($sql,$id){
    $conn = connectDB();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$book) {
        echo "Đồng hồ không tồn tại!";
        exit;
    }

    return $book;
}
?>
