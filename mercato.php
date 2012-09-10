<?php
##################################################################################
#    FANTACALCIOBAZAR EVOLUTION
#    Copyright (C) 2003 - 2010 by Antonello Onida
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

if ($_SESSION['valido'] == "SI" and $_SESSION['utente'] != $admin_user) {
	require("./menu.php");

	if(!$_GET['vedi_operazioni']) $vedi_operazioni = "SI";

	echo $acapo."<script type='text/javascript'>
	function CreaOrario() {
	days = new Array('Lunedì','Martedì','Mercoledì','Giovedì','Venerdì','Sabato','Domenica');
	months = new Array('Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre');
	nd = new Date;
	ore = nd.getHours(); min = nd.getMinutes(); sec = nd.getSeconds();
	d=nd.getDay(); g = nd.getDate(); m = nd.getMonth()+1; a = nd.getFullYear();
	if (sec < 10) sec0 = '0'; else sec0 = '';
	if (min < 10) min0 = '0'; else min0 = '';
	if (ore < 10) ore0 = '0'; else ore0 = '';
	DinOrario = ore0 + ore + ':' + min0 + min + ':' + sec0 + sec;
	DinData = g +' '+ months[m-1] +' '+ a
	valore = DinOrario
	if (document.getElementById){
	document.getElementById('orario').innerHTML=valore;
	}
	valore = DinData
	if (document.getElementById){
	document.getElementById('data').innerHTML=valore;
	}
	setTimeout('CreaOrario()', 1000)
	}
	window.onload = CreaOrario;
	</script>".$acapo;
	
	for ($num1 = 1 ; $num1 < $otgiornate_totali+1 ; $num1++) {
		if (strlen($num1) == 1) $num1 = "0".$num1;
		$giornata_controlla = "giornata$num1";
		if (!@is_file($percorso_cartella_dati."/".$giornata_controlla."_".$_SESSION['torneo']."_".$_SESSION['serie'])) break;
		else $giornata_ultima = $num1;
	} # fine for $num1
	
	$np = 0; $nd = 0; $nc = 0; $nf = 0; $na = 0;
	$calciatori = @file($percorso_cartella_dati."/mercato_".$_SESSION['torneo']."_".$_SESSION['serie'].".txt");
	$num_calciatori = count($calciatori);
	for ($num1 = 0 ; $num1 < $num_calciatori ; $num1++) {
		$dati_calciatori[$num1] = explode(",", $calciatori[$num1]);
	}

	if ($ordinamento) {
		if ($verso == "asc") {
			function cmp1 ($a, $b) {
				global $ordinamento;
				return strcmp($a[$ordinamento], $b[$ordinamento]);
			}
		}
		else {
			function cmp1 ($a, $b) {
				global $ordinamento;
				return strcmp($b[$ordinamento], $a[$ordinamento]);
			}
		}
		usort($dati_calciatori, "cmp1");
	}

	$tab_comprati = "<tr>
	<td class='testa'>Num.</td>
	<td class='testa'>Nome</td>
	<td class='testa'>Ruolo</td>
	<td class='testa'>Costo</td>
	<td class='testa'>Proprietario</td>
	<td class='testa'>Tempo rimasto</td>
	<td class='testa'>&nbsp;</td></tr>";

	$soldi_spesi = 0;
	$num_calciatori_posseduti = 0;
	$adesso = mktime(date("H"),date("i"),0,date("m"),date("d"),date("Y"));

	for ($num1 = 0 ; $num1 < $num_calciatori ; $num1++) {
		$vecchio_proprietario = "";
		$nuovo_costo = "";
		$dati_calciatore = $dati_calciatori[$num1];

		if (count($dati_calciatore) >= 6) {
			$numero = $dati_calciatore[0];
			$nome = stripslashes($dati_calciatore[1]);
			$ruolo = $dati_calciatore[2];
			$costo = $dati_calciatore[3];
			$proprietario = $dati_calciatore[4];
			if ($_SESSION['utente'] == $proprietario) {
				$soldi_spesi = $soldi_spesi + $dati_calciatore[3];
				$num_calciatori_posseduti++;
				if ($ruolo == "P") $np++;
				else if ($ruolo == "D") $nd++;
				else if ($ruolo == "C") $nc++;
				else if ($ruolo == "F") $nf++;
				else if ($ruolo == "A") $na++;
			} # fine if ($proprietario == $_SESSION['utente'])

			$tempo_off = $dati_calciatore[5];
			$anno_off = substr($tempo_off,0,4);
			$mese_off = substr($tempo_off,4,2);
			$giorno_off = substr($tempo_off,6,2);
			$ora_off = substr($tempo_off,8,2);
			$minuto_off = substr($tempo_off,10,2);
			$secondo_off = substr($tempo_off,12,2);
			$sec_restanti = mktime($ora_off,$minuto_off,0,$mese_off,$giorno_off,$anno_off) - $adesso;
			if ($sec_restanti < 1 OR ($stato_mercato != "I" and $stato_mercato != "P")) {
				$tempo_restante = "Comprato";
				$offri = "Nessuna opzione";

				if ($_SESSION['utente'] == $proprietario and $mercato_libero != "SI" and $stato_mercato == "B") $offri = "<a href='busta_vendi_subito.php?num_calciatore=$numero' class='user'>svincola</a>";
				if ($_SESSION['utente'] != $proprietario and $mercato_libero != "SI" and $stato_mercato == "B") $offri = "<a href='busta_offerta.php?num_calciatore=$numero&amp;altro_utente=$proprietario' class='user'>offerta</a>";
				if ($_SESSION['utente'] == $proprietario and $mercato_libero != "SI" and $stato_mercato != "I" and $stato_mercato != "C") $offri = "<a href='vendi.php?num_calciatore=$numero' class='user'>vendi</a>";
				if ($_SESSION['utente'] != $proprietario and $mercato_libero != "SI" and $stato_mercato != "I" and $stato_mercato != "C") $offri = "<a href='scambia.php?num_calciatore=$numero&amp;altro_utente=$proprietario' class='user'>scambia</a>";
				if ($_SESSION['utente'] != $proprietario and $mercato_libero == "SI" and $stato_mercato == "I") $offri = "<a href='compra.php?num_calciatore=$numero' class='user'>compra</a>";
				if ($_SESSION['utente'] == $proprietario and $mercato_libero == "SI" and $stato_mercato != "I") $offri = "<a href='cambi.php?num_calciatore=$numero' class='user'>cambi</a>";
				elseif ($_SESSION['utente'] == $proprietario and $mercato_libero == "SI" and $stato_mercato == "I") $offri = "<a href='vedi_vendi_subito.php?num_calciatore=$numero' class='user'>vendi ora</a>";

				if ($chiusura_giornata == 1) $offri = "<font color='red' size='-2'>Giornata chiusa!</font>";

				if ($num1 % 2) $colore=$colore_riga_alt;
				else $colore="#FFFFFF";

				$tab_comprati .= "<tr bgcolor=$colore><td align=center>$numero</td>
				<td align='left'>$nome</td>
				<td align='center'>$ruolo</td>
				<td align='center'>$costo</td>
				<td align='center'>$proprietario</td>
				<td align='center'>$tempo_restante</td>
				<td align='center'>$offri</td></tr>";
			} # fine if ($sec_restanti < 0)
			else {
				$tempo_restante = "";
				$giorni=floor($sec_restanti/86400);
				$secondi_resto=$sec_restanti-($giorni*86400); 
				$ore=floor($secondi_resto/3600);
				$secondi_resto=$sec_restanti-($giorni*86400)-($ore*3600);
				$minuti= floor($secondi_resto/60);
				$secondi_resto = $sec_restanti-($giorni*86400)-($ore*3600)-$minuti*60;

				if ($giorni > 0) {
					if ($giorni > 1) $tempo_restante .= $giorni." giorni";
					else $tempo_restante .= $giorni." giorno";
				}

				if ($ore > 0) {
					if ($tempo_restante != "") $tempo_restante .= ", ";
					if ($ore > 1) $tempo_restante .= $ore." ore";
					else $tempo_restante .= $ore." ora";
				}

				if ($minuti > 0) {
					if ($tempo_restante != "") $tempo_restante .= ", ";
					if ($minuti > 1) $tempo_restante .= $minuti." minuti";
					else $tempo_restante .= $minuti." minuto";
				}

				if ($giorni == 0 AND $ore == 0 AND $minuti == 0 AND $secondi_resto > 0) $tempo_restante .= $secondi_resto." secondi";
				unset($giorni,$ore,$minuti,$secondi_resto);

				if ($_SESSION['utente'] != $proprietario AND $mercato_libero != "SI" AND $stato_mercato == "B") $offri = "<a href='busta_offerta.php?num_calciatore=$numero' class='user'>offri</a>";
				elseif ($_SESSION['utente'] != $proprietario and $mercato_libero != "SI") $offri = "<a href='offerta.php?num_calciatore=$numero' class='user'>rilancia</a>";
				else $offri = "Attendere";

				$vecchio_proprietario = $dati_calciatore[6];
				$nuovo_costo = $dati_calciatore[8];

				if ($vecchio_proprietario) { $proprietario_mostra = "$proprietario <font size='-2'>(offerta precedente di $vecchio_proprietario)</font>"; }
				else { $proprietario_mostra = $proprietario; }

				if ($nuovo_costo) { $costo_mostra = $nuovo_costo; }
				else { $costo_mostra = $costo; }

				if ($num1 % 2) $color=$colore_riga_alt;
				else $color="#FFFFFF";

				$tempo=$anno_off.", ".$mese_off."-1, ".$giorno_off.", ".$ora_off.", ".$minuto_off.", ".$secondo_off; #formato 2012, 8-1, 02, 13, 14
				countdown($numero,$tempo);				

				$tab_comprati .= "<tr bgcolor='$color'>
				<td align='center'>$numero</td>
				<td>$nome</td>
				<td align='center'>$ruolo</td>
				<td align='center'>$costo_mostra</td>
				<td align='center'>$proprietario_mostra</td>
				<td align='center'><div id='$numero'></div></td>
				<td align='center'>$offri</td></tr>";
			} # fine else if ($sec_restanti < 0)

		} # fine if (count($dati_calciatore) >= 6)

	} # fine for $num1

	$num_calciatori_comprabili = $max_calciatori - $num_calciatori_posseduti;

	$file = file($percorso_cartella_dati."/utenti_".$_SESSION["torneo"].".php");
	@list($outente, $opass, $opermessi, $oemail, $ourl, $osquadra, $otorneo, $oserie, $ocitta, $ocrediti, $ovariazioni, $ocambi, $oreg) = explode("<del>", $file[$_SESSION['uid']]);

	$surplus = INTVAL($ocrediti);
	$variazioni = INTVAL($ovariazioni);
	$cambi_effettuati = INTVAL($ocambi);
	$soldi_spendibili = $soldi_iniziali + $ocrediti + $ovariazioni - $soldi_spesi;
	$valore_squadra = $soldi_spesi;
	$valuta_saldo = $valuta_saldo + $surplus;
	$spesa_totale = $soldi_spesi - $variazioni;

	/*
	echo "surplus: $surplus<br />
	variazioni: $variazioni<br />
	cambi_effettuati: $cambi_effettuati<br />
	soldi_spendibili: $soldi_spendibili<br />
	valore_squadra: $valore_squadra<br />
	soldi_spesi: $soldi_spesi;<br />
	valuta_saldo: $valuta_saldo<br />
	spesa_totale: $spesa_totale";
	*/

	###################
	# Controlla squadra

	$xsquadra_ok = "NO";
	if ($num_calciatori_comprabili <= 0 or $soldi_spendibili <= 0) { $offribile = "NO"; }

	if ($max_calciatori != $num_calciatori_posseduti) {
		$num_calciatori_comprabili = $max_calciatori - $num_calciatori_posseduti;
		$schema_giocatori = "$np$nd$nc$nf$na";
		$verifica_sg = "";
		$num_giocons = count($composizione_squadra);
		for ($num1 = 0 ; $num1 < $num_giocons ; $num1++) {
			$verifica_sg .= "$composizione_squadra[$num1]<br />";
			if ($composizione_squadra[$num1] == $schema_giocatori) { $xsquadra_ok = "SI"; }
		} # fine for $num1

		if ($xsquadra_ok != "SI") {
			$inserire = "NO";
			$xsquadra_ok = "NO";
			$controlla_squadra = "";
			if ($np == substr($verifica_sg,0,1))  $controlla_squadra .= "<b>Portieri: $np su ".substr($verifica_sg,0,1)." - <font color='red'>OK</font></b><br />";
			elseif ($np > substr($verifica_sg,0,1)) $controlla_squadra .= "Portieri: $np su ".substr($verifica_sg,0,1)." - <font color='red'>Vendere esubero</font><br />";
			elseif ($np < substr($verifica_sg,0,1)) $controlla_squadra .= "Portieri: $np su ".substr($verifica_sg,0,1)." - <a href='tab_calciatori.php?ruolo_guarda=P&amp;xsquadra_ok=NO&amp;mercato_libero=$mercato_libero' class='user'>acquista</a><br />";

			if ($nd == substr($verifica_sg,1,1))  $controlla_squadra .= "<b>Difensori: $nd su ".substr($verifica_sg,1,1)." - <font color='red'>OK</font></b><br />";
			elseif ($nd > substr($verifica_sg,1,1)) $controlla_squadra .= "Difensori: $nd su ".substr($verifica_sg,1,1)." - <font color='red'>Vendere esubero</font><br />";
			elseif ($nd < substr($verifica_sg,1,1)) $controlla_squadra .= "Difensori: $nd su ".substr($verifica_sg,1,1)." - <a href='tab_calciatori.php?ruolo_guarda=D&amp;xsquadra_ok=NO&amp;mercato_libero=$mercato_libero' class='user'>acquista</a><br />";

			if ($nc == substr($verifica_sg,2,1))  $controlla_squadra .= "<b>Centrocampisti: $nc su ".substr($verifica_sg,2,1)." - <font color='red'>OK</font></b><br />";
			elseif ($nc > substr($verifica_sg,2,1)) $controlla_squadra .= "Centrocampisti: $nc su ".substr($verifica_sg,2,1)." - <font color='red'>Vendere esubero</font><br />";
			elseif ($nc < substr($verifica_sg,2,1)) $controlla_squadra .= "Centrocampisti: $nc su ".substr($verifica_sg,2,1)." - <a href='tab_calciatori.php?ruolo_guarda=C&amp;xsquadra_ok=NO&amp;mercato_libero=$mercato_libero' class='user'>acquista</a><br />";

			if ($considera_fantasisti_come == "F") {
				if ($nf == substr($verifica_sg,3,1))  $controlla_squadra .= "<b>Fantasisti: $nf su ".substr($verifica_sg,3,1)." - <font color='red'>OK</font></b><br />";
				elseif ($nf > substr($verifica_sg,3,1)) $controlla_squadra .= "Fantasisti: $nf su ".substr($verifica_sg,3,1)." - <font color='red'>Vendere esubero</font><br />";
				elseif ($nf < substr($verifica_sg,3,1)) $controlla_squadra .= "Fantasisti: $nf su ".substr($verifica_sg,3,1)." - <a href='tab_calciatori.php?ruolo_guarda=F&amp;xsquadra_ok=NO&amp;mercato_libero=$mercato_libero' class='user'>acquista</a><br />";
			}

			if ($na == substr($verifica_sg,4,1)) $controlla_squadra .= "<b>Attaccanti: $na su ".substr($verifica_sg,4,1)." - <font color='red'>OK</font></b><br />";
			elseif ($na > substr($verifica_sg,4,1)) $controlla_squadra .= "Attaccanti: $na su ".substr($verifica_sg,4,1)." - <font color='red'>Vendere esubero</font><br />";
			elseif ($na < substr($verifica_sg,4,1)) $controlla_squadra .= "Attaccanti: $na su ".substr($verifica_sg,4,1)." - <a href='tab_calciatori.php?ruolo_guarda=A&amp;xsquadra_ok=NO&amp;mercato_libero=$mercato_libero' class='user'>acquista</a><br />";
		} # fine if $xsquadra_ok

		if (@is_file($percorso_cartella_dati."/editoriale.txt"))	$linee_editoriale = @file($percorso_cartella_dati."/editoriale.txt");
		else $linee_editoriale = "";

		$num_linee_editoriale = count($linee_editoriale);
		$messaggio_editoriale = "";

		for ($num1 = 0 ; $num1 < $num_linee_editoriale; $num1++) $messaggio_editoriale .= $linee_editoriale[$num1];

		########################
		#### Layout mercato ####
		#### Squadra da formare

		if (trim($messaggi[2]) != "") echo "<div class='slogan'>".html_entity_decode($messaggi[2])."</div>";

		echo "<table summary='mercato' bgcolor='$sfondo_tab' align='center' width='100%' cellpadding='10' cellspacing='0'>
		<caption>GESTIONE CAMPIONATO</caption>
		<tr><th>Scrivania</th><th>Gestione squadra</th></tr>
		<tr><td align='left' width='50%'>
		<div style='font-size:12px; color: #cccccc'>$messaggio_editoriale</div>
		<br />Benvenuto <b>".$_SESSION['utente']."</b>!<br />Ancora la tua formazione non &egrave; completata; devi acquistare i calciatori indicati a lato e poi potrai procedere con la formazione della squadra.<br />";

		if ($stato_mercato != "I") echo "Rammenta che in questa fase non puoi fare errori di impostazione della formazione, ogni acquisto &egrave; a titolo definitivo, e non puoi annullare eventuali errori. Si consiglia di iniziare gli acquisti solo dopo aver gi&agrave; creato la squadra su carta.<br /><br />";

		echo"<br />$mess01<hr />
		Dettagli torneo:<br />
		<b><u>$otdenom</u></b><br />";

		if ($mercato_libero == "SI") echo "Modalit&agrave; Magic Cup<br /><br />";
		elseif ($mercato_libero == "NO") echo "Modalit&agrave; Asta Pubblica<br /><br />";

		if ($stato_mercato == "I") echo " <b>Fase iniziale</b><br />";
		elseif ($stato_mercato == "C") echo " Stato mercato: chiuso<br />";
		elseif ($stato_mercato == "S") echo " Stato mercato: sospeso<br />";
		elseif ($stato_mercato == "P") echo " Stato mercato: asta perenne<br />";
		elseif ($stato_mercato == "B") echo " Stato mercato: buste chiuse<br />";
		elseif ($stato_mercato == "A") echo " Stato mercato: aperto<br />";
		elseif ($stato_mercato == "R") echo " Stato mercato: riparazione<br />";

		if ($stato_mercato == "I" OR $stato_mercato == "B" OR $stato_mercato == "R") echo "<br />Lista calciatori del: " . date ("d-m-Y H:i:s.", filemtime($percorso_cartella_dati."/calciatori.txt"))."<br />";

		$data_busta_chiusa = @join('', @file("./dati/data_buste_".$_SESSION['torneo']."_0.txt"));
		$giornobuste = "$data_busta_chiusa[6]$data_busta_chiusa[7]/$data_busta_chiusa[4]$data_busta_chiusa[5]";
		$orabuste = "$data_busta_chiusa[8]$data_busta_chiusa[9]:$data_busta_chiusa[10]$data_busta_chiusa[11]";

		if ($mercato_libero == "NO" and $stato_mercato == "B") echo "<br /><div class='evidenziato'>Il termine per completare le offerte nelle buste &egrave; il <b>$giornobuste</b> alle ore <b>$orabuste</b></div>";

		if ($mercato_libero == "NO" and $stato_mercato == "I") echo "L'asta ha un periodo di attesa di giorni <b><font color='red'>$aspetta_giorni</font></b>, ore <b><font color='red'>$aspetta_ore</font></b> e minuti <b><font color='red'>$aspetta_minuti</font></b>.<br />";

		echo  "<br />La prossima chiusura automatica &egrave; fissata per <b>$def_giorno $gc.$mc.$ac</b> alle ore <b>$orac : $minc</b>";

		if ($ultgio >= 1) echo " e siamo alla giornata n. <b>$ultgio</b>.<br /><br />";
		else echo ".<br /><br />";

		include("./inc/online.php");

		echo "</td><td width='50%' align='center'>
		<div id='orario' style='text-align:right; font-size:36px; color: #585858'>Attendere...</div>
		<div id='data' style='text-align:right; font-size:18px; color: #585858'>Attendere...</div><br />";
		if ($mostra_immagini_in_login == "SI") {immagine_casuale('sx',0,0);}
		echo "<br /><br /><b><u><font color='red'>Acquisto calciatori</font></u></b><br />
		FantaEuro disponibili: <b>$soldi_spendibili</b> Fanta-Euro.<br />
		Numero di calciatori da comprare: <b>$num_calciatori_comprabili</b>.<br />$controlla_squadra</td></tr></table>";

	} # fine if ($max_calciatori != $num_calciatori_posseduti)

	else {
		########################
		#### Layout mercato ####
		#### Squadra formata

		if (@is_file($percorso_cartella_dati."/editoriale.txt")) $linee_editoriale = file($percorso_cartella_dati."/editoriale.txt");
		else $linee_editoriale = "";

		$num_linee_editoriale = count($linee_editoriale);
		$messaggio_editoriale = "";

		for ($num1 = 0 ; $num1 < $num_linee_editoriale; $num1++) $messaggio_editoriale .= $linee_editoriale[$num1];

		echo "<div style='min-width:1000px'><table summary='mercato' width='100%' cellpadding='0' cellspacing='10' border='0' style='background-color:$sfondo_tab;' >";

		if (isset($messaggi[2]) AND trim($messaggi[2]) != "") echo "<caption>Editoriale</caption><tr><td align='left' colspan='2' bgcolor='$sfondo_tab'>".html_entity_decode($messaggi[2])."</td></tr>";

		$dati_squadra = @file($percorso_cartella_dati."/squadra_".$_SESSION['utente']);
		$numtitolari = explode(",", $dati_squadra[1]);
		$numtitolari = count ($numtitolari)-1;
		$numpanchinari = explode(",", $dati_squadra[2]);
		$numpanchinari = count ($numpanchinari)-1;

		echo "<tr><td valign='top' align='center'>
		<div class='box-shadow' style='padding:5px;height:40px'>
		<div id='data' style='float:left; text-align:center; line-height:36px; font-size:25px; color: #585858'>Attendere...</div>$acapo
		<div id='orario' style='float:right; text-align:center; font-size:36px; color: #585858'>Attendere...</div>$acapo
		<div style='clear:both'></div>
		</div></td>";
		echo "<td valign='top' align='center'><div class='box-shadow' style='padding:5px;height:40px'>Ciao, <b>".$_SESSION['utente']."</b>!<br />".$acapo;
		#<div style='float:left; text-align:center;'>Server time:".date("G:i")."</div>
		include("./inc/online.php");

		echo "</td></tr><tr><td valign='top' align='left'>
		<div class='box_utente_header'>STATO SQUADRA</div>
		<div class='box_utente_content'>
		<div id='logoMain' style='float:right'><img src='immagini/loghi/$outente.jpg' class='logoMain' /></div>";
		if(is_file($percorso_cartella_dati."/classifica_".$_SESSION['torneo'])) {
			$curr_pos = 0;
			$file_classifica = file($percorso_cartella_dati."/classifica_".$_SESSION['torneo']);
			$num_linee_file_classifica = count($file_classifica);
			$trovato_utente = false;
			$num_linea = 0;
			while(!$trovato_utente && $num_linea < $num_linee_file_classifica) {
				$linea_file_classifica = trim(togli_acapo($file_classifica[$num_linea]));
				$dati_squadra = explode("##@@&&", $linea_file_classifica);
				if($dati_squadra[0] == $_SESSION['utente']){
					$curr_pos = $num_linea;
					$trovato_utente = true;
				}
				else		
					$num_linea++;
			}
			echo "<div class='posMain' style='float:right'>#".($curr_pos-1)."</div>";
		}
		echo "<b><u>$otdenom</u></b><br />";
		if($mercato_libero == "SI") echo "Modalit&agrave; Fanta Cup<br />";

		if($stato_mercato == "I") echo " <b>FASE INIZIALE</b><br />";
		elseif($stato_mercato == "P") echo " Stato mercato: asta perenne<br />";
		elseif($stato_mercato == "B") echo " Stato mercato: buste chiuse<br />";
		elseif($stato_mercato == "C") echo " Stato mercato: chiuso<br />";
		elseif($stato_mercato == "S") echo " Stato mercato: sospeso<br />";
		elseif($stato_mercato == "A") echo " Stato mercato: aperto<br />";
		elseif($stato_mercato == "R") echo " Stato mercato: riparazione<br />";

		if($mercato_libero == "NO" and $stato_mercato == "I") echo "L'asta ha un periodo di attesa di giorni <b><font color='red'>$aspetta_giorni</font></b>, ore <b><font color='red'>$aspetta_ore</font></b> e minuti <b><font color='red'>$aspetta_minuti</font></b>.<br />";

		echo"<br /><u><b>Status squadra</b></u><br/>FantaEuro residui: <b>$soldi_spendibili</b> Fanta-Euro.<br/>
		Numero di calciatori da comprare: <b>$num_calciatori_comprabili</b>.<br/>
		Titolari schierati: <b>$numtitolari</b><br/>
		Panchinari schierati: <b>$numpanchinari</b><br/><br/>
		<div style='clear:both'></div>";
		echo "<div class='box-shadow-noround_chi_gio'>La prossima chiusura automatica &egrave; fissata per <br/><b>$def_giorno $gc.$mc.$ac</b> alle ore <b>$orac : $minc</b></div>";
		if ($stato_mercato != "I" and $stato_mercato != "B" and $vedi_campetto == "SI") {
			echo "<hr><div id='boxFormazioneAttuale'><div style='float:left;width:70%'><div style='float:right;vertical-align: middle'><img src='./fantacampo.php?orientamento_campetto=1&amp;riduci=75&amp;iutente=$outente' alt='La tua squadra in campo' /></div></div>";
			echo "<div style='float:right;width:30%'><div style='float:left;vertical-align:top;padding-left: 5px;'>";
			if ($chiusura_giornata != 1) {
				echo "<form action='./squadra.php' method='post'>";
				echo "<input type='submit' value='Cambia formazione'></form>";
			}
			else{
				echo "<form action='./squadra.php' method='post'>";
				echo "<input type='submit' value='Cambia formazione' disabled='disabled'></form>";
			}
			echo "<div align='left' style='text-align: center; padding: 5px 0 5px 0'><b>PANCHINA</b></div>";
			#################################
			$dati_squadra = @file($percorso_cartella_dati."/squadra_".$outente);
			
			for ($num0 = 1; $num0 < 40; $num0++) {
				if (strlen($num0) == 1) $num0 = "0".$num0;
				if (@is_file("$percorso_cartella_voti/voti$num0.txt")) $ultima_giornata=0;
				else {
					$ultima_giornata = $num0 - 1;
					if (strlen($ultima_giornata) == 1) $ultima_giornata = "0".$ultima_giornata;
					break;
				} # fine else
			} # fine for $num1
			
			if (intval($ultima_giornata) >= 1)
				$cerca = @file("$percorso_cartella_voti/voti$ultima_giornata.txt");
			else 
				$cerca = @file("$percorso_cartella_dati/calciatori.txt");
			
			#
			$num_calciatori = count($cerca);
			$conta_panca = 0;
			$layout_panchina="";
			$tab_centro="";
			$panchina_img = explode(",", $dati_squadra[2]);
			#
			$riga_dati_calciatori =-1;
			
			foreach ($panchina_img as $valore_panca) {
				$riga_dati_calciatori = ricerca_binaria($cerca,$num_calciatori, $valore_panca);
				#
				if ($riga_dati_calciatori >= 0) {
					$maglia_calciatore = explode($separatore_campi_file_calciatori, $cerca[$riga_dati_calciatori]);
					$mnumero = $maglia_calciatore[($num_colonna_numcalciatore_file_calciatori-1)];
					$mnumero = trim($mnumero);
			
					if ($valore_panca == $mnumero){
						$mnome = stripslashes($maglia_calciatore[($num_colonna_nome_file_calciatori-1)]);
						$mnome = trim($mnome);
						$mnome = ereg_replace("\"","",$mnome);
						if (ereg("[a-z]",$mnome,$array));
						$posizione=strpos($mnome,$array[0]);
						#i luigi
						$mnome .="  ";
						$mnome1 = $mnome;
						#f luigi
						$mnome = substr($mnome,0,strpos($mnome,$array[0])-2);
						$mnome = trim($mnome);
						#i luigi
						$mnome1 = substr($mnome1,strpos($mnome1,$array[0])-1,strlen($mnome1));
						$mnome1 = trim($mnome1);
						#f luigi
						$msquadra = $maglia_calciatore[($num_colonna_squadra_file_calciatori-1)];
						$msquadra = ereg_replace("\"","",$msquadra);
						$msquadra = trim($msquadra);
						#i luigi
						$msquadra = str_replace(' ','_',$msquadra);
						if (file_exists("./immagini/m_".strtolower($msquadra).".gif")) $m_squadra = "./immagini/m_".strtolower($msquadra).".gif";
							echo "<div align='left' class=\"panchinari\"><img src='$m_squadra' style='vertical-align:middle' vspace='3' hspace='3' alt='$msquadra' title='$msquadra' />$mnome</div>";
						$conta_panca++;
					}# end-if ($valore_panca == $mnumero)
				}#  ($riga_dati_calciatori >= 0)
				elseif ($riga_dati_calciatori < 0 and trim($valore_panca) != "" )
				{
					$msquadra="no_squadra";
					$mnome="$valore_panca";
					$mnome1="";
					if (file_exists("./immagini/m_".strtolower($msquadra).".gif")) $m_squadra = "./immagini/m_".strtolower($msquadra).".gif";
						echo "<div align='left' class=\"panchinari\"><img src='$m_squadra' style='vertical-align:middle' vspace='3' hspace='3' alt='$msquadra' title='$msquadra' />$mnome</div>";
					$conta_panca++;
				}# end-if ($riga_dati_calciatori < 0 and trim($valore_panca) != "" )
		
			}#end-foreach ($panchina_img as $valore_panca)
			echo "</div></div>";
		}	
		#######################
		echo "</div></div>";
		
		########Recupero risultati giornata corrente########
		$giornata_ultima = 0;
		for ($num1 = 1; $num1 < 40 ; $num1++) {
			if (strlen($num1) == 1) $num1 = "0".$num1;
			$giornata_controlla = "giornata$num1";
			if (!@is_file($percorso_cartella_dati."/".$giornata_controlla."_".$_SESSION['torneo']."_".$_SESSION['serie'])) break;
			else $giornata_ultima = $num1;
		} # fine for $num1
		
		### Box Chat
		echo "<div style='clear:both'></div>
		<div class='box_utente_header' style='margin-top:10px'>STATISTICHE DOPO LA GIORNATA N.".intval($giornata_ultima)."</div>
		 		<div class='box_utente_content' style='text-align:center;height:40px'>
		 		Work in progress...
				</div>";
		### Fine Box Chat
		
		echo "</td><td valign='top' align='center'>";
		
		########Recupero risultati giornata corrente########
		
		if (!$giornata or $giornata > $giornata_ultima) $giornata = "$giornata_ultima";
		
		$tab_formazioni = "<tr>";
		$num_colonne = 0;
		$punti = "";
			$voti = "";
			$scontri = "";
			$num2 = 0;
		$leggendo_formazioni = "SI";
			$leggendo_punteggi = "NO";
			$leggendo_voti = "NO";
			$leggendo_scontri = "NO";
			$voti_esistenti = "NO";
		
		if ($giornata_ultima) $file_giornata = @file($percorso_cartella_dati."/giornata".$giornata."_".$_SESSION['torneo']."_".$_SESSION['serie']);
			$num_linee_file_giornata = count($file_giornata);
		for($num1 = 0 ; $num1 < $num_linee_file_giornata; $num1++) {
		$linea = togli_acapo($file_giornata[$num1]);
		if ($linea == "#@& fine formazioni #@&") $leggendo_formazioni = "NO";
		if ($leggendo_formazioni == "SI") {
					if ($linea == "#@& formazione #@&") $giocatore = "";
		if ($giocatore) {
		${
		$formazione}[$num2] = $file_giornata[$num1];
		$num2++;
		} # fine if ($giocatore)
		if ($aggiorna_giocatore) {
		$giocatore = $linea;
		$formazione = "formazione_$giocatore";
		$num2 = 0;
						$aggiorna_giocatore = "";
		} # fine if ($aggiorna_giocatore)
		if ($linea == "#@& formazione #@&") $aggiorna_giocatore = "SI";
				} # fine if ($leggendo_formazioni == "SI")
		
		if ($linea == "#@& fine voti #@&") $leggendo_voti = "NO";
				if ($leggendo_voti == "SI") {
		$voti[$num2] = $linea;
		$num2++;
				} # fine if ($leggendo_voti == "SI")
		if ($linea == "#@& voti #@&") {
		$leggendo_voti = "SI";
		$voti_esistenti = "SI";
		$num2 = 0;
		} # fine if ($linea == "#@& voti #@&")
		
		if ($linea == "#@& fine modificatore #@&") $leggendo_modificatore = "NO";
		if ($leggendo_modificatore == "SI") {
		$modificatore[$num2] = $linea;
		$num2++;
		} # fine if ($leggendo_modificatore == "SI")
		if ($linea == "#@& modificatore #@&") {
		$leggendo_modificatore = "SI";
		$modificatore_esistenti = "SI";
					$num2 = 0;
		} # fine if ($linea == "#@& modificatore #@&")
		
		if ($linea == "#@& fine punteggi #@&") $leggendo_punteggi = "NO";
		if ($leggendo_punteggi == "SI") {
					$punteggi[$num2] = $linea;
		$num2++;
		} # fine if ($leggendo_punteggi == "SI")
		if ($linea == "#@& punteggi #@&") {
		$leggendo_punteggi = "SI";
		$punteggi_esistenti = "SI";
		$num2 = 0;
		} # fine if ($linea == "#@& punteggi #@&")
		
		if ($linea == "#@& fine scontri #@&") $leggendo_scontri = "NO";
		if ($leggendo_scontri == "SI") {
		$scontri[$num2] = $linea;
					$num2++;
				} # fine if ($leggendo_scontri == "SI")
		if ($linea == "#@& scontri #@&") {
		$leggendo_scontri = "SI";
		$scontri_esistenti = "SI";
		$num2 = 0;
		} # fine if ($linea == "#@& scontri #@&")
			} # fine for $num1
		
		$file = file($percorso_cartella_dati."/utenti_".$_SESSION["torneo"].".php");
		$num_giocatori = 0;
		$linee1 =count($file);
		for($num1 = 1; $num1 < $linee1; $num1++){
				$num_giocatori++;
			}
		
			for ($num1 = 1 ; $num1 < $num_giocatori+1; $num1++) {
		@list($outente, $opass, $opermessi, $oemail, $ourl, $osquadra, $otorneo, $oserie, $ocitta, $ocrediti, $ovariazioni, $ocambi, $oreg) = explode("<del>", $file[$num1]);
		
		$nome_posizione[$num1] = $outente;
		$soprannome_squadra = $osquadra;
		
		if ($soprannome_squadra) {
		$nome_squadra_memo[$outente] = $soprannome_squadra;
		$soprannome_squadra = "<b>".$soprannome_squadra."</b>";
		} # fine if ($soprannome_squadra)
			else {
			$soprannome_squadra = "Squadra";
			$nome_squadra_memo[$osquadra] = $osquadra;
		} # fine else if ($soprannome_squadra)
		
			if ($num_colonne >= 2) {
				$tab_formazioni .= "</tr><tr>";
				$num_colonne = 0;
			} # fine if ($num_colonne >= 2)
				$num_colonne++;
				$tab_formazioni .= "<td valign='top'><h4>$soprannome_squadra di $giocatore</h4>";
				$formazione = "formazione_$giocatore";
				$formazione = $$formazione;
				$num_linee_formazione = count($formazione);
				for ($num2 = 0 ; $num2 < $num_linee_formazione; $num2++) {
					# $formazione[$num2] = ereg_replace(" ","_",$formazione[$num2]);
				$tab_formazioni .= $formazione[$num2]."<br />";
		} # fine for $num2
				$tab_formazioni .= "</td>";
		
				} # fine for $num1
		
				for ($num1 = $num_colonne ; $num1 < 2; $num1++) $tab_formazioni .= "<td>&nbsp;</td>";
				$tab_formazioni .= "</tr>";
		
				$tipo_campionato = "";
				$num_giornata = str_replace("giornata","",$giornata);
		
				if (substr($num_giornata,0,1) == 0) $num_giornata = substr($num_giornata,1);
		
				$num_campionati = count($campionato);
				reset($campionato);
		
				for($num1 = 0 ; $num1 < $num_campionati; $num1++) {
				$key_campionato = key($campionato);
				$giornate_campionato = explode("-",$key_campionato);
		
				if ($num_giornata <= $giornate_campionato[1] and $num_giornata >= $giornate_campionato[0]-1) {
				$num_giornata_campionato = $num_giornata - $giornate_campionato[0] + 1;
				$tipo_campionato = $campionato[$key_campionato];
				$g_inizio_campionato = $giornate_campionato[0];
				break;
				} # fine if ($num_giornata <= $giornate_campionato[1] and...
				next($campionato);
				} # fine for $num1
				
				if ($voti_esistenti == "SI") {
					$num_voti = count($voti);
					for ($num1 = 0 ; $num1 < $num_voti ; $num1++) {
						$dati_voti = explode("##@@&&", $voti[$num1]);
						settype($dati_voti[1],"double");
						$voto[$dati_voti[0]] = $dati_voti[1];
					} # fine for $num1
				}
				
				if($num_giornata_campionato > 0) {
					### Box Risultati Giornata corrente
					echo "<div class='box_utente_header'>RISULTATI GIORNATA N.$num_giornata_campionato</div>
					<div class='box_utente_content' style='min-width:450px;width:auto !important; width:450px'>";
					echo "<table width='100%' cellspacing='1' cellpadding='0' border='0' bgcolor='$sfondo_tab'>";
					if($tipo_campionato == "S") {
						$partite = "";
						$marcotori = "";
						$num_scontri = count($scontri);
						for ($num1 = 0 ; $num1 < $num_scontri ; $num1++) {
							$dati_scontri = explode("##@@&&", $scontri[$num1]);
							echo "<tr ";
							if($num1%2 != 0)echo"bgcolor='#E6E6E6'";else echo"bgcolor='#F3F3F3'";
							echo "><td align='center'>".$nome_squadra_memo[$dati_scontri[0]]."</td><td align='center'> - </td><td align='center'>".$nome_squadra_memo[$dati_scontri[1]]."</td>
							<td align='center'>".($voto[$dati_scontri[0]]+$otvoti_bonus_in_casa)." - ".$voto[$dati_scontri[1]]."</td>
							<td align='center'>".$dati_scontri[2]." - ".$dati_scontri[3]."</td></tr>";
						} # fine for $num1
					}
					elseif($tipo_campionato != "N") {
						$num_punteggi = count($punteggi);
						for ($num1 = 0 ; $num1 < $num_punteggi ; $num1++) {
							$dati_punteggi = explode("##@@&&", $punteggi[$num1]);
							echo "<tr ";
							if($num1%2 != 0)echo"bgcolor='#E6E6E6'";else echo"bgcolor='#F3F3F3'";
							echo "><td align='center'>".$nome_squadra_memo[$dati_punteggi[0]]."</td><td align='center'>".$dati_punteggi[1]."</td></tr>";
						} # fine for $num1
					}
					echo "</table></div>";
					### Fine Box Risultati Giornata corrente
				}
				
				if ($tipo_campionato == "S") {
					
					### Box Prossimo Turno
					$file_scontri = file($percorso_cartella_scontri."/squadre".$otpart);
					$num_linee_file_scontri = count($file_scontri);
					$trovato_prossimo_turno = false;
					$num_linea = 0;
					$num_gio_cal = 0;
					while(!$trovato_prossimo_turno && $num_linea < $num_linee_file_scontri) {
						$linea_file_scontri = trim(togli_acapo($file_scontri[$num_linea]));
						if ($linea_file_scontri == "<giornata>") $num_gio_cal++;
						if ($num_gio_cal == $num_giornata+1) {
							echo "<div class='box_utente_header' style='margin-top: 10px'>PROSSIMO TURNO</div>
							<div class='box_utente_content'>";
							echo "<table width='100%' cellspacing='1' cellpadding='0' border='0' bgcolor='$sfondo_tab'>";
							$trovato_prossimo_turno = true;
							$num_partita = $num_linea+2;
							$linea_file_scontri = trim(togli_acapo($file_scontri[$num_partita]));
							while($linea_file_scontri != "</giornata>") {
								$partita = explode("-",$linea_file_scontri);
								echo "<tr ";
								if($num_partita%2 != 0)echo"bgcolor='#E6E6E6'";else echo"bgcolor='#F3F3F3'";
								echo "><td align='center'>".$nome_squadra_memo[$nome_posizione[$partita[0]]]."</td>
								<td align='center'> - </td>
								<td align='center'>".$nome_squadra_memo[$nome_posizione[$partita[1]]]."</td>
								</tr>";
								$num_partita++;
								$linea_file_scontri = trim(togli_acapo($file_scontri[$num_partita]));
							}
							echo "</table></div>";
						}
						$num_linea++;
					}
					### Fine Box Prossimo Turno
					
				} # fine if ($tipo_campionato == "S")
				
				
				########Fine recupero risultati giornata corrente########
				
				if($num_giornata_campionato > 0) {
				### Box Classifica di giornata
				$file_classifica = file($percorso_cartella_dati."/classifica_".$_SESSION['torneo']);
				$num_linee_file_classifica = count($file_classifica);
				$trovato_prossimo_turno = false;
				$num_linea = 0;
				$trovato_classifica = false;
				$num_linea = 0;
				while(!$trovato_punteggi && $num_linea < $num_linee_file_classifica) {
					$linea_file_classifica = trim(togli_acapo($file_classifica[$num_linea]));
					if ($linea_file_classifica == "#@& classifica #@&") {
						echo "<div class='box_utente_header' style='margin-top: 10px'>CLASSIFICA DOPO LA GIORNATA N.$num_giornata_campionato</div>
						<div class='box_utente_content'>";
						echo "<table width='100%' cellspacing='1' cellpadding='0' border='0' bgcolor='$sfondo_tab'>";
						$trovato_classifica = true;
						$num_squadra = $num_linea+1;
						$linea_file_classifica = trim(togli_acapo($file_classifica[$num_squadra]));
						while($linea_file_classifica != "#@& fine classifica #@&" && ($num_squadra < $num_linea+8 )) {
							$dati_squadra = explode("##@@&&", $linea_file_classifica);
							if($num_squadra == $num_linea+1){
								if($tipo_campionato == "S"){
									echo "<tr bgcolor='#808080'><td align='center'>".$dati_squadra[0]."</td><td align='center'>"
									.$dati_squadra[1]."</td><td align='center'>".$dati_squadra[2]."</td><td align='center'>"
									.$dati_squadra[3]."</td><td align='center'>".$dati_squadra[4]."</td><td align='center'>"
									.$dati_squadra[5]."</td><td align='center'>".$dati_squadra[20]."</td><td align='center'>"
									.$dati_squadra[21]."</td>
									</tr>";
								}
								elseif($tipo_campionato == "P") {
									echo "<tr bgcolor='#808080'><td align='center'>".$dati_squadra[0]."</td><td align='center'>"
									.$dati_squadra[1]."</td>
									</tr>";
								}
								else{
									echo "<tr bgcolor='#808080'><td align='center'>".$dati_squadra[0]."</td><td align='center'>"
									.$dati_squadra[1]."</td>
									</tr>";
								}
							}
							else {
								if($tipo_campionato == "S") {
									echo "<tr ";
									if($num_squadra%2 != 0) echo"bgcolor='#E6E6E6'"; else echo"bgcolor='#F3F3F3'";
									echo "><td align='center'>".$nome_squadra_memo[$dati_squadra[0]]."</td><td align='center'>"
									.$dati_squadra[1]."</td><td align='center'>".$dati_squadra[2]."</td><td align='center'>"
									.$dati_squadra[3]."</td><td align='center'>".$dati_squadra[4]."</td><td align='center'>"
									.$dati_squadra[5]."</td><td align='center'>".$dati_squadra[20]."</td><td align='center'>"
									.$dati_squadra[21]."</td>
									</tr>";
								}
								elseif($tipo_campionato == "P") {
									echo "<tr ";
									if($num_squadra%2 != 0) echo"bgcolor='#E6E6E6'"; else echo"bgcolor='#F3F3F3'";
									echo "><td align='center'>".$nome_squadra_memo[$dati_squadra[0]]."</td><td align='center'>"
									.$dati_squadra[1]."</td>
									</tr>";
								}
								else {
									echo "<tr ";
									if($num_squadra%2 != 0) echo"bgcolor='#E6E6E6'"; else echo"bgcolor='#F3F3F3'";
									echo "><td align='center'>".$nome_squadra_memo[$dati_squadra[0]]."</td><td align='center'>"
									.$dati_squadra[1]."</td>
									</tr>";
								}
							}
							$num_squadra++;
							$linea_file_classifica = trim(togli_acapo($file_classifica[$num_squadra]));
						}
						echo "</table></div>";
					}
					$num_linea++;
				}
				### Fine Box Classifica di giornata
				}
		
		### Box Media
		echo "<div class='box_utente_header' style='margin-top: 10px'>TOP 11 DELLA GIORNATA N.$num_giornata_campionato</div>
		<div class='box_utente_content' style='height:40px'>";
		echo "Work in progress...";
		echo "</div>";
		### Fine Box Media
				
		### Box Media
		//echo "<div class='box_utente_header' style='margin-top: 10px'>MEDIA</div>
		//<div class='box_utente_content'>";
		//if (trim($messaggi[8]) != "") echo "" . html_entity_decode($messaggi[8]) . "";
		//else immagine_casuale('sx',10,5);
		//echo "</div>";
		### Fine Box Media
		
		echo "</td></tr>";
		echo "<tr><td colspan='2'><hr style='clear: both'/></td></tr><tr><td valign='top' align='center'>".$acapo;
		
		//if ($_SESSION["permessi"] >= 4) echo "<br /><a href='tc.php'>t</a><a href='evoluzioni.php'>e</a>";
		echo "
		<div class='box_utente_header'>SHOUTBOX</div>
		<div id='yshout' class='box_utente_content' style='text-align:left'>";
		echo "</div>";		
		
		echo "</div></td><td valign='top' align='center'>";
		echo "
		<div class='box_utente_header'>ULTIMI 10 MESSAGGI</div>
		<div class='box_utente_content' style='text-align:left;height:245px'>";
		ultimi10($otdenom);
		echo "</div>";
		#CHAT SHOUTBOX
		$base_url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];	
		$yshout_url = substr($base_url,0,strrpos($base_url,"/"))."/inc/yshout";
		echo "<script src='inc/yshout/js/jquery.js' type='text/javascript'></script>
		<script src='inc/yshout/js/yshout.js' type='text/javascript'></script>
		<script src='inc/js/waitUntilExists.js' type='text/javascript'></script>
		<link rel='stylesheet' href='inc/yshout/example/css/light.yshout.css' />	
		<script type='text/javascript'>
	   	new YShout({
			yPath: '$yshout_url',
			log: $otid
		});
		
		waitUntilExists('ys-input-nickname',function(){
			$('#ys-input-nickname').attr('disabled','disabled');
			$('#ys-input-nickname').val('".$_SESSION['utente']."');
		});
		</script>"; 
		#FINE CHAT SHOUTBOX
		echo "</td></tr></table></div>";
	} # fine else

	###########################################################
	if ($vedi_operazioni == "SI" AND ($mercato_libero == "NO" AND $stato_mercato != "B" OR $ordinamento)) {
		echo "</table><br /><br /><table summary='mercato' width='100%' cellspacing='1' cellpadding='2' align='center' bgcolor='$sfondo_tab'>
		<caption>Operazioni di mercato effettuate</caption>
		<tr><td align='center' height='20' colspan='7'>Ordinamento per -
		<a href='mercato.php?ordinamento=1&amp;verso=asc' class='user'>nome</a> -
		<a href='mercato.php?ordinamento=2' class='user'>ruolo</a> -
		<a href='mercato.php?ordinamento=4' class='user'>proprietario</a> -
		<a href='mercato.php?ordinamento=5&amp;verso=asc' class='user'>scadenza</a>
		-";
		if($ordinamento) {
			echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='mercato.php?ordinamento=$ordinamento&amp;verso=asc' class='user'><font size='-2'>ascendente</font></a> - <a href='mercato.php?ordinamento=$ordinamento&amp;verso=disc' class='user'><font size='-2'>discendente</font></a>";
		}
		echo "</td></tr>$tab_comprati";
	}
	else 	echo "</table>";
	
} # fine if ($_SESSION['valido'] == "SI" and $_SESSION['utente'] != $admin_user) {
else header("location: logout.php?logout=2");
#include("./footer.php");
?>
