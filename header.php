<!-- de inhoud van dit bestand wordt bovenaan elke pagina geplaatst -->
<?php
session_start();
include "database.php";
$databaseConnection = connectToDatabase();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>NerdyGadgets</title>

    <!-- Javascript -->
    <script src="public/js/fontawesome.js"></script>
    <script src="public/js/jquery.min.js"></script>
    <script src="public/js/bootstrap.min.js"></script>
    <script src="public/js/popper.min.js"></script>
    <script src="public/js/resizer.js"></script>

    <!-- Style sheets-->
    <link rel="stylesheet" href="public/css/style.css" type="text/css">
    <link rel="stylesheet" href="public/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="public/css/typekit.css">
    <meta charset="UTF-8">
    <meta name="theme-color" content="#999999" />
    <meta name="description" content="Nerdygadgets">
    <meta name="keywords" content="Webshop, ICT, nerdygadgets">
    <meta name="author" content="Bram Teunis, Jorg Veerman, Rick Beniers, Kristof Raams, Thomas Pham, Owen Bremer">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="Background">
    <div class="row" id="Header">
        <div class="col-2"><a href="index.php" id="LogoA" aria-label="Home">
                <div id="LogoImage"></div>
            </a></div>
        <div class="col-8" id="CategoriesBar">
            <ul id="ul-class">
                <?php
                $HeaderStockGroups = getHeaderStockGroups($databaseConnection);

                foreach ($HeaderStockGroups as $HeaderStockGroup) {
                    ?>
                    <li>
                        <a href="browse.php?category_id=<?php print $HeaderStockGroup['StockGroupID']; ?>"
                           class="HrefDecoration"><?php print $HeaderStockGroup['StockGroupName']; ?></a>
                    </li>
                    <?php
                }
                ?>
                <li>
                    <a href="categories.php" class="HrefDecoration">Alle categorieën</a>
                </li>
            </ul>
        </div>
<!-- code voor US3: zoeken. -->
        <ul id="ul-class-navigation">
            <li>
                <a href="cart.php" class="HrefDecoration"><i class="fas fa-shopping-cart"></i></a>
            </li>
            <li>
                <a href="browse.php" class="HrefDecoration"><i class="fa fa-search" aria-hidden="true"></i></i></a>
            </li>
            <li>
                <a href="account.php" class="HrefDecoration"><i class="fas fa-user-circle"></i> Inloggen</a>
            </li>
        </ul>
<!-- einde code voor US3 zoeken. -->
    </div>
    <div class="row" id="Content">
        <div class="col-12">
            <div id="SubContent">
