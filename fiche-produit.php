<?php
require_once('inc/init.inc.php');

//verification de l'existence du produit en base de données.
$produit = $pdo->prepare("SELECT * FROM produit WHERE id_produit =:id_produit ");
$produit->bindParam('id_produit',$_GET['id_produit'], PDO::PARAM_INT);
$produit->execute();
$resultat = $produit->rowCount();

$category = array('reunion','bureau','formation');

if($resultat == 0 || empty($_GET['categorie']) || empty($_GET['action']) || !in_array($_GET['categorie'],$category))
{
    header('location:index.php');
}

// selection des distincts categories  dans la page index.php
$liste_categorie=$pdo->query(" SELECT DISTINCT categorie FROM salle  ORDER BY  categorie  "); // par défaut l ordre est ASC
// affichage des informations du produit :
require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');
require_once('inc/modal_reservation.inc.php');
require_once('inc/modal_connexion.inc.php');
require_once('inc/modal_inscription.inc.php');
echo'<pre>';
echo'</pre>';
?>
<!-- fiche produit -->
<div class="col-xl-10 mx-auto mt-4 mb-4">
    <div class="card">
        <div class="row">
            <?php

            if(isset($_GET['categorie']) && isset($_GET['id_produit'])){
                $fiche_produit=$pdo -> prepare(
                    "   SELECT *
                    FROM salle
                    INNER JOIN produit
                    WHERE salle.id_salle = produit.id_salle
                    AND produit.id_produit = :id_produit
                    "); // un prepare pour éviter les problèmes de sécurité
                $id_produit= $_GET['id_produit'];
                $fiche_produit -> bindParam(':id_produit',$id_produit,PDO::PARAM_STR);
                $fiche_produit->execute();
                    $produit =$fiche_produit->fetchAll(PDO::FETCH_ASSOC); // un fetch du produit pour récuperer toutes les donnée de ce produit .
                    $id_salle=$produit[0]['id_salle'];
                    //la date en format français:           
                    $date_arrivee = new DateTime($produit[0]['date_arrivee']);
                    $date_depart = new DateTime($produit[0]['date_depart']);
                    $date_fr_arrivee =  $date_arrivee->format("d/m/Y");
                    $date_fr_depart = $date_depart->format("d/m/Y"); 

                    // on essai d'afficher les noms de catégories avec le bon orthographe français:
                    //on déclare une variable catégorie vide et elle aura des valeurs selon la valeur de $produit[0]['categorie']
                    $categorie_fr='' ;
                    if($produit[0]['categorie']=='reunion' ){  $categorie_fr='Réunion';} 
                    elseif($produit[0]['categorie']=='bureau') {$categorie_fr='Bureau';}
                    elseif($produit[0]['categorie']=='formation') {  $categorie_fr='Formation';} 
                    // maintenant on va afficher les données de ce produit dans la fiche produit .
                    ?>
             <aside class="col-md-5 border-right pr-0">
                <div class="pl-3 pt-3">
                    <?php   // calcule de la moyenne des notes données pour cette salle :
                        $req_moyenne=$pdo->query("SELECT AVG(a.note), a.active FROM produit p LEFT JOIN salle s ON s.id_salle = p.id_salle LEFT JOIN avis a ON p.id_salle = a.id_salle WHERE p.id_produit = '$id_produit' AND a.active=2");
                            // SELECT  AVG(note) FROM avis  WHERE id_membre=id_membre AND id_produit=id_produit");
                        $moyenne = $req_moyenne->fetch(PDO::FETCH_ASSOC);
                            
                        if($moyenne['AVG(a.note)'] >= 1 && $moyenne['AVG(a.note)']<=1.5){
                            echo'
                                <i class="text-warning fa fa-star"></i>
                                <i class="text-dark fa fa-star"></i>
                                <i class="text-dark fa fa-star"></i>
                                <i class="text-dark fa fa-star"></i>
                                <i class="text-dark fa fa-star"></i>';
                            }
                            elseif($moyenne['AVG(a.note)'] > 1.5 && $moyenne['AVG(a.note)']<=2.5){
                                echo '    <i class="text-warning fa fa-star"></i>
                                <i class="text-warning fa fa-star"></i>
                                <i class="text-dark fa fa-star"></i>
                                <i class="text-dark fa fa-star"></i>
                                <i class="text-dark fa fa-star"></i>';
                            }
                            elseif($moyenne['AVG(a.note)'] > 2.5 && $moyenne['AVG(a.note)']<=3.5){
                                echo '<i class="text-warning fa fa-star"></i>
                                <i class="text-warning fa fa-star"></i>
                                <i class="text-warning fa fa-star"></i>
                                <i class="text-dark fa fa-star"></i>
                                <i class="text-dark fa fa-star"></i>';
                            }
                            elseif($moyenne['AVG(a.note)'] > 3.5 && $moyenne['AVG(a.note)']<=4.5){
                                echo' <i class="text-warning fa fa-star"></i>
                                <i class="text-warning fa fa-star"></i>
                                <i class="text-warning fa fa-star"></i>
                                <i class="text-warning fa fa-star"></i>
                                <i class="text-dark fa fa-star"></i>';
                            }
                            elseif($moyenne['AVG(a.note)'] > 4.5){
                                echo'<i class="text-warning fa fa-star"></i>
                                <i class="text-warning fa fa-star"></i>
                                <i class="text-warning fa fa-star"></i>
                                <i class="text-warning fa fa-star"></i>
                                <i class="text-warning fa fa-star"></i>';
                            }
                            else{
                                echo' <span class="text-primary"> Pas encore notée </span>';
                            }
                            if($moyenne['AVG(a.note)']!=0){
                            echo ' ('.number_format($moyenne['AVG(a.note)'],2).')'; // pour limiter le nombre de chiffres apres la virgule.
                        } else { echo '';}
                            // verification de la maps :
                        $maps = str_replace(' ', '+', $produit[0]['adresse']) . '%2C+' . $produit[0]['cp'] . '+' . $produit[0]['ville'];
                        ?>
                    </div>
                        <a  href="#" data-toggle="modal" data-target="#modal-photo"><img id="image-produit" src="<?php echo URL.$lien_image.$produit[0]['photo'] ?>" alt="photos de la salle principale" class="p-3 mx-auto img-fluid w-100"></a>
                        <hr>
                        <div class="col-md-12 mx-auto">
                             <p>Localisation</p>
                            <a href="#" class=" text-uppercase" data-toggle="modal" data-target="#exampleModalCenter"> <img src="https://img.icons8.com/color/48/000000/google-maps.png" alt="maps-google"> voir la localisation</a>
                        </div>
                        <hr class="mb-0">
                        <div style="background-color:#7f4f4f; " class="p-2 m-0 w-100 col-11 col-sm-11 col-md-12 mx-auto ">
                            <p class="text-center pb-2 text-white"> Disponibilités de la salle </p>
                            <div class="col-md-8 mx-auto input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="arrivee">Arrivée</span>
                            </div>
                            <input type="text" class="form-control text-success text-center" aria-label="Default" value="<?php echo $date_fr_arrivee; ?>">
                        </div>
                        <div class="col-md-8 mx-auto input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="depart">Départ</span>
                            </div>
                            <input type="text" class="form-control text-success text-center" aria-label="Default"  value="<?php echo $date_fr_depart; ?>">
                        </div>
                    </div>
                </aside>
                <aside class="col-md-7 px-0">
                    <article class="card-body p-5">
                        <h3 class="title mb-3"><?php echo $produit[0]['titre'] ?> </h3>
                        <p class="price-detail-wrap">
                            <span class="price h3 text-warning">
                                <span class="currency"></span><span class="num"><?php echo $produit[0]['prix'] .' € ' ?></span>
                            </span>
                            <span>/ Jour</span>
                        </p>
                        <dl class="item-property">
                            <dt>Description</dt>
                            <dd>
                                <p> <?php echo $produit[0]['description'] ?></p>
                            </dd>
                        </dl>
                        <dl class="param param-feature">
                            <dt>capacité</dt>
                            <dd>
                                <?php echo $produit[0]['capacite'] ?>
                            </dd>
                        </dl>
                        <dl class="param param-feature">
                            <dt>Catégorie</dt>
                            <dd>
                                <?php echo $categorie_fr;?>
                            </dd>
                        </dl>
                        <dl class="param param-feature">
                            <dt>Adresse</dt>
                            <dd><?php echo $produit[0]['adresse'].'<br>'.$produit[0]['cp'].' '.$produit[0]['ville'] ;?></dd>
                        </dl>
                        <hr>
                        <form  method='post' action='panier.php'>
                            <input type="hidden" name="id_produit" value="<?php echo $produit[0]['id_produit'] ?>">
                            <input type="hidden" name="titre" value="<?php  echo $produit[0]['titre'] ?>">
                            <input type="hidden" name="prix" value="<?php echo $produit[0]['prix'] ?>">
                            <input type="hidden" name="categorie" value="<?php echo $_GET['categorie'] ?>">
                            <input type="hidden" name="id_salle" value="<?php echo $produit[0]['id_salle'] ?>">
                            <input type="hidden" name="quantite" value="1">
                            <?php
                                    if (isconnected()){ // si l utilisateur est connecté
                                        echo' <input type="submit" name="ajout_panier" class="btn btn-lg btn-outline-dark text-uppercase col-sm-12" value="ajouter au panier"/> ';
                                    }
                                    // si l'utilisateur n'est pas connecté
                                    if (!isconnected()){
                                        echo'<input id="trigger-connexion"   class="btn btn-lg btn-outline-dark text-uppercase col-sm-12 mb-2" value="connecte pour réserver" style="background-color:#d6cfcf ;"/> ';
                                        echo'<div><a id="trigger-inscription"  href="#triggger-inscription" class=" col-sm-12 mt-3" > inscrivez_vous!</a></div> ';
                                    }
                            ?>
                        </form>
                        <div class="row col-md-12 mx-auto mt-3">
                            <p class="col-md-7"></p>
                            <a href="#" class=" text-uppercase text-danger col-md-5 "  id="lien_avis"> Afficher les Avis</a>
                        </div>
                            <?php }
                         ?>
                    </article>
                </aside>
            
        </div>
    </div>    
      <?php
        require_once('avis.php');
      ?> 
</div>


     <!-- Autres produits  -->
     <?php
    if(isset($_GET['categorie'])){
        $categorie= $_GET['categorie'];
        $liste_categorie=$pdo->prepare(
            " SELECT *
            FROM salle
            INNER JOIN produit
            WHERE salle.id_salle = produit.id_salle
            AND salle.categorie = :categorie
            AND produit.id_produit != :id_produit ");
        $liste_categorie -> bindParam(':categorie',$categorie,PDO::PARAM_STR);
        $liste_categorie -> bindParam(':id_produit',$id_produit,PDO::PARAM_INT);
        $liste_categorie->execute(); ?>
        <div class="row mt-4 col-xl-10 px-0 mx-auto px-0 " style="background-color:#523131;">
            <h3 class="col-12 text-white p-2 mb-2 pb-2">Plus de Salles <?php echo $categorie_fr .' : '; ?> </h3>
            <?php
            while($distinct_categorie =$liste_categorie->fetchAll(PDO::FETCH_ASSOC)){
                for( $i=0;$i<count($distinct_categorie);$i++){  ?>
                    <div class=" col-sm-10 mx-sm-auto col-md-6 col-lg-4 mb-2">
                        <div class="card card-produit p-3">
                            <div class="img-wrap "><img src="<?php echo URL . $lien_image . $distinct_categorie[$i]['photo'] ?>" alt="salle" class="w-100 img-fluid m-0 " style="height:250px"></div>
                            <div class="info-wrap">
                                <h4 class="title pt-3"><?php echo $distinct_categorie[$i]['titre'] ?></h4>
                                <div class="rating-wrap">
                                    <div class="rating-wrap">
                                        <div class="label-rating"><span style="font-weight:bold; color:brown;" >Categorie :</span>
                                            <?php if($distinct_categorie[$i]['categorie']=='reunion'){echo 'Réunion';} 
                                            elseif($distinct_categorie[$i]['categorie']=='bureau'){echo 'Bureau';} 
                                            elseif($distinct_categorie[$i]['categorie']=='formation'){echo 'Formation';}?>
                                        </div>
                                        <div class="label-rating"><span style="font-weight:bold; color:brown;" >Capacité :</span><?php echo ' '.$distinct_categorie[$i]['capacite'].' personnes .'?></div>
                                        <div class="label-rating"><span style="font-weight:bold; color:brown;" >Adresse :</span><?php echo ' '.$distinct_categorie[$i]['adresse'].' '.'<br>'.$distinct_categorie[$i]['cp'].' '.$distinct_categorie[$i]['ville'].' .';?> </div>
                                    </div>
                                </div>
                                <div class="bottom-wrap">
                                    <a href="?action=affichage&categorie=<?php echo $distinct_categorie[$i]['categorie'] ?>&id_produit=<?php echo $distinct_categorie[$i]['id_produit'] ?>" class="btn btn-sm btn-dark float-right">Voir la fiche</a>
                                    <div class="price-wrap h5">
                                        <p style="font-weight:bold; color:brown;" >Prix : <span class="text-dark"><?php echo ' '.$distinct_categorie[$i]['prix'].' €' ?></span> </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        <?php
                    }
                }
            }
            ?>
        </div> <!-- row.// -->
    
    <!--container.//-->
    <?php
    if ( isset($_GET['statut_produit']) && $_GET['statut_produit'] == 'ajoute'){
        ?>
        <div class="modal fade" id="maModale" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Le produit a bien été ajouté au panier</h4>
                    </div>
                    <div class="modal-body">
                        <a class="btn btn-dark"  href="<?= URL . 'panier.php' ?>">Voir le panier</a>
                        <a class="btn btn-dark" href="<?= URL ?>">Continuer ses achats</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } ?>
    <!-- modale de la maps -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" >
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="background-color:#c5b6b6">
                <div class="modal-header">
                    <h5 class=" row modal-title mx-auto" id="exampleModalCenterTitle"><?php echo $produit[0]['titre']; ?></h5>
                    <button type="button" class="close text-danger m-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2625.767832714858!2d2.294586415902588!3d48.843567009703996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e67016472aab37%3A0x7f5783172b32028a!2s<?= $maps; ?>!5e0!3m2!1sfr!2sfr!4v1550225895068" class="w-100" height="250"  style="border:0" allowfullscreen></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary " data-dismiss="modal" style="background-color:#753030">Sortir</button>
                </div>
            </div>
        </div>
    </div>
    <!-- modal pour voir la photos produit -->
    <div class="container">      
        <!-- The Modal -->
        <div class="modal fade" id="modal-photo">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header" style="background-color:black;">
                        <h4 class="modal-title text-center w-100 text-white " ><?php echo 'Salle ' . $produit[0]['titre'] ?></h4>
                        <a href="#" class="text-white display-4 close"   data-dismiss="modal" style="text-decoration: none;">&times;</a>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <img  src="<?php echo URL.$lien_image.$produit[0]['photo'] ?>" alt="photos de la salle principale" class="p-3 mx-auto img-fluid w-100">
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- script pour le triger  -->
    <script>
        jQuery(function($){
            $('#connexion').click(function(e){
            });
            $('#trigger-connexion').click(function(e){
                $('#connexion').trigger('click'); // équivalent de  $('#lien1').click();
            });
        });
        // pour la modale inscription pour l'appeler dans la fiche produit avec trigger
        jQuery(function($){
            $('#trigger-fiche').click(function(e){
            
             }); 
            $('#trigger-inscription').click(function(e){
                $('#trigger-fiche').trigger('click'); // équivalent de  $('#lien1').click();
            });
        }); 
    </script>
</div>
    <?php
    require_once('inc/modal_mentionslegales.inc.php');
    require_once('inc/modal_conditionsdevente.inc.php');
    require_once('inc/footer.inc.php');
    ?>


