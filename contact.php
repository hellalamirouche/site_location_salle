<?php
require_once('inc/init.inc.php');
require_once('inc/header.inc.php');
require_once('inc/modal_connexion.inc.php');
require_once('inc/modal_inscription.inc.php');
require_once('inc/nav.inc.php');
if (isset($_POST['objet'])&& isset($_POST['message'])){

    if(!isconnected()){  
        $msg=' <div class="alert alert-danger">
             <strong>Ahh!</strong> Connecte toi pour nous joindre.
           </div>';   
      }
    if(isconnected()){
    $objet=htmlentities($_POST['objet']);
    $message=htmlentities($_POST['message']);
    $destinataire='hellal.amirouche@gmail.com';
    $mail= mail($destinataire,$objet,$message);
    $msg=' <div class="alert alert-success">
             <strong>Merci!</strong> On vous répond dans les plus bref délais.
           </div>';
    }
}
?>
<!--CONTENUE ICI-->
    <!-- contact -->
   <div class="row container-fluid col-xl-10 mx-auto"></div>
        <h3 class="col-sm-6 mx-auto text-center mt-5 dark text-white p-3 rounded ">Contactez-nous </h3>
        <div class="col-xl-10 row justify-content-between mx-auto ">
            <form class="  formulaire col-lg-10  img-thumbnail p-5 text-white my-5 bandeau mx-auto " action="#" method="post">
            <?php echo $msg ?>
                <select class="custom-select" name="objet">
                    <option value="objet" selected>OBJET</option>
                    <option value="reclamation">Réclamation</option>
                    <option value="recrutement">Recrutement</option>
                </select>
                <div class="mb-3 ">
                    <label for="valider">Message :</label>
                    <textarea class="form-control message" name="message" id="valider" placeholder="" required style="min-height:300px"></textarea>
                    <div class="invalid-feedback">
                        veuillez entrer un messager !.
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit"  class="btn btn-black my-3 w-100 mx-auto">Envoyer</button>
                </div>
            </form>
        </div>
<?php
require_once ('inc/footer.inc.php');
?>