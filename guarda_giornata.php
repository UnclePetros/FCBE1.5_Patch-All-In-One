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
$escludi_controllo = $_POST['escludi_controllo'];

if ($escludi_controllo != "SI") {
	require_once ("./controlla_pass.php");
	require_once("./inc/funzioni.php");
	include("./header.php");
	require ("menu.php");
}
else {
	require ("./dati/dati_gen.php");
	require_once("./inc/funzioni.php");
	include("./header.php");
	$ca=explode(';',$_POST['itorneo']);
	$_SESSION['torneo']=$ca[0];
	$_SESSION['serie'] = "0";
	$range_campionato = $_POST['range'];
	$campionato[$range_campionato] = $_POST['otipo'];
	$otdenom=$ca[1];
	$giornata=$_POST['giornata'];
}

	for($num1 = "01" ; $num1 < 40 ; $num1++) {
		if (strlen($num1) == 1) $num1 = "0".$num1;
	$giornata_controlla = "giornata$num1";
		if (!@is_file($percorso_cartella_dati."/".$giornata_controlla."_".$_SESSION['torneo']."_".$_SESSION['serie'])) break;
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

echo "<table summary='guarda_giornata' bgcolor='$sfondo_tab' align='center' width='100%' cellpadding='10' cellspacing='0'>
<caption>TABELLINI GIORNATA N.$num_giornata_campionato</caption><tr><td>";

### Box Risultati Giornata corrente
echo "<br><div class='box_utente_header'>RISULTATI GIORNATA N.$num_giornata_campionato</div>
<div class='box_utente_content'>";
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
	echo "</table></div></td></tr>";
### Fine Box Risultati Giornata corrente

### Tabellini Giornata corrente
echo "<tr><td><table align='center' width='100%' bgcolor='$sfondo_tab' border='0' cellpadding='10' cellspacing='0'>";

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
			echo "<tr><td align='left' valign='top'><a name='$vedivoti'></a>
				<div class='box_utente_header'>$nome_squadra_memo[$utente0]</div>
				<div class='box_utente_content'>";
			echo $tabellini[$utente0];
			echo "<div style='float:left'><a href='#top'><img src='immagini/button_top.png'/></a></div>
				<div class='box-shadow-noround' style='float:right;font-size:25px; font-weight: bolder'><div class='button_circle' style='float:left;margin-right:5px'>+$otvoti_bonus_in_casa</div>".($voto[$utente0]+$otvoti_bonus_in_casa)."</div>
				</div></td>";
			
			$vedivoti=htmlentities($nome_squadra_memo[$utente1],ENT_QUOTES);
			echo "<td align='left' valign='top'><a name='$vedivoti'></a>
				<div class='box_utente_header'>$nome_squadra_memo[$utente1]</div>
				<div class='box_utente_content'>";
			echo $tabellini[$utente1];
			echo "<div style='float:left'><a href='#top'><img src='immagini/button_top.png'/></a></div>
				<div class='box-shadow-noround' style='float:right;font-size:25px; font-weight: bolder'>$voto[$utente1]</div>
				</div></td></tr>";
		} # fine for $num1
	}
	elseif($tipo_campionato != "N") {
		echo "<tr>";
		$num_squadre = count($tabellini);
		for ($num1 = 0 ; $num1 < $num_squadre ; $num1++) {
			if ($num_colonne%2 == 0) {
				echo "</tr><tr>";
				$num_colonne = 0;
			} # fine if ($num_colonne >= 2)
			$utente = $nome_posizione[$num1+1];
			$soprannome_squadra = $nome_squadra_memo[$utente];
			$vedivoti=htmlentities($nome_squadra_memo[$utente],ENT_QUOTES);
			echo "<td align='left' valign='top'><a name='$vedivoti'></a>
				<div class='box_utente_header'>$nome_squadra_memo[$utente]</div>
				<div class='box_utente_content'>";
			echo $tabellini[$utente];
			echo "<div style='float:left'><a href='#top'><img src='immagini/button_top.png'/></a></div>
				<div class='box-shadow-noround' style='float:right;font-size:25px; font-weight: bolder'>$voto[$utente]</div>
				</div></td>";
			$num_colonne++;
		} # fine for $num1
		echo "</tr>";
	}
 
echo "</table></td></tr>";
### Fine Tabellini Giornata corrente
	
include("./footer.php");
?>
