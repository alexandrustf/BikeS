<?php
require '../fpdf181/fpdf.php';

$CONFIG = [
    'servername' => "localhost",
    'username' => "root",
    'password' => '',
    'db' => 'test'
];

$conn = new mysqli($CONFIG["servername"], $CONFIG["username"], $CONFIG["password"], $CONFIG["db"]);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function import_stocks()
{
    global $conn;
    header('Location: stocks.controller.php');
    echo 'OKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKK';
    // $filename = $_FILES['file']['name'];
    // $extension = $_FILES['file']['type'];
    // echo $extension;
    // $destination = 'Uploads/';

    // if (!in_array($extension, ['csv', 'json'])) {
    //     echo "You file extension must be .csv or .json";
    // } else {
    //     // move the uploaded file to the specified destination
    //     if (move_uploaded_file($filename, $destination)) {
    //         echo 'OK';
    //         switch ($extension) {
    //             case 'json':
    //         $json = json_encode($filename);
    //         echo $json;
    //         }
    //             $sql = "INSERT INTO files (name, size, downloads) VALUES ('$filename', $size, 0)";
    //             if (mysqli_query($conn, $sql)) {
    //                 echo "File uploaded successfully";
    //             }
    //         } else {
    //             echo "Failed to upload file.";
    //     }
    // }
}

function export_stocks_csv()
{
    global $conn;
    $sql = "SELECT * FROM stocks";
    $result = mysqli_query($conn, $sql);
    $stocks = mysqli_fetch_all($result, MYSQLI_NUM);

    $file = fopen("Downloads/stocks.csv", "w");
    $header = [];
    array_push($header, "id", "nume", "cantitate", "created_at", "updated_at");
    fputcsv($file, $header);
    foreach ($stocks as $stock) {
        fputcsv($file, $stock);
    }
}

function export_stocks_json()
{
    global $conn;
    $sql = "SELECT * FROM stocks";
    $result = mysqli_query($conn, $sql);
    $stocks = mysqli_fetch_all($result, MYSQLI_NUM);

    $downloaded_stocks = [];
    for ($stock_index = 0; $stock_index < sizeof($stocks); $stock_index++) {
        array_push($downloaded_stocks, [
            'id' => $stocks[$stock_index][0],
            'nume' => $stocks[$stock_index][1],
            'cantitate' => $stocks[$stock_index][2],
            'created_at' => $stocks[$stock_index][3],
            'updated_at' => $stocks[$stock_index][4]
        ]);
    }
    file_put_contents("Downloads/stocks.json", json_encode($downloaded_stocks, JSON_PRETTY_PRINT));
    return $downloaded_stocks;
}

function export_stocks_pdf()
{
    global $conn;

    $sql = "SELECT * FROM stocks";
    $result = mysqli_query($conn, $sql);
    // $stocks = mysqli_fetch_all($result, MYSQLI_NUM);

    $display_heading = ['id' => 'id', 'nume_produs' => 'nume_produs', 'cantitate' => 'cantitate', 'created_at' => 'created_at', 'updated_at' => 'updated_at'];

    $header = mysqli_query($conn, "SHOW columns FROM stocks");

    $pdf = new PDF();
    //header
    $pdf->AddPage();
    //foter page
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial', 'B', 12);
    foreach ($header as $heading) {
        $pdf->Cell(40, 12, $display_heading[$heading['Field']], 1);
    }

    foreach ($result as $row) {
        $pdf->Ln();
        foreach ($row as $column)
            $pdf->Cell(40, 12, $column, 1);
    }
    $pdf->Output("F", "Downloads/stocks.pdf");
}

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        $this->Image('../../Images/logo-header.png', 10, -1, 70);
        $this->SetFont('Arial', 'B', 13);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(80, 10, 'Stocks', 1, 0, 'C');
        // Line break
        $this->Ln(20);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}
