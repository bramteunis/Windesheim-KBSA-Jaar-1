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
    //change the "version" of a stylesheet when it is not loading.
    <link rel="stylesheet" href="public/css/style.css?version=3" type="text/css">
    <link rel="stylesheet" href="public/css/bootstrap.min.css?version=3" type="text/css">
    <link rel="stylesheet" href="public/css/typekit.css?version=3">
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
                <a href="cart.php" class="HrefDecoration"><i class="fas fa-shopping-cart"></i> Winkelwagen</a>
                <!--
                <a href="cart.php" class="HrefDecoration">
                <svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="shopping-cart" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="svg-inline--fa fa-shopping-cart fa-w-18 fa-spin fa-lg" width="30px" style="color:black"><path fill="currentColor" d="M551.991 64H144.28l-8.726-44.608C133.35 8.128 123.478 0 112 0H12C5.373 0 0 5.373 0 12v24c0 6.627 5.373 12 12 12h80.24l69.594 355.701C150.796 415.201 144 430.802 144 448c0 35.346 28.654 64 64 64s64-28.654 64-64a63.681 63.681 0 0 0-8.583-32h145.167a63.681 63.681 0 0 0-8.583 32c0 35.346 28.654 64 64 64 35.346 0 64-28.654 64-64 0-18.136-7.556-34.496-19.676-46.142l1.035-4.757c3.254-14.96-8.142-29.101-23.452-29.101H203.76l-9.39-48h312.405c11.29 0 21.054-7.869 23.452-18.902l45.216-208C578.695 78.139 567.299 64 551.991 64zM208 472c-13.234 0-24-10.766-24-24s10.766-24 24-24 24 10.766 24 24-10.766 24-24 24zm256 0c-13.234 0-24-10.766-24-24s10.766-24 24-24 24 10.766 24 24-10.766 24-24 24zm23.438-200H184.98l-31.31-160h368.548l-34.78 160z" class=""></path></svg>
                <i class="fas "></i> Winkelwagen</a>
                -->
            </li>
            <li>
                <a href="browse.php" class="HrefDecoration"><i class="fas fa-search search" style="color: black"></i> Zoeken</a>
            </li>
        </ul>
<!-- einde code voor US3 zoeken. -->
    </div>
    <div class="row" id="Content">
        <div class="col-12">
            <div id="SubContent">
