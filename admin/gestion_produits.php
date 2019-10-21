<?php 
require_once('../inc/init.inc.php');
if(!isAdmin()){
    header("location:".URL.'../index.php');
    exit();	
}
require_once('../inc/header.inc.php');
require_once('../inc/nav.inc.php');
$msg="";
//---------------------------------------requete d'ajout de produit--------------------------------------------------//
if(isset($_POST['validation'])){ 
    if(isset($_GET['action'])&& $_GET['action'] == 'ajouter'){ 
        $recup_membre =  $pdo->prepare('SELECT * FROM produit WHERE id_produit = :id_produit');
        $recup_membre->bindParam(':id_produit', $_GET['id_produit'],PDO::PARAM_INT);
        $recup_membre->execute(); 
        $date=date_create($_POST['arrive']);
        $date1=date_create($_POST['depart']);
        $hour_arrive = date_format($date,'Y-m-d H:i:s');
        $hour_depart = date_format($date1,'Y-m-d H:i:s'); 
        if(isset($_POST['arrive']) && isset($_POST['depart']) && isset($_POST['salle']) && isset($_POST['prix']) && isset($_POST['etat']) && isset($_POST['quantite'])){  
            $id_salle = substr($_POST['salle'],0,2);
            $insert_produit = $pdo->prepare('INSERT INTO produit(date_arrivee ,date_depart ,id_salle,prix,etat,quantite) VALUES(:arrive,:depart,:salle,:prix,:etat,:quantite)');
            $insert_produit->bindParam(':arrive', $hour_arrive,PDO::PARAM_STR);
            $insert_produit->bindParam(':depart',$hour_depart,PDO::PARAM_STR); 
            $insert_produit->bindParam(':salle', $id_salle,PDO::PARAM_STR);
            $insert_produit->bindParam(':prix', $_POST['prix'], PDO::PARAM_INT);
            $insert_produit->bindParam(':etat', $_POST['etat'], PDO::PARAM_INT);
            $insert_produit->bindParam(':quantite',$_POST['quantite'], PDO::PARAM_INT);
            $insert_produit->execute();      
            //vider les variables...
            $_POST['arrive'] = '';
            $_POST['depart'] = '';
            $_POST['salle'] = ''; 
            $_POST['tarif'] = '';
            $_POST['etat'] = '';
            $_POST['quantite'] ='';            
            $msg .='<div class="bg-success">Le membre a bien été ajouté en base de donnée</div>';	    
        }
    }	   
}
//---------------------------------------requete de modification--------------------------------------------------//
if(isset($_GET['action'])&& $_GET['action'] == 'modification'){
    
    $recup_produit =  $pdo->prepare('SELECT p.id_produit,p.date_arrivee,p.date_depart,s.titre,p.prix,p.etat,s.id_salle,p.quantite FROM produit p, salle s WHERE p.id_salle = s.id_salle and p.id_produit =:id_produit');
    $recup_produit->bindParam(':id_produit', $_GET['id_produit'],PDO::PARAM_INT);
    $recup_produit->execute();   
    if($recup_produit->rowCount()>0)
    {   
        $produit_actuel = $recup_produit->fetch(PDO::FETCH_ASSOC); 
        $id_produit = trim($produit_actuel['id_produit']);
        $arrive = trim($produit_actuel['date_arrivee']);
        $depart = trim($produit_actuel['date_depart']);
        $id_salle = trim($produit_actuel['id_salle']);
        $prix = trim($produit_actuel['prix']);
        $etat = trim($produit_actuel['etat']);
        $titre = trim($produit_actuel['titre']);
        $quantite= trim($produit_actuel['quantite']);
        if(isset($_POST['arrive']) && isset($_POST['depart']) && isset($_POST['salle']) && isset($_POST['prix']) && isset($_POST['quantite'])){
            
            if(isset($id_produit)){
                $insert_produit = $pdo->prepare('UPDATE produit SET id_salle = :salle,date_arrivee = :arrive,date_depart = :depart,prix = :prix,etat =:etat, quantite=:quantite WHERE id_produit = :id_produit');
                $insert_produit->bindParam(':id_produit',$_GET['id_produit'],PDO::PARAM_INT);
                $insert_produit->bindParam(':arrive', $_POST['arrive'],PDO::PARAM_STR);
                $insert_produit->bindParam(':depart',$_POST['depart'],PDO::PARAM_STR);
                $insert_produit->bindParam(':salle', $_POST['salle'],PDO::PARAM_STR);
                $insert_produit->bindParam(':prix', $_POST['prix'], PDO::PARAM_STR);
                $insert_produit->bindParam(':etat', $_POST['etat'], PDO::PARAM_STR);
                $insert_produit->bindParam(':quantite',$_POST['quantite'],PDO::PARAM_STR);
                $insert_produit->execute();
            }   
            $msg .='<div class="bg-success">Le produit a  bien été modifié</div>';	      
        }
    }	    
}
//---------------------------------------requete de suppression--------------------------------------------------//
if(isset($_GET['action']) && $_GET['action'] == 'suppression' && (!empty($_GET['id_produit'])))
{
    $sup_produit = $pdo->prepare('DELETE FROM produit where id_produit=:id_produit');
    $sup_produit->bindParam(':id_produit',$_GET['id_produit'],PDO::PARAM_INT);
    $sup_produit->execute();
    //message de suppression de membre valide
    $msg .='<div class="bg-success">Le membre a bien été supprimé</div>';   
}
//code de pagination
$limite = 4;
$page = (!empty($_GET['page'])? $_GET['page']: 1);
$debut = ($page -1)* $limite;
//$employees = $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM membre ORDER BY id_membre DESC LIMIT :limite OFFSET :debut");
$produits = $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS p.id_produit,p.date_arrivee,p.date_depart,s.titre,p.prix,p.etat,p.quantite FROM produit p, salle s WHERE p.id_salle = s.id_salle ORDER BY p.id_produit DESC  LIMIT :limite OFFSET :debut");
$produits->bindParam(':limite',$limite,PDO::PARAM_INT);
$produits->bindParam(':debut',$debut,PDO::PARAM_INT);
$produits->execute();
$resultfound = $pdo->query('SELECT found_rows()');
$nombretotal = $resultfound->fetchColumn();
//creation de la premiere ligne du tableau
echo $msg; 
echo'<table class="table table-bordered bg-white ">
<thead class="thead-dark">
<tr>
<th>ID</th>
<th>Date arrivée</th>
<th>Date départ</th>
<th>Titre</th>
<th>Prix</th>
<th>Etat</th>
<th>Quantite</th>
<th>modifier</th>
<th>suppimer</th>
</tr>';
echo'</thead>';

