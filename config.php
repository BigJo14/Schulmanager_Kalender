<?php
$conn = mysqli_connect("localhost", "root", "", "schull_test");

if (!$conn) {
    die("فشل الاتصال بالسيرفر: " . mysqli_connect_error());
}
?>