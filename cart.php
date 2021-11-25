<?php
include "cartfuncties.php";
//include "browse.php";

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
    if($aantalartikel > 0){
        
        $StockItem = getStockItem($artikelnummer, $databaseConnection);
        $StockItemImage = getStockItemImage($artikelnummer, $databaseConnection);
        print ("<h1 style='color:black;'>".$StockItem['StockItemName']."</h1>");
        print ("<img src="."public/stockitemimg/".str_replace(" ", "%20",strtolower($StockItemImage[0]['ImagePath'])).">");
        
        //print ("<h1 class='StockItemPriceText'>".'€'.sprintf('%0.2f', berekenVerkoopPrijs($row['RecommendedRetailPrice'], $row['TaxRate']))."</h1>");
        //print($_SESSION['prijs']);
        
        
        //print ("<h1 class='StockItemPriceText'>".'€'.sprintf('%0.2f', berekenVerkoopPrijs($row['RecommendedRetailPrice'], $row['TaxRate']))."</h1>");
        
       

        print('<form method="post">');  
        print('<input type="number" name="stockItemID" value="print($artikelnummer)" hidden>');
        print('<input class="ToevoegenWinkelmandbutton ToevoegenWinkelmandbutton1" type="submit" name="submit" value="Verwijderen uit winkelmandje">');
        print('</form>');

        if (isset($_POST["submit"])) {              // zelfafhandelend formulier
            $stockItemID = $artikelnummer;
            removeProductFromCart($stockItemID);         // maak gebruik van geïmporteerde functie uit cartfuncties.php
        }
    }
}
print_r($cart);

?>
<p><a href='view.php?id=0'>Naar artikelpagina van artikel 0</a></p>
</body>
</html>
