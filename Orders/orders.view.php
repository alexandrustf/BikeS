<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../Orders/orders.css">
    <title>BikeS - Orders</title>
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
                <form action="orders.controller.php" method="POST" enctype="multipart/form-data">
                    <label for="import" title="Only .csv and .json file types"> Import comenzi </label>
                    <input type="file" accept=".csv, .json" id="import" name="file">
                    <input type="submit" name="submit">
                </form>
            </li>

            <li>
                <form action="orders.controller.php" method="GET">
                    <label for="csv"></label>
                    <button type="submit" id="csv" name="export" value="csv" onclick="alert('Orders exported to CSV succesfully!')"> Export comenzi CSV </button>
                </form>
            </li>

            <li>
                <form action="orders.controller.php" method="GET">
                    <label for="json"></label>
                    <button type="submit" id="json" name="export" value="json" onclick="alert('Orders exported to JSON succesfully!')"> Export comenzi JSON </button>
                </form>
            </li>

            <li>
                <form action="orders.controller.php" method="GET">
                    <label for="pdf"></label>
                    <button type="submit" id="pdf" name="export" value="pdf" onclick="alert('Orders exported to PDF succesfully!')"> Export comenzi PDF </button>
                </form>
            </li>

            <li>
                <a href="../Login/login.view.php">
                    Logout
                </a>
            </li>
        </ul>

        <form class="order-form" action="./orders.controller.php" method="GET">
            <label for="comanda-select"></label>
            <select id="comanda-select" name="comanda-select" required>
                <option value="" selected>--Produs--</option>
                <option value="angrenaj">angrenaj</option>
                <option value="anvelopa">anvelopa</option>
                <option value="baieu">baieu</option>
                <option value="cadru">cadru</option>
                <option value="camera">camera</option>
                <option value="cuvete">cuvete</option>
                <option value="disc-frana">disc frana</option>
                <option value="etrier">etrier</option>
                <option value="furca">furca</option>
                <option value="ghidon">ghidon</option>
                <option value="janta">janta</option>
                <option value="lant">lant</option>
                <option value="pedala">pedala</option>
                <option value="pinion">pinion</option>
                <option value="placuta-frana">placuta frana</option>
                <option value="sa">sa</option>
                <option value="schimbator">schimbator</option>
                <option value="spita">spita</option>
            </select>

            <label for="comanda"></label>
            <input type="number" id="comanda" min="1" name="comanda" placeholder="Cantitate (buc)" required>
            <button type="submit" name="submit">Comanda!</button>
        </form>
    </nav>


    <div class="orders-container">
        <?php
        include_once './orders.model.php';
        $orders = getOrders();
        foreach ($orders as $order) {

            echo '        
            <div class="order">
                <p>Id comanda: ' . $order->id . '</p>
                <p>Produs: ' . $order->nume_produs . '</p>
                <p>Cantitate: ' . $order->cantitate . ' buc</p>
                <p>Data primirii: ' . $order->data_primirii . '</p>
            </div>';
        }

        ?>
    </div>
</body>

</html>