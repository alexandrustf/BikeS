<?php
// session_start();
include_once 'stocks.model.php';
include_once 'import_export_stocks.model.php';
include '../Login/login.model.php';

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

if (check_token()) {
    if (isset($_GET['elimina'])) {
        echo "AM FOLSIT GET" . $_GET['eliminasubmit'] . " " . $_GET['elimina'];
        $id = $_GET['eliminasubmit'];
        $valoareEliminata = $_GET['elimina'];
        $success = eliminaStoc($id, $valoareEliminata);
    }

    if (isset($_GET['adaugasubmit'])) {
        echo "AM FOLSIT GET" . $_GET['adaugasubmit'] . " " . $_GET['adauga'];
        $id = $_GET['adaugasubmit'];
        $valoareAdaugata = $_GET['adauga'];
        echo "COdul este: " . $success = adaugaStoc($id, $valoareAdaugata);
    }
    header('Location: ./stocks.view.php');

    if (isset($_GET['export'])) {
        switch ($_GET['export']) {
            case 'json':
                export_stocks_json();
                break;

            case 'csv':
                export_stocks_csv();
                break;

            case 'pdf':
                export_stocks_pdf();
                break;
        }
    }

    if (isset($_FILES['file'])) {
        header('Location: ./stocks.controller.php');

        echo '<h1> OKKKKKKKKKKKKKKKKKKKKKKK </h1>';
        import_stocks();
    }
}