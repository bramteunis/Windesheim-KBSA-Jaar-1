<?php
include "cartfuncties.php";
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Winkelwagen</title>
    <link rel="stylesheet" href="public/css/style.css" type="text/css">
</head>
<body>
<h1>Inhoud Winkelwagen</h1>

<?php

include __DIR__ . "/header.php";

$cart = getCart();
foreach($cart as $artikelnummer => $aantalartikel){
    $StockItem = getStockItem($artikelnummer, $databaseConnection);
    $StockItemImage = getStockItemImage($artikelnummer, $databaseConnection);
    print ("<h1 style='color:black;'>".$StockItem['StockItemName']."</h1>");
    print ("<img src="."public/stockitemimg/".str_replace(" ", "%20",strtolower($StockItemImage[0]['ImagePath'])).">");

    
    print('<form method="post">
    <input type="number" name="stockItemID" value="print($artikelnummer)" hidden>
    <input class="ToevoegenWinkelmandbutton ToevoegenWinkelmandbutton1" type="submit" name="submit" value="Verwijderen uit winkelmandje">
    </form>');


    if (isset($_POST["submit"])) {              // zelfafhandelend formulier
        $stockItemID = $artikelnummer;
        removeProductFromCart($stockItemID);         // maak gebruik van geïmporteerde functie uit cartfuncties.php
        
    }

    

}
print_r($cart);


//gegevens per artikelen in $cart (naam, prijs, etc.) uit database halen
//totaal prijs berekenen
//mooi weergeven in html
//etc...

?>
<p><a href='view.php?id=0'>Naar artikelpagina van artikel 0</a></p>
</body>
</html>
