<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class Gebruikers
{

    public $conf;
    public $stmt;

    public $db;
    public $sql;

    //verbinding met de database
    public function DBConnect()
    {
        try {
            // De connectie met de database openen
            $dsn = "mysql:host=localhost;dbname=nerdygadgets;";
            $pdo = new PDO($dsn, "U4i5Q;au)q{v=94>a>+A", "kbs");
            return $pdo;
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }


    //functie om in te loggen
    public function newUser($EmailAddress, $psw)
    {
        // Controleren of de gegeven gebruikersgegevens overeen komen met wat er in de database staat geregistreerd
        $db = $this->DBConnect();
        $sql = "SELECT * FROM people WHERE `EmailAddress` = :Gebruiker_Email";
        $obj = $db->prepare($sql);
        $obj->bindParam(":EmailAddress", $EmailAddress);
        $obj->execute();
        $result = $obj->fetch(PDO::FETCH_ASSOC);
        if (!empty($result) && password_verify($psw, $result['HashedPassword']) == true) {
            $_SESSION['FullName'] = $result['FullName'];
            $_SESSION['PreferredName'] = $result['PreferredName'];
            $_SESSION['EmailAddress'] = $result['EmailAddress'];
            $_SESSION['PhoneNumber'] = $result['PhoneNumber'];

            return true;
        } else {
            return false;
        }
    }







}
