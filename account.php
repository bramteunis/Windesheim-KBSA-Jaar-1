<?php
echo "<div style='margin-top: 20px; text-align: center'>";
if(isset($_SESSION['EmailAddress'])) {
    $Gebruiker_Gebruikersnaam = $_SESSION['PreferredName'];
    $Gebruiker_Voornaam = $_SESSION['FullName'];
    $Gebruiker_Achternaam = $_SESSION['Gebruiker_Achternaam'];
    $Gebruiker_Email = $_SESSION['EmailAddress'];

    echo "<br>";
    echo $Gebruiker_Gebruikersnaam . "<br>";
    echo $Gebruiker_Voornaam . " " . $Gebruiker_Achternaam . "<br>";
    echo $Gebruiker_Email . "<br><br><br><div style='margin-top: 20px; text-align: center'><a class='uitloggen' href='uitloggen.php'>Uitloggen</a ></div>";} else {
    include "login.php";
}

?>
