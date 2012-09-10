<?php
##################################################################################
#    FANTACALCIOBAZAR EVOLUTION
#    Copyright (C) 2003-2006 by Antonello Onida (fantacalciobazar@sassarionline.net)
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

if ($_SESSION['valido'] == "SI" AND ($stato_mercato != "I" OR $stato_mercato != "R" OR $_SESSION['permessi'] == 4)) {
require("./menu.php");

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
	if (@is_file($percorso_cartella_dati."/giornata".$num1."_".$_SESSION['torneo']."_".$_SESSION['serie'])) $num_giornata = $num1+1;
	else break;
} # fine for $num1
### Fine Calcolo giornata corrente

### Caricamento dati torneo
$tornei = @file($percorso_cartella_dati."/tornei.php");
$num_tornei = count($tornei);

for($num = 1 ; $num < $num_tornei; $num++) {
	@list($otid, $otdenom, $otpart, $otserie, $otmercato_libero, $ottipo_calcolo, $otgiornate_totali, $otritardo_torneo, $otcrediti_iniziali, $otnumcalciatori, $otcomposizione_squadra, $temp1, $temp2, $temp3, $temp4, $otstato, $otmodificatore_difesa, $otschemi, $otmax_in_panchina, $otpanchina_fissa, $otmax_entrate_dalla_panchina, $otsostituisci_per_ruolo, $otsostituisci_per_schema,  $otsostituisci_fantasisti_come_centrocampisti, $otnumero_cambi_max, $otrip_cambi_numero, $otrip_cambi_giornate, $otrip_cambi_durata, $otaspetta_giorni, $otaspetta_ore, $otaspetta_minuti, $otnum_calciatori_scambiabili, $otscambio_con_soldi, $otvendi_costo, $otpercentuale_vendita, $otsoglia_voti_primo_gol, $otincremento_voti_gol_successivi, $otvoti_bonus_in_casa, $otpunti_partita_vinta, $otpunti_partita_pareggiata, $otpunti_partita_persa, $otdifferenza_punti_a_parita_gol, $otdifferenza_punti_zero_a_zero, $otdifferenza_punti_prima_soglia_meno_sei, $otdifferenza_punti_gol_premio, $otmin_num_titolari_in_formazione, $otpunti_pareggio, $otpunti_pos) = explode(",", $tornei[$num]);
	if($otid == $_SESSION['torneo']) break;
}
### Fine Caricamento dati torneo

$file = @file($percorso_cartella_dati."/utenti_".$_SESSION['torneo'].".php");
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
	$calciatori = @file($percorso_cartella_dati."/mercato_".$_SESSION['torneo']."_".$_SESSION['serie'].".txt");
	
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

echo "<table summary='guarda_giornata' bgcolor='$sfondo_tab' align='center' width='100%' cellpadding='10' cellspacing='0'>
<caption>FORMAZIONI UFFICIALI GIORNATA N.$num_giornata</caption><tr><td>";

$tipo_campionato = $ottipo_calcolo;
### Tabellini Giornata corrente
echo "<tr><td><table align='center' width='100%' bgcolor='$sfondo_tab' border='0' cellpadding='10' cellspacing='0'>";
	
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
			echo "<tr><td align='left' valign='top'><a name='$vedivoti'></a>
				<div class='box_utente_header'>$nome_squadra_memo[$utente0]</div>
				<div class='box_utente_content'>";
			echo $tabellini[$utente0];
			echo "</div></td>";
			
			$vedivoti=htmlentities($nome_squadra_memo[$utente1],ENT_QUOTES);
			echo "<td align='left' valign='top'><a name='$vedivoti'></a>
				<div class='box_utente_header'>$nome_squadra_memo[$utente1]</div>
				<div class='box_utente_content'>";
			echo $tabellini[$utente1];
			echo "</div></td></tr>";
		} # fine for $num1
	}
	elseif($tipo_campionato != "N") {
		echo "<tr>";
		$num_squadre = count($tabellini);
		for ($num1 = 1 ; $num1 <= $num_squadre ; $num1++) {
			if ($num_colonne%2 == 0) {
				echo "</tr><tr>";
				$num_colonne = 0;
			} # fine if ($num_colonne >= 2)
			$utente = $nome_posizione[$num1];
			$soprannome_squadra = $nome_squadra_memo[$utente];
			$vedivoti=htmlentities($nome_squadra_memo[$utente],ENT_QUOTES);
			echo "<td align='left' valign='top'><a name='$vedivoti'></a>
				<div class='box_utente_header'>$nome_squadra_memo[$utente]</div>
				<div class='box_utente_content'>";
			echo $tabellini[$utente];
			echo "</div></td>";
			$num_colonne++;
		} # fine for $num1
		echo "</tr>";
	}
 
echo "</table></td></tr>";
### Fine Tabellini Giornata corrente
} # fine VALID
include("./footer.php");
?>
