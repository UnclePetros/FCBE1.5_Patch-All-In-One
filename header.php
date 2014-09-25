<?php
$clock[] = "Inizio ".microtime();
if ($attiva_log == "SI"){
	$xx1=$HTTP_SERVER_VARS['SERVER_PORT'];
	$giorno = date("d",time()); $mese = date("m",time()); $anno = date("Y",time()); $ora = date("H",time()); $minuto = date("i",time());
		if ($HTTP_SERVER_VARS['REMOTE_HOST'] == "") $visitatore_info = $_SERVER['REMOTE_ADDR'];
		else $visitatore_info = $_SERVER['REMOTE_HOST'];
	$base = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	$x1="host $REMOTE_ADDR";
	$x2=$REMOTE_PORT;
	$date="$giorno-$mese-$anno $ora:$minuto";
		if ($_SESSION['utente'] == "") $infonome = "Visitatore"; else $infonome = $_SESSION['utente'];
	$fp = fopen($percorso_cartella_dati."/log".$_SESSION["torneo"].".txt", "a");
	fwrite($fp, "$date - $infonome - $base:$xx1 - $visitatore_info\n");
	fclose($fp);
}

if (strtoupper(substr(PHP_OS,0,3) == 'WIN')) $acapo = "\r\n";  
elseif (strtoupper(substr(PHP_OS,0,3) == 'MAC')) $acapo = "\r"; 
else $acapo = "\n";

$chiusura_giornata = INTVAL(@file($percorso_cartella_dati."/chiusura_giornata.txt"));

$nomi_giorni = array('Domenica','Lunedì','Martedì','Mercoledì','Giovedì','Venerdì','Sabato');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it" dir="ltr" >
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="Italian" />
<meta name="Author" content="Antonello Onida - http://fantacalciobazar.sssr.it" />
<meta name="Description" content="FantacalcioBazar | Il migliore gestore di Fantacalcio on line" />
<meta name="Keywords" content="fantacalciobazar, fantacalcio, semplice, completo, online" />
<meta name="Robots" content="INDEX, FOLLOW" />

<link rel="stylesheet" type="text/css" media="all" href="./immagini/style.css" />
<link rel="stylesheet" type="text/css" media="all" href="./immagini/style_patch_all-in-one.css" />
<link rel="stylesheet" type="text/css" media="all" href="./immagini/style_clickandstore.css" />

 
<!--[if lte IE 7]>
        <link rel="stylesheet" type="text/css" media="all" href="./immagini/ie7-style_patch_all-in-one.css" />
	<link rel="stylesheet" type="text/css" media="all" href="./immagini/ie7-style_clickandstore.css" />
<![endif]-->

<style type="text/css">
body {
	background-color: <?php echo $sfondo_tab1 ?>;
	color: <?php echo $carattere_colore ?>;
	font-family: <?php echo $carattere_tipo ?>;
	font-size: <?php echo $carattere_size ?>
	}
caption {
	background-color: <?php echo $sfondo_tab2 ?>
	}
.menu_s a {
	background: <?php echo $sfondo_tab3 ?> url(immagini/vmenuarrow.png) no-repeat center left;
	color: <?php echo $carattere_colore_chiaro ?>
	}
</style>
<?php
if ($a_fm == "SI") echo"<link rel='stylesheet' type='text/css' href='./inc/fm_style.css' />".$acapo;
?>

<!--[if lt IE 9]>
	<script src="./inc/js/jquery-1.10.2.min.js"></script>
<![endif]-->
<!--[if gte IE 9]>
	<script src="./inc/js/jquery-2.0.3.min.js"></script>
<![endif]-->

<script type="text/javascript" src="./inc/js/gestioneFormazione.js"></script>
<script type="text/javascript">
/* <![CDATA[ */
$(document).ready(function(){
/* CONFIG */
/* set start (sY) and finish (fY) heights for the list items */
sY = 24;
fY = 375;
/* end CONFIG */

/* open first list item */
animate (fY)

$("#slide .top").click(function() {
	if (this.className.indexOf('clicked') == -1 ) {
		animate(sY)
		$('.clicked').removeClass('clicked');
		$(this).addClass('clicked');
		animate(fY)
	}
});

function animate(pY) {
$('.clicked').animate({"height": pY + "px"}, 500);
}

});

