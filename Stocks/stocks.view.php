<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="stocks.css">
    <title>BikeS - Stocks</title>
</head>

<body>
    <nav>
        <img src="../../Images/logo-header.png" alt="Bike service logo" class="logo_image">
        <ul>
            <li>
                <a href="../Appointments/appointments.view.php">
                    Programari
                </a>
            </li>

            <li>
                <a href="../Stocks/stocks.view.php">
                    Stocuri
                </a>
            </li>

            <li>
                <a href="../Orders/orders.view.php">
                    Comenzi
                </a>
            </li>

            <li>
                <form action="stocks.controller.php" method="POST" enctype="multipart/form-data" id="import_form">
                    <label for="import" title="Only .csv and .json file types"> Import stocuri </label>
                    <input type="file" accept=".csv, .json" id="import" name="file">
                    <script>
                        document.getElementById("import").onchange = function() {
                            document.getElementById("import_form").submit();
                        };
                    </script>
                </form>
            </li>

            <li>
                <form action="stocks.controller.php" method="GET">
                    <label for="csv"></label>
                    <button type="submit" id="csv" name="export" value="csv" onclick="alert('Stocks exported to CSV succesfully!')"> Export stocuri CSV </button>
                </form>
            </li>

            <li>
                <form action="stocks.controller.php" method="GET">
                    <label for="json"></label>
                    <button type="submit" id="json" name="export" value="json" onclick="alert('Stocks exported to JSON succesfully!')"> Export stocuri JSON </button>
                </form>
            </li>

            <li>
                <form action="stocks.controller.php" method="GET">
                    <label for="pdf"></label>
                    <button type="submit" id="pdf" name="export" value="pdf" onclick="alert('Stocks exported to PDF succesfully!')"> Export stocuri PDF </button>
                </form>
            </li>


            <li>
                <a href="../Login/login.view.php">
                    Logout
                </a>
            </li>
        </ul>
    </nav>

    <div class="stocks_container">
        <?php
        include_once './stocks.model.php';
        $stocks = getStocks();
        foreach ($stocks as $stock) {
            echo '  <div class="stock">
                                <p>Id produs: ' . $stock->id . '</p>
                                <p>Denumire: ' . $stock->nume_produs . '</p>
                                <p>Cantitate (buc): ' . $stock->cantitate . '</p>
                                <div>
                                    <form action="./stocks.controller.php" method="get">
                                        <label for="adauga">Adauga: </label>
                                        <input type="number" id="adauga" min="1" name="adauga" placeholder="Cantitate (buc)" required>
                                        <button type="submit" name="adaugasubmit" value="' . $stock->id . '">
                                            Adauga!
                                        </button>
                                    </form>

                                    <form action="./stocks.controller.php" method="get">
                                        <label for="elimina">Elimina: </label>
                                        <input type="number" id="elimina" min="1" name="elimina" placeholder="Cantitate (buc)" required>
                                        <button type="submit" name="eliminasubmit" value="' . $stock->id . '">
                                            Elimina!
                                        </button>
                                    </form>
                                </div>
                            </div> ';
        }
        ?>
    </div>
</body>

</html>