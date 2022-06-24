<?php
session_start();
include "db.php";

if(isset($_SESSION['logged_in'])){
    header("location:dashboard.php");
}

if(isset($_POST['submit'])){
    $email = $_POST['email'];

    mysqli_query($conn ,"SET @p0='".$email."'");
    
    $sql = "CALL GenerateForgotPasswordCode (@p0)";
    $result = $conn -> query($sql);

    if ( !empty($result->num_rows) && $result->num_rows > 0){
        $row = $result->fetch_assoc();
        echo $row['email'] . " | " . $row['code'];
        $command = 'python python/send_mail.py ' . $row['email'] . " " . $row['code'];
        $output = shell_exec($command);
        header("location:reset_password.php");
        }
    else{
        echo "<h1>No account associated with this email</h1>";
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
    <title>Reliefant | Forgot Password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>

    <link href="css/login_styles.css" rel="stylesheet">
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
            <h4 style="text-align: center; font-weight: bold;">Forgot password</h4>
            <br>
            <form class="form-signin" method="post">
                <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus
                    name="email">
                <button type="submit" class="btn btn-primary" name="submit">Send reset code</button>
            </form>
            <a href="reset_password.php" class="forgot-password">
                Already have a code?
            </a>
        </div>
    </div>
</body>

</html>