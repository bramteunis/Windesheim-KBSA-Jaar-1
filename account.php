<?php

include __DIR__ . "/header.php";
include __DIR__ . "/inloggen.php";
$databaseConnection = connectToDatabase();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Account</title>
</head>
<?php
    $query = "SELECT `:FullName`, `PreferredName`, `EmailAddress` FROM people";
    $Statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);

    //    if($R != "test"){
    //        print($query);
    //    }

    // Opvragen van de informatie van de gebruiker
    if(isset($_POST['FullName'])) {
        if (isset($_POST['PreferredName'])) {
            if (isset($_POST['psw'])) {
                if (isset($_POST['EmailAddress'])) {
                    if (isset($_POST['PhoneNumber'])) {
                        echo '<table align="center" border="1px" style="width:600px; line-height:40px;">';
                        echo '<tr>';
                        echo '<th colspan="4" style="color: black"><h2>Gegevens</h2></th>';
                        echo '</tr>';
                        echo '<th style="color: black"> Naam </th>';
                        echo '<th style="color: black"> Gebruik </th>';
                        echo '<th style="color: black"> Email </th>';
                        echo '</tr>';
                        while($rows = $R)
                        {
                            ?>
                            <tr> <td style="color: black"><?php echo $rows['FullName']; ?></td>
                                <td style="color: black"><?php echo $rows['PreferredName']; ?></td>
                                <td style="color: black"><?php echo $rows['EmailAddress']; ?></td>
                            </tr>
                            <?php
                        }
                        echo '</table>';
                    }
                }
            }
        }
    }
?>



