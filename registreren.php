<?php
session_start();
include __DIR__ . "/header.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function newUser($FullName, $PreferredName, $HashedPassword, $EmailAddress, $PhoneNumber, $databaseConnection)
{

    // Eerst controleren of de gebruiker niet al bestaat
    $query = "SELECT PersonID FROM people WHERE PreferredName = '".$PreferredName."'";
    $Statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);

    if($R != "test"){
        print("<h1 style='color:black'>Gebruikersnaam is uniek</h1>");
        $psw = password_hash($HashedPassword, PASSWORD_BCRYPT);
        $query = "INSERT INTO people (FullName, PreferredName, SearchName, IsPermittedToLogon, LogonName, IsExternalLogonProvider, HashedPassword, IsSystemUser, IsEmployee, IsSalesperson, UserPreferences, PhoneNumber, FaxNumber, EmailAddress, Photo, CustomFields, OtherLanguages, LastEditedBy, ValidFrom, ValidTo) 
                VALUES ('".$FullName."', '".$PreferredName."', 'kaas', 1, '".$EmailAddress."', 0, '".$psw."', 1, 0, 0, 'null', '".$PhoneNumber."', '(415) 555-0103', '".$EmailAddress."', 'null', 'null', 'null', 1, '9999-12-31', '9999-12-31')";
        $statement = mysqli_prepare($databaseConnection, $query);
        mysqli_stmt_execute($statement);
        print($query);
    }


}

if(isset($_POST['FullName'])) {
    if (isset($_POST['PreferredName'])) {
        if (isset($_POST['psw'])) {
            if (isset($_POST['EmailAddress'])) {
                if (isset($_POST['PhoneNumber'])) {
                    //echo "<script>window.open('categories.php','_self');</script>";
                    newUser($_POST['FullName'],$_POST['PreferredName'],$_POST['psw'],$_POST['EmailAddress'],$_POST['PhoneNumber'], $databaseConnection);
                }
            }
        }
    }
}
?>
<head>
    <title>Registreren</title>
    <link rel="stylesheet" type="text/css" href="registreren.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
          crossorigin="anonymous">
</head>
<form class="loginbox" method="post" action=" ">
    <div id="form_wrapper">
        <div id="form_left">
            <img src="public/productimghighres/nerdylogin.png" alt="computer icon">
        </div>
        <div id="form_right">
            <h1 style="text-align: center; color: black">Registreren</h1>
            <div class="input_container">
                <i id="registrerenI" class="fas fa-envelope"></i>
                <input placeholder="Voornaam" type="text" name="FullName" id="field_email" class='input_field' required>
            </div>
            <div class="input_container">
                <i id="registrerenI" class="fas fa-envelope"></i>
                <input placeholder="Tussenvoegsel" type="text" name="User_Tussenvoegsel" id="field_email" class='input_field'>
            </div>
            <div class="input_container">
                <i id="registrerenI" class="fas fa-envelope"></i>
                <input placeholder="Achternaam" type="text" name="Gebruiker_Achternaam" id="field_email" class='input_field' required>
            </div>
            <div class="input_container">
                <i id="registrerenI" class="fas fa-envelope"> </i>
                <input placeholder="Gebruikersnaam" type="text" name="PreferredName" id="field_email" class='input_field' required>
            </div>
            <div class="input_container">
                <i id="registrerenI" class="fas fa-lock"></i>
                <input  placeholder="Wachtwoord" type="password" name="psw" id="field_password" class='input_field' required>
            </div>
            <div class="input_container">
                <i id="registrerenI" class="fas fa-lock"></i>
                <input  placeholder="Email" type="email" name="EmailAddress" id="field_password" class='input_field' required>
            </div>
            <div class="input_container">
                <i id="registrerenI" class="fas fa-lock"></i>
                <input placeholder="Telefoonnummer" type="tel" name="PhoneNumber" id="field_password" class='input_field' required>
            </div>
            <input type="submit" value="Registreren" id='input_submit' class='input_field'>
        </div>
    </div>
</form>