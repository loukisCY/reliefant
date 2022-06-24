<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <link rel="icon" type="image/png" href="assets/favicon.png" />
    <title>Document</title>
</head>

<style>
.vert-line {
    height: 60px;
    width: 5px;
    background-color: white;
    border-radius: 99px;
    margin: 15px 20px 20px;
}

mark.blue {
    color: #3482fc;
    background: none;
}
</style>

<body>
    <?php
session_start();
include "db.php";
if (!isset($_SESSION['logged_in']))
{
    header("Location: login.php");
}
if (!isset($_SESSION['patient_uid']))
{
    header("Location: dashboard.php");
}
echo "<script>
        window.onload = function() {
        document.querySelector('title').textContent = 'Reliefant | " . $_SESSION['patient_first_name'] . " " . $_SESSION['patient_last_name'] . "';
        }
        </script>";


mysqli_query($conn, "SET @p0='" . $_SESSION["patient_uid"] . "'");
$sql = "CALL AveragePain (@p0)";
$result = $conn->query($sql);
if ($result->num_rows > 0)
{
    $row = mysqli_fetch_assoc($result);
    $avg_pain = $row['pain_amount'];
}

// Free result set
$result->close();
$conn->next_result();
?>

    <!-- <nav class='navbar sticky-top navbar-dark bg-dark'
        style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
        <a class='btn btn-primary btn-md' href='dashboard.php' role='button' style="float-right">To Dashboard</a>
        <a class='btn btn-secondary btn-md' href='logout.php' role='button'>Logout</a>
    </nav> -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top"
        style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
        <div class="container-fluid">
            <!-- <div class="navbar-brand">
                <?php echo "<h3>" . $_SESSION['patient_first_name'] . " " .  $_SESSION['patient_last_name'] . "</h3>";?>
            </div> -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <?php
if (file_exists($_SESSION["patient_profile_img"]))
{
echo "<img src=' " . $_SESSION["patient_profile_img"] . " ' height='90px' width='90px' style=border-radius:50%;/><br>";
}
else
{
    echo "<img src='profile_pictures/default.png' height='90px' width='90px' style=border-radius:50%;/><br>";
}
?>
                    </li>
                    <li class="nav-item">
                        <?php echo "<h3 style='color:white; margin-left:20px; margin-top:25px;'>" . $_SESSION['patient_first_name'] . " " .  $_SESSION['patient_last_name'] . "</h3>";?>
                    </li>
                    <li>
                        <div class="vert-line"></div>
                    </li>
                    <li class="nav-item">
                        <h5 style='color:white; margin-top:15px;'>Latest doctor's visit: <mark class="blue">01 May
                                2022 (static for now)</mark></h5>
                        <h5 style='color:white;'>Average pain in the last (?)month(?): <mark
                                class="blue"><?php echo $avg_pain?></mark>
                        </h5>
                    </li>
                </ul>
                <span class="navbar-text">
                    <a class='btn btn-primary btn-md' href='dashboard.php' role='button'>Dashboard</a>
                    <a class='btn btn-primary btn-md' href='logout.php' role='button'>Logout</a>
                </span>
            </div>
        </div>
    </nav>

</body>


</html>