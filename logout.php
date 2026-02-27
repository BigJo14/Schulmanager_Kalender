<?php
// بدء الجلسة للتمكن من الوصول إليها وإنهائها
session_start();

// مسح جميع بيانات الجلسة من الذاكرة
$_SESSION = array();

// تدمير الجلسة بالكامل
session_destroy();

// توجيه المستخدم فوراً إلى صفحة تسجيل الدخول
header("Location: login.php");
exit();
?>
