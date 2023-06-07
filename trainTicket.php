

<?php
session_start();
include 'Server.php'; // افزودن کدهای مربوط به اتصال به دیتابیس
include 'functions.php';
include 'NavBar.php';

$user_data = check_login($con);

// بررسی ارسال اطلاعات فرم به سرور
if(isset($_POST['buyticket'])) {
    $source = $_POST['source'];
    $destin = $_POST['destin'];
    $depart_date = $_POST['depart_date'];
    $return_date = isset($_POST['return_date']) ? $_POST['return_date'] : '';
    $type = $_POST['type'];
    $passengers = $_POST['passengers'];

    if(empty($source)){
        echo "لطفاً فیلد مبدأ را وارد کنید.";
    }
    elseif(empty($destin)){
        echo "لطفاً فیلد مقصد را وارد کنید.";
    }
    elseif(empty($depart_date)){
        echo "لطفاً فیلد تاریخ رفت را وارد کنید.";
    }
    elseif($type == 'return' && empty($_POST['return_date'])){
        echo "لطفاً فیلد تاریخ برگشت را وارد کنید.";
    }
    elseif($type == 'return'){
        echo "بلیط شما با موفقیت خریداری شد! تاریخ برگشت: $return_date";
    }
    elseif(empty($passengers)){
        echo "لطفاً تعداد مسافران را وارد کنید.";
    }
    
}
else{
    echo '<div style=" position :absolute;margin:30px 50px; color:tomato">   خطا در اتصال سرور </div>';
}

?>

<!DOCTYPE html>
<html dir="rtl">

<head>
    <meta charset="utf-8">
    <title>فرم خرید بلیط قطار</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="assets/css/kamadatepicker.min.css">
    <!-- تایپ عبارت مورد نظر و یافتن در اپشن -->

 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.default.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js"></script>

<?php
// در صفحه PHP خود، بررسی کنید که آیا کوکی sourceSelectizeValue مقدار دارد یا خیر
$sourceSelectizeValue = isset($_COOKIE['sourceSelectizeValue']) ? $_COOKIE['sourceSelectizeValue'] : '';

// بررسی کوکی و تنظیم مقدار پیش‌فرض
$defaultOption = '';
if (!empty($sourceSelectizeValue)) {
    // اگر کوکی مقدار دارد، مقدار پیش‌فرض را تنظیم کنید
    $defaultOption = $sourceSelectizeValue;
}
?>

<!-- افزودن استایل ها و اسکریپت های مورد نیاز -->

<select id="source" name="source" placeholder="مبدا">
    <option value="">انتخاب کنید</option>
    <!-- افزودن گزینه های دیگر -->
</select>

<script>
$(document).ready(function() {
    var sourceSelectize = $('#source').selectize();

    // تنظیم مقدار پیش‌فرض در selectize
    var sourceSelectizeInstance = sourceSelectize[0].selectize;
    sourceSelectizeInstance.setValue('<?php echo $defaultOption; ?>');

    // بازیابی مقدار قبلی از localStorage (اگر وجود داشت)
    var storedValue = localStorage.getItem('sourceSelectizeValue');
    if (storedValue) {
        sourceSelectizeInstance.setValue(storedValue);
    }

    // ذخیره مقدار انتخاب شده در localStorage و کوکی
    sourceSelectizeInstance.on('change', function(value) {
        localStorage.setItem('sourceSelectizeValue', value);
        document.cookie = 'sourceSelectizeValue=' + value + '; path=/';
    });

    // بررسی وجود کوکی و اجرای عملیات مربوطه
    var hasCookie = document.cookie.indexOf('sourceSelectizeValue=') !== -1;
    if (!hasCookie) {
        // اگر کوکی وجود نداشته باشد، انتخاب اولین گزینه را فعال کنید
        var options = sourceSelectizeInstance.options;
        var firstOptionValue = Object.keys(options)[0];
        sourceSelectizeInstance.setValue(firstOptionValue);
    }
});
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
</head>
<body>
    <div class="container">
        <form class="formbuy" method="post" action="#">
            <label for="type">نوع سفر:</label>
            <select id="type" name="type" required>
                <option value="one-way">یک طرفه</option>
                <option value="round-trip">رفت و برگشت</option>
            </select>
            <select name="source" id="source" placeholder="شهر مبدا" required>
                <option value="">شهر مبدا</option>
                <?php
                $select = "SELECT * FROM source_city";
                $result = mysqli_query($con, $select);
                if (isset($_POST['buyticket'])){
                    $id_CTS = $_POST['idCTS'];
                }
                foreach ($result as $key => $value) {
                    echo '<option value="' . $value['idCTS'] . '">' . $value['City'] . '</option>';
                }
                ?>
            </select>
            <select name="destin" id="destin" placeholder="شهر مقصد" required>
                <option value="">شهر مقصد</option>
                <?php

                
                $select = "SELECT * FROM destination_city";
                $result = mysqli_query($con, $select);
                if (isset($_POST['buyticket'])){
                    $id_CTD = $_POST['idCTD'];
                }
                foreach ($result as $key => $value) {
                    echo '<option value="' . $value['idCTD'] . '">' . $value['city'] . '</option>';
                }
                ?>
            </select>
