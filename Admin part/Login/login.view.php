<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="login.css">
    <title>BikeS - Login</title>
</head>

<body>
    <main>
        <img class="logo-image" src="../../Images/logo-header.png" alt="Bike service logo">

        <form action="login.controller.php" method="POST">
            <label for="email"></label>
            <input type="text" id="email" name="email" placeholder="Email" required>

            <label for="password"></label>
            <input type="password" id="password" name="password" placeholder="Parola" required>

            <input type="submit" class="submit-button" name="submit" value="Login">
            <input type="submit" class="submit-button" name="submit" value="Register">
        </form>
    </main>
</body>

</html>