<?php

//include "cartfuncties.php";
include "header.php";

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
function getCart(){
    if(isset($_SESSION['cart'])){               //controleren of winkelmandje (=cart) al bestaat
        $cart = $_SESSION['cart'];                  //zo ja:  ophalen
    } else{
        $cart = array();                            //zo nee: dan een nieuwe (nog lege) array
    }
    return $cart;                               // resulterend winkelmandje terug naar aanroeper functie
}
function removeProductFromCart($stockItemID){
    $cart = getCart();                          // eerst de huidige cart ophalen

    if(array_key_exists($stockItemID, $cart)){  //controleren of $stockItemID(=key!) al in array staat
        $cart[$stockItemID] -= 1;                   //zo ja:  aantal met 1 verhogen
    }else{
        $cart[$stockItemID] = 0;                    //zo nee: key toevoegen en aantal op 1 zetten.
    }
    
    saveCart($cart);                            // werk de "gedeelde" $_SESSION["cart"] bij met de bijgewerkte cart
    
}

function berekenVerkoopPrijs($adviesPrijs, $btw) {
		return $btw * $adviesPrijs / 100 + $adviesPrijs;
}
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
        
       if (isset($ReturnableResult) && count($ReturnableResult) > 0) {
	   foreach ($ReturnableResult as $row) {
		   if($artikelnummer == $row["StockItemID"]){
		    //debug_to_console("Prijs van: ".$row["StockItemID"]."is: "."€".sprintf(" %0.2f", berekenVerkoopPrijs($row["RecommendedRetailPrice"], $row["TaxRate"])));
		    print ("<h1 class='StockItemPriceText'>".'€'.sprintf('%0.2f', berekenVerkoopPrijs($row['RecommendedRetailPrice'], $row['TaxRate']))."</h1>");
		    //print("<h1 style='color:black;'>".$row['MarketingComments']."</h1>");
		   }
	   }
	}
        print('<form method="post">');  
        print('<input type="number" name="stockItemID" value="print($artikelnummer)" hidden>');
        print('<input class="ToevoegenWinkelmandbutton ToevoegenWinkelmandbutton1" type="submit" name="submit" value="Verwijderen uit winkelmandje">');
        print('</form>');

        if (isset($_POST["submit"])) {              // zelfafhandelend formulier
	    
            $stockItemID = $artikelnummer;
	    debug_to_console($cart[$stockItemID]);
            removeProductFromCart($stockItemID);         // maak gebruik van geïmporteerde functie uit cartfuncties.php
        }
    }
}
?>
