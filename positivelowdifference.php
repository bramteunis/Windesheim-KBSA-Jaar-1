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
  <title>Temperaturen</title>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/3.2.0/css/font-awesome.min.css'><link rel="stylesheet" href="./style.css">

</head>
<body>
<!-- partial:index.partial.html -->
<script type="text/javascript" src="//use.typekit.net/uvs8amk.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

  
<a href='temperatures.php' style='float:none'>Nieuwste</a>
<a href='oudstetemperatuur.php' style='float:none'>Oudste</a>
<a href='highdifference.php' style='float:none'>Hoogste afwijking</a>
<a href='lowdifference.php' style='float:none;background:#03625a;'>Kleinste afwijking</a>
<br>
<a href='positivelowdifference.php' style='float:none;margin-top:10px;background:#03625a;'>Positieve afwijking</a>
<a href='negativelowdifference.php' style='float:none;margin-top:10px;'>Negatieve afwijking</a>
<?php
  
   
  
  $Query = "SELECT * FROM coldroomtemperatures WHERE Temperature < 33.4 ORDER BY (Temperature - 33.4) DESC";
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
