<?php
    //destinataire
$to = $_POST['email'];;
    //sujet
$subject = 'Bienvenue sur Salle Location';

    //message
$message ='
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<title>Message de sallea.com</title>


<style> @media only screen and (max-width: 300px){ 
  body {
    width:218px !important;
    margin:auto !important;
  }
  .table {width:195px !important;margin:auto !important;}
  .logo, .titleblock, .linkbelow, .box, .footer, .space_footer{width:auto !important;display: block !important;}    
  span.title{font-size:20px !important;line-height: 23px !important}
  span.subtitle{font-size: 14px !important;line-height: 18px !important;padding-top:10px !important;display:block !important;}    
  td.box p{font-size: 12px !important;font-weight: bold !important;}
  .table-recap table, .table-recap thead, .table-recap tbody, .table-recap th, .table-recap td, .table-recap tr { 
    display: block !important; 
  }
  .table-recap{width: 200px!important;}
  .table-recap tr td, .conf_body td{text-align:center !important;}  
  .address{display: block !important;margin-bottom: 10px !important;}
  .space_address{display: none !important;} 
}
@media only screen and (min-width: 301px) and (max-width: 500px) { 
  body {width:308px!important;margin:auto!important;}
  .table {width:285px!important;margin:auto!important;} 
  .logo, .titleblock, .linkbelow, .box, .footer, .space_footer{width:auto!important;display: block!important;}  
  .table-recap table, .table-recap thead, .table-recap tbody, .table-recap th, .table-recap td, .table-recap tr { 
    display: block !important; 
  }
  .table-recap{width: 295px !important;}
  .table-recap tr td, .conf_body td{text-align:center !important;}

}
@media only screen and (min-width: 501px) and (max-width: 768px) {
  body {width:478px!important;margin:auto!important;}
  .table {width:450px!important;margin:auto!important;} 
  .logo, .titleblock, .linkbelow, .box, .footer, .space_footer{width:auto!important;display: block!important;}      
}
@media only screen and (max-device-width: 480px) { 
  body {width:308px!important;margin:auto!important;}
  .table {width:285px;margin:auto!important;} 
  .logo, .titleblock, .linkbelow, .box, .footer, .space_footer{width:auto!important;display: block!important;}

  .table-recap{width: 295px!important;}
  .table-recap tr td, .conf_body td{text-align:center!important;} 
  .address{display: block !important;margin-bottom: 10px !important;}
  .space_address{display: none !important;} 
}
</style>

</head>
<body style="-webkit-text-size-adjust:none;background-color:#fff;width:650px;font-family:Open-sans, sans-serif;color:#555454;font-size:13px;line-height:18px;margin:auto" >
<table class="table table-mail" style="width: 100%; margin-top: 10px; -moz-box-shadow: 0 0 5px #afafaf; -webkit-box-shadow: 0 0 5px #afafaf; -o-box-shadow: 0 0 5px #afafaf; box-shadow: 0 0 5px #afafaf; filter: progid:DXImageTransform.Microsoft.Shadow(color=#afafaf,Direction=134,Strength=5);">
<tbody>
<tr>
<td class="space" style="width: 20px; padding: 7px 0;"> </td>
<td style="padding: 7px 0;" align="center">
<table class="table" style="width: 100%;" bgcolor="#ffffff">
<tbody>
<tr>
<td class="logo" style="border-bottom: 4px solid #333333; padding: 7px 0;" align="center"><a title="sallea" href="http://sallea.000webhostapp.com" style="color: #337ff1;"></a></td>
</tr>
<tr>
<td class="titleblock" style="padding: 7px 0;" align="center"><span style="color: #555454; font-family: Open-sans, sans-serif; font-size: small;" size="2" face="Open-sans, sans-serif"> <span class="title" style="font-weight: 500; font-size: 28px; text-transform: uppercase; line-height: 33px;">Bonjour '.$_POST["nom"].' '. $_POST["prenom"].',</span><br /> <span class="subtitle" style="font-weight: 500; font-size: 16px; text-transform: uppercase; line-height: 25px;">Merci d\'avoir créé votre compte client sur sallea.com.</span> </span></td>
</tr>
<tr>
<td class="space_footer" style="padding: 0!important;"> </td>
</tr>
<tr>
<td class="box" style="border: 1px solid #D6D4D4; background-color: #f8f8f8; padding: 7px 0;">
<table class="table" style="width: 100%;">
<tbody>
<tr>
<td style="padding: 7px 0;" width="10"> </td>
<td style="padding: 7px 0;">
<p data-html-only="1" style="border-bottom: 1px solid #D6D4D4; margin: 3px 0 7px; text-transform: uppercase; font-weight: 500; font-size: 18px; padding-bottom: 10px;">Vos codes d\'accès sur Sallea.</p>
<span style="color: #555454; font-family: Open-sans, sans-serif; font-size: small;" size="2" face="Open-sans, sans-serif"><span style="color: #777;"> Vos codes d\'accès :<br /> <span style="color: #333;"><strong>Adresse e-mail : <a style="color: #337ff1;">'.$_POST["email"].'</a></strong></span> </span> </span></td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td class="space_footer" style="padding: 0!important;"> </td>
</tr>
<tr>
<td class="box" style="border: 1px solid #D6D4D4; background-color: #f8f8f8; padding: 7px 0;">
<table class="table" style="width: 100%;">
<tbody>
<tr>
<td style="padding: 7px 0;" width="10"> </td>
<td style="padding: 7px 0;">
<p style="border-bottom: 1px solid #D6D4D4; margin: 3px 0 7px; text-transform: uppercase; font-weight: 500; font-size: 18px; padding-bottom: 10px;">Conseils de sécurité importants :</p>
<ol style="margin-bottom: 0;">
<li>Vos informations de compte doivent rester confidentielles.</li>
<li>Ne les communiquez jamais à qui que ce soit.</li>
<li>Changez votre mot de passe régulièrement.</li>
<li>Si vous pensez que quelqu\'un utilise votre compte illégalement, veuillez nous prévenir immédiatement.</li>
</ol></td>
<td style="padding: 7px 0;" width="10"> </td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td class="space_footer" style="padding: 0!important;"> </td>
</tr>
<tr>
<td class="linkbelow" style="padding: 7px 0;"><span style="color: #555454; font-family: Open-sans, sans-serif; font-size: small;" size="2" face="Open-sans, sans-serif"> <span>Vous pouvez dès à présent passer commande sur notre boutique : <a href="http://sallea.000webhostapp.com" style="color: #337ff1;">Sallea</a></span> </span></td>
</tr>
<tr>
<td class="space_footer" style="padding: 0!important;"> </td>
</tr>
<tr>
<td class="footer" style="border-top: 4px solid #333333; padding: 7px 0;"><span><a href="http://sallea.000webhostapp.com" style="color: #337ff1;">sallea</a> powered by <a href="http://sallea.000webhostapp.com" style="color: #337ff1;">Sallea™</a></span></td>
</tr>
</tbody>
</table>
</td>
<td class="space" style="width: 20px; padding: 7px 0;"> </td>
</tr>
</tbody>
</table>
</body>
</html>
';


    // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/html; charset=iso-8859-1';

mail($to, $subject, $message, implode("\r\n", $headers));