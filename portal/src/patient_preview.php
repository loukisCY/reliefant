<?php
session_start();
include "db.php";
if (!isset($_SESSION['logged_in'])) header("Location: login.php");

$q = intval($_GET['q']);

mysqli_query($conn, "SET @p0='" . $_SESSION["UID"] . "'");
mysqli_query($conn, "SET @p1='" . $q . "'");
$sql = "CALL GetPatientPreview (@p0, @p1)";
$result = $conn->query($sql);

$row = $result->fetch_assoc();
$profile_img_filename = 'profile_pictures/' . $row["national_id"] . '.png';

$_SESSION["patient_profile_img"] = $profile_img_filename;
$_SESSION["patient_national_id"] = $row["national_id"];
$_SESSION["patient_uid"] = $row["UID"];
$_SESSION["patient_first_name"] = $row["first_name"];
$_SESSION["patient_last_name"] = $row["last_name"];
$_SESSION["patient_email"] = $row["email"];

// Free result set
$result->close();
$conn->next_result();

mysqli_query($conn, "SET @p0='" . $_SESSION["patient_uid"] . "'");
// GET THE LAST 10 DAYS OF PAIN
mysqli_query($conn, "SET @p1='" . '10' . "'");
$sql = "CALL GetPain (@p0, @p1)";
$result = $conn->query($sql);

if ($result->num_rows > 0)
{
    while ($row = mysqli_fetch_assoc($result))
    {
        $res[] = $row; // Inside while loop
        
    }

    echo "<meta name='chart_data_x' content='";
    foreach ($res as $r)
    {
        echo $r['datetime'] . " ";
    }
    echo "'>";

    echo "<meta name='chart_data_y' content='";
    foreach ($res as $r)
    {
        echo $r['pain_amount'] . " ";
    }
    echo "'>";
}
else
{
    echo "<meta name='chart_data_x' content=''>";
    echo "<meta name='chart_data_y' content=''>";
}

// Free result set
$result->close();
$conn->next_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <title></title>
</head>

<body>
    <br>
    <div class="container"
        style="background:#f1f1f1;  border-radius: 20px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
        <div class="row">
            <div class="col-sm-auto">
                <?php
if (file_exists($profile_img_filename))
{
    echo "<br><img src=' " . $profile_img_filename . " ' height='150px' width='150px' style=border-radius:50%;/><br>";
}
else
{
    echo "<br><img src='profile_pictures/default.png' height='150px' width='150px' style=border-radius:50%;/><br>";
}
?>
            </div>
            <div class="col-md-auto">
                <?php
echo "<h4><br><b>ID: </b>" . $_SESSION["patient_national_id"] . "<br><b>Name: </b> " . $_SESSION["patient_first_name"] . "<br><b>Surname: </b> " . $_SESSION["patient_last_name"] . "<br><b>Email: </b> " . $_SESSION["patient_email"] . "</h4><br>";
?>
            </div>
        </div>
        <br>
    </div>
    <br>
    <div class="container-fluid" id="chart_container"
        style="background: #f1f1f1; border-radius: 20px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
        <!-- <canvas id="myChart" style="width:100%;height: 280px;"></canvas> -->
        <div id="chart_div"></div><br>
    </div>

    <br><a id='patient_button' class='btn btn-primary btn-lg' href='patient.php' role='button'
        style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>Patient's
        Page</a>

    <br>
</body>

</html>