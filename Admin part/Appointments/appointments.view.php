<?php
session_start();
include_once '../Login/login.model.php';

if(isset($_SESSION['key'])){ 
    if(checkJWT($_SESSION['key']) === false){
        header('Location: ../Login/login.view.php');
    }
}
else header('Location: ../Login/login.view.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="appointments.css">
    <title>BikeS - Appointments</title>
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
                <label for="import_appointments" title="Only .csv and .json file types"> Import programari </label>
                <input type="file" accept=".csv, .json" id="import_appointments" name="import_appointments">
            </li>

            <li>
                <label title="Only .csv, .json and .pdf file types"> Export programari </label>
            </li>

            <li>
                <a href="../Login/login.controller.php">
                    Logout
                </a>
            </li>
        </ul>
    </nav>

    <div class="appointments_container">
        <?php
            include_once './appointments.model.php';
            $appointments = getAppointments();
            foreach($appointments as $appointment){
                echo '
                <div class="appointment">
                    <p>Id: ' . $appointment->id . '</p>
                    <p>Nume: ' . $appointment->nume . '</p>
                    <p>Telefon: ' . $appointment->telefon . '</p>
                    <p>Email: ' . $appointment->email . '</p>
                    <p>Data: ' . $appointment->data_programarii . '</p>
                    <p>Ora: ' . $appointment->ora_programarii . '</p>
                    <p>Descriere: ' . $appointment->descriere . '</p>';
                    // echo 'SUNTEM IN VIEW si afisam src-ul: ' . $appointment->fileSrc;
                    // echo $appointment->fileSrc;

                    if($appointment->fileSrc !== 0){
                        // echo "<br>AICI AVEM AJUNS!!!". $appointment->fileSrc;
                        $pieces = explode('.', $appointment->fileSrc);
                        // echo 'Nu inteleg de ce nu afiseaza nimic aici: ' . $pieces[1];
                        if($appointment->fileType ==='jpg' || $appointment->fileType ==='png'|| $appointment->fileType ==='gif'){
                            echo '<img src="'. $appointment->fileSrc . '" alt="Bike Image" >';
                       }
                       elseif($appointment->fileType ==='mp4' || $appointment->fileType ==='ogg'|| $appointment->fileType ==='gif'){
                        echo 
                        ' <video controls>
                            <source src="'. $appointment->fileSrc .'" type="video/mp4">
                            Your browser does not support HTML5 video.
                        </video>';
                       }
                       elseif($appointment->fileType ==='ogg'){
                        echo 
                        ' <video controls>
                            <source src="'. $appointment->fileSrc .'" type="video/ogg">
                            Your browser does not support HTML5 video.
                        </video>';
                       }
                    }
                    
                   echo '<div><p>Status: ' . $appointment->status . '</p>';
                    if($appointment->status === 'Asteapta Raspuns..'){                        
                        echo'      
                        <form action="./appointments.controller.php" method="POST">
                            <label>Pretul: </label>
                            <input type="text" name="pret" value="pret">
                            <button type="submit" name="Accepta" value="'. $appointment->id .'">Accepta!</button>
                        </form>    

                        <form action="./appointments.controller.php" method="POST">
                            <label>Motiv:</label>
                            <input type="text" name="motiv" value="motiv">
                            <button type="submit" name="Refuza" value="'. $appointment->id .'">Refuza!</button>
                        </form>';
                    }
                echo'</div>';

                echo'</div>';
                
            }

        ?>
    </div>
</body>

</html>