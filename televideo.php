<?php
##################################################################################
#    FANTACALCIOBAZAR EVOLUTION
#    Copyright (C) 2003-2009 by Antonello Onida
#
#    This program is free software; you can redistribute it and/or modify
#    it under the terms of the GNU General Public License as published by
#    the Free Software Foundation; either version 2 of the License, or
#    (at your option) any later version.
#
#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU General Public License for more details.
#
#    You should have received a copy of the GNU General Public License
#    along with this program; if not, write to the Free Software
#    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
##################################################################################
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it" dir="ltr" >
<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
<meta http-equiv="Content-Language" content="Italian" />
<meta name="Author" content="Antonello Onida - http://fantacalciobazar.sssr.it" />
<meta name="Description" content="FantacalcioBazar | Il migliore gestore di Fantacalcio on line" />
<meta name="Keywords" content="fantacalciobazar, fantacalcio, semplice, completo, online" />
<meta name="Robots" content="INDEX, FOLLOW" />
<!--[if !IE]><!-->
        <link rel="stylesheet" type="text/css" media="all" href="./immagini/style.css" />
	<link rel="stylesheet" type="text/css" media="all" href="./immagini/style_patch_all-in-one.css" />
 <!--<![endif]-->
 
<!--[if lte IE 7]>
        <link rel="stylesheet" type="text/css" href="./immagini/ie7-style.css" />
        <link rel="stylesheet" type="text/css" media="all" href="./immagini/ie7-style_patch_all-in-one.css" />
<![endif]-->

<!--[if gte IE 8]>
	<link rel="stylesheet" type="text/css" media="all" href="./immagini/style.css" />
	<link rel="stylesheet" type="text/css" media="all" href="./immagini/style_patch_all-in-one.css" />
<![endif]-->
<head>
<body>
<?php
if (!$_GET['telev']) $telev = "201"; else $telev = $_GET['telev'];
if (!$_GET['sottop']) $sottop = ""; else $sottop = $_GET['sottop'];
if ($_POST['invio'] == "Precedente") $telev = $telev-1;
if ($_POST['invio'] == "Successiva") $telev = $telev+1;

if ($sottop == "")
$lnkimage = "http://www.televideo.rai.it/televideo/pub/tt4web/Nazionale/page-" . $telev . ".png";
else
$lnkimage = "http://www.televideo.rai.it/televideo/pub/tt4web/Nazionale/page-" . $telev . "." . $sottop . ".png";

if (!@fopen($lnkimage, "r")){
$lnkimage = "";
}

