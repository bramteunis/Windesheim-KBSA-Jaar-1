<?php
include_once "Gebruikers.php";
?>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="login.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
          crossorigin="anonymous">
</head>
<form method="post" action=" ">
    <?php
    $db = new Gebruikers();
    if(isset($_POST['EmailAddress'])){
        if ($db->newUser($_POST['EmailAddress'], $_POST['psw'])){
            echo "<script>window.open('index.php','_self');</script>";
        }
        else{
            echo "<br><span><h3 style='color: black'>E-mailadress of Wachtwoord klopt niet!</h3></span>";
        }
    }
    ?>
    <div id="form_wrapper">
        <div id="form_left">
            <img src="public/productimghighres/nerdylogin.png" alt="computer icon">
        </div>
        <div id="form_right">
            <h1 class="formText">Inloggen</h1>
            <div class="input_container">
                <i id="loginI" class="fas fa-envelope"></i>
                <input placeholder="E-mail" type="text" name="EmailAddress" id="field_email" class='input_field' required>
            </div>
            <div class="input_container">
                <i id="loginI" class="fas fa-lock"></i>
                <input placeholder="Wachtwoord" type="password" name="psw" id="field_password" class='input_field'>
            </div>
            <input type="submit" value="Inloggen" id='input_submit' class='input_field'>
            <span>
                <a class="loginA" onclick="window.location.href='registreren.php'">Nog geen account? Maak er hier een aan!</a>
            </span>
        </div>
    </div>
</form>