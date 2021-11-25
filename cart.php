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
function berekenVerkoopPrijs($adviesPrijs, $btw) {
    return $btw * $adviesPrijs / 100 + $adviesPrijs;
}
function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}    
$cart = getCart();
foreach($cart as $artikelnummer => $aantalartikel){
    if($aantalartikel > 0){
        debug_to_console(" == ".$artikelnummer);
        $StockItem = getStockItem($artikelnummer, $databaseConnection);
        $StockItemImage = getStockItemImage($artikelnummer, $databaseConnection);
        print ("<h1 style='color:black;'>".$StockItem['StockItemName']."</h1>");
        print ("<img src="."public/stockitemimg/".str_replace(" ", "%20",strtolower($StockItemImage[0]['ImagePath'])).">");
        
        //print ("<h1 class='StockItemPriceText'>".'€'.sprintf('%0.2f', berekenVerkoopPrijs($row['RecommendedRetailPrice'], $row['TaxRate']))."</h1>");
        //print($_SESSION['prijs']);
        
        
        //print ("<h1 class='StockItemPriceText'>".'€'.sprintf('%0.2f', berekenVerkoopPrijs($row['RecommendedRetailPrice'], $row['TaxRate']))."</h1>");
        
        $Query = "
                   SELECT SI.StockItemID, SI.StockItemName, SI.MarketingComments, TaxRate, RecommendedRetailPrice,
                   ROUND(SI.TaxRate * SI.RecommendedRetailPrice / 100 + SI.RecommendedRetailPrice,2) as SellPrice,
                   QuantityOnHand,
                   (SELECT ImagePath FROM stockitemimages WHERE StockItemID = SI.StockItemID LIMIT 1) as ImagePath,
                   (SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = SI.StockItemID LIMIT 1) as BackupImagePath
                   FROM stockitems SI
                   JOIN stockitemholdings SIH USING(stockitemid)
                   JOIN stockitemstockgroups USING(StockItemID)
                   JOIN stockgroups ON stockitemstockgroups.StockGroupID = stockgroups.StockGroupID
                   WHERE 'iii' NOT IN (SELECT StockGroupID from stockitemstockgroups WHERE StockItemID = SI.StockItemID)
                   GROUP BY StockItemID";

            $Statement = mysqli_prepare($databaseConnection, $Query);
            mysqli_stmt_execute($Statement);
            $ReturnableResult = mysqli_stmt_get_result($Statement);
            $ReturnableResult = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC);


        if (isset($ReturnableResult) && count($ReturnableResult) > 0) {
            foreach ($ReturnableResult as $row) {
                debug_to_console($row["StockItemID"]." == ".$artikelnummer);
                if($row["StockItemID"] == $artikelnummer){
                    //debug_to_console("Prijs van: ".$row["StockItemID"]."is: "."€".sprintf(" %0.2f", berekenVerkoopPrijs($row["RecommendedRetailPrice"], $row["TaxRate"])));
                    print("<h1 class='StockItemPriceText'>".'€'.sprintf('%0.2f', berekenVerkoopPrijs($row['RecommendedRetailPrice'], $row['TaxRate']))."</h1>");
                }else{debug_to_console("fout2");}
           }
        }else{
        debug_to_console("fout1");
        }

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