<label for="depart-date">تاریخ رفت:</label>
<input type="text" style="font-family: IranYekan;" id="datepicker" name="depart_date" placeholder="تاریخ رفت" required>
<label for="return-date">تاریخ برگشت:</label>
<input type="text" id="return_date" placeholder="تاریخ برگشت" name="return_date" disabled>
<label for="passengers">تعداد مسافران:</label>
<input type="number" id="passengers" name="passengers" min="1" max="10" required >
 

            <input type="submit" name="buyticket" value="جستجو">
        </form>
    </div>
<?php
// کلیک باتن چستجو توسط کاربر
if (isset($_POST['buyticket'])) {
    $source = $_POST['source'];
    $destin = $_POST['destin'];
    $depart_date = $_POST['depart_date'];
    $return_date = isset($_POST['return_date']) ? $_POST['return_date'] : '';
    $type = $_POST['type'];

    $query = "SELECT CR.*, SC.City AS source_city, DS.city AS destination_city, TL.departure_date as depart_date, TL.arrival_date as return_date, TL.price, TL.Description, TL.is_round_trip,TL.idTL,
    TL.available_tickets
    FROM city_routes CR 
    INNER JOIN source_city SC ON SC.idCTS = CR.idCTS 
    INNER JOIN destination_city DS ON DS.idCTD = CR.idCTD 
    INNER JOIN train_lines TL ON TL.idCTR = CR.idCTR 
    WHERE SC.idCTS = '$source' AND DS.idCTD = '$destin' AND TL.departure_date = '$depart_date'";

    if ($type == 'round-trip') {
        if (isset($_POST['return_date'])) {
            $return_date = $_POST['return_date'];
            $query .= " AND TL.arrival_date = '$return_date' AND TL.is_round_trip = '1'";
        } else {
            $return_date = '';
            $query .= " AND TL.is_round_trip = '1'";
        }
    } else {
        $query .= " AND TL.is_round_trip = '0'";
    }

    $result = mysqli_query($con, $query);
   

    if (mysqli_num_rows($result) > 0  ) {
     
       

           
            $count = 1;
            while ($row = mysqli_fetch_assoc($result)) {

         
            $available_tickets = $row['available_tickets'];
            // echo $available_tickets;

                if($available_tickets>0){
                echo "<div  style='margin:20px 50px; color:lightgreen'> نتایج جستجو</div>";

                echo "<table class='table_search' style=' width:1268px;
        border-collapse: collapse;
        margin-top: 20px;
        color: var(--title-color);' >";
                echo "<thead>";
                echo "<tr>";
                echo "<th style=' padding: 10px;
        text-align: center;
        border: 1px solid transparent;' >ثبت خرید</th>";
                echo "<th>ردیف</th>";
                echo "<th>مبدا</th>";
                echo "<th>مقصد</th>";
                echo "<th>تاریخ رفت</th>";
                echo "<th>تاریخ برگشت</th>";
                echo "<th>قیمت</th>";
                echo "<th>توضیحات</th>";
                echo "<th>نوع سفر</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                
                
                echo "<tr>";
                echo "<td>";
                echo "<form method='POST' action=''>
      <input type='hidden' name='source' value='" . $source . "'>
      <input type='hidden' name='destin' value='" . $destin . "'>
      <input type='hidden' name='depart_date' value='" . $depart_date . "'>
      <input type='hidden' name='return_date' value='" . $return_date . "'>
      <input type='hidden' name='type' value='" . $type . "'>
      <input type='hidden' name='passengers' value='" . $passengers . "'>
      <input type='submit' style='box-shadow: 0 20px 120px rgba(209, 186, 186, 0.959);
      background-color: var(--primaryColor);
      border: none;
      padding: 0.5rem;
      border-radius: 7px;
      color: var(--text-color);
      font-size: 14px;
      color:white;
      width: 80px;
      cursor: pointer;
      transition: 1s ease-in-out;'
      name='purchase'
      value='ثبت خرید'>
   </form>";

                echo "</td>";

                echo "</td>";
                echo "<td>" . $count . "</td>";
                echo "<td>" . $row['source_city'] . "</td>";
                echo "<td>" . $row['destination_city'] . "</td>";
                echo "<td>" . $row['depart_date'] . "</td>";
                echo "<td>" . $row['return_date'] . "</td>";
                echo "<td>" . $row['price'] . " تومان</td>";
                echo "<td>" . $row['Description'] . "</td>";
                echo "<td>";
                if ($row['is_round_trip'] == 0) {
                    echo "یک طرفه";
                } elseif ($row['is_round_trip'] == 1) {
                    echo "رفت و برگشت";
                } else {
                    echo "نامشخص";
                }
                echo "</td>";
                echo "</tr>";

                $count++;
                echo "</tbody>";
                echo "</table>";








                }
                
                else {
           /*      $update_available_tickets = "UPDATE train_lines SET available_tickets = 0";
                $result_update_tickets = mysqli_query($con, $update_available_tickets); */
                    echo "<div  style='margin:20px 50px; color:tomato'> ظرفیت این سرویس تکمیل است لطفاً سرویس دیگری را انتخاب کنید    .</div>";

                }


               

            }


        

    }
    
    else{
        echo "<div  style='margin:20px 50px; color:tomato'>بلیطی برای این مشخصات یافت نشد.</div>";

    }
   
} 

