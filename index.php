<?php
require_once('inc/init.inc.php');
require_once('inc/header.inc.php');
require_once('inc/modal_connexion.inc.php');
require_once('inc/modal_inscription.inc.php');
require_once('inc/nav.inc.php');
// selection des distincts categories  dans la page index.php 
$liste_categorie=$pdo->query(" SELECT DISTINCT categorie FROM salle  ORDER BY  categorie  "); // par défaut l ordre est ASC
$liste_ville=$pdo->query("SELECT DISTINCT ville FROM salle ORDER BY ville"); // liste des villes 
$liste_capacite = $pdo->query("SELECT DISTINCT capacite FROM salle ORDER BY capacite"); // liste des capacites
//recuperation de tout les salles dans la base de données .
?>
<div class="container pb-4">
	<?php if(isconnected()){ // si l'utilisateur est connecté fais moi un message de bienvenu avec son nom et prénom : ?> 
	<h3 class="text-center pt-4">Bienvenue</h3> <br> <p class="text-warning text-center display-4"><?php if(isset($_SESSION['membre']['nom'])){echo $_SESSION['membre']['nom'];}else{ echo"";}
	if(isset($_SESSION['membre']['prenom'])){ echo ' '.$_SESSION['membre']['prenom'];}else{ echo""; }  echo'</p>';      
}
else{ 
	echo '<h1 class="text-center pt-4"> Sallea</h1>';
}
?>
<hr>
</div>
<div class="col-xl-11 mx-auto row  mt-0">
	<div class=" col-sm-4 col-md-3 px-0  mx-auto mb-3">
		<form action="<?php  echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="row col-12 rounded mx-auto mb-2 form" style=background-color:#251f1f;>
			<div class="form-group col-11 mx-auto">
				<label for="startDate3" class="text-success pt-2">Date d'arrivée</label>
				<input type="text" name="arrive"  id="startDate3" value="" class="form-control datepicker">
			</div>
			<div class="form-group col-11 mx-auto  ">
				<label for="endDate3"  class="text-danger">Date de départ</label>
				<input type="text" id="endDate3" name="depart" value="" class="form-control datepickerd">   
			</div>
			<input type="reset" value="vider les champs de dates" class="col-10 mx-auto mb-3 rounded" style="background-color:#d49a9a;">  
		</form>
		<form action="<?php  echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="col-12 p-0 mb-2 form"> 
			<select id="choix" name='capacite' class="form-control form-select text-white" style="background-color:#350101; font-size:1em">
				<option selected='selected' value='0' style="background-color:grey;">Faites votre choix</option><?php
				while ($capacite=$liste_capacite->fetch(PDO::FETCH_ASSOC)){
					echo"<option value=".$capacite['capacite']." class='capacite' style='background-color:grey;'>$capacite[capacite]</option>";
				} 
				?>
			</select>
			<div>
				<label for="start">Recherche par Prix</label>
			</div>
			<div><p id="prix" class="text-center"></p>
				<input type="range" id="start" name="prix" min="0" max="5000" class="w-100"  value="0"  step="1">
				
			</div>
		</form>  
		<form action="<?php  echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="col-12 p-0 mb-2 form">   
			<ul class="  row col-12 p-0 m-0 w-100 list-group mb-2">
				<li class="list-group-item text-white" style="background-color:#4c2929;" >Villes</li>
					<?php 
                    // affichage de toutes  les catégories de salles :
						while ($ville=$liste_ville->fetch(PDO::FETCH_ASSOC)){   
                        //echo '<li class="list-group-item" > <a href="?ville='.$ville['ville'] .'">'.$ville['ville'] .'</a></li>'; // avec le href="?categorie='.$cat['categorie'] .'" on récupere a chaque fois avec le GET l'élément qu on a cliquer dans l'url ?categorie c'est un indice qu on donne arbitrairement , mais le plus approprié ici
						echo "<li class='list-group-item' style='height:50px;'>$ville[ville]<input type='checkbox' name=$ville[ville]  value=$ville[ville]  class='ville ml-4 my-auto'></li>";
							}
					?>
				<li class="list-group-item" style='height:50px; '> <a href="<?php echo URL.'index.php' //pour afficher toutes les villes ?>"> Toutes les villes</a></li> 
			</ul> 
		</form>
		<form action="<?php  echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="col-12 p-0 mb-2 form"> 

			<ul class="  row col-12 p-0 m-0 w-100 list-group text-white">
				<li class="list-group-item text-white" style="background-color:#4c2929;" >Catégories</li>
					<?php 
                    // affichage de toutes  les catégories de salles :
						while ($cat=$liste_categorie->fetch(PDO::FETCH_ASSOC)){   
                        //echo '<li class="list-group-item" > <a href="?categorie='.$cat['categorie'] .'">'.$cat['categorie'] .'</a></li>';
                        // avec le href="?categorie='.$cat['categorie'] .'" on récupere a chaque fois avec le GET l'élément qu on a cliquer dans l'url ?categorie c'est un indice qu on donne arbitrairement , mais le plus approprié ici
					    echo "<li class='list-group-item text-dark' style='height:50px;'>$cat[categorie]<input type='checkbox' name=$cat[categorie] class='category ml-4 my-auto' value=$cat[categorie] ></li>";
							}
					?>
				<li class="list-group-item" style='height:50px;' > <a href="<?php echo URL.'index.php' //pour afficher toutes les categories ?>"> Toutes les salles</a></li>
			</ul> 
		</form> 
	</div>

