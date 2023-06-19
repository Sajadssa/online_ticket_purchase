<?php
ob_start();
// Start the session to retrieve the logged-in user's ID
session_start();
require_once 'functions.php';
require_once 'Server.php';
require_once 'navbar.php';

$user_data = check_login($con);

if (isset($_POST['submit'])) {
    // Retrieve the logged-in user's ID from the session
    $user_id = $user_data['id'];

    // دریافت داده‌های موجود در جدول tempororys
    $selectTemporary = "SELECT idTL, passengers, total_price, available_tickets FROM tempororys";
    $resultTemporary = mysqli_query($con, $selectTemporary);

    if (mysqli_num_rows($resultTemporary) > 0) {
        $purchaseSuccess = true; // متغیری برای بررسی موفقیت عملیات خرید

        // وارد کردن داده‌ها در جدول purchase
        while ($rowTemporary = mysqli_fetch_assoc($resultTemporary)) {
            $idTL = $rowTemporary['idTL'];
            $passengers = $rowTemporary['passengers'];
            $total_price = $rowTemporary['total_price'];
            $available_tickets = $rowTemporary['available_tickets'];

            // مبلعی که کاربر وارد می کند
            $amountuserinput = $_POST['amount'];
            $remark = $_POST['remark']??'شارژ کیف پول';
            $walletdate = date('Y,m,d');
            // $message = $amountuserinput;
            // دریافت موجودی از بانک
            $accountamount = "select amount from wallet where id='$user_id'";
            $result_wallet = mysqli_query($con, $accountamount);

//    حالت دوم : رکورد در جدول موجودی هست

            if (mysqli_num_rows($result_wallet) > 0) {
// الان باید موجودی رو باید از بانک گرفته و همین طور مبلغ ورودی را نیز گرفته و باهم جمع کنیم ور در جدول موجودی ،موجودی کاربری که لاگین شده رو آپدیت کنیم

                // مقدار جدید موجودی
                // آپدیت کردن موجودی توسط مبلغ وارد شده توسط کاربر
                // نوشتن کویری آپدیت
                $updatewallet = "update wallet set amount=amount+$amountuserinput where id='$user_id'";
// اجرای کوئری
                $result_walletupdate = mysqli_query($con, $updatewallet);

// نوشتن کوئری برای گرفتن مقدار جدید موجودی
                $queryCheckWallet = "SELECT amount FROM wallet WHERE id = '$user_id'";
                // اچرای کوئری
                $resultCheckWallet = mysqli_query($con, $queryCheckWallet);

                // دسترسی به مقدار جدید موجودی

                    $rowCheckWallet = mysqli_fetch_assoc($resultCheckWallet);
                    $currentBalance = $rowCheckWallet['amount'];
                   
      // مقایسه موچودی جدید با قیمت کل بلیط 
    if($currentBalance>=$total_price){
// وارد کردن رکورد آپدیت موجودی


                    // تولید شماره پیگیری 10 رقمی
                    $transaction_id = str_pad(rand(0, pow(10, 8) - 1), 10, '0', STR_PAD_LEFT);
                    $transactiondate = date('Y-m-d');
                    $insertTransaction = "INSERT INTO transaction (id, transactionAmount, Remark, transaction_date,transaction_id) VALUES ('$user_id', '$currentBalance', 'شارژ کیف پول', '$transactiondate','$transaction_id')";

                    mysqli_query($con, $insertTransaction);

                    // درج رکورد در جدول transaction برای خرید
                    $transaction_purchase_id = str_pad(rand(0, pow(10, 7) - 1), 10, '0', STR_PAD_LEFT);
                    $transaction_date = date('Y-m-d');
                    $insertTransaction_Purchase = "INSERT INTO transaction (id, transactionAmount, Remark, transaction_date,transaction_id) VALUES ('$user_id', '$total_price', ' خریدبلیط', '$transaction_date','$transaction_purchase_id')";
                    mysqli_query($con, $insertTransaction_Purchase);


                    // درج رکورد در جدول purchase
                    // تولید شماره پیگیری 10 رقمی
                    $tracking_id = str_pad(rand(0, pow(10, 9) - 1), 10, '0', STR_PAD_LEFT);
                    $purchase_date = date('Y,m,d');

// درج اطلاعات بلیط در جدول خرید
                    $insertPurchase = "INSERT INTO purchase (id, idTL, passengers, total_price,tracking_id,purchase_date) VALUES ('$user_id', '$idTL', '$passengers', '$total_price','$tracking_id','$purchase_date')";
                    mysqli_query($con, $insertPurchase);


                  

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


                    // آپدیت موجودی بعد از خرید

                    $updatewallet = "update wallet set amount=amount-$total_price";
                    mysqli_query($con, $updatewallet);

                    // حذف رکوردهای جدول tempororys
                    $deleteTemporary = "DELETE FROM tempororys";
                    mysqli_query($con, $deleteTemporary);

                    echo '<div style="color: green; margin-right:50px">عملیات خرید با موفقیت انجام شد.</div>';
             
                    // انتقال به صفحه trainticket.php همراه با ارسال پارامترهای مربوطه
                    header('Location: ticket_info.php');
                    exit;
                    
}

          else {
                    echo '<div style="position :absolute;top:80%;bottom:0 ;left:22% ;color:tomato">  موجودی حساب شما برای خرید بلیط کافی نیست . </div>';
                }
            

            }

            // حالت اول: رکوردی در جدول wallet نیست

            else {


                // اول بایستی قیمت کل بلیط رو از جدولtemproray بگیریم
// مبلغی که کاربر وارد می کند را هم باید بگیریم و این دو رو باهم مقایسه می کنیم
//  اگر مبلغی که کاربر وارد می کند از قیمت کل بیشتر بود عمل ثبت موجودی در حدول wallet رو انجام بده

 if ($amountuserinput >= $total_price) {

 // درج رکورد در جدول wallet
                   
                    $insertWallet = "INSERT INTO wallet (id, Amount,remark,walletdate) VALUES ('$user_id', '$amountuserinput','$remark','$walletdate')";
                    mysqli_query($con, $insertWallet);
                    echo '<div style="position :absolute;top:60%;bottom:0 ;left:22% ;color:green">   شارژ کیف پول با موفقیت انجام شد. </div>';


                    // عملیت خرید وآپدیت کیف پول

                        // تولید شماره پیگیری 10 رقمی
                        $transaction_id = str_pad(rand(0, pow(10, 8) - 1), 10, '0', STR_PAD_LEFT);;

                    $transactiondate = date('Y-m-d');
                    $insertTransaction = "INSERT INTO transaction (id, transactionAmount, Remark, transaction_date,transaction_id) VALUES ('$user_id', '$amountuserinput', 'شارژ کیف پول', '$transactiondate','$transaction_id')";

                    mysqli_query($con, $insertTransaction);

                    // درج رکورد در جدول transaction برای خرید
                    $transaction_date = date('Y-m-d');
                     $transaction_purchase_id= str_pad(rand(0, pow(10, 7) - 1), 10, '0', STR_PAD_LEFT);
                    $insertTransaction_Purchase = "INSERT INTO transaction (id, transactionAmount, Remark, transaction_date,transaction_id) VALUES ('$user_id', '$total_price', ' خریدبلیط', '$transaction_date','$transaction_purchase_id')";
                    // ON DUPLICATE KEY UPDATE transactionAmount = '$total_price', Remark = 'شارژ کیف پول', transaction_date = '$transaction_date',$transaction_id='$transaction_purchase_id'";
                    mysqli_query($con, $insertTransaction_Purchase);



                    // درج رکورد در جدول purchase
                        // تولید شماره پیگیری 10 رقمی
                        $tracking_id = str_pad(rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT);
                        $purchase_date = date('Y,m,d');


                        $insertPurchase = "INSERT INTO purchase (id, idTL, passengers, total_price,tracking_id,purchase_date) VALUES ('$user_id', '$idTL', '$passengers', '$total_price','$tracking_id','$purchase_date')";
                        mysqli_query($con, $insertPurchase);

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


                        // آپدیت موجودی بعد از خرید

                        $updatewallet = "update wallet set amount=amount-$total_price";
                        mysqli_query($con, $updatewallet);

                        // حذف رکوردهای جدول tempororys
                        $deleteTemporary = "DELETE FROM tempororys";
                        mysqli_query($con, $deleteTemporary);

                   
                    // رفتن به صفحه ticket_info
                    header("location:ticket_info.php");

                }
                else{
                    echo '<div style="position :absolute;top:80%;bottom:0 ;left:22% ;color:tomato">  موجودی حساب شما برای خرید بلیط کافی نیست . </div>';

                }

            }


        }



    }
}


