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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amc = $_POST['amc'];
    $owner = $_POST['owner'];
    $bank = $_POST['bank'];
    $folio_number = $_POST['folio-number'];
    $scheme = $_POST['scheme'];
    $type = $_POST['type'];
    $nav_date = $_POST['nav-date'];
    $purchased_nav = $_POST['purchased-nav'];
    $amount_invested = $_POST['amount-invested'];
    $units_purchased = $_POST['units-purchased'];
    $today_date = $_POST['today-date'];
    $today_nav = $_POST['today-nav'];

    // Validate and format dates
    $nav_date = date('Y-m-d', strtotime($nav_date));
    $today_date = date('Y-m-d', strtotime($today_date));

    $sql = "INSERT INTO mutual_funds (amc, owner, bank, folio_number, scheme, type, nav_date, purchased_nav, amount_invested, units_purchased, today_date, today_nav)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    $stmt->bind_param("sssssssdiisd", $amc, $owner, $bank, $folio_number, $scheme, $type, $nav_date, $purchased_nav, $amount_invested, $units_purchased, $today_date, $today_nav);

    if ($stmt->execute()) {
        header("Location: de.html");
        exit(); // Make sure to call exit after redirect to stop further execution
    } else {
        echo "Error: " . $sql . "<br>" . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
