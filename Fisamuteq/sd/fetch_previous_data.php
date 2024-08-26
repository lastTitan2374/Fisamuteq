<?php
// Establish database connection
$servername = "localhost";
$username = "Group5";
$password = "Zephiroth123#";
$dbname = "finance";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Retrieve transaction date from GET parameter
$transactionDate = $_GET['transactionDate'];

try {
    // SQL query to fetch opening balance, income, and expenses for each account number on the selected date
    $sql = "SELECT account_number, 
                   MAX(CASE WHEN transaction_date < :transactionDate THEN current_balance END) AS opening_balance,
                   MAX(CASE WHEN transaction_date = :transactionDate THEN income ELSE 0 END) AS income,
                   MAX(CASE WHEN transaction_date = :transactionDate THEN expenses ELSE 0 END) AS expenses
            FROM SavingsDeposit 
            GROUP BY account_number";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':transactionDate', $transactionDate);
    $stmt->execute();

    $previousData = [];

    // Fetch all rows as associative array
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $previousData[] = [
            'accountNumber' => $row['account_number'],
            'openingBalance' => $row['opening_balance'] ?? 0,
            'income' => $row['income'] ?? 0,
            'expenses' => $row['expenses'] ?? 0
        ];
    }

    // Return JSON response with previous data
    header('Content-Type: application/json');
    echo json_encode($previousData);

} catch (PDOException $e) {
    die("Error retrieving previous data: " . $e->getMessage());
}
?>
