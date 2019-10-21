<?php 
require_once('../inc/init.inc.php');
if(!isAdmin()){
	header("location:".URL.'../index.php');
	exit();	
}
require_once('../inc/header.inc.php');
require_once('../inc/nav.inc.php');
$msg="";
//---------------------------------------requete d'ajout de membre--------------------------------------------------//
if(isset($_POST['validation'])){
	if(isset($_GET['action'])&& $_GET['action'] == 'ajouter'){
		$recup_membre =  $pdo->prepare('SELECT * FROM membre WHERE id_membre =:id_membre');
		$recup_membre->bindParam(':id_membre', $_GET['id_membre'],PDO::PARAM_STR);
		$recup_membre->execute();
		print_r($_POST);
		if(isset($_POST['pseudo']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) &&isset($_POST['civilite']) && isset($_POST['statut'])){
			$mdp='';
			if(isset($_POST['civilite'])){
				if($_POST['civilite'] == 'femme'){
					$_POST['civilite'] = 'f';
				}else{
					$_POST['civilite'] = 'm';
				}
			}
			if(isset($_POST['statut'])){

				if($_POST['statut'] == 'Administrateur')
				{
					$_POST['statut'] = 2;
				}else{
					$_POST['statut'] = 1;
				}	
			}
		}
		$mdp = password_hash( $_POST['mdp'],PASSWORD_DEFAULT);
		$insert_membre = $pdo->prepare('INSERT INTO membre(pseudo,mdp,nom,prenom,email,civilite,statut,date_enregistrement) VALUES(:pseudo,:mdp,:nom,:prenom,:email,:civilite,:statut,NOW())');
		$insert_membre->bindParam(':pseudo', $_POST['pseudo'],PDO::PARAM_STR);
		$insert_membre->bindParam(':mdp',$mdp,PDO::PARAM_STR);
		$insert_membre->bindParam(':nom',$_POST['nom'],PDO::PARAM_STR);
		$insert_membre->bindParam(':prenom', $_POST['prenom'],PDO::PARAM_STR);
		$insert_membre->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
		$insert_membre->bindParam(':civilite',$_POST['civilite'], PDO::PARAM_STR);
		$insert_membre->bindParam(':statut', $_POST['statut'],PDO::PARAM_STR);
		$insert_membre->execute();
		require_once("../mails/mail_inscription.php");
			//vider les variables...
		$_POST['pseudo'] = '';
		$_POST['mdp'] = '';
		$_POST['nom'] = '';
		$_POST['prenom'] = '';
		$_POST['email'] = '';
		$_POST['civilite'] = '';
		$_POST['statut'] = '';

		$msg .='<div class="bg-success">Le membre a bien été ajouté en base de donnée</div>';	
	}
}	
//---------------------------------------requete de modification--------------------------------------------------//
if(isset($_GET['action'])&& $_GET['action'] == 'modification'){
	$recup_membre =  $pdo->prepare('SELECT * FROM membre WHERE id_membre =:id_membre');
	$recup_membre->bindParam(':id_membre', $_GET['id_membre'],PDO::PARAM_STR);
	$recup_membre->execute();
	if($recup_membre->rowCount()>0)
	{
		$membre_actuel = $recup_membre->fetch(PDO::FETCH_ASSOC);
		$id_membre = trim($membre_actuel['id_membre']);
		$pseudo = trim($membre_actuel['pseudo']);
		$nom = trim($membre_actuel['nom']);
		$prenom = trim($membre_actuel['prenom']);
		$email = trim($membre_actuel['email']);
		$civilite = trim($membre_actuel['civilite']);
		$status = trim($membre_actuel['statut']);
		$date_enregistrement = trim($membre_actuel['date_enregistrement']);
		if(isset($_POST['pseudo']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) &&isset($_POST['civilite']) && isset($_POST['statut'])){
			if(isset($_POST['civilite'])){
				if($_POST['civilite'] == 'femme'){
					$_POST['civilite'] = 'f';
				}else{
					$_POST['civilite'] = 'm';
				}
			}
			if(isset($_POST['statut'])){

				if($_POST['statut'] == 'Administrateur')
				{
					$_POST['statut'] = 2;
				}else{
					$_POST['statut'] = 1;
				}	
			}
			if(isset($id_membre)){
				$insert_membre = $pdo->prepare('UPDATE membre SET pseudo = :pseudo,nom = :nom,prenom = :prenom,email = :email,civilite =:civilite, statut = :statut WHERE id_membre = :id_membre');
				$insert_membre->bindParam(':id_membre',$id_membre,PDO::PARAM_INT);
				$insert_membre->bindParam(':pseudo', $_POST['pseudo'],PDO::PARAM_STR);
				$insert_membre->bindParam(':nom',$_POST['nom'],PDO::PARAM_STR);
				$insert_membre->bindParam(':prenom', $_POST['prenom'],PDO::PARAM_STR);
				$insert_membre->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
				$insert_membre->bindParam(':civilite',$_POST['civilite'], PDO::PARAM_STR);
				$insert_membre->bindParam(':statut', $_POST['statut'],PDO::PARAM_STR);
				$insert_membre->execute();
			}
			$msg .='<div class="bg-success">Le membre '.$_POST["pseudo"].'a  bien été modifié</div>';	
		}
	}	
}
//---------------------------------------requete de suppression--------------------------------------------------//
if(isset($_GET['action']) && $_GET['action'] == 'suppression' && (!empty($_GET['id_membre'])))
{
	$sup_membre = $pdo->prepare('DELETE FROM membre where id_membre=:id_membre');
	$sup_membre->bindParam(':id_membre',$_GET['id_membre'],PDO::PARAM_INT);
	$sup_membre->execute();
//message de suppression de membre valide
	$msg .='<div class="bg-success">Le membre a bien été supprimé</div>';
}
//code de pagination
$limite = 4;
$page = (!empty($_GET['page'])? $_GET['page']: 1);
$debut = ($page -1)* $limite;
$employees = $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM membre ORDER BY id_membre DESC LIMIT :limite OFFSET :debut");
$employees->bindParam(':limite',$limite,PDO::PARAM_INT);
$employees->bindParam(':debut',$debut,PDO::PARAM_INT);
$employees->execute();
$resultfound = $pdo->query('SELECT found_rows()');
$nombretotal = $resultfound->fetchColumn();
//creation de la premiere ligne du tableau
echo $msg; 
echo'<table class="table table-bordered bg-white">
<thead class="thead-dark">
<tr>
<th>ID</th>
<th>Pseudo</th>
<th>Nom</th>
<th>Prenom</th>
<th>Email</th>
<th>civilite</th>
<th>statut</th>
<th>date_enregistrement</th>
<th>modifier</th>
<th>suppimer</th>
</tr>';
echo'</thead>';
while($membres=$employees->fetch(PDO::FETCH_ASSOC)){
	echo '<tr>' ;
	foreach($membres as $indice=>$valeur)
	{
		if($indice == 'mdp'){
			continue;
		}elseif($indice == 'civilite'){

			if($valeur == 'f'){
				$valeur ="Femme";
				echo '<td>'.$valeur.'</td>';	
			}else{
				$valeur = 'Homme';
				echo '<td>'.$valeur.'</td>';
			}
		}elseif($indice == 'statut'){
			if($valeur == '1'){
				$valeur = 'Client';
				echo '<td>'.$valeur.'</td>';	
			}else{
				$valeur = 'Administrateur';
				echo '<td>'.$valeur.'</td>';
			}		
		}else{
			echo '<td>'.$valeur.'</td>';
		}
	}
	echo '<td><a href="?page='.$_GET["page"].'&action=modification&id_membre='.$membres['id_membre'].'" class="btn btn-success text-white"  > <i class="fas fa-edit"></i></a>  </td>';
	echo '<td> <a href="?page='.$_GET["page"].'&action=suppression&id_membre='.$membres['id_membre'].'" class="btn btn-danger text-white" onclick="(confirm(\'Etes-vous sûr ? \'));"> <i class="fas fa-trash-alt"></i></a> </td>';
	echo '</tr>' ;
}
echo '</table>';
//pagination partie 2
// Partie "Liens"
/* On calcule le nombre de pages */
$nombreDePages = ceil($nombretotal / $limite);
/* On va effectuer une boucle autant de fois que l'on a de pages */
echo'<nav aria-label="...">
<ul class="pagination pagination-sm">';
for ($i = 1; $i <= $nombreDePages; $i++):
	?><li class='page-item <?php if($i == $_GET["page"]){ echo "active";} ?>'><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li> <?php
