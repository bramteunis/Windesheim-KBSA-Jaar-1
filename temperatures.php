<?php
include __DIR__ . "/cartfuncties.php";
include __DIR__ . "/database.php";

$databaseConnection = connectToDatabase();
$databaseConnection2 = connectToDatabase2();

?>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>CodePen - Carrier listing</title>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/3.2.0/css/font-awesome.min.css'><link rel="stylesheet" href="./style.css">

</head>
<body>
<!-- partial:index.partial.html -->
<script type="text/javascript" src="//use.typekit.net/uvs8amk.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

<?php
  
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
  
  $Query = "SELECT * FROM coldroomtemperatures ORDER BY ColdRoomTemperatureID DESC LIMIT 400";
  $Statement2 = mysqli_prepare($databaseConnection2, $Query);
  mysqli_stmt_execute($Statement2);

  $ReturnableResult2 = mysqli_stmt_get_result($Statement2);
  $ReturnableResult2 = mysqli_fetch_all($ReturnableResult2, MYSQLI_ASSOC);
  foreach ($ReturnableResult2 as $row) {
      $temp = $row["Temperature"];
      $temp2 = str_replace(".",",",$temp);
      $percentage1 = round(abs(((33.4 - $temp)/33.4)*100),2);
    
      if($temp < 33.4){
        print("<div><span>".$row["ValidFrom"]." / ".$row["ValidTo"]."</span><b><i>°</i>".$temp2."</b><small><i>°</i>33,4</small><strong><em class='icon icon-chevron-down'></em>".$percentage1."%</strong><a href='#'>DELETE</a></div>");
      }else{
        print("<div><span>".$row["ValidFrom"]." / ".$row["ValidTo"]."</span><b><i>°</i>".$temp2."</b><small><i>°</i>33,4</small><strong><em class='icon icon-down icon-chevron-up'></em>".$percentage1."%</strong><a href='#'>DELETE</a></div>");
      }
  }
  
?>
<!--
<div><span>Progressive</span><b><i>$</i>74</b><small><i>$</i>354</small><strong><em class="icon icon-chevron-down"></em>7%</strong><a href="#">SELECT</a></div>

<div><span>The General</span><b><i>$</i>89</b><small><i>$</i>379</small><strong><em class="icon icon-chevron-down"></em>5%</strong><a href="#">SELECT</a></div>

<div><span>Esurance</span><b><i>$</i>92</b><small><i>$</i>432</small><strong><em class="icon icon-down icon-chevron-up"></em>4%</strong><a href="#">SELECT</a></div>

-->
<!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
</body>
</html>
