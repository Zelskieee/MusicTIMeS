<?php
include '../../db.php'; // Include your database connection

// Ensure the enterprise_id is passed as a parameter
if (!isset($_GET['enterprise_id'], $_GET['start_date'], $_GET['end_date'])) {
    die("Missing parameters");
}

$enterprise_id = $_GET['enterprise_id'];
$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];

// Prepare and execute the SQL query to fetch sales data within the specified date range and enterprise
$query = $conn->prepare("
    SELECT 
        p.product_name, 
        p.product_price, 
        SUM(oi.order_quantity) AS total_quantity, 
        (p.product_price * SUM(oi.order_quantity)) AS total_sales 
    FROM order_item oi 
    JOIN product p ON oi.product_id = p.product_id 
    JOIN `order` o ON oi.order_id = o.order_id
    WHERE p.enterprise_id = ? 
      AND o.created_order BETWEEN ? AND ?
      AND o.order_status = 'Complete'
    GROUP BY p.product_name, p.product_price
");
$query->bind_param("sss", $enterprise_id, $start_date, $end_date);

$query->execute();
$result = $query->get_result();

// Generate Excel file
require '../../vendor/autoload.php'; // Corrected path
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Add title
$enterprise_name = getEnterpriseName($enterprise_id); // You need to implement this function to get the enterprise name
$title = "Sales Report for $enterprise_name from $start_date to $end_date";
$sheet->setCellValue('A1', $title);

// Add header row
$sheet->setCellValue('A2', 'Product Name')
      ->setCellValue('B2', 'Price per Unit (RM)')
      ->setCellValue('C2', 'Total Quantity')
      ->setCellValue('D2', 'Price (RM)')
      ->setCellValue('E2', 'Total of the Sales (RM)');

// Fetch the data and write to the Excel file
$rowNumber = 3;
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNumber, $row['product_name'])
          ->setCellValue('B' . $rowNumber, number_format($row['product_price'], 2, '.', ''))
          ->setCellValue('C' . $rowNumber, $row['total_quantity'])
          ->setCellValue('D' . $rowNumber, number_format($row['product_price'], 2, '.', '')) // Assuming Price column is the same as Price per Unit
          ->setCellValue('E' . $rowNumber, number_format($row['total_sales'], 2, '.', ''));
    $rowNumber++;
}

// Create a line chart
$dataSeriesLabels = [
    new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', "'Worksheet'!\$E\$2", null, 1),
];
$xAxisTickValues = [
    new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', "'Worksheet'!\$A\$3:\$A\$" . ($rowNumber - 1), null, 4),
];
$dataSeriesValues = [
    new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('Number', "'Worksheet'!\$E\$3:\$E\$" . ($rowNumber - 1), null, 4),
];

// Build the data series
$series = new \PhpOffice\PhpSpreadsheet\Chart\DataSeries(
    \PhpOffice\PhpSpreadsheet\Chart\DataSeries::TYPE_LINECHART,
    \PhpOffice\PhpSpreadsheet\Chart\DataSeries::GROUPING_STANDARD,
    range(0, count($dataSeriesValues) - 1),
    $dataSeriesLabels,
    $xAxisTickValues,
    $dataSeriesValues
);

$plotArea = new \PhpOffice\PhpSpreadsheet\Chart\PlotArea(null, [$series]);
$legend = new \PhpOffice\PhpSpreadsheet\Chart\Legend(\PhpOffice\PhpSpreadsheet\Chart\Legend::POSITION_RIGHT, null, false);
$title = new \PhpOffice\PhpSpreadsheet\Chart\Title('Total Sales Chart');

$chart = new \PhpOffice\PhpSpreadsheet\Chart\Chart(
    'sales_chart',
    $title,
    $legend,
    $plotArea,
    true,
    0,
    null,
    null
);

// Set the position where the chart should appear in the worksheet
$chart->setTopLeftPosition('F4');
$chart->setBottomRightPosition('P20');

// Add the chart to the worksheet
$sheet->addChart($chart);

// Save Excel file
$writer = new Xlsx($spreadsheet);
$writer->setIncludeCharts(true);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="sales_report.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit();

// Function to get enterprise name based on enterprise_id
function getEnterpriseName($enterprise_id) {
    global $conn;
    $query = $conn->prepare("SELECT enterprise_name FROM enterprise WHERE enterprise_id = ?");
    $query->bind_param("i", $enterprise_id);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_assoc();
    return $row['enterprise_name'];
}
?>
