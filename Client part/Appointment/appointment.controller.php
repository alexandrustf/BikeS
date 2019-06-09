<?php
session_start();
include_once './appointment.model.php';

if(isset($_POST['submit'])){
    if(isset($_POST['Ora-select'])&& $_POST['Nume'] && $_POST['Email'] && $_POST['Telefon']&& $_POST['Mesaj']){
        echo "ADAUGAM PROGRAMAREA BE READY!!!  ";
        
        $success = addAppointment(
            $_POST['Ora-select'],
            $_POST['Nume'],
            $_POST['Email'],
            $_POST['Telefon'],
            $_POST['Mesaj']
        );
        if(isset($_FILES['file'])){
            echo "A fost setata o imagine de uploadat!";
            uploadFile($_FILES['file'], $_POST['Nume'], $_POST['Ora-select']);
        }
        session_unset();
        if($success === true)
            $_SESSION['response'] = "Programarea ta a fost inregistrata! Vei primi in cel mai scurt timp raspunsul pe mail. Zi faina!";
        else $_SESSION['response'] = "Ceva nu a mers! Reincearca mai tarziu!";
    }
    else{
        $_SESSION['response'] = "Trebuie sa completezi ora si toate campurile marcate cu * pentru a fi programat!";
    }
}
if(isset($_POST['data'])){
    echo "SALUT";
    if(isset($_POST['Data-select'])){
        echo "SUNTEM AICI!";
        $_SESSION['date'] = $_POST['Data-select'];
        $hours = checkAvailability($_POST['Data-select']);
        $_SESSION['hours'] = $hours;
        foreach( $_SESSION['hours'] as $hour){
            echo $hour;
        }
    }   
}
header('Location: ./appointment.view.php');
?>