<?php
require_once('../inc/init.inc.php');
//------------------------------------verification connexion----------------------------------------//
$pseudo="";

$msg ='';

if(isset($_POST['pseudo']) && isset($_POST['mdp'])) {
  $pseudo=trim($_POST['pseudo']);
  $mdp=trim($_POST['mdp']);

    //pour la sécurite prepare pas le choix
  $verif_connexion=$pdo->prepare("SELECT * FROM membre WHERE pseudo=:pseudo");
  $verif_connexion->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
  $verif_connexion->execute();

  if($verif_connexion->rowCount() > 0) {

        // echo '<pre>'; print_r($infos_membre);echo '</pre>';
        $infos_membre=$verif_connexion->fetch(PDO::FETCH_ASSOC);  // pour rendre la ligne exploitable par php en array fetch : prend la ligne en cours et te le renvoie en tableau array une ligne à la fois

    // verification du MDP password_hash
        if(password_verify($mdp, $infos_membre['mdp'])) {

          $_SESSION['membre']=array(); 
          $_SESSION['membre']['id_membre']=$infos_membre['id_membre'];       
          $_SESSION['membre']['pseudo']=$infos_membre['pseudo'];       
          $_SESSION['membre']['nom']=$infos_membre['nom'];       
          $_SESSION['membre']['prenom']=$infos_membre['prenom'];       
          $_SESSION['membre']['civilite']=$infos_membre['civilite'];       
          $_SESSION['membre']['email']=$infos_membre['email'];             
          $_SESSION['membre']['statut']=$infos_membre['statut'];  

    // si la connexion est ok on redirigie vers profil
          

        } else {
          $msg .='<div class="alert alert-danger mt-2"> Attention,<br>Erreur sur le pseudo et/ou le mot de passe<br> Veuillez recommencez</div>';
        }


      } else {
        $msg .='<div class="alert alert-danger mt-2"> Attention,<br>Erreur sur le pseudo et/ou le mot de passe<br> Veuillez recommencez</div>';
      }

    }
    echo $msg;
    ?>