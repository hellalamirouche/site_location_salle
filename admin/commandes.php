<?php
require_once('../inc/init.inc.php');
require_once('../inc/header.inc.php');
require_once('../inc/nav.inc.php');
$detailscommandes = $pdo->prepare("SELECT s.id_salle,s.titre,s.description,s.photo,s.pays,s.ville,s.adresse,s.cp,s.capacite,s.categorie  FROM detail_commande d,salle s WHERE d.id_salle = s.id_salle  AND d.id_commande =:id_commande");
$detailscommandes->bindParam(':id_commande',$_GET['id_commande'],PDO::PARAM_INT);
$detailscommandes->execute();
echo '<h2 class="text-center"> Vos reservations</h2>';
echo'<table class="table table-bordered">
      <thead class="thead-dark">
         <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Description</th>
            <th>Photo</th>
            <th>Pays</th>
            <th>Ville</th>
            <th>Adresse</th>
            <th>Code Postal</th>
            <th>Capacite</th>
            <th>Categorie</th>
         </tr>';
      echo'</thead>';
while($detailcommande = $detailscommandes->fetch(PDO::FETCH_ASSOC))
{       echo '<tr>';
    foreach($detailcommande as $key1 => $value1)
    {  
        if($key1 == 'photo')
        {
            $value1 ="<img src='http://sallea.000webhostapp.com/img/$value1' width='50' height='50' alt='image-produit' />";
            echo'<td>'.$value1.'</td>'; 
        }else{
            echo '<td>'.$value1.'</td>';
        }  
    }
    echo'</tr>';
}
echo '</table>';
$pseudo = $pdo->prepare("SELECT DISTINCT m.id_membre,m.pseudo,m.nom,m.prenom,m.email,m.civilite FROM detail_commande d,membre m WHERE d.id_membre= m.id_membre AND d.id_commande =:id_commande");
$pseudo->bindParam(':id_commande',$_GET['id_commande'],PDO::PARAM_INT);
$pseudo->execute();
   echo'<table class="table table-bordered">
         <thead class="thead-dark">
            <tr>
               <th>Id membre</th>
               <th>Pseudo</th>
               <th>Nom</th>
               <th>Prenom</th>
               <th>Email</th>
               <th>Civilite</th>
            </tr>';
         echo'</thead>';
   while($detailpseudo = $pseudo->fetch(PDO::FETCH_ASSOC))
   {       echo '<tr>';
      foreach($detailpseudo as $key1 => $value1)
      { 
         if($key1 == 'civilite')
         {
               if($value1 == 'm')
               {
                  $value1 = 'Homme';
                  echo'<td>'.$value1.'</td>';
               }
               else{
                  $value1 = 'Femme';
               } 
               
         }else{ 
               echo '<td>'.$value1.'</td>';
         }   
      }
      echo'</tr>';   
   }
   echo '</table>';
//inclusion des éléments:
require_once('../inc/modal_mentionslegales.inc.php');
require_once('../inc/modal_conditionsdevente.inc.php');
require_once('../inc/footer.inc.php');
?>
