<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "database.php";
$databaseConnection = connectToDatabase();
$databaseConnection2 = connectToDatabase2();

if (isset($_GET["nummer"])) {
        $Tempnummer = $_GET["nummer"];
    } else {
        $Tempnummer = "Onbekend";
    }


$Query = "DELETE FROM coldroomtemperatures WHERE ColdRoomTemperatureID = ".$Tempnummer;
$Statement2 = mysqli_prepare($databaseConnection2, $Query);
mysqli_stmt_execute($Statement2);

$Statement3 = mysqli_prepare($databaseConnection, $Query);
mysqli_stmt_execute($Statement3);

print('<meta http-equiv="refresh" content="0; url=temperatures.php" />');
?>
