<?php
##################################################################################
#    FANTACALCIOBAZAR Evolution
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

	$formazione = array();
	$voti = array();
	$punti = array();
	$gol = array();
	$giornata_ultima = 0;

	$tornei = @file($percorso_cartella_dati."/tornei.php");
	$num_tornei = count($tornei);

	echo "<table cellpadding='2'><tr><td align='center'>";
	
	for($num = 1 ; $num < $num_tornei; $num++) {
		@list($otid, $otdenom, $otpart, $otserie, $otmercato_libero, $ottipo_calcolo, $otgiornate_totali, $otritardo_torneo, $otcrediti_iniziali, $otnumcalciatori, $otcomposizione_squadra, $temp1, $temp2, $temp3, $temp4, $otstato, $otmodificatore_difesa, $otschemi, $otmax_in_panchina, $otpanchina_fissa, $otmax_entrate_dalla_panchina, $otsostituisci_per_ruolo, $otsostituisci_per_schema,  $otsostituisci_fantasisti_come_centrocampisti, $otnumero_cambi_max, $otrip_cambi_numero, $otrip_cambi_giornate, $otrip_cambi_durata, $otaspetta_giorni, $otaspetta_ore, $otaspetta_minuti, $otnum_calciatori_scambiabili, $otscambio_con_soldi, $otvendi_costo, $otpercentuale_vendita, $otsoglia_voti_primo_gol, $otincremento_voti_gol_successivi, $otvoti_bonus_in_casa, $otpunti_partita_vinta, $otpunti_partita_pareggiata, $otpunti_partita_persa, $otdifferenza_punti_a_parita_gol, $otdifferenza_punti_zero_a_zero, $otdifferenza_punti_prima_soglia_meno_sei, $otdifferenza_punti_gol_premio, $otmin_num_titolari_in_formazione, $otpunti_pareggio, $otpunti_pos) = explode(",", $tornei[$num]);

	$tipo_campionato = $ottipo_calcolo;

	############################################
	# intestazione tabella classifica
	############################################
	for ($num1 = 1 ; $num1 < $otgiornate_totali+1 ; $num1++) {
	if (strlen($num1) == 1) $num1 = "0".$num1;
	$giornata_controlla = "giornata$num1";
	if (!@is_file($percorso_cartella_dati."/".$giornata_controlla."_".$otid."_".$otserie)) break;
	else $giornata_ultima = $num1;
	} # fine for $num1

	$num_giornata = $giornata_ultima;

	$file = file($percorso_cartella_dati."/utenti_".$otid.".php");
	$linee = count($file);
	for($num1 = 1 ; $num1 < $linee; $num1++) {
	@list($outente, $opass, $opermessi, $oemail, $ourl, $osquadra, $otorneo, $oserie, $ocittà, $ocrediti, $ovariazioni, $ocambi, $oreg) = explode("<del>", $file[$num1]);
	$nome_squadra_memo[$outente] = $osquadra;
	}
	
	if ($num_giornata > 0) {

	### Box Classifica di giornata
		$file_classifica = file($percorso_cartella_dati."/classifica_".$otid);
		$num_linee_file_classifica = count($file_classifica);
		$trovato_prossimo_turno = false;
	$num_linea = 0;
	$trovato_classifica = false;
	$num_linea = 0;
	while(!$trovato_punteggi && $num_linea < $num_linee_file_classifica) {
	$linea_file_classifica = trim(togli_acapo($file_classifica[$num_linea]));
	if ($linea_file_classifica == "#@& classifica #@&") {
				echo "<div class='box_utente_header' style='margin-top: 10px'>".strtoupper($otdenom)." - CLASSIFICA DOPO LA GIORNATA N.$num_giornata</div>
	<div class='box_utente_content'>";
				echo "<table width='100%' cellspacing='1' cellpadding='0' border='0' bgcolor='$sfondo_tab'>";
	$trovato_classifica = true;
	$num_squadra = $num_linea+1;
	$linea_file_classifica = trim(togli_acapo($file_classifica[$num_squadra]));
	$curr_sq = 0;
	while($linea_file_classifica != "#@& fine classifica #@&") {
					$dati_squadra = explode("##@@&&", $linea_file_classifica);
	if($num_squadra == $num_linea+1){
	if($tipo_campionato == "S"){
	echo "<tr bgcolor='#808080' style='height:25px'><td align='center'>".$dati_squadra[0]."</td><td align='center'>"
	.$dati_squadra[1]."</td><td align='center'>".$dati_squadra[2]."</td><td align='center'>"
	.$dati_squadra[3]."</td><td align='center'>".$dati_squadra[4]."</td><td align='center'>"
							.$dati_squadra[5]."</td><td align='center'>".$dati_squadra[18]."</td><td align='center'>"
							.$dati_squadra[19]."</td><td align='center'>".$dati_squadra[20]."</td><td align='center'>"
							.$dati_squadra[21]."</td>
							</tr>";
						}
						elseif($tipo_campionato == "P") {
							echo "<tr bgcolor='#808080' style='height:25px'><td align='center'>".$dati_squadra[0]."</td><td align='center'>"
							.$dati_squadra[1]."</td>
							</tr>";
						}
						else{
	echo "<tr bgcolor='#808080' style='height:25px'><td align='center'>".$dati_squadra[0]."</td><td align='center'>"
							.$dati_squadra[1]."</td>
							</tr>";
						}
					}
	else {
	$curr_sq++;
	if($tipo_campionato == "S") {
	echo "<tr style='height:25px'";
	if($num_squadra%2 != 0) echo"bgcolor='#E6E6E6'"; else echo"bgcolor='#F3F3F3'";
	if($curr_sq < 4) echo"style='border: 1px solid #000000'";
	echo "><td align='center'>".$nome_squadra_memo[$dati_squadra[0]]."</td><td align='center' bgcolor='#B7B8FC'>".$dati_squadra[1]."</td>
	<td align='center' bgcolor='#FFE475'>".$dati_squadra[2]."</td><td align='center' bgcolor='#FFE475'>".$dati_squadra[3]."</td>
	<td align='center' bgcolor='#FFE475'>".$dati_squadra[4]."</td><td align='center' bgcolor='#FFE475'>".$dati_squadra[5]."</td>
							<td align='center' bgcolor='#FFE475'>".$dati_squadra[18]."</td><td align='center' bgcolor='#FFE475'>".$dati_squadra[19]."</td>
							<td align='center' bgcolor='#FFE475'>".$dati_squadra[20]."</td><td align='center' bgcolor='#B7B8FC'>".$dati_squadra[21]."</td>
							</tr>";
	}
	elseif($tipo_campionato == "P") {
							echo "<tr style='height:25px'";
							if($num_squadra%2 != 0) echo"bgcolor='#E6E6E6'"; else echo"bgcolor='#F3F3F3'";
							echo "><td align='center'>".$nome_squadra_memo[$dati_squadra[0]]."</td><td align='center' bgcolor='#B7B8FC'>"
							.$dati_squadra[1]."</td>
	</tr>";
	}
	else {
	echo "<tr style='height:25px'";
							if($num_squadra%2 != 0) echo"bgcolor='#E6E6E6'"; else echo"bgcolor='#F3F3F3'";
	echo "><td align='center'>".$nome_squadra_memo[$dati_squadra[0]]."</td><td align='center' bgcolor='#B7B8FC'>"
							.$dati_squadra[1]."</td>
							</tr>";
	}
	}
	$num_squadra++;
	$linea_file_classifica = trim(togli_acapo($file_classifica[$num_squadra]));
	}
	}
	$num_linea++;
		}
		### Fine Box Classifica di giornata

	echo "</table></div>";

	#echo"<br /><br />
	#<h2>Miglior punteggio fatto fino ad ora: <u>$magg&nbsp;</u>
	#fatto da <u>$gioc&nbsp;</u> alla giornata <u>$gior&nbsp;</u></h2>\n";

}
	else {
	echo "<div class='box_utente_header' style='margin-top: 10px'>CLASSIFICA INIZIALE</div>
	<div class='box_utente_content'>";
	echo "<table width='100%' cellspacing='1' cellpadding='0' border='0' bgcolor='$sfondo_tab'>";
		if($tipo_campionato == "S"){
			echo "<tr bgcolor='#808080' style='height:25px'><td align='center'>SQUADRA</td><td align='center'>PUNTI</td>
			<td align='center'>G</td><td align='center'>V</td><td align='center'>N</td>
			<td align='center'>P</td><td align='center'>RF</td><td align='center'>RS</td>
			<td align='center'>DIFF</td><td align='center'>TPF</td>
			</tr>";
		}
		elseif($tipo_campionato == "P") {
			echo "<tr bgcolor='#808080' style='height:25px'><td align='center'>SQUADRA</td><td align='center'>PUNTI</td>
			</tr>";
	}
	else{
	echo "<tr bgcolor='#808080' style='height:25px'><td align='center'>SQUADRA</td><td align='center'>PUNTI</td>
			</tr>";
		}
		
		for($num1 = 1 ; $num1 < $linee; $num1++) {
	@list($outente, $opass, $opermessi, $oemail, $ourl, $osquadra, $otorneo, $oserie, $ocittà, $ocrediti, $ovariazioni, $ocambi, $oreg) = explode("<del>", $file[$num1]);
	if ($opermessi >= 0) {
	if($tipo_campionato == "S") {
	echo "<tr style='height:25px'";
	if($num1%2 != 0) echo"bgcolor='#E6E6E6'"; else echo"bgcolor='#F3F3F3'";
	echo "><td align='center'>".$osquadra."</td><td align='center' bgcolor='#B7B8FC'>0</td>
					<td align='center' bgcolor='#FFE475'>0</td><td align='center' bgcolor='#FFE475'>0</td>
					<td align='center' bgcolor='#FFE475'>0</td><td align='center' bgcolor='#FFE475'>0</td>
					<td align='center' bgcolor='#FFE475'>0</td><td align='center' bgcolor='#FFE475'>0</td>
					<td align='center' bgcolor='#FFE475'>0</td><td align='center' bgcolor='#B7B8FC'>0</td>
					</tr>";
				}
				elseif($tipo_campionato == "P") {
					echo "<tr style='height:25px'";
					if($num_squadra%2 != 0) echo"bgcolor='#E6E6E6'"; else echo"bgcolor='#F3F3F3'";
					echo "><td align='center'>".$osquadra."</td><td align='center' bgcolor='#B7B8FC'>0.0</td>
					</tr>";
	}
	else {
	echo "<tr style='height:25px'";
	if($num_squadra%2 != 0) echo"bgcolor='#E6E6E6'"; else echo"bgcolor='#F3F3F3'";
					echo "><td align='center'>".$osquadra."</td><td align='center' bgcolor='#B7B8FC'>0.0</td>
					</tr>";
				}
	}
	}
	echo "</table></div>";
	}
	echo "<br>";
} #fine ciclo for su tornei

echo "<div class='box-shadow' style='padding:5px;margin: 20px 0 2px 0'>
			<div style='width: 100%; text-align: center; background: #EEEEEE;margin-bottom: 3px'><b>LEGENDA</b></div>
			<table width='100%'>
	<tr>
			<td>G: partite giocate</td>
			<td>V: partite vinte</td>
			<td>N: partite pareggiate</td>
			<td>P: partite perse</td>
			</tr><tr>
			<td>RF: reti fatte</td>
			<td>RS: reti subite</td>
			<td>DIFF: differenza reti</td>
			<td>TPF: totale punti fatti</td>
			</tr>
		</div>";
echo "</td></tr></table>";
echo "</table>";

#include("./footer.php");
?>