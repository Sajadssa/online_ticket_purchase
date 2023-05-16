<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link rel="stylesheet" href="assets/css/kamadatepicker.min.css">
</head>
<body>
    
<form action="">
<input type="text" name="" id="s">



</form>





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
kamaDatepicker("s",option);
 

</script>
</body>
</html>