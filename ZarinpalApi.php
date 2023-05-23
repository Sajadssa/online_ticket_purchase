<?php
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
        }
        // مبلعی که کاربر وارد می کند
        $amountuserinput = $_POST['amount'];
        $message = $amountuserinput;

        $accountamount = "select amount from wallet where id='$user_id'";
        $result_wallet = mysqli_query($con, $accountamount);

        if (mysqli_num_rows($result_wallet) > 0) {
            $row_wallet = mysqli_fetch_assoc($result_wallet);
            $amount = $row_wallet['amount'];
            echo $amount;

            // مقدار جدید موجودی
            // آپدیت کردن موجودی توسط مبلغ وارد شده توسط کاربر
            $updatewallet = "update wallet set amount=$amount+$amountuserinput";
            $result_walletupdate = mysqli_query($con, $updatewallet);


 $queryCheckWallet = "SELECT amount FROM wallet WHERE id = '$user_id'";
        $resultCheckWallet = mysqli_query($con, $queryCheckWallet);

            if (mysqli_num_rows($resultCheckWallet) > 0) {

                $rowCheckWallet = mysqli_fetch_assoc($resultCheckWallet);
                $currentBalance = $rowCheckWallet['amount'];

            }

 
        }
    
            if ( $currentBalance < $total_price) {

                echo '<div style="position: absolute; top: 70%; bottom: 0; left: 22%; color: tomato"> موجودی شما برای خرید بلیط انتخاب شده کافی نیست. موجودی فعلی حساب شما: ' . $currentBalance . '</div>';

            }

            
             

            
            
           else{


            $transactiondate = date('Y-m-d');
            $insertTransaction = "INSERT INTO transaction (id, transactionAmount, Remark, transaction_date) VALUES ('$user_id', '$amount', 'شارژ کیف پول', '$transactiondate')";
            mysqli_query($con, $insertTransaction);

            // درج رکورد در جدول transaction برای خرید
            $transaction_date = date('Y-m-d');
            $insertTransaction_Purchase = "INSERT INTO transaction (id, transactionAmount, Remark, transaction_date) VALUES ('$user_id', '$total_price', ' خریدبلیط', '$transaction_date')
                    ON DUPLICATE KEY UPDATE transactionAmount = '$total_price', Remark = 'شارژ کیف پول', transaction_date = '$transaction_date'";
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

            $message = "تراکنش با موفقیت انجام شد.";

            // انتقال به صفحه trainticket.php
            header('Location: trainticket.php');
            exit;


        }


        

     

                    // بررسی وجود رکورد در جدول wallet
        $queryCheckWallet = "SELECT amount FROM wallet WHERE id = '$user_id'";
        $resultCheckWallet = mysqli_query($con, $queryCheckWallet);
        if (mysqli_num_rows($resultCheckWallet) == 0) {
            // در صورت عدم وجود رکورد، اجازه شارژ کیف پول در صورتی که مبلغ وارد شده بزرگتر یا مساوی قیمت کل بلیط باشد
            if (!empty($_POST['amount']) && is_numeric($_POST['amount']) && $_POST['amount'] >= $total_price) {
                $amount = $_POST['amount'];
                $remark = '';
                echo $amount;

                // درج رکورد در جدول wallet
                $walletdate = date('Y,m,d');
                $insertWallet = "INSERT INTO wallet (id, Amount,remark,walletdate) VALUES ('$user_id', '$amount','$remark','$walletdate')";
                mysqli_query($con, $insertWallet);
                echo '<div style="position :absolute;top:60%;bottom:0 ;left:22% ;color:green">   شارژ کیف پول با موفقیت انجام شد. </div>';

                if (mysqli_affected_rows($con) > 0) {


                    $transactiondate = date('Y-m-d');
                    $insertTransaction = "INSERT INTO transaction (id, transactionAmount, Remark, transaction_date) VALUES ('$user_id', '$amount', 'شارژ کیف پول', '$transactiondate')";
                    mysqli_query($con, $insertTransaction);

                    // درج رکورد در جدول transaction برای خرید
                    $transaction_date = date('Y-m-d');
                    $insertTransaction_Purchase = "INSERT INTO transaction (id, transactionAmount, Remark, transaction_date) VALUES ('$user_id', '$total_price', ' خریدبلیط', '$transaction_date')
                    ON DUPLICATE KEY UPDATE transactionAmount = '$total_price', Remark = 'شارژ کیف پول', transaction_date = '$transaction_date'";
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

                    $message = "تراکنش با موفقیت انجام شد.";

                    // انتقال به صفحه trainticket.php
                    header('Location: trainticket.php');
                    exit;

                } else {
                    echo '<div style="position :absolute;top:60%;bottom:0 ;left:22%; color:tomato">   موجودی کیف پول کافی نیست. </div>';
                }
            } else {
                echo '<div style="position: absolute; top: 65%; bottom: 0; left: 22%; color: tomato">کیف پول شما خالی است. لطفاً قبل از خرید بلیط، آن را شارژ کنید.</div>';
            }

        }


    }
}



// Remaining code...
$id = $user_data['id'];
$query = "SELECT amount, remark FROM wallet WHERE id = $id";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);
$wallet_amount = $row['amount'] ?? 0;
$wallet_remark = $row['remark'] ?? '';
$new_amount = isset($_POST['amount']) ? $_POST['amount'] : $wallet_amount;
 
 
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
        <input type="text" name="remark" id="remark" value="<?php echo $wallet_remark; ?>" required>
    
        <input type="submit" name="submit" value="<?php echo $wallet_amount > 0 ? 'بروزرسانی' : 'پرداخت'; ?>">
    </form>



</div>

<p><?php echo $message ?? ''; ?></p>