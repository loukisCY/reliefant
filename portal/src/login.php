<?php
session_start();
include "db.php";

if(isset($_SESSION['logged_in'])){
    header("location:dashboard.php");
}

if(isset($_POST['submit'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    mysqli_query($conn ,"SET @p0='".$email."'");
    mysqli_query($conn ,"SET @p1='".$password."'");

    $sql = "CALL Login (@p0,@p1)";
    $result = $conn -> query($sql);

    if ($result->num_rows > 0){
        $row = $result->fetch_assoc();

        $_SESSION['UID'] = $row["UID"];
        $_SESSION['first_name'] = $row["first_name"];
        $_SESSION['last_name'] = $row["last_name"];
        $_SESSION['email'] = $row["email"];

        $_SESSION['logged_in'] = true;
        $_SESSION['login_attempts'] = 0;
        header("location:dashboard.php");
    }
    else{
        $_SESSION['login_attempts'] = $_SESSION['login_attempts'] + 1;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="assets/favicon.png" />
    <title>Reliefant | Login</title>
    <link href="css/login_styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">

    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="js/show_password.js"></script>

    <link href="css/login_styles.css" rel="stylesheet">

    <script src="https://unpkg.com/bootstrap-show-password@1.2.1/dist/bootstrap-show-password.min.js"></script>
    <script src="jquery.min.js"></script>
    <script src="bootstrap.min.js"></script>
    <script src="bootstrap-show-password.js"></script>



    <!------ Include the above in your HEAD tag ---------->
</head>

<script>
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
</script>

<body>
    <div class="container">
        <div class="card card-container"
            style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
            <h2 style="text-align: center; font-weight: bolder;">Reliefant Doctors Portal</h2>
            <img id="profile-img" class="profile-img-card" src="assets/doc.png" />
            <form class="form-signin" method="post">
                <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus
                    name="email">
                <input type="password" id="inputPassword" class="form-control" placeholder="Password" required
                    name="password" data-toggle="password">
                <button type="submit" class="btn btn-primary" name="submit">Login</button>
            </form>
            <a href="forgot_password.php" class="forgot-password">
                Forgot the password?
            </a>
            <?php
            if($_SESSION['login_attempts'] > 0){
                echo "<br><h6 style='color:red; font-weight:bold;'>Wrong credentials! Please try again</h6>";
            }
            ?>
        </div>
    </div>
</body>

</html>