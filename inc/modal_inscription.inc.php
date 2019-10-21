<!-- modal inscription -->
<div id="modal_inscription" class="modal fade bd-example-modal-lg px-2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="myLargeModalLabel">
            <div class="row">
                <div class="col-sm-11 py-4 mx-auto">
                <!-- affichage ajax message erreur -->
                    <div id='msg' class="text-center"></div>
                    <form method="POST" action="#" id='inscription' class='px-2'>
                        <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close" style="font-size:3em; position:relative; bottom:20px;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="form-group">
                            <label for="pseudo">Pseudo</label>
                            <input type="text" name="pseudo" id="pseudo" value="" class="form-control">
                            <div id='error_pseudo'></div>
                        </div>
                        <div class="form-group">
                            <label for="mdp">Mot de passe</label>
                            <input type="password" name="mdp" id="mdp" value="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <input type="text" name="nom" id="nom" value="" class="form-control">
                            <div id='error_nom'></div>
                        </div>
                        <div class="form-group">
                            <label for="prenom">Prenom</label>
                            <input type="text" name="prenom" id="prenom" value="" class="form-control">
                            <div id='error_prenom'></div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" name="email" id="email" value="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="civilite">Civilité</label>
                            <select name="civilite" class="form-control" id="civilite">
                                <option value="m">Homme</option>
                                <option value="f"  > Femme</option>  <!-- tjrs mettre un espace avant/apres qd php dans html -->
                            </select>
                        </div>
                        <hr>
                        <input type="submit" name="inscription" id="inscription1" value="Inscription" class="form-control btn btn-primary text-white">
                    </form>
                </div>
            </div>    
        </div>
    </div>
</div>
