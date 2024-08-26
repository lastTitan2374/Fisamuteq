<?php
require('fpdf.php');

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Select Arial bold 15
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Cell(80);
        // Framed title
        $this->Cell(30,10,'Monthly Report',0,1,'C');
        // Line break
        $this->Ln(10);
    }

    // Page footer
    function Footer()
    {
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Select Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
    }

    // Table header
    function TableHeader()
    {
        $this->SetFont('Arial','B',12);
        $this->Cell(40,10,'Account Holder',1);
        $this->Cell(30,10,'Bank Name',1);
        $this->Cell(40,10,'Account Number',1);
        $this->Cell(30,10,'Opening Balance',1);
        $this->Cell(30,10,'Income',1);
        $this->Cell(30,10,'Expenses',1);
        $this->Cell(30,10,'Current Balance',1);
        $this->Ln();
    }

    // Table rows
    function TableRows($reportData)
    {
        $this->SetFont('Arial','',12);
        $currentAccountHolder = '';
        foreach ($reportData as $data) {
            $this->Cell(40,10,($data['accountHolder'] !== $currentAccountHolder ? $data['accountHolder'] : ''),1);
            $this->Cell(30,10,$data['bankName'],1);
            $this->Cell(40,10,$data['accountNumber'],1);
            $this->Cell(30,10,$data['openingBalance'],1);
            $this->Cell(30,10,$data['income'],1);
            $this->Cell(30,10,$data['expenses'],1);
            $this->Cell(30,10,$data['currentBalance'],1);
            $this->Ln();
            $currentAccountHolder = $data['accountHolder'];
        }
    }
}

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
                 AND transaction_date >= DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m-01')
                 ORDER BY transaction_date ASC LIMIT 1) AS opening_balance,
                (SELECT SUM(income) 
                 FROM SavingsDeposit 
                 WHERE account_number = ? 
                 AND MONTH(transaction_date) = ?) AS income,
                (SELECT SUM(expenses) 
                 FROM SavingsDeposit 
                 WHERE account_number = ? 
                 AND MONTH(transaction_date) = ?) AS expenses,
                (SELECT current_balance 
                 FROM SavingsDeposit 
                 WHERE account_number = ? 
                 AND transaction_date <= LAST_DAY(DATE(CONCAT(YEAR(NOW()), '-', ?, '-01')))
                 ORDER BY transaction_date DESC LIMIT 1) AS current_balance
            FROM SavingsDeposit 
            WHERE account_number = ?
            LIMIT 1"
        );

        $stmt->bind_param('iiiissii', $accountNumber, $accountNumber, $selectedMonth, $accountNumber, $selectedMonth, $accountNumber, $selectedMonth, $accountNumber);
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

    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->TableHeader();
    $pdf->TableRows($reportData);
    $pdf->Output('D', 'Monthly_Report.pdf');
}
?>
