<?php
require_once('inc/init.inc.php');
// si l'utilisateur n'est pas connecté on le rederige à header(location:connexion.php);
if(!isconnected()){
    header('location:index.php');
}
$id_membre=$_SESSION['membre']['id_membre'];
//suppression du profil :
if(isset($_GET['action'])&& $_GET['action'] =='supprimer' && !empty($_SESSION['membre']['id_membre']) ){
    $sup_membre=$pdo->prepare("DELETE FROM membre WHERE id_membre = :id_membre ");
    $sup_membre->bindParam(':id_membre',$_SESSION['membre']['id_membre'],PDO::PARAM_STR);
    $sup_membre->execute();
    header("location:" . URL . "index.php");
    session_destroy();
}
// RECUPERATION DES INFORMATIONS DU membre pour le modifier :
$msg='';
if(isset($_POST['modification'])){
    $recup_membre=$pdo->prepare("SELECT * FROM membre WHERE id_membre =:id_membre");
    $recup_membre-> bindParam(':id_membre',$_SESSION['membre']['id_membre'],PDO::PARAM_STR);
    $recup_membre -> execute();
    $result = $recup_membre->fetch(PDO::FETCH_ASSOC);
    if($recup_membre -> rowCount()> 0 ){
        if(isset($_POST['pseudo']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['civilite'])){
            //mise à jour du profil
            $id_membre = $_SESSION['membre']['id_membre'];
            $pseudo = trim($_POST['pseudo']);
            $nom = trim($_POST['nom']);
            $prenom = trim($_POST['prenom']);
            $email = trim($_POST['email']);
            $civilite = $_POST['civilite'];
            $_SESSION['membre']['pseudo'] = $pseudo;
            $_SESSION['membre']['nom'] = $nom;
            $_SESSION['membre']['prenom'] = $prenom;
            $_SESSION['membre']['email'] = $email;
            $_SESSION['membre']['civilite'] = $civilite;
            
            // contrôle sur la validité des caractères du pseudo
            $verif_caractere=preg_match("#^[a-zA-Z0-9._-]+$#", $_SESSION['membre']['pseudo']);
            
            if(!$verif_caractere && !empty($_SESSION['membre']['pseudo'])){
                $msg .='<div class="alert alert-danger"> Attention, <br> Caractères acceptés: A à Z et 0 à 9<br> Veuillez vérifier votre saisie</div>';
            }
            
            // controle sur la taille du pseudo
            if(iconv_strlen($_SESSION['membre']['pseudo']) < 4 || iconv_strlen($_SESSION['membre']['pseudo']) > 14){
                $msg .='<div class="alert alert-danger mt-2"> Attention, <br> Le pseudo doit avoir entre 4 et 14 caractères<br> Veuillez vérifier votre saisie</div>';
            }
            
            // controle sur la validité du champ email
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $msg .='<div class="alert alert-danger mt-2"> Attention,<br>Format de l\'email incorrect<br> Veuillez vérifier votre saisie</div>';
            }
            
            // controle sur la dispo du pseudo
            $verif_pseudo=$pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
            $verif_pseudo->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
            $verif_pseudo->execute();
            
            if($verif_pseudo->rowCount() > 0) {
                $msg .='<div class="alert alert-danger mt-2"> Attention,<br>Pseudo indisponible</div>';
            }
            
            // controle sur le nom et le prénom
            if(empty($nom) || empty($prenom)){
                $msg .='<div class="alert alert-danger mt-2"> Attention,<br>Veuillez entrez un nom et/ou un prénom</div>';
            }
            if(empty($msg)){
                $enregistrement = $pdo->prepare( " UPDATE membre SET pseudo = :pseudo ,nom=:nom ,prenom = :prenom ,email =:email,civilite =:civilite  WHERE id_membre = :id_membre ");
                $enregistrement -> bindParam(':id_membre' ,$id_membre ,PDO::PARAM_STR );
                $enregistrement -> bindParam(':pseudo',$pseudo,PDO::PARAM_STR);
                $enregistrement -> bindParam(':nom',$nom,PDO::PARAM_STR);
                $enregistrement -> bindParam(':prenom',$prenom,PDO::PARAM_STR);
                $enregistrement -> bindParam(':email',$email,PDO::PARAM_STR);
                $enregistrement -> bindParam(':civilite',$civilite,PDO::PARAM_STR);
                $enregistrement -> execute();
            }
        }
    }
    
}

