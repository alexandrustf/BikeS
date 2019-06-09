<?php
session_start();
include_once './appointments.model.php';
include_once '../Login/login.model.php';
if(isset($_SESSION['key'])){
    if(checkJWT($_SESSION['key']) === true){

        if (isset($_POST['Accepta'])) {
            // echo "AM FOLSIT GET" . $_POST['eliminasubmit'] . " " . $_POST['elimina'];
            $id = $_POST['Accepta'];
            $pret = $_POST['pret'];
            echo 'id-ul este: ' . $id .' si pretul este: '. $pret;

            $success = acceptAppointment($id, $pret);
        }

        if (isset($_POST['Refuza'])) {
            // echo "AM FOLSIT GET" . $_POST['adaugasubmit'] . " " . $_POST['adauga'];
            $id = $_POST['Refuza'];
            $motiv = $_POST['motiv'];
            echo 'id-ul este: ' . $id .' si motivul este: '. $motiv;
            // echo "COdul este: " . $success = adaugaStoc($id, $valoareAdaugata);
            $success = declineAppointment($id, $motiv);

        }
        header('Location: ./appointments.view.php');
    } else header('Location: ../Login/login.view.php');
}
else header('Location: ../Login/login.view.php');
?>