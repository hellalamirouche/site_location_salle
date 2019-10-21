<?php
require('../inc/init.inc.php');

// déclaration des variables vides:
$pseudo= isset($_POST['pseudo'])? $_POST['pseudo']:'';
$mdp= isset($_POST['mdp'])? $_POST['mdp'] :'';
$nom= isset($_POST['nom'])? $_POST['nom'] : '';
$prenom= isset($_POST['prenom'])?$_POST['prenom'] : '';
$email= isset($_POST['email'])? $_POST['email'] : '';
$civilite= isset($_POST['civilite'])? $_POST['civilite'] : $_POST['civilite'] = 'm';
$msg = '';


if(isset($_POST['pseudo']) && isset($_POST['mdp']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['civilite'])){

  $pseudo=trim($_POST['pseudo']);  // les champs restent presremplies apres une validation si il existe une erreur quelques part
  $mdp=trim($_POST['mdp']);
  $nom=$_POST['nom'];
  $prenom=$_POST['prenom'];
  $email=$_POST['email'];
  $civilite=$_POST['civilite'];
  
  //verification  du mdp :
  $verification_pseudo =  preg_match('#^[a-zA-Z0-9._-]+$#',$_POST['pseudo']);

  if(!$verification_pseudo && !empty($pseudo)){

   $msg.='<div class="alert alert-danger "> Attention <br> Caractères acceptés : A à Z et 0 à 9 <br> verifierz votre Pseudo </div>';

 }
 if(iconv_strlen($pseudo)<3 || iconv_strlen($pseudo)>13 ){

   $msg.= '<div class="alert alert-danger"> Attention <br> le Pseudo doit avoir entre 3 et 13 caractères inclus  <br> verifierz votre Pseudo </div>';

 }

 if(iconv_strlen($nom)<3 || iconv_strlen($nom)>13 ){

   $msg.= '<div class="alert alert-danger"> Attention <br> le Nom doit avoir entre 3 et 13 caractères inclus  <br> verifierz votre Nom </div>';

 }

 if(iconv_strlen($prenom)<3 || iconv_strlen($prenom)>13 ){

   $msg.= '<div class="alert alert-danger"> Attention <br> le Prenom doit avoir entre 3 et 13 caractères inclus  <br> verifierz votre Prenom </div>';

 }

     // verification du mail 
 if(!filter_var( $email,FILTER_VALIDATE_EMAIL)){
   $msg .='<div class="alert alert-danger"> Attention <br> le format de l\'email est incorrect   <br> verifierz votre saisie </div>';

 }


//controle sur la disponibilité du pseudo car le champs est unique en BDD
 $verification_pseudo = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");

 $verification_pseudo -> bindParam(':pseudo',$pseudo,PDO::PARAM_STR);
 $verification_pseudo -> execute();

 if($verification_pseudo->rowCount()>0){
    // s'il ya une ligne dans $verif_pseudo alors le pseudo existe en BDD
  $msg .="<div class='alert alert-danger'> Attention <br> le pseudo  $pseudo existe déja</div>";

}

/* s'il n'ya pas d'erreur alors on lance l'enregistrement */

if(empty($msg)){

    $mdp=password_hash( $mdp,PASSWORD_DEFAULT); //pour le hashage et cryptage de mot de passe
    $enregistrement = $pdo->prepare("INSERT INTO membre (pseudo,mdp,nom ,prenom,email,civilite,date_enregistrement,statut) VALUES (:pseudo,:mdp,:nom ,:prenom,:email,:civilite,NOW() ,1)");
    $enregistrement -> bindParam(':pseudo',$pseudo,PDO::PARAM_STR);
    $enregistrement -> bindParam(':mdp',$mdp,PDO::PARAM_STR);
    $enregistrement -> bindParam(':nom',$nom,PDO::PARAM_STR);
    $enregistrement -> bindParam(':prenom',$prenom,PDO::PARAM_STR);
    $enregistrement -> bindParam(':email',$email,PDO::PARAM_STR);
    $enregistrement -> bindParam(':civilite',$civilite,PDO::PARAM_STR);
    $enregistrement -> execute(); 

    //envoi du mail de creation de compte
    require_once('../mails/mail_inscription.php');

  }
  echo $msg;
}


?>