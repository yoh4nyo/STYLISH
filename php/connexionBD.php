<?php
$serveur = "mysql-stylish.alwaysdata.net";
$utilisateur = "stylish";
$motDePasse = "stylishmmayy88!"; 
$baseDeDonnees = "stylish_bdd";

try {
    $connexion = new PDO("mysql:host=$serveur;dbname=$baseDeDonnees", $utilisateur, $motDePasse);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "La connexion a échoué : " . $e->getMessage();
    exit(); 
}
?>
