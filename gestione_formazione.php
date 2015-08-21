<?php
##################################################################################
#    FANTACALCIOBAZAR EVOLUTION
#    Copyright (C) 2003 - 2012 by Antonello Onida
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

##################################################################################
#	Modulo per la gestione rapida della formazione
#	Sviluppato da: UnclePetros
#	Contatto: unclepetros@alice.it
##################################################################################

require_once("./controlla_pass.php");
include("./header.php");

if ($_SESSION['valido'] == "SI") {
	require ("./menu.php");
	
	$chiusura_giornata = intval(@file($percorso_cartella_dati."/chiusura_giornata.txt"));
	
	for ($num1 = 1; $num1 < 40; $num1++) {
		if (strlen($num1) == 1) $num1 = "0".$num1;
		if (@is_file("$percorso_cartella_voti/voti$num1.txt")) $ultima_giornata=0;
		else {
			$ultima_giornata = $num1 - 1;
			if (strlen($ultima_giornata) == 1) $ultima_giornata = "0".$ultima_giornata;
			break;
		} # fine else
	} # fine for $num1
	
	if ($chiusura_giornata != 1) {
		
		$message = "";
		
		if ($salva_formazione) {
			
			//creazione stringa titolari
			for ($numT = 1 ; $numT <= 11; $numT++) {
				$giocatoreCurr = "giocatore".$numT;
				$lista_titolari .= $$giocatoreCurr;
				$lista_titolari .= ",";
			}
			
			//creazione stringa riserve
			for ($numT = 12 ; $numT <= 11+$otmax_in_panchina; $numT++) {
				$giocatoreCurr = "giocatore".$numT;
				$lista_panchinari .= $$giocatoreCurr;
				$lista_panchinari .= ",";
			}
			
			$filesquadra = $percorso_cartella_dati."/squadra_".$_SESSION['utente'];
			$clinee = @file($filesquadra);
			$file_squadra = @fopen($filesquadra,"wb+");
			flock($file_squadra,LOCK_EX);
			$num_linee = count($clinee);
			if ($num_linee < 3) { $num_linee = 3; }
			$clinee[0] = "Test".$acapo;
			$clinee[1] = "$lista_titolari".$acapo;
			$clinee[2] = "$lista_panchinari".$acapo;
			for ($num = 0 ; $num < $num_linee ; $num++) {
				fwrite($file_squadra,$clinee[$num]);
			} # fine for $num
			fclose($file_squadra);
			
			$message = "<div style='color: blue;font-weight:bold;margin-top:10px;margin-left:5px'>Formazione salvata!<img src='immagini/ok2.png' valign='bottom' style='margin-left:3px'/></div>";
		}
		
		//Caricamento dati del torneo attuale
		$tornei = @file($percorso_cartella_dati."/tornei.php");
		
		unset ($otid, $otdenom, $otpart, $otserie, $otmercato_libero, $ottipo_calcolo, $otgiornate_totali, $otritardo_torneo, $otcrediti_iniziali, $otnumcalciatori, $otcomposizione_squadra, $temp1, $temp2, $temp3, $temp4, $otstato, $otmodificatore_difesa, $otschemi, $otmax_in_panchina, $otpanchina_fissa, $otmax_entrate_dalla_panchina, $otsostituisci_per_ruolo, $otsostituisci_per_schema,  $otsostituisci_fantasisti_come_centrocampisti, $otnumero_cambi_max, $otrip_cambi_numero, $otrip_cambi_giornate, $otrip_cambi_durata, $otaspetta_giorni, $otaspetta_ore, $otaspetta_minuti, $otnum_calciatori_scambiabili, $otscambio_con_soldi, $otvendi_costo, $otpercentuale_vendita, $otsoglia_voti_primo_gol, $otincremento_voti_gol_successivi, $otvoti_bonus_in_casa, $otpunti_partita_vinta, $otpunti_partita_pareggiata, $otpunti_partita_persa, $otdifferenza_punti_a_parita_gol, $otdifferenza_punti_zero_a_zero, $otdifferenza_punti_prima_soglia_meno_sei, $otdifferenza_punti_gol_premio, $otmin_num_titolari_in_formazione, $otpunti_pareggio, $otpunti_pos, $continuare, $errore, $errori, $voti, $schema_attuale, $mercato_libero, $campionato, $diff_num_giornata_file, $stato_mercato, $soldi_iniziali, $composizione_squadra, $numero_cambi_max, $rip_cambi_numero, $rip_cambi_giornate, $rip_cambi_durata, $modificatore_difesa, $schemi, $max_in_panchina, $panchina_fissa, $max_entrate_dalla_panchina, $sostituisci_per_ruolo, $sostituisci_per_schema, $sostituisci_fantasisti_come_centrocampisti, $aspetta_giorni, $aspetta_ore, $aspetta_minuti, $num_calciatori_scambiabili, $scambio_con_soldi, $vendi_costo, $percentuale_vendita, $soglia_voti_primo_gol, $incremento_voti_gol_successivi, $voti_bonus_in_casa, $punti_partita_vinta, $punti_partita_pareggiata, $punti_partita_persa, $differenza_punti_a_parita_gol, $differenza_punti_zero_a_zero, $min_num_titolari_in_formazione, $punti_pareggio, $punti_posizione, $formazione,$num_giornata_voti);
		@list($otid, $otdenom, $otpart, $otserie, $otmercato_libero, $ottipo_calcolo, $otgiornate_totali, $otritardo_torneo, $otcrediti_iniziali, $otnumcalciatori, $otcomposizione_squadra, $temp1, $temp2, $temp3, $temp4, $otstato, $otmodificatore_difesa, $otschemi, $otmax_in_panchina, $otpanchina_fissa, $otmax_entrate_dalla_panchina, $otsostituisci_per_ruolo, $otsostituisci_per_schema,  $otsostituisci_fantasisti_come_centrocampisti, $otnumero_cambi_max, $otrip_cambi_numero, $otrip_cambi_giornate, $otrip_cambi_durata, $otaspetta_giorni, $otaspetta_ore, $otaspetta_minuti, $otnum_calciatori_scambiabili, $otscambio_con_soldi, $otvendi_costo, $otpercentuale_vendita, $otsoglia_voti_primo_gol, $otincremento_voti_gol_successivi, $otvoti_bonus_in_casa, $otpunti_partita_vinta, $otpunti_partita_pareggiata, $otpunti_partita_persa, $otdifferenza_punti_a_parita_gol, $otdifferenza_punti_zero_a_zero, $otdifferenza_punti_prima_soglia_meno_sei, $otdifferenza_punti_gol_premio, $otmin_num_titolari_in_formazione, $otpunti_pareggio, $otpunti_pos) = explode(",", $tornei[$_SESSION['torneo']]);
		
		
		$timeout = 2;
		$old = ini_set('default_socket_timeout', $timeout);
		$file = @fopen('http://fcbe.sssr.it/dati/_stats', 'r');
		$file2 = @fopen('http://fantadownload.altervista.org/mirrorFCBE/dati/2012/_stats', 'r');
		ini_set('default_socket_timeout', $old);
		//stream_set_timeout($file, $timeout);
		//stream_set_blocking($file, 0);
		
		if ($file) $stat_1 = file_get_contents('http://fcbe.sssr.it/dati/_stats');
		else if ($file2) $stat_1 = file_get_contents('http://fantadownload.altervista.org/mirrorFCBE/dati/2012/_stats');
		
		if (isset($stat_1)) {
		
			$ok_pre="SI";
			$stat_x=array();
			$stat_1 =preg_replace('/\n/', ' ', $stat_1);
			$stat_x = unserialize($stat_1);
			if (@!fopen($percorso_cartella_dati.'/_stats', 'r')) {
				$stat_2 = fopen($percorso_cartella_dati.'/_stats', 'w+');
				fwrite($stat_2,"test");
				fclose($stat_2);
			}
			$dati = array();
			$fd = file_get_contents($percorso_cartella_dati.'/_stats');
			$fd = preg_replace('/\n/', ' ', $fd);
			$dati = unserialize($fd);
			if ($stat_x != $dati) {
				$stat_2 = fopen($percorso_cartella_dati."/_stats","w");
				fwrite($stat_2,$stat_1);
				fclose($stat_2);
				$fd = file_get_contents($percorso_cartella_dati.'/_stats');
				$fd = preg_replace('/\n/', ' ', $fd);
				$dati = unserialize($fd);
			}
		}else  $ok_pre ="NO";

		//Fine recupero statistiche dal sito Gazzetta
		
		//Caricamento dati utente
		$file = file($percorso_cartella_dati."/utenti_".$_SESSION["torneo"].".php");
		@list($outente, $opass, $opermessi, $oemail, $ourl, $osquadra, $otorneo, $oserie, $ocitta, $ocrediti, $ovariazioni, $ocambi, $oreg) = explode("<del>", $file[$_SESSION['uid']]);
		
		### Caricamento rosa squadra		
		$rosa_squadra = array();
		$calciatori = @file($percorso_cartella_dati."/mercato_".$_SESSION['torneo']."_".$_SESSION['serie'].".txt");
		
		$num_calciatori = count($calciatori);
		for ($num2 = 0 ; $num2 < $num_calciatori ; $num2++) {
			$dati_calciatore = explode(",", $calciatori[$num2]);
			$proprietario = $dati_calciatore[4];
			$numero = $dati_calciatore[0];
			$ruolo = $dati_calciatore[2];
			$costo = $dati_calciatore[3];
			$nome = stripslashes($dati_calciatore[1]);
		
			if ($proprietario == $outente) {
				$rosa_squadra["$numero"] = $ruolo.",".$nome.",".$proprietario.",".$costo;
			} # fine if ($proprietario == $outente)
		} # fine for $num2
		###	Fine Caricamento rosa squadra
		
		### Aggiunta squadra appartenenza giocatore
		if (intval($ultima_giornata) >= 1)
			$calciatori = file("$percorso_cartella_voti/voti$ultima_giornata.txt");
		else
			$calciatori = @file("$percorso_cartella_dati/calciatori.txt");
		
		$num_calciatori = count($calciatori);
		for ($num2 = 0 ; $num2 < $num_calciatori ; $num2++) {
			$dati_calciatore = explode("|", $calciatori[$num2]);

			if(isset($rosa_squadra["$dati_calciatore[0]"])){
				$rosa_squadra["$dati_calciatore[0]"] = $dati_calciatore[0].",".$rosa_squadra["$dati_calciatore[0]"].",".substr($dati_calciatore[3],1,strlen($dati_calciatore[3])-2);
			}
		}
		
		//Caricamento formazione salvata
		$dati_formazione = "";
		if(@is_file($percorso_cartella_dati."/squadra_".$outente))
			$dati_formazione = @file($percorso_cartella_dati."/squadra_".$outente);
		$titolari = array(); $panchina = array(); $modulo = "";
		if(isset($dati_formazione[1])) $titolari = explode(",", $dati_formazione[1]);
		if(isset($dati_formazione[2])) $panchina = explode(",", $dati_formazione[2]);
		
		//Recupero del modulo adottato a partire dalla formazione salvata
		$num_p = 0;$num_d = 0;$num_c = 0;$num_a = 0;
		if($titolari){
			foreach( $titolari as $gioc ){
				$dati_giocatore = explode(",", $rosa_squadra["$gioc"]);
				if($dati_giocatore[1] == "P") $num_p++;
				if($dati_giocatore[1] == "D") $num_d++;
				if($dati_giocatore[1] == "C") $num_c++;
				if($dati_giocatore[1] == "A") $num_a++;
			}
				
			$modulo = $num_d."-".$num_c."-".$num_a;
		}
		echo "<table summary='gestioneFormazione' bgcolor='$sfondo_tab' align='center' width='100%' cellpadding='5' cellspacing='0'>
		<caption>GESTIONE FORMAZIONE</caption><tr><td style='min-width:900px'>";
		echo "<div id='main3cols'>
		<div style='float: left;width:48%;margin-left:5px;margin-top:10px'>
		<div class='box_utente_header'>LA TUA ROSA</div>
		<div class='box_utente_content'>
		<div id='portieri'>
		<div class='header'>PORTIERI<img src='immagini/circle_green.png' class='floatRight' onclick='toggle(this);return false;'></div><div>";
		foreach ($rosa_squadra as $key => $row) {
			$riga_calciatore = explode(",", $row);
			$codice_calciatore = $riga_calciatore[0];
			$nome_calciatore = stripslashes($riga_calciatore[2]);
			$ruolo_calciatore = $riga_calciatore[1];
			$costo_calciatore = $riga_calciatore[4];
			$squadra_calciatore = $riga_calciatore[5];
			$cognome_calciatore = estraiCognome($nome_calciatore);
			
			//Formattazione in html delle statistiche sulla squadra del calciatore
			if ($ok_pre=="SI") $stat_squadra = statsToHtml($dati, $squadra_calciatore, $nome_calciatore);
			else $stat_squadra = "Nessun dato";
			
			if($ruolo_calciatore == "P" ){
				echo "<div id='row'><div id='blockrow' style='float:left;width:54%;border-right:1px solid #c3c3c3;border-top-right-radius:0px; border-bottom-right-radius:0px'>
				<a href='#' onclick='gestisciInserimentoPortiereSuNome(this);return false;' cognome=\"$cognome_calciatore\" squadra='".strtolower($squadra_calciatore)."' style=\"background-image:url('immagini/".strtolower($squadra_calciatore).".gif');background-position: -30px -15px\" onclick='gestisciInserimentoPortiere(this);return false;' cognome='".$cognome_calciatore."' codice='".$codice_calciatore."'>".$nome_calciatore."</a>
				</div>";
				echo "<div style='float:left;width:36%;height:22px;'>
				$stat_squadra
				</div>";
				$img_checkbox = "";
				if(in_array("$codice_calciatore", $titolari) || in_array("$codice_calciatore", $panchina))
					if(in_array("$codice_calciatore", $titolari))
						$img_checkbox = 'immagini/checkbox_on.png';
					else
						$img_checkbox = 'immagini/checkbox_on_p.png';
				else
					$img_checkbox = 'immagini/checkbox_off.png';
				echo "<div id='blockrow' style='float:right;width:7%;border-left:1px solid #c3c3c3;border-top-left-radius:0px; border-bottom-left-radius:0px'>
				<a href='#' onclick='gestisciInserimentoPortiere(this);return false;' cognome=\"$cognome_calciatore\" squadra='".strtolower($squadra_calciatore)."' codice='".$codice_calciatore."'><img class='check' src='$img_checkbox' style='width: 23px'/></a>
				</div>";
				echo "<div style='clear:both'></div></div>";
			}				
		}
		echo "</div></div>
		<div id='difensori'>
		<div class='header'>DIFENSORI<img src='immagini/circle_green.png' class='floatRight' onclick='toggle(this);return false;'></div><div>";
		foreach ($rosa_squadra as $key => $row) {
			$riga_calciatore = explode(",", $row);
			$codice_calciatore = $riga_calciatore[0];
			$nome_calciatore = stripslashes($riga_calciatore[2]);
			$ruolo_calciatore = $riga_calciatore[1];
			$costo_calciatore = $riga_calciatore[4];
			$squadra_calciatore = $riga_calciatore[5];
			$cognome_calciatore = estraiCognome($nome_calciatore);

			//Formattazione in html delle statistiche sulla squadra del calciatore
			if ($ok_pre=="SI") $stat_squadra = statsToHtml($dati, $squadra_calciatore, $nome_calciatore);
			else $stat_squadra = "Nessun dato";
				
		if($ruolo_calciatore == "D" ){
				echo "<div id='row'><div id='blockrow' style='float:left;width:54%;border-right:1px solid #c3c3c3;border-top-right-radius:0px; border-bottom-right-radius:0px'>
				<a href='#' onclick='gestisciInserimentoDifensoreSuNome(this);return false;' cognome=\"$cognome_calciatore\" squadra='".strtolower($squadra_calciatore)."' style=\"background-image:url('immagini/".strtolower($squadra_calciatore).".gif');background-position: -30px -15px\" onclick='inserisciCessione(this);return false;' costo='".$costo_calciatore."' codice='".$codice_calciatore."'>".$nome_calciatore."</a>
				</div>";
				echo "<div style='float:left;width:36%;height:22px;'>
				$stat_squadra
				</div>";
				$img_checkbox = "";
				if(in_array("$codice_calciatore", $titolari) || in_array("$codice_calciatore", $panchina))
					if(in_array("$codice_calciatore", $titolari))
						$img_checkbox = 'immagini/checkbox_on.png';
					else
						$img_checkbox = 'immagini/checkbox_on_p.png';
				else
					$img_checkbox = 'immagini/checkbox_off.png';
				echo "<div id='blockrow' style='float:right;width:7%;border-left:1px solid #c3c3c3;border-top-left-radius:0px; border-bottom-left-radius:0px'>
				<a href='#' onclick='gestisciInserimentoDifensore(this);return false;' cognome=\"$cognome_calciatore\" squadra='".strtolower($squadra_calciatore)."' codice='".$codice_calciatore."'><img class='check' src='$img_checkbox' style='width: 23px'/></a>
				</div>";
				echo "<div style='clear:both'></div></div>";
			}
		}			
		echo "</div></div>
		<div id='centrocampisti'>
		<div class='header'>CENTROCAMPISTI<img src='immagini/circle_green.png' class='floatRight' onclick='toggle(this);return false;'></div><div>";
		foreach ($rosa_squadra as $key => $row) {
			$riga_calciatore = explode(",", $row);
			$codice_calciatore = $riga_calciatore[0];
			$nome_calciatore = stripslashes($riga_calciatore[2]);
			$ruolo_calciatore = $riga_calciatore[1];
			$costo_calciatore = $riga_calciatore[4];
			$squadra_calciatore = $riga_calciatore[5];
			$cognome_calciatore = estraiCognome($nome_calciatore);

			//Formattazione in html delle statistiche sulla squadra del calciatore
			if ($ok_pre=="SI") $stat_squadra = statsToHtml($dati, $squadra_calciatore, $nome_calciatore);
			else $stat_squadra = "Nessun dato";
				
		if($ruolo_calciatore == "C" ){
				echo "<div id='row'><div id='blockrow' style='float:left;width:54%;border-right:1px solid #c3c3c3;border-top-right-radius:0px; border-bottom-right-radius:0px'>
				<a href='#' onclick='gestisciInserimentoCentrocampistaSuNome(this);return false;' cognome=\"$cognome_calciatore\" squadra='".strtolower($squadra_calciatore)."' style=\"background-image:url('immagini/".strtolower($squadra_calciatore).".gif');background-position: -30px -15px\" onclick='inserisciCessione(this);return false;' costo='".$costo_calciatore."' codice='".$codice_calciatore."'>".$nome_calciatore."</a>
				</div>";
				echo "<div style='float:left;width:36%;height:22px;'>
				$stat_squadra
				</div>";
				$img_checkbox = "";
				if(in_array("$codice_calciatore", $titolari) || in_array("$codice_calciatore", $panchina))
					if(in_array("$codice_calciatore", $titolari))
						$img_checkbox = 'immagini/checkbox_on.png';
					else
						$img_checkbox = 'immagini/checkbox_on_p.png';
				else
					$img_checkbox = 'immagini/checkbox_off.png';
				echo "<div id='blockrow' style='float:right;width:7%;border-left:1px solid #c3c3c3;border-top-left-radius:0px; border-bottom-left-radius:0px'>
				<a href='#' onclick='gestisciInserimentoCentrocampista(this);return false;' cognome=\"$cognome_calciatore\" squadra='".strtolower($squadra_calciatore)."' codice='".$codice_calciatore."'><img class='check' src='$img_checkbox' style='width: 23px'/></a>
				</div>";
				echo "<div style='clear:both'></div></div>";
			}
		}
		echo "</div></div>
		<div id='attaccanti'>
		<div class='header'>ATTACCANTI<img src='immagini/circle_green.png' class='floatRight' onclick='toggle(this);return false;'></div><div>";
		foreach ($rosa_squadra as $key => $row) {
			$riga_calciatore = explode(",", $row);
			$codice_calciatore = $riga_calciatore[0];
			$nome_calciatore = stripslashes($riga_calciatore[2]);
			$ruolo_calciatore = $riga_calciatore[1];
			$costo_calciatore = $riga_calciatore[4];
			$squadra_calciatore = $riga_calciatore[5];
			$cognome_calciatore = estraiCognome($nome_calciatore);

			//Formattazione in html delle statistiche sulla squadra del calciatore
			if ($ok_pre=="SI") $stat_squadra = statsToHtml($dati, $squadra_calciatore, $nome_calciatore);
			else $stat_squadra = "Nessun dato";
				
		if($ruolo_calciatore == "A" ){
				echo "<div id='row'><div id='blockrow' style='float:left;width:54%;border-right:1px solid #c3c3c3;border-top-right-radius:0px; border-bottom-right-radius:0px'>
				<a href='#' onclick='gestisciInserimentoAttaccanteSuNome(this);return false;' cognome=\"$cognome_calciatore\" squadra='".strtolower($squadra_calciatore)."' style=\"background-image:url('immagini/".strtolower($squadra_calciatore).".gif');background-position: -30px -15px\" onclick='inserisciCessione(this);return false;' costo='".$costo_calciatore."' codice='".$codice_calciatore."'>".$nome_calciatore."</a>
				</div>";
				echo "<div style='float:left;width:36%;height:22px;'>
				$stat_squadra
				</div>";
				$img_checkbox = "";
				if(in_array("$codice_calciatore", $titolari) || in_array("$codice_calciatore", $panchina)){
					if(in_array("$codice_calciatore", $titolari))
						$img_checkbox = 'immagini/checkbox_on.png';
					else
						$img_checkbox = 'immagini/checkbox_on_p.png';
				}
				else
					$img_checkbox = 'immagini/checkbox_off.png';
				echo "<div id='blockrow' style='float:right;width:7%;border-left:1px solid #c3c3c3;border-top-left-radius:0px; border-bottom-left-radius:0px'>
				<a href='#' onclick='gestisciInserimentoAttaccante(this);return false;' cognome=\"$cognome_calciatore\" squadra='".strtolower($squadra_calciatore)."' codice='".$codice_calciatore."'><img class='check' src='$img_checkbox' style='width: 23px'/></a>
				</div>";
				echo "<div style='clear:both'></div></div>";
			}
		}	
		echo "</div></div>
		</div></div>
		<div style='float:right; width:50%;margin-top:10px'>
		<div style='width:440px;margin:auto'>
		<div id='pulsanti' class='box-simple' style='margin-bottom: 10px'>
		<form name='salvaFormazione' method='post' action='./gestione_formazione.php'><input type='submit' class='' name='Azzera' value='Azzera' style='float:left;height:45px' onclick='azzeraFormazione();return false;'/>
		<div id='message' style='float:left;margin-left:25px'>$message</div>
		<input type='hidden' name='salva_formazione' value='true' />
		<input type='submit' class='' name='salvaFormazione' value='Salva Formazione' style='float:right;height:45px'/>
		<div style='clear:both'></div>
		</div>
		<div class='header' style='box-shadow: 5px 5px 5px #CCCCCC;height:26px;line-height:26px'>
		<div style='float:left;margin-left:20px'>FORMAZIONE IN CAMPO</div>
		<div style='float:right;margin-right:2px'>Modulo: <select id='modulo' onchange='disponiModuloInCampo(this)' numPanchina='$otmax_in_panchina'>"; 
		$schemi = explode("-", $otschemi);
		$schemaFull = "";
		foreach($schemi as $schema){
			if(strlen($schema) == 4)
				$schemaFull = substr($schema,1,1)."-".substr($schema,2,1)."-".substr($schema,3,1);
			else if(strlen($schema) == 5) 
				$schemaFull = substr($schema,1,1)."-".substr($schema,2,1)."-".substr($schema,4,1);
			else 
				$schemaFull = "non valido";
			echo "<option value='".$schemaFull."'>".$schemaFull."</option>";
		}
		echo "<select></div><div style='clear:both'></div></div>
		<div id='campo'>";
		for($i = 0; $i < 11; $i++){
			$img_avatar="";$nome_avatar = "";$background_url = "";
			$class_nome_avatar = "nome_avatar_clean";
			if($titolari[$i]){
				$num_avatar = $titolari[$i];
				$dati_calciatore = explode(",",$rosa_squadra["$num_avatar"]);
				$img_avatar = "<img src='immagini/t_".strtolower($dati_calciatore[5]).".png'/>";
				$background_url = "background-image: none";
				$nome_avatar = estraiCognome(stripslashes($dati_calciatore[2]));
				$class_nome_avatar = "nome_avatar";
			}
			echo "<div id='avatar".($i+1)."' class='avatar' style='$background_url;display:none'>
					<div id='avatar".($i+1)."_img' class='img_avatar'>$img_avatar</div>
					<div id='avatar".($i+1)."_nome' class='$class_nome_avatar'>$nome_avatar</div>
					<input type='hidden' name='giocatore".($i+1)."' value='".$num_avatar."' />
				</div>";
		}
		$tipo_panchina = ""; $tipo_sostituzione = "";
		if($otpanchina_fissa == "SI") $tipo_panchina = "fissa";
		if($otsostituisci_per_schema == "SI") $tipo_sostituzione = "schema";
		echo "</div>
		<div id='panchinaFull' style='margin-top:10px;border-radius:8px'>
		<div class='header'>PANCHINA</div>
		<div id='panchina' class='box_utente_content_simple' tipoPanchina='$tipo_panchina' tipoSostituzione='$tipo_sostituzione'>";
		$nump=0;$numd=0;$numc=0;$numa=0;
		for($i = 0; $i < $otmax_in_panchina; $i++){
			$img_avatar="";$nome_avatar = "";$background = "";
			$class_nome_avatar = "nome_avatar_clean_panc";
			$ruolo_panc = ""; $posPanc = 0;
			if($panchina[$i]){
				$num_avatar = $panchina[$i];
				$dati_calciatore = explode(",",$rosa_squadra["$num_avatar"]);
				$ruolo_panc = $dati_calciatore[1];
				$img_avatar = "<img src='immagini/t_".strtolower($dati_calciatore[5]).".png'/>";
				$background_url = "background-image: none";
				$nome_avatar = estraiCognome(stripslashes($dati_calciatore[2]));
				$class_nome_avatar = "nome_avatar";
				if($ruolo_panc == 'P') {$nump++;$posPanc=1;}
				else if($ruolo_panc == 'D') {$numd++;$posPanc=10+$numd;}
				else if($ruolo_panc == 'C') {$numc++;$posPanc=20+$numc;}
				else if($ruolo_panc == 'A') {$numa++;$posPanc=30+$numa;}
			}
			echo "<div id='avatar".($i+12)."' class='avatar' ruolo='$dati_calciatore[1]' posPanc='$posPanc' style='$background_url;display:none'>
			<div id='avatar".($i+12)."_img' class='img_avatar'>$img_avatar</div>
			<div id='avatar".($i+12)."_nome' class='$class_nome_avatar'>$nome_avatar</div>
			<input type='hidden' name='giocatore".($i+12)."' value='".$num_avatar."' />
			</div>";
		}
		echo "</div></div></div></div></form>
		<div style='clear:both'></div>
		</div>
		</td></tr></table>";
		if($modulo == ""){
			$modulo = substr($schemi[0],1,1)."-".substr($schemi[0],2,1)."-".substr($schemi[0],3,1);
		}
		echo "<script>$(document).ready(function(){
			init();";
		if($titolari) echo "disponiFormazioneInCampo('$modulo');";
		else echo "disponiModuloInCampo($('#modulo'));";
		echo "});</script>";
		
	} # fine if ($chiusura_giornata != 1)
	else {
		echo "<div class='box-shadow' style='height:90px;line-height:90px'><span style='font-weight:bold'>
		Giornata chiusa. Non è più consentito effettuare operazioni per questa giornata di campionato.
		</span></div>";
	}
}
else
	echo"<meta http-equiv='refresh' content='0; url=logout.php'>";
?>
