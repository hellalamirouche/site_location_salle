 $('#cover').css('text-align','center');
 $('#cover').hide();
 var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
 $('#startDate3').datepicker({
    uiLibrary: 'bootstrap4',
    iconsLibrary: 'fontawesome',
    minDate: today,
    maxDate: function () {
        return $('#endDate3').val();
    }
});
 $('#endDate3').datepicker({
    uiLibrary: 'bootstrap4',
    iconsLibrary: 'fontawesome',
    minDate: function () {
        return $('#startDate3').val();
    }
});
 $('.form input,.form select').change(function(event){
    event.preventDefault; 
    $('.all').hide(); 
    var params1 = '';
    var ville = $('.ville:checked');
    var capacite = $('#choix').val() ? $('#choix').val() : '';
    var category = $('.category:checked');
    var arrive = $('#startDate3').val();
    var depart = $('#endDate3').val();
    var prix = $('#start').val();
	
    if(category.length >= 1){ 
        for(i=0; i< category.length;i++){  
            params1	+= "categ"+category[i].value+"="+category[i].value+'&';
        }   
    }
    if(capacite.length > 0){
        params1 += 'capacite='+capacite+'&';
    }
    if(arrive.length > 0 && depart.length > 0)
    {	
        params1 += 'arrive='+arrive+'&';
        params1 += 'arrive1='+depart+'&';
    }
    if(ville.length > 0){

        for(i=0; i< ville.length;i++){

            params1	+= 'ville'+ville[i].value+'='+ville[i].value+'&';
        }
    } 

    if(prix > 0)
    {
		document.getElementById('prix').innerHTML = 'De 0€ à '+prix+'€';
        params1 +='prix='+prix+'&';
    }else{
		document.getElementById('prix').innerHTML = '';
	}
	
    console.log(params1);
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function(){ 
        if(xhr.readyState != 4){
            document.getElementById('result').innerHTML = '';
            $('#cover').show();

        } 
        if(xhr.readyState == 4 && xhr.status == 200){
         $('#cover').hide();

         document.getElementById('result').innerHTML = xhr.responseText; 


         if(xhr.responseText == false)
         {
            document.getElementById('result').innerHTML = '<h2 class="text-center">Aucune Salle n\' est disponible pour ces dates</h2>';
        }
    }
}
document.getElementById('cover').innerHTML ='<img src="http://www.aveva.com/Images/ajax-loader.gif" style="width:400px;height:400px;"/>';
xhr.open('GET','http://localhost/sallea/ajax/ajax1.php?'+params1,true);
xhr.send();
});