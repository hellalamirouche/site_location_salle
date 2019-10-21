<?php
//foction permettant de savoir si l'utilisateur est connecté
function isconnected(){
  if(isset($_SESSION['membre'])){	
    return true;
  }
  return false;
}
//fonction permettand de savoir si l'utilsateur a le statut administrateur.
function isAdmin(){
  if(isconnected() && $_SESSION['membre']['statut'] == 2){
    return true;
  }
  return false;
}
//fonction de creation de panier
//soit le panier existe on retourne true et on le modifie
//soit le panier n'existe pas on le cree
function create_panier(){
  if(!isset($_SESSION['panier'])){ 
    $_SESSION['panier'] = array();
    $_SESSION['panier']['id_produit'] = array();
    $_SESSION['panier']['quantite']	= array();
    $_SESSION['panier']['prix'] = array();
    $_SESSION['panier']['titre'] = array();	  
  }
  return true;
}
//fonction pour ajouter un article dans le panier
function ajout_panier($id_produit,$titre,$prix,$quantite = 1){
  //avant d'ajouter l'article, nous devaons verifier s'il n'est pas deja dans le panier. si c'est le cas on change que la quantité.sinon on le rajoute.
  create_panier(); 
  $position_produit = array_search($id_produit, $_SESSION['panier']['id_produit']);
  if( $position_produit === false){
    $_SESSION['panier']['id_produit'][] = $id_produit;
    $_SESSION['panier']['titre'][] =$titre;
    $_SESSION['panier']['quantite'][] = $quantite;
    $_SESSION['panier']['prix'][] = $prix;
  }else{
    $_SESSION['panier']['quantite'][$position_produit] += $quantite;
  }
}
//calcul du montant du panier
function montantTotal(){
  $total = 0;
  for($i =0; $i<count($_SESSION['panier']['id_produit']); $i++){
    $total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
  }
  return $total;
}
//retrait d'un produit du panier
function retraitDuPanier($id_article){
  $position_article = array_search($id_article, $_SESSION['panier']['id_produit']);
  if($position_article !== false){
    array_splice($_SESSION['panier']['id_produit'],$position_article,1);
    array_splice($_SESSION['panier']['titre'], $position_article,1);
    array_splice($_SESSION['panier']['quantite'],$position_article,1);
    array_splice($_SESSION['panier']['prix'],$position_article,1);
  }
}
function nbArticles(){
  $nb='';
  if(isset($_SESSION['panier']['id_produit'])){
    $nb = array_sum($_SESSION['panier']['quantite']);
    if($nb > 0){
      $nb='<span class="badge badge-primary">'.$nb.'</span>';
      echo $nb;
    }else{
      $nb='';
      echo $nb;
    }	
  }
  return $nb;
}
