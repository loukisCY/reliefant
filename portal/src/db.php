<?php

$servername = "localhost";
$username = "client";
$password = "client";
$dbname = "reliefant";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if ($conn->connect_error){
    die("Connection failed" . $conn ->connect_error);
}
else{
    $_SESSION['login_attempts'] = 0;
}
?>