// نمایش اطلاعات فرم پرداخت
$id = $user_data['id'];
$query = "SELECT amount, remark FROM wallet WHERE id = $id";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);
$wallet_amount = $row['amount'] ?? 0;
$wallet_remark = $row['remark'] ?? '';
$new_amount = isset($_POST['amount']) ? $_POST['amount'] : $wallet_amount;

ob_end_flush();
?>


 


<script>
    document.getElementById('myForm').submit();
</script>


<div class="containers">

    <form  id="myForm" class="charge" method="POST" action="">




               <label for="amount"> نام کاربری:</label>
        <input type="text" name="user" id="user" value="<?php echo $user_data['username']; ?>" >

        <label for="amount">مبلغ (تومان):</label>
        <input type="number" name="amount" id="amount" value="<?php echo $new_amount; ?>" required>
    
    <label for="remark">توضیحات:</label>
        <input type="text" name="remark" id="remark" value=" شارژ کیف پول " required>
    <!-- تعریف دو حالت برای دکمه در صورت شارژ برای بار اول حالت دکمه به صورت پرداخت و در صورت افزایش موجودی به بروز رسانی تغییر حالت می دهد -->
        <input type="submit" name="submit" value="<?php echo $wallet_amount > 0 ? 'بروزرسانی' : 'پرداخت'; ?>">
    </form>





</div>


<!--  نمایش پیغام -->
<p><?php echo $message ?? ''; ?></p>