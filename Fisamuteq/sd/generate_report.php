<?php
$servername = "localhost";
$username = "Group5";
$password = "Zephiroth123#";
$dbname = "finance";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedMonth = $_POST['month'];
    $selectedYear = date('Y'); // Assuming the current year is selected. Adjust as needed.

    $accountMapping = [
        123456 => 'Biji', 123457 => 'Biji', 123458 => 'Biji',
        234567 => 'Bindu', 234568 => 'Bindu', 234569 => 'Bindu',
        345678 => 'Nikitha',
        678901 => 'Jonathan', 678902 => 'Jonathan',
        456789 => 'Chachen',
        567890 => 'Mummy'
    ];

    $reportData = [];
    foreach ($accountMapping as $accountNumber => $accountHolder) {
        $stmt = $conn->prepare(
            "SELECT 
                bank_name,
                account_number,
                (SELECT opening_balance 
                 FROM SavingsDeposit 
                 WHERE account_number = ? 
                 AND transaction_date >= DATE(CONCAT(?, '-', ?, '-01')) 
                 ORDER BY transaction_date ASC LIMIT 1) AS opening_balance,
                (SELECT SUM(income) 
                 FROM SavingsDeposit 
                 WHERE account_number = ? 
                 AND MONTH(transaction_date) = ? 
                 AND YEAR(transaction_date) = ?) AS income,
                (SELECT SUM(expenses) 
                 FROM SavingsDeposit 
                 WHERE account_number = ? 
                 AND MONTH(transaction_date) = ? 
                 AND YEAR(transaction_date) = ?) AS expenses,
                (SELECT current_balance 
                 FROM SavingsDeposit 
                 WHERE account_number = ? 
                 AND transaction_date <= LAST_DAY(DATE(CONCAT(?, '-', ?, '-01')))
                 ORDER BY transaction_date DESC LIMIT 1) AS current_balance
            FROM SavingsDeposit 
            WHERE account_number = ?
            LIMIT 1"
        );

        $stmt->bind_param('issiiiiiiiiii', 
            $accountNumber, $selectedYear, $selectedMonth,
            $accountNumber, $selectedMonth, $selectedYear, 
            $accountNumber, $selectedMonth, $selectedYear, 
            $accountNumber, $selectedYear, $selectedMonth, 
            $accountNumber
        );

        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $reportData[] = [
                'accountHolder' => $accountHolder,
                'bankName' => $row['bank_name'],
                'accountNumber' => $row['account_number'],
                'openingBalance' => $row['opening_balance'] ?? 0,
                'income' => $row['income'] ?? 0,
                'expenses' => $row['expenses'] ?? 0,
                'currentBalance' => $row['current_balance'] ?? 0
            ];
        }
        $stmt->close();
    }
    $conn->close();
    require 'tr.html';
}
?>
