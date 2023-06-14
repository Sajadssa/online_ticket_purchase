<?php
session_start();
require_once 'functions.php';
require_once 'Server.php';
 

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
GROUP BY usr.username, usr.email, usr.mobile, Cr.Route, TL.departure_date, pr.purchase_date,
pr.tracking_id, TL.arrival_date, pr.total_price, TL.Description, TL.is_round_trip";

$result = mysqli_query($con, $query);

$html = '
<!DOCTYPE html>
<html lang="fa" dir="rtl" >

<head>
    <meta charset="UTF-8">
    <title>اطلاعات بلیط</title>
   <link rel="stylesheet" href="/assets/css/style.css">
   <link rel="stylesheet" href="/assets/css/font.css">
   
</head>

<body >';


$html = '<table class="ticket-table">

<tr class="print">
                <th colspan="14" style="text-align: center; border:none">
                    <img src="./assets/images/logo.jpg" alt="logo" style="max-width: 200px; height: auto;">
                    
                </th>
                <div class="print">
                
                <h1 style="margin:0 auto;text-align:center">فرم خرید بلیط قطار</h1>
                    <h2  >شرکت TRAIN TICKET سفر خوبی برای شما آرزو می کند.</h2>
                </div>
            </tr>
        <tr>
            <th>نام مسافر</th>
            <th>شماره موبایل</th>
          
            <th>ایمیل</th>
            <th>مسیر</th>
            <th>تاریخ رفت</th>
            <th>تاریخ برگشت</th>
            <th>نام قطار</th>
            <th>قیمت کل</th>
            <th>توضیحات</th>
            <th>زمان حرکت</th>
            <th>زمان ورود</th>
            <th>تاریخ خرید</th>
            <th>تعداد مسافران</th>
            <th class="no-print">چاپ</th>
        </tr>';

while ($row = mysqli_fetch_assoc($result)) {
    $html .= '<tr>
            <td>' . $row['username'] . '</td>
            <td>' . $row['mobile'] . '</td>
        
            <td>' . $row['email'] . '</td>
            <td>' . $row['Route'] . '</td>
            <td>' . $row['depart_date'] . '</td>
            <td>' . $row['return_date'] . '</td>
            <td>' . $row['train_name'] . '</td>
            <td>' . $row['total_price'] . '</td>
            <td>' . $row['Description'] . '</td>
            <td>' . $row['departure_time'] . '</td>
            <td>' . $row['arrival_time'] . '</td>
            <td>' . $row['purchase_date'] . '</td>
            <td>' . $row['passengers'] . '</td>
            <td class="no-print">
                <a href="#" onclick="window.print()" class="download-button">چاپ بلیط</a>
            </td>
        </tr>';
}

$html .= '
    </table>';

echo '<style>

.ticket-table {
  width: 1268px;
  border-collapse: collapse;
  color: var(--primaryColor);
 margin-top: 20px;
font-family: "IranYekan";
  font-size: 15px;
 direction:rtl;
 margin:0 auto;
   background-color: gray;
  border: none;
}

.ticket-table th,
.ticket-table td {
  border: 1px solid #ddd;
  padding: 8px;
  color:var(--mainBlack);

  font-size: 12px;
   
 
  background-color: white;

}

.ticket-table th {
 
  text-align: center;
  background-color: #c6c6c6;
   
}

.ticket-table tr:nth-child(even) {
 background-color: var(--mainWhite);
}



.ticket-table td {
  text-align: center;
}
.ticket-table td a{
  color:green;
  text-decoration: none;
}

h1,h2{
  margin:0 auto;
  text-align:center; 
  direction:rtl;
  font-family: "IranYekan";
  font-size: 20px;
}


/* logo in print ticket */
.print{
  display: none;
}

/* عدم نمایش navbar */
@media print {
  .no-print {
    display: none;
  }
  .print{
    display: block;
  }
}

a {text-decoration:none; margin-top: 20px;
font-family: "IranYekan";
font-size: 15px;
margin:50px auto;
}



</style>';


// نمایش جدول در صفحه
echo $html;

echo '<a href="login.php" class="no-print">صفحه ورود کاربر</a>';
?>
