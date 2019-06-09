<?php 
    ini_set('session.cookie_lifetime', $TWO_HOURS);
    ini_set('session.gc_maxlifetime', $TWO_HOURS);
    
    session_start();
		header('Location: ./appointment.view.php');
?>