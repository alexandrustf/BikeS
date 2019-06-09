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

function getStocks()
{
    global $conn;

    $getStocksStmt = $conn->prepare('SELECT id, nume_produs, cantitate FROM stocks');

    $getStocksStmt->execute();
    $results = $getStocksStmt->get_result();
    $getStocksStmt->close();

    $stocks = array();
    $id = 0;
    $nume_produs = "";
    $cantitate = 0;
    while ($row = $results->fetch_array(MYSQLI_NUM)) {
        $count = 1;

        foreach ($row as $r) {
            // print "Aici avem coloana din linie : $r ";
            // echo  "<br>";
            if ($count == 1) {
                $id = $r;
            }
            if ($count == 2) {
                $nume_produs = $r;
            }
            if ($count == 3) {
                $cantitate = $r;
            }
            $count++;
        }
        $stock = new Stock($id, $nume_produs, $cantitate);
        // echo "IDUL ESTE: ". $stock -> id;
        // echo "<br>";
        // echo "nume_produs ESTE: ".$stock -> nume_produs;
        // echo "<br>";

        // echo "cantitate ESTE: ".$stock -> cantitate;
        // echo "<br>";

        array_push($stocks, $stock);
        // print "\n";
    }
    return $stocks;
}

function eliminaStoc($id, $valoareEliminata)
{
    global $conn;
    $getStocksStmt = $conn->prepare('SELECT cantitate FROM stocks WHERE id = ?');
    $getStocksStmt->bind_param('i', $id);
    $getStocksStmt->execute();
    $results = $getStocksStmt->get_result();
    $getStocksStmt->close();
    while ($row = $results->fetch_array(MYSQLI_NUM)) {
        foreach ($row as $r) {
            $newValue = $r - $valoareEliminata;
        }
        echo "valoarea este : " . $r;
    }
    if ($newValue < 20) {
        //fa comanda
        adaugaComandaAutomat($id, 100);
    }
    if ($newValue >= 0) {
        $eliminateStmt = $conn->prepare('UPDATE stocks SET cantitate = ? WHERE id = ?;');
        $eliminateStmt->bind_param('ii', $newValue, $id);

        $success = $eliminateStmt->execute();

        $eliminateStmt->close();

        return $success;
    }
}
function adaugaComandaAutomat($id_stoc, $cantitate) // de fapt trebuia pusa in orders.model.php
{
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
function adaugaStoc($id, $valoareAdaugata)
{
    global $conn;
    $getStocksStmt = $conn->prepare('SELECT cantitate FROM stocks WHERE id = ?');
    $getStocksStmt->bind_param('i', $id);
    $getStocksStmt->execute();
    $results = $getStocksStmt->get_result();
    $getStocksStmt->close();
    while ($row = $results->fetch_array(MYSQLI_NUM)) {
        foreach ($row as $r) {
            $newValue = $r + $valoareAdaugata;
        }
        echo "valoarea este : " . $r;
    }
    if ($newValue >= 0) {
        $eliminateStmt = $conn->prepare('UPDATE stocks SET cantitate = ? WHERE id = ?;');
        $eliminateStmt->bind_param('ii', $newValue, $id);

        $success = $eliminateStmt->execute();

        $eliminateStmt->close();

        return $success;
    }
}

class Stock
{
    public $id;
    public $nume_produs;
    public $cantitate;

    function __construct($id, $nume_produs, $cantitate)
    {
        $this->id = $id;
        $this->nume_produs = $nume_produs;
        $this->cantitate = $cantitate;
    }
}