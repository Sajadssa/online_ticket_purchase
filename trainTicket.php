

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
    echo "خطا در اتصال سرور";
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

if (isset($_POST['buyticket'])) {
    $source = $_POST['source'];
    $destin = $_POST['destin'];
    $depart_date = $_POST['depart_date'];
    $return_date = isset($_POST['return_date']) ? $_POST['return_date'] : '';
    $type = $_POST['type'];

    $query = "SELECT CR.*, SC.City AS source_city, DS.city AS destination_city, TL.departure_date as depart_date, TL.arrival_date as return_date, TL.price, TL.Description, TL.is_round_trip
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

    if (mysqli_num_rows($result) > 0) {
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

        $count = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>";
          echo "<input type='button' style=' box-shadow: 0 20px 120px rgba(209, 186, 186, 0.959);
  background-color: var(--primaryColor);
  border: none;
  padding: 0.5rem;
  border-radius: 7px;
  color: var(--text-color);
  font-size: 14px;
 color:white;
  width: 80px;
  cursor: pointer;
  transition: 1s ease-in-out;' value='ثبت خرید' onclick='buyTicket(" . $row['idCTR'] . ")'>";
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
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<div>بلیطی برای این مشخصات یافت نشد.</div>";
    }
} else {
    echo "<div style='margin:20px 50px; color:tomato'>لطفاً فرم را پر کنید و برای جستجو ارسال کنید.</div>";
}
?>


<!-- عمل ثبت بلیط در جدول خرید بلیط -->
<?php
// بررسی کاربر آیا لاگین شده یانه
$user_data = check_login($con);
if(!$user_data){

   header(Location:"Login.php"); 
}
else{

$user_data = check_login($con);
$user_id=$user_data['id'];

//  دریافت مسیر یافت شده
if(isset($_POST['buyticket'])){
 $source = $_POST['source'];
    $destin = $_POST['destin'];
    $depart_date = $_POST['depart_date'];
    $return_date = isset($_POST['return_date']) ? $_POST['return_date'] : '';
    $type = $_POST['type'];


   $query = "SELECT CR.*, SC.City AS source_city, DS.city AS destination_city, TL.departure_date as depart_date, TL.arrival_date as return_date, TL.price, TL.Description, TL.is_round_trip,
   TL.idTL FROM city_routes CR 
    INNER JOIN source_city SC ON SC.idCTS = CR.idCTS 
    INNER JOIN destination_city DS ON DS.idCTD = CR.idCTD 
    INNER JOIN train_lines TL ON TL.idCTR = CR.idCTR 
    WHERE SC.idCTS = '$source' AND DS.idCTD = '$destin' AND TL.departure_date = '$depart_date'";
// اجرای کویری بالا
  $result = mysqli_query($con, $query);


//idTL,price رو از جدول trian_lines  بگیر
 if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $idTL = $row['idTL'];
        $price = $row['price']; 

        // گرفتن کوجودی کاربری که لاگین شده است
$wallet="select ";


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
