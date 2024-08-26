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

// Prepare SQL statement to update fixed deposit details
$sql = "UPDATE fixed_deposits SET 
            account_holder=?, 
            bank_name=?, 
            deposit_no=?, 
            start_date=?, 
            end_date=?, 
            deposit_amount=?, 
            payout_type=?, 
            interest=?, 
            remarks=?
        WHERE id=?";

// Check if all required POST parameters are present
if (isset($_POST['edit_entry_id'], $_POST['edit_account_holder'], $_POST['edit_bank_name'], $_POST['edit_deposit_no'], $_POST['edit_start_date'], $_POST['edit_end_date'], $_POST['edit_deposit_amount'], $_POST['edit_payout_type'], $_POST['edit_interest'], $_POST['edit_remarks'])) {
    // Bind parameters and execute the update
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssdsssi", 
        $_POST['edit_account_holder'], 
        $_POST['edit_bank_name'], 
        $_POST['edit_deposit_no'], 
        $_POST['edit_start_date'], 
        $_POST['edit_end_date'], 
        $_POST['edit_deposit_amount'], 
        $_POST['edit_payout_type'], 
        $_POST['edit_interest'], 
        $_POST['edit_remarks'],
        $_POST['edit_entry_id']
    );

    if ($stmt->execute()) {
        echo "Record updated successfully";

        // Call the stored procedure to recalculate maturity_amount and payout_amount
        $sqlCalculate = "CALL CalculateMaturityAndPayoutAmounts()";
        if ($conn->query($sqlCalculate) === TRUE) {
            echo " Maturity and Payout amounts recalculated successfully";
        } else {
            echo "Error calling CalculateMaturityAndPayoutAmounts: " . $conn->error;
        }
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Missing required parameters";
}

$conn->close();
?>
