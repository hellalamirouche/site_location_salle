<?php
require_once('../inc/init.inc.php');

//pour la sécurité:  //personne ne sera ici c pas admin
if (!isAdmin()){

    header("location:" . URL . "../index.php");  // si l'utilisateur n'est pas admin il ne poura pas rentrer dans cette page il ne pourra pas s'amuser sur l url grace à l'exit()
    exit();//provoque une erreur fatal et bloc l execution du script  
}

require_once('../inc/header.inc.php');
require_once('../inc/nav.inc.php');
// selection des salles les mieux notées:
$req1=$pdo->query("SELECT s.titre,ville,AVG(a.note) as note_moyenne FROM salle s LEFT JOIN avis a ON s.id_salle=a.id_salle GROUP BY s.titre ORDER BY AVG(a.note) DESC limit 5");
// les salles les plus commandées :
$req2=$pdo->query("SELECT s.titre,ville,COUNT(d.id_commande) FROM salle s LEFT JOIN detail_commande d ON s.id_salle=d.id_salle GROUP BY s.titre ORDER BY COUNT(d.id_commande) DESC limit 5");
// le membre qui achete le plus :
$req3=$pdo->query("SELECT m.id_membre,m.nom,m.prenom,COUNT(d.id_commande) FROM membre m LEFT JOIN detail_commande d ON m.id_membre=d.id_membre GROUP BY m.nom ORDER BY COUNT(d.id_commande) DESC limit 5");
// top 5 des membres qui achetent le plus cher :
$req4=$pdo->query("SELECT m.id_membre,m.nom,m.prenom,COUNT(c.id_commande) as nombre_commande ,SUM(c.montant) as total_achat FROM membre m LEFT JOIN commande c ON m.id_membre=c.id_membre GROUP BY m.nom ORDER BY total_achat DESC limit 5");
?>
<div class="container mx-auto m-4 pb-4 " style="background-color:#4c3e3e;">
    <div class="card row ">
        <div class="card-header col-sm-12 mx-auto">
            Les statistiques de la boutique :
        </div>
        <div class="card-body row col-12 mx-auto p-0">
            <a  href="#notes" class="  col-md-6 btn btn-info p-2 my-3"> Salles les mieux notées :</a>
            <a  href="#commande" class=" col-md-6 btn btn-warning p-2 my-3"> Salles les plus commandées :</a>
            <a  href="#achat" class="col-md-6 btn btn-danger p-2 my-3"> Les membres qui achetent le plus :</a>
            <a  href="#plus_cher" class="col-md-6 btn btn-primary p-2 my-3"> Les membres qui achetent le plus cher :</a>
        </div>
    </div>
    <?php
    // les salles les mieux notées 
    echo '<button type="button" class="btn btn-info p-2 my-3" id="note"> Salles les mieux notées :</button>'; 
    echo '<table class=" p-0 table-striped table-bordered text-center table mx-auto bg-white">';
    echo '<tr style="background-color:#d6934e;">
    <th>Salle</th>
    <th>Localisation</th>
    <th>Note</th>
    </tr>';
    while($meilleurs_note = $req1->fetch(PDO::FETCH_ASSOC)){
        echo '<tr>';
        echo '<td>'.$meilleurs_note['titre'].'</td>';
        echo '<td>'.$meilleurs_note['ville'].'</td>';
        if ($meilleurs_note['note_moyenne']>0){
            echo '<td><span class="badge badge-primary p-2">'.number_format($meilleurs_note['note_moyenne'],1).'</span></td>';
        } 
        else{
            echo '<td class="text-primary"> Pas de notes </td>'; }
            echo '</tr>';
            // echo print_r($meilleurs_note);
        }
        echo '</table>'; 
        // les salles les plus commandées 
        echo '<button type="button" class="btn btn-warning p-2 my-3" id="commande"> Salles les plus commandées :</button>';
        echo '<table class=" p-0 table-striped table-bordered text-center table mx-auto bg-white">';
        echo
        '<tr style="background-color:#d6934e;" >
        <th>Salle</th>
        <th>Localisation</th>
        <th>Nombre de Commandes</th>
        </tr>';
        while($plus_commandees = $req2->fetch(PDO::FETCH_ASSOC)){
            
            echo '<tr>';
            echo '<td>'.$plus_commandees['titre'].'</td>';
            echo '<td>'.$plus_commandees['ville'].'</td>';
            echo '<td><span class="badge badge-warning p-2">'.$plus_commandees['COUNT(d.id_commande)'].'</span></td>';
            echo '</tr>';
            // echo print_r($meilleurs_note);
        }
        echo '</table>'; 
        // les membres qui achetent le plus 
        echo '<button type="button" class="btn btn-danger p-2 my-3" id="achat"> Les membres qui achetent le plus :</button>';
        echo '<table class=" p-0 table-striped table-bordered text-center table mx-auto bg-white">';
        echo
        '<tr style="background-color:#d6934e;">
        <th>Id_membre</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Nombre d\'achats</th>
        </tr>';
        while($membre_achete_plus = $req3->fetch(PDO::FETCH_ASSOC)){ 
            echo '<tr>';
            echo '<td>'.$membre_achete_plus['id_membre'].'</td>';
            echo '<td>'.$membre_achete_plus['nom'].'</td>';
            echo '<td>'.$membre_achete_plus['prenom'].'</td>';
            echo '<td><span class="badge badge-danger p-2">'.$membre_achete_plus['COUNT(d.id_commande)'].' '.' achat(s) </span></td>';
            echo '</tr>';
            // echo print_r($meilleurs_note);
        }
        echo '</table>';
        // les membres qui achetent le plus cher: 
        echo '<button type="button" class="btn btn-primary p-2 my-3" id="plus_cher"> Les membres qui achetent le plus cher :</button>';
        echo '<table class=" p-0 table-striped table-bordered text-center table mx-auto bg-white ">';
        echo
        '<tr style="background-color:#d6934e;" >
        <th>Id_membre</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Dépenses en euro</th>
        </tr>'; 
        while($membre_achete_cher = $req4->fetch(PDO::FETCH_ASSOC)){   
            echo '<tr>';
            echo '<td>'.$membre_achete_cher['id_membre'].'</td>';
            echo '<td>'.$membre_achete_cher['nom'].'</td>';
            echo '<td>'.$membre_achete_cher['prenom'].'</td>';
            if ($membre_achete_cher['nombre_commande']>0){
                echo '<td><span class="badge badge-primary p-2">'.$membre_achete_cher['total_achat'].' € </span> </td>';
            } 
            else{
                echo '<td class="text-primary"> 0 dépenses </td>'; }
                echo '</tr>';
                // echo print_r($meilleurs_note);
            }
            echo '</table>'; 
            ?>
            
        </div>
        <?php
    //inclusion des éléments:
        require_once('../inc/modal_mentionslegales.inc.php');
        require_once('../inc/modal_conditionsdevente.inc.php');
        require_once('../inc/footer.inc.php');
        ?>
