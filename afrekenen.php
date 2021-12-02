<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Afreken</title>
    <link rel="stylesheet" href="public/css/gegevens.css">
</head>
<body>
    <div class="mainDiv">
    <h2>Persoonlijke gegevens</h2>
    <form method="post">
        <div class="wrapper1">
            <label class="font">Voornaam</label>
            <input class="no-outline" type="text" name="voornaam" required><br><br>
            <label class="font">Postcode</label>
            <input class="no-outline" type="text" name="postcode" required><br><br>
            <label class="font">Straatnaam</label>
            <input class="no-outline" type="text" name="straatnaam" required><br><br>
            <label class="font">E-mail</label>
            <input class="no-outline" type="email" name="email" required><br><br>
            <label class="font">Telefoonnummer</label>
            <input class="no-outline" type="tel" name="telefoonnummer"><br><br>
        </div>
        <div class="wrapper2">
            <label class="font">Tussenvoegsel</label>
            <input class="no-outline" type="text" name="tussenvoegsel"><br><br>
            <label class="font">Huisnummer</label>
            <input class="no-outline" type="number" name="huisnummer" required><br><br>
            <label class="font">Plaats</label>
            <input class="no-outline" type="text" name="plaats" required>

        </div>
        <div class="wrapper3">
            <label class="font">Achternaam</label>
            <input class="no-outline" type="text" name="achternaam" required>
        </div>
            <span class="underline"></span>
    </form>
    </div>
</body>
</html>
