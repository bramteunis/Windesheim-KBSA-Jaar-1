<?php

include __DIR__ . "/header.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
function getCart()
{
    if(isset($_SESSION['cart']))
    {               
      $cart = $_SESSION['cart'];                  
    } else
    {
      $cart = array();                           
    }
    return $cart;                               
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
    <div class="mainDiv" style="margin-left:20px; margin-top">
        <h2 style="color:black;margin-left: 36%;">Persoonlijke gegevens</h2>
        <form method="post">
            <div class="Information" style="border: blue;border-style: double;height: 396px;border-radius: 22px;padding: 60px;width: 961px;">
                <div class="wrapper1">
                    <label class="font">Voornaam</label>
                    <input class="no-outline" type="text" name="voornaam" required><br><br>
                    <label class="font">Postcode</label>
                    <input class="no-outline" type="text" name="postcode" required><br><br>
                    <label class="font">Straatnaam</label>
                    <input class="no-outline" type="text" name="straatnaam" required><br><br>

                </div>
                <div class="wrapper2">
                    <label class="font">Tussenvoegsel</label>
                    <input class="no-outline" type="text" name="tussenvoegsel"><br><br>
                    <label class="font">Huisnummer</label>
                    <input class="no-outline" type="number" name="huisnummer" required><br><br>
                    <label class="font">Plaats</label>
                    <input class="no-outline" type="text" name="plaats" required>
                    <input style="margin-top:50%;" type="submit" name="afrekenensubmit2" value="Afrekenen">
                </div>
                <div class="wrapper3">
                    <label class="font">Achternaam</label>
                    <input class="no-outline" type="text" name="achternaam" required>
                    <label class="font" style="margin-top: 24px;">E-mail</label>
                    <input class="no-outline" type="email" name="email" required><br><br>
                    <label class="font">Telefoonnummer</label>
                    <input class="no-outline" type="tel" name="telefoonnummer"><br><br>

                </div>
                <span class="underline"></span>
            </div>
        </form>
    </div>
    <?php
    $cart = getCart();
    print("<div id='Pricediv'>");
        foreach($cart as $artikelnummer => $aantalartikel){
            $StockItem = getStockItem($artikelnummer, $databaseConnection);
            print("<div id='CardItems'>");
                if(strlen($StockItem['StockItemName'])<=47){
                    print($StockItem['StockItemName']);
                }else{
                    print(substr($StockItem['StockItemName'],0,47));
                }
            print("</div>");
            print("<div id='CardItemsAmount'>");
                print($aantalartikel."x");
            print("</div>");
        }
    print("</div>");
      
        
    function debug_to_console($data) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);

        echo "<script>console.log(': " . $output . "' );</script>";
    }

    function testinconsole(){
        debug_to_console("Test is geslaagd");
    }
    if(isset($_POST["afrekenensubmit2"])){
        $cart = getCart();
        $Query2="START TRANSACTION;";
        foreach($cart as $artikelnummer => $aantalartikel){
            $StockItem = getStockItem($artikelnummer, $databaseConnection);
            $nieuwevoorraad = str_replace("Voorraad: ", "",$StockItem['QuantityOnHand']) - $aantalartikel;
            $Query2 = $Query2."UPDATE stockitemholdings SET quantityonhand=".$nieuwevoorraad." WHERE stockitemid=".$artikelnummer.";";
        }
        
        
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
        $Query2 = $Query2."INSERT INTO nerdygadgets.customers (CustomerName, BillToCustomerID, CustomerCategoryID, PrimaryContactPersonID, 
                           DeliveryMethodID, DeliveryCityID, PostalCityID, AccountOpenedDate, StandardDiscountPercentage, 
                           IsStatementSent, IsOnCreditHold, PaymentDays, PhoneNumber, FaxNumber, WebsiteURL, DeliveryAddressLine1, 
                           DeliveryAddressLine2, DeliveryPostalCode, PostalAddressLine1, PostalPostalCode, LastEditedBy, 
                           ValidFrom, ValidTo) VALUES ('".$volledigenaam."', 1062, 0, 0, 3, '".$postcode."', '".$postcode."', '".$date."', 0, 0, 
                           0, 7, '".$telefoonnummer."', '".$telefoonnummer."', 'null', '".$huisnummer."', '".$straatnaam."', '".$postcode."', 'null', 0, 
                           0, '".$date."', '9999-12-31');";

        $Query3 = "SELECT max(CustomerID) AS CustomerID  FROM customers;";
        $Statement = mysqli_prepare($databaseConnection, $Query3);
        mysqli_stmt_execute($Statement);

        $ReturnableResult = mysqli_stmt_get_result($Statement);
        $ReturnableResult = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC);
        foreach ($ReturnableResult as $row) {
            $customerID = $row["CustomerID"] + 1;
        }
        
        $Query2 =   $Query2."INSERT INTO nerdygadgets.orders (CustomerID, SalespersonPersonID, ContactPersonID, OrderDate, ExpectedDeliveryDate, IsUndersupplyBackordered, LastEditedBy, LastEditedWhen) 
                    VALUES (".$customerID.", 0, 0, '".$date."', '".$date."', 1, 0, '".$date."');";
       
        $Query5 = "SELECT max(OrderID) AS OrderID FROM orders";
        $Statement = mysqli_prepare($databaseConnection, $Query5);
        mysqli_stmt_execute($Statement);
        $ReturnableResult = mysqli_stmt_get_result($Statement);
        $ReturnableResult = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC);
        foreach ($ReturnableResult as $row) {
            $OrderID = $row["OrderID"] + 1;
            debug_to_console("Order_id: ".$OrderID);
        }
        
        $cart = getCart();
        foreach($cart as $artikelnummer => $aantalartikel)
        {
        $Query2 =   $Query2."INSERT INTO `nerdygadgets`.`orderlines` (`OrderID`, `StockItemID`, `Description`, `PackageTypeID`, `Quantity`, `UnitPrice`, `TaxRate`,
                    `PickedQuantity`, `LastEditedBy`, `LastEditedWhen`) VALUES (".$OrderID.", ".$artikelnummer.",
                    '32 mm Anti stabic bubble wrap (Blue) 10m', 7, ".$aantalartikel.", 250.00, 15.000, 10, 9, '".$date."');";
        }
        $Query2=$Query2."commit;";
        $databaseConnection->multi_query($Query2);
    }
    ?>
    </body>
    </html>

<?php


?>
