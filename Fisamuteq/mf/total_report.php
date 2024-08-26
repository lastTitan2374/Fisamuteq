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

$sql = "SELECT amc, owner, bank, folio_number, scheme, type, nav_date, purchased_nav, amount_invested, units_purchased, today_date, today_nav FROM mutual_funds";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total Report</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 16px;
            text-align: left;
            table-layout: auto;
        }
        table th, table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
        }
        table th {
            background-color:  rgb(37, 2, 2); /* Dark brown color */
            color: #ffffff; /* White text color */
        }
        table tr:nth-of-type(even) {
            background-color: #f9f9f9;
        }
        table tr:nth-of-type(odd) {
            background-color: #ffffff;
        }
        .table-wrapper {
            overflow-x: auto;
        }
        h2 {
            margin-bottom: 20px;
            font-size: 24px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <a href="/Fisamuteq/home/home.html"> <img src="Logo.png" alt="Logo" style="height: 35px; width: auto; position: relative; top: 10px;"> </a>
        </div>
        <div class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <nav class="nav-links" id="nav-links">
            <a href="de.html">Data Entry</a>
        </nav>
    </div>
    <center>
        <div class="b1">
            <br><br><br>
            <div class="container" id="total-report">
                <div class="form-section">
                    <h2>Total Report of Mutual Funds</h2>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>AMC</th>
                                    <th>Owner</th>
                                    <th>Bank</th>
                                    <th>Folio Number</th>
                                    <th>Scheme</th>
                                    <th>Type</th>
                                    <th>NAV Date</th>
                                    <th>Purchased NAV</th>
                                    <th>Amount Invested</th>
                                    <th>Units Purchased</th>
                                    <th>Today Date</th>
                                    <th>Today NAV</th>
                                    <th>Value</th>
                                    <th>Gain</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $value = $row['today_nav'] * $row['units_purchased'];
                                        $gain = $value - $row['amount_invested'];
                                        echo "<tr>
                                            <td>{$row['amc']}</td>
                                            <td>{$row['owner']}</td>
                                            <td>{$row['bank']}</td>
                                            <td>{$row['folio_number']}</td>
                                            <td>{$row['scheme']}</td>
                                            <td>{$row['type']}</td>
                                            <td>{$row['nav_date']}</td>
                                            <td>{$row['purchased_nav']}</td>
                                            <td>{$row['amount_invested']}</td>
                                            <td>{$row['units_purchased']}</td>
                                            <td>{$row['today_date']}</td>
                                            <td>{$row['today_nav']}</td>
                                            <td>{$value}</td>
                                            <td>{$gain}</td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='14'>No records found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <br><br><br><br><br>
        </div>
    </center>
</body>
<script>
    document.getElementById('hamburger').addEventListener('click', function () {
        document.getElementById('nav-links').classList.toggle('active');
    });
</script>
</html>

<?php
$conn->close();
?>
