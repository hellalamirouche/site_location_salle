
//  connexion
$('#connect').click(function(event){
	event.preventDefault();

	var mdp = $('#mot_de_passe').val();
	var pseudo = $('#pseu').val();

	var xhr = new XMLHttpRequest();

	xhr.onreadystatechange = function(){

		if(xhr.readyState == 4 && xhr.status == 200){

			document.getElementById('msg1').innerHTML = xhr.responseText;

			console.log(xhr.responseText.lenght);


			if(xhr.responseText === '')
			{
				
				$('#exampleModal').modal('hide');
				window.location.reload(true);
			}	
		}
	}
	xhr.open('POST','http://localhost/sallea/ajax/ajax-connexion.php');
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send('mdp='+mdp+'&pseudo='+pseudo);

});
/*----------------------------------------------------------------------XMLHttprequest-----------------------------------------------------------------------------------------------------------------*/

// INSCRIPTION

/*----------------------------------------------------------------------XMLHttprequest-----------------------------------------------------------------------------------------------------------------*/


$('#inscription1').click(function(event){
	event.preventDefault();

	var mdp = $('#mdp').val();
	var pseudo = $('#pseudo').val();
	var nom= $('#nom').val();
	var prenom = $('#prenom').val();
	var email = $('#email').val();
	var civilite = $('#civilite').val();

	var INSCR = new XMLHttpRequest();

	INSCR.onreadystatechange = function(){

		if(INSCR.readyState == 4 && INSCR.status == 200){

			document.getElementById('msg').innerHTML = INSCR.responseText;

			console.log(INSCR.responseText.lenght);


			if(INSCR.responseText === '')
			{
				
				$('#modal_inscription').modal('hide');
				window.location.reload(true);
			}	
		}
	}
	INSCR.open('POST','http://localhost/sallea/ajax/ajax-inscription.php');
	INSCR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	INSCR.send('mdp='+mdp+'&pseudo='+pseudo + '&nom=' + nom + '&prenom=' + prenom + '&email=' + email + '&civilite=' + civilite);

});