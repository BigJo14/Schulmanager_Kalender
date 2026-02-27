<?php
include 'config.php';

$message = "";

if (isset($_POST['register'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];

    // تشفير كلمة المرور لحماية البيانات
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    // التحقق من أن الاسم غير مكرر
    $check_user = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
    
    if (mysqli_num_rows($check_user) > 0) {
        $message = "خطأ: اسم المستخدم موجود مسبقاً!";
    } else {
        $sql = "INSERT INTO users (username, password) VALUES ('$user', '$hashed_password')";
        if (mysqli_query($conn, $sql)) {
            $message = "تم التسجيل بنجاح! يمكنك الآن <a href='login.php'>تسجيل الدخول</a>";
        } else {
            $message = "حدث خطأ أثناء التسجيل.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إنشاء حساب جديد</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <style>
        .register-container { max-width: 400px; margin: 100px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); text-align: center; }
        input { width: 90%; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>إنشاء حساب جديد</h2>
        <p style="color: red;"><?php echo $message; ?></p>
        <form method="POST">
            <input type="text" name="username" placeholder="اسم المستخدم" required>
            <input type="password" name="password" placeholder="كلمة المرور" required>
            <button type="submit" name="register">تسجيل</button>
        </form>
        <p>لديك حساب؟ <a href="login.php">دخول</a></p>
    </div>
</body>
</html>