// اگر کاربر باتن رو کلیک نکرده بود
else {
    echo "<div style='margin:20px 50px; color:tomato'>لطفاً فرم را پر کنید و برای جستجو ارسال کنید.</div>";
}

?>

<!-- عمل ثبت بلیط در جدول خرید بلیط -->
<?php
// بررسی کاربر آیا لاگین شده یانه
$user_data = check_login($con);
if(!$user_data){

   header("Location:Login.php"); 
} else {

    $user_data = check_login($con);
    $user_id = $user_data['id'];

    // echo $user_id;

    if (isset($_POST['purchase'])) {
        // دریافت مسیر یافت شده

        $source = $_POST['source'];
        $destin = $_POST['destin'];
        $depart_date = $_POST['depart_date'];
        $return_date = isset($_POST['return_date']) ? $_POST['return_date'] : '';
        $type = $_POST['type'];
        $passengers = $_POST['passengers'];

        $query = "SELECT CR.*, SC.City AS source_city, DS.city AS destination_city, TL.departure_date as depart_date, TL.arrival_date as return_date, TL.price, TL.Description, TL.is_round_trip,
        TL.idTL,TL.available_tickets FROM city_routes CR 
        INNER JOIN source_city SC ON SC.idCTS = CR.idCTS 
        INNER JOIN destination_city DS ON DS.idCTD = CR.idCTD 
        INNER JOIN train_lines TL ON TL.idCTR = CR.idCTR 
        WHERE SC.idCTS = '$source' AND DS.idCTD = '$destin' AND TL.departure_date = '$depart_date'";

        // اجرای کوئری بالا
        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $idTL = $row['idTL'];
            $price = $row['price'];
            $available_tickets = $row['available_tickets'];
         /*    echo $available_tickets; */
            $total_price = $price * $passengers;


        } else {
            $error = mysqli_error($con);
            if (strpos($error, 'Duplicate entry') !== false) {
                echo '<div class="error-message">خطا: رکورد تکراری وجود دارد.</div>';
            } else {
                echo '<div class="error-message">خطا در انجام عملیات.</div>';
            }

        }

        $total_price = $price * $passengers;
        // echo $total_price;

        $user_id = $user_data['id'];



        $query_wallet = "SELECT amount FROM wallet WHERE id = '$user_id'";
        $result_wallet = mysqli_query($con, $query_wallet);


        $row_wallet = mysqli_fetch_assoc($result_wallet);
        $amount = $row_wallet['amount'];
  
        // موجودی است و باید بررسی شود
        if (mysqli_num_rows($result_wallet) > 0) {


            if($amount< $total_price){
                // Insert data into Temporory table
                $query = "INSERT INTO Tempororys (id, idTL, passengers, total_price,available_tickets) VALUES ('$user_id', '$idTL', '$passengers', '$total_price','$available_tickets')";
                mysqli_query($con, $query);

                // موجودی کافی نیست. نمایش پیغام خطا به صورت alert
                echo '<script>alert("موجودی کافی نیست. لطفاً موجودی کیف پول خود را آپدیت کنید.");</script>';
                // انتقال کاربر به صفحه zarinpalapi.php
                echo '<script>window.location.href = "zarinpalapi.php";</script>';




            }
            // بررسی موجودی با قیمت کل

            if ($amount>0 && $amount >= $total_price) {

             

                // ثبت خرید بلیط در جدول purchase
                $purchase_date = date('Y-m-d');
                $tracking_number = mt_rand(10000, 99999);
                $insert_purchase = "INSERT INTO purchase(id, idTL, passengers, total_price, tracking_id, purchase_date)
                                        VALUES ('$user_id', '$idTL', '$passengers', '$total_price', '$tracking_number', '$purchase_date')";
                mysqli_query($con, $insert_purchase);

                // آپدیت موجودی کیف پول کاربر
                $new_amount = $amount - $total_price;
                $update_wallet = "UPDATE wallet SET Amount = '$new_amount' WHERE id = '$user_id'";
                mysqli_query($con, $update_wallet);

                // آپدیت transactionAmount در جدول transaction
                $purchase_date = date('Y-m-d');
                $transaction_id = str_pad(rand(0, pow(10, 7) - 1), 10, '0', STR_PAD_LEFT);
                $insert_transaction = "INSERT INTO transaction(id, transactionAmount, Remark, transaction_date,transaction_id)
                                           VALUES ('$user_id', '$total_price', ' خرید بلیط  ', '$purchase_date','$transaction_id')";

                mysqli_query($con, $insert_transaction);

                // آپدیت بلیط‌های در دسترس برای هر مسیر
                $selectPassengerCount = "SELECT idTL, SUM(passengers) AS total_passengers FROM purchase WHERE idTL = '$idTL' GROUP BY idTL";
                $resultPassengerCount = mysqli_query($con, $selectPassengerCount);

                if (mysqli_num_rows($resultPassengerCount) > 0) {
                    $rowPassengerCount = mysqli_fetch_assoc($resultPassengerCount);
                    $idTL = $rowPassengerCount['idTL'];
                    $passengerCount = $rowPassengerCount['total_passengers'];

                    $update_available_tickets = "UPDATE train_lines SET available_tickets = available_tickets - $passengerCount WHERE idTL = '$idTL'";
                    mysqli_query($con, $update_available_tickets);
                }


                // حذف رکوردهای جدول tempororys
                $deleteTemporary = "DELETE FROM tempororys";
                mysqli_query($con, $deleteTemporary);



                echo '<div style="color: green; margin-right:50px">عملیات خرید با موفقیت انجام شد.</div>';


                
// اجرای کوئری و بازیابی نتایج
$query = "SELECT usr.username, usr.mobile, usr.national_code, usr.email, cr.Route, TL.departure_date AS depart_date, TL.arrival_date AS return_date, tl.train_name, pr.total_price, TL.Description, tl.departure_time, tl.arrival_time, pr.purchase_date, pr.passengers
FROM city_routes CR 
INNER JOIN source_city SC ON SC.idCTS = CR.idCTS 
INNER JOIN destination_city DS ON DS.idCTD = CR.idCTD 
INNER JOIN train_lines TL ON TL.idCTR = CR.idCTR 
INNER JOIN purchase pr ON pr.idTL = TL.idTL
INNER JOIN users usr ON usr.id = pr.id
WHERE pr.idbt IN (SELECT MAX(idbt) FROM purchase)
AND usr.id = 1
GROUP BY usr.username, usr.email, usr.mobile, usr.national_code, Cr.Route, TL.departure_date, pr.purchase_date, pr.tracking_id, TL.arrival_date, pr.total_price, TL.Description, TL.is_round_trip";

$result = mysqli_query($con, $query);

// ایجاد جدول HTML
$html = '<table>
            <tr>
                <th>نام کاربری</th>
                <th>شماره موبایل</th>
                <th>کد ملی</th>
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
            </tr>';

while ($row = mysqli_fetch_assoc($result)) {
    $html .= '<tr>
                <td>'.$row['username'].'</td>
                <td>'.$row['mobile'].'</td>
                <td>'.$row['national_code'].'</td>
                <td>'.$row['email'].'</td>
                <td>'.$row['Route'].'</td>
                <td>'.$row['depart_date'].'</td>
                <td>'.$row['return_date'].'</td>
                <td>'.$row['train_name'].'</td>
                <td>'.$row['total_price'].'</td>
                <td>'.$row['Description'].'</td>
                <td>'.$row['departure_time'].'</td>
                <td>'.$row['arrival_time'].'</td>
                <td>'.$row['purchase_date'].'</td>
                <td>'.$row['passengers'].'</td>
            </tr>';
}

$html .= '</table>';

// ذخیره فایل HTML
$filename = 'ticket_info.html';
file_put_contents($filename, $html);

// ایجاد لینک دانلود
$download_link = '<a href="'.$filename.'" download  style="margin-right:120px;padding-top:50px;text-decoration:none"> دانلود بلیط</a>';

// نمایش لینک دانلود
echo $download_link;







            } 
            
            
           

        }
        // موجودی خالی باشد
        else {
            // Insert data into Temporory table
            $query = "INSERT INTO Tempororys (id, idTL, passengers, total_price,available_tickets) VALUES ('$user_id', '$idTL', '$passengers', '$total_price','$available_tickets')";
            mysqli_query($con, $query);

            // موجودی کافی نیست. نمایش پیغام خطا به صورت alert
            echo '<script>alert("موجودی کافی نیست. لطفاً موجودی کیف پول خود را آپدیت کنید.");</script>';
            // انتقال کاربر به صفحه zarinpalapi.php
            echo '<script>window.location.href = "zarinpalapi.php";</script>';

        }

    }
}

?>



   <script>
    var typeSelect = document.getElementById("type");
    var returnDateInput = document.getElementById("return_date");
    typeSelect.addEventListener("change", function(e) {
        if (typeSelect.value === "round-trip") {
            e.preventDefault();
            returnDateInput.disabled = false;
        } else {
            returnDateInput.disabled = true;
        }
    });

  // Set placeholder for date input field
  const dateInput = document.getElementById('depart_date');
  dateInput.placeholder = 'تاریخ رفت';
    </script>
<!-- import jquery for use datepicker shamsi -->
    <script src="assets/js/jQuery.js"></script>
    <script src="assets/js/kamadatepicker.min.js"></script>

    <script>
        let option={
           nextButtonIcon:"assets/images/timeir_next.png",
           previousButtonIcon:"assets/images/timeir_prev.png",
           forceFarsiDigits:true,
           markToday:true,
           markHolidays:true,
           sync:true,
      
        }
kamaDatepicker("datepicker",option);
kamaDatepicker("return_date",option);
 
function preventPageRefresh(event) {
      event.preventDefault(); // جلوگیری از رفرش کردن صفحه
}

</script>
</body>
</html>