/* ]]> */
</script>
<title><?php echo $titolo_sito; ?></title>
<!-- CHAT stile facebook -->
<?php if($_SESSION['valido'] == "SI" and $_SESSION['utente'] != $admin_user) {
	echo "<script type='text/javascript' src='./inc/js/jquery.min.js'></script>";
	echo "<script type='text/javascript' src='https://static.jappix.com/php/get.php?l=it&amp;t=js&amp;g=mini.xml'></script>";
	$tornei = @file($percorso_cartella_dati."/tornei.php");
	$num_tornei = count($tornei);
	
	for($num = 1 ; $num < $num_tornei; $num++) {
		@list($otid, $otdenom, $otpart, $otserie, $otmercato_libero, $ottipo_calcolo, $otgiornate_totali, $otritardo_torneo, $otcrediti_iniziali, $otnumcalciatori, $otcomposizione_squadra, $temp1, $temp2, $temp3, $temp4, $otstato, $otmodificatore_difesa, $otschemi, $otmax_in_panchina, $otpanchina_fissa, $otmax_entrate_dalla_panchina, $otsostituisci_per_ruolo, $otsostituisci_per_schema,  $otsostituisci_fantasisti_come_centrocampisti, $otnumero_cambi_max, $otrip_cambi_numero, $otrip_cambi_giornate, $otrip_cambi_durata, $otaspetta_giorni, $otaspetta_ore, $otaspetta_minuti, $otnum_calciatori_scambiabili, $otscambio_con_soldi, $otvendi_costo, $otpercentuale_vendita, $otsoglia_voti_primo_gol, $otincremento_voti_gol_successivi, $otvoti_bonus_in_casa, $otpunti_partita_vinta, $otpunti_partita_pareggiata, $otpunti_partita_persa, $otdifferenza_punti_a_parita_gol, $otdifferenza_punti_zero_a_zero, $otdifferenza_punti_prima_soglia_meno_sei, $otdifferenza_punti_gol_premio, $otmin_num_titolari_in_formazione, $otpunti_pareggio, $otpunti_pos) = explode(",", $tornei[$num]);
		if($_SESSION['torneo'] == $otid) break;
	}
	
	echo "<script type='text/javascript'>
   	jQuery(document).ready(function() {
      MINI_GROUPCHATS = ['".str_replace(" ","",$otdenom)."'];
      MINI_ANIMATE = true;
      MINI_NICKNAME = '".str_replace(" ","",$_SESSION['utente'])."';
      launchMini(true, false, 'anonymous.jappix.com');
   	});
	</script>";
}
elseif($_SESSION['valido'] == "SI" and $_SESSION['utente'] == $admin_user){
	echo "<script type='text/javascript' src='./inc/js/jquery.min.js'></script>";
	echo "<script type='text/javascript' src='https://static.jappix.com/php/get.php?l=it&amp;t=js&amp;g=mini.xml'></script>";
	$tornei = @file($percorso_cartella_dati."/tornei.php");
	$num_tornei = count($tornei);
	$denom = array();
	for($num = 1 ; $num < $num_tornei; $num++) {
		@list($otid, $otdenom, $otpart, $otserie, $otmercato_libero, $ottipo_calcolo, $otgiornate_totali, $otritardo_torneo, $otcrediti_iniziali, $otnumcalciatori, $otcomposizione_squadra, $temp1, $temp2, $temp3, $temp4, $otstato, $otmodificatore_difesa, $otschemi, $otmax_in_panchina, $otpanchina_fissa, $otmax_entrate_dalla_panchina, $otsostituisci_per_ruolo, $otsostituisci_per_schema,  $otsostituisci_fantasisti_come_centrocampisti, $otnumero_cambi_max, $otrip_cambi_numero, $otrip_cambi_giornate, $otrip_cambi_durata, $otaspetta_giorni, $otaspetta_ore, $otaspetta_minuti, $otnum_calciatori_scambiabili, $otscambio_con_soldi, $otvendi_costo, $otpercentuale_vendita, $otsoglia_voti_primo_gol, $otincremento_voti_gol_successivi, $otvoti_bonus_in_casa, $otpunti_partita_vinta, $otpunti_partita_pareggiata, $otpunti_partita_persa, $otdifferenza_punti_a_parita_gol, $otdifferenza_punti_zero_a_zero, $otdifferenza_punti_prima_soglia_meno_sei, $otdifferenza_punti_gol_premio, $otmin_num_titolari_in_formazione, $otpunti_pareggio, $otpunti_pos) = explode(",", $tornei[$num]);
		$denom[$num-1] = $otdenom;
	}
	
	$groupchats = "";
	for($num = 1 ; $num < $num_tornei; $num++) {
		$groupchats .= "'".str_replace(" ","",$denom[$num-1])."'";
		if($num < $num_tornei-1)
		$groupchats .= ",";
	}
	
	echo "<script type='text/javascript'>
	   	jQuery(document).ready(function() {
	      MINI_GROUPCHATS = [".$groupchats."];
	      MINI_ANIMATE = true;
	      MINI_NICKNAME = 'admin';
	      launchMini(true,false, 'anonymous.jappix.com');
	   	});
		</script>";	
}
?>
<!-- Fine CHAT stile facebook -->
</head>
<body>
<a name="top"></a>
<ul id="nav">
<li><a href="index.php" title="Ritorna alla pagina iniziale del sito - accesskey = h" accesskey="h"><u>H</u>ome</a></li>
<li><a href="http://fantacalciobazar.sssr.it/risorse.php" title="Collezione di utility e file utili - accesskey = r" accesskey="r"><u>R</u>isorse</a></li>
<li><a href="http://fantacalciobazar.sssr.it/risorse/" title="Wiki manuale - accesskey = s" accesskey="s"><u>S</u>viluppo</a></li>
<li><a href="http://fantacalciobazar.altervista.org/comunica/index.php" title="Forum per discutere e chiedere informazioni di vario carattere - accesskey = f" accesskey="f"><u>F</u>orum</a></li>
<li><a href="#top" title="Risale ad inizio pagina - accesskey = t" accesskey="t"><u>T</u>op</a></li>
</ul>
<div id="header">
<div class='banner_titolo'>
<?php 
if(@$_SESSION['valido'] == "SI")  {
	echo "<img src='immagini/banner_header_small.png'></img>";
}
else
	echo "<img src='immagini/banner_header.png'></img>";
?>
</div>
<div id="hmenu">
</div>
</div>
<table width='100%' cellpadding='5' align='center' summary='Tabella principale'>
<tr>
<td valign='top'>
