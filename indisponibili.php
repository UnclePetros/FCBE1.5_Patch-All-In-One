<?PHP
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

<script type="text/javascript" src="./inc/js/jquery.js"></script>
<script type="text/javascript">

function showIndisponibili(element){
	if($(element).text() == "La Repubblica"){
		$('#iframe1').show();
		$('#iframe2').hide();		
	}
	else{
		$('#iframe1').hide();
		$('#iframe2').show();
	}
			
}
</script>

<head>
<body>
<?php
echo"<center><div style='margin:auto; margin-bottom:5px; background-color: #194A93;color:#FFFFFF'>Indisponibili - Serie A TIM</div>
<div style='width: 500px;margin:auto'>
<div class='button' style='float:left'><a href='#' onclick='showIndisponibili(this)' style='display:block'>La Repubblica</a></div> 
<div class='button' style='float:right;margin-left: 10px'><a href='#' onclick='showIndisponibili(this)' style='display:block'>Fantagazzetta</a></div> 
</div>
<br/><br/>
<iframe class='box-shadow' id='iframe1' align='center' style='width: 670px; height: 650px;overflow-x:hidden' src='http://www.fantacalcio.kataweb.it/stat/index.php?page=indisponibili' marginwidth='0' marginheight='0' hspace='0' vspace='0' frameborder='0'>
Il tuo browser non supporta i Frame in linea non puoi vedere questa pagina.
</iframe>
<iframe class='box-shadow' id='iframe2' align='center' style='width: 670px; height: 650px;display:none;overflow-x:hidden' src='http://www.fantagazzetta.com/indisponibili_serie_a.asp' marginwidth='0' marginheight='0' hspace='0' vspace='0' frameborder='0'>
Il tuo browser non supporta i Frame in linea non puoi vedere questa pagina.
</iframe>

</center>";
?>
</body>
</html>
