<?php
require '../vendor/autoload.php'; // Include Composer autoloader

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Start output buffering
ob_start();
session_start();
include("config.php");
$obj = new database();

// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header values (removed jobId and bill_date)
$headers = [
    'supplier_id (ID - Name)', 'bill_no',
    'pur_date (YYYY-MM-DD)', 'lot_no', 'bale_no', 'image', 'raw_id',
    'width_id', 'd_no', 'qty'
];

$sheet->fromArray($headers, null, 'A1');

// Apply styling to the header
$headerRange = 'A1:K1'; // Adjusted the range
$sheet->getStyle($headerRange)->getFont()->setBold(true);
$sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID);
$sheet->getStyle($headerRange)->getFill()->getStartColor()->setARGB(Color::COLOR_YELLOW);

// Get supplier data
$suppliers = [];
$table = "`supplier`";
$columns = "*";
$where = "`status`=1";
$order = "`id`";
$supplierData = $obj->get_rows($table, $columns, $where, $order);

if ($supplierData) {
    $dropDownValues = [];
    foreach ($supplierData as $supl) {
        $id = $supl['id'];
        $name = $supl['name'];
        $formattedName = "$id - $name";
        $dropDownValues[] = $formattedName;
    }
}

$dropDownString = implode(",", $dropDownValues);
$dataValidation = $spreadsheet->getActiveSheet()->getCell('A2')->getDataValidation(); // Adjusted cell for dropdown
$dataValidation->setType(DataValidation::TYPE_LIST);
$dataValidation->setAllowBlank(true);
$dataValidation->setFormula1('"' . $dropDownString . '"');
$dataValidation->setShowDropDown(true);

// Set supplier dropdown for column A
for ($row = 2; $row <= 100; $row++) {
    $sheet->getCell('A' . $row)->setDataValidation(clone $dataValidation);
}

// Get raw material data
$raw_id = [];
$table = "`raw_material`";
$columns = "*";
$where = "`type`='fabric'";
$order = "`id`";
$rawData = $obj->get_rows($table, $columns, $where, $order);

if ($rawData) {
    $dropDownValues1 = [];
    foreach ($rawData as $raw) {
        $id1 = $raw['id'];
        $name1 = $raw['name'];
        $formattedName1 = "$id1 - $name1";
        $dropDownValues1[] = $formattedName1;
    }
}

$dropDownString1 = implode(",", $dropDownValues1);
$dataValidation1 = $spreadsheet->getActiveSheet()->getCell('G2')->getDataValidation(); // Adjusted cell for dropdown
$dataValidation1->setType(DataValidation::TYPE_LIST);
$dataValidation1->setAllowBlank(true);
$dataValidation1->setFormula1('"' . $dropDownString1 . '"');
$dataValidation1->setShowDropDown(true);



// Set raw material dropdown for column G
for ($row = 2; $row <= 100; $row++) {
    $sheet->getCell('G' . $row)->setDataValidation(clone $dataValidation1);
}

$widths = [];
$table = "`width`";
$columns = "*";
$order = "`id`";
$widthData = $obj->get_rows($table, $columns, $order);

if ($widthData) {
    $dropDownValues2 = [];
    foreach ($widthData as $width) {
        $id2 = $width['id'];
        $name2 = $width['width']. ' ' . $width['unit'];
        $formattedName2 = "$id2 - $name2";
        $dropDownValues2[] = $formattedName2;
    }
}

$dropDownString2 = implode(",", $dropDownValues2);
$dataValidation2 = $spreadsheet->getActiveSheet()->getCell('H2')->getDataValidation(); // Adjusted cell for dropdown
$dataValidation2->setType(DataValidation::TYPE_LIST);
$dataValidation2->setAllowBlank(true);
$dataValidation2->setFormula1('"' . $dropDownString2 . '"');
$dataValidation2->setShowDropDown(true);

// Set width dropdown for column H
for ($row = 2; $row <= 100; $row++) {
    $sheet->getCell('H' . $row)->setDataValidation(clone $dataValidation2);
}

// Set column widths
$sheet->getColumnDimension('A')->setWidth(30); // Supplier ID column
$sheet->getColumnDimension('B')->setWidth(30); // Bill No column
$sheet->getColumnDimension('C')->setWidth(30); // Pur Date column
$sheet->getColumnDimension('D')->setWidth(30); // Lot No column
$sheet->getColumnDimension('E')->setWidth(15); // Bale No column
$sheet->getColumnDimension('F')->setWidth(15); // Image column
$sheet->getColumnDimension('G')->setWidth(30); // Raw ID column
$sheet->getColumnDimension('H')->setWidth(15); // Width ID column
$sheet->getColumnDimension('I')->setWidth(15); // D No column
// $sheet->getColumnDimension('J')->setWidth(15); // Meter column
$sheet->getColumnDimension('J')->setWidth(15); // Qty column

// Set date format for the pur_date column (C)
$sheet->getStyle('C2:C100')->getNumberFormat()->setFormatCode('yyyy-mm-dd');

// Check if an image was uploaded
if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    $imagePath = $_FILES['image']['tmp_name'];
    $imageType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($imagePath);

    // Load the image into the spreadsheet
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setName('Image');
    $drawing->setDescription('Image');
    $drawing->setPath($imagePath); // Path to the uploaded file
    $drawing->setHeight(100); // Set the height of the image
    $drawing->setCoordinates('F2'); // Set the cell where the image will be placed
    $drawing->setWorksheet($sheet); // Attach the drawing to the worksheet
}

// Set headers to prompt download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="stock.xlsx"');
header('Cache-Control: max-age=0');

// Clean the output buffer
ob_end_clean();

// Create the writer and output to php://output
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// Terminate the script
exit;
?>
