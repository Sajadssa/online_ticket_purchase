<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>فرم خرید بلیط قطار</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

		<form method="post" action="buy_ticket.php">
			<label for="from">مبدأ:</label>
			<input type="text" id="from" name="from" required>
			<label for="to">مقصد:</label>
			<input type="text" id="to" name="to" required>
			<label for="date">تاریخ:</label>
			<input type="text" id="date" name="date" placeholder="روز/ماه/سال" required>
			<input type="radio" id="one_way" name="trip_type" value="one_way" checked>
			<label for="one_way">یک طرفه</label>
			<input type="radio" id="round_trip" name="trip_type" value="round_trip">
			<label for="round_trip">رفت و برگشت</label>
			<input type="submit" value="خرید بلیط">
		</form>
	
</body>
</html>


