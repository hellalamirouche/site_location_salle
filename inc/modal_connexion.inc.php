<?php
//déconnexion
if(isset($_GET['action']) && $_GET['action']=='deconnexion'){ //si l'indice action exite dans le chemin GET de l url deconnexion  dans le lien deconnexion de la page nav : execute moi cette fonction qui va me rederiger vers connexion
  //session_destroy();

unset($_SESSION['membre']);
?>
<script>
	window.location ='http://localhost/sallea';
</script>
<?php	
}
?>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="row col-12 modal-title text-center" id="exampleModalLabel">Connexion</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-11 mx-auto">
						<div id='msg1' class='text-center'></div>
						<form method="post" action="#" id='connex'>
							<div class="form-group">
								<label for="pseu">Pseudo</label>
								<input type="text" name="pseudo" id="pseu" value="" class="form-control">
							</div>
							<div class="form-group">
								<label for="mot_de_passe">Mot de passe</label>
								<input type="password" name="mdp" id="mot_de_passe" value="" class="form-control">
							</div>
							<hr>
							<input type="submit" name="connexion" id="connect" value="Connexion" class="form-control btn btn-info text-white">
						</form>    
					</div>  
				</div> 
			</div>
		</div>
	</div>
</div>

