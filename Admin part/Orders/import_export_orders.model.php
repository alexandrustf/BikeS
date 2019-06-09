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

function export_orders_csv()
{
    global $conn;
    $sql = "SELECT * FROM orders";
    $result = mysqli_query($conn, $sql);
    $orders = mysqli_fetch_all($result, MYSQLI_NUM);

    $file = fopen("Downloads/orders.csv", "w");
    $header = [];
    array_push($header, "id", "id_stoc", "cantitate", "data_primirii", "created_at", "updated_at");
    fputcsv($file, $header);
    foreach ($orders as $order) {
        fputcsv($file, $order);
    }
}

function export_orders_json()
{
    global $conn;
    $sql = "SELECT * FROM orders";
    $result = mysqli_query($conn, $sql);
    $orders = mysqli_fetch_all($result, MYSQLI_NUM);

    $downloaded_orders = [];
    for ($order_index = 0; $order_index < sizeof($orders); $order_index++) {
        array_push($downloaded_orders, [
            'id' => $orders[$order_index][0],
            'id_stoc' => $orders[$order_index][1],
            'cantitate' => $orders[$order_index][2],
            'data_primirii' => $orders[$order_index][3],
            'created_at' => $orders[$order_index][4],
            'updated_at' => $orders[$order_index][5]
        ]);
    }
    file_put_contents("Downloads/orders.json", json_encode($downloaded_orders, JSON_PRETTY_PRINT));
}

function export_orders_pdf()
{
    global $conn;

    $sql = "SELECT * FROM orders";
    $result = mysqli_query($conn, $sql);
    // $orders = mysqli_fetch_all($result, MYSQLI_NUM);

    $display_heading = ['id' => 'id', 'id_stoc' => 'id_stoc', 'cantitate' => 'cantitate', 'data_primirii' => 'data_primirii', 'created_at' => 'created_at', 'updated_at' => 'updated_at'];

    $header = mysqli_query($conn, "SHOW columns FROM orders");

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
    $pdf->Output("F", "Downloads/orders.pdf");
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