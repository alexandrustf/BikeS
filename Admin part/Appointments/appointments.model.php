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

function getAppointments()
{
    global $conn;

    $getAppointmentsStmt = $conn->prepare('SELECT id, nume, email, telefon, data_programarii, descriere, status FROM appointments');

    $getAppointmentsStmt->execute();
    $results = $getAppointmentsStmt->get_result();
    $getAppointmentsStmt->close();

    $appointments = array();
    $id = 0;
    $nume = "";
    $email = "";
    $telefon = "";
    $data_programarii = "";
    $descriere = "";
    $status = 0;
    while ($row = $results->fetch_array(MYSQLI_NUM)) {
        $count = 1;

        foreach ($row as $r) {
            // print "Aici avem coloana din linie : $r ";
            // echo  "<br>";
            if ($count == 1) {
                $id = $r;
            }
            if ($count == 2) {
                $nume = $r;
            }
            if ($count == 3) {
                $email = $r;
            }
            if ($count == 4) {
                $telefon = $r;
            }
            if ($count == 5) {
                $data_programarii = $r;
            }
            if ($count == 6) {
                $descriere = $r;
            }
            if ($count == 7) {
                $status = $r;
            }
            $count++;
        }
        $appointment = new Appointment($id, $nume, $email, $telefon, $data_programarii, $descriere, $status);
        // echo "IDUL ESTE: ". $stock -> id;
        // echo "<br>";
        // echo "nume_produs ESTE: ".$stock -> nume_produs;
        // echo "<br>";

        // echo "cantitate ESTE: ".$stock -> cantitate;
        // echo "<br>";

        array_push($appointments, $appointment);
        // print "\n";
    }
    return $appointments;
}
function acceptAppointment($id, $pret){

    global $conn;

    $getAppointmentsStmt = $conn->prepare('UPDATE appointments SET status = ? WHERE id = ?');
    $status = 1;
    $getAppointmentsStmt->bind_param('ii', $status, $id);

    $getAppointmentsStmt->execute();
    $results = $getAppointmentsStmt->get_result();
    $getAppointmentsStmt->close();
    $mesaj = true;
    sendMail($id, $mesaj, $pret);
        //send MAIL to client

}
function declineAppointment($id, $motiv){
    // DELETE FROM table_name WHERE condition;
    global $conn;
    $mesaj = false;
    sendMail($id, $mesaj, $motiv);
    //send MAIL to client
    $getAppointmentsStmt = $conn->prepare('DELETE FROM appointments WHERE id = ?');
    $getAppointmentsStmt->bind_param('i', $id);

    $getAppointmentsStmt->execute();
    $results = $getAppointmentsStmt->get_result();
    $getAppointmentsStmt->close();
}
function sendMail($id, $raspuns, $response){
    global $conn;

    $getAppointmentsStmt = $conn->prepare('SELECT nume, email, data_programarii FROM appointments WHERE id = ?');
    $getAppointmentsStmt->bind_param('i', $id);

    $getAppointmentsStmt->execute();
    $results = $getAppointmentsStmt->get_result();
    $getAppointmentsStmt->close();

    $appointments = array();
    $nume = "";
    $mail ="";
    $data_programarii = "";

    while ($row = $results->fetch_array(MYSQLI_NUM)) {
        $count = 1;

        foreach ($row as $r) {
            // print "Aici avem coloana din linie : $r ";
            // echo  "<br>";
            if ($count == 1) {
                $nume = $r;
            }
            if ($count == 2) {
                $mail = $r;
            }
            if ($count == 3) {
                $data_programarii = $r;
            }
            $count++;
        }
    }
    echo 'Mail-ul este: ' . $mail;
    echo '<br>nume-ul este: ' . $nume;
    echo '<br>data_programarii este: ' . $data_programarii;

    if($raspuns === true){
        $mesaj = '
            Buna ziua, d-le'. $nume .'! 
            
                Programarea dumneavoastra a fost acceptata si va asteptam la sediul nostru la data de: '. $data_programarii . ' la adresa Sf. Lazar, nr. 11. Pretul reparatiei estimativ este de: ' .  $response . '. Va uram o zi buna!
                    Cu respect,
                Reprezentat BikeS, Popescu Ion';
            $mesaj = wordwrap($mesaj,70);

            mail($mail,"BikeS - Programare reparatie", $mesaj);

    }
    else{
        $mesaj = '
            Buna ziua, d-le '. $nume .'! 
            
            Din pacate, programarea dumneavoastra  de la data de'. $data_programarii . ' nu a fost acceptata. Motivul este: ' .  $response . '. Va mai asteptam si va uram o zi buna!
            Cu respect,
        Reprezentat BikeS, Popescu Ion';
        $mesaj = wordwrap($mesaj,70);

        mail($mail,"BikeS - Programare reparatie", $mesaj);
    }
}
class Appointment
{
    public $id;
    public $nume;
    public $email;
    public $telefon;
    public $data_programarii;
    public $ora_programarii;
    public $descriere;
    public $fileSrc;
    public $fileType;
    public $status;

    function __construct($id, $nume, $email, $telefon, $data_programarii, $descriere, $status)
    {
        $this->id = $id;
        $this->nume = $nume;
        $this->email = $email;
        $this->telefon = $telefon;
        $pieces = explode(" ", $data_programarii);
        $this->data_programarii = $pieces[0];
        $this->ora_programarii = $pieces[1];
        $this->descriere = $descriere;

        $src = $this->checkFile($nume, $pieces[0], $pieces[1]);
        $this->fileSrc = $src; 
        $statusString = "";
        if($status === 0 ){
            $statusString = 'Asteapta Raspuns..';
        }
        else $statusString = 'Programat';
        $this->status = $statusString;
    }

    function checkFile($nume, $data_programarii, $ora_programarii)
    {
        // EU05-29-1914-00 numeData(cu -) si ora(cu -)
        $data_programarii = str_replace("/", "-",  $data_programarii);
        $ora_programarii = str_replace(":", "-",  $ora_programarii);
        $filename = $nume . $data_programarii . $ora_programarii;
        // echo $filename;
        $file =  $this->search_file('../../Upload', $filename);
        if(isset($file)){
            // echo 'Aici avem file : '. $file;
            $pieces = explode("\Upload", $file);
            // echo '<br>Aici este src-ul fisierului SPLITAT: ' . $pieces[1];
            $pieces2 = explode(".", $pieces[1]);
            $this->fileType = $pieces2[1];
            return '../../Upload/' . $pieces[1];
        }
        return 0;
    }


function search_file($dir,$file_to_search){

    $files = scandir($dir);

    foreach($files as $key => $value){

        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);

        if(!is_dir($path)) {
            $pieces = explode(".", $value);
            if($file_to_search == $pieces[0]){
                // echo "file found<br>";
                // echo $path;
                $pieces = explode(" ", $path);
                // echo '<br>Aici avem ceva dar nu stiu ce: ' . $pieces[0]; // piece1                
                return $pieces[0];
                break;
            }

        } else if($value != "." && $value != "..") {

            search_file($path, $file_to_search);

        }  
    } 
}
}
?>
