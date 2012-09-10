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
require_once ("./controlla_pass.php");
include("./header.php");

  if($attiva_multi == "SI") {
		$frase='INDICA PER QUALE TORNEO VUOI INVIARE LE FORMAZIONI';
		$vedi_tornei_attivi = "<select name='l_torneo' onchange=\"cambiaCommento(this.options[this.selectedIndex])\">";
		$vedi_tornei_attivi .= "<option value=''>Scegli il torneo</option>";
		$tornei = @file("$percorso_cartella_dati/tornei.php");
		$num_tornei = 0;
			for($num1 = 0; $num1 < count($tornei); $num1++){
			$num_tornei++;
			}

			for ($num1 = 1 ; $num1 < $num_tornei; $num1++) {
			@list($otid, $otdenom) = explode(",", $tornei[$num1]);
			### Calcolo giornata corrente
			for ($num11 = 1; $num11 < 40 ; $num11++) {
				if (strlen($num11) == 1) $num11 = "0".$num11;
				if (@is_file($percorso_cartella_dati."/giornata".$num11."_".$otid."_0")) $num_giornata = $num11+1;
				else break;
			} # fine for $num1
			### Fine Calcolo giornata corrente
			$vedi_tornei_attivi .= "<option value='$otid' id='".intval($num_giornata)."'>$otdenom</option>";
			} # fine for $num1

		$vedi_tornei_attivi .= "</select>";

		}
		else 
		$vedi_tornei_attivi = "<input type='hidden' name='l_torneo' value='1' />";

