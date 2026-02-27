<?php
// 1. بدء الجلسة وتأمين الصفحة
session_start();

// إذا لم يكن المستخدم مسجلاً للدخول، يتم تحويله لصفحة الدخول فوراً
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';
$table_name = "calender_event";

// 2. معالجة إضافة موعد جديد
if (isset($_POST['add_event'])) {
    $subject = mysqli_real_escape_string($conn, $_POST['subject_name']);
    $date = $_POST['subject_date'];
    if (!empty($subject) && !empty($date)) {
        $insert_query = "INSERT INTO $table_name (titel, event_date) VALUES ('$subject', '$date')";
        mysqli_query($conn, $insert_query);
        header("Location: index.php");
        exit();
    }
}

// 3. معالجة حذف موعد
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM $table_name WHERE id = $id");
    header("Location: index.php");
    exit();
}

// 4. جلب المواعيد من قاعدة البيانات
$query = "SELECT * FROM $table_name";
$result = mysqli_query($conn, $query);
$events = [];
while ($row = mysqli_fetch_assoc($result)) {
    // تخزين المواعيد في مصفوفة (التاريخ -> [العنوان والآيدي])
    $events[$row['event_date']] = ['titel' => $row['titel'], 'id' => $row['id']];
}

// 5. إعدادات التقويم للأسبوع الحالي
$monday_this_week = date('Y-m-d', strtotime('monday this week'));
$days_names_ar = ["الاثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة", "السبت", "الأحد"];
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقويم المعهد الذكي</title>
    <!-- ربط ملف الـ CSS المستقل مع إضافة توقيت لإجبار التحديث -->
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

    <div class="header">
        <h1>📅 نظام التقويم المدرسي</h1>
        <div class="user-info">
            مرحباً، <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> | 
            <a href="logout.php" style="color: red; text-decoration: none;">تسجيل الخروج</a>
        </div>
    </div>

    <!-- نموذج إضافة موعد جديد -->
    <div class="form-container">
        <h3>إضافة حصة أو امتحان جديد</h3>
        <form method="POST">
            <input type="text" name="subject_name" placeholder="اسم المادة" required>
            <input type="date" name="subject_date" required>
            <button type="submit" name="add_event">إضافة للجدول</button>
        </form>
    </div>

    <!-- جدول التقويم -->
    <table>
        <thead>
            <tr>
                <?php
                for ($i = 0; $i < 7; $i++) {
                    $date_str = date('Y-m-d', strtotime("$monday_this_week +$i days"));
                    echo "<th>" . $days_names_ar[$i] . "<br><small>$date_str</small></th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php
                for ($i = 0; $i < 7; $i++) {
                    $current_day = date('Y-m-d', strtotime("$monday_this_week +$i days"));
                    echo "<td>";
                    if (isset($events[$current_day])) {
                        echo "<div class='event'>";
                        // زر الحذف الصغير
                        echo "<a href='index.php?delete_id=" . $events[$current_day]['id'] . "' class='delete-btn' onclick='return confirm(\"هل أنت متأكد من الحذف؟\")'>✖</a>";
                        echo htmlspecialchars($events[$current_day]['titel']);
                        echo "</div>";
                    } else {
                        echo "<span class='no-event'>فارغ</span>";
                    }
                    echo "</td>";
                }
                ?>
            </tr>
        </tbody>
    </table>

    <div style="text-align: center; margin-top: 20px; color: #888; font-size: 12px;">
        <p>متصل بقاعدة البيانات: schull_test | سيرفر المعهد</p>
    </div>

</body>
</html>