//inclusion du header et de la nav:
require_once('inc/header.inc.php');
// require_once('inc/modal_connexion.inc.php');
// require_once('inc/modal_inscription.inc.php');
require_once('inc/nav.inc.php');

// récuperation des données du membres connecté :
if(isset($_SESSION['membre'])){
    $recup_membre=$pdo->prepare("SELECT * FROM membre WHERE id_membre =:id_membre");
    $recup_membre-> bindParam(':id_membre',$_SESSION['membre']['id_membre'],PDO::PARAM_STR);
    $recup_membre -> execute();
    $result = $recup_membre->fetch(PDO::FETCH_ASSOC);
    $id_membre=trim($result['id_membre']);
    $pseudo=trim($result['pseudo']);
    $nom=trim($result['nom']);
    $prenom=trim($result['prenom']);
    $email=trim($result['email']);
    $civilite=trim($result['civilite']);
    
    ?>
    <div class="container  mt-1">
    </div>
    <div class="container row mx-auto bg-white">
        <div class="col-md-4">
            <div class="profile-img">
                <img src="<?php echo URL.'img/profil.jpg'; ?>" alt="photos-de-profil" class="photo mb-4 ml-2 mt-3 pt-2"/>
                <div class=" file btn btn-lg btn-primary " style="display:none">
                    Changer la photo
                    <input type="file" name="file"/>
                </div>
            </div>
        </div>
        <div class="col-md-11 mx-auto">
            <div class="profile-head">
                <div class="pb-3">
                    <?php echo '<p class="text-white text-center p-4" style="background-color:grey; font-size:2em">Bienvenue </p>' .' <br> '.$result['nom'].' '.$result['prenom'] ?>
                </div>
                <!-- <p class="proile-rating">RANKINGS : <span>8/10</span></p> -->
                <ul class="nav nav-tabs" id="myTab" role="tablist" style="background-color:#2e2e33;">
                    <li class="nav-item">
                        <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Réservations</a>
                    </li>
                    <li class="nav-item">
                        <a  <?php if(isset($_POST['modification'])){ echo'class="nav-link active show"';}else{ echo'class="nav-link active"';} ?> id="form-tab" data-toggle="tab" href="#form" role="tab" aria-controls="form"<?php if(isset($_POST['modification'])){ echo'aria-selected="true"';}else{ echo ' aria-selected="false" ';} ?>>Edit profil</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row container mx-auto" style="background-color:#d3c9c9;">
        <div class="col-md-12">
            <div class="tab-content profile-tab" id="myTabContent">
                <div class=" col-12 tab-pane" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row pt-4">
                        <div class="col-md-6">
                            <label>Identifiant client</label>
                        </div>
                        <div class="col-md-6">
                            <p> <?php echo $id_membre ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Pseudo</label>
                        </div>
                        <div class="col-md-6">
                            <p><?php echo $pseudo ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Nom </label>
                        </div>
                        <div class="col-md-6">
                            <p><?php echo $nom ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Prénom </label>
                        </div>
                        <div class="col-md-6">
                            <p><?php echo $prenom ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Civilité</label>
                        </div>
                        <div class="col-md-6">
                            <p><?php if ($result['civilite'] =='m' ){echo 'Homme';}
                            else{echo 'Femme';
                        }?></p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <label>Email</label>
                    </div>
                    <div class="col-md-6">
                        <p><?php echo $email .' '; ?></p>
                    </div>
                </div>
                <hr>
                <div class="row mt-4">
                    <div class="col-md-4 offset-md-8 mt-4">
                        <a  href="?action=supprimer&id_membre=<?php $result['id_membre'] ;?>" class="btn btn-primary text-white profile-edit-btn w-100 mx-auto py-2 mb-2" onclick="(confirm('etes vous sur'));">Supprimer profil</a>
                    </div>
                </div>
            </div>
            <?php
                //    if(isset($_GET['action']) && $_GET['action']=='edit_reservation'){ }
            $recup_commande=$pdo->prepare("SELECT c.id_commande, s.titre,s.categorie,s.adresse,s.cp,s.ville,p.id_produit, p.prix, p.date_arrivee, p.date_depart FROM detail_commande c LEFT JOIN produit p ON c.id_produit=p.id_produit LEFT JOIN salle s ON p.id_salle=s.id_salle WHERE c.id_membre=:id_membre");
            $recup_commande-> bindParam(':id_membre',$_SESSION['membre']['id_membre'],PDO::PARAM_STR);

            $recup_commande -> execute();
            $nombreCommande= $recup_commande->rowCount();
            $commandes =$recup_commande->fetchAll(PDO::FETCH_ASSOC); ?>
            <div class="tab-pane mt-4 p-4" id="profile" role="tabpanel" aria-labelledby="profile-tab" style="background-color:#e6e6e6;">
                <div class="row bg-info p-2 text-center mb-4">
                    <div class="col-md-6  ">
                        <label class="pt-4">Nombre de commande</label>
                    </div>
                    <div class="col-md-6 ">
                        <p class=""><span class="badge badge-secondary p-3 mt-2"><?php echo $nombreCommande ;?></span></p>
                    </div>
                </div>
                <div id="accordion">
                    <?php
                    for($i=0; $i < sizeof($commandes); $i++ ){
                        ?>
                        <div class="card">
                            <div class="card-header" id="<?php echo 'heading'.$i ?>">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="<?php echo '#collapse'.$i ?>" aria-expanded="true" aria-controls="<?php echo 'collapse'.$i ?>">
                                        commande <?php echo $i ?>
                                    </button>
                                </h5>
                            </div>
                            <div id="<?php echo 'collapse'.$i ?>" class="collapse " aria-labelledby="<?php echo 'heading'.$i ?>" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6  ">
                                            <label>Numéro de la commande</label>
                                        </div>
                                        <div class="col-md-6">
                                            <p><?php echo $commandes[$i]['id_commande'];?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>identifiant du produit</label>
                                        </div>
                                        <div class="col-md-6">
                                            <p><?php echo $commandes[$i]['id_produit'];?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Catégorie de la  salle</label>
                                        </div>
                                        <div class="col-md-6">
                                            <p><?php echo $commandes[$i]['categorie'];?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Nom de la  salle</label>
                                        </div>
                                        <div class="col-md-6">
                                            <p><?php echo $commandes[$i]['titre'];?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Prix</label>
                                        </div>
                                        <div class="col-md-6">
                                            <p><?php echo $commandes[$i]['prix'] .' €';?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Adresse de la salle</label>
                                        </div>
                                        <div class="col-md-6">
                                            <p><?php echo $commandes[$i]['adresse'] . ' ' .$commandes[$i]['cp'] .'  '. $commandes[$i]['ville'] ;?></p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Date d'arrivée</label>
                                        </div>
                                        <div class="col-md-6">
                                            <p><?php echo $commandes[$i]['date_arrivee'];?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Date de départ</label>
                                        </div>
                                        <div class="col-md-6">
                                            <p><?php echo $commandes[$i]['date_depart'];?></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <!--formulaire  -->

            <div class="  tab-pane mt-4 p-4 show active" id="form" role="tabpanel" aria-labelledby="form-tab">
                <form method="post" class="p-4 text-white"  action="#"  style=" background-color:#0856a4; ">
                    <hr>
                    <?php echo $msg; ?>
                    <hr>
                    <div class="form-group">
                        <label for="pseudo">Pseudo</label>
                        <input type="text" name="pseudo" id="pseudo" value="<?php echo trim($pseudo) ; ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="mdp">Mot de passe</label>
                        <input type="text" name="mdp" id="mdp" value="" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" name="nom" id="nom" value="<?php echo trim($nom);  ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" name="prenom" id="prenom" value="<?php echo trim($prenom) ; ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" value="<?php echo trim($email) ;  ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="email">Civilité</label>
                        <select name="civilite" class="form-control" id="civilite" >
                            <option value="m"><?php echo  trim($civilite) ; ?></option>
                        </select>
                    </div>
                    <hr>
                    <input id="validation_modif" type="submit" name="modification"  value="Modification" class="form-control btn  text-white" style="background-color:#221e12;">
                </form>
            </div>
        </div>
    </div>

</div>
<?php
    // fin de la condition de la récupération du membre de la base de données pour éviter d'utiliser $_SESSION
}
?>
<script>
    $(document).ready(function(){
        $('#formulaire').hide();
        $('#btn_edit').on('click', function(e){
            e.preventDefault();

            $('#formulaire').show();
        });
    });
</script>
<?php
//inclusion du footer
require_once('inc/footer.inc.php');