if ($_SESSION['valido'] == "SI" and $_SESSION['permessi'] <= 4) {
require ("./menu.php");

##################################
# Invio email

	if ($gestione_email == "mail_anteprima" or $gestione_email == "mail_OK") {

	########################
	# Layout pagina

	$mail_formazione = "";
	$color = "ghostwhite";
	$nome_squadra = "tutti";
	############################################

	$tab_formazioni = "";
	$tabellini = array();
	$num_colonne = 0;
	$punti = "";
	$voti = "";
	$scontri = "";
	$num2 = 0;
	$rosa_squadra = array();
	$formazione = array();
	$rosa_pos = 0;
	$num_giornata = 1;
	
	### Caricamento dati torneo
	$tornei = @file($percorso_cartella_dati."/tornei.php");
		$num_tornei = count($tornei);
	
	for($num = 1 ; $num < $num_tornei; $num++) {
	@list($otid, $otdenom, $otpart, $otserie, $otmercato_libero, $ottipo_calcolo, $otgiornate_totali, $otritardo_torneo, $otcrediti_iniziali, $otnumcalciatori, $otcomposizione_squadra, $temp1, $temp2, $temp3, $temp4, $otstato, $otmodificatore_difesa, $otschemi, $otmax_in_panchina, $otpanchina_fissa, $otmax_entrate_dalla_panchina, $otsostituisci_per_ruolo, $otsostituisci_per_schema,  $otsostituisci_fantasisti_come_centrocampisti, $otnumero_cambi_max, $otrip_cambi_numero, $otrip_cambi_giornate, $otrip_cambi_durata, $otaspetta_giorni, $otaspetta_ore, $otaspetta_minuti, $otnum_calciatori_scambiabili, $otscambio_con_soldi, $otvendi_costo, $otpercentuale_vendita, $otsoglia_voti_primo_gol, $otincremento_voti_gol_successivi, $otvoti_bonus_in_casa, $otpunti_partita_vinta, $otpunti_partita_pareggiata, $otpunti_partita_persa, $otdifferenza_punti_a_parita_gol, $otdifferenza_punti_zero_a_zero, $otdifferenza_punti_prima_soglia_meno_sei, $otdifferenza_punti_gol_premio, $otmin_num_titolari_in_formazione, $otpunti_pareggio, $otpunti_pos) = explode(",", $tornei[$num]);
	if($otid == $_SESSION['torneo']) break;
	}
	### Fine Caricamento dati torneo
	
	### Calcolo giornata corrente
	for ($num1 = 1; $num1 < 40 ; $num1++) {
		if (strlen($num1) == 1) $num1 = "0".$num1;
		if (@is_file($percorso_cartella_dati."/giornata".$num1."_".$otid."_0")) $num_giornata = $num1+1;
		else break;
	} # fine for $num1
	### Fine Calcolo giornata corrente
	
	$file = @file($percorso_cartella_dati."/utenti_".$otid.".php");
	$linee = count($file);
	for ($num1 = 1 ; $num1 < $linee; $num1++) {
	@list($outente, $opass, $opermessi, $oemail, $ourl, $osquadra, $otorneo, $oserie, $ocittà, $ocrediti, $ovariazioni, $ocambi, $oreg) = explode("<del>", $file[$num1]);
		$nome_posizione[$num1] = $outente;
	$soprannome_squadra = $osquadra;
	
	if ($soprannome_squadra) {
	$nome_squadra_memo[$outente] = $soprannome_squadra;
	$soprannome_squadra = "<b>".$soprannome_squadra."</b>";
	} # fine if ($soprannome_squadra)
	else {
	$soprannome_squadra = "Squadra";
	$nome_squadra_memo[$outente] = $outente;
	} # fine else if ($soprannome_squadra)
	
	### Caricamento rosa squadra
	$calciatori = @file($percorso_cartella_dati."/mercato_".$storneo."_0.txt");
		
		$num_calciatori = count($calciatori);
	for ($num2 = 0 ; $num2 < $num_calciatori ; $num2++) {
	$dati_calciatore = explode(",", $calciatori[$num2]);
	$proprietario = $dati_calciatore[4];
	
	if ($proprietario == $outente) {
	$numero = $dati_calciatore[0];
	$ruolo = $dati_calciatore[2];
	$nome = stripslashes($dati_calciatore[1]);
	$rosa_squadra["$numero"] = $ruolo.",".$nome.",".$proprietario;
	} # fine if ($proprietario == $outente)
	} # fine for $num2
	###	Fine Caricamento rosa squadra
	$calciatori = @file($percorso_cartella_dati."/calciatori.txt");
	$num_calciatori = count($calciatori);
			for ($num2 = 0 ; $num2 < $num_calciatori ; $num2++) {
	$dati_calciatore = explode("|", $calciatori[$num2]);
		if(isset($rosa_squadra["$dati_calciatore[0]"]))
		$rosa_squadra["$dati_calciatore[0]"] = $rosa_squadra["$dati_calciatore[0]"].",".substr($dati_calciatore[3],1,strlen($dati_calciatore[3])-2);
	}
		### Aggiunta squadra appartenenza giocatore
	
		### Fine Aggiunta squadra appartenenza giocatore
	
		### Caricamento formazione corrente
		$dati_squadra = @file($percorso_cartella_dati."/squadra_".$outente);
		$formazione = explode(",", $dati_squadra[1].",".$dati_squadra[2]);
		###	Fine Caricamento formazione corrente
			
		$tab_formazioni = "<table width='100%' cellpadding='0' cellspacing='1' bgcolor='$sfondo_tab'>
			<tr bgcolor='#808080'><td align='center'><font size='-2'><u>R</u></font></td><td align='center'><font size='-2'><u>Calciatore</u></font></td>
			<td align='center'>Squadra</td></tr>";
			$num_linee_formazione = count($formazione);
				for ($num2 = 0 ; $num2 < $num_linee_formazione; $num2++) {
		$riga_calciatore = explode(",", $rosa_squadra["$formazione[$num2]"]);
		$nome_calciatore = stripslashes($riga_calciatore[1]);
		if ($num2 % 2) $colore="white"; else $colore=$colore_riga_alt;
		//if (strlen($nome_calciatore) > 20)
		//	$nome_calciatore = substr($nome_calciatore,0,20);
		$tab_formazioni .= "<tr bgcolor='$colore' height='21px'>
		<td align='center' style='padding: 0px 1px 0px 1px'>$riga_calciatore[0]</td>
				<td  style='padding: 0px 1px 0px 1px'><a href='stat_calciatore.php?num_calciatore=$formazione[$num2]&amp;escludi_controllo=$escludi_controllo' >$nome_calciatore</a></td>
		<td style='padding: 0px 1px 0px 1px'>$riga_calciatore[3]</td></tr>";
				} # fine for $num2
		$tab_formazioni .= "</table>";
		$tabellini[$outente] = $tab_formazioni;
	
	} # fine for $num1
	//for ($num1 = $num_colonne ; $num1 < 2; $num1++) $tab_formazioni .= "<td>&nbsp;</td>";
	
	$mail_formazione .= "<table summary='guarda_giornata' bgcolor='$sfondo_tab' align='center' width='100%' cellpadding='10' cellspacing='0'>
		<caption>FORMAZIONI UFFICIALI GIORNATA N.$num_giornata</caption><tr><td>";
		
		$tipo_campionato = $ottipo_calcolo;
		### Tabellini Giornata corrente
	$mail_formazione .= "<tr><td><table align='center' width='100%' bgcolor='$sfondo_tab' border='0' cellpadding='10' cellspacing='0'>";
			
	if($tipo_campionato == "S") {
		$partite = "";
				$marcotori = "";
		
	### Caricamento scontri giornata corrente
	$file_scontri = file($percorso_cartella_scontri."/squadre".$otpart);
	$num_linee_file_scontri = count($file_scontri);
		$trovata_giornata_corrente = false;
	$num_linea = 0;
	$num_gio_cal = 0;
	$num_sc = 0;
	while(!$trovata_giornata_corrente && $num_linea < $num_linee_file_scontri) {
	$linea_file_scontri = trim(togli_acapo($file_scontri[$num_linea]));
	if ($linea_file_scontri == "<giornata>") $num_gio_cal++;
	if ($num_gio_cal == $num_giornata) {
	$trovata_giornata_corrente = true;
	$num_partita = $num_linea+2;
	$linea_file_scontri = trim(togli_acapo($file_scontri[$num_partita]));
	while($linea_file_scontri != "</giornata>") {
	$scontri[$num_sc] = $linea_file_scontri;
	$num_sc++;
	$num_partita++;
		$linea_file_scontri = trim(togli_acapo($file_scontri[$num_partita]));
	}
	}
	$num_linea++;
	}
	### Fine Caricamento scontri giornata corrente
	
	$num_scontri = count($scontri);
	for ($num1 = 0 ; $num1 < $num_scontri ; $num1++) {
	$dati_scontri = explode("-", $scontri[$num1]);
	
	$utente0 = $nome_posizione[$dati_scontri[0]];
		$utente1 = $nome_posizione[$dati_scontri[1]];
		$soprannome_squadra = $nome_squadra_memo[$utente0];
		$vedivoti=htmlentities($nome_squadra_memo[$utente0],ENT_QUOTES);
		$mail_formazione .= "<tr><td align='left' valign='top'><a name='$vedivoti'></a>
		<div class='box_utente_header'>$nome_squadra_memo[$utente0]</div>
		<div class='box_utente_content'>";
		$mail_formazione .= $tabellini[$utente0];
		$mail_formazione .= "</div></td>";
					
					$vedivoti=htmlentities($nome_squadra_memo[$utente1],ENT_QUOTES);
		$mail_formazione .= "<td align='left' valign='top'><a name='$vedivoti'></a>
		<div class='box_utente_header'>$nome_squadra_memo[$utente1]</div>
		<div class='box_utente_content'>";
		$mail_formazione .= $tabellini[$utente1];
		$mail_formazione .= "</div></td></tr>";
				} # fine for $num1
	}
		elseif($tipo_campionato != "N") {
		$mail_formazione .= "<tr>";
		$num_squadre = count($tabellini);
		for ($num1 = 1 ; $num1 <= $num_squadre ; $num1++) {
		if ($num_colonne%2 == 0) {
		$mail_formazione .= "</tr><tr>";
		$num_colonne = 0;
		} # fine if ($num_colonne >= 2)
		$utente = $nome_posizione[$num1];
		$soprannome_squadra = $nome_squadra_memo[$utente];
		$vedivoti=htmlentities($nome_squadra_memo[$utente],ENT_QUOTES);
		$mail_formazione .= "<td align='left' valign='top'><a name='$vedivoti'></a>
		<div class='box_utente_header'>$nome_squadra_memo[$utente]</div>
			<div class='box_utente_content'>";
			$mail_formazione .= $tabellini[$utente];
		$mail_formazione .= "</div></td>";
			$num_colonne++;
				} # fine for $num1
		$mail_formazione .= "</tr>";
		}
	
		$mail_formazione .= "</table>";
		### Fine Tabellini Giornata corrente
	
		$oggetto = "Invio formazioni\r\n";
		$mail_css = "<style type=\"text/css\">
    div.box_utente_header{
	background: #194A93;
	color: #FFFFFF;
	font-weight: bold;
	text-align: center;
	padding: 3px 3px 3px 3px;
	border: 1px solid #000000;
	/* Border Radius Style */
    border-top-right-radius: 7px;
    border-top-left-radius: 7px;
    /* Mozilla Firefox Extension*/
    -moz-border-radius-topright: 7px;
    -moz-border-radius-topleft: 7px;
}

div.box_utente_header-noround{
	background: #194A93;
	color: #FFFFFF;
	font-weight: bold;
	text-align: center;
	padding: 3px 3px 3px 3px;
	border: 1px solid #000000;
}

div.box_utente_content{
	background: #FFFFFF;
	border: 1px solid #C6C6C6;
	border-top: 0px;
	margin: 0; 
	padding: 5px;
	/* Border Radius Style */
    border-bottom-right-radius: 7px;
    border-bottom-left-radius: 7px;
    /* Mozilla Firefox Extension*/
    -moz-border-radius-bottomright: 7px;
    -moz-border-radius-bottomleft: 7px;
    overflow: hidden;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
}
</style>";
	
		$mail_formazione = "$mail_css $commento<hr>".trim(stripslashes("$mail_formazione"));

	########################
		$destinatari = "";
        $file = file($percorso_cartella_dati."/utenti_".$_SESSION['torneo'].".php");
		$linee = count($file);
			for($linea = 1; $linea < $linee; $linea++) {
				@list($outente, $opassword, $opermessi, $oemail, $ourl, $osquadra, $ocittà, $ocrediti, $ovariazioni, $ocambi, $oreg) = explode("<del>", $file[$linea]);
				$destinatari = "$outente <$oemail>,";
				$destinatariHtml .= "$outente,";
			
				$destinatari .= "\r\n";

				if ($gestione_email == "mail_OK") {
		  			$intestazioni  = "MIME-Version: 1.0\n";
			  		$intestazioni .= "Content-type: text/html; charset=iso-8859-1\n";	
				   	#$intestazioni .= "X-Priority: 3\n";
				   	#$intestazioni .= "X-MSMail-Priority: Normal\n";
			   		#$intestazioni .= "X-Mailer: FantacalcioBazar mailer\n";
					$intestazioni .= "From: $admin_nome <$email_mittente>\n" ;

					if(@mail($destinatari,$oggetto,"$mail_formazione\n",$intestazioni)){ 
						$azione .= "La mail a ".$outente." è stata inoltrata con successo.<br>";
						sleep(1); 
					}
					else $azione .= "Si sono verificati dei problemi nell'invio della mail a ".$outente.".<br>";
					
				}
			}
		
		if ($gestione_email == "mail_anteprima") $azione = "<center><h3>Invio formazioni</h3><form method='post' action='a_invia_voti.php'>
		<input type='hidden' name='commento' value='$commento' />
		<input type='hidden' name='gestione_email' value='mail_OK' />
		<input type='submit' name='invia' value='Invia messaggio' />
		</form></center><hr><b>DESTINATARI</b><br/>$destinatariHtml<hr><hr><b>MESSAGGIO</b><br/>$mail_formazione";

		elseif ($azione == "") $azione = "Errore, Invio email non riuscito.";
	}
	# fine gestione invio emails
	###############################

	else $azione = "<h3>Invio email con formazioni</h3>
	Inserisci un eventuale commento all'invio delle formazioni<br/>
	<form method='post' action='a_invia_voti.php'>
	<input type='hidden' name='gestione_email' value='mail_anteprima' />
	<textarea name='commento' rows=8 cols=60 wrap='virtual'>Invio delle formazioni selezionate per questa giornata di fantacalcio.</textarea><br/>
	<input type='submit' value='Anteprima invio' /></form>";

echo "$azione";

include("./footer.php");
} # fine if ($pass_admin_errata == "NO")
#########################################################

