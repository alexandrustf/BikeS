<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <title>Fa rezervare!</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="../header_footer.css">
    <link rel="stylesheet" type="text/css" href="appointment.css">
    <link rel="stylesheet" type="text/css" href="responsive.css">
	<link type="text/css" rel="stylesheet" href="style.css">
	<script src="script.js"></script>
</head>

<body>
    <div class="wrapper">
        <header>
            <div class="logo">
                <img src="../../Images/logo-header.png" alt="BikeRepairLogo">
            </div>

            <div class="separate_contact">
                +40 757 256 789 <br> BikeS@gmail.com
            </div>

            <div class="separate_contact">
                Iasi, Sf. Lazar nr. 13
            </div>

            <div class="separate_contact">
                Luni-Vineri <br> 09:00 - 18:00
            </div>
        </header>

        <nav>
            <div class="nav_element">
                <a href="../Home/home.html">Acasa</a>
            </div>

            <div class="nav_element">
                <a href="../Services/services.html">Servicii</a>
            </div>

            <div class="book_appointment">
                <a href="../Appointment/appointment.view.php">Fa rezervare!</a>
            </div>
        </nav>

        <main>
            <div class="appointment_form_wrapper">
                <div class="appointment_form_header">
                    <h2>Rezerva!</h2>
                </div>

                <div class="appointment_form_body">
                    <form action="./appointment.controller.php" method="POST" enctype="multipart/form-data">
						<div id="calendar-container">
							<div id="calendar-header">
								<span id="calendar-month-year"></span>
							</div>
							<div id="calendar-dates">
							</div>
						</div>	
                        <div>
                            <label for="Data-select" name="Data-select"></label>
                            <select name="Data-select" id="Data-select" >
                                <option value="" selected>--Data--</option>
                                <?php
                                    include_once './appointment.model.php';
                                    $validDates = getValidDates();
                                    foreach($validDates as $day){
                                        echo '<option value="' . $day .'">' . $day . '</option>';
                                    }
                                ?>
                            </select>
                            <button type="submit" name="data" value ="data" formaction="./appointment.controller.php">
                                Vezi orele disponibile!
                            </button>
                        </div>
                        <div>
                                <label for="Ora-select" name="Ora-select"></label>
          
                                    <?php
                                        if(isset($_SESSION['hours'])){
                                            foreach( $_SESSION['hours'] as $hour){
                                                echo '<div class="ora-radio">
                                                        <input type="radio" name="Ora-select" class="radio" value="' .  $_SESSION['date'] . " " . $hour .'"><p>'. $_SESSION['date'] . " " . $hour ."</p>";
                                                echo '</div>';
                                            }
                                        }
                                    ?>
                        </div>						
                        <div>
                            <label for="Nume"></label>
                            <input type="text" id="Nume" name="Nume" placeholder="Nume*" ><br />
                        </div>

                        <div>
                            <label for="Email"></label>
                            <input type="email" id="Email" name="Email" placeholder="Email*" ><br />
                        </div>

                        <div>
                            <label for="Telefon"></label>
                            <input type="tel" id="Telefon" name="Telefon" placeholder="Telefon*" >
                        </div>

                        <div>
                            <!-- <p>Scrie o descriere!</p> -->
                            <label for="Mesaj"></label>
                            <input name="Mesaj" id="Mesaj" placeholder="Descriere*" >
                            
                        </div>

                        <div id="upload_files">
                            <!-- <p>Incarca o poza sau un video!</p> -->
                            <label for="Upload_files"></label>
                            <input type="file" name="file"/>
                        </div>
                        
                        <button type="submit" name="submit" value ="submit">
                            Programeaza!
                        </button>
                        <?php
                            if(isset($_SESSION['response'])){
                                echo "<p>" . $_SESSION['response'] . "</p>";
                                session_unset();
                            } 
                        ?>
                    </form>
                </div>
            </div>

        </main>
    </div>

    <footer>
        <div class="logo">
            <img src="../../Images/logo-footer.png" alt="BikeRepairLogo">
        </div>

        <div class="separate_contact">
            +40 757 256 789 <br> BikeS@gmail.com
        </div>

        <div class="separate_contact">
            Iasi, Sf. Lazar nr. 13
        </div>

        <div class="separate_contact">
            Luni-Vineri <br> 09:00 - 18:00
        </div>
    </footer>
</body>

</html>
