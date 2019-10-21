

$('#inscription1').click(function(e){
    e.preventDefault();


    var nom = $('#nom').val();
    var prenom = $("#prenom").val();
    var email = $("#email").val();
    var mdp = $('#mdp').val();
    var civilite = $("input:radio[name='civilite']:checked").val()?$("input:radio[name='civilite']:checked").val():'m';
    var pseudo = $('#pseudo').val();

    var params1 = '';

    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function(){
     if(xhr.readyState == 4 && xhr.status == 200){

        document.getElementById('msg').innerHTML = xhr.responseText;

        
        if(xhr.responseText === '')
        {
            
            $('.bd-example-modal-lg').modal('hide');
            window.location.reload(true);
        }   
    }
}
xhr.open('POST','http://sallea.000webhostapp.com/ajax/ajax-inscription.php');
xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
xhr.send('nom='+nom+'&prenom='+prenom+'&email='+email+'&mdp='+mdp+'&civilite='+civilite+'&pseudo='+pseudo);
});


