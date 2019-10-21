<?php
require_once ('inc/init.inc.php');

//verification de l'existence du produit en base de données.
$produit1 = $pdo->prepare("SELECT * FROM produit WHERE id_produit =:id_produit ");
$produit1->bindParam('id_produit',$_GET['id_produit'], PDO::PARAM_INT);
$produit1->execute();
$resultat = $produit1->rowCount();
//redirection si les éléments ci-dessous n existe pas :
$category = array('reunion','bureau','formation');

if($resultat == 0 || empty($_GET['categorie']) || empty($_GET['action']) || !in_array($_GET['categorie'],$category))
{
    header('location:index.php');
}

$membre = '';
// récuperation du membre et le mettre dans une variable .
$id_salle = ''; // il me faut l id de la salle commentée
if (isset($_POST['note']) && isset($_POST['commentaire'])) {
    $membre = $_SESSION['membre']['id_membre'];
    $note = htmlentities($_POST['note'], ENT_QUOTES);
    $commentaire = htmlentities($_POST['commentaire'], ENT_QUOTES);
    $id_salle = $produit[0]['id_salle'];
    $req = "INSERT INTO avis (id_avis , id_membre,id_salle,note,commentaire, date_enregistrement) VALUES (NULL, '$membre' ,'$id_salle', '$note', '$commentaire' , NOW()) "; //requete pour inserer les avis avec le bon id_salle
    $enregistrement = $pdo->prepare($req);
    $enregistrement->bindParam(':note', $note, PDO::PARAM_STR);
    $enregistrement->bindParam(':id_membre', $membre, PDO::PARAM_STR);
    $enregistrement->execute();
    // pour perdre les saisies de l'utilisateur (message qui se réenregistre avec un F5):  
}
// le nombre d'avis pour ce produit :
$id_produit = $_GET['id_produit'];
// requete pour selectionner le bon avis du bon produit avec inner join
$recup_avis = $pdo->query("SELECT *from avis
    WHERE id_salle in (SELECT id_salle FROM produit WHERE id_produit = $id_produit);");
    //echo'<pre>' ;print_r($_GET); echo '</pre>'; // pour afficher les éléménts récuperer sans la photos ;
    // echo '<pre>' ;echo print_r($_FILES);echo '</pre>'; // pour afficher array de l'élément  photo;
require_once ('inc/header.inc.php');
    //récupération du nombre d'avis qui s'affiches:
$avis_actif = $pdo->query("SELECT * from avis WHERE id_salle in (SELECT id_salle FROM produit WHERE id_produit = $id_produit) AND active=2 ORDER BY date_enregistrement DESC ");
$nombre_avis_actif = $avis_actif->rowCount();
?>
<!-- avis -->
<div class=" col-12 p-0 p-sm-2 mx-auto bg-info py-3 mt-2" id="avis" style="display:none">
    <div class=" col-xl-12 mx-auto bg-light pt-3">
                <div class="row mx-auto my-3">
                    <h5 class="col-sm-4 "> Le nombre d'avis : <?php echo $nombre_avis_actif ?> </h5>
                    <?php
                    if (isconnected()) {
                        echo '<a class="btn btn-beige  col-sm-3 offset-sm-5 text-white " href="#form" style="background-color:#101014;"> Ecrire un Avis</a> ';
                    } else {
                        echo '<a class="btn btn-beige  col-sm-3 offset-sm-5 text-white " href="#form" style="background-color:#101014;"> voir les avis</a> ';
                    }
                    ?>
                </div>
                <hr>
                <div class="row col-xl-12 mx-auto ">
                    <?php
                            //affichage des avis en boucle
                    while ($avis = $recup_avis->fetch(PDO::FETCH_ASSOC)) {
                        $valeur = $avis['active'];
                                // jusque ici on a récuperer les bon avis des bons produit mais il nous faut le nom et le prénom de la personne qui a déposer son avis du coup on va faire une requete sur le base qu on connais l'id de la personne qui a poté un avis car elle existe sur la table avis  . on la récupere dans une base de donnée
                        if ($valeur == 2) {
                            $info_membre = trim($avis['id_membre']);
                                    // recuperation des informations du membre  qui ont l'id de cet avis et c'est bon avec cette requete ;
                            $recup_infos = $pdo->query("SELECT *from membre WHERE id_membre = $info_membre ");
                                    $recuperation_du_membre = $recup_infos->fetch(PDO::FETCH_ASSOC); // avec un fetch pour donner du sens au résultat puis on bas on va remplacer le nom et prénom par le bon prenom dans la variable $recuperation_du_membre['nom'] et $recuperation_du_membre['prenom']
                                    
                                    // affichage de la date en francais
                                    $date_commentaire = new DateTime($avis['date_enregistrement']);
                                    $date_fr = $date_commentaire->format("d/m/Y");
                                    $heure_fr = $date_commentaire->format("H:i:s");
                                    //protection contre les injections xss
                                    $avis['note'] = htmlentities($avis['note'], ENT_QUOTES);
                                    $avis['commentaire'] = trim(htmlentities($avis['commentaire'], ENT_QUOTES));
                                    //on remplace les valeurs juste en bas dans le code html qui se trouve dans la boucle while .
                                    echo '<hr>';
                                    ?>
                                    
                                    <div class="row mx-auto m-0 p-0">
                                        <div class="row card-body">
                                            <!-- <div class="row"> -->
                                                <div class="col-12   col-sm-3 col-md-2 ">
                                                    <img  src="https://image.ibb.co/jw55Ex/def_face.jpg" class=" image-avis left-auto img img-rounded img-fluid" alt="profile"/>
                                                    <hr>
                                                    
                                                <p class=" text-secondary text-center left-auto"><?php echo 'publié le <br>' . $date_fr . ' à ' . $heure_fr  ?></p>
                                            </div>
                                            <div class="col-sm-9 col-md-10 p-3 text-white" style="background-color:#3a3b3b; border-radius: 20px;">
                                                <p class="p-2" >
                                                    <a class="float-left" href="https://maniruzzaman-akash.blogspot.com/p/contact.html" ><strong><?php echo $recuperation_du_membre['nom'] . ' ' . $recuperation_du_membre['nom']; ?>
                                                </strong></a>
                                                <?php 
                                                    // il nous affiche le nombre d'etoiles selon la note donnée de 1 jusqua 5 étoiles .
                                                if($avis['note']==1){
                                                    echo '<span class="float-right text-right "><i class="text-white fa fa-star"></i></span>';
                                                    echo '<span class="float-right text-right "><i class="text-white fa fa-star"></i></span>';
                                                    echo '<span class="float-right text-right "><i class="text-white fa fa-star"></i></span>';
                                                    echo '<span class="float-right text-right "><i class="text-white fa fa-star"></i></span>';
                                                    echo '<span class="float-right text-right "><i class="text-warning fa fa-star"></i></span>';  
                                                }
                                                elseif($avis['note']==2){
                                                    echo '<span class="float-right text-right "><i class="text-white fa fa-star"></i></span>';
                                                    echo '<span class="float-right text-right "><i class="text-white fa fa-star"></i></span>';
                                                    echo '<span class="float-right text-right "><i class="text-white fa fa-star"></i></span>';
                                                    echo '<span class="float-right text-right "><i class="text-warning fa fa-star"></i></span>';
                                                    echo '<span class="float-right text-right "><i class="text-warning fa fa-star"></i></span>';  
                                                }
                                                elseif($avis['note']==3){  
                                                    echo '<span class="float-right text-right "><i class="text-white fa fa-star"></i></span>';
                                                    echo '<span class="float-right text-right "><i class="text-white fa fa-star"></i></span>';
                                                    echo '<span class="float-right text-right "><i class="text-warning fa fa-star"></i></span>';
                                                    echo '<span class="float-right text-right "><i class="text-warning fa fa-star"></i></span>';
                                                    echo '<span class="float-right text-right "><i class="text-warning fa fa-star"></i></span>';  
                                                }
                                                elseif($avis['note']==4){
                                                    echo '<span class="float-right text-right "><i class="text-white fa fa-star"></i></span>';
                                                    echo '<span class="float-right text-right "><i class="text-warning fa fa-star"></i></span>';
                                                    echo '<span class="float-right text-right "><i class="text-warning fa fa-star"></i></span>';
                                                    echo '<span class="float-right text-right "><i class="text-warning fa fa-star"></i></span>';
                                                    echo '<span class="float-right text-right "><i class="text-warning fa fa-star"></i></span>';  
                                                }
                                                elseif($avis['note']==5){
                                                    echo '<span class="float-right text-right "><i class="text-warning fa fa-star"></i></span>';
                                                    echo '<span class="float-right text-right "><i class="text-warning fa fa-star"></i></span>';
                                                    echo '<span class="float-right text-right "><i class="text-warning fa fa-star"></i></span>';
                                                    echo '<span class="float-right text-right "><i class="text-warning fa fa-star"></i></span>';
                                                    echo '<span class="float-right text-right "><i class="text-warning fa fa-star"></i></span>';  
                                                }
                                                ?>
                                            </p>
                                            <p class='text-avis px-lg-3 pt-4 ' data-maxlength="50">     
                                                <?php
                                                echo $avis['commentaire'];
                                                ?>
                                            </p>  
                                        </div>  
                                    </div>
                                </div>
                                    <?php
                                }  
                            }; ?>
                        </div>
                        <hr>       
                        <!-- formulaire d'avis -->
                        <?php
                        if (isconnected()) {
                            echo '<form class=" col-xl-8 mx-auto img-thumbnail p-5 text-white my-5 bandeau mb-3" action="#" method="post" id="form">
                            <div class="text-primary mb-4">
                            <p>Ajouter un commentaire :</p>
                            </div>
                            <select class="custom-select mb-2" name="note">
                            <option value="1" selected>1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            </select>
                            <div class="mb-3 ">
                            <textarea class="form-control message" name="commentaire" id="valider" placeholder="" style="min-height:150px"
                            required></textarea>
                            <div class="invalid-feedback">
                            veuillez entrer un messager !.
                            </div>
                            </div>
                            <div class="col-auto">
                            <button type="submit" name="envoyer" class="btn btn-black my-3 w-100 mx-auto">Envoyer</button>
                            </div>
                            </form> ';
                            echo ' </div>
                            </div>
                            </div>
                            </div>
                            </div>';
                        }
                        
                        ?>    
           
</div>
        <!--FIN CONTENUE-->
        <script>
        //script de mouvement avis:
        $(document).ready(function () {
            $('#lien_avis').on('click', function () {
                $("#avis").toggle("blind");
                $("#avis").css("display","block");
            });  
        });
        //fin.
    </script>

