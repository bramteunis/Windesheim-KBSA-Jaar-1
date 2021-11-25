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
    print("<div class='flex-container' style='border:2px solid red;float:left;width:1500px;height:250px;display:flex'>");
    print ("<img style='float: left;width:200px;height:200px;'src="."public/stockitemimg/".str_replace(" ", "%20",strtolower($StockItemImage[0]['ImagePath'])).">");
    print ("<h5 style='color:black; margin-left: 50px;margin-top:15px;width:500px;height:50px'>".$StockItem['StockItemName']."</h5>");
    print("<h5 style='color: black; margin-left: 50px;margin-top:15px;float:right;'>voorraad beschikbaarheid</h5>");
    print("</div>");
    print("<div style='border:2px solid red;float:right;width:344px;height:250px;'>");
    print('<form method="post">
    <input type="number" name="stockItemID" value="print($artikelnummer)" hidden>
    <input class="ToevoegenWinkelmandbutton ToevoegenWinkelmandbutton1" type="submit" name="submit" value="Verwijderen">
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
