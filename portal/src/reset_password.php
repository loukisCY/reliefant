<?php
session_start();
include "db.php";

if(isset($_SESSION['logged_in'])){
    header("location:dashboard.php");
}

if(isset($_POST['submit'])){
    $email = $_POST['email'];
    // $code = $_POST['code'];
    $code = str_replace("-","",$_POST['code']);
    $new_pass = $_POST['password'];

    mysqli_query($conn ,"SET @p0='".$email."'");
    mysqli_query($conn ,"SET @p1='".$new_pass."'");
    mysqli_query($conn ,"SET @p2='".$code."'");
    
    $sql = "CALL ResetPassword (@p0, @p1, @p2)";
    $result = $conn -> query($sql);

    if (!empty($result->num_rows) && $result->num_rows > 0){
        // echo "<script>alert('Password reset successfully!');</script>'";
        // header("location:login.php");
        echo "<script>
                alert('Password reset successfully!');
                window.location.href='login.php';
            </script>";
        }
    else{
        echo "<script>
                alert('An error occured!');
                window.location.href='reset_password.php';
            </script>";
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
    <title>Reliefant | Reset Password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link href="css/login_styles.css" rel="stylesheet">
    <script src="https://unpkg.com/bootstrap-show-password@1.2.1/dist/bootstrap-show-password.min.js"></script>
    <script src="js/password_control.js" type="text/javascript">
    </script>
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
            <h4 style="text-align: center; font-weight: bold;">Reset password</h4>
            <br>
            <form class="form-signin" method="post">
                <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus
                    name="email">
                <input type="text" class="form-control" placeholder="Reset code" required autofocus name="code">
                <input type="password" name="password" id="inputPassword1" class="form-control"
                    placeholder="New Password" data-toggle="password" />
                <input type="password" id="inputPassword2" class="form-control" placeholder="Confirm Password" required
                    name="password" data-toggle="password">
                <p id="no_match" style="color:red; display:none">Passwords do not match</p>
                <p id="too_short" style="color:red; display:none">Password must be at least 8 characters long</p>
                <button type="submit" class="btn btn-primary" name="submit" id="submit_btn">Reset password</button>
            </form>
            <a href="login.php" class="forgot-password">
                Back to login
            </a>
        </div>
    </div>
</body>

</html>