<?php
include "cartfuncties.php";
include __DIR__ . "/header.php";
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Winkelwagen</title>
    <link rel="stylesheet" href="public/css/style.css" type="text/css">
</head>
<body>
    <div id="cartBackground">
        <!-- <p><a href='view.php?id=0'>Naar artikelpagina van artikel 0</a></p> -->
        <div id="titleCart">
            <h1 id="titleText">Winkelmand</h1>
            <button id="verderWinkelenKnop">Verder winkelen</button>
            <button id="AfrekenenKnop">Afrekenen</button>
        </div>
<?php
$cart = getCart();
foreach($cart as $artikelnummer => $aantalartikel){

    $StockItem = getStockItem($artikelnummer, $databaseConnection);
    $StockItemImage = getStockItemImage($artikelnummer, $databaseConnection);
    print("<div class='productCart'style='border:2px solid black;margin-top:115px;width:1848px;height: 250px;'>");
    print("<div style='border:2px solid red;float:left;width:1000px;height:250px;'>");
    print ("<img style='float: left;width: 300px;height:300px;'src="."public/stockitemimg/".str_replace(" ", "%20",strtolower($StockItemImage[0]['ImagePath'])).">");
    print ("<h1 style='color:black; margin-left: 15px;height: max-content;'>".$StockItem['StockItemName']."</h1>");
    print("<h1 style='color: black; margin-left: 150px float:right;'>voorraad beschikbaarheid</h1>");
    print("</div>");
    print("<div style='border:2px solid red;float:right;margin-left: 800px;width: 800px;height: max-content;   '>");
    print('<form method="post">
    <input type="number" name="stockItemID" value="print($artikelnummer)" hidden>
    <input class="ToevoegenWinkelmandbutton ToevoegenWinkelmandbutton1" type="submit" name="submit" value="Verwijderen uit winkelmandje">
    </form>');
    print("</div>");
    print("</div>");

    if (isset($_POST["submit"])) {              // zelfafhandelend formulier
        $stockItemID = $artikelnummer;
        removeProductFromCart($stockItemID);         // maak gebruik van geïmporteerde functie uit cartfuncties.php
    }
}
//if cart array is NOT empty print its content in the page
if($cart != null)
{
    print_r($cart);
}else
{
    debug_to_console($cart);
}

?>
    </div>
</body>
</html>