$tp = $telev -1;
$ts = $telev +1;
/*
echo "<form method='post' action='televideo.php'>
<input type='hidden' name='telev' value='".$_GET['telev']."' />
<table align=center cellpadding=5 cellspacing=10 width='100%'>
<tr><td bgcolor=black align=center valign=middle>
<img SRC='$lnkimage' hspace=5 vspace=5 alt='Televideo RAI' /></td>
<td align=center valign=middle>
<h2>Televideo RAI</h2><br/><br/>Pagina <input type='text' name='telev' size=3 maxlength=3 value='$telev' />
Sottopagina <input type='text' name='sottop' size=2 maxlength=2 value='$sottop' />
<input type='submit' name='invio' value='Vai' /><br/><br/>
<input type='submit' name='invio' value='$tp' />
<input type='submit' name='invio' value='$ts' /><br/>  <br/>
<input type='submit' name='telev' value='100' />
<input type='submit' name='telev' value='200'/><br/><br/>
Se non appare la pagina televideo pu&ograve; significare <br/>che la pagina non esiste <br/>o che occorre cambiare il numero di sottopagina.";
*/
#if ($errore) echo "<hr>$errore";
echo"<div><div style='float:left;margin: 5px 5px'>
<div class='box_utente_header' style='font-size:small'>TELEVIDEO</div>
<div class='box_utente_content' style='background-color:black;border:2px; vertical-align:middle;width:380px;height:400px'>
<img src='$lnkimage' hspace='5' vspace='5' alt='Televideo RAI'/></td>
</div>
<div id='menu_e' style='margin-top:5px'>
<div class='box_utente_content'>
<div  style='background:#B2C7E9;font-size: small'>
Probabili formazioni 
<a href='televideo.php?telev=280' style='display:inline;background:#B2C7E9;padding:0px;'>1</a>
<a href='televideo.php?telev=280&sottop=2' style='display:inline;background:#B2C7E9;padding:0px;'>2</a> 
<a href='televideo.php?telev=280&sottop=3' style='display:inline;background:#B2C7E9;padding:0px;'>3</a>
<a href='televideo.php?telev=280&sottop=4' style='display:inline;background:#B2C7E9;padding:0px;'>4</a> 
<a href='televideo.php?telev=280&sottop=5' style='display:inline;background:#B2C7E9;padding:0px;'>5</a>
<a href='televideo.php?telev=280&sottop=6' style='display:inline;background:#B2C7E9;padding:0px;'>6</a> 
<a href='televideo.php?telev=280&sottop=7' style='display:inline;background:#B2C7E9;padding:0px;'>7</a>
<a href='televideo.php?telev=280&sottop=8' style='display:inline;background:#B2C7E9;padding:0px;'>8</a> 
<a href='televideo.php?telev=280&sottop=9' style='display:inline;background:#B2C7E9;padding:0px;'>9</a>
<a href='televideo.php?telev=280&sottop=10' style='display:inline;background:#B2C7E9;padding:0px;'>10</a></div></a>
</div></div>
</div>
<div id='menu_e' style='float:left; width: 160px;margin:5px 5px;'>
<div class='box_utente_header' style='font-size:small'>LINK</div>
<div class='box_utente_content'>
<a href='televideo.php?telev=229' style='padding: 0px 0px 0px 19px;font-size: small'>Brevi calcio</a>
<a href='televideo.php?telev=230' style='padding: 0px 0px 0px 19px;font-size: small;margin-top:5px'>Atalanta</a>
<a href='televideo.php?telev=231' style='padding: 0px 0px 0px 19px;font-size: small'>Bologna</a>
<a href='televideo.php?telev=232' style='padding: 0px 0px 0px 19px;font-size: small'>Cagliari</a>
<a href='televideo.php?telev=233' style='padding: 0px 0px 0px 19px;font-size: small'>Catania</a>
<a href='televideo.php?telev=234' style='padding: 0px 0px 0px 19px;font-size: small'>Chievo</a>
<a href='televideo.php?telev=235' style='padding: 0px 0px 0px 19px;font-size: small'>Fiorentina</a>
<a href='televideo.php?telev=236' style='padding: 0px 0px 0px 19px;font-size: small'>Genoa</a>
<a href='televideo.php?telev=237' style='padding: 0px 0px 0px 19px;font-size: small'>Inter</a>
<a href='televideo.php?telev=238' style='padding: 0px 0px 0px 19px;font-size: small'>Juventus</a>
<a href='televideo.php?telev=239' style='padding: 0px 0px 0px 19px;font-size: small'>Lazio</a>
<a href='televideo.php?telev=240' style='padding: 0px 0px 0px 19px;font-size: small'>Livorno</a>
<a href='televideo.php?telev=241' style='padding: 0px 0px 0px 19px;font-size: small'>Milano</a>
<a href='televideo.php?telev=242' style='padding: 0px 0px 0px 19px;font-size: small'>Napoli</a>
<a href='televideo.php?telev=243' style='padding: 0px 0px 0px 19px;font-size: small'>Parma</a>
<a href='televideo.php?telev=244' style='padding: 0px 0px 0px 19px;font-size: small'>Roma</a>
<a href='televideo.php?telev=245' style='padding: 0px 0px 0px 19px;font-size: small'>Sampdoria</a>
<a href='televideo.php?telev=246' style='padding: 0px 0px 0px 19px;font-size: small'>Sassuolo</a>
<a href='televideo.php?telev=247' style='padding: 0px 0px 0px 19px;font-size: small'>Torino</a>
<a href='televideo.php?telev=248' style='padding: 0px 0px 0px 19px;font-size: small'>Udinese</a>
<a href='televideo.php?telev=249' style='padding: 0px 0px 0px 19px;font-size: small'>Verona</a>
</div>
</div>
<div style='clear:both'></div></div>";
?>
</body>
</html>
