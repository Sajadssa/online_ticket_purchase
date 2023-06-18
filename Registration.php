<?php
session_start();
include 'Server.php'; //افزودن کدهای مربوط به اتصال به دیتابیس
include 'functions.php';
include 'NavBar.php';

$msg = "";
$msgsuc = "";
if (isset($_POST['register'])) {
    //something was posted
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass =$_POST['pass'];
    $mobile =$_POST['mobile'];


    if (!empty($name) && !empty($pass) &&  !empty($email) && !is_numeric($name)) {


        //save to database
        $user_id = random_num(20);
     

        try {
            $query = "insert into users (user_id,username,email,pass,mobile) values ('$user_id','$name','$email','$pass','$mobile')";


            mysqli_query($con, $query);
            $msgsuc = "<div class='success' style='display:block;color:rgba(0, 255, 1, .9);font-size:20px;margin:5rem auto 0;text-align:center;font-weight:bold'>  اطلاعات باموفقیت ثبت شد</div>";
            echo $msgsuc;
            header("Location:Login.php");
            exit;
        } 
        
        catch (Exception $e) {

            echo 'Message: ' . $e->getMessage();
            $msg = "<div class='danger' style='display:flex; align-items:center;color:red;margin:10rem auto 0;text-align:center;'>  اطلاعات وارد شده نادرست است یا تکراری وارد شده است</div>";
            echo $msg;
           
        }
    } else {

        $msg = "<div class='danger' style='display:block;color:red;margin:5rem auto 0;text-align:center;'>  اطلاعات وارد شده نادرست است یا تکراری وارد شده است</div>";
        echo $msg;
    }
}


?>

<!-- قرار دادن کدهای ثبت کاربران در صفحه ثبت کاربران -->
<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">

</head>

<body>

    <!-- Registration form-->

    <div class="reg_form_Container">
        <form method="post">

            <h1 class="reg_title">فرم عضویت در سایت</h1>

            <input type="text" name="mobile" id="mobile" Placeholder=" تلفن همراه" required>


            <input type="text" name="name" id="user" Placeholder="نام کاربری" required>

            <input type="email" name="email" id="email" Placeholder="پست الکترونیکی" required>

            <input type="password" name="pass" id="password" value="" maxlength="8" Placeholder=" رمز عبور" required>

            <div class="btns">
                <button type="submit" name="register" class="btn register">ثبت </button>
            </div>
            <p class="reg_form_desc">
                کاربر وجود دارد؟
                <a href="Login.php"><b>ورود</b></a>
            </p>

        </form>
    </div>


    

</body>

</html>