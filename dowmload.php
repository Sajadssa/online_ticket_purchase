






<?php
session_start();
include 'Server.php'; // افزودن کدهای مربوط به اتصال به دیتابیس
include 'functions.php';
include 'NavBar.php';
include 'trainticket.php';
 

$user_data = check_login($con);
$user_id = $user_data['id'];

$query = "SELECT usr.username, usr.mobile, usr.national_code, usr.email, cr.Route, TL.departure_date AS depart_date,
    TL.arrival_date AS return_date, tl.train_name, pr.total_price, TL.Description, tl.departure_time, tl.arrival_time,
    pr.purchase_date, pr.passengers
    FROM city_routes CR
    INNER JOIN source_city SC ON SC.idCTS = CR.idCTS
    INNER JOIN destination_city DS ON DS.idCTD = CR.idCTD
    INNER JOIN train_lines TL ON TL.idCTR = CR.idCTR
    INNER JOIN purchase pr ON pr.idTL = TL.idTL
    INNER JOIN users usr ON usr.id = pr.id
    WHERE pr.idbt IN (SELECT MAX(idbt) FROM purchase)
    AND usr.id = '$user_id'
    GROUP BY usr.username, usr.email, usr.mobile, usr.national_code, Cr.Route, TL.departure_date, pr.purchase_date,
    pr.tracking_id, TL.arrival_date, pr.total_price, TL.Description, TL.is_round_trip";

$result = mysqli_query($con, $query);

ob_start(); // شروع بفرست کردن خروجی به بافر
// ایجاد جدول HTML
$html = '<table style="border-collapse: collapse; width: 100%; text-align: center;">
            <tr>
                <th style="border: 1px solid black; padding: 8px;">نام کاربری</th>
                <th style="border: 1px solid black; padding: 8px;">شماره موبایل</th>
                <th style="border: 1px solid black; padding: 8px;">کد ملی</th>
                <th style="border: 1px solid black; padding: 8px;">ایمیل</th>
                <th style="border: 1px solid black; padding: 8px;">مسیر</th>
                <th style="border: 1px solid black; padding: 8px;">تاریخ رفت</th>
                <th style="border: 1px solid black; padding: 8px;">تاریخ برگشت</th>
                <th style="border: 1px solid black; padding: 8px;">نام قطار</th>
                <th style="border: 1px solid black; padding: 8px;">قیمت کل</th>
                <th style="border: 1px solid black; padding: 8px;">توضیحات</th>
                <th style="border: 1px solid black; padding: 8px;">زمان حرکت</th>
                <th style="border: 1px solid black; padding: 8px;">زمان ورود</th>
                <th style="border: 1px solid black; padding: 8px;">تاریخ خرید</th>
                <th style="border: 1px solid black; padding: 8px;">تعداد مسافران</th>
            </tr>';

while ($row = mysqli_fetch_assoc($result)) {
    $html .= '<tr>
                <td style="border: 1px solid black; padding: 8px;">' . $row['username'] . '</td>
                <td style="border: 1px solid black; padding: 8px;">' . $row['mobile'] . '</td>
                <td style="border: 1px solid black; padding: 8px;">' . $row['national_code'] . '</td>
                <td style="border: 1px solid black; padding: 8px;">' . $row['email'] . '</td>
                <td style="border: 1px solid black; padding: 8px;">' . $row['Route'] . '</td>
                <td style="border: 1px solid black; padding: 8px;">' . $row['depart_date'] . '</td>
                <td style="border: 1px solid black; padding: 8px;">' . $row['return_date'] . '</td>
                <td style="border: 1px solid black; padding: 8px;">' . $row['train_name'] . '</td>
                <td style="border: 1px solid black; padding: 8px;">' . $row['total_price'] . '</td>
                <td style="border: 1px solid black; padding: 8px;">' . $row['Description'] . '</td>
                <td style="border: 1px solid black; padding: 8px;">' . $row['departure_time'] . '</td>
                <td style="border: 1px solid black; padding: 8px;">' . $row['arrival_time'] . '</td>
                <td style="border: 1px solid black; padding: 8px;">' . $row['purchase_date'] . '</td>
                <td style="border: 1px solid black; padding: 8px;">' . $row['passengers'] . '</td>
            </tr>';
}

$html .= '</table>';

ob_end_clean(); // تمام محتوای بافر را حذف کنید

// ذخیره فایل HTML
$filename = 'ticket_info.html';
file_put_contents($filename, $html);

// مشخصات فایل دانلود
$file_path = $filename;
$file_size = filesize($file_path);

// تنظیمات هدر برای دانلود فایل
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=" . basename($file_path));
header("Content-Length: " . $file_size);

// خروجی فایل
readfile($file_path);

?>