<?php

 

$dbhost="localhost";//نام سرور میزبان
$dbuser = "root";//نام کاربری
$dbpass="";//رمزعبور
$dbname = "online_ticket_purchase";//نام دیتابیس
if(!$con=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname)){
    die("failed to connect!");
   
}//تابع مذکور براساس پارامترهای داده شده اتصال سرور به پایگاه داده رو چک  می کند






 

?>