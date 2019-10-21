<?php
require_once('../inc/init.inc.php');
//pour la sécurité:  //personne ne sera ici c pas admin
if (!isAdmin()){
    header("location:" . URL . "connexion.php");  // si l'utilisateur n'est pas admin il ne poura pas rentrer dans cette page il ne pourra pas s'amuser sur l url grace à l'exit()
    exit();//provoque une erreur fatal et bloc l execution du script  
}
/*----------------------------------------------------------------------------------------------------------*/
/*----------------------------------------------------------------------------------------------------------*/
/*------------------------------------@suppression salles--------------------------------------------*/
/*----------------------------------------------------------------------------------------------------------*/
/*----------------------------------------------------------------------------------------------------------*/
//avec cette fonction le bouton en bas supprime l element séléctionné :
if(isset($_GET['action']) && $_GET['action']=='suppression' && !empty($_GET ['id_salle'])){
    
    $suppression =$pdo->prepare("DELETE FROM salle WHERE id_salle =:id");
    $suppression -> bindParam( ":id", $_GET['id_salle'] , PDO::PARAM_STR ). // pas d'espace quend tu écrit les indices ":id"
    $suppression->execute();
    // des qu on supprime on rebascule sur l'affichage du tableau :
    $_GET['action']='afficher';    
}
/*----------------------------------------------------------------------------------------------------------*/
/*----------------------------------------------------------------------------------------------------------*/
/*------------------------------------@fin de suppression salle--------------------------------------------*/
/*----------------------------------------------------------------------------------------------------------*/
// pour ne pas avoir des erreurs il faut déclarer toutes les variables tourt en haut
$id_salle="";
$titre='';
$description='';
$photo='';
$photo_bdd='';
$pays='';
$ville='';
$adresse = '';
$code_postal ='';
$capacite ='';
$categorie ='';
$cp='';
/*----------------------------------------------------------------------------------------------------------*/
/*----------------------------------------------------------------------------------------------------------*/
/*----------------------------------------------------------------------------------------------------------*/
/*------------------------------------@ENREGISTREMENT ou MODIFICATION-------------------------------------------*/
/*----------------------------------------------------------------------------------------------------------*/
/*----------------------------------------------------------------------------------------------------------*/
/*----------------------------------------------------------------------------------------------------------*/
// récupération des informations du produit en cas de modification
// avec ce code on va récuperer les valeurs de l'élément qu on veut modifier dans le formulaire comme ca le client va changer juste la valeur qu il veut 
if(isset($_GET['action']) && $_GET['action']=='modification' && !empty($_GET['id_salle'])){ 
    $recup_salle=$pdo->prepare("SELECT * FROM salle WHERE id_salle =:id");
    $recup_salle-> bindParam(':id',$_GET['id_salle'],PDO::PARAM_STR);
    $recup_salle -> execute();
    if($recup_salle -> rowCount()>0){ // si tu trouve l id de  l'élément  , donc tu lui affiche les valeurs selon l'indice de cet élément 
    $salle_actuelle=$recup_salle->fetch(PDO::FETCH_ASSOC);
    $id_salle=trim($salle_actuelle['id_salle']);
    $titre=trim($salle_actuelle['titre']);
    $photo_bdd=trim($salle_actuelle['photo']);
    $description=trim($salle_actuelle['description']);
    $pays=trim($salle_actuelle['pays']);
    $ville=trim($salle_actuelle['ville']);
    $cp=trim($salle_actuelle['cp']);
    $capacite=trim($salle_actuelle['capacite']);
    $categorie=trim($salle_actuelle['categorie']);
    $adresse =htmlentities($salle_actuelle['adresse']);
} 
}
//----------//
if(isset($_POST['titre'])&& isset($_POST['description'])&&isset($_POST['pays'])&&isset($_POST['ville'])&&isset($_POST['cp'])&&isset($_POST['capacite'])&&isset($_POST['categorie'])){ // si ses elements existes c que le formulaire est validé mais attention pas la photo ici 
    $id_salle =trim($_POST['id_salle']);
    $titre=trim($_POST['titre']);
    $description=trim($_POST['description']);
    $pays=trim($_POST['pays']);
    $ville=trim($_POST['ville']);
    $cp=trim($_POST['cp']);
    $capacite=trim($_POST['capacite']);
    $categorie=trim($_POST['categorie']);
    $photo = $_FILES['photo']['name'];
    $adresse = htmlentities($_POST['adresse']);
    //controle sur la disponibilité  de la référence car unique en BDD .
    $verif_salles=$pdo->prepare("SELECT * FROM salle WHERE id_salle=:id");   
    $verif_salles->bindParam(":id", $id_salle, PDO::PARAM_STR);                
    $verif_salles->execute();   
    if($verif_salles->rowCount()>0 && empty($id_salle)){
        
        $msg .='<div class="alert alert-danger mt-2"> Attention,<br>référence indisponible,<br> Veuillez vérifire vos saisies</div>';
    }
    //controle sur l'extention photos
    if(!empty($_FILES['photo']['name']) && empty($msg) ){ // si le champs name de array photos n'est pas vide donc la photos est chargée  et le message d'érreur est vide la variable $msg ne contient pas d'erreur
        //1 controle :  sur la validité de l'extension 
        $extension=strrchr( $_FILES['photo']['name'], '.'); //une fonction qui découpe lorsque elle trouve un point elle découpe le string , (.jpg .png ;gig .jpeg) .
        // si le fichier s'appelle maphotos.jpg il récupere .jpg   
        // on enleve le . et on transforme en minuscule strtolower( ); car il ya des extentions en majuscule des photos et le php est sensible à la casse  .
        $extension =strtolower( substr($extension ,1)); //cette fonction substr( ) prend deux arguments , le premier c'est le string qu on veut traiter et le deuxieme et à partir de quand on découpe , ici à partir de l'élément 1 jusqu'a la fin , du coup en enleve le . de l'extension et on aura à la fin des deux fonction la une extension en minuscule sans le point . 
        //on déclare un tableau array contenant les extensions accéptées .
        $tab_extension_valide= (array('png','jpg','jpeg','gif'));// tableau des extensions qu on accepte 
        //on compare l'extension ave"c celles autorisées via la fonction in_array() qui test si le premier argument fourni est présent dans les valeurs d'un tableau fourni en deuxieme argument .
        $verif_extension =in_array($extension,$tab_extension_valide) ; // in_array(extension qu on a récuperer ,l'extension qui se trouve dans le tableau qu on vient de déclarer ) c'est une fonction qui teste les valeurs , elle récupere les valeurs d'un tableau qu on a déclarer , des extentions valide $tab_extension_valide= (array('png','jpg','jpeg','gif')); et compare avec l'extension qu ona récuperer $extension .
        if($verif_extension){
            $nom_photo=$_FILES['photo']['name']; 
            $photo_bdd='img/'.$nom_photo;
            // pour copier un media (tel la photo )dans la base de donnée on a besoin de  donc on va dans le dossier init pour : faire une variable define pour le server root constante RACINE_SERVER  .
            $dossier_photo= RACINE_SERVER .'/img/'.$nom_photo ;
            //copie de l'image depuis l'emplacement temporaire vers la cible représentée par la variable $dossier_photo
            copy( $_FILES['photo']['tmp_name'] , $dossier_photo) ; //execute cette fonction copy( emplacement de départ , emplacement cible)
        }
        else{
            $msg .='<div class="alert alert-danger mt-2"> Attention,<br>format de l\'image n\'est pas accepté <br> format accépté .jpg .jpeg .png .gif</div>';
        }
    }
    // s'il n ya pas eu d'erreur on commence l'enregistrement /
    if(empty($msg)){
        
        if(empty($id_salle)){  // si l'id salle est vide donc ya pas cet salle donc on va entamer l enregistrement  sinon c'est else qui se déclenche , c'est la modification
        $enregistrement= $pdo->prepare("INSERT INTO salle (titre ,description,photo , pays,ville,adresse,cp,capacite,categorie) VALUES(:titre ,:description,:photo , :pays,:ville,:adresse,:cp,:capacite,:categorie)" );
    }
    
        else { // si la salle existe on fait juste un update , une mise à jour , du coup on fait une requete update  des indices
            $enregistrement = $pdo->prepare( " UPDATE salle SET titre = :titre ,description=:description,photo=:photo,pays = :pays,ville =:ville,adresse =:adresse,cp =:cp,capacite =:capacite, categorie =:categorie  WHERE id_salle = :id_salle ");
            $enregistrement-> bindParam(':id_salle' , $id_salle, PDO::PARAM_STR);
        }
        //avec ce code la  on peut changer certains indices sans les autres  meme la photos on peut la changer
        $enregistrement-> bindParam(':titre' ,$titre ,PDO::PARAM_STR );
        $enregistrement-> bindParam('description' ,$description ,PDO::PARAM_STR );
        $enregistrement-> bindParam(':photo' ,$photo ,PDO::PARAM_STR );
        $enregistrement-> bindParam(':pays' ,$pays ,PDO::PARAM_STR );
        $enregistrement-> bindParam(':ville' ,$ville ,PDO::PARAM_STR );
        $enregistrement-> bindParam(':adresse' ,$adresse ,PDO::PARAM_STR );
        $enregistrement-> bindParam(':cp' ,$cp ,PDO::PARAM_STR );
        $enregistrement-> bindParam(':capacite' ,$capacite ,PDO::PARAM_STR );
        $enregistrement-> bindParam(':categorie' ,$categorie ,PDO::PARAM_STR );   
        // n'ouble pas execute()
        $enregistrement-> execute();  
        //onrebascule sur l'affichage du tableau : 
        $_GET['action']='afficher';
    }
}
/*------------------------------------@ FIN DES ENREGISTREMENT DES salles---------------------------------*/
/*----------------------------------------------------------------------------------------------------------*/

