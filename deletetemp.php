<?php
include "database.php";
$databaseConnection = connectToDatabase();
$databaseConnection2 = connectToDatabase2();

if (isset($_GET["nummer"])) {
        $Tempnummer = $_GET["nummer"];
    } else {
        $Tempnummer = "Onbekend";
    }


$Query = "DELETE FROM coldroomtemperatures WHERE ColdRoomTemperatureID = ".$Tempnummer;
$Statement2 = mysqli_prepare($databaseConnection, $Query);
mysqli_stmt_execute($Statement2);

?>
