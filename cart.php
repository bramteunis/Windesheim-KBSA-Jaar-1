<?php
include __DIR__ . "/cartfuncties.php";
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
    print("<div style='border:2px solid black;margin-top:10px;width:1848px;height:125px;'>");
    print("<div class='flex-container' style='float:left;width:1500px;height:125px;display:flex;'>");
    print ("<img style='float:left;width:110px;height:110px;margin-top:5px;margin-left:5px;'src="."public/stockitemimg/".str_replace(" ", "%20",strtolower($StockItemImage[0]['ImagePath'])).">");
    print ("<h5 style='color:black; margin-left: 50px;margin-top:15px;width:500px;height:50px'>".$StockItem['StockItemName']."</h5>");
    print("<h5 style='color: black; margin-left: 50px;margin-top:15px;float:right;'>voorraad beschikbaarheid</h5>");
    print("</div>");
    print("<div style='float:right;width:344px;height:125px;'>");
    print('<form method="post">
    <div style="width:344px;height:62px;">
    <input type="number" name="stockItemID" value="print($artikelnummer)" hidden>
    <input type="number" value="1" id="rangeInputForm">
    <h6 style="color:black;width:140px;height:30px;float:right;margin-top:10px;margin-right:10px;align-content:center;">prijs_placeholder</h6>
    </div>
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
testFunction();
?>
    </div>
</body>
</html>
