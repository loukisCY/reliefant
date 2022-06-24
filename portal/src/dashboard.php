<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script src="js/preview_chart.js" type="text/javascript"></script>

    <link rel="icon" type="image/png" href="assets/favicon.png" />
    <link href="css/dashboard_styles.css" rel="stylesheet">
    <title>Reliefant | Dashboard</title>
</head>

<script>
// if user presses back button while in dashboard, he is getting logged out
window.history.pushState({
    page: 1
}, "", "");
window.onpopstate = function(event) {
    if (event) {
        document.getElementById('logout_button').click();
    }
}
</script>

<script>
function show_patient(str) {
    // document.body.scrollTop = 0;
    // document.documentElement.scrollTop = 0;
    if (str == "") {
        document.getElementById("patient_preview").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("patient_preview").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "patient_preview.php?q=" + str, true);
        xmlhttp.send();


        xmlhttp.onload = function() {
            if (xmlhttp.status == 200) {
                show_chart();
            } else {
                console.log("ERROR");
            }
        }
    }
}
</script>

<body>
    <?php
session_start();
$_SESSION['loginerror'] = false;
if (!isset($_SESSION['logged_in'])){
    header("Location: login.php");
}
else
{
    echo "
        <nav class='navbar navbar-expand-lg navbar-dark bg-dark sticky-top'
        style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
        <div class='container-fluid'>
            <h2 style='color:white;'>Welcome Dr. " . $_SESSION['last_name'] . "</h2>;
            <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarText'
                aria-controls='navbarText' aria-expanded='false' aria-label='Toggle navigation'>
                <span class='navbar-toggler-icon'></span>
            </button>
            <div class='collapse navbar-collapse' id='navbarText'>
                <ul class='navbar-nav me-auto mb-2 mb-lg-0'>
                    <!-- <li class='nav-item'>
                        <a class='nav-link active' aria-current='page' href='#'>Home</a>
                    </li>
                    <li class='nav-item'>
                        <a class='nav-link' href='#'>Features</a>
                    </li>
                    <li class='nav-item'>
                        <a class='nav-link' href='#'>Pricing</a>
                    </li> -->
                </ul>
                <span class='navbar-text'>
                    <a class='btn btn-primary btn-md' href='logout.php' role='button'>Logout</a>
                </span>
            </div>
        </div>
    </nav>
    ";
}

?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm">
                <h1>Your patients:</h1>

                <?php
include "db.php";
mysqli_query($conn, "SET @p0='" . $_SESSION["UID"] . "'");
$sql = "CALL GetPatients (@p0)";
$result = $conn->query($sql);
if ($result->num_rows > 0)
{
    echo "<div style='overflow-y:auto; height: 550px; border:0.5px solid black; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>";
    echo "<table>";
    echo "<tr>";
    echo "<th>ID</th> <th>Firstname</th> <th>Lastname</th> <th>Preview</th>";
    while ($row = $result->fetch_assoc())
    {
        echo "<tr>";
        echo "<td>" . $row["national_id"] . "</td><td>" . $row["first_name"] . "</td><td>" . $row["last_name"] . "</td>";
        echo "<td><input type='Submit' value='See preview' onclick='show_patient(" . $row["UID"] . ")'></td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
}
else
{
    echo "<h2>No Patients</h2>";
}
// Free result set
$result->close();
$conn->next_result();

?>
            </div>
            <div class="col-sm">
                <div id="patient_preview">
                    <h4><br><br>Person info will be listed here.</h4>
                </div>
            </div>
        </div>
</body>

</html>