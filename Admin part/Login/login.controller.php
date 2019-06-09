<?php

// session_start();
include_once 'login.model.php';

if (isset($_POST['submit'])) {
    // file_put_contents("user_data.txt", file_get_contents("php://input"));
    // $data = json_decode(file_get_contents("user_data.txt"));
    // $query_string = implode('', $_POST);
    // $query_string = explode("&", $query_string);
    // $query_string = implode('', $query_string);
    // $data = json_decode($query_string);
    // echo gettype($data);

    $query_string = file_get_contents("php://input");
    $keywords = preg_split("/[\s,=,&]+/", $query_string);
    $arr = [];
    for ($i = 0; $i < sizeof($keywords); $i++) {
        $arr[$keywords[$i]] = $keywords[++$i];
    }
    $data = (object)$arr;

    header('Location: login.view.php');

    switch ($_POST['submit']) {
        case 'Login':
            login($data);
            break;

        case 'Register':
            register($data);
            break;
    }
}
