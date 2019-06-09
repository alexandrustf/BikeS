<?php

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
function getOrders()
{
    global $conn;

    $getStocksStmt = $conn->prepare('SELECT id, id_stoc, cantitate, data_primirii FROM orders');

    $getStocksStmt->execute();
    $results = $getStocksStmt->get_result();
    $getStocksStmt->close();

    $orders = array();
    $id = 0;
    $id_stoc = 0;
    $cantitate = 0;
    $data_primirii = "";
    while ($row = $results->fetch_array(MYSQLI_NUM)) {
        $count = 1;

        foreach ($row as $r) {
            // print "Aici avem coloana din linie : $r ";
            // echo  "<br>";
            if ($count == 1) {
                $id = $r;
            }
            if ($count == 2) {
                $id_stoc = $r;
            }
            if ($count == 3) {
                $cantitate = $r;
            }
            if ($count == 4) {
                $data_primirii = $r;
            }
            $count++;
        }
        $order = new Order($id, $id_stoc, $cantitate, $data_primirii);
        // echo "IDUL ESTE: ". $stock -> id;
        // echo "<br>";
        // echo "nume_produs ESTE: ".$stock -> nume_produs;
        // echo "<br>";

        // echo "cantitate ESTE: ".$order -> cantitate;
        // echo "<br>";

        array_push($orders, $order);
        // print "\n";
    }
    return $orders;
}
function getNumeProdus($id_stoc)
{
    global $conn;

    $getStocksStmt = $conn->prepare('SELECT nume_produs FROM stocks WHERE id = ?');
    $getStocksStmt->bind_param('i', $id_stoc);

    $getStocksStmt->execute();
    $results = $getStocksStmt->get_result();
    $getStocksStmt->close();

    while ($row = $results->fetch_array(MYSQLI_NUM)) {
        foreach ($row as $r) {
            return $r;
        }
        // echo "valoarea este : " . $r;
    }
}
function getIdStoc($nume_produs)
{
    global $conn;

    $getStocksStmt = $conn->prepare('SELECT id FROM stocks WHERE nume_produs = ?');
    $getStocksStmt->bind_param('s', $nume_produs);

    $getStocksStmt->execute();
    $results = $getStocksStmt->get_result();
    $getStocksStmt->close();

    while ($row = $results->fetch_array(MYSQLI_NUM)) {
        foreach ($row as $r) {
            // echo "valoarea este : " . $r;
            return $r;
        }
    }
    return $nume_produs;
}
function adaugaComanda($nume_produs, $cantitate)
{
    $id_stoc = getIdStoc($nume_produs);
    $currentTime = date("Y/m/d") . date("h:i:sa");
    $arrivalTime = date('Y/m/d', strtotime(date("Y/m/d") . ' + 7 day'));
    global $conn;

    $getStocksStmt = $conn->prepare('INSERT INTO orders (id_stoc, cantitate, data_primirii ,created_at, updated_at) VALUES (?,?,?,?,?);');
    $getStocksStmt->bind_param('iisss', $id_stoc, $cantitate, $arrivalTime, $currentTime, $currentTime);

    $getStocksStmt->execute();
    $results = $getStocksStmt->get_result();
    $getStocksStmt->close();
    return $results;
}

class Order
{
    public $id;
    public $nume_produs;
    public $cantitate;
    public $data_primirii;
    function getNumeProdus($id_stoc)
    {
        global $conn;

        $getStocksStmt = $conn->prepare('SELECT nume_produs FROM stocks WHERE id = ?');
        $getStocksStmt->bind_param('i', $id_stoc);

        $getStocksStmt->execute();
        $results = $getStocksStmt->get_result();
        $getStocksStmt->close();

        while ($row = $results->fetch_array(MYSQLI_NUM)) {
            foreach ($row as $r) {
                $nume_produs = $r;
            }
            // echo "valoarea este : " . $r;
        }
        return $nume_produs;
    }

    function __construct($id, $id_stoc, $cantitate, $data_primirii)
    {
        $this->id = $id;
        $this->nume_produs = $this->getNumeProdus($id_stoc);
        $this->cantitate = $cantitate;
        $this->data_primirii = $data_primirii;
    }
}