while($produit=$produits->fetch(PDO::FETCH_ASSOC)){
     // affichage de la date en francais
     $date_arrive = new DateTime($produit['date_arrivee']);
     $date_arrive_fr = $date_arrive->format("d/m/Y");
     $date_depart = new DateTime($produit['date_depart']);
     $date_depart_fr = $date_depart->format("d/m/Y");
    echo '<tr>';
        echo '<td>'.$produit['id_produit'].'</td>';
        echo '<td>'.$date_arrive_fr.'</td>';
        echo '<td>'.$date_depart_fr.'</td>';
        echo '<td>'.$produit['titre'].'</td>';
        echo '<td>'.$produit['prix'].'</td>';
        echo '<td>'.$produit['etat'].'</td>';
        echo '<td>'.$produit['quantite'].'</td>';
        echo '<td><a href="?page='.$_GET["page"].'&action=modification&id_produit='.$produit['id_produit'].'" class="btn btn-success text-white"  > <i class="fas fa-edit"></i></a>  </td>';
        echo '<td> <a href="?page='.$_GET["page"].'&action=suppression&id_produit='.$produit['id_produit'].'" class="btn btn-danger text-white" onclick="(confirm(\'Etes-vous sûr ? \'));"> <i class="fas fa-trash-alt"></i></a> </td>';
        echo '</tr>' ;
}
echo '</table>';
// echo '</div>';
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
<a href='?page=<?php echo $_GET["page"] ?>&action=ajouter'  class="btn btn-primary">ajouter un produit</a>
<!--creation du formulaire-->
<form action="#" method="post" class="col-xl-10 mx-auto">
    <div class="form-group">
        <label for="startDate1">Date d'arrivée</label>
        <input type="text" name="arrive"  id="startDate1" value="<?php if(isset($_POST[''])){echo $_POST['arrive'];}elseif(isset($arrive)){echo $arrive;}else{ echo $_POST['arrive'] = '';} ?>" class="form-control datepicker">
    </div>
    <div class="form-group">
        <label for="endDate1">Date de départ</label>
        <input type="text" id="endDate1" name="depart" value="<?php if(isset($_POST['depart'])){echo $_POST['depart'];}elseif(isset($depart)){echo $depart;}else{echo $_POST['depart'] ='';} ?>" class="form-control datepickerd">
    </div>
    <div class="form-group">
        <label for="prix">Tarif</label>
        <input type="text" id="prix" name="prix" value="<?php if(isset($_POST['prix'])){echo $_POST['prix'];}elseif(isset($prix)){echo $prix;}else{echo $_POST['prix'] ='';} ?>" class="form-control">
    </div>
    <div class="form-group">
        <label for="quantite">Quantite</label>
        <input type="text" id="quantite" name="quantite" value="<?php if(isset($_POST['quantite'])){echo $_POST['quantite'];}elseif(isset($quantite)){echo $quantite;}else{echo $_POST['quantite'] ='';} ?>" class="form-control">
    </div>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <label class="input-group-text" for="salle">Salle</label>
        </div>
        <?php $salle = $pdo->query('SELECT * FROM salle');
        $salle->execute();
        $salle->rowCount();
        echo'<select class="custom-select" id="salle" name="salle">';
        while($salles=$salle->fetch(PDO::FETCH_ASSOC)){?> 
            <option <?php if(isset($id_salle) && $id_salle == $salles["id_salle"]){echo"selected";}?> value='<?php echo $salles["id_salle"] ?>'> <?php echo "$salles[id_salle] - $salles[titre] - $salles[adresse] - $salles[cp] - $salles[ville] - $salles[capacite]" ?></option>   
        <?php }
        echo'</select>';
        echo'</div>';
        ?>  
        <select class="custom-select" id="etat" name="etat">
            <option <?php if(isset($etat) && $etat == 'libre' || empty($_POST['etat'])){echo 'selected';} ?>>Libre</option>
            <option <?php if(isset($etat) && $etat == 'reservation'){echo 'selected';} ?>>reservation</option>
        </select>
        <input type="submit" class="btn btn-primary" value="soumettre" name="validation">
        <?php if(isset($_GET['action']) && $_GET['action'] == 'ajouter'){ ?>
            <input type="reset" name="reset" value="reset" class="btn btn-warning mt-3"> 	
        <?php } ?>	   
    </form>	
    <!-- //modale de résérvation: -->
    <script>
        var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
        $('#startDate1').datepicker({
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            minDate: today,
            maxDate: function () {
                return $('#endDate1').val();
            }
        });
        $('#endDate1').datepicker({
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            minDate: function () {
                return $('#startDate1').val();
            }
        });
    </script>
    <?php
    //inclusion des éléments:
    require_once('../inc/modal_mentionslegales.inc.php');
    require_once('../inc/modal_conditionsdevente.inc.php');
    require_once('../inc/footer.inc.php');
    ?>
    
