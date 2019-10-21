<?php
require_once ('../inc/init.inc.php');
if (!isAdmin()) {
  header("location:" . URL . '../index.php');
  exit();
}

$resultat['note']='';
// suppression des avis par l'administrateur :
//avec cette action le bouton  supprime l element séléctionné :
if (isset($_GET['action']) && $_GET['action'] == 'suppression' && !empty($_GET['id_avis'])) {
  $suppression = $pdo->prepare("DELETE FROM avis WHERE id_avis = :id_avis");
  $suppression->bindParam(":id_avis", $_GET['id_avis'], PDO::PARAM_STR);
  $suppression->execute();
}
//modification du commentaire par l'administrateur :
if (isset($_GET['action']) && $_GET['action'] == 'modification' || isset($_GET['moderation'])) {
  $modif_avis = $pdo->prepare("SELECT note,commentaire,id_avis,active FROM avis WHERE id_avis =:id_avis");
  $modif_avis->bindParam(':id_avis', $_GET['id_avis'], PDO::PARAM_STR);
  $modif_avis->execute();
  $resultat = $modif_avis->fetch(PDO::FETCH_ASSOC);
  $id_avis = trim($resultat['id_avis']);
  $commentaire = htmlentities($resultat['commentaire']);
  $note = trim($resultat['note']);
  $active = trim($resultat['active']);
    //modification du commentaire par l'administrateur :
    // j'ai trouver un probleme pour récuperer l id_avis mais j'ai trouvé la solution en rajoutant un imput type hidden dans le formulaire  avec comme name id_avis  .

  
}
//  echo'<pre>' ;print_r($resultat); echo '</pre>';

?>

<?php
require_once ('../inc/header.inc.php');
require_once ('../inc/nav.inc.php');
$msg = "";
if (isAdmin()) {
    //code de pagination
  $limite = 4;
  $page = (!empty($_GET['page']) ? $_GET['page'] : 1);
  $debut = ($page - 1) * $limite;
  $recup_avis = $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM avis ORDER BY id_avis DESC LIMIT :limite OFFSET :debut");
  $recup_avis->bindParam(':limite', $limite, PDO::PARAM_INT);
  $recup_avis->bindParam(':debut', $debut, PDO::PARAM_INT);
  $recup_avis->execute();
  $resultfound = $pdo->query('SELECT found_rows()');
  $nombretotal = $resultfound->fetchColumn();
    //creation de la premiere ligne_avis du tableau
  echo $msg;
  echo '<div class="container">';
  echo '<table class=" table table-bordered mt-4  mx-auto bg-white ">';
  echo '<thead class="thead-dark">';
  echo '<tr>
  <th>Id_commentaire</th>
  <th>id_membre</th>
  <th>Id_salle</th>
  <th>Commentaire</th>
  <th>Note</th>
  <th>Date d\'enregistrement</th>
  <th>active</th>
  <th>modifier</th>
  <th>suppimer</th>
  </tr>';
  echo '</thead>';
  
    //affichage des commentaires dans un tableau html avec l'aide de la boucle while et un FETCH_ASSOC :
  while ($ligne_avis = $recup_avis->fetch(PDO::FETCH_ASSOC)) {
        // gérer le format de la date en fr:
    $date_commentaire = new DateTime($ligne_avis['date_enregistrement']);
    $date_fr = $date_commentaire->format("d/m/Y");
    $heure_fr = $date_commentaire->format("H:i:s");
    echo '<tr class="text-center">';
    foreach ($ligne_avis AS $indice => $valeur) {
            if ($indice == 'commentaire') { // si l'indice est commentaire tu la découpe car elle est longue et tu m'affiche à la fois un nombre égale de caratactere avec la fonction substr()
            echo '<td>' . substr($valeur, 0, 20) . ' <a href ="">...Lire </a> </td>';
          } elseif ($indice == 'date_enregistrement') {
            echo '<td>' . $date_fr . ' à ' . $heure_fr . ' </td>';
          } elseif ($indice == "active") {
            if ($valeur == 2) {
              $valeur = '<td><i class="far fa-thumbs-up"></i></td>';
              echo $valeur;
            } elseif ($valeur == 1) {
              $valeur = '<td><img src="http://sallea.000webhostapp.com/img/no.png" alt="pousse-bas"/></td>';
              echo $valeur;
            } else {
              $valeur = '<td><i class="fas fa-pause-circle"></i></td>';
              echo $valeur;
            }
          } else {
            echo '<td>' . $valeur . '</td>';
          }
        }
        // on a crééer deux bouton dans cette boucle avec les icones i et avec des methodes GET  on va cibler l element qu on veut soit supprimer ou éditer selon son id .voir juste apres:
        // on a mis un petit onclick(confirm())  javascript pour dire si vous etes sur de supprimer l'élément car supprimer est irrévérsible . regarde en haut pour voir la fonction Get pour supprimer (@suppression produits)
        
        echo '<td><a href="?action=modification&page=' . $_GET["page"] . '&id_avis=' . $ligne_avis['id_avis'] . '" class="btn btn-success text-white"  > <i class="modif-avis fas fa-edit"></i></a>  </td>';
        echo '<td> <a href="?action=suppression&page=' . $_GET["page"] . '&id_avis=' . $ligne_avis['id_avis'] . '" class="btn btn-danger text-white" onclick="(confirm(\'Etes-vous sûr ? \'));"> <i class="fas fa-trash-alt"></i></a> </td>';
        /* on peut le faire à la main
        echo '<td>'. $article['id_article'].'</td>'; et on fait ca evec tout les indices et on va faire pareil que le foreitch avec l'ordre qu on veut */
        echo '</tr>';
      }
      echo '</table>';
      echo '</div>';
    }
