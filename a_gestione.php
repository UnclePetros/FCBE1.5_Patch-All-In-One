<?PHP
##################################################################################
#    FANTACALCIOBAZAR EVOLUTION
#    Copyright (C) 2003 - 2009 by Antonello Onida
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
require_once("./controlla_pass.php");
include("./header.php");

if ($_SESSION['valido'] == "SI" and $_SESSION['utente'] == $admin_user) {
	require ("./a_menu.php");

	
	if($cfv == "SI" AND isset($nfv) AND isset($lfv)){
	$voti_1x = file_get_contents($lfv);
	$voti_2x = fopen("./".$prima_parte_pos_file_voti.$nfv.".txt","w+");
	fwrite($voti_2x,$voti_1x);
	fclose($voti_2x);
	$errori= error_get_last();
	if (empty($errori)){
    $messcfv = "Errore nella copia del file: ".$errori['type'];
    $messcfv .= "\n".$errori['message'];
		} 
	else $messcfv = "File voti $nfv copiato con successo!";
	}
	if($ccfv == "SI" AND isset($clfv)){
		$voti_1 = file_get_contents($clfv);
		$voti_2 = fopen($percorso_cartella_dati."/calciatori.txt","w+");
		fwrite($voti_2,$voti_1);
		fclose($voti_2);
		$errori= error_get_last();
		if (empty($errori)){
		$messccfv = "Errore nella copia del file: ".$errori['type'];
		$messccfv .= "\n".$errori['message'];
		} 
		else $messccfv = "File calciatori.txt caricato con successo!";
	}
	if ($blocca_giornata == "chiudi") {
		$file_dati = fopen($percorso_cartella_dati."/chiusura_giornata.txt","wb+");
		flock($file_dati,LOCK_EX);
		fwrite($file_dati, "1");
		flock($file_dati,LOCK_UN);
		fclose($file_dati);
		echo "<meta http-equiv='refresh' content='0; url=a_gestione.php?messgestutente=60'>";
		exit;
	}

	if ($blocca_giornata == "apri") {
		if (@is_file($percorso_cartella_dati."/chiusura_giornata.txt")) {
			unlink ($percorso_cartella_dati."/chiusura_giornata.txt");
			echo "<meta http-equiv='refresh' content='0; url=a_gestione.php?messgestutente=61'>";
			exit;
		}
		else {
			echo "<meta http-equiv='refresh' content='0; url=a_gestione.php?messgestutente=62'>";
			exit;
		}
	}

	if ($cambia_data == "cambia_data") {
		$file_dati = fopen($percorso_cartella_dati."/data_chigio.txt","wb+");
		flock($file_dati,LOCK_EX);
		fwrite($file_dati, "$annom$mesem$giornom$oram$minutim");
		flock($file_dati,LOCK_UN);
		fclose($file_dati);
		echo "<meta http-equiv='refresh' content='0; url=a_gestione.php?messgestutente=65'>";
		exit;
	}
	$dis="";
	$dis1="";
	$dis2="";

	if (@is_file($percorso_cartella_dati."/chiusura_giornata.txt")) $status_giornata = "giornata chiusa";
	else $status_giornata = "giornata aperta";

	$data_chigio = @file($percorso_cartella_dati."/data_chigio.txt");
	$ac = substr($data_chigio[0],0,4);
	$mc = substr($data_chigio[0],4,2);
	$gc = substr($data_chigio[0],6,2);
	$orac = substr($data_chigio[0],8,2);
	$minc = substr($data_chigio[0],10,2);

	$tabella_chigio = "<div><h3>Chiusura giornata</h3>
	<font class='evidenziato'><b><u>Stato giornata: $status_giornata</u></b></font><br/>
	La prossima chiusura automatica &eacute; fissata per il giorno<br />
	<b>$def_giorno $gc - $mc - $ac</b> alle ore <b>$orac : $minc</b><br /><br/>$mextex<br/>";

	if ($status_giornata == "giornata chiusa") $tabella_chigio .= "<form method='post' action='./a_gestione.php'>
	<input type='hidden' name='blocca_giornata' value='apri' />
	<input type='hidden' name='messgestutente' value='Giornata aperta' />
	<input type='submit' name='apri' value='Apri giornata' />
	</form>";
	elseif ($status_giornata == "giornata aperta") $tabella_chigio .= "<form method='post' action='./a_gestione.php'>
	<input type='hidden' name='blocca_giornata' value='chiudi' />
	<input type='hidden' name='messgestutente' value='Giornata chiusa' />
	<input type='submit' name='chiudi' value='Chiudi giornata' />
	</form>";

	$tabella_chigio .= "<br/><br/>
	<form method='post' action='a_gestione.php'>
	<input type='hidden' name='cambia_data' value='cambia_data' />
	<select name='giornom'>";

	for ($num1 = 1 ; $num1 < 32 ; $num1++) {
		if ($num1 == $gc) $selected = "selected"; else $selected = "";
		if (strlen($num1) == 1) $num1 = "0".$num1;
		$tabella_chigio .= "<option $selected>$num1</option>";
	}
	$tabella_chigio .= "</select>";

	if (!$mc) $mc = 8;
	$mesedopo = $mc+1;
	if ($mesedopo == 13) $mesedopo = 1;
	if (strlen($mc) == 1) $mc = "0".$mc;
	if (strlen($mesedopo) == 1) $mesedopo = "0".$mesedopo;

	if (!$ac) $ac = 2011;
	$annodopo = $ac+1;

	$tabella_chigio .= "<select name='mesem'>
	<option value='$mc' selected>$mc</option>
	<option value='$mesedopo'>$mesedopo</option>
	</select>
	<select name='annom'>
	<option value='$ac' selected>$ac</option>
	<option value='$annodopo'>$annodopo</option>
	</select>
	<br/><br/>
	<select name='oram'>";

	for ($num1 = 1 ; $num1 <= 24 ; $num1++) {
		if ($num1 == $orac) $selected = "selected"; else $selected = "";
		if (strlen($num1) == 1) $num1 = "0".$num1;
		$tabella_chigio .= "<option $selected>$num1</option>";
	}
	$tabella_chigio .= "</select>
	<select name='minutim'>";

	for ($num1 = 0; $num1 < 60;) {
		if ($num1 == $minc) $selected = "selected"; else $selected = "";
		if (strlen($num1) == 1) $num1 = "0".$num1;
	$tabella_chigio .= "<option $selected>$num1</option>";
	$num1 = $num1 +5;
	}
	$tabella_chigio .= "</select><br/><br/>
	<input type='submit' value='Cambia data' />
	</form></div>";
	$tabella_giornate = "<br/><table summary='Tabella giornate' style='width: 100%; margin: 3px; padding:5px; background-color:transparent'>
	<caption>GIORNATE</caption><tr><td align='center'>";

	$tornei = @file($percorso_cartella_dati."/tornei.php");
	$num_tornei = count($tornei);
	@list($otid, $otdenom, $otpart, $otserie, $otmercato_libero, $ottipo_calcolo, $otgiornate_totali, $otritardo_torneo, $otcrediti_iniziali, $otnumcalciatori, $otcomposizione_squadra, $temp1, $temp2, $temp3, $temp4, $otstato, $otmodificatore_difesa, $otschemi, $otmax_in_panchina, $otpanchina_fissa, $otmax_entrate_dalla_panchina, $otsostituisci_per_ruolo, $otsostituisci_per_schema,  $otsostituisci_fantasisti_come_centrocampisti, $otnumero_cambi_max, $otrip_cambi_numero, $otrip_cambi_giornate, $otrip_cambi_durata, $otaspetta_giorni, $otaspetta_ore, $otaspetta_minuti, $otnum_calciatori_scambiabili, $otscambio_con_soldi, $otvendi_costo, $otpercentuale_vendita, $otsoglia_voti_primo_gol, $otincremento_voti_gol_successivi, $otvoti_bonus_in_casa, $otpunti_partita_vinta, $otpunti_partita_pareggiata, $otpunti_partita_persa, $otdifferenza_punti_a_parita_gol, $otdifferenza_punti_zero_a_zero, $otdifferenza_punti_prima_soglia_meno_sei, $otdifferenza_punti_gol_premio, $otmin_num_titolari_in_formazione, $otpunti_pareggio, $otpunti_pos, $otreset_scadenza) = explode(",", $tornei[$num1]);

	$num1 = 1;
	while ($num1 <= 38) {
		if (strlen($num1) == 1) $num1 = "0".$num1;
		if ($num1 == 11 OR $num1 == 21 OR $num1 == 31) $tabella_giornate .= "<br/><br/>";
		
		$giornata = "giornata$num1";

		for($num0 = 1; $num0 < $num_tornei; $num0++) {
			if (@is_file($percorso_cartella_dati."/".$giornata."_".$num0."_0") AND $num1 >= $prossima) {
				$tabella_giornate .= "<a href='a_giornata.php?num_giornata=$num1' class='evidenziato'>&nbsp;$num1&nbsp;</a>&nbsp;&nbsp;&nbsp;";
				if($num1 >= $prossima) $prossima = $num1+1;
			} # fine if (is_file($giornata))
		} # fine for $num0
	$num1++;
	} # fine for $num1
	
	if ($prossima < 1) $prossima = 1;
	if (strlen($prossima) == 1) $prossima = "0".$prossima;
	
	$timeout = 2;
	$old = ini_set('default_socket_timeout', $timeout);
	$file = @fopen('http://fcbe.sssr.it/dati/'.$cartella_remota.'/MCC'.$prossima.'.txt', 'r');
	$file2 = @fopen('http://fantadownload.altervista.org/mirrorFCBE/dati/'.$cartella_remota.'/MCC'.$prossima.'.txt', 'r');
	ini_set('default_socket_timeout', $old);
	//stream_set_timeout($file, $timeout);
	//stream_set_blocking($file, 0);
	
	if ($file) $lfv = "http://fcbe.sssr.it/dati/".$cartella_remota."/MCC$prossima.txt";
	elseif ($file2) $lfv = "http://fantadownload.altervista.org/mirrorFCBE/dati/".$cartella_remota."/MCC$prossima.txt";
	else {
		$dis2="disabled='disabled'";
		$lfv = "NO";}
	$file_voti_locale = "./".$prima_parte_pos_file_voti.$prossima.".txt";
	$tabella_giornate .= "<hr noshade /><div style='float: clear'>";
	
	if (@fopen($file_voti_locale,'r')) {$dis="";}
		else {$dis="disabled='disabled'";}
	

	#if ($lfv == "NO") $tabella_giornate .= "<div style='float: left; padding: 5px;'>MCC$prossima.txt non disponibile!</div>";
	#elseif (@fopen($file_voti_locale,'r')) $tabella_giornate .= "<div style='float: left; padding: 5px;'>MCC$prossima.txt presente!<br />Si pu&ograve; creare la giornata!</div>";
	#$tabella_giornate .= "Cartella voti remota\n";
	$tabella_giornate .= "<center>Cartella voti remota: <b>$cartella_remota</center></b>";	
	$tabella_giornate .= "<div style='float: left; padding: 5px;'><form method='post' action='./a_gestione.php'>
		<input type='hidden' name='cfv' value='SI' />
		<input type='hidden' name='lfv' value='$lfv' />
		<input type='hidden' name='nfv' value='$prossima' />
		<input type='submit' name='preleva_voti' $dis2 value='Preleva MCC$prossima.txt' />
		</form></div>";
	
	$tabella_giornate .= "<div style='float: left; padding: 5px;'><form method='post' action='./a_crea_giornata.php'>
	<input type='hidden' name='giornata' value='$prossima' />
	<input type='submit' name='crea_giornata' $dis value='Crea la giornata $prossima' />
	</form></div>";
	
############################### file calciatori

if (@fopen('http://fcbe.sssr.it/dati/'.$cartella_remota.'/MCC00.txt', 'r')) $clfv = "http://fcbe.sssr.it/dati/'.$cartella_remota.'/MCC00.txt";
	elseif (@fopen('http://fantadownload.altervista.org/mirrorFCBE/dati/'.$cartella_remota.'/calciatori.txt', 'r')) $clfv = "http://fantadownload.altervista.org/mirrorFCBE/dati/".$cartella_remota."/calciatori.txt";
	else {
		$clfv = "NO";
		$dis1="disabled='disabled'";}
	
	$file_voti_localec = "./dati/calciatori.txt";
	$gh=date ("j/n/Y", filemtime($file_voti_localec));
#########################	controllo presenza voti --> disattivo pulsante calciatori
#	$ultima=ultima_giornata_giocata();
#	if (@fopen("$percorso_cartella_voti/voti$ultima.txt", 'r')) $dis1="disabled='disabled'";
#		else $dis1="";

	#if ($clfv == "NO") $tabella_giornate .= "<div style='float: left; padding: 5px;'>Calciatori.txt non disponibile!</div>";
	#elseif (@fopen($file_voti_localec,'r')) $tabella_giornate .= "<div style='float: left; padding: 5px;'>calciatori.txt presente!<br />Si può aggiornare!</div>";
	if ($flag_ritardo == 0) {
	$tabella_giornate .= "<div style='float: left; padding: 5px;'><form method='post' action='./a_gestione.php'>
		<input type='hidden' name='ccfv' value='SI' />
		<input type='hidden' name='clfv' value='$clfv' />
		<input type='submit' name='carica_calciatori' $dis1 value='Carica calciatori.txt' />
		agg. al: $gh		
		</form></div>";
	}

#######################################
$tabella_giornate.="</div></td></tr></table>";

if (isset($messcfv)) $tabella_giornate .= "<div style='float: left; padding: 5px'>$messcfv</div><br /><br />";
if (isset($messccfv)) $tabella_giornate .= "<div style='float: left; padding: 5px'>$messccfv</div><br /><br />";
	
	if ($messgestutente) {
		require_once ("./inc/avvisi.php");
		$tabella_msg = "<br/><span class='evidenziato' style='color: #000000'> $avviso[$messgestutente] </span>";
	} # fine if ($messgestutente)

	if ($manutenzione == "SI") $mess_manu = "<img border='1' src='./immagini/manutenzione.gif' alt='Sito in manutenzione' vspace='10' width='150' /><br/><b>Attenzione: sito in stato di manutenzione!</b><br/><br/>";
	else unset($mess_manu);

	echo "<table summary='Amministrazione' bgcolor='$sfondo_tab' width='100%'>
	<caption>Benvenuto Amministratore!".$tabella_msg."</caption>
	<tr><td valign='top' width='50%' align='center'>".$tabella_giornate;

	#############
	# RSS forum #
	#############

	echo "<div style='text-align:left; padding:10px;'><u><b>FORUM FCBE</b></u><br/>";
	
	$RSSUrl="http://fantacalciobazar.altervista.org/syndication.php"; //rss url
	include_once "./inc/rss_fetch.php";
	$html  = "- <a href='#{link}' target='_blank'>#{title}</a> | <font size='-2'>#{pubDate}</font><br />\n";
	#$html .= "      #{description}<br />\n";
	$rss = new rss_parser($RSSUrl, 15, $html, 1);	
	echo "</div>";

	#############

	echo "</td><td valign='top' align='center' width='50%'>".$mess_manu.
	$tabella_chigio."<br/>Si evidenzia il giorno di chiusura.<br/>";
	crea_calendario_admin();

	echo "<br /><br /><div style='text-align:left'>Statistiche<hr />";
	include('./inc/online.php');
	include('./inc/flount.php');
	echo "</div></td></tr></table>";
}
else header("location: logout.php?logout=2");

include("./footer.php");
?>
