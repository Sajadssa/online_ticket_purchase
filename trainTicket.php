<!DOCTYPE html>
<html>

<head>
    <title>فرم خرید آنلاین بلیط قطار</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    label {
        margin-top: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    input[type="text"],
    input[type="number"],
    input[type="date"] {
        margin: 5px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 100%;
    }

    input[type="submit"] {
        margin-top: 10px;
        padding: 10px 20px;
        background-color: #4CAF50;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #3e8e41;
    }
    </style>
</head>

<body>
    <h1>فرم خرید آنلاین بلیط قطار</h1>
    <form method="post" action="submit.php">
        <label>
            <input type="radio" name="ticket_type" value="one-way">
            یک طرفه
        </label>
        <label>
            <input type="radio" name="ticket_type" value="round-trip">
            رفت و برگشت
        </label>
        <label>
            مبدا:
            <select name="origin">
                <option value="tehran">تهران</option>
                <option value="mashhad">مشهد</option>
                <option value="isfahan">اصفهان</option>
                <option value="shiraz">شیراز</option>
            </select>
        </label>
        <label>
            مقصد:
            <select name="destination">
                <option value="tehran">تهران</option>
                <option value="mashhad">مشهد</option>
                <option value="isfahan">اصفهان</option>
                <option value="shiraz">شیراز</option>
            </select>
        </label>
        <label>
            تاریخ رفت:
            <input type="date" name="departure_date" placeholder="تاریخ رفت (شمسی)">
        </label>