<?php
include __DIR__ . "/cartfuncties.php";
include __DIR__ . "/header.php";



$Query = "
           SELECT SI.StockItemID, SI.StockItemName, SI.MarketingComments, TaxRate, RecommendedRetailPrice,
           ROUND(SI.TaxRate * SI.RecommendedRetailPrice / 100 + SI.RecommendedRetailPrice,2) as SellPrice,
           QuantityOnHand,
           (CASE WHEN (RecommendedRetailPrice*(1+(TaxRate/100))) > 50 THEN 0 ELSE 6.95 END) AS SendCosts,
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
        <?php if($winkelmandjevol != "leeg"){ ?>
        <div id="titleCart">
            <h1 id="titleText">Winkelmand</h1>
            <button id="verderWinkelenKnop">Verder winkelen</button>
            <button id="AfrekenenKnop">Afrekenen</button>
        </div>
        <?php } ?>
<?php
$totaalprijs = 0;
$hoogsteverzending = 0;
$cart = getCart();
foreach($cart as $artikelnummer => $aantalartikel)
{
    if($aantalartikel > 0){
    $StockItem = getStockItem($artikelnummer, $databaseConnection);
    $StockItemImage = getStockItemImage($artikelnummer, $databaseConnection);
    print("<div style='border:2px solid black;margin-top:10px;width:1848px;height:125px;'>");
    print("<div class='flex-container' style='float:left;width:1500px;height:125px;display:flex;'>");
    
    foreach ($ReturnableResult as $row) {
            if ($artikelnummer == $row["StockItemID"]) {
                
                if(str_replace(" ", "%20",strtolower($row['ImagePath'])) == "" OR str_replace(" ", "%20",strtolower($row['ImagePath'])) == null){
                      $imagepath = str_replace(" ", "%20",strtolower($row['BackupImagePath']));
                      print ("<img style='float:left;width:110px;height:110px;margin-top:5px;margin-left:5px;'src="."public/stockgroupimg/".$imagepath.">");
               }else{
                      $imagepath = str_replace(" ", "%20",strtolower($row['ImagePath']));
                      print ("<img style='float:left;width:110px;height:110px;margin-top:5px;margin-left:5px;'src="."public/stockitemimg/".$imagepath.">");
               }
               
            }
    }
    print ("<h5 style='color:black; margin-left: 50px;margin-top:15px;width:500px;height:50px'>".$StockItem['StockItemName']."</h5>");
    print("<h5 style='color: black; margin-left: 50px;margin-top:15px;float:right;'>".$StockItem['QuantityOnHand']."</h5>");
    print("</div>");
    print("<div style='float:right;width:344px;height:125px;'>");
    print('<form method="post">
    <div style="width:344px;height:62px;">
    <input type="number" name="stockItemID" value="print($artikelnummer)" hidden>
    <input type="number" name="aantalvanartikelen" value='.$cart[$artikelnummer].' id="rangeInputForm" hidden> </form>');
               
               print('
               <div class="select-editable">
                   <form method="POST" action="">
                       <select name='."Test".$artikelnummer.' onchange="this.nextElementSibling.value=this.value,this.form.submit()">');
                           for($x =$aantalartikel-2; $x<$aantalartikel+6; $x++){
                              $y = $x+ $aantalartikel;
                              if($x>-1){
                                         if($aantalartikel == $x){
                                            print('<option value='.$x.' selected>'.$x.'</option>');
                                         }else{
                                            print('<option value='.$x.' >'.$x.'</option>');
                                         }
                              }
                              
                              }
                       print('</select>
                       <input type="text" name="format" value=""/>
                   </form>
               </div>
               <style>
                   .select-editable {position:relative; background-color:white; border:solid grey 1px;  width:120px; height:18px;}
                   .select-editable select {position:absolute; top:0px; left:0px; font-size:14px; border:none; width:120px; margin:0;}
                   .select-editable input {position:absolute; top:0px; left:0px; width:100px; padding:1px; font-size:12px; border:none;}
                   .select-editable select:focus, .select-editable input:focus {outline:none;}
               </style>');

               if(isset($_POST["Test".$artikelnummer])){
                   $country[$artikelnummer]=$_POST["Test".$artikelnummer];
                   if($_POST["format"] == "") {
                       //print("selected aantal van ".$artikelnummer." is => " . $country[$artikelnummer]);
                       if(str_replace("Voorraad: ", "",$StockItem['QuantityOnHand']) >= $country[$artikelnummer]){
                                  updateProductFromCart($artikelnummer,$country[$artikelnummer]);
                                  echo("<meta http-equiv='refresh' content='1'>"); 
                       }else{
                                 debug_to_console("Aantal is boven voorraad");
                       }
                   }else{
                       //print("selected aantal ".$artikelnummer." is => " . $country[$artikelnummer]=$_POST["format"]);
                       $country[$artikelnummer]=$_POST["format"];
                       if(str_replace("Voorraad: ", "",$StockItem['QuantityOnHand']) >= $country[$artikelnummer]){
                                  updateProductFromCart($artikelnummer,$country[$artikelnummer]);
                                 echo("<meta http-equiv='refresh' content='1'>"); 
                       }else{
                                 debug_to_console("Aantal is boven voorraad");
                       }
                       
                   }
           }
    if (isset($ReturnableResult) && count($ReturnableResult) > 0) {
        foreach ($ReturnableResult as $row) {
            if ($artikelnummer == $row["StockItemID"]) {
                $totaalprijs += $cart[$artikelnummer] * sprintf('%0.2f', berekenVerkoopPrijs($row['RecommendedRetailPrice'], $row['TaxRate']));
                print("<h6 style='color:black;width:140px;height:30px;float:right;margin-top:10px;margin-right:10px;align-content:center;'> €". $cart[$artikelnummer] * sprintf('%0.2f', berekenVerkoopPrijs($row['RecommendedRetailPrice'], $row['TaxRate']))."</h6>");
                //print("<h1 style='color:black;'>".$row['MarketingComments']."</h1>");
                if(str_replace("Verzendkosten:", "",$row["SendCosts"])  > $hoogsteverzending){
                      $hoogsteverzending = str_replace("Verzendkosten:", "",$row["SendCosts"]);
                           
                }
            }
        }
    }
    
    print('</div>
    <input class="ToevoegenWinkelmandbutton ToevoegenWinkelmandbutton1" type="submit" name='."submit".$artikelnummer.' value="Verwijderen">
    </form>');
    print("</div>");
    print("</div>");
    if (isset($_POST["submit".$artikelnummer])) {              // zelfafhandelend formulier
        $stockItemID = $artikelnummer;
        removeProductFromCart($stockItemID);         // maak gebruik van geïmporteerde functie uit cartfuncties.php
    }
    }
}


if($cart != null)
{
           print("<h1 style='color:black'>Totaalprijs: €".$totaalprijs."</h1>");
           print("<h1 style='color:black'>Verzendkosten: €".$hoogsteverzending."</h1>");
           $totaal = $totaalprijs + $hoogsteverzending;
           print("<h1 style='color:black'>Totaal: €".$totaal."</h1>");
           $winkelmandjevol = "niet leeg";
}else
{
    print("<h1 style='color:black'>Uw winkelmand is leeg</h1>");
           $winkelmandjevol = "leeg";
}

?>
    </div>
</body>
</html>
