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

function debug_to_console($data) 
{
    $output = $data;
    if (is_array($output))
    {
        $output = implode(',', $output);
    }

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
function saveCart($cart){
    $_SESSION["cart"] = $cart;                  // werk de "gedeelde" $_SESSION["cart"] bij met de meegestuurde gegevens
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
    debug_to_console($cart[$stockItemID]);
    if(array_key_exists($stockItemID, $cart)){  //controleren of $stockItemID(=key!) al in array staat
        $cart[$stockItemID] -= 2;                   //zo ja:  aantal met 1 verhogen
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

if($aantalartikel > 0)
{
    if (isset($ReturnableResult) && count($ReturnableResult) > 0) 
    {
	      foreach ($ReturnableResult as $row) 
        {
		       if($artikelnummer == $row["StockItemID"]){
		       print ("<h1 class='StockItemPriceText'>".'€'.sprintf('%0.2f', berekenVerkoopPrijs($row['RecommendedRetailPrice'], $row['TaxRate']))."</h1>");
		       //print("<h1 style='color:black;'>".$row['MarketingComments']."</h1>");
		      }
	   }
}
?>