<!-- affichage de tout les produits  -->

<div class="row  col-sm-8  col-md-9 mx-auto px-0 mb-3"  >
	<div id="cover" style="width: 100%; height:100%;text-align-center;display:none"></div>
		<div id='result' class="col-12 mx-auto w-100"></div>  

		<?php
	    $liste_produit=$pdo->query('SELECT * FROM produit p, salle s WHERE p.id_salle = s.id_salle AND p.etat="libre" AND ( p.date_arrivee <= NOW() OR p.date_arrivee > NOW()) AND p.date_depart > NOW() AND p.quantite="1" ');
		while($produit=$liste_produit->fetch(PDO:: FETCH_ASSOC)){  
		$description=$produit['description'];
		?>
			<div class=" p-0 p-sm-2 col-md-6 col-lg-4  pt-sm-0  all mb-1 " style="min-height:300px;" >
				<div class="card card-produit p-3 h-100 img-fluid">
					<div class="img-wrap "><img src="<?php echo URL.'img/'.$produit['photo']?>" alt="salle" class="w-100 img-fluid " style="height:200px"></div>
						<div class="info-wrap ">
							<h4 class="title pt-3"><?php echo $produit['titre']?></h4>
							<div class="rating-wrap">
				            <div class="label-rating">
							<span style="font-weight:bold; color:brown;" >Categorie :</span>
                                                    <?php // affichage du bon orthographe des catégories :
                                                    if($produit['categorie']=='reunion'){echo 'Réunion';} 
                                                    elseif($produit['categorie']=='bureau'){echo 'Bureau';} 
                                                    elseif($produit['categorie']=='formation'){echo 'Formation';}?></div>
                            <div class="label-rating"><span style="font-weight:bold; color:brown;" >Capacité :</span><?php echo ' '.$produit['capacite'].' personnes .'?></div>
                            <div class="label-rating"><span style="font-weight:bold; color:brown;" >Adresse :</span><?php echo ' '.$produit['adresse'] . ' '.$produit['cp']?> </div>
                            <div class="label-rating"><span style="font-weight:bold; color:brown;" >Ville :</span><?php echo ' '.$produit['ville']?> </div>
                            <div class="label-rating mt-3" style="font-weight:bold; color:brown;">Prix :<span class="text-primary" ><?php echo ' '.$produit['prix'].' €' ?></span> </div>
                        </div> 
                    </div>
                        <div class="bottom-wrap">
                            <a href="<?php echo URL.'fiche-produit.php?action=affichage&categorie='.$produit['categorie'].'&id_produit='.$produit['id_produit']; ?>" class="btn btn-sm btn-primary float-right">Voir la fiche</a>	
                        </div> 
                </div>
            </div> 
                                                      <?php  } //fermeture de la boucle ?> 
    </div>
</div>
<script src="<?php echo URL.'js/traitement_ajax.js' ?>"></script>
<?php
require_once('inc/modal_mentionslegales.inc.php');
require_once('inc/modal_conditionsdevente.inc.php');
require_once('inc/footer.inc.php');
?>