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

// Prepare SQL statement to fetch fixed deposit details based on account holder
$sql = "SELECT id, account_holder, bank_name, deposit_no, start_date, end_date, period, deposit_amount, interest, payout_type, payout_amount, maturity_amount, remarks FROM fixed_deposits WHERE account_holder = ?";

// Get account holder from GET parameter (assuming passed from JavaScript)
if (isset($_GET['account_holder'])) {
    $accountHolder = $_GET['account_holder'];

    // Prepare and bind parameter
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $accountHolder);

    // Execute SQL query
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Fetch data and store in array
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Return data as JSON
        echo json_encode($data);
    } else {
        // Error handling
        echo json_encode(array('error' => 'Unable to fetch data'));
    }

    // Close statement
    $stmt->close();
} else {
    // If account_holder parameter is not provided
    echo json_encode(array('error' => 'Account holder not specified'));
}

// Close connection
$conn->close();
?>