//pagination partie 2
// Partie "Liens"
    /* On calcule le nombre de pages */
    $nombreDePages = ceil($nombretotal / $limite);
    /* On va effectuer une boucle autant de fois que l'on a de pages */
    echo '<nav aria-label="...">
    <ul class="pagination pagination-sm">';
    for ($i = 1;$i <= $nombreDePages;$i++):
      ?><li class='page-item <?php if ($i == $_GET["page"]) {
        echo "active";
      } ?>'><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li> <?php
    endfor;
    echo '  </ul>
    </nav>';
    ?>

    <!-- formulaire d'avis -->
    <form class=" col-xl-8 mx-auto img-thumbnail p-5 text-white my-5 bandeau" action="#" method="POST" id="form">
      <input  type="hidden" name='id_avis' id='id_avis' value="<?php echo $id_avis; ?>">
      <div class="text-primary mb-4">
        <p>Modification du commentaire:</p>
      </div>
      <?php  echo '<h1>'.$resultat["note"].'testtototo</h1>';   ?>
      <select class="custom-select mb-2" name="note" >
        <option value="1" <?php if (isset($resultat["note"]) && $resultat["note"] == '1' || empty($resultat["note"])) {
          echo ' selected';
        } ?>>1</option>
        <option value="2" <?php if (isset($resultat["note"]) && $resultat["note"] == '2' || empty($resultat["note"])) {
          echo ' selected';
        } ?>>2</option>
        <option value="3" <?php if (isset($resultat["note"]) && $resultat["note"] == '3' || empty($resultat["note"])) {
          echo ' selected';
        } ?>>3</option>
        <option value="4" <?php if (isset($resultat["note"]) && $resultat["note"] == '4' || empty($resultat["note"])) {
          echo ' selected';
        } ?>>4</option>
        <option value="5" <?php if (isset($resultat["note"]) && $resultat["note"] == '5' || empty($resultat["note"])) {
          echo ' selected';
        } ?>>5</option>
      </select>
      <div class="mb-3 ">
        <textarea class="form-control message" name="commentaire" id="valider" placeholder="" style="min-height:150px"
        required> <?php if (isset($resultat['commentaire'])) {
          echo $resultat['commentaire'];
        } else {
          echo '';
        } ?></textarea>
        <div class="invalid-feedback">
          veuillez entrer un messager !.
        </div>
      </div>
      <select class="custom-select" id="inputGroupSelect01" name="active">
        <option  value="0"<?php if (isset($resultat['active']) && $resultat['active'] == '0' || empty($resultat['active'])) {
          echo ' selected';
        } ?>>En cours de Modération</option>
        <option value="1"<?php if (isset($resultat['active']) && $resultat['active'] == '1') {
          echo 'selected';
        } ?>> Non Approuvé</option>
        <option value="2"<?php if (isset($resultat['active']) && $resultat['active'] == '2') {
          echo ' selected';
        } ?> > Approuvé</option>
      </select>

      <div class="col-auto">
        <button type="submit" name="moderation" class="btn btn-black my-3 w-100 mx-auto"> Moderation </button>
      </div>
    </form>


    <!-- fin du contenu -->

    <?php 

// definition de la variable par defaut de la page qui égale à 1 pour la page 1 de la gestion des avis :
    if (isset($_POST['note']) && isset($_POST['commentaire']) && isset($_POST['id_avis']) ) {
      $id_avis = $_POST['id_avis'];
      $commentaire_nouveau = $_POST['commentaire'];
      $note_nouvelle = $_POST['note'];
      echo $_POST['note'];

      $modif = $pdo->prepare(" UPDATE avis SET commentaire = :commentaire , note = :note, active =:active  WHERE id_avis = :id  ");
      $modif->bindParam(':id', $id_avis, PDO::PARAM_STR);
      $modif->bindParam(':commentaire', $commentaire_nouveau, PDO::PARAM_STR);
      $modif->bindParam(':note', $note_nouvelle, PDO::PARAM_INT);
      $modif->bindParam(':active', $_POST['active'], PDO::PARAM_STR);
      $modif->execute();
    }
//inclusion des éléments:
    require_once('../inc/modal_mentionslegales.inc.php');
    require_once('../inc/modal_conditionsdevente.inc.php');
    require_once('../inc/footer.inc.php');
    ?>


