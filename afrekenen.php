<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Afreken</title>
    <link rel="stylesheet" href="public/css/gegevens.css">
</head>
<body>
    <div class="mainDiv">
    <h2>Persoonlijke gegevens</h2>
    <form method="post">
        <div class="wrapper1">
            <label class="font">Voornaam</label>
            <input class="no-outline" type="text" name="voornaam" required><br><br>
            <label class="font">Postcode</label>
            <input class="no-outline" type="text" name="postcode" required><br><br>
            <label class="font">Straatnaam</label>
            <input class="no-outline" type="text" name="straatnaam" required><br><br>
            <label class="font">E-mail</label>
            <input class="no-outline" type="email" name="email" required><br><br>
            <label class="font">Telefoonnummer</label>
            <input class="no-outline" type="tel" name="telefoonnummer"><br><br>
        </div>
        <div class="wrapper2">
            <label class="font">Tussenvoegsel</label>
            <input class="no-outline" type="text" name="tussenvoegsel"><br><br>
            <label class="font">Huisnummer</label>
            <input class="no-outline" type="number" name="huisnummer" required><br><br>
            <label class="font">Plaats</label>
            <input class="no-outline" type="text" name="plaats" required>

        </div>
        <div class="wrapper3">
            <label class="font">Achternaam</label>
            <input class="no-outline" type="text" name="achternaam" required>
        </div>
            <span class="underline"></span>
        <?php
            function testinconsole(){
                debug_to_console("Test is geslaagd");
            }
            print('<form method="POST" action="" onsubmit="testinconsole()"><input type="submit" name="afrekenensubmit2" value="Afrekenen"></form>');
            if(isset($_POST["afrekenensubmit2"])){  
                $voornaam = $_POST["voornaam"];
                $achternaam = $_POST["achternaam"];
                $postcode = $_POST["postcode"];
                $straatnaam = $_POST["straatnaam"];
                $email = $_POST["email"];
                $telefoonnummer = $_POST["telefoonnummer"];
                $tussenvoegsel = $_POST["tussenvoegsel"];
                $huisnummer = $_POST["huisnummer"];
                $plaats = $_POST["plaats"];
                $date = date('Y-m-d H:i:s');
                
                $Query2 = "INSERT INTO nerdygadgets.customers (CustomerID, CustomerName, BillToCustomerID, CustomerCategoryID, BuyingGroupID, PrimaryContactPersonID, 
                           AlternateContactPersonID, DeliveryMethodID, DeliveryCityID, PostalCityID, CreditLimit, AccountOpenedDate, StandardDiscountPercentage, 
                           IsStatementSent, IsOnCreditHold, PaymentDays, PhoneNumber, FaxNumber, DeliveryRun, RunPosition, WebsiteURL, DeliveryAddressLine1, 
                           DeliveryAddressLine2, DeliveryPostalCode, DeliveryLocation, PostalAddressLine1, PostalAddressLine2, PostalPostalCode, LastEditedBy, 
                           ValidFrom, ValidTo) VALUES ('1062', ".$voornaam.' '.$tussenvoegsel.' '.$achternaam.", '1062', '0', '0', '0', '0', '3', '".$postcode."', '".$postcode."', '100000', ".$date.", '0', '0', 
                           '0', '7', ".$telefoonnummer.", ".$telefoonnummer.", 'null', 'null', 'null', ".$huisnummer.", ".$straatnaam.", ".$postcode.", 'null', 'null', 'null', 'null', 
                           0, ".$date.", '9999-12-31');";
                
                $Statement2 = mysqli_prepare($databaseConnection, $Query2);
                mysqli_stmt_execute($Statement2);     
            }
        ?>
    </form>
    </div>
</body>
</html>
<?php


?>
