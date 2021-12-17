<!-- dit is het bestand dat wordt geladen zodra je naar de website gaat en test je dit even? -->

<?php
include __DIR__ . "/cartfuncties.php";
include __DIR__ . "/header.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$bestverkocht = "SELECT StockItemID, SUM(Quantity) AS TotalQuantity FROM orderlines GROUP BY StockItemID ORDER BY SUM(Quantity) DESC LIMIT 1";
$Statement = mysqli_prepare($databaseConnection, $bestverkocht);
mysqli_stmt_execute($Statement);
$ReturnableResult = mysqli_stmt_get_result($Statement);
$ReturnableResult = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC);
foreach ($ReturnableResult as $row) {
    $BST = ($row["StockItemID"]);
}


?>

<div class="underHeadDiv" style="
    margin-top: 50px;">
    <i class="fas fa-check" style="color: green"></i>
    <p class="underHeadText">&nbspVoor 23.59 uur besteld, morgen gratis bezorgd</p>
    <i class="fas fa-check" style="color: green"></i>
    <p class="underHeadText"><strong>&nbspGratis</strong> verzending vanaf 20,-</p>
    <i class="fas fa-check" style="color: green"></i>
    <p class="underHeadText"><strong>&nbspGratis</strong> retourneren</p>
</div>
<br><br>
<div class="IndexStyle">
    <div class="col-11">
        <div class="TextPrice">
            <a href="view.php?id=<?php print $BST ?>" aria-label="product 1">

                <div class="TextMain">

                    FURRY ANIMAL SOCKS (PINK) S

                </div>
                <ul id="ul-class-price">
                    <li class="HomePagePrice">€69.69</li>
                </ul>
        </div>
            </a>
        <div class="HomePageStockItemPicture"></div>
    </div>
</div>
<hr>
<div class="IndexStyle">
    <div class="col-11">
        <div class="TextPrice">
            <a href="view.php?id=23" aria-label="Product 2">
                <div class="TextMain">
                    DBA JOKE MUG<br>- IT DEPENDS (BLACK)
                </div>
                <ul id="ul-class-price">
                    <li class="HomePagePrice">21.50</li>
                </ul>
        </div>
        </a>
        <div class="HomePageStockItemPicture2"></div>
    </div>
</div>
<hr>
<?php
include __DIR__ . "/footer.php";
?>

