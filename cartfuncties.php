<?php
session_start();                                // altijd hiermee starten als je gebruik wilt maken van sessiegegevens
function OnSelectionChange(){
           debug_to_console("test is geslaagd");
    }
function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
function getCart()
{
    if(isset($_SESSION['cart']))
    {               
      //controleren of winkelmandje (=cart) al bestaat
      $cart = $_SESSION['cart'];                  //zo ja:  ophalen
    } else
    {
      $cart = array();                            //zo nee: dan een nieuwe (nog lege) array
    }
    return $cart;                               // resulterend winkelmandje terug naar aanroeper functie
}

function saveCart($cart)
{
    $_SESSION["cart"] = $cart;                  // werk de "gedeelde" $_SESSION["cart"] bij met de meegestuurde gegevens
    
}

function addProductToCart($stockItemID)
{
    $cart = getCart();                          // eerst de huidige cart ophalen
    if(array_key_exists($stockItemID, $cart))
    {  
      //controleren of $stockItemID(=key!) al in array staat
      $cart[$stockItemID] += 1;                   //zo ja:  aantal met 1 verhogen
    }else
    {
        $cart[$stockItemID] = 1;                    //zo nee: key toevoegen en aantal op 1 zetten.
    }
    saveCart($cart);                            // werk de "gedeelde" $_SESSION["cart"] bij met de bijgewerkte cart
}
function removeProductFromCart($stockItemID)
{
    $cart = getCart();                          // eerst de huidige cart ophalen
    if(array_key_exists($stockItemID, $cart))
    {  
      //controleren of $stockItemID(=key!) al in array staat
      if($cart[$stockItemID] == 1){
      		unset($cart[$stockItemID]);
      }else{
	      unset($cart[$stockItemID]);   
      }
      saveCart($cart);     
    }else
    {
        $cart[$stockItemID] = 0;                    //zo nee: key toevoegen en aantal op 1 zetten.
    }
    saveCart($cart);                            // werk de "gedeelde" $_SESSION["cart"] bij met de bijgewerkte cart
     echo "<meta http-equiv='refresh' content='0'>";
}
    //echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
function updateProductFromCart($stockItemID, $newvalue){
	$cart = getCart();  
	if(array_key_exists($stockItemID, $cart))
    {
		$cart[$stockItemID] = $newvalue;
		saveCart($cart);
	}
}
function testFunction()
{
    debug_to_console("Test");
}
function berekenVerkoopPrijs($adviesPrijs, $btw) {
		return $btw * $adviesPrijs / 100 + $adviesPrijs;
}
function promptBoxView(){
    print("<div id='promptBox'> wilt u doorgaan naar afrekenen? Y/N </div>");
    debug_to_console("view.php test");
}
?>
