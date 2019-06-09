<?php
require '../vendor/autoload.php';
session_start();

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

function register($data)
{
    global $conn;
    $email = $data->email;
    $password = $data->password;
    $stmt = $conn->prepare('INSERT INTO users SET email = ?, password = ?');
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt->bind_param('ss', $email, $password_hash);
    if ($stmt->execute()) {
        http_response_code(200);
        echo '<script>
            alert("Admin was successfully registered.");
        </script>';
    } else {
        http_response_code(400);
        echo '<script>
            alert("Admin was not registered.");
        </script>';
    }
}

function login($data)
{
    global $conn;

    $email = $data->email;
    $password = $data->password;

    $stmt = $conn->prepare('SELECT email password FROM users WHERE email = ? LIMIT 0,1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $num = $stmt->num_rows();

    if ($num > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $password2 = $row['password'];

        if (password_verify($password, $password2)) {
            $secret_key = "YOUR_SECRET_KEY";
            $issuer_claim = "localhost"; // this can be the servername
            $issued_at_claim = time(); // issued at
            $notbefore_claim = $issued_at_claim + 10; //not before (starting 10 seconds after issue)
            $expire_claim = $issued_at_claim + 60 * 60; // expire time in seconds (available 1h)
            $token = array(
                "iss" => $issuer_claim,
                "iat" => $issued_at_claim,
                "nbf" => $notbefore_claim,
                "exp" => $expire_claim,
                "data" => array(
                    "email" => $email
                )
            );

            http_response_code(200);
            $jwt = JWT::encode($token, $secret_key);
            $_SESSION['token'] = $jwt;
            $_SESSION['email'] = $row['email'];
            $stmt = $conn->prepare('UPDATE users SET jwt = ' . $jwt);
            $stmt->execute();
        } else {
            http_response_code(401);
            echo '<script>
                    alert("Login failed!");
                </script>';
        }
    }
}

function check_token()
{
    global $conn;

    $stmt = $conn->prepare('SELECT token password FROM users WHERE email = ? LIMIT 0,1');
    $stmt->bind_param('s', $_SESSION['email']);
    if ($stmt->execute()) {
        if ($_SESSION['token'] === $stmt->fetch()) {
            return true;
        }
    }
    return false;
}