endfor;
echo '</ul>
</nav>';
?>
<a href='?page=<?php echo $_GET["page"] ?>&action=ajouter' class="btn btn-primary">ajouter un Membre</a>
<!--creation du formulaire-->
<form action="#" method="post" class="col-xl-10 mx-auto">
	<div class="form-group">
		<label for="pseudo">Pseudo</label>
		<input type="text" id="pseudo" name="pseudo" value="<?php if(isset($_POST['pseudo'])){echo $_POST['pseudo'];}elseif(isset($pseudo)){echo $pseudo;}else{ echo $_POST['pseudo'] = '';} ?>" class="form-control">
	</div>
	<?php if(isset($_GET['action']) && $_GET['action'] == 'ajouter'){?>
		<div class="form-group">
			<label for="mdp">Mot de passe</label>
			<input type="password" name="mdp" id="mdp" value="<?php if(isset($_POST['mdp'])){echo $_POST['mdp'];}elseif(isset($mdp)){echo $mdp;}else{ echo $_POST['mdp'] = '';} ?>" class="form-control">
		</div>
	<?php } ?>
	<div class="form-group">
		<label for="prenom">Prenom</label>
		<input type="text" id="prenom" name="prenom" value="<?php if(isset($_POST['prenom'])){echo $_POST['prenom'];}elseif(isset($prenom)){echo $prenom;}else{echo $_POST['prenom'] ='';} ?>" class="form-control">
	</div>
	<div class="form-group">
		<label for="nom">Nom</label>
		<input type="text" id="nom" name="nom" value="<?php if(isset($_POST['nom'])){ echo $_POST['nom'];}elseif(isset($nom)){echo $nom;}else{echo $_POST['nom']='';} ?>" class="form-control">
	</div>
	<div class="form-group">
		<label for="email">Email</label>
		<input type="email" id="email" name="email" value="<?php if(isset($_POST['email'])){ echo $_POST['email'];}elseif(isset($email)){echo $email;}else{ echo $_POST['email'] =''; } ?>" class="form-control">
	</div>
	<div class="input-group mb-3">
		<div class="input-group-prepend">
			<label class="input-group-text" for="inputGroupSelect01">Civilite</label>
		</div>	
		<select class="custom-select" id="inputGroupSelect01" name="civilite">
			<option <?php if(isset($civilite) && $civilite == 'h' || empty($_POST['civilite'])){echo 'selected';} ?>>Homme</option>
			<option <?php if(isset($civilite) && $civilite == 'f'){echo 'selected';} ?>>femme</option>
			<option value='f'>Femme</option>
		</select>
	</div>
	<div class="input-group mb-3">
		<div class="input-group-prepend">
			<label class="input-group-text" for="inputGroupSelect01">Statut</label>
		</div>
		<select class='custom-select' id='inputGroupSelect02' name="statut">
			<option <?php if(isset($status) && $status =="2"){echo 'selected';} ?>>Admin</option>
			<option <?php if(isset($status) && $status == "1" || empty($_POST['statut'])){echo 'selected';} ?>>client</option>
		</select>	
	</div>
	<input type="submit" class="btn btn-primary" value="soumettre" name="validation">
	<?php if(isset($_GET['action']) && $_GET['action'] == 'ajouter'){ ?>
		<input type="reset" name="reset" value="reset" class="btn btn-warning"> 	
	<?php } ?>	
</form>	
<?php
//inclusion des éléments:
require_once('../inc/modal_mentionslegales.inc.php');
require_once('../inc/modal_conditionsdevente.inc.php');
require_once('../inc/footer.inc.php');
