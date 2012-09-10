<?php
##################################################################################
#    FANTACALCIOBAZAR
#    Copyright (C) 2003 by Antonello Onida (fantacalcio@sassarionline.net)
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
 require ("./controlla_pass.php");
 require ("./header.php");
 require_once("./inc/funzioni.php");
 
  if($attiva_multi == "SI") {
  	
  		$site_root = "http://".$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].substr($_SERVER["REQUEST_URI"], 0,strrpos($_SERVER["REQUEST_URI"], "/"));
		$frase='INDICA PER QUALE TORNEO VUOI INVIARE I VOTI';
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
				if (@is_file($percorso_cartella_dati."/giornata".$num11."_".$otid."_0")) $num_giornata = $num11;
				else break;
			} # fine for $num1
			### Fine Calcolo giornata corrente
			$vedi_tornei_attivi .= "<option value='$otid' id='".intval($num_giornata)."'>$otdenom</option>";
			} # fine for $num1

		$vedi_tornei_attivi .= "</select>";

		}
		else 
		$vedi_tornei_attivi = "<input type='hidden' name='l_torneo' value='1' />";

if ($_SESSION['valido'] == "SI" AND $_SESSION['permessi'] == 4){
	require ("./menu.php");

	for($num1 = "01" ; $num1 < 40 ; $num1++) {
		if (strlen($num1) == 1) $num1 = "0".$num1;
		$giornata_controlla = "giornata$num1";
		if (!@is_file($percorso_cartella_dati."/".$giornata_controlla."_".$_SESSION['torneo']."_".$_SESSION['serie'])) break;
		else $giornata_ultima = $num1;
	} # fine for $num1
	
if ($gestione_email == "mail_anteprima" or $gestione_email == "mail_OK") {
	
	if (!$giornata or $giornata > $giornata_ultima) $giornata = "$giornata_ultima";
	
	$tab_formazioni = "";
	$tabellini = array();
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
	$linea = trim($file_giornata[$num1]);
	if ($linea == "#@& fine formazioni #@&") $leggendo_formazioni = "NO";
	if ($leggendo_formazioni == "SI") {
	if ($linea == "#@& formazione #@&") $giocatore = "";
	if ($giocatore) {
	${$formazione}[$num2] = $file_giornata[$num1];
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
	
	$tab_formazioni = "<table width='100%' cellpadding='0' cellspacing='1' bgcolor='$sfondo_tab'>
		<tr bgcolor='#808080'><td align='center'><font size='-2'><u>R</u></font></td><td align='center'><font size='-2'><u>Calciatore</u></font></td>
		<td align='center'><font size='-2'>Voto<br/>giornale</font></td><td align='center'>Bonus</td><td align='center'>Malus</td>
		<td align='center'><font size='-2'>Fanta<br/>voto</font></td></tr>";
		$formazione = "formazione_$outente";
		$formazione = $$formazione;
	$num_linee_formazione = count($formazione);
	for ($num2 = 0 ; $num2 < $num_linee_formazione; $num2++) {
	$riga_calciatore = explode(",", $formazione[$num2]);
	$nome_calciatore = stripslashes($riga_calciatore[1]);
	if ($num2 % 2) $colore="white"; else $colore=$colore_riga_alt;
	if(substr($nome_calciatore,0,3) == "<b>") {
	$nome_tmp = substr($nome_calciatore,3,strlen($nome_calciatore)-7);
	if (strlen($nome_tmp) > 20)
	$nome_calciatore = "<b>".substr($nome_tmp,0,20)."</b>";
			}
			else{
	if (strlen($nome_calciatore) > 20)
	$nome_calciatore = substr($nome_calciatore,0,20);
	}
	if (isset($riga_calciatore[4]) && $riga_calciatore[4] == 0) $riga_calciatore[4] = 's.v.';
	if ( !isset($riga_calciatore[3]) || (isset($riga_calciatore[3]) && $riga_calciatore[3] == 0)) $nome_calciatore = "<del><em><span style='color: grey'>$nome_calciatore</span></em></del>";
			$tab_formazioni .= "<tr bgcolor='$colore' height='21px'><td align='center' style='padding: 0px 1px 0px 1px'>$riga_calciatore[2]</td><td  style='padding: 0px 1px 0px 1px'><a href='stat_calciatore.php?num_calciatore=$riga_calciatore[0]&amp;escludi_controllo=$escludi_controllo' >$nome_calciatore</a></td>
			<td align='center'>$riga_calciatore[4]</td><td align='center' width='15%'>";
	if($riga_calciatore[5] > 0) $tab_formazioni .= "<div class='golsegnato'><div class='textalign-bottom-right'>$riga_calciatore[5]</div></div>";
			if($riga_calciatore[6] > 0) $tab_formazioni .= "<div class='assist'><div class='textalign-bottom-right'>$riga_calciatore[6]</div></div>";
			if($riga_calciatore[7] > 0) $tab_formazioni .= "<div class='rigoreparato'><div class='textalign-bottom-right'>$riga_calciatore[7]</div></div>";
			$tab_formazioni .= "<div style='clear:both'></div></td><td align='center' width='15%'>";
			if($riga_calciatore[8] == 1) $tab_formazioni .= "<div class='ammonizione'></div>";
	if($riga_calciatore[9] == 1) $tab_formazioni .= "<div class='espulsione'></div>";
	if($riga_calciatore[10] > 0) $tab_formazioni .= "<div class='rigoresbagliato'><div class='textalign-bottom-right'>$riga_calciatore[10]</div></div>";
			if($riga_calciatore[11] > 0) $tab_formazioni .= "<div class='autogol'><div class='textalign-bottom-right'>$riga_calciatore[11]</div></div>";
			if($riga_calciatore[12] > 0) $tab_formazioni .= "<div class='golsubito'><div class='textalign-bottom-right'>$riga_calciatore[12]</div></div>";
			$tab_formazioni .= "<div style='clear:both'></div></td><td align='center'>$riga_calciatore[3]</td></tr>";
			} # fine for $num2
		$tab_formazioni .= "</table>";
	$tabellini[$outente] = $tab_formazioni;
	
	} # fine for $num1
	//for ($num1 = $num_colonne ; $num1 < 2; $num1++) $tab_formazioni .= "<td>&nbsp;</td>";
	
	
	$tipo_campionato = "";
	$num_giornata = str_replace("giornata","",$giornata);
	if (substr($num_giornata,0,1) == 0) $num_giornata = substr($num_giornata,1);
	$num_campionati = count($campionato);
	reset($campionato);
	for($num1 = 0 ; $num1 < $num_campionati; $num1++) {
	$key_campionato = key($campionato);
	$giornate_campionato = explode("-",$key_campionato);
	if ($num_giornata <= $giornate_campionato[1] and $num_giornata >= $giornate_campionato[0]) {
	$num_giornata_campionato = $num_giornata - $giornate_campionato[0] + 1;
	$tipo_campionato = $campionato[$key_campionato];
	$g_inizio_campionato = $giornate_campionato[0];
	break;
	} # fine if ($num_giornata <= $giornate_campionato[1] and...
	next($campionato);
	} # fine for $num1
	if (!$tipo_campionato) $tipo_campionato = "N";
	
	if ($voti_esistenti == "SI") {
	$num_voti = count($voti);
	for ($num1 = 0 ; $num1 < $num_voti ; $num1++) {
	$dati_voti = explode("##@@&&", $voti[$num1]);
	settype($dati_voti[1],"double");
	$voto[$dati_voti[0]] = $dati_voti[1];
	} # fine for $num1
	}
	
	$mexemail .= "<table summary='guarda_giornata' bgcolor='$sfondo_tab' align='center' width='100%' cellpadding='10' cellspacing='0'>
	<caption>TABELLINI GIORNATA N.$num_giornata_campionato</caption><tr><td>";
	
	### Box Risultati Giornata corrente
	$mexemail .= "<br><div class='box_utente_header'>RISULTATI GIORNATA N.$num_giornata_campionato</div>
	<div class='box_utente_content'>";
	$mexemail .= "<table width='100%' cellspacing='1' cellpadding='0' border='0' bgcolor='$sfondo_tab'>";
	if($tipo_campionato == "S") {
		$partite = "";
		$marcotori = "";
		$num_scontri = count($scontri);
	for ($num1 = 0 ; $num1 < $num_scontri ; $num1++) {
	$dati_scontri = explode("##@@&&", $scontri[$num1]);
	$mexemail .= "<tr ";
	if($num1%2 != 0)$mexemail .="bgcolor='#E6E6E6'";else $mexemail .="bgcolor='#F3F3F3'";
	$mexemail .= "><td align='center'>".$nome_squadra_memo[$dati_scontri[0]]."</td><td align='center'> - </td><td align='center'>".$nome_squadra_memo[$dati_scontri[1]]."</td>
			<td align='center'>".($voto[$dati_scontri[0]]+$otvoti_bonus_in_casa)." - ".$voto[$dati_scontri[1]]."</td>
	<td align='center'>".$dati_scontri[2]." - ".$dati_scontri[3]."</td></tr>";
		} # fine for $num1
	}
	elseif($tipo_campionato != "N") {
	$num_punteggi = count($punteggi);
	for ($num1 = 0 ; $num1 < $num_punteggi ; $num1++) {
	$dati_punteggi = explode("##@@&&", $punteggi[$num1]);
	$mexemail .= "<tr ";
	if($num1%2 != 0)$mexemail .="bgcolor='#E6E6E6'";else $mexemail .="bgcolor='#F3F3F3'";
	$mexemail .= "><td align='center'>".$nome_squadra_memo[$dati_punteggi[0]]."</td><td align='center'>".$dati_punteggi[1]."</td></tr>";
	} # fine for $num1
	}
	$mexemail .= "</table></div></td></tr>";
	### Fine Box Risultati Giornata corrente
	
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
			$mexemail .= "<tr><td><div class='box_utente_header' style='margin-top: 10px'>CLASSIFICA DOPO LA GIORNATA N.$num_giornata_campionato</div>
							<div class='box_utente_content'>";
			$mexemail .= "<table width='100%' cellspacing='1' cellpadding='0' border='0' bgcolor='$sfondo_tab'>";
			$trovato_classifica = true;
			$num_squadra = $num_linea+1;
			$linea_file_classifica = trim(togli_acapo($file_classifica[$num_squadra]));
			while($linea_file_classifica != "#@& fine classifica #@&" && ($num_squadra < $num_linea+$otpart+2 )) {
			$dati_squadra = explode("##@@&&", $linea_file_classifica);
			if($num_squadra == $num_linea+1){
			if($tipo_campionato == "S"){
			$mexemail .= "<tr bgcolor='#808080'><td align='center'>".$dati_squadra[0]."</td><td align='center'>"
			.$dati_squadra[1]."</td><td align='center'>".$dati_squadra[2]."</td><td align='center'>"
			.$dati_squadra[3]."</td><td align='center'>".$dati_squadra[4]."</td><td align='center'>"
										.$dati_squadra[5]."</td><td align='center'>".$dati_squadra[20]."</td><td align='center'>"
										.$dati_squadra[21]."</td>
										</tr>";
									}
			elseif($tipo_campionato == "P") {
										$mexemail .= "<tr bgcolor='#808080'><td align='center'>".$dati_squadra[0]."</td><td align='center'>"
										.$dati_squadra[1]."</td>
										</tr>";
									}
									else{
			$mexemail .= "<tr bgcolor='#808080'><td align='center'>".$dati_squadra[0]."</td><td align='center'>"
										.$dati_squadra[1]."</td>
										</tr>";
									}
								}
			else {
			if($tipo_campionato == "S") {
			$mexemail .= "<tr ";
			if($num_squadra%2 != 0) $mexemail .="bgcolor='#E6E6E6'"; else $mexemail .="bgcolor='#F3F3F3'";
			$mexemail .= "><td align='center'>".$nome_squadra_memo[$dati_squadra[0]]."</td><td align='center'>"
			.$dati_squadra[1]."</td><td align='center'>".$dati_squadra[2]."</td><td align='center'>"
										.$dati_squadra[3]."</td><td align='center'>".$dati_squadra[4]."</td><td align='center'>"
										.$dati_squadra[5]."</td><td align='center'>".$dati_squadra[20]."</td><td align='center'>"
										.$dati_squadra[21]."</td>
										</tr>";
			}
			elseif($tipo_campionato == "P") {
										$mexemail .= "<tr ";
			if($num_squadra%2 != 0) $mexemail .="bgcolor='#E6E6E6'"; else $mexemail .="bgcolor='#F3F3F3'";
										$mexemail .= "><td align='center'>".$nome_squadra_memo[$dati_squadra[0]]."</td><td align='center'>"
										.$dati_squadra[1]."</td>
										</tr>";
									}
			else {
			$mexemail .= "<tr ";
			if($num_squadra%2 != 0) $mexemail .="bgcolor='#E6E6E6'"; else $mexemail .="bgcolor='#F3F3F3'";
										$mexemail .= "><td align='center'>".$nome_squadra_memo[$dati_squadra[0]]."</td><td align='center'>"
										.$dati_squadra[1]."</td>
										</tr>";
									}
								}
								$num_squadra++;
										$linea_file_classifica = trim(togli_acapo($file_classifica[$num_squadra]));
							}
							$mexemail .= "</table></div></td></tr>";
			}
			$num_linea++;
			}
			### Fine Box Classifica di giornata
	
	### Tabellini Giornata corrente
	$mexemail .= "<tr><td><table align='center' width='100%' bgcolor='$sfondo_tab' border='0' cellpadding='10' cellspacing='0'>";
	
		if($tipo_campionato == "S") {
	$partite = "";
			$marcotori = "";
			$num_scontri = count($scontri);
	for ($num1 = 0 ; $num1 < $num_scontri ; $num1++) {
				$dati_scontri = explode("##@@&&", $scontri[$num1]);
		
	$utente0 = $dati_scontri[0];
	$utente1 = $dati_scontri[1];
	$soprannome_squadra = $nome_squadra_memo[$utente0];
	$vedivoti=htmlentities($nome_squadra_memo[$utente0],ENT_QUOTES);
	$mexemail .= "<tr><td align='left' valign='top'><a name='$vedivoti'></a>
	<div class='box_utente_header'>$nome_squadra_memo[$utente0]</div>
					<div class='box_utente_content'>";
				$mexemail .= $tabellini[$utente0];
				$mexemail .= "<div style='float:left;padding-top:7px'><a href='#top'><img src='immagini/button_top.png'/></a></div>
	<div class='box-shadow-noround' style='float:right;font-size:25px; font-weight: bolder'><div class='button_circle' style='float:left;margin-right:5px'>+$otvoti_bonus_in_casa</div>".($voto[$utente0]+$otvoti_bonus_in_casa)."</div>
					</div></td>";
				
				$vedivoti=htmlentities($nome_squadra_memo[$utente1],ENT_QUOTES);
	$mexemail .= "<td align='left' valign='top'><a name='$vedivoti'></a>
	<div class='box_utente_header'>$nome_squadra_memo[$utente1]</div>
					<div class='box_utente_content'>";
	$mexemail .= $tabellini[$utente1];
				$mexemail .= "<div style='float:left;padding-top:7px'><a href='#top'><img src='immagini/button_top.png'/></a></div>
	<div class='box-shadow-noround' style='float:right;font-size:25px; font-weight: bolder'>$voto[$utente1]</div>
					</div></td></tr>";
			} # fine for $num1
		}
		elseif($tipo_campionato != "N") {
			$mexemail .= "<tr>";
			$num_squadre = count($tabellini);
			for ($num1 = 0 ; $num1 < $num_squadre ; $num1++) {
	if ($num_colonne%2 == 0) {
		$mexemail .= "</tr><tr>";
		$num_colonne = 0;
	} # fine if ($num_colonne >= 2)
		$utente = $nome_posizione[$num1+1];
			$soprannome_squadra = $nome_squadra_memo[$utente];
			$vedivoti=htmlentities($nome_squadra_memo[$utente],ENT_QUOTES);
			$mexemail .= "<td align='left' valign='top'><a name='$vedivoti'></a>
			<div class='box_utente_header'>$nome_squadra_memo[$utente]</div>
			<div class='box_utente_content'>";
				$mexemail .= $tabellini[$utente];
			$mexemail .= "<div style='float:left;padding-top:7px'><a href='#top'><img src='immagini/button_top.png'/></a></div>
					<div class='box-shadow-noround' style='float:right'>$voto[$utente]</div>
					</div></td>";
				$num_colonne++;
			} # fine for $num1
			$mexemail .= "</tr>";
		}
	 
	$mexemail .= "</table></td></tr>";

##################################
# Invio email


    $oggetto = "Invio risultati\r\n";
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

div.golsegnato{
	position: relative;
	float:left;
	background:url(".$site_root."/immagini/golsegnato.png) no-repeat;
	width:24px;
	height:21px;
	color: red;
	font-size: 11px;
	font-weight: bolder;
}

div.assist{
	position: relative;
	float:left;
	background:url(".$site_root."/immagini/assist.png) no-repeat;
	width:27px;
	height:21px;
	color: red;
	font-size: 11px;
	font-weight: bolder;
}

div.rigoreparato{
	position: relative;
	float:left;
	background:url(".$site_root."/immagini/rigoreparato.png) no-repeat;
	width:26px;
	height:21px;
	color: red;
	font-size: 11px;
	font-weight: bold;
}

div.ammonizione{
	position: relative;
	float:left;
	background:url(".$site_root."/immagini/ammonizione.png) no-repeat;
	width:20px;
	height:21px;
	color: red;
	font-size: 11px;
	font-weight: bold;
}

div.espulsione{
	position: relative;
	float:left;
	background:url(".$site_root."/immagini/espulsione.png) no-repeat;
	width:20px;
	height:21px;
	color: red;
	font-size: 11px;
	font-weight: bold;
}

div.rigoresbagliato{
	position: relative;
	float:left;
	background:url(".$site_root."/immagini/rigoresbagliato.png) no-repeat;
	width:26px;
	height:21px;
	color: red;
	font-size: 11px;
	font-weight: bold;
}

div.autogol{
	position: relative;
	float:left;
	background:url(".$site_root."/immagini/autogol.png) no-repeat;
	width:24px;
	height:21px;
	color: red;
	font-size: 11px;
	font-weight: bold;
}
div.golsubito{
	position: relative;
	float:left;
	background:url(".$site_root."/immagini/golsubito.png) no-repeat;
	width:24px;
	height:21px;
	color: red;
	font-size: 11px;
	font-weight: bold;
}

div.textalign-bottom-right{
	position: absolute;
	bottom: 0;
	margin: 0;
	right: 0;
	padding: 0;
	padding-right: 2px;
}

div.button_circle {
	position: relative;
	background:url(".$site_root."/immagini/button_circle.png) no-repeat;
	width:30px;
	height:30px;
	color: red;
	font-size: 16px;
	font-weight: bolder;
	text-align: center;
	line-height: 30px;
	vertical-align: middle;;
}
</style>";

    $mexemail = "$mail_css $commento<hr>".trim(stripslashes("$mexemail"));

    ########################
        $destinatari = "";
        $file = file("./dati/utenti_".$storneo.".php");
        $linee = count($file);
        for($linea = 1; $linea < $linee; $linea++) {       
        	@list($outente, $opassword, $opermessi, $oemail, $ourl, $osquadra, $ocitta, $ocrediti, $ovariazioni, $ocambi, $oreg) = explode("<del>", $file[$linea]);      
        	$destinatari = "$outente <$oemail>,";      
        	$destinatari .= "\r\n";
        	$destinatariHtml .= "$outente,";
       
       	 	if ($gestione_email == "mail_OK") {
				$intestazioni  = "MIME-Version: 1.0\r\n";
				$intestazioni .= "Content-type: text/html; charset=iso-8859-1\r\n";
				$intestazioni .= "From: $admin_nome <$email_mittente>\r\n" ;
				$intestazioni .= "X-Mailer: PHP v".phpversion()."\r\n";
				$intestazioni .= "bcc: ".$destinatari;
                if(@mail("Utenti $titolo_sito <$email_mittente>",$oggetto,"$mexemail\r\n",$intestazioni)) {
                    $azione = "La mail a ".$outente." è stata inoltrata con successo.<br>";
                    sleep(1);        
                }
                else 
                    $azione = "Si sono verificati dei problemi nell'invio della mail a ".$outente.".<br>";      
                }
        }
        
        if ($gestione_email == "mail_anteprima") $azione = "<center><h3>Invio resoconto ultima giornata</h3><form method=\"post\" action=\"a_invia_risultati.php\">
        <input type=\"hidden\" name=\"commento\" value=\"$commento\">
        <input type=\"hidden\" name=\"gestione_email\" value=\"mail_OK\">
        <input type=\"submit\" name=\"invia\" value=\"Invia messaggio\">
        </form></center><hr><b>DESTINATARI</b><br>$destinatariHtml<hr><hr><b>MESSAGGIO</b><br>$mexemail";

        elseif ($azione == "") $azione = "Errore, Invio email non riuscito.";
    }
    # fine gestione invio emails
    ###############################

    else $azione = "<h3>Invio resoconto ultima giornata</h3>
    Inserisci un eventuale commento all'invio dei voti<br>
    <form method=\"post\" action=\"a_invia_risultati.php\">
    <input type=\"hidden\" name=\"gestione_email\" value=\"mail_anteprima\">
    <textarea name=\"commento\" rows=8 cols=60 wrap=\"virtual\"> ".$otdenom." - Invio risultati ufficiali della ".$giornata."° giornata di fantacampionato.</textarea><br>
    <input type=\"submit\" value=\"Anteprima invio\"></form>";

