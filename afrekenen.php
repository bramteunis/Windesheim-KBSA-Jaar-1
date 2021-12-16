<?php

include __DIR__ . "/header.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
function getCart()
{
    if(isset($_SESSION['cart']))
    {               
      //controleren of winkelmandje (=cart) al bestaat
      $cart = $_SESSION['cart'];                  //zo ja:  ophalen
    } else
    {
      $cart = array();                            //zo nee: dan een nieuwe (nog lege) array
    }
    return $cart;                               // resulterend winkelmandje terug naar aanroeper functie
}

?>
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
                <input type="submit" name="afrekenensubmit2" value="Afrekenen">
            </div>
            <span class="underline"></span>
        </form>
    </div>
    <?php
    function debug_to_console($data) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);

        echo "<script>console.log(': " . $output . "' );</script>";
    }

    function testinconsole(){
        debug_to_console("Test is geslaagd");
    }
    //print('<form method="POST" action="" onsubmit="testinconsole()"><input type="submit" name="afrekenensubmit2" value="Afrekenen"></form>');
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
        $date = date('Y-m-d');
        if($tussenvoegsel == ""){
            $volledigenaam = $voornaam.' '.$achternaam;
        }else{
            $volledigenaam = $voornaam.' '.$tussenvoegsel.' '.$achternaam;
        }
        debug_to_console($voornaam." / ".$tussenvoegsel." / ".$achternaam." / ".$postcode." / ".$straatnaam." / ".$email." / ".$telefoonnummer." / ".$huisnummer." / ".$plaats. " / ".$date);
        $Query2 = "INSERT INTO nerdygadgets.customers (CustomerName, BillToCustomerID, CustomerCategoryID, PrimaryContactPersonID, 
                           DeliveryMethodID, DeliveryCityID, PostalCityID, AccountOpenedDate, StandardDiscountPercentage, 
                           IsStatementSent, IsOnCreditHold, PaymentDays, PhoneNumber, FaxNumber, WebsiteURL, DeliveryAddressLine1, 
                           DeliveryAddressLine2, DeliveryPostalCode, PostalAddressLine1, PostalPostalCode, LastEditedBy, 
                           ValidFrom, ValidTo) VALUES ('".$volledigenaam."', 1062, 0, 0, 3, '".$postcode."', '".$postcode."', '".$date."', 0, 0, 
                           0, 7, '".$telefoonnummer."', '".$telefoonnummer."', 'null', '".$huisnummer."', '".$straatnaam."', '".$postcode."', 'null', 0, 
                           0, '".$date."', '9999-12-31')";
        $Statement = mysqli_prepare($databaseConnection, $Query2);
        mysqli_stmt_execute($Statement);
        
        $Query3 = "SELECT CustomerID FROM customers WHERE customername = '".$volledigenaam."' AND deliveryPostalCode = '".$postcode."'";
        $Statement = mysqli_prepare($databaseConnection, $Query3);
        mysqli_stmt_execute($Statement);

        $ReturnableResult = mysqli_stmt_get_result($Statement);
        $ReturnableResult = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC);
        foreach ($ReturnableResult as $row) {
            $customerID = $row["CustomerID"];
        }
        
        $Query4 =   "INSERT INTO nerdygadgets.orders (CustomerID, SalespersonPersonID, ContactPersonID, OrderDate, ExpectedDeliveryDate, IsUndersupplyBackordered, LastEditedBy, LastEditedWhen) 
                    VALUES (".$customerID.", 0, 0, '".$date."', '".$date."', 1, 0, '".$date."')";
        
        $Statement = mysqli_prepare($databaseConnection, $Query4);
        mysqli_stmt_execute($Statement);
        
        $Query5 = "SELECT OrderID FROM orders WHERE CustomerID = '".$customerID."' AND OrderDate = '".$date."'";
        $Statement = mysqli_prepare($databaseConnection, $Query5);
        mysqli_stmt_execute($Statement);
        
        debug_to_console("SELECT OrderID FROM orders WHERE CustomerID = ".$customerID." AND OrderDate = ".$date);
        $ReturnableResult = mysqli_stmt_get_result($Statement);
        $ReturnableResult = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC);
        foreach ($ReturnableResult as $row) {
            $OrderID = $row["OrderID"];
            debug_to_console("Order_id: ".$OrderID);
        }
        
        $cart = getCart();
        foreach($cart as $artikelnummer => $aantalartikel)
        {
        $Query6 =   "INSERT INTO `nerdygadgets`.`orderlines` (`OrderID`, `StockItemID`, `Description`, `PackageTypeID`, `Quantity`, `UnitPrice`, `TaxRate`,
                    `PickedQuantity`, `LastEditedBy`, `LastEditedWhen`) VALUES (".$OrderID.", ".$artikelnummer.",
                    '32 mm Anti stabic bubble wrap (Blue) 10m', 7, ".$aantalartikel.", 250.00, 15.000, 10, 9, '".$date."');";
        
        $Statement = mysqli_prepare($databaseConnection, $Query6);
        mysqli_stmt_execute($Statement);
        }
        //$Statement2 = mysqli_prepare($databaseConnection, $Query2);
        //mysqli_stmt_execute($Statement2);
    }
    ?>

    </body>
    </html>

<?php


?>
