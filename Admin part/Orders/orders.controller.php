<?php
// session_start();
include_once 'orders.model.php';
include_once 'import_export_orders.model.php';
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
    echo "GIGEL";
    echo "<br>";
    if (isset($_GET['submit'])) {
        echo 'HELLO';
        echo "<br>";
        echo $_GET['comanda-select'];
        echo "<br>";
        echo $_GET['comanda'];
        if (isset($_GET['comanda-select']) && $_GET['comanda']) {
            $success = adaugaComanda($_GET['comanda-select'], $_GET['comanda']);
        }
    }
    header('Location: ./orders.view.php');

    if (isset($_GET['export'])) {
        switch ($_GET['export']) {
            case 'json':
                export_orders_json();
                break;

            case 'csv':
                export_orders_csv();
                break;

            case 'pdf':
                export_orders_pdf();
                break;
        }
    }

    if (isset($_POST['submit'])) {
        import_orders();
    }
}