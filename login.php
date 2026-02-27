<?php
session_start(); // لبدء الجلسة وتذكر المستخدم
include 'config.php';

$message = "";

if (isset($_POST['login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];

    // البحث عن المستخدم في القاعدة
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // التأكد من كلمة المرور المشفرة
        if (password_verify($pass, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: index.php"); // التوجه للتقويم بعد النجاح
            exit();
        } else {
            $message = "كلمة المرور خاطئة!";
        }
    } else {
        $message = "اسم المستخدم غير موجود!";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <style>
        .login-container { max-width: 400px; margin: 100px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); text-align: center; }
        input { width: 90%; margin-bottom: 15px; padding: 10px; }
        button { width: 95%; background-color: #007bff; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>تسجيل الدخول</h2>
        <p style="color: red;"><?php echo $message; ?></p>
        <form method="POST">
            <input type="text" name="username" placeholder="اسم المستخدم" required>
            <input type="password" name="password" placeholder="كلمة المرور" required>
            <button type="submit" name="login">دخول</button>
        </form>
        <p>ليس لديك حساب؟ <a href="register.php">سجل هنا</a></p>
    </div>
</body>
</html>
