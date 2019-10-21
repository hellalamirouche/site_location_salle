<?php
require('../inc/init.inc.php');

$whereclause = '';
$clauseville = '';
$clausecapacite ='';
$clauseprix ='';
$clauseres = '';
$tab_cat = [];
$tab_vil = [];
$tab_cap = [];
$tab_res = [];

foreach($_GET as $key => $value)
{

	if(substr($key,0,3) == 'cat')
	{
		array_push($tab_cat,$value);

		$explode_categ = implode("','",$tab_cat);

		$whereclause="AND s.categorie IN ('$explode_categ')";
		
	}

	if(substr($key,0,3) == 'cap')
	{
		if($value != 0){
			array_push($tab_cap,$value);

			$explode_cap = implode("','",$tab_cap);

			$clausecapacite ="AND s.capacite IN ('$explode_cap')";
		}
		
	}

	if(substr($key,0,3) =='vil')
	{
		array_push($tab_vil,$value);

		$explode_vil = implode("','",$tab_vil);

		$clauseville ="AND s.ville IN ('$explode_vil')";
	}

	if(substr($key,0,3) =='arr')
	{

		$date=date_create($_GET['arrive']);
		$date1=date_create($_GET['arrive1']);
		$hour_arrive = date_format($date,'Y-m-d H:i:s');
		$hour_depart = date_format($date1,'Y-m-d H:i:s');


		$clauseres ="AND ( p.date_arrivee < NOW() OR  p.date_arrivee >= '$hour_arrive' ) AND ( p.date_depart <= '$hour_depart' AND p.date_depart > NOW() )";
	}

	if(substr($key,0,3) =='pri')
	{

		$prix = isset($_GET['prix'])? $_GET['prix'] : '';


		$clauseprix ="AND p.prix BETWEEN 0 AND $prix";
	}



}

$connexion_pdo = $pdo->query("SELECT * FROM produit p, salle s WHERE p.id_salle = s.id_salle $whereclause $clauseville  $clausecapacite $clauseprix $clauseres AND ( p.date_arrivee <= NOW() OR p.date_arrivee > NOW()) AND p.date_depart > NOW() AND p.etat='libre' AND p.quantite='1' ORDER BY p.prix ASC");

if( isset($_GET['categ']) && $_GET['categ'] != '*' ){
	$whereclause=' AND categorie=:categorie';
	$connexion_pdo->bindParam(':categorie',$_GET['categ'],PDO::PARAM_STR);
}

if( isset($_GET['ville']) && $_GET['ville'] != '*' ){
	$clauseville =' AND ville=:ville';
	$connexion_pdo->bindParam(':ville',$_GET['ville'],PDO::PARAM_STR);
}

$connexion_pdo->execute();
?>

<div class="row  col-12 mx-auto px-0 mb-3"  > 
	<?php
	while($produit=$connexion_pdo->fetch(PDO::FETCH_ASSOC)){ 
		?>
		<div class="col-md-6 col-lg-4 p-0 pt-sm-0 px-sm-2 all mb-1 " style="min-height:300px;" >
			<figure class="card card-produit p-3 h-100 img-fluid">
				<div class="img-wrap "><img src="<?php echo URL.'img/'.$produit['photo']?>" alt="salle" class="w-100 img-fluid " style="height:200px"></div>
				<figcaption class="info-wrap ">
					<h4 class="title pt-3"><?php echo $produit['titre']?></h4>
					<div class="rating-wrap">
						<div class="label-rating"><span style="font-weight:bold; color:brown;" >Categorie :</span><?php // affichage du bon orthographe des catégories :
						if($produit['categorie']=='reunion'){echo ' Réunion';} 
						elseif($produit['categorie']=='bureau'){echo ' Bureau';} 
						elseif($produit['categorie']=='formation'){echo ' Formation';}?></div>
						<div class="label-rating"><span style="font-weight:bold; color:brown;" >Capacité :</span><?php echo ' '.$produit['capacite'].' personnes .'?></div>
						<div class="label-rating"><span style="font-weight:bold; color:brown;" >Adresse :</span><?php echo ' '.$produit['adresse']?> </div>
						<div class="label-rating"><span style="font-weight:bold; color:brown;" >Ville :</span><?php echo ' '.$produit['ville']?> </div>
						<div class="label-rating mt-3" style="font-weight:bold; color:brown;">Prix :<span class="text-primary" ><?php echo ' '.$produit['prix'].' €' ?></span> </div>
					</div>		 	
				</figcaption>
				<div class="bottom-wrap">
					<a href="<?php echo URL.'fiche-produit.php?action=affichage&categorie='.$produit['categorie'].'&id_produit='.$produit['id_produit']; ?>" class="btn btn-sm btn-primary float-right">Voir la fiche</a>	
				</div> 
			</figure>		
		</div> 
		<?php	
	} ?> 
</div>