/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

require_once('../inc/header.inc.php');
require_once('../inc/nav.inc.php');
?>
<div class="starter-template">
    <h1><i class="fab fa-think-peaks text-warning"></i> Gestion Boutique <i class="fab fa-think-peaks text-warning"></i></h1>
    <p class="lead">Bienvenue sur ma boutique.</p>
    <hr>
    <div class="w-100">
        <a  onclick="window.location.href='?action=ajouter&page=1'" class='btn btn-primary'>Ajouter une salle</a>  
        <a  onclick="window.location.href='?action=afficher&page=1'" class='btn btn-warning'>Voir les salles</a>   
    </div>
    <?php echo $msg; ?>
    <hr>
</div>
<div class="row">
    <?php // si on appuie sur le bouton oû l'indice action est égal ajouter (<a href="?action=ajouter" class='btn btn-primary'>Ajouter un salle</a>)il va nous afficher le formulaire et si on appuie sur un autre bouton ou l'indice n'est pas ajouter il va disparaitre , c'est utile 
    if(isset($_GET['action'])&& ($_GET['action']=='ajouter' || $_GET['action']=='modification')) {   ?> <!-- on affiche le formulaire si on appuie aussi sur modifier || $_GET['action']=='modification' -->
    <div class=" col-sm-8 mx-auto">
        <!-- enctype="multipart/form-data dans la balise form pour appeler la photos car c un file" -->
        <form method="POST" action="#" enctype="multipart/form-data" >  
            <input type="hidden" name='id_salle' id='id_salle' value="<?php echo $id_salle; ?>">
            <div class="form-group">
                <label for="titre">Nom</label>
                <input type="text" name="titre" id="titre" value="<?php echo $titre; ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" rows="5" id="description" name="description"><?php if(isset($_POST['description'])){ echo $_POST['description'];}else{echo $description;} ?></textarea>
            </div>
           
            <?php
             
            if(isset($salle_actuelle)){
                
                //affiche la photos de la salle actuel par son url et indice .URL.$salle_actuelle['photos'] 
                // l'image va s'afficher si l utilisateur veut modifier la salle .
                echo'<div class="text-center"> ';
                echo'<h3> photos actuelle </h3>';
                echo'<img src="'.URL.'img/'.$salle_actuelle['photo'].'" class="img-thumbnail" alt="image salle" style="max-width:200px">';
                echo'</div> ';
            }
            ?>
            <div class="form-group">
                <label for="photo">Photo</label>
                <input type="file" name="photo" id="photo" class="form-control">
            </div>
            <div class="form-group">
                <label for="capacite">Capacite</label>
                <select name="capacite" class="form-control" id="capacite">
                    <option <?php if ($capacite == '2') { echo 'selected'; } ?> > 2</option>
                    <option <?php if ($capacite == '5') { echo 'selected'; } ?> > 5</option> 
                    <option <?php if ($capacite == '10') { echo 'selected'; } ?> > 10</option> 
                    <option <?php if ($capacite == '20') { echo 'selected'; } ?> > 20</option> 
                    <option <?php if ($capacite == '30') { echo 'selected'; } ?> > 30</option> 
                    <option <?php if ($capacite == '50') { echo 'selected'; } ?> > 50</option> 
                    <option <?php if ($capacite == '55') { echo 'selected'; } ?> > 55</option> 
                    <option <?php if ($capacite == '60') { echo 'selected'; } ?> > 60</option> 
                </select>
            </div>
            <div class="form-group">
                <label for="reunion">Categorie</label>
                <select name="categorie" class="form-control" id="reunion">
                    <option <?php if ($categorie == 'reunion') { echo 'selected'; } ?> > Réunion</option>
                    <option <?php if ($categorie == 'bureau') { echo 'selected'; } ?> > Bureau</option> 
                    <option <?php if ($categorie == 'formation') { echo 'selected'; } ?> > Formation</option> 
                </select>
            </div>
            <div class="form-group">
                <label for="pays">Pays</label>
                <select name="pays" class="form-control" id="pays">
                    <option <?php if ($pays == 'france') { echo 'selected'; } ?> > France</option>
                    <option <?php if ($pays == 'portugal') { echo 'selected'; } ?> >Portugal</option> 
                    <option <?php if ($pays == 'espagne') { echo 'selected'; } ?> > Espagne</option> 
                    <option <?php if ($pays == 'italie') { echo 'selected'; } ?> > Italie</option> 
                    <option <?php if ($pays == 'ukraine') { echo 'selected'; } ?> > Ukraine</option> 
                    <option <?php if ($pays == 'senegal') { echo 'selected'; } ?> > Senegal</option> 
                    <option <?php if ($pays == 'maroc') { echo 'selected'; } ?> > Maroc</option> 
                    <option <?php if ($pays == 'zaire') { echo 'selected'; } ?> > Zaire</option> 
                </select>
            </div>
            <div class="form-group">
                <label for="ville">Ville</label>
                <select name="ville" class="form-control" id="ville">
                    <option <?php if ($ville == 'paris') { echo 'selected'; } ?> > Paris</option>
                    <option <?php if ($ville == 'dakar') { echo 'selected'; } ?> > Dakar</option> 
                    <option <?php if ($ville == 'tunis') { echo 'selected'; } ?> > Tunis</option> 
                    <option <?php if ($ville == 'marseille') { echo 'selected'; } ?> > Marseille</option> 
                    <option <?php if ($ville == 'lyon') { echo 'selected'; } ?> > Lyon</option> 
                    <option <?php if ($ville == 'roubaix') { echo 'selected'; } ?> > Roubaix</option> 
                    <option <?php if ($ville == 'nantes') { echo 'selected'; } ?> > Nantes</option> 
                    <option <?php if ($ville == 'strasbourg') { echo 'selected'; } ?> > Strasbourg</option> 
                </select>
            </div>
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <textarea class="form-control" rows="5" id="adresse" name="adresse"><?php if(isset($_POST['adresse'])){ echo $_POST['addresse'];}else{echo $adresse;} ?></textarea>
            </div>    
            <div class="form-group">
                <label for="code_postal">Code Postal</label>
                <input type="text" name="cp" id="code_postal" value="<?php if(isset($_POST['cp'])){ echo $_POST['cp'];}else{echo $cp;} ?>" class="form-control">
            </div>
            <hr>
            <!--  afficher le text du bouton valider selon soit modification et enregistrement ( elle change l 'intitulé selon le bouton appuyer -->
            <input type="submit" name="inscription" id="inscription" value="<?php echo ucfirst($_GET['action'] );?>"   class="form-control btn btn-warning text-white">
        </form>
    </div>   
<?php }  ?>
<?php 
//affichage des salles produits 
//code de pagination
$limite = 2; 
$page = (!empty($_GET['page'])? $_GET['page']: 1);
$debut = ($page -1)* $limite;
if(isset($_GET['action'])&& $_GET['action']=='afficher') { //si on appuie sur le bouton oû l'indice action est égal ajouter (<a href="?action=afficher" class='btn btn-primary'>Ajouter un salle</a>)il va nous afficher le formulaire et si on appuie sur un autre bouton ou l'indice n'est pas ajouter il va disparaitre , c'est utile 
    $list_salle= $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM salle ORDER BY id_salle DESC LIMIT :limite OFFSET :debut"); //selection de touts les produits .
    $list_salle->bindParam(':limite',$limite,PDO::PARAM_INT);
    $list_salle->bindParam(':debut',$debut,PDO::PARAM_INT);
    $list_salle->execute();
    $resultfound = $pdo->query('SELECT found_rows()');
    $nombretotal = $resultfound->fetchColumn();
    echo '<div class="col-sm-12">';
    // affichage de nombre d'salles avec rowCount():
    echo ' <button type="button"  class="btn btn-primary">
    Nombre de Salle : <span class="badge badge-light" >' . $nombretotal .'</span> </button> <hr>';
    //affichage du tableau :
    echo '<table class="table table-bordered bg-white">';
    echo '<thead class="thead-dark">';
    echo ' <tr>
    <th> Id </th>
    <th> Nom</th>
    <th> Description</th>
    <th> Photo</th>
    <th> Pays </th>
    <th> Ville </th>
    <th>Adresse</th>
    <th> Code Postal </th>
    <th> Capacite </th>
    <th> Categorie</th>
    <th> Modifier </th>
    <th> Supprimer</th>
    </tr>';
    echo '</thead>';
    while ($mysalle=$list_salle -> fetch(PDO::FETCH_ASSOC)){
        echo '<tr>' ;
        foreach($mysalle AS $indice=>$valeur) {
            
            if( $indice=='photo'){ // si lindice est photos tu me l'appele par son url pour qu elle s'affiche 
            echo '<td> <a href="'.URL.'img/'.$valeur.'" rel="lightbox" ><img src="'.URL.'img/'.$valeur.'" alt="image salle" class="img-fluid" style="max-width:110px" ></a> </td>';    
        }
            elseif( $indice=='description'){ // si l'indice est description tu la découpe car elle est longue et tu m'affiche à la fois un nombre égale de caratactere avec la fonction substr()
            echo '<td>'.substr($valeur,0,14 ).' <a href ="">... </a> </td>';   
        }
        else{  
            echo '<td>'.$valeur.'</td>';
        }   
    } 
        // on a crééer deux bouton dans cette boucle avec les icones i et avec des methodes GET  on va cibler l element qu on veut soit supprimer ou éditer selon son id-salle .voir juste apres:
        // on a mis un petit onclick(confirm())  javascript pour dire si vous etes sur de supprimer l'élément car supprimer est irrévérsible . regarde en haut pour voir la fonction Get pour supprimer (@suppression produits)
    echo '<td><a href="?page='.$_GET["page"].'&action=modification&id_salle='.$mysalle['id_salle'].'" class="btn btn-success text-white"  > <i class="fas fa-edit"></i></a>  </td>';
    echo '<td> <a href="?page='.$_GET["page"].'&action=suppression&id_salle='.$mysalle['id_salle'].'" class="btn btn-danger text-white" onclick="(confirm(\'Etes-vous sûr ? \'));"> <i class="fas fa-trash-alt"></i></a> </td>';      
        /* on peut le faire à la main 
        echo '<td>'. $salle['id_salle'].'</td>'; et on fait ca evec tout les indices et on va faire pareil que le foreitch avec l'ordre qu on veut */       
        echo '</tr>' ;
    }   
    echo '</table>';
    echo '</div>';   
    $nombreDePages = ceil($nombretotal / $limite);   
    echo $nombretotal;  
    /* On va effectuer une boucle autant de fois que l'on a de pages */
    echo'<nav aria-label="...">
    <ul class="pagination pagination-sm">';
    for ($i = 1; $i <= $nombreDePages; $i++):
        ?><li class='page-item <?php if($i == $_GET["page"]){ echo "active";} ?>'><a class="page-link" href="?action=afficher&page=<?php echo $i; ?>"><?php echo $i; ?></a></li> <?php
    endfor;
    echo '  </ul>
    </nav>';      
}
?>
</div>    
<?php
//inclusion des éléments:
require_once('../inc/modal_mentionslegales.inc.php');
require_once('../inc/modal_conditionsdevente.inc.php');
require_once('../inc/footer.inc.php');