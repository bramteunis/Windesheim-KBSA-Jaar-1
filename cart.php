<?php
include __DIR__ . "/cartfuncties.php";
include __DIR__ . "/header.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function numberOfDecimals($value)
    {
        if ((int)$value == $value)
        {
            return 0;
        }
        else if (! is_numeric($value))
        {
            // throw new Exception('numberOfDecimals: ' . $value . ' is not a number!');
            return false;
        }

        return strlen($value) - strrpos($value, '.') - 1;
    }

function Get_information($databaseConnection,$artikelnummer){
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
           WHERE 'iii' NOT IN (SELECT StockGroupID from stockitemstockgroups WHERE StockItemID = SI.StockItemID) AND SI.StockItemID = ".$artikelnummer."
           GROUP BY StockItemID";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    
    $ReturnableResult = mysqli_stmt_get_result($Statement);
    $ReturnableResult = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC);
    foreach ($ReturnableResult as $row) {
        debug_to_console($row["StockItemID"]);
    }
    return $ReturnableResult;
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
            
            <form action="index.php">
                <button id="verderWinkelenKnop" class="ToevoegenWinkelmandbutton ToevoegenWinkelmandbutton1" style="padding: 8px;margin-top: 1px;">Verder winkelen</button></form>
            <h1 id="titleText">Winkelmand</h1>
            <?php
            function testinconsole(){
                debug_to_console("Test is geslaagd");
            }
            print('<form method="POST" action="" onsubmit="testinconsole()"><input class="ToevoegenWinkelmandbutton ToevoegenWinkelmandbutton1" type="submit" name="afrekenensubmit" value="Afrekenen" style="margin-top: -60px;"></form>');
            if(isset($_POST["afrekenensubmit"])){
                $cart = getCart();
                foreach($cart as $artikelnummer => $aantalartikel){
                    $StockItem = getStockItem($artikelnummer, $databaseConnection);
                    $nieuwevoorraad = str_replace("Voorraad: ", "",$StockItem['QuantityOnHand']) - $aantalartikel;

                    $Query2 = "UPDATE stockitemholdings SET quantityonhand=".$nieuwevoorraad." WHERE stockitemid=".$artikelnummer;
                    $Statement2 = mysqli_prepare($databaseConnection, $Query2);
                    mysqli_stmt_execute($Statement2);
                    //debug_to_console("Nieuwevooraad van artikel: ". $artikelnummer." is: ".$nieuwevoorraad34);
                }print('<meta http-equiv="refresh" content="0; url=WinkemandCreateAccount.php" />');
                //header("Refresh:0");

            }

            ?>
        </div>
    <?php }else{

        $cart = array();
        debug_to_console("testover array legen");

    } ?>
    <?php

    $totaalprijs = 0;
    $hoogsteverzending = 7;
    $cart = getCart();
    foreach($cart as $artikelnummer => $aantalartikel)
    {
        if($aantalartikel > 0){
            $StockItem = getStockItem($artikelnummer, $databaseConnection);
            $StockItemImage = getStockItemImage($artikelnummer, $databaseConnection);

            print("<div id='itemcartcards' class='".$artikelnummer."'>");
            print("<div id='".$artikelnummer."' >");
            print("<div class='flex-container' style='float:left;width:592px;height:125px;display:flex;'>");

            $ReturnableResult = Get_information($databaseConnection,$artikelnummer);
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
            print ("<h5 style='color:black; margin-left: 3%;margin-top:15px;width:500px;height:50px'>".$StockItem['StockItemName']."</h5>");
            print("<h5 style='color: black; margin-left: 3%;margin-top:15px;float:right;'>".$StockItem['QuantityOnHand']."</h5>");
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
                   .select-editable input {position:absolute; top:2.5px; left:1px; width:95px; height:99%; padding:1px; font-size:12px; border:none;}
                   .select-editable select:focus, .select-editable input:focus {outline:none;}
               </style>');






            if (isset($ReturnableResult) && count($ReturnableResult) > 0) {
                foreach ($ReturnableResult as $row) {
                    if ($artikelnummer == $row["StockItemID"]) {
                        $totaalprijs += $cart[$artikelnummer] * sprintf('%0.2f', berekenVerkoopPrijs($row['RecommendedRetailPrice'], $row['TaxRate']));
                        $prijs = $cart[$artikelnummer] * sprintf('%0.2f', berekenVerkoopPrijs($row['RecommendedRetailPrice'], $row['TaxRate']));
                        if(numberOfDecimals($prijs) == 1){$prijs = $prijs."0";}
                        $prijs = str_replace(".",",",$prijs);
                        print("<h6 style='color:black;width:140px;height:30px;float:right;margin-right:1%;align-content:center;font-size: 140%;'> € ".$prijs."</h6>");
                        //print("<h1 style='color:black;'>".$row['MarketingComments']."</h1>");
                        if(str_replace("Verzendkosten:", "",$row["SendCosts"])  < $hoogsteverzending){
                            $hoogsteverzending = str_replace("Verzendkosten:", "",$row["SendCosts"]);

                        }
                    }
                }
            }


            print('</div><form method="POST" action="">

    <input class="ToevoegenWinkelmandbutton ToevoegenWinkelmandbutton1" type="submit" name='."submit".$artikelnummer.' value="Verwijderen">
    </form>');
            print("</div>");
            
            
            if (isset($_POST["submit".$artikelnummer])) {              // zelfafhandelend formulier
                $stockItemID = $artikelnummer;
                print("
                <style>
                .".$artikelnummer." {
                border: yellow;
                margin-top:10px;
                width:100%;
                height: 20%;
                border-style: double;
                border-radius: 10px;
                padding: 7px; 
                box-shadow: 5px 10px 18px #888888; 
                margin-bottom: 1%; 
                background: linear-gradient(90deg, rgba(255,255,255,1) 0%, rgba(64,64,159,0.1) 62%, #9fd2ff 100%);
                animation-name: example;
                animation-duration: 4s;
                }
                
                @keyframes example {
                  from {padding: 7px;}
                  to {padding: 187px;}
                }
                </style>
                ");
                //removeProductFromCart($stockItemID);         // maak gebruik van geïmporteerde functie uit cartfuncties.php

            }else{
                debug_to_console('verwijderen word geprobeerd maar valt onder else');
            }
            print("</div>");
            print("</div>");
        }
    }
    

    


    if($cart != null AND $totaalprijs != 0)

    {
        if(numberOfDecimals($totaalprijs) == 1){$totaalprijs = $totaalprijs."0";}
        print("<div id='Totaalprijs'>");
        
        print("Totaalprijs: ");
        
        
        print("<div id='Totaalprijs-prijs'>");
        $totaalprijs2 = str_replace(".",",",$totaalprijs);
        print("€  ".$totaalprijs2);
        print("</div>");
        
        print("<br>");
        if($totaalprijs > 50){$hoogsteverzending=0;}
        print("Verzendkosten: ");
        
        
        print("<div id='Totaalprijs-prijs'>");
        if($hoogsteverzending  != 0){
            if($totaalprijs<100){
                $hoogsteverzending2 = str_replace(".",",",$hoogsteverzending);
                print("€    ".$hoogsteverzending2);
            }elseif($totaalprijs>999){
                $hoogsteverzending2 = str_replace(".",",",$hoogsteverzending);
                print("€      ".$hoogsteverzending2);
            }else{
                $hoogsteverzending2 = str_replace(".",",",$hoogsteverzending);
                print("€     ".$hoogsteverzending2);
            }
        }else{
            if($totaalprijs<99){
                $hoogsteverzending2 = str_replace(".",",",$hoogsteverzending);
                print("€         ".$hoogsteverzending2);
            }elseif($totaalprijs>999){
                $hoogsteverzending2 = str_replace(".",",",$hoogsteverzending);
                print("€             ".$hoogsteverzending2);
            }else{
                $hoogsteverzending2 = str_replace(".",",",$hoogsteverzending);
                print("€           ".$hoogsteverzending2);
            }
        }
            
        print("</div>");
        
        print("<br>");
        $totaal = $totaalprijs + $hoogsteverzending;
        print("Totaal: ");
        
        
        
        print("<div id='Totaalprijs-prijs' style='font-weight: bold;'>");
        if(numberOfDecimals($totaal) == 1){$totaal = $totaal."0";}
        $totaal = str_replace(".",",",$totaal);
        print("€ ".$totaal);
        print("</div>");
        print("</div>");
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
