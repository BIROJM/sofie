<?php
$mysqli = new mysqli("127.0.0.1", "root", "root", 8889, "db_sofiev4");

if ($mysqli->connect_error) {
    die("Erreur MySQL : " . $mysqli->connect_error);
}

echo "Connexion MySQL OK !";