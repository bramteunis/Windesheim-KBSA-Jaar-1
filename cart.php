<?php
include __DIR__ . "/cartfuncties.php";
include __DIR__ . "/header.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

for($x=1;$x<2;$x++){
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
           WHERE 'iii' NOT IN (SELECT StockGroupID from stockitemstockgroups WHERE StockItemID = SI.StockItemID) AND SI.StockItemID = ".$x."
           GROUP BY StockItemID";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    
    $ReturnableResult = mysqli_stmt_get_result($Statement);
    $ReturnableResult = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC);
    foreach ($ReturnableResult as $row) {
        debug_to_console($row["StockItemID"]);
    }
}
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

    <?php $cart = getCart();
    $value = max($cart);
    if ($cart != null AND $value > 0) { ?>
        <div id="titleCart">
            <h1 id="titleText">Winkelmand</h1>
            <form action="index.php">
                <button id="verderWinkelenKnop">Verder winkelen</button></form>
            <?php
            function testinconsole(){
                debug_to_console("Test is geslaagd");
            }
            print('<form method="POST" action="" onsubmit="testinconsole()"><input class="ToevoegenWinkelmandbutton ToevoegenWinkelmandbutton1" type="submit" name="afrekenensubmit" value="Afrekenen"></form>');
            if(isset($_POST["afrekenensubmit"])){
                $cart = getCart();
                foreach($cart as $artikelnummer => $aantalartikel){
                    $StockItem = getStockItem($artikelnummer, $databaseConnection);
                    $nieuwevoorraad = str_replace("Voorraad: ", "",$StockItem['QuantityOnHand']) - $aantalartikel;

                    $Query2 = "UPDATE stockitemholdings SET quantityonhand=".$nieuwevoorraad." WHERE stockitemid=".$artikelnummer;
                    $Statement2 = mysqli_prepare($databaseConnection, $Query2);
                    mysqli_stmt_execute($Statement2);
                    //debug_to_console("Nieuwevooraad van artikel: ". $artikelnummer." is: ".$nieuwevoorraad34);
                }header("Location: WinkemandCreateAccount.php");
                header("Refresh:0");

            }

            ?>
        </div>
    <?php }else{

        $cart = array();
        debug_to_console("testover array legen");

    } ?>
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
            //print('<input class="ToevoegenWinkelmandbutton ToevoegenWinkelmandbutton1" type="submit" name='."aanpassensubmit".$artikelnummer.' value="Aanpassen">');
            if(isset($_POST["Test".$artikelnummer])){
                $country[$artikelnummer]=$_POST["Test".$artikelnummer];
                if($_POST["format"] == "") {
                    //print("selected aantal van ".$artikelnummer." is => " . (isset($variable))?$variable:'');
                    if(str_replace("Voorraad: ", "",$StockItem['QuantityOnHand']) >= $country[$artikelnummer]){

                        debug_to_console($country[$artikelnummer]);
                        updateProductFromCart($artikelnummer,$country[$artikelnummer]);
                        echo("<meta http-equiv='refresh' content='1'>");
                    }else{
                        updateProductFromCart($artikelnummer,str_replace("Voorraad: ", "",$StockItem['QuantityOnHand']));
                        echo("<meta http-equiv='refresh' content='1'>");

                    }
                }else{
                    //print("selected aantal ".$artikelnummer." is => " . $country[$artikelnummer]=$_POST["format"]);
                    $country[$artikelnummer]=$_POST["format"];
                    if(str_replace("Voorraad: ", "",$StockItem['QuantityOnHand']) >= $country[$artikelnummer]){

                        debug_to_console($country[$artikelnummer]);
                        updateProductFromCart($artikelnummer,$country[$artikelnummer]);
                        echo("<meta http-equiv='refresh' content='1'>");
                        debug_to_console($cart);
                    }else{
                        updateProductFromCart($artikelnummer,str_replace("Voorraad: ", "",$StockItem['QuantityOnHand']));
                        echo("<meta http-equiv='refresh' content='1'>");
                    }

                }
            }

            print('
               <div class="select-editable">
                   <form method="POST" action="">
                       <select name='."Test".$artikelnummer.' onchange="this.nextElementSibling.value=this.value,this.form.submit()">');
            if(str_replace("Voorraad: ", "",$StockItem['QuantityOnHand']) < $aantalartikel){
                $aantalartikel = str_replace("Voorraad: ", "",$StockItem['QuantityOnHand']);
                updateProductFromCart($artikelnummer,str_replace("Voorraad: ", "",$StockItem['QuantityOnHand']));
                echo("<meta http-equiv='refresh' content='1'>");
            }
            for($x =$aantalartikel-2; $x<$aantalartikel+6; $x++){
                $y = $x+ $aantalartikel;
                if($x>-1){
                    if($aantalartikel == $x){
                        $b = $x;
                        print('<option value='.$x.' selected>'.$x.'</option>');
                    }else{
                        print('<option value='.$x.' >'.$x.'</option>');
                    }
                }

            }
            print('</select>
                       <input type="text" name="format" value='.$b.'>
                   </form>
               </div>
               <style>
                   .select-editable {position:relative; background-color:white; border:solid grey 1px;  width:120px; height:18px;}
                   .select-editable select {position:absolute; top:0px; left:0px; font-size:14px; border:none; width:120px; margin:0;}
                   .select-editable input {position:absolute; top:0px; left:0px; width:100px; padding:1px; font-size:12px; border:none;}
                   .select-editable select:focus, .select-editable input:focus {outline:none;}
               </style>');






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


            print('</div><form method="POST" action="">

    <input class="ToevoegenWinkelmandbutton ToevoegenWinkelmandbutton1" type="submit" name='."submit".$artikelnummer.' value="Verwijderen">
    </form>');
            print("</div>");
            print("</div>");
            if (isset($_POST["submit".$artikelnummer])) {              // zelfafhandelend formulier
                $stockItemID = $artikelnummer;
                removeProductFromCart($stockItemID);         // maak gebruik van geïmporteerde functie uit cartfuncties.php

            }else{
                debug_to_console('verwijderen word geprobeerd maar valt onder else');
            }
        }
    }


    if($cart != null AND $totaalprijs != 0)

    {
        print("<h1 style='color:black'>Totaalprijs: €".$totaalprijs."</h1>");
        print("<h1 style='color:black'>Verzendkosten: €".$hoogsteverzending."</h1>");
        $totaal = $totaalprijs + $hoogsteverzending;
        print("<h1 style='color:black'>Totaal: €".$totaal."</h1>");
    }else
    {

        $cart = array();
        print('<h1 style = "font-size:2.5vw;position:fixed; width:30%; margin-left:37%; color:Black; margin-top:15%;">Uw winkelmand is leeg</h1>');  //Tekst winkelmand is leeg, wanneer cart =0
        print('<form style = "method="get" action="index.php"> 
           <button style="font-size:1.5vw;position:fixed; width:10%; margin-left:45%;margin-top:23%; color:Black; " class="Hovershadowbutton" type="submit">Homepagina</button></form>');  //Knop die leidt naar de homepage

    }

    ?>
</div>
</body>
</html>
