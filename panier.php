<?php
require_once('inc/init.inc.php');
$feu_rouge = '';
$message ='';
$stock = 0;
$contenu ='';
//ajout d'un produit dans le panier
if(isset($_POST['ajout_panier']) && isset($_SESSION['membre'])){
	$ajout_produit = $pdo->prepare("SELECT s.titre,p.prix FROM salle s,produit p where s.id_salle = p.id_salle and p.id_produit =:id_produit");
	$ajout_produit->bindParam(':id_produit',$_POST['id_produit'],PDO::PARAM_INT);
	$ajout_produit->execute();
	if($ajout_produit->rowCount() > 0)
	{
        //recuperation des donnée des l'articles		
		$article = $ajout_produit->fetch();
		ajout_panier($_POST['id_produit'], $article['titre'],$article['prix'],$_POST['quantite']);
	}
	
	header('location:fiche-produit.php?id_produit='.$_POST['id_produit'].'&statut_produit=ajoute&action=affichage&categorie='.$_POST['categorie']);
	exit();	
}
//vider le panier
if(isset($_GET['action']) && $_GET['action'] == 'vider'){
	unset($_SESSION['panier']);	
}
//supression d'une ligne de panier
if(isset($_GET['action']) && $_GET['action'] == 'suppr' && isset($_GET['id_produit'])){
	retraitDuPanier($_GET['id_produit']);
}
//validation d'un panier( => transformation en commande)
if(isset($_GET['action']) && $_GET['action'] == 'valider'){
	$feu_rouge = 0;
    //controle du panier avant commande 
	for($i=0; $i<count($_SESSION['panier']['id_produit']); $i++){
		$resultat = $pdo->prepare("SELECT * FROM produit p,salle s WHERE p.id_salle = s.id_salle AND id_produit=:id_produit");
		$resultat->bindParam(':id_produit',$_SESSION['panier']['id_produit'][$i],PDO::PARAM_INT);
		$resultat->execute();
		$produit = $resultat->fetch();
		$messsage='';
		if( $_SESSION['panier']['quantite'][$i] > 5)
		{
			$feu_rouge = 1;
		}
		
		if( $_SESSION['panier']['quantite'][$i] > 1)
		{
			$feu_rouge = 1;
		} 
		
		if($_SESSION['panier']['prix'][$i] != $produit['prix']){
			$feu_rouge = 1;
		}	
	}
	if($feu_rouge == 0){
        //on procede à la commande
		$id_membre = $_SESSION['membre']['id_membre'];
		$montant_total = montantTotal();
		$etat = 'en cours d\'enregistrement';
		$insertcom = $pdo->prepare('INSERT INTO commande(id_commande,id_membre,montant,etat,date_enregistrement)VALUES(NULL,:id_membre,:montant,:etat,NOW())');
		$insertcom->bindParam(':id_membre',$id_membre,PDO::PARAM_INT);
		$insertcom->bindParam(':montant',$montant_total,PDO::PARAM_INT);
		$insertcom->bindParam(':etat',$etat,PDO::PARAM_STR);
		$insertcom->execute();
		$id_commande = $pdo->lastInsertId();
        //on boucle le panier
		for($i=0; $i<count($_SESSION['panier']['id_produit']); $i++) {
			$id_produit = $_SESSION['panier']['id_produit'][$i];
			$quantite = $_SESSION['panier']['quantite'][$i];
			$prix = $_SESSION['panier']['prix'][$i];
			$id_salle = 35; 
            //on aliment details commande 
			$resultat1 = $pdo->prepare("INSERT INTO detail_commande VALUES(NULL,:id_commande,:id_produit,:id_salle,:id_membre,:quantite,:prix)");
			$resultat1->bindParam(':id_commande',$id_commande, PDO::PARAM_INT);
			$resultat1->bindParam(':id_produit',$id_produit, PDO::PARAM_INT);
			$resultat1->bindParam(':quantite',$quantite, PDO::PARAM_INT);
			$resultat1->bindParam(':prix',$prix, PDO::PARAM_INT);
			$resultat1->bindParam(':id_membre',$id_membre,PDO::PARAM_INT);
			$resultat1->bindparam(':id_salle',$id_salle, PDO::PARAM_INT);
			$resultat1->execute();
            //on decrementele stock
			$resultat2 = $pdo->prepare('UPDATE produit SET etat = "reservation", quantite="0" WHERE id_produit = :id_produit');
			$resultat2->bindParam(':id_produit',$id_produit,PDO::PARAM_INT);
			$resultat2->execute();
            //apres insertions, on detruit le panier
			unset($_SESSION['panier']);
			
			header('location:profil.php');
			exit();  
		}	   
	}
	else{ 
		$contenu .='<div class="alert alert-danger">
		La commande n\' a pas été validée en raison de modifications concernant le stock ou les prix des produits de votre panier.</div>';
	}   
}
require_once('inc/header.inc.php');
require_once('inc/modal_connexion.inc.php');
require_once('inc/modal_inscription.inc.php');
require_once('inc/nav.inc.php');
?>

	<h2 class="col-8 mx-auto text-center pb-3">Voici votre panier</h2>
	<?php if(empty($_SESSION['panier']['id_produit'])){
		?>
		<div class="alert alert-info col-sm-6 mx-auto" style="min-height:500px; line-height:500px; text-align:center;font-size:2em;">Votre panier est vide :( </div>
			<?php
		}else{
			?>
			<table class="table table-bordered table-striped bg-white  col-xl-10 mx-auto">
				<tr>
					<th>Titre</th>
					<th>Quantité</th>
					<th>Prix unitaire</th>
					<th>Total</th>
					<th>Action</th>
				</tr>
				<?php 
			//controle et réecriture eventuelle du panier
				for($i=0;$i < count($_SESSION['panier']['id_produit']); $i++){
					$resultatpan = $pdo->prepare('SELECT * FROM produit p,salle s WHERE p.id_salle = s.id_salle and p.id_produit=:id_produit');
					$resultatpan->bindParam(':id_produit', $_SESSION['panier']['id_produit'][$i]);
					$resultatpan->execute();
					
					$produit = $resultatpan->fetch();
					if($_SESSION['panier']['quantite'][$i] > 5){
						$_SESSION['panier']['quantite'][$i] = 5;
					}  
				/*
				if($produit['stock'] < $_SESSION['panier']['quantite'][$i])
				{
				$_SESSION['panier']['quantite'][$i] = $produit['stock'];
				$message .='<div class="alert alert-info">La quantité a été réajusté en fonction du stock et dans la limite';
			} */
			
			if($_SESSION['panier']['prix'][$i] != $produit['prix']){
				$_SESSION['panier']['prix'][$i] = $produit['prix'];
				$message .='<div class="alert alert-info">Le prix a été réajusté</div>';    
			} ?>
		<tr>
				<td><?= $produit['titre'] ?></td>
				<td><?= $_SESSION['panier']['quantite'][$i] ?></td>
				<td><?= $_SESSION['panier']['prix'][$i] ?></td>
				<td><?= $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i] ?>€</td>
				<td><a href="?action=suppr&id_produit=<?=$_SESSION['panier']['id_produit'][$i] ?>">&#128465;</a></td>
				<!-- <td><a href="fiche-produit.php?id_produit=<?= $_SESSION['panier']['prix'][$i] ?>"></a></td> -->
			
		</tr>	
		<?php
	}
	?>
	<tr class="info">
		<th colspan="4" class="text-right">Total</th>
		<th colspan="2"><?= montantTotal() ?>€ </th>
	</tr>
	<?php
	if(isAdmin()){
		?>
		<tr>
			<td colspan="6" class="text-center">
				<a href="?action=valider" class="btn btn-primary">Valider le panier</a>
			</td>
		</tr>
		<tr>
		<td colspan="6" class="text-center">
			<a href="?action=vider" class="btn btn-warning">Vider le panier</a>
		</td>
	</tr>
		<?php			
	} ?>

	</table>
</div>
<?php
}	
require_once('inc/modal_mentionslegales.inc.php');
require_once('inc/modal_conditionsdevente.inc.php');
require_once('inc/footer.inc.php');

