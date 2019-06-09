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

function import_appointments()
{
    global $conn;
    header('Location: appointments.controller.php');
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

function export_appointments_csv()
{
    global $conn;
    $sql = "SELECT * FROM appointments";
    $result = mysqli_query($conn, $sql);
    $appointments = mysqli_fetch_all($result, MYSQLI_NUM);

    $file = fopen("Downloads/appointments.csv", "w");
    $header = [];
    array_push($header, "id", "nume", "email", "telefon", "data_programarii", "ora_programarii", "descriere", "status");
    fputcsv($file, $header);
    foreach ($appointments as $appointment) {
        fputcsv($file, $appointment);
    }
}

function export_appointments_json()
{
    global $conn;
    $sql = "SELECT * FROM appointments";
    $result = mysqli_query($conn, $sql);
    $appointments = mysqli_fetch_all($result, MYSQLI_NUM);

    $downloaded_appointments = [];
    for ($appointment_index = 0; $appointment_index < sizeof($appointments); $appointment_index++) {
        array_push($downloaded_appointments, [
            'id' => $appointments[$appointment_index][0],
            'nume' => $appointments[$appointment_index][1],
            'email' => $appointments[$appointment_index][2],
            'telefon' => $appointments[$appointment_index][3],
            'data_programarii' => $appointments[$appointment_index][4],
            'ora_programarii' => $appointments[$appointment_index][5],
            'descriere' => $appointments[$appointment_index][6],
            'status' => $appointments[$appointment_index][7]
        ]);
    }
    file_put_contents("Downloads/appointments.json", json_encode($downloaded_appointments, JSON_PRETTY_PRINT));
    return $downloaded_appointments;
}

function export_appointments_pdf()
{
    global $conn;

    $sql = "SELECT * FROM appointments";
    $result = mysqli_query($conn, $sql);
    // $stocks = mysqli_fetch_all($result, MYSQLI_NUM);

    $display_heading = [
        "id" => "id",
        "nume" => "nume",
        "email" => "email",
        "telefon" => "telefon",
        "data_programarii" => "data_programarii",
        "ora_programarii" => "ora_programarii",
        "descriere" => "descriere",
        "status" => "status"
    ];

    $header = mysqli_query($conn, "SHOW columns FROM appointments");

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
    $pdf->Output("F", "Downloads/appointments.pdf");
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
        $this->Cell(80, 10, 'Appointments', 1, 0, 'C');
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