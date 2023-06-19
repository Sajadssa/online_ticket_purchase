<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="assets/css/style.css">

    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer">

    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
</head>

<body>
    <!-- header.header>nav.nav_container>ul.nav_link>li.nav_item*5>a -->
    <header>
        <nav class="nav_container no-print">

            <!-- <div class=" toggler">
                <div class="bar bar-one"></div>
                <div class="bar bar-two"></div>
                <div class="bar bar-three"></div>
            </div> -->
            <!-- نمایش لوگوی وب سایت -->
            <div class="logo">


                <img src="./assets/images/logo.jpg" alt="logo" />
            </div>



<!-- تعریف لینک صفحات -->

            <ul class="nav_link">
                <li class="nav_item"><a href="index.php">خانه</a></li>

                <li class="nav_item"><a href="About.php">درباره ما </a></li>
                <li class=" nav_item"><a href="Contact.php"> ارتباط با ما</a></li>
                <li class=" nav_item">
                    <a href="login.php"> ورود/
                        <a href="Registration.php" <span>عضویت</span>

                        </a>
                </li>
                <li class="nav_item">

                    <i class="uil uil-moon change-theme uil-sun"  id="theme-button"></i>

                </li>




            </ul>


        </nav>
    </header>

<script>
   


function preventPageRefresh(event) {
      event.preventDefault(); // جلوگیری از رفرش کردن صفحه
}
</script>
 
    

    <!-- فایل جاوااسکریپتی که بصورت خارجی به این صفحه لینک شده که عملیات رفتاری وب سایت رو انجام می دهد مانند تغییر ظاهر وب سایت از لایت به دارک و... -->
    <script src="assets/js/index.js"></script>

</body>


</html>