if ($_SESSION['permessi'] == 5) {
require ("./a_menu.php");

	if($_POST["l_torneo"]!= "")
$storneo=$_POST["l_torneo"];

##################################
# Invio email

	if ($gestione_email == "mail_anteprima" or $gestione_email == "mail_OK") {

	########################
	# Layout pagina

	$mail_formazione = "";
	$color = "ghostwhite";
	$nome_squadra = "tutti";
	############################################

	$tab_formazioni = "";
	$tabellini = array();
	$num_colonne = 0;
	$punti = "";
	$voti = "";
	$scontri = "";
	$num2 = 0;
	$rosa_squadra = array();
	$formazione = array();
	$rosa_pos = 0;
	$num_giornata = 1;
	
	### Calcolo giornata corrente
	for ($num1 = 1; $num1 < 40 ; $num1++) {
	if (strlen($num1) == 1) $num1 = "0".$num1;
	if (@is_file($percorso_cartella_dati."/giornata".$num1."_".$storneo."_0")) $num_giornata = $num1+1;
	else break;
	} # fine for $num1
	### Fine Calcolo giornata corrente
	
	### Caricamento dati torneo
	$tornei = @file($percorso_cartella_dati."/tornei.php");
	$num_tornei = count($tornei);
	
	for($num = 1 ; $num < $num_tornei; $num++) {
	@list($otid, $otdenom, $otpart, $otserie, $otmercato_libero, $ottipo_calcolo, $otgiornate_totali, $otritardo_torneo, $otcrediti_iniziali, $otnumcalciatori, $otcomposizione_squadra, $temp1, $temp2, $temp3, $temp4, $otstato, $otmodificatore_difesa, $otschemi, $otmax_in_panchina, $otpanchina_fissa, $otmax_entrate_dalla_panchina, $otsostituisci_per_ruolo, $otsostituisci_per_schema,  $otsostituisci_fantasisti_come_centrocampisti, $otnumero_cambi_max, $otrip_cambi_numero, $otrip_cambi_giornate, $otrip_cambi_durata, $otaspetta_giorni, $otaspetta_ore, $otaspetta_minuti, $otnum_calciatori_scambiabili, $otscambio_con_soldi, $otvendi_costo, $otpercentuale_vendita, $otsoglia_voti_primo_gol, $otincremento_voti_gol_successivi, $otvoti_bonus_in_casa, $otpunti_partita_vinta, $otpunti_partita_pareggiata, $otpunti_partita_persa, $otdifferenza_punti_a_parita_gol, $otdifferenza_punti_zero_a_zero, $otdifferenza_punti_prima_soglia_meno_sei, $otdifferenza_punti_gol_premio, $otmin_num_titolari_in_formazione, $otpunti_pareggio, $otpunti_pos) = explode(",", $tornei[$num]);
	if($otid == $storneo) break;
	}
	### Fine Caricamento dati torneo
	
	$file = @file($percorso_cartella_dati."/utenti_".$storneo.".php");
	$linee = count($file);
	for ($num1 = 1 ; $num1 < $linee; $num1++) {
	@list($outente, $opass, $opermessi, $oemail, $ourl, $osquadra, $otorneo, $oserie, $ocittà, $ocrediti, $ovariazioni, $ocambi, $oreg) = explode("<del>", $file[$num1]);
	$nome_posizione[$num1] = $outente;
	$soprannome_squadra = $osquadra;
	
	if ($soprannome_squadra) {
	$nome_squadra_memo[$outente] = $soprannome_squadra;
	$soprannome_squadra = "<b>".$soprannome_squadra."</b>";
	} # fine if ($soprannome_squadra)
	else {
	$soprannome_squadra = "Squadra";
	$nome_squadra_memo[$outente] = $outente;
	} # fine else if ($soprannome_squadra)
	
	### Caricamento rosa squadra
	$calciatori = @file($percorso_cartella_dati."/mercato_".$storneo."_0.txt");
	
	$num_calciatori = count($calciatori);
	for ($num2 = 0 ; $num2 < $num_calciatori ; $num2++) {
	$dati_calciatore = explode(",", $calciatori[$num2]);
			$proprietario = $dati_calciatore[4];
	
	if ($proprietario == $outente) {
	$numero = $dati_calciatore[0];
		$ruolo = $dati_calciatore[2];
		$nome = stripslashes($dati_calciatore[1]);
		$rosa_squadra["$numero"] = $ruolo.",".$nome.",".$proprietario;
	} # fine if ($proprietario == $outente)
	} # fine for $num2
	###	Fine Caricamento rosa squadra
	$calciatori = @file($percorso_cartella_dati."/calciatori.txt");
	$num_calciatori = count($calciatori);
		for ($num2 = 0 ; $num2 < $num_calciatori ; $num2++) {
	$dati_calciatore = explode("|", $calciatori[$num2]);
	if(isset($rosa_squadra["$dati_calciatore[0]"]))
	$rosa_squadra["$dati_calciatore[0]"] = $rosa_squadra["$dati_calciatore[0]"].",".substr($dati_calciatore[3],1,strlen($dati_calciatore[3])-2);
	}
	### Aggiunta squadra appartenenza giocatore
	
	### Fine Aggiunta squadra appartenenza giocatore
	
	### Caricamento formazione corrente
	$dati_squadra = @file($percorso_cartella_dati."/squadra_".$outente);
	$formazione = explode(",", $dati_squadra[1].",".$dati_squadra[2]);
	###	Fine Caricamento formazione corrente
			
	$tab_formazioni = "<table width='100%' cellpadding='0' cellspacing='1' bgcolor='$sfondo_tab'>
		<tr bgcolor='#808080'><td align='center'><font size='-2'><u>R</u></font></td><td align='center'><font size='-2'><u>Calciatore</u></font></td>
		<td align='center'>Squadra</td></tr>";
		$num_linee_formazione = count($formazione);
			for ($num2 = 0 ; $num2 < $num_linee_formazione; $num2++) {
	$riga_calciatore = explode(",", $rosa_squadra["$formazione[$num2]"]);
	$nome_calciatore = stripslashes($riga_calciatore[1]);
	if ($num2 % 2) $colore="white"; else $colore=$colore_riga_alt;
	//if (strlen($nome_calciatore) > 20)
	//	$nome_calciatore = substr($nome_calciatore,0,20);
	$tab_formazioni .= "<tr bgcolor='$colore' height='21px'>
	<td align='center' style='padding: 0px 1px 0px 1px'>$riga_calciatore[0]</td>
			<td  style='padding: 0px 1px 0px 1px'><a href='stat_calciatore.php?num_calciatore=$formazione[$num2]&amp;escludi_controllo=$escludi_controllo' >$nome_calciatore</a></td>
	<td style='padding: 0px 1px 0px 1px'>$riga_calciatore[3]</td></tr>";
			} # fine for $num2
		$tab_formazioni .= "</table>";
	$tabellini[$outente] = $tab_formazioni;
	
	} # fine for $num1
	//for ($num1 = $num_colonne ; $num1 < 2; $num1++) $tab_formazioni .= "<td>&nbsp;</td>";
	
	$mail_formazione .= "<table summary='guarda_giornata' bgcolor='$sfondo_tab' align='center' width='100%' cellpadding='10' cellspacing='0'>
	<caption>FORMAZIONI UFFICIALI GIORNATA N.$num_giornata</caption><tr><td>";
	
	$tipo_campionato = $ottipo_calcolo;
	### Tabellini Giornata corrente
	$mail_formazione .= "<tr><td><table align='center' width='100%' bgcolor='$sfondo_tab' border='0' cellpadding='10' cellspacing='0'>";
		
		if($tipo_campionato == "S") {
	$partite = "";
			$marcotori = "";
			
			### Caricamento scontri giornata corrente
	$file_scontri = file($percorso_cartella_scontri."/squadre".$otpart);
	$num_linee_file_scontri = count($file_scontri);
	$trovata_giornata_corrente = false;
	$num_linea = 0;
	$num_gio_cal = 0;
	$num_sc = 0;
	while(!$trovata_giornata_corrente && $num_linea < $num_linee_file_scontri) {
	$linea_file_scontri = trim(togli_acapo($file_scontri[$num_linea]));
	if ($linea_file_scontri == "<giornata>") $num_gio_cal++;
	if ($num_gio_cal == $num_giornata) {
	$trovata_giornata_corrente = true;
	$num_partita = $num_linea+2;
	$linea_file_scontri = trim(togli_acapo($file_scontri[$num_partita]));
	while($linea_file_scontri != "</giornata>") {
	$scontri[$num_sc] = $linea_file_scontri;
	$num_sc++;
	$num_partita++;
	$linea_file_scontri = trim(togli_acapo($file_scontri[$num_partita]));
	}
	}
	$num_linea++;
	}
	### Fine Caricamento scontri giornata corrente
	
	$num_scontri = count($scontri);
	for ($num1 = 0 ; $num1 < $num_scontri ; $num1++) {
	$dati_scontri = explode("-", $scontri[$num1]);
		
	$utente0 = $nome_posizione[$dati_scontri[0]];
	$utente1 = $nome_posizione[$dati_scontri[1]];
	$soprannome_squadra = $nome_squadra_memo[$utente0];
	$vedivoti=htmlentities($nome_squadra_memo[$utente0],ENT_QUOTES);
	$mail_formazione .= "<tr><td align='left' valign='top'><a name='$vedivoti'></a>
	<div class='box_utente_header'>$nome_squadra_memo[$utente0]</div>
	<div class='box_utente_content'>";
	$mail_formazione .= $tabellini[$utente0];
				$mail_formazione .= "</div></td>";
				
				$vedivoti=htmlentities($nome_squadra_memo[$utente1],ENT_QUOTES);
	$mail_formazione .= "<td align='left' valign='top'><a name='$vedivoti'></a>
	<div class='box_utente_header'>$nome_squadra_memo[$utente1]</div>
	<div class='box_utente_content'>";
	$mail_formazione .= $tabellini[$utente1];
				$mail_formazione .= "</div></td></tr>";
			} # fine for $num1
	}
		elseif($tipo_campionato != "N") {
			$mail_formazione .= "<tr>";
	$num_squadre = count($tabellini);
			for ($num1 = 1 ; $num1 <= $num_squadre ; $num1++) {
	if ($num_colonne%2 == 0) {
	$mail_formazione .= "</tr><tr>";
	$num_colonne = 0;
	} # fine if ($num_colonne >= 2)
		$utente = $nome_posizione[$num1];
		$soprannome_squadra = $nome_squadra_memo[$utente];
		$vedivoti=htmlentities($nome_squadra_memo[$utente],ENT_QUOTES);
				$mail_formazione .= "<td align='left' valign='top'><a name='$vedivoti'></a>
		<div class='box_utente_header'>$nome_squadra_memo[$utente]</div>
		<div class='box_utente_content'>";
		$mail_formazione .= $tabellini[$utente];
		$mail_formazione .= "</div></td>";
		$num_colonne++;
			} # fine for $num1
			$mail_formazione .= "</tr>";
		}
	
		$mail_formazione .= "</table>";
	### Fine Tabellini Giornata corrente

	$oggetto = "Invio formazioni\r\n";
			$mail_css = "<style type=\"text/css\">
    div.box_utente_header{
	background: #194A93;
	color: #FFFFFF;
	font-weight: bold;
	text-align: center;
	padding: 3px 3px 3px 3px;
	border: 1px solid #000000;
	/* Border Radius Style */
    border-top-right-radius: 7px;
    border-top-left-radius: 7px;
    /* Mozilla Firefox Extension*/
    -moz-border-radius-topright: 7px;
    -moz-border-radius-topleft: 7px;
}

div.box_utente_header-noround{
	background: #194A93;
	color: #FFFFFF;
	font-weight: bold;
	text-align: center;
	padding: 3px 3px 3px 3px;
	border: 1px solid #000000;
}

div.box_utente_content{
	background: #FFFFFF;
	border: 1px solid #C6C6C6;
	border-top: 0px;
	margin: 0; 
	padding: 5px;
	/* Border Radius Style */
    border-bottom-right-radius: 7px;
    border-bottom-left-radius: 7px;
    /* Mozilla Firefox Extension*/
    -moz-border-radius-bottomright: 7px;
    -moz-border-radius-bottomleft: 7px;
    overflow: hidden;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
}
</style>";

	$mail_formazione = "$mail_css $commento<hr>".trim(stripslashes("$mail_formazione"));

	########################
		$destinatari = "";
		$file = @file("./dati/utenti_".$storneo.".php");
		$linee = count($file);
			for($linea = 1; $linea < $linee; $linea++) {
				@list($outente, $opassword, $opermessi, $oemail, $ourl, $osquadra, $ocittà, $ocrediti, $ovariazioni, $ocambi, $oreg) = explode("<del>", $file[$linea]);
				$destinatari = "$outente <$oemail>,";
				$destinatariHtml .= "$outente, ";
			
				$destinatari .= "\r\n";

				if ($gestione_email == "mail_OK") {

				  	$intestazioni  = "MIME-Version: 1.0\n";
				  	$intestazioni .= "Content-type: text/html; charset=iso-8859-1\n";
				   	#$intestazioni .= "X-Priority: 3\n";
				   	#$intestazioni .= "X-MSMail-Priority: Normal\n";
			   		#$intestazioni .= "X-Mailer: FantacalcioBazar mailer\n";
					$intestazioni .= "From: $admin_nome <$email_mittente>\n" ;	

					if(@mail($destinatari,$oggetto,"$mail_formazione\n",$intestazioni)){ 
						$azione .= "La mail a ".$outente." stata inoltrata con successo.<br>";
						sleep(1); 
					}
					else $azione .= "Si sono verificati dei problemi nell'invio della mail a ".$outente.".<br>";
				}
			}
			
		if ($gestione_email == "mail_anteprima") $azione = "<center><h3>Invio formazioni</h3><form method='post' action='a_invia_voti.php'>
		<input type='hidden' name='commento' value='$commento' />
		<input type=\"hidden\" name=\"storneo\" value=\"$storneo\">
		<input type='hidden' name='gestione_email' value='mail_OK' />
		<input type='submit' name='invia' value='Invia messaggio' />
		</form></center><hr><b>DESTINATARI</b><br/>$destinatariHtml<hr><hr><b>MESSAGGIO</b><br/>$mail_formazione";

		elseif ($azione == "") $azione = "Errore, Invio email non riuscito.";
	}
	# fine gestione invio emails
	###############################

		else $azione = "<h3>Invio email con formazioni</h3>
      $frase<br>
    <form method=\"post\" action=\"a_invia_voti.php\">
    <input type=\"hidden\" name=\"gestione_email\" value=\"mail_anteprima\">
    <input type=\"hidden\" name=\"storneo\" value=\"mail_anteprima\">
    $vedi_tornei_attivi	<br/><br/>
    Inserisci un eventuale commento all'invio delle formazioni<br>
	<textarea name=\"commento\" rows=8 cols=60 wrap=\"virtual\">Invio formazioni prossima giornata.</textarea><br>
    <input type=\"submit\" value=\"Anteprima invio\"></form>";

echo "$azione";
echo "<script>
function cambiaCommento(torneo){
	textarea = document.getElementsByTagName(\"textarea\").item(0);
	textarea.value = torneo.innerHTML+\" - Invio formazioni ufficiali della \"+torneo.id+\"° giornata di fantacampionato.\";
}
</script>";

include("./footer.php");
} # fine if ($pass_admin_errata == "NO")
?>