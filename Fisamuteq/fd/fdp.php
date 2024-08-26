<?php
// Database connection parameters
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

// Prepare SQL insert statement
$sql = "INSERT INTO fixed_deposits (account_holder, bank_name, deposit_no, start_date, end_date, deposit_amount, payout_type, interest, remarks, maturity_amount, payout_amount)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, NULL)";

// Prepare and bind parameters
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssss", $account_holder, $bank_name, $deposit_no, $start_date, $end_date, $deposit_amount, $payout_type, $interest, $remarks);

// Set parameters and execute
$account_holder = $_POST['account-holder'];
$bank_name = $_POST['bank-name'];
$deposit_no = $_POST['deposit-no'];
$start_date = $_POST['start-date'];
$end_date = $_POST['end-date'];
$deposit_amount = $_POST['deposit-amount'];
$payout_type = $_POST['payout-type'];
$interest = $_POST['interest'];
$remarks = $_POST['remarks'];

if ($stmt->execute()) {
    // Call the stored procedure to update the maturity_amount and payout_amount
    $sql = "CALL CalculateMaturityAndPayoutAmounts()";
    if ($conn->query($sql) === TRUE) {
        header("Location: de.html?msg=success");
        exit;
    } else {
        echo "Error calling CalculateMaturityAndPayoutAmounts: " . $conn->error;
    }
} else {
    echo "Error executing SQL insert statement: " . $stmt->error;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
