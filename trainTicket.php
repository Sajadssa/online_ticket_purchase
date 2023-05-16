<!DOCTYPE html>
<?php
session_start();
include 'Server.php'; //افزودن کدهای مربوط به اتصال به دیتابیس
include 'functions.php';
include 'NavBar.php';
// set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/includes');
// set_include_path(get_include_path() . PATH_SEPARATOR . 'jajdatetime.class.php');
require_once './includes/jdatetime.class.php';

 

 
 

// search for find available ticket


// اتصال به پایگاه داده


// دریافت اطلاعات ارسال شده توسط کاربر از فرم trainticket.php
// if (isset($_POST['buyticket'])){
// $type=$_POST['type'];
// $destin = $_POST['destin'];
// $source = $_POST['source'];
// $depart_date = $_POST['depart_date'];
// $return_date = $_POST['return_date'];
// $passengers=$_POST['passengers'];
// $price=$_POST['price'];



// // تولید کوئری برای دریافت اطلاعات مورد نیاز
// $query = "SELECT *, SC.City AS `شهرمبدا`, DS.city AS `شهرمقصد`, CR.Route AS `مسیر` 
//           FROM city_routes CR 
//           INNER JOIN source_city SC ON SC.idCTS = CR.idCTS 
//           INNER JOIN destination_city DS ON DS.idCTD = CR.idCTD 
//           INNER JOIN train_lines TL ON TL.idCTR = CR.idCTR 
//           WHERE SC.City = '$source_city' AND DS.city = '$destination_city' 
//                 AND TL.DepartureDate = '$departure_date' AND TL.ReturnDate = '$return_date' 
//                 AND TL.TravelType = '$travel_type'";

// // اجرای کوئری و دریافت نتایج
// $result = mysqli_query($con, $query);

// // نمایش نتایج در یک جدول HTML
// echo "<table>";
// echo "<tr><th>شهرمبدا</th><th>شهرمقصد</th><th>مسیر</th><th>تاریخ رفت</th><th>تاریخ برگشت</th><th>ظرفیت باقیمانده</th></tr>";
// while ($row = mysqli_fetch_assoc($result)) {
//     echo "<tr>";
//     echo "<td>".$row['شهرمبدا']."</td>";
//     echo "<td>".$row['شهرمقصد']."</td>";
//     echo "<td>".$row['مسیر']."</td>";
//     echo "<td>".$row['DepartureDate']."</td>";
//     echo "<td>".$row['ReturnDate']."</td>";
//     if ($row['RemainingCapacity'] > 0) {
//         echo "<td>".$row['RemainingCapacity']."</td>";
//     } else {
//         echo "<td>ظرفیت تکمیل شده است</td>";
//     }
//     echo "</tr>";
// }
// echo "</table>";

// // بستن اتصال به پایگاه داده
// mysqli_close($con);

// }

// ?>

<html dir="rtl">

<head>
    <meta charset="utf-8">
    <title>فرم خرید بلیط قطار</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="assets/css/kamadatepicker.min.css">

</head>

<body>

<div class="container">
    <form class="formbuy" method="post" action="#">
        <label for="type">نوع سفر:</label>
        <select id="type"  name="type" >
            <option value="one-way">یک طرفه</option>
            <option value="round-trip">رفت و برگشت</option>
        </select>
 
      

                


                 <select name="source" id="source" placeholder="شهر مبدا">
                    <option value="">شهر مبدا</option>

                    <?php 
                    // load data in select option
$select = "select * from source_city ";

$result = mysqli_query($con, $select);

 if (isset($_POST['buyticket'])){
     $id_CTS = $_POST['idCTS'];
 }
 foreach ($result as $key => $value) { ?>

                    <option value="<?= $value['idCTS']; ?>"><?= $value['City']; ?></option>


                    <?php } ?>
                </select>

         


    
                    <select name="destin" id="destin" placeholder="شهر مقصد">
                                        <option value="">شهر مقصد</option>
                    
                                        <?php 
                                        // load data in select option
                    $select = "select * from destination_city ";
                    $result = mysqli_query($con, $select);
                     if (isset($_POST['buyticket'])){
                         $id_CTD = $_POST['idCTD'];
                     }
                     foreach ($result as $key => $value) { ?>
                    
                                        <option value="<?= $value['idCTD']; ?>"><?= $value['city']; ?></option>
                                        <?php } ?>
                                    </select>


                

                  <label for="depart-date">تاریخ رفت:</label>
<input type="text" style="font-family:IranYekan;"   id="datepicker" name="depart_date" placeholder="تاریخ رفت" >


                <label for="return-date">تاریخ برگشت:</label>
                <input type="text" id="return_date" placeholder="تاریخ برگشت" name="return-date" disabled>
    

  <label for=" passengers">تعداد مسافران:</label>
        <input type="number" id="passengers" name="passengers" min="1" max="10">

        <label for="price">قیمت بلیط:</label>
        <input type="text" id="price" name="price" readonly>

        <input type="submit" name="buyticket" value=" جستجو">
    </form>

 </div>

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

    // var fromInput = document.getElementById("from");
    // var toInput = document.getElementById("to");
    // var passengersInput = document.getElementById("passengers");
    // var priceInput = document.getElementById("price");

    // fromInput.addEventListener("change", calculatePrice);
    // toInput.addEventListener("change", calculatePrice);
    // passengersInput.addEventListener("change", calculatePrice);

    // function calculatePrice() {
    //     var from = fromInput.value;
    //     var to = toInput.value;
    //     var passengers = passengersInput.value;

    //     // Perform price calculation here and update priceInput value

    //     priceInput.value = "120,000 تومان";
    // }



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
           sync:false,
      

        }
kamaDatepicker("datepicker",option);
 

</script>
</body>

</html>