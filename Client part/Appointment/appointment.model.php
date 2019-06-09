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
    function addAppointment($data, $nume, $mail, $telefon, $mesaj) {
        GLOBAL $conn;
        echo "GIGEL SUNT AICI " . $data;
        echo"<br>";

        echo "Data este : ". $data;
        echo"<br>";
        $currentTime = date("Y/m/d") . date("h:i:sa");
        $registerStmt = $conn -> prepare('INSERT INTO appointments (nume, email, telefon, data_programarii, descriere , created_at, updated_at) 
        VALUES(?,?,?,?,?,?,?);');

        $registerStmt -> bind_param('sssssss', $nume, $mail, $telefon, $data, $mesaj, $currentTime, $currentTime);
        
        $success = $registerStmt -> execute();

        $registerStmt -> close();

        return $success;
    }
    function getSuitableHours($hours, $hourAppointment){
        foreach ($hours as $hour)
        {  
            if($hourAppointment === $hour){
                if (($key = array_search($hourAppointment, $hours)) !== false) {
                    unset($hours[$key]);
                }
            }
        }

        echo "<br>";
        foreach ($hours as $hour)
        {  
            echo $hour . " <br>";
        }
        return $hours;
    }
    function checkAvailability($dataChosen){
        GLOBAL $conn;

        $loginStmt = $conn -> prepare('SELECT data_programarii FROM appointments');
        echo"<br>";
        echo "Am ajuns aici macar!";
        $loginStmt -> execute();
        $results = $loginStmt -> get_result();
        $loginStmt -> close();
        $allHours = array('08:00', '10:00', '12:00', '14:00');        
        echo "Am ajuns si aici!";
        while ($row = $results->fetch_array(MYSQLI_NUM))
        {   
            foreach ($row as $r)
            {   
                    $data = $r;
                    echo "AICIT SUNT datele: " . $data;
                    echo  "<br>";
                    $pieces = explode(" ", $data);
                    if($pieces[0] === $dataChosen)
                    {
                        echo "Data aleasa de utilizator este: " . $dataChosen . "<br>";
                        echo "Ora din baza de date este: " . $pieces[1] . " Ar trebui sa excludem aceasta ora!<br>";

                        $hourAppointment = $pieces[1];
                        $allHours = getSuitableHours($allHours, $hourAppointment);
                    }                             
            }
            print "\n";
        }
        return $allHours;

    }
    function isweekend($date){
        $date = strtotime($date);
        $date = date("l", $date);
        $date = strtolower($date);
        echo $date;
        if($date == "saturday" || $date == "sunday") {
            return true;
        } else {
            return false;
        }
    }
    function getValidDates(){       
        $today = date("m/d/Y");
        $timestamp = strtotime($today);
        $days = array();
        for ($i = 0; $i < 14; $i++) {
            $weekDay = strftime('%x', $timestamp);
            if(isweekend($weekDay) === false){            
                if(!empty(checkAvailability($weekDay))){
                    $days[] = $weekDay;            
                }               
            }
            $timestamp = strtotime('+1 day', $timestamp);                     
        }
        return $days;
    }
    function uploadFile($file, $nume, $data){
        // $file = $_FILES['file'];
        print_r($file);
        $file_name = $_FILES['file']['name'];
        $file_type = $_FILES['file']['type'];
        $file_size = $_FILES['file']['size'];
        $file_tem_loc = $_FILES['file']['tmp_name'];
        $pieces = explode(".", $file_name);
        
        $pieces2 = explode(" ", $data);
        $pieces2[0] = str_replace("/", "-",  $pieces2[0]);
        $pieces2[1] = str_replace(":", "-",  $pieces2[1]);

        // $file_name = $mail . $data . $pieces[1];
        // echo $pieces[1]. "<br>"; // extensia ex: .png etc.

        $file_store = "../../Upload/" .  $nume . $pieces2[0] . $pieces2[1].'.' . $pieces[1]; //numele fisierului format este: numeData-Ora.extensie din ex: HELLO05-31-1912-00.jpg

        move_uploaded_file($file_tem_loc, $file_store);
    }
?>