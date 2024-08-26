<?php
$servername = "localhost";
$username = "Group5";
$password = "Zephiroth123#";
$dbname = "finance";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$transactionDate = $_POST['transactionDate'];
$accountHolders = $_POST['accountHolder'];
$bankNames = $_POST['bankName'];
$accountNumbers = $_POST['accountNumber'];
$openingBalances = $_POST['openingBalance'];
$incomes = $_POST['income'];
$expenses = $_POST['expenses'];

for ($i = 0; $i < count($accountNumbers); $i++) {
    $accountHolder = $accountHolders[$i];
    $bankName = $bankNames[$i];
    $accountNumber = $accountNumbers[$i];
    $openingBalance = $openingBalances[$i];
    $income = $incomes[$i];
    $expense = $expenses[$i];
    $currentBalance = $openingBalance + $income - $expense;

    $sql = "INSERT INTO SavingsDeposit (transaction_date, account_holder, bank_name, account_number, opening_balance, income, expenses, current_balance)
            VALUES ('$transactionDate', '$accountHolder', '$bankName', '$accountNumber', '$openingBalance', '$income', '$expense', '$currentBalance')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
header("Location: de.html"); // Replace with the path to your actual HTML form page
exit();
?>
