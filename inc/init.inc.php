<?php
// connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=sallea','root','',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'));
// Ouverture de la session 
session_start();
//declaration d'une variable permettant d'afficher des messages pour l'utilisateur.
echo $msg ='';	
//appel de notre fichier contenant toutes les fonctions du site.
require_once("functions.inc.php");
//declaration des constantes
//constantes url representant le chemin absolu jusqu'a la racine de notre projet.
define('URL','http://localhost/sallea/');
//constante RACINE_SERVER representant le chemein depuis la racine du serveur .
define('RACINE_SERVER', $_SERVER['DOCUMENT_ROOT'] );
//site Key et secret key recapcha
define('SITEKEY','6Ldw75QUAAAAAJ1gvLPFxlO5iKltTbjZRKAZ93yS');
define('SECRETKEY','6Ldw75QUAAAAAEMBlRJfdnPytwPeGqvmrhr8tZE8');
$lien_image='img/';