echo "$azione</td></tr></table>";


require ("./footer.php");
}
########################################################
	if ($_SESSION['permessi'] == 5) {

	require ("./a_menu.php");
	
	if($_POST["l_torneo"]!= "")
$storneo=$_POST["l_torneo"];

$pp="_".$storneo."_0";

$tornei = file($percorso_cartella_dati."/tornei.php");
$linee_tornei = count($tornei);

for ($num1 = 1 ; $num1 < $linee_tornei; $num1++) {
	@list($otid, $otdenom, $otpart, $otserie, $otmercato_libero, $ottipo_calcolo, $otgiornate_totali, $otritardo_torneo, $otcrediti_iniziali, $otnumcalciatori, $otcomposizione_squadra, $otmessaggi, $temp2, $temp3, $temp4, $otstato, $otmodificatore_difesa, $otschemi, $otmax_in_panchina, $otpanchina_fissa, $otmax_entrate_dalla_panchina, $otsostituisci_per_ruolo, $otsostituisci_per_schema,  $otsostituisci_fantasisti_come_centrocampisti, $otnumero_cambi_max, $otrip_cambi_numero, $otrip_cambi_giornate, $otrip_cambi_durata, $otaspetta_giorni, $otaspetta_ore, $otaspetta_minuti, $otnum_calciatori_scambiabili, $otscambio_con_soldi, $otvendi_costo, $otpercentuale_vendita, $otsoglia_voti_primo_gol, $otincremento_voti_gol_successivi, $otvoti_bonus_in_casa, $otpunti_partita_vinta, $otpunti_partita_pareggiata, $otpunti_partita_persa, $otdifferenza_punti_a_parita_gol, $otdifferenza_punti_zero_a_zero, $otdifferenza_punti_prima_soglia_meno_sei, $otdifferenza_punti_gol_premio, $otmin_num_titolari_in_formazione, $otpunti_pareggio, $otpunti_pos, $otreset_scadenza) = explode(",", $tornei[$num1]);
	if ($storneo == $otid) break;
} # fine for $num$tornei = @file("$percorso_cartella_dati/tornei.php");
$range_campionato = "1-$otgiornate_totali";
$campionato[$range_campionato] = $ottipo_calcolo;
	
if ($gestione_email == "mail_anteprima" or $gestione_email == "mail_OK") {
	
	for($num1 = "01" ; $num1 < 40 ; $num1++) {
		if (strlen($num1) == 1) $num1 = "0".$num1;
		$giornata_controlla = "giornata$num1";
		if (!@is_file($percorso_cartella_dati."/".$giornata_controlla."_".$otid."_".$otserie)) break;
		else $giornata_ultima = $num1;
	} # fine for $num1
	
	if (!$giornata or $giornata > $giornata_ultima) $giornata = "$giornata_ultima";
		
	$tab_formazioni = "";
		$tabellini = array();
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
	
	if ($giornata_ultima) $file_giornata = @file($percorso_cartella_dati."/giornata".$giornata."_".$storneo."_".$otserie);
	$num_linee_file_giornata = count($file_giornata);
	
	for($num1 = 0 ; $num1 < $num_linee_file_giornata; $num1++) {
	$linea = trim($file_giornata[$num1]);
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
	
		$tab_formazioni = "<table width='100%' cellpadding='0' cellspacing='1' bgcolor='$sfondo_tab'>
			<tr bgcolor='#808080'><td align='center'><font size='-2'><u>R</u></font></td><td align='center'><font size='-2'><u>Calciatore</u></font></td>
			<td align='center'><font size='-2'>Voto<br/>giornale</font></td><td align='center'>Bonus</td><td align='center'>Malus</td>
			<td align='center'><font size='-2'>Fanta<br/>voto</font></td></tr>";
			$formazione = "formazione_$outente";
			$formazione = $$formazione;
		$num_linee_formazione = count($formazione);
		for ($num2 = 0 ; $num2 < $num_linee_formazione; $num2++) {
		$riga_calciatore = explode(",", $formazione[$num2]);
		$nome_calciatore = stripslashes($riga_calciatore[1]);
		if ($num2 % 2) $colore="white"; else $colore=$colore_riga_alt;
		if(substr($nome_calciatore,0,3) == "<b>") {
		$nome_tmp = substr($nome_calciatore,3,strlen($nome_calciatore)-7);
		if (strlen($nome_tmp) > 20)
		$nome_calciatore = "<b>".substr($nome_tmp,0,20)."</b>";
		}
		else{
		if (strlen($nome_calciatore) > 20)
		$nome_calciatore = substr($nome_calciatore,0,20);
		}
		if (isset($riga_calciatore[4]) && $riga_calciatore[4] == 0) $riga_calciatore[4] = 's.v.';
		if ( !isset($riga_calciatore[3]) || (isset($riga_calciatore[3]) && $riga_calciatore[3] == 0)) $nome_calciatore = "<del><em><span style='color: grey'>$nome_calciatore</span></em></del>";
				$tab_formazioni .= "<tr bgcolor='$colore' height='21px'><td align='center' style='padding: 0px 1px 0px 1px'>$riga_calciatore[2]</td><td  style='padding: 0px 1px 0px 1px'><a href='stat_calciatore.php?num_calciatore=$riga_calciatore[0]&amp;escludi_controllo=$escludi_controllo' >$nome_calciatore</a></td>
				<td align='center'>$riga_calciatore[4]</td><td align='center' width='15%'>";
		if($riga_calciatore[5] > 0) $tab_formazioni .= "<div class='golsegnato'><div class='textalign-bottom-right'>$riga_calciatore[5]</div></div>";
				if($riga_calciatore[6] > 0) $tab_formazioni .= "<div class='assist'><div class='textalign-bottom-right'>$riga_calciatore[6]</div></div>";
				if($riga_calciatore[7] > 0) $tab_formazioni .= "<div class='rigoreparato'><div class='textalign-bottom-right'>$riga_calciatore[7]</div></div>";
				$tab_formazioni .= "<div style='clear:both'></div></td><td align='center' width='15%'>";
				if($riga_calciatore[8] == 1) $tab_formazioni .= "<div class='ammonizione'></div>";
		if($riga_calciatore[9] == 1) $tab_formazioni .= "<div class='espulsione'></div>";
		if($riga_calciatore[10] > 0) $tab_formazioni .= "<div class='rigoresbagliato'><div class='textalign-bottom-right'>$riga_calciatore[10]</div></div>";
				if($riga_calciatore[11] > 0) $tab_formazioni .= "<div class='autogol'><div class='textalign-bottom-right'>$riga_calciatore[11]</div></div>";
				if($riga_calciatore[12] > 0) $tab_formazioni .= "<div class='golsubito'><div class='textalign-bottom-right'>$riga_calciatore[12]</div></div>";
				$tab_formazioni .= "<div style='clear:both'></div></td><td align='center'>$riga_calciatore[3]</td></tr>";
				} # fine for $num2
			$tab_formazioni .= "</table>";
		$tabellini[$outente] = $tab_formazioni;
	
		} # fine for $num1
		//for ($num1 = $num_colonne ; $num1 < 2; $num1++) $tab_formazioni .= "<td>&nbsp;</td>";
	
	
		$tipo_campionato = "";
		$num_giornata = str_replace("giornata","",$giornata);
		if (substr($num_giornata,0,1) == 0) $num_giornata = substr($num_giornata,1);
		$num_campionati = count($campionato);
		reset($campionato);
		for($num1 = 0 ; $num1 < $num_campionati; $num1++) {
		$key_campionato = key($campionato);
		$giornate_campionato = explode("-",$key_campionato);
		if ($num_giornata <= $giornate_campionato[1] and $num_giornata >= $giornate_campionato[0]) {
		$num_giornata_campionato = $num_giornata - $giornate_campionato[0] + 1;
		$tipo_campionato = $campionato[$key_campionato];
		$g_inizio_campionato = $giornate_campionato[0];
		break;
		} # fine if ($num_giornata <= $giornate_campionato[1] and...
		next($campionato);
		} # fine for $num1
		if (!$tipo_campionato) $tipo_campionato = "N";
	
		if ($voti_esistenti == "SI") {
		$num_voti = count($voti);
		for ($num1 = 0 ; $num1 < $num_voti ; $num1++) {
		$dati_voti = explode("##@@&&", $voti[$num1]);
		settype($dati_voti[1],"double");
		$voto[$dati_voti[0]] = $dati_voti[1];
		} # fine for $num1
		}
		
		$mexemail .= "<table summary='guarda_giornata' bgcolor='$sfondo_tab' align='center' width='100%' cellpadding='10' cellspacing='0'>
		<caption>TABELLINI GIORNATA N.$num_giornata_campionato</caption><tr><td>";
		
		### Box Risultati Giornata corrente
		$mexemail .= "<br><div class='box_utente_header'>RISULTATI GIORNATA N.$num_giornata_campionato</div>
		<div class='box_utente_content'>";
		$mexemail .= "<table width='100%' cellspacing='1' cellpadding='0' border='0' bgcolor='$sfondo_tab'>";
		if($tipo_campionato == "S") {
			$partite = "";
			$marcotori = "";
		$num_scontri = count($scontri);
		for ($num1 = 0 ; $num1 < $num_scontri ; $num1++) {
		$dati_scontri = explode("##@@&&", $scontri[$num1]);
		$mexemail .= "<tr ";
		if($num1%2 != 0)$mexemail .="bgcolor='#E6E6E6'";else $mexemail .="bgcolor='#F3F3F3'";
		$mexemail .= "><td align='center'>".$nome_squadra_memo[$dati_scontri[0]]."</td><td align='center'> - </td><td align='center'>".$nome_squadra_memo[$dati_scontri[1]]."</td>
				<td align='center'>".($voto[$dati_scontri[0]]+$otvoti_bonus_in_casa)." - ".$voto[$dati_scontri[1]]."</td>
		<td align='center'>".$dati_scontri[2]." - ".$dati_scontri[3]."</td></tr>";
			} # fine for $num1
		}
		elseif($tipo_campionato != "N") {
		$num_punteggi = count($punteggi);
		for ($num1 = 0 ; $num1 < $num_punteggi ; $num1++) {
		$dati_punteggi = explode("##@@&&", $punteggi[$num1]);
		$mexemail .= "<tr ";
		if($num1%2 != 0)$mexemail .="bgcolor='#E6E6E6'";else $mexemail .="bgcolor='#F3F3F3'";
		$mexemail .= "><td align='center'>".$nome_squadra_memo[$dati_punteggi[0]]."</td><td align='center'>".$dati_punteggi[1]."</td></tr>";
		} # fine for $num1
		}
		$mexemail .= "</table></div></td></tr>";
		### Fine Box Risultati Giornata corrente
	
		### Box Classifica di giornata
		$file_classifica = file($percorso_cartella_dati."/classifica_".$storneo);
		$num_linee_file_classifica = count($file_classifica);
		$trovato_prossimo_turno = false;
		$num_linea = 0;
		$trovato_classifica = false;
		$num_linea = 0;
		while(!$trovato_punteggi && $num_linea < $num_linee_file_classifica) {
			$linea_file_classifica = trim(togli_acapo($file_classifica[$num_linea]));
			if ($linea_file_classifica == "#@& classifica #@&") {
				$mexemail .= "<tr><td><div class='box_utente_header' style='margin-top: 10px'>CLASSIFICA DOPO LA GIORNATA N.$num_giornata_campionato</div>
								<div class='box_utente_content'>";
				$mexemail .= "<table width='100%' cellspacing='1' cellpadding='0' border='0' bgcolor='$sfondo_tab'>";
				$trovato_classifica = true;
				$num_squadra = $num_linea+1;
				$linea_file_classifica = trim(togli_acapo($file_classifica[$num_squadra]));
				while($linea_file_classifica != "#@& fine classifica #@&" && ($num_squadra < $num_linea+$otpart+2 )) {
				$dati_squadra = explode("##@@&&", $linea_file_classifica);
				if($num_squadra == $num_linea+1){
				if($tipo_campionato == "S"){
				$mexemail .= "<tr bgcolor='#808080'><td align='center'>".$dati_squadra[0]."</td><td align='center'>"
				.$dati_squadra[1]."</td><td align='center'>".$dati_squadra[2]."</td><td align='center'>"
				.$dati_squadra[3]."</td><td align='center'>".$dati_squadra[4]."</td><td align='center'>"
											.$dati_squadra[5]."</td><td align='center'>".$dati_squadra[20]."</td><td align='center'>"
											.$dati_squadra[21]."</td>
											</tr>";
										}
				elseif($tipo_campionato == "P") {
											$mexemail .= "<tr bgcolor='#808080'><td align='center'>".$dati_squadra[0]."</td><td align='center'>"
											.$dati_squadra[1]."</td>
											</tr>";
										}
										else{
				$mexemail .= "<tr bgcolor='#808080'><td align='center'>".$dati_squadra[0]."</td><td align='center'>"
											.$dati_squadra[1]."</td>
											</tr>";
										}
									}
				else {
				if($tipo_campionato == "S") {
				$mexemail .= "<tr ";
				if($num_squadra%2 != 0) $mexemail .="bgcolor='#E6E6E6'"; else $mexemail .="bgcolor='#F3F3F3'";
				$mexemail .= "><td align='center'>".$nome_squadra_memo[$dati_squadra[0]]."</td><td align='center'>"
				.$dati_squadra[1]."</td><td align='center'>".$dati_squadra[2]."</td><td align='center'>"
											.$dati_squadra[3]."</td><td align='center'>".$dati_squadra[4]."</td><td align='center'>"
											.$dati_squadra[5]."</td><td align='center'>".$dati_squadra[20]."</td><td align='center'>"
											.$dati_squadra[21]."</td>
											</tr>";
				}
				elseif($tipo_campionato == "P") {
											$mexemail .= "<tr ";
				if($num_squadra%2 != 0) $mexemail .="bgcolor='#E6E6E6'"; else $mexemail .="bgcolor='#F3F3F3'";
											$mexemail .= "><td align='center'>".$nome_squadra_memo[$dati_squadra[0]]."</td><td align='center'>"
											.$dati_squadra[1]."</td>
											</tr>";
										}
				else {
				$mexemail .= "<tr ";
				if($num_squadra%2 != 0) $mexemail .="bgcolor='#E6E6E6'"; else $mexemail .="bgcolor='#F3F3F3'";
											$mexemail .= "><td align='center'>".$nome_squadra_memo[$dati_squadra[0]]."</td><td align='center'>"
											.$dati_squadra[1]."</td>
											</tr>";
										}
									}
									$num_squadra++;
											$linea_file_classifica = trim(togli_acapo($file_classifica[$num_squadra]));
								}
								$mexemail .= "</table></div></td></tr>";
				}
				$num_linea++;
				}
				### Fine Box Classifica di giornata
		
		### Tabellini Giornata corrente
		$mexemail .= "<tr><td><table align='center' width='100%' bgcolor='$sfondo_tab' border='0' cellpadding='10' cellspacing='0'>";
		
			if($tipo_campionato == "S") {
		$partite = "";
				$marcotori = "";
		$num_scontri = count($scontri);
		for ($num1 = 0 ; $num1 < $num_scontri ; $num1++) {
		$dati_scontri = explode("##@@&&", $scontri[$num1]);
	
		$utente0 = $dati_scontri[0];
		$utente1 = $dati_scontri[1];
		$soprannome_squadra = $nome_squadra_memo[$utente0];
		$vedivoti=htmlentities($nome_squadra_memo[$utente0],ENT_QUOTES);
		$mexemail .= "<tr><td align='left' valign='top'><a name='$vedivoti'></a>
		<div class='box_utente_header'>$nome_squadra_memo[$utente0]</div>
						<div class='box_utente_content'>";
		$mexemail .= $tabellini[$utente0];
					$mexemail .= "<div style='float:left;padding-top:7px'><a href='#top'><img src='immagini/button_top.png'/></a></div>
		<div class='box-shadow-noround' style='float:right;font-size:25px; font-weight: bolder'><div class='button_circle' style='float:left;margin-right:5px'>+$otvoti_bonus_in_casa</div>".($voto[$utente0]+$otvoti_bonus_in_casa)."</div>
		</div></td>";
	
		$vedivoti=htmlentities($nome_squadra_memo[$utente1],ENT_QUOTES);
		$mexemail .= "<td align='left' valign='top'><a name='$vedivoti'></a>
		<div class='box_utente_header'>$nome_squadra_memo[$utente1]</div>
						<div class='box_utente_content'>";
		$mexemail .= $tabellini[$utente1];
		$mexemail .= "<div style='float:left;padding-top:7px'><a href='#top'><img src='immagini/button_top.png'/></a></div>
		<div class='box-shadow-noround' style='float:right;font-size:25px; font-weight: bolder'>$voto[$utente1]</div>
						</div></td></tr>";
				} # fine for $num1
			}
			elseif($tipo_campionato != "N") {
				$mexemail .= "<tr>";
				$num_squadre = count($tabellini);
		for ($num1 = 0 ; $num1 < $num_squadre ; $num1++) {
		if ($num_colonne%2 == 0) {
		$mexemail .= "</tr><tr>";
		$num_colonne = 0;
		} # fine if ($num_colonne >= 2)
		$utente = $nome_posizione[$num1+1];
				$soprannome_squadra = $nome_squadra_memo[$utente];
		$vedivoti=htmlentities($nome_squadra_memo[$utente],ENT_QUOTES);
		$mexemail .= "<td align='left' valign='top'><a name='$vedivoti'></a>
		<div class='box_utente_header'>$nome_squadra_memo[$utente]</div>
				<div class='box_utente_content'>";
					$mexemail .= $tabellini[$utente];
		$mexemail .= "<div style='float:left;padding-top:7px'><a href='#top'><img src='immagini/button_top.png'/></a></div>
						<div class='box-shadow-noround' style='float:right'>$voto[$utente]</div>
						</div></td>";
					$num_colonne++;
				} # fine for $num1
				$mexemail .= "</tr>";
		}
	
		$mexemail .= "</table></td></tr>";
	
##################################
# Invio email


    $oggetto = "Invio risultati\r\n";
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

div.golsegnato{
	position: relative;
	float:left;
	background:url(".$site_root."/immagini/golsegnato.png) no-repeat;
	width:24px;
	height:21px;
	color: red;
	font-size: 11px;
	font-weight: bolder;
}

div.assist{
	position: relative;
	float:left;
	background:url(".$site_root."/immagini/assist.png) no-repeat;
	width:27px;
	height:21px;
	color: red;
	font-size: 11px;
	font-weight: bolder;
}

div.rigoreparato{
	position: relative;
	float:left;
	background:url(".$site_root."/immagini/rigoreparato.png) no-repeat;
	width:26px;
	height:21px;
	color: red;
	font-size: 11px;
	font-weight: bold;
}

div.ammonizione{
	position: relative;
	float:left;
	background:url(".$site_root."/immagini/ammonizione.png) no-repeat;
	width:20px;
	height:21px;
	color: red;
	font-size: 11px;
	font-weight: bold;
}

div.espulsione{
	position: relative;
	float:left;
	background:url(".$site_root."/immagini/espulsione.png) no-repeat;
	width:20px;
	height:21px;
	color: red;
	font-size: 11px;
	font-weight: bold;
}

div.rigoresbagliato{
	position: relative;
	float:left;
	background:url(".$site_root."/immagini/rigoresbagliato.png) no-repeat;
	width:26px;
	height:21px;
	color: red;
	font-size: 11px;
	font-weight: bold;
}

div.autogol{
	position: relative;
	float:left;
	background:url(".$site_root."/immagini/autogol.png) no-repeat;
	width:24px;
	height:21px;
	color: red;
	font-size: 11px;
	font-weight: bold;
}
div.golsubito{
	position: relative;
	float:left;
	background:url(".$site_root."/immagini/golsubito.png) no-repeat;
	width:24px;
	height:21px;
	color: red;
	font-size: 11px;
	font-weight: bold;
}

div.textalign-bottom-right{
	position: absolute;
	bottom: 0;
	margin: 0;
	right: 0;
	padding: 0;
	padding-right: 2px;
}

div.button_circle {
	position: relative;
	background:url(".$site_root."/immagini/button_circle.png) no-repeat;
	width:30px;
	height:30px;
	color: red;
	font-size: 16px;
	font-weight: bolder;
	text-align: center;
	line-height: 30px;
	vertical-align: middle;;
}
</style>";

    $mexemail = "$mail_css $commento<hr>".trim(stripslashes("$mexemail"));

    ########################
        $destinatari = "";
        $file = file("./dati/utenti_".$storneo.".php");
        $linee = count($file);
        for($linea = 1; $linea < $linee; $linea++) {       
        	@list($outente, $opassword, $opermessi, $oemail, $ourl, $osquadra, $ocitta, $ocrediti, $ovariazioni, $ocambi, $oreg) = explode("<del>", $file[$linea]);      
        	$destinatari = "$outente <$oemail>,";      
        	$destinatari .= "\r\n";      
        	$destinatariHtml .= "$outente,";

       	 	if ($gestione_email == "mail_OK") {
				$intestazioni  = "MIME-Version: 1.0\r\n";
				$intestazioni .= "Content-type: text/html; charset=iso-8859-1\r\n";
				$intestazioni .= "From: $admin_nome <$email_mittente>\r\n" ;
				$intestazioni .= "X-Mailer: PHP v".phpversion()."\r\n";
				$intestazioni .= "bcc: ".$destinatari;
                if(@mail("Utenti $titolo_sito <$email_mittente>",$oggetto,"$mexemail\r\n",$intestazioni)) {
                    $azione .= "La mail a ".$outente." è stata inoltrata con successo.<br>";
                    sleep(1);        
                }
                else 
                    $azione .= "Si sono verificati dei problemi nell'invio della mail a ".$outente.".<br>";      
         	}
        }
        if ($gestione_email == "mail_anteprima") $azione = "<center><h3>Invio resoconto ultima giornata</h3><form method=\"post\" action=\"a_invia_risultati.php\">
        <input type=\"hidden\" name=\"commento\" value=\"$commento\">
	    <input type=\"hidden\" name=\"storneo\" value=\"$storneo\">
        <input type=\"hidden\" name=\"gestione_email\" value=\"mail_OK\">
        <input type=\"submit\" name=\"invia\" value=\"Invia messaggio\">
        </form></center><hr><b>DESTINATARI</b><br>$destinatariHtml<hr><hr><b>MESSAGGIO</b><br>$mexemail";

        elseif ($azione == "") $azione = "Errore, Invio email non riuscito.";
    }
    # fine gestione invio emails
    ###############################

		else $azione = "<h3>Invio resoconto ultima giornata</h3>
      $frase<br>
    <form method=\"post\" action=\"a_invia_risultati.php\">
    <input type=\"hidden\" name=\"gestione_email\" value=\"mail_anteprima\">
    <input type=\"hidden\" name=\"storneo\" value=\"mail_anteprima\">
    $vedi_tornei_attivi	<br/><br/>
    Inserisci un eventuale commento all'invio dei voti<br>
	<textarea name=\"commento\" rows=8 cols=60 wrap=\"virtual\">".$otdenom." - Invio risultati ufficiali della ".intval($giornata_ultima)."° giornata di fantacampionato.</textarea><br>
    <input type=\"submit\" value=\"Anteprima invio\"></form>";

echo "$azione</td></tr></table>";
echo "<script>
function cambiaCommento(torneo){
	textarea = document.getElementsByTagName(\"textarea\").item(0);
	textarea.value = torneo.innerHTML+\" - Invio risultati ufficiali della \"+torneo.id+\"° giornata di fantacampionato.\";
}
</script>";

require ("./footer.php");

	}